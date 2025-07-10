<?php

namespace App\Http\Controllers;

use App\Models\Bonus;
use App\Models\MotorbikeRental;
use App\Models\Motorcycle;
use App\Models\Transaction;
use App\Services\MidtransStatusSyncService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemilikController extends Controller
{
    public function index()
    {
        $motorcycles = Motorcycle::where('owner_id', auth()->id())->paginate(3);
        $totalMotor = Motorcycle::where('owner_id', auth()->id())->count();
        $motorcycleIds = $motorcycles->pluck('id');

        $recentTransactions = Transaction::with(['motorcycle', 'motorcycle.owner'])
            ->whereIn('motorcycle_id', $motorcycleIds)
            ->latest()
            ->orderByRaw("FIELD(payment_status, 'pending', 'success') DESC")
            ->take(3)
            ->get();

        $allTransactions = Transaction::with(['motorcycle', 'motorcycle.owner'])
            ->whereIn('motorcycle_id', $motorcycleIds)
            ->where('payment_status', 'success')
            ->get();

        $activeOrders = $allTransactions->filter(function ($transaction) {
            return $transaction->rental_status === 'on_going';
        })->count();

        $totalIncome = $allTransactions
            ->filter(function ($transaction) {
                return $transaction->rental_status === 'finished';
            })
            ->sum(function ($transaction) {
                return $transaction->total_amount;
            });

        return view('pages.pemilik.dashboard', compact('motorcycles', 'recentTransactions', 'activeOrders', 'totalIncome', 'totalMotor'));
    }

    public function showDaftarMotor()
    {
        $motorcycles = Motorcycle::where('owner_id', auth()->id())->paginate(5);
        $totalMotor = Motorcycle::where('owner_id', auth()->id())->count();
        $user = auth()->user();
        $isApproved = $user && $user->is_approved;

        return view('pages.pemilik.daftar-motor.showDaftarMotor', compact('motorcycles', 'totalMotor', 'isApproved'));
    }

    public function showPesanan()
    {
        $ownerId = auth()->id();
        $transactions = Transaction::with(['motorcycle', 'motorcycle.owner'])
            ->whereHas('motorcycle', function ($query) use ($ownerId) {
                $query->where('owner_id', $ownerId);
            })
            ->orderByRaw("FIELD(payment_status, 'pending', 'success') DESC")
            ->orderBy('id', 'desc')
            ->latest()
            ->paginate(5);

        // Auto-sync SEMUA transaksi pending untuk real-time update
        $syncService = new MidtransStatusSyncService;
        $syncCount = 0;

        foreach ($transactions as $transaction) {
            if (in_array($transaction->payment_status, ['pending']) && $transaction->created_at->diffInDays(now()) <= 7) {
                $oldStatus = $transaction->payment_status;
                $syncService->syncPaymentStatus($transaction);
                $transaction->refresh();

                if ($oldStatus !== $transaction->payment_status) {
                    $syncCount++;
                }
            }
        }

        if ($syncCount > 0) {
            session()->flash('success', "✅ {$syncCount} status pembayaran berhasil diperbarui!");
        }

        // Debug info transaksi per motor
        $motorcycleGroups = $transactions->groupBy('motorcycle_id');
        \Log::info('Pesanan page loaded', [
            'owner_id' => $ownerId,
            'total_transactions' => $transactions->count(),
            'unique_motorcycles' => $motorcycleGroups->count(),
            'motorcycle_groups' => $motorcycleGroups->map(function ($group, $motorcycleId) {
                return [
                    'motorcycle_id' => $motorcycleId,
                    'motorcycle_name' => $group->first()->motorcycle->name ?? 'Unknown',
                    'transaction_count' => $group->count(),
                    'transaction_ids' => $group->pluck('id')->toArray(),
                ];
            }),
        ]);

        return view('pages.pemilik.pesanan.showPesanan', compact('transactions'));
    }

    public function createMotor()
    {
        // Cek apakah user sudah memiliki motor atau rental yang ada
        $existingMotorcycles = Motorcycle::where('owner_id', auth()->id())->with('motorbikeRental.bonuses')->get();
        $hasExistingRental = $existingMotorcycles->isNotEmpty();

        $existingRental = null;
        if ($hasExistingRental) {
            $existingRental = $existingMotorcycles->first()->motorbikeRental;
        }

        return view('pages.pemilik.daftar-motor.createMotor', compact('hasExistingRental', 'existingRental'));
    }

    public function returnMotor(Transaction $transaction)
    {
        // Pastikan motor milik user yang login
        if ($transaction->motorcycle->owner_id !== auth()->id()) {
            \Log::warning('Unauthorized return attempt', [
                'user_id' => auth()->id(),
                'transaction_id' => $transaction->id,
                'motorcycle_owner' => $transaction->motorcycle->owner_id,
            ]);

            return redirect()->route('pemilik.pesanan')->with('error', 'Anda tidak memiliki akses untuk motor ini.');
        }

        // Sinkronisasi status pembayaran dengan Midtrans
        $syncService = new MidtransStatusSyncService;
        $transaction = $syncService->syncPaymentStatus($transaction);

        \Log::info('Return motor process started', [
            'transaction_id' => $transaction->id,
            'motorcycle_id' => $transaction->motorcycle->id,
            'motorcycle_name' => $transaction->motorcycle->name,
            'current_rental_status' => $transaction->rental_status ?? 'null',
            'payment_status' => $transaction->payment_status,
            'customer_name' => $transaction->name,
        ]);

        // Validasi kondisi return cek rental_status transaksi individu
        // rental_status belum finished dan payment_status success
        if (
            $transaction->motorcycle &&
            in_array($transaction->rental_status, ['on_going', null]) &&
            $transaction->rental_status !== 'finished' &&
            strtoupper($transaction->payment_status) === 'SUCCESS'
        ) {
            $motorcycle = $transaction->motorcycle;
            $oldRentalStatus = $transaction->rental_status;
            $oldAvailableStock = $motorcycle->available_stock;

            // Update rental status transaksi individual menjadi finished
            $transaction->rental_status = 'finished';
            $transaction->save();

            $stockIncreased = $motorcycle->increaseStock(1);

            \Log::info('Motor returned successfully', [
                'transaction_id' => $transaction->id,
                'motorcycle_id' => $motorcycle->id,
                'motorcycle_name' => $motorcycle->name,
                'rental_status_changed' => "{$oldRentalStatus} -> {$transaction->rental_status}",
                'stock_changed' => "{$oldAvailableStock} -> {$motorcycle->available_stock}",
                'stock_increase_success' => $stockIncreased,
                'customer_name' => $transaction->name,
            ]);

            return redirect()->route('pemilik.pesanan')->with('success',
                "✅ Motor {$motorcycle->name} telah dikembalikan oleh {$transaction->name}. Stok tersedia: {$motorcycle->available_stock}/{$motorcycle->stock}");
        } else {
            $reason = 'Unknown';
            if (! $transaction->motorcycle) {
                $reason = 'Motor tidak ditemukan';
            } elseif ($transaction->rental_status === 'finished') {
                $reason = 'Motor sudah dikembalikan sebelumnya';
            } elseif (! in_array($transaction->rental_status, ['on_going', null])) {
                $reason = 'Status rental tidak valid untuk pengembalian';
            } elseif (strtoupper($transaction->payment_status) !== 'SUCCESS') {
                $reason = 'Pembayaran belum berhasil';
            }

            \Log::warning('Return motor failed - conditions not met', [
                'transaction_id' => $transaction->id,
                'motorcycle_exists' => $transaction->motorcycle ? 'yes' : 'no',
                'rental_status' => $transaction->rental_status ?? 'null',
                'payment_status' => $transaction->payment_status,
                'reason' => $reason,
            ]);

            return redirect()->route('pemilik.pesanan')->with('error',
                "Motor tidak dapat dikembalikan. Alasan: {$reason}. Status rental: ".
                ($transaction->rental_status ?? 'unknown').', Payment: '.$transaction->payment_status);
        }
    }

    public function storeMotor(Request $request)
    {
        $user = auth()->user();
        try {
            $useExistingRental = $request->has('use_existing_rental') && $request->use_existing_rental == '1';

            if ($useExistingRental && $request->has('existing_rental_id')) {
                $rental = MotorbikeRental::findOrFail($request->existing_rental_id);
                $userMotorcycles = Motorcycle::where('owner_id', auth()->id())
                    ->where('motorbike_rental_id', $rental->id)
                    ->exists();
                if (! $userMotorcycles) {
                    throw new \Exception('Anda tidak memiliki akses ke rental ini.');
                }
            } else {
                $validated = $request->validate([
                    'thumbnail' => 'required|image',
                    'name' => 'required|string',
                    'slug' => 'required|string|unique:motorbike_rentals,slug',
                    'city_id' => 'required|exists:cities,id',
                    'description' => 'required|string',
                    'address' => 'required|string',
                ]);
                $thumbnailPath = $request->file('thumbnail')->store('motorbike_rental', 'public');
                $rental = MotorbikeRental::create([
                    'name' => $request->name,
                    'slug' => $request->slug,
                    'thumbnail' => $thumbnailPath,
                    'city_id' => $request->city_id,
                    'description' => $request->description,
                    'address' => $request->address,
                    'contact' => Auth::user()->phone ?? '',
                ]);
            }

            // Simpan bonus
            if ($request->has('bonuses')) {
                foreach ($request->bonuses as $bonus) {
                    if ((isset($bonus['image']) && $bonus['image']) || ($bonus['name'] ?? '') !== '' || ($bonus['description'] ?? '') !== '') {
                        $bonusImage = null;
                        if (isset($bonus['image']) && $bonus['image']) {
                            $bonusImage = $bonus['image']->store('bonuses', 'public');
                        }
                        Bonus::create([
                            'motorbike_rental_id' => $rental->id,
                            'image' => $bonusImage,
                            'name' => $bonus['name'] ?? '',
                            'description' => $bonus['description'] ?? '',
                        ]);
                    }
                }
            }

            // Validasi checklist fisik & video (global)
            $checklistFisik = $request->input('checklist_fisik');
            $videoFisik = $request->file('video_fisik');
            if (! is_array($checklistFisik) || ! $videoFisik) {
                return back()->withInput()->with('error', 'Checklist fisik dan video wajib diisi.');
            }

            // Simpan motor
            $firstMotorcycle = null;
            if ($request->has('motorcycles') && is_array($request->motorcycles) && count($request->motorcycles) > 0) {
                foreach ($request->motorcycles as $idx => $motor) {
                    $stnkImages = [];
                    if (isset($motor['stnk_images'])) {
                        foreach ($motor['stnk_images'] as $img) {
                            $stnkImages[] = $img->store('stnk', 'public');
                        }
                    }
                    $motorcycle = Motorcycle::create([
                        'owner_id' => auth()->id(),
                        'motorbike_rental_id' => $rental->id,
                        'category_id' => $motor['category_id'],
                        'name' => $motor['name'],
                        'vehicle_number_plate' => $motor['vehicle_number_plate'],
                        'stnk_images' => $stnkImages,
                        'price_per_day' => $motor['price_per_day'],
                        'stock' => 1,
                        'available_stock' => 1,
                        'has_gps' => $motor['has_gps'] ?? false,
                        'start_rent_hour' => $motor['start_rent_hour'] ?? '08:00',
                        'end_rent_hour' => $motor['end_rent_hour'] ?? '20:00',
                    ]);
                    if ($firstMotorcycle === null) {
                        $firstMotorcycle = $motorcycle;
                    }
                    // Simpan gambar motor
                    if (isset($motor['images'])) {
                        foreach ($motor['images'] as $img) {
                            $imgPath = $img->store('motorcycles', 'public');
                            \App\Models\MotorcycleImage::create([
                                'motorcycle_id' => $motorcycle->id,
                                'image' => $imgPath,
                            ]);
                        }
                    }
                    // Simpan checklist/video per-motor jika multi-motor dan ada input khusus
                    $videoFile = $request->file("motorcycles.$idx.video_fisik");
                    $checklist = $request->input("motorcycles.$idx.checklist_fisik");
                    if ($videoFile && is_array($checklist)) {
                        $videoPath = $videoFile->store('motorcycle_physical_checks', 'public');
                        \App\Models\MotorcyclePhysicalCheck::create([
                            'motorcycle_id' => $motorcycle->id,
                            'motorbike_rental_id' => $rental->id,
                            'checklist' => json_encode($checklist),
                            'video_path' => $videoPath,
                        ]);
                    }
                }
            }
            // Simpan checklist/video dari tab global ke motor pertama jika motor pertama belum punya physicalCheck
            if ($firstMotorcycle && ! $firstMotorcycle->physicalCheck) {
                $videoPath = $videoFisik->store('motorcycle_physical_checks', 'public');
                \App\Models\MotorcyclePhysicalCheck::create([
                    'motorcycle_id' => $firstMotorcycle->id,
                    'motorbike_rental_id' => $rental->id,
                    'checklist' => json_encode($checklistFisik),
                    'video_path' => $videoPath,
                ]);
            }

            $message = $useExistingRental
                ? 'Motor berhasil ditambahkan ke rental yang sudah ada!'
                : 'Data rental dan motor berhasil disimpan!';

            return redirect()->route('pemilik.daftar-motor')->with('success', $message);
        } catch (\Throwable $e) {
            $fullError = $e->getMessage().' | FILE: '.$e->getFile().' | LINE: '.$e->getLine().' | TRACE: '.$e->getTraceAsString();

            return back()->withInput()->with('error', $fullError);
        }
    }

    public function editMotor(Motorcycle $motorcycle)
    {
        if ($motorcycle->owner_id !== auth()->id()) {
            abort(403);
        }
        $motorbikeRental = $motorcycle->motorbikeRental()->with('bonuses')->first();

        // Pastikan checklistLama selalu array, handle null, string kosong, string JSON, array
        $checklistLama = [];
        if ($motorcycle->physicalCheck && isset($motorcycle->physicalCheck->checklist)) {
            $raw = $motorcycle->physicalCheck->checklist;
            if (is_array($raw)) {
                $checklistLama = $raw;
            } elseif (is_string($raw) && strlen($raw) > 0) {
                $decoded = json_decode($raw, true);
                $checklistLama = is_array($decoded) ? $decoded : [];
            } else {
                $checklistLama = [];
            }
        }

        return view('pages.pemilik.daftar-motor.editMotor', compact('motorcycle', 'motorbikeRental', 'checklistLama'));
    }

    public function updateMotor(Request $request, Motorcycle $motorcycle)
    {
        if ($motorcycle->owner_id !== auth()->id()) {
            abort(403);
        }

        $motorbikeRental = $motorcycle->motorbikeRental;
        if ($motorbikeRental) {
            $validated = $request->validate([
                'rental_name' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:motorbike_rentals,slug,'.$motorbikeRental->id,
                'city_id' => 'required|exists:cities,id',
                'description' => 'required|string',
                'address' => 'required|string',
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:100000',
            ]);

            $motorbikeRentalData = [
                'name' => $request->input('rental_name'),
                'slug' => $request->input('slug'),
                'city_id' => $request->input('city_id'),
                'description' => $request->input('description'),
                'address' => $request->input('address'),
            ];
            // Update thumbnail jika ada file baru
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('motorbike_rental', 'public');
                $motorbikeRentalData['thumbnail'] = $thumbnailPath;
            }
            $motorbikeRental->update($motorbikeRentalData);
            // Update bonus: update/tambah/hapus sesuai input
            $inputBonuses = $request->bonuses ?? [];
            $oldBonuses = $motorbikeRental->bonuses()->get();

            $bonusIdx = 0;
            foreach ($inputBonuses as $idx => $bonus) {
                // Hanya update/tambah jika ada salah satu field terisi
                if ((isset($bonus['image']) && $bonus['image']) || ($bonus['name'] ?? '') !== '' || ($bonus['description'] ?? '') !== '') {
                    $bonusImage = null;
                    if (isset($bonus['image']) && $bonus['image']) {
                        $bonusImage = $bonus['image']->store('bonuses', 'public');
                    } elseif (isset($oldBonuses[$bonusIdx]) && $oldBonuses[$bonusIdx]->image) {
                        $bonusImage = $oldBonuses[$bonusIdx]->image;
                    }

                    if (isset($oldBonuses[$bonusIdx])) {
                        // Update bonus lama
                        $oldBonuses[$bonusIdx]->update([
                            'image' => $bonusImage,
                            'name' => $bonus['name'] ?? '',
                            'description' => $bonus['description'] ?? '',
                        ]);
                    } else {
                        // Tambah bonus baru
                        $motorbikeRental->bonuses()->create([
                            'image' => $bonusImage,
                            'name' => $bonus['name'] ?? '',
                            'description' => $bonus['description'] ?? '',
                        ]);
                    }
                    $bonusIdx++;
                }
            }
            // Hapus bonus lama yang tidak ada di input (hanya jika input kosong)
            for ($i = $bonusIdx; $i < count($oldBonuses); $i++) {
                $oldBonuses[$i]->delete();
            }
        }

        $motorcycleValidated = $request->validate([
            'motorcycle_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'vehicle_number_plate' => 'required|string|max:255',
            'price_per_day' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:1',
            'available_stock' => 'required|integer|in:0,1',
            'stnk_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:100000',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:100000',
            // --- Tambahan validasi checklist fisik & video ---
            'checklist_fisik' => 'required|array|min:1',
            'checklist_fisik.*' => 'string',
            'video_fisik' => 'required|file|mimetypes:video/mp4,video/3gp,video/quicktime|max:102400', // 100MB
        ]);

        // Update Motorcycle (Motor) - menghapus bidang status
        $motorcycle->update([
            'name' => $request->input('motorcycle_name'),
            'vehicle_number_plate' => $request->input('vehicle_number_plate'),
            'price_per_day' => $request->input('price_per_day'),
            'stock' => $request->input('stock', $motorcycle->stock),
            'available_stock' => $request->input('available_stock', $motorcycle->available_stock),
            'has_gps' => $request->has('has_gps'),
            'start_rent_hour' => $request->input('start_rent_hour', $motorcycle->start_rent_hour ?? '08:00'),
            'end_rent_hour' => $request->input('end_rent_hour', $motorcycle->end_rent_hour ?? '20:00'),
        ]);

        // Update gambar STNK jika ada
        if ($request->hasFile('stnk_images')) {
            $stnkImages = [];
            foreach ($request->file('stnk_images') as $img) {
                $stnkImages[] = $img->store('stnk', 'public');
            }
            $motorcycle->stnk_images = $stnkImages;
            $motorcycle->save();
        }
        // Update gambar motor jika ada
        if ($request->hasFile('images')) {
            foreach ($motorcycle->images as $img) {
                $img->delete();
            }
            foreach ($request->file('images') as $img) {
                $imgPath = $img->store('motorcycles', 'public');
                \App\Models\MotorcycleImage::create([
                    'motorcycle_id' => $motorcycle->id,
                    'image' => $imgPath,
                ]);
            }
        }

        // Tambah motorcycles baru jika ada
        if ($request->has('motorcycles')) {
            foreach ($request->motorcycles as $motor) {
                $stnkImages = [];
                if (isset($motor['stnk_images'])) {
                    foreach ($motor['stnk_images'] as $img) {
                        $stnkImages[] = $img->store('stnk', 'public');
                    }
                }
                // Simpan data motor baru
                $newMotorcycle = Motorcycle::create([
                    'owner_id' => auth()->id(),
                    'motorbike_rental_id' => $motorbikeRental->id,
                    'category_id' => $motor['category_id'], // <-- fix: tambahkan category_id
                    'name' => $motor['name'],
                    'vehicle_number_plate' => $motor['vehicle_number_plate'],
                    'stnk' => $motor['stnk'],
                    'stnk_images' => $stnkImages,
                    'price_per_day' => $motor['price_per_day'],
                    'stock' => $motor['stock'] ?? 1,
                    'available_stock' => $motor['available_stock'] ?? $motor['stock'] ?? 1,
                    'has_gps' => isset($motor['has_gps']) ? true : false,
                ]);
                // Simpan gambar motor ke motorcycle_images
                if (isset($motor['images'])) {
                    foreach ($motor['images'] as $img) {
                        $imgPath = $img->store('motorcycles', 'public');
                        \App\Models\MotorcycleImage::create([
                            'motorcycle_id' => $newMotorcycle->id,
                            'image' => $imgPath,
                        ]);
                    }
                }
            }
        }

        // --- Tambahan: Simpan/update checklist fisik & video ---
        $videoFile = $request->file('video_fisik');
        $checklist = $request->input('checklist_fisik');
        if ($videoFile && $checklist) {
            $videoPath = $videoFile->store('motorcycle_physical_checks', 'public');
            $check = $motorcycle->physicalCheck;
            if ($check) {
                $check->update([
                    'checklist' => $checklist,
                    'video_path' => $videoPath,
                ]);
            } else {
                \App\Models\MotorcyclePhysicalCheck::create([
                    'motorcycle_id' => $motorcycle->id,
                    'motorbike_rental_id' => $motorcycle->motorbike_rental_id,
                    'checklist' => $checklist,
                    'video_path' => $videoPath,
                ]);
            }
        }

        return redirect()->route('pemilik.daftar-motor')->with('success', 'Data motor & rental berhasil diupdate!');
    }

    public function destroyRental($motorbike_rental_id)
    {
        $rental = MotorbikeRental::withTrashed()->with(['motorcycles.images' => function ($q) {
            $q->withTrashed();
        }, 'bonuses' => function ($q) {
            $q->withTrashed();
        }])->findOrFail($motorbike_rental_id);

        foreach ($rental->motorcycles as $motorcycle) {
            foreach ($motorcycle->images as $img) {
                $img->forceDelete();
            }
            $motorcycle->forceDelete();
        }
        foreach ($rental->bonuses as $bonus) {
            $bonus->forceDelete();
        }
        $rental->forceDelete();

        return redirect()->route('pemilik.daftar-motor')->with('success', 'Rental dan semua data terkait berhasil dihapus permanen!');
    }

    // Sinkronisasi status pembayaran manual dari Midtrans
    public function syncPaymentStatus(Transaction $transaction)
    {
        $syncService = new MidtransStatusSyncService;
        $updated = $syncService->syncPaymentStatus($transaction);

        if ($updated->payment_status !== $transaction->payment_status) {
            return redirect()->route('pemilik.pesanan')
                ->with('success', "Status pembayaran berhasil diperbarui menjadi: {$updated->payment_status}");
        }

        return redirect()->route('pemilik.pesanan')
            ->with('info', 'Status pembayaran sudah up-to-date');
    }

    // AJAX endpoint check status pembayaran real-time
    public function checkPaymentStatus(Transaction $transaction)
    {
        $syncService = new MidtransStatusSyncService;
        $oldStatus = $transaction->payment_status;
        $updated = $syncService->syncPaymentStatus($transaction);

        return response()->json([
            'success' => true,
            'old_status' => $oldStatus,
            'new_status' => $updated->payment_status,
            'updated' => $oldStatus !== $updated->payment_status,
            'rental_status' => $updated->rental_status ?? null,
            'message' => $oldStatus !== $updated->payment_status
                ? "Status berubah dari {$oldStatus} ke {$updated->payment_status}"
                : "Status tidak berubah ({$updated->payment_status})",
        ]);
    }

    public function destroyMotor(Motorcycle $motorcycle)
    {
        if ($motorcycle->owner_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus motor ini.');
        }
        // Cek apakah motor sedang dalam transaksi aktif
        $activeTransactions = Transaction::where('motorcycle_id', $motorcycle->id)
            ->whereIn('payment_status', ['pending', 'success'])
            ->where('rental_status', '!=', 'finished')
            ->count();
        if ($activeTransactions > 0) {
            return redirect()->route('pemilik.daftar-motor')
                ->with('error', 'Motor tidak dapat dihapus karena sedang dalam transaksi aktif.');
        }
        $motorName = $motorcycle->name;
        $rental = $motorcycle->motorbikeRental;
        foreach ($motorcycle->images as $img) {
            $img->delete();
        }
        $motorcycle->delete();

        // Cek apakah masih ada motor lain di rental ini
        $remainingMotors = Motorcycle::where('motorbike_rental_id', $rental->id)->count();
        if ($remainingMotors == 0) {
            // Jika tidak ada motor lagi, hapus rental beserta bonus
            foreach ($rental->bonuses as $bonus) {
                $bonus->delete();
            }
            $rental->delete();

            return redirect()->route('pemilik.daftar-motor')
                ->with('success', "Motor '{$motorName}' berhasil dihapus. Rental juga dihapus karena tidak ada motor yang tersisa.");
        }

        return redirect()->route('pemilik.daftar-motor')
            ->with('success', "Motor '{$motorName}' berhasil dihapus.");
    }
}
