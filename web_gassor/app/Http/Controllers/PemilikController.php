<?php

namespace App\Http\Controllers;

use App\Models\Motorcycle;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\MotorbikeRental;
use App\Models\Bonus;

class PemilikController extends Controller
{
    public function index()
    {
        $motorcycles = Motorcycle::where('owner_id', auth()->id())->paginate(3);
        $totalMotor = Motorcycle::where('owner_id', auth()->id())->count();

        $motorcycleIds = $motorcycles->pluck('id');

        $transactions = Transaction::with(['motorcycle', 'motorcycle.owner'])
            ->whereIn('motorcycle_id', $motorcycleIds)
            ->latest()
            ->orderByRaw("FIELD(payment_status, 'pending', 'success') DESC")
            ->take(3)
            ->get();

        $activeOrders = $transactions->filter(function($transaction) {
            return (
                strtolower($transaction->payment_status) === 'success'
                && ($transaction->motorcycle && $transaction->motorcycle->status === 'on_going')
            );
        })->count();

        $totalIncome = $transactions
            ->where('payment_status', 'success')
            ->sum(function ($transaction) {
                // Hitung durasi hari sewa
                $start = \Carbon\Carbon::parse($transaction->start_date);
                $end = \Carbon\Carbon::parse($transaction->end_date);
                $days = $start->diffInDays($end) ?: 1; // minimal 1 hari

                return $transaction->motorcycle->price_per_day * $days;
            });

        return view('pages.pemilik.dashboard', compact('motorcycles', 'transactions', 'activeOrders', 'totalIncome', 'totalMotor'));
    }

    public function showDaftarMotor()
    {
        $motorcycles = Motorcycle::where('owner_id', auth()->id())->paginate(5);
        $totalMotor = Motorcycle::where('owner_id', auth()->id())->count();

        return view('pages.pemilik.daftar-motor.showDaftarMotor', compact('motorcycles', 'totalMotor'));
    }

    public function showPesanan()
    {
        $ownerId = auth()->id();
        $transactions = Transaction::with(['motorcycle', 'motorcycle.owner'])
            ->whereHas('motorcycle', function ($query) use ($ownerId) {
                $query->where('owner_id', $ownerId);
            })
            ->orderByRaw("FIELD(payment_status, 'pending', 'success') DESC")
            ->latest()
            ->paginate(5);

        return view('pages.pemilik.pesanan.showPesanan', compact('transactions'));
    }

    public function createMotor()
    {
        return view('pages.pemilik.daftar-motor.createMotor');
    }

    public function returnMotor(Transaction $transaction)
    {
        if (
            $transaction->motorcycle &&
            $transaction->motorcycle->status === 'on_going' &&
            strtoupper($transaction->payment_status) === 'SUCCESS'
        ) {
            $motorcycle = $transaction->motorcycle;
            $motorcycle->status = 'finished';
            $motorcycle->is_available = 1;
            $motorcycle->save();
        }
        return redirect()->route('pemilik.pesanan')->with('success', 'Status motor berhasil diubah menjadi selesai.');
    }

    public function storeMotor(Request $request)
    {
        try {
            $validated = $request->validate([
                'thumbnail' => 'required|image',
                'name' => 'required|string',
                'slug' => 'required|string|unique:motorbike_rentals,slug',
                'city_id' => 'required|exists:cities,id',
                'category_id' => 'required|exists:categories,id',
                'description' => 'required|string',
                'address' => 'required|string',
            ]);

            $thumbnailPath = $request->file('thumbnail')->store('motorbike_rental', 'public');

            // Simpan motorbike rental
            $rental = MotorbikeRental::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'thumbnail' => $thumbnailPath,
                'city_id' => $request->city_id,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'address' => $request->address,
                'contact' => Auth::user()->phone ?? '',
            ]);

            // Simpan bonus
            if ($request->has('bonuses')) {
                foreach ($request->bonuses as $bonus) {
                    $bonusImage = null;
                    if (isset($bonus['image'])) {
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

            // Simpan motorcycles
            if ($request->has('motorcycles')) {
                foreach ($request->motorcycles as $motor) {
                    $stnkImages = [];
                    if (isset($motor['stnk_images'])) {
                        foreach ($motor['stnk_images'] as $img) {
                            $stnkImages[] = $img->store('stnk', 'public');
                        }
                    }
                    // Simpan data motor
                    $motorcycle = Motorcycle::create([
                        'owner_id' => auth()->id(),
                        'motorbike_rental_id' => $rental->id,
                        'name' => $motor['name'],
                        'motorcycle_type' => $motor['motorcycle_type'],
                        'vehicle_number_plate' => $motor['vehicle_number_plate'],
                        'stnk' => $motor['stnk'],
                        'stnk_images' => $stnkImages,
                        'price_per_day' => $motor['price_per_day'],
                        'is_available' => true,
                        'status' => $motor['status'] ?? null,
                        'has_gps' => $motor['has_gps'] ?? false,
                    ]);
                    // Simpan gambar motor ke motorcycle_images
                    if (isset($motor['images'])) {
                        foreach ($motor['images'] as $img) {
                            $imgPath = $img->store('motorcycles', 'public');
                            \App\Models\MotorcycleImage::create([
                                'motorcycle_id' => $motorcycle->id,
                                'image' => $imgPath,
                            ]);
                        }
                    }
                }
            }

            return redirect()->route('pemilik.daftar-motor')->with('success', 'Data berhasil disimpan!');
        } catch (\Throwable $e) {
            $fullError = $e->getMessage() . ' | FILE: ' . $e->getFile() . ' | LINE: ' . $e->getLine() . ' | TRACE: ' . $e->getTraceAsString();
            return back()->withInput()->with('error', $fullError);
        }
    }

    public function editMotor(Motorcycle $motorcycle)
    {
        // hanya owner yang bisa edit
        if ($motorcycle->owner_id !== auth()->id()) {
            abort(403);
        }
        $motorbikeRental = $motorcycle->motorbikeRental()->with('bonuses')->first();
        return view('pages.pemilik.daftar-motor.editMotor', compact('motorcycle', 'motorbikeRental'));
    }

    public function updateMotor(Request $request, Motorcycle $motorcycle)
    {
        if ($motorcycle->owner_id !== auth()->id()) {
            abort(403);
        }
        // Update MotorbikeRental (Informasi Umum)
        $motorbikeRental = $motorcycle->motorbikeRental;
        if ($motorbikeRental) {
            $motorbikeRentalData = [
                'name' => $request->input('name'),
                'slug' => $request->input('slug'),
                'city_id' => $request->input('city_id'),
                'category_id' => $request->input('category_id'),
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

            foreach ($inputBonuses as $idx => $bonus) {
                $bonusImage = null;
                if (isset($bonus['image']) && $bonus['image']) {
                    $bonusImage = $bonus['image']->store('bonuses', 'public');
                } elseif (isset($oldBonuses[$idx]) && $oldBonuses[$idx]->image) {
                    $bonusImage = $oldBonuses[$idx]->image;
                }

                if (isset($oldBonuses[$idx])) {
                    // Update bonus lama
                    $oldBonuses[$idx]->update([
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
            }
            // Hapus bonus lama yang tidak ada di input
            for ($i = count($inputBonuses); $i < count($oldBonuses); $i++) {
                $oldBonuses[$i]->delete();
            }
        }
        // Update Motorcycle (Motor)
        $motorcycle->update([
            'name' => $request->input('name'),
            'motorcycle_type' => $request->input('motorcycle_type'),
            'vehicle_number_plate' => $request->input('vehicle_number_plate'),
            'stnk' => $request->input('stnk'),
            'price_per_day' => $request->input('price_per_day'),
            'status' => $request->input('status'),
            'has_gps' => $request->has('has_gps'),
            'is_available' => $request->has('is_available'),
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
        return redirect()->route('pemilik.daftar-motor')->with('success', 'Data motor & rental berhasil diupdate!');
    }

    public function destroyRental($motorbike_rental_id)
    {
        $rental = MotorbikeRental::withTrashed()->with(['motorcycles.images' => function($q){$q->withTrashed();}, 'bonuses' => function($q){$q->withTrashed();}])->findOrFail($motorbike_rental_id);

        // Hapus semua gambar motor
        foreach ($rental->motorcycles as $motorcycle) {
            foreach ($motorcycle->images as $img) {
                $img->forceDelete();
            }
            $motorcycle->forceDelete();
        }
        // Hapus semua bonus
        foreach ($rental->bonuses as $bonus) {
            $bonus->forceDelete();
        }
        // Hapus rental
        $rental->forceDelete();

        return redirect()->route('pemilik.daftar-motor')->with('success', 'Rental dan semua data terkait berhasil dihapus permanen!');
    }
}
