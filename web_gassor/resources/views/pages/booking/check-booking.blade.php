@extends('layouts.app')

@section('content')
<div style="position: absolute; top: 0; width: 100%; height: 430px; border-bottom-left-radius: 75px; border-bottom-right-radius: 75px; background: linear-gradient(180deg, #e6a43b 0%, #e6a43b 100%)"></div>
<div class="relative flex flex-col gap-[30px] my-[60px] px-5">
    <h1 class="font-bold text-[30px] leading-[45px] text-center">Rincian<br>Pemesanan Aktif</h1>

    @forelse ($transactions as $transaction)
        <div class="w-full mb-4">
            <form action="{{ route('check-booking.show') }}" method="POST" class="w-full">
                @csrf
                <input type="hidden" name="code" value="{{ $transaction->code }}">
                <input type="hidden" name="email" value="{{ $transaction->email }}">
                <input type="hidden" name="phone_number" value="{{ $transaction->phone_number }}">
                <button type="submit" class="w-full text-left">
                    <div class="bonus-card flex items-center justify-between rounded-[22px] border border-[#000000] p-[10px] gap-3 mb-3 bg-white transition-shadow hover:shadow-lg cursor-pointer">
                        <div style="text-align:left;width:100%">
                            <p class="font-semibold text-lg" style="text-align:left">{{ $transaction->motorcycle->name ?? '-' }}</p>
                            {{-- <p class="text-sm text-gassor-grey" style="text-align:left">
                                Disewa : <span class="font-semibold" style="text-align:left">{{ $transaction->name }}</span>
                            </p> --}}
                            <p class="text-sm text-gassor-grey" style="text-align:left">
                                Pemilik : <span class="font-semibold" style="text-align:left">{{ $transaction->motorcycle->owner->name ?? '-' }}</span>
                            </p>
                            <p class="text-sm text-gassor-grey" style="text-align:left">
                                Mulai : {{ $transaction->start_date ? (\Carbon\Carbon::parse($transaction->start_date)->isoFormat('D MMMM YYYY') . ($transaction->start_time ? ' - ' . (strlen($transaction->start_time) === 5 ? $transaction->start_time : (\Carbon\Carbon::createFromFormat('H:i:s', $transaction->start_time)->format('H:i'))) . ' WIB' : '')) : '-' }}
                                <br>
                                Selesai : {{ $transaction->start_date ? (\Carbon\Carbon::parse($transaction->start_date)->addDays(1)->isoFormat('D MMMM YYYY') . ($transaction->end_time ? ' - ' . (strlen($transaction->end_time) === 5 ? $transaction->end_time : (\Carbon\Carbon::createFromFormat('H:i:s', $transaction->end_time)->format('H:i'))) . ' WIB' : '')) : '-' }}
                            </p>
                        </div>
                        <div class="flex flex-col items-end gap-2 justify-center h-full">
                            @php
                                $rentalStatus = $transaction->rental_status ?? 'pending';

                                if ($transaction->payment_status === 'success') {
                                    if ($rentalStatus === 'finished') {
                                        $rentalStatusLabel = 'SELESAI';
                                        $rentalStatusColor = '#27ae60';
                                    } elseif ($rentalStatus === 'on_going') {
                                        $rentalStatusLabel = 'SEDANG BERJALAN';
                                        $rentalStatusColor = '#E6A43B';
                                    } else {
                                        $rentalStatusLabel = null;
                                        $rentalStatusColor = '#828282';
                                    }
                                } else {
                                    $rentalStatusLabel = null;
                                    $rentalStatusColor = '#828282';
                                }
                            @endphp
                            <p class="rounded-full p-[6px_12px] font-bold text-xs leading-[18px] break-all text-white text-center" style="background: {{
                                match(strtoupper($transaction->payment_status)) {
                                    'SUCCESS' => '#27ae60',
                                    'FAILED' => '#eb5757',
                                    'CANCELED' => '#bdbdbd',
                                    'PENDING' => '#E6A43B',
                                    'EXPIRED' => '#9b51e0',
                                    default => '#828282',
                                }
                            }};">
                                {{ strtoupper($transaction->payment_status) }}
                            </p>
                            @if(strtoupper($transaction->payment_status) === 'SUCCESS' && $rentalStatusLabel)
                                <p class="rounded-full p-[6px_12px] font-bold text-xs leading-[18px] text-white text-center" style="background: {{ $rentalStatusColor }};">
                                    {{ $rentalStatusLabel }}
                                </p>
                            @endif
                        </div>
                    </div>
                </button>
            </form>
            @if(strtoupper($transaction->payment_status) === 'PENDING')
                <div style="display: flex; gap: 16px; margin-top: 32px; margin-bottom: 8px;">
                    <form action="{{ route('booking.retry-payment', $transaction->code) }}" method="POST" style="flex: 1;" class="retry-payment-form">
                        @csrf
                        <button type="submit" onclick="confirmRetryPayment(event, this.form)" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; border-radius: 12px; padding: 12px 0; background: #27ae60; color: #fff; font-weight: bold; font-size: 1rem; box-shadow: none; transition: all 0.3s ease; cursor: pointer;"
                        onmouseover="this.style.backgroundColor='#239C56'; this.style.transform='translateY(-2px)'"
                        onmouseout="this.style.backgroundColor='#27ae60'; this.style.transform='translateY(0)'">
                            Bayar Ulang
                        </button>
                    </form>
                    <form action="{{ route('booking.cancel', $transaction->code) }}" method="POST" style="flex: 1;" class="cancel-form">
                        @csrf
                        <button type="submit" onclick="confirmCancel(event, this.form)" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; border-radius: 12px; padding: 12px 0; background: #EB5757; color: #fff; font-weight: bold; font-size: 1rem; box-shadow: none; transition: all 0.3s ease; cursor: pointer;"
                        onmouseover="this.style.backgroundColor='#E74C3C'; this.style.transform='translateY(-2px)'"
                        onmouseout="this.style.backgroundColor='#EB5757'; this.style.transform='translateY(0)'">
                            Batalkan
                        </button>
                    </form>
                </div>
            @elseif(strtoupper($transaction->payment_status) === 'CANCELED')
            @elseif(strtoupper($transaction->payment_status) === 'SUCCESS')
            @endif
        </div>
    @empty
        <p class="text-center text-gassor-grey">Belum ada pesanan terbaru.</p>
    @endforelse
</div>

@include('includes.navigation')
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('error'))
            Swal.fire({
                icon: 'warning',
                title: 'Akun Belum Diverifikasi',
                text: '{{ session('error') }}',
                confirmButtonColor: '#ff9900',
            });
        @endif
    });

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            confirmButtonText: 'OK',
            confirmButtonColor: '#E6A43B',
            background: '#ffffff',
            customClass: {
                popup: 'rounded-lg shadow-lg',
                title: 'text-gassor-black font-bold',
                content: 'text-gassor-grey',
                confirmButton: 'rounded-full px-6 py-2 font-bold'
            }
        });
    @endif

    // Konfirmasi pembatalan pesanan
    function confirmCancel(event, form) {
        event.preventDefault();

        Swal.fire({
            icon: 'warning',
            title: 'Konfirmasi Pembatalan',
            text: 'Apakah Anda yakin ingin membatalkan pesanan ini? Tindakan ini tidak dapat dibatalkan.',
            showCancelButton: true,
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Tidak',
            confirmButtonColor: '#EB5757',
            cancelButtonColor: '#95A5A6',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-lg shadow-lg',
                title: 'text-gassor-black font-bold',
                content: 'text-gassor-grey',
                confirmButton: 'rounded-full px-6 py-2 font-bold',
                cancelButton: 'rounded-full px-6 py-2 font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan loading
                Swal.fire({
                    title: 'Membatalkan Pesanan...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Submit form pembatalan
                form.submit();
            }
        });
    }

    // Konfirmasi pembayaran ulang
    function confirmRetryPayment(event, form) {
        event.preventDefault();

        Swal.fire({
            icon: 'question',
            title: 'Konfirmasi Pembayaran',
            text: 'Anda akan diarahkan ke halaman pembayaran. Lanjutkan?',
            showCancelButton: true,
            confirmButtonText: 'Ya, Bayar Sekarang',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#27AE60',
            cancelButtonColor: '#95A5A6',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-lg shadow-lg',
                title: 'text-gassor-black font-bold',
                content: 'text-gassor-grey',
                confirmButton: 'rounded-full px-6 py-2 font-bold',
                cancelButton: 'rounded-full px-6 py-2 font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan loading
                Swal.fire({
                    title: 'Mengarahkan ke Pembayaran...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Submit form retry payment
                form.submit();
            }
        });
    }
</script>
@endsection
