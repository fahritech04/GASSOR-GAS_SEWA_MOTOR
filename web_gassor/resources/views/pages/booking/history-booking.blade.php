@extends('layouts.app')

@section('content')
<div style="position: absolute; top: 0; width: 100%; height: 430px; border-bottom-left-radius: 75px; border-bottom-right-radius: 75px; background: linear-gradient(180deg, #e6a43b 0%, #e6a43b 100%)"></div>

<div id="TopNav" class="relative flex items-center justify-between px-5 mt-[60px]">
    <a href="{{ route('profile.penyewa') }}" class="flex items-center justify-center w-12 h-12 overflow-hidden bg-white rounded-full shrink-0">
        <img src="{{ asset('assets/images/icons/arrow-left.svg') }}" class="w-[28px] h-[28px]" alt="icon" />
    </a>
    <p class="font-semibold text-black">Riwayat Pemesanan</p>
    <div class="w-12 dummy-btn"></div>
</div>

<div class="relative flex flex-col gap-[30px] my-[60px] px-5">
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

                                if ($rentalStatus === 'finished') {
                                    $rentalStatusLabel = 'SELESAI';
                                    $rentalStatusColor = '#27ae60';
                                } elseif ($rentalStatus === 'canceled') {
                                    $rentalStatusLabel = 'DIBATALKAN';
                                    $rentalStatusColor = '#eb5757';
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
                            @if($rentalStatusLabel)
                                <p class="rounded-full p-[6px_12px] font-bold text-xs leading-[18px] text-white text-center" style="background: {{ $rentalStatusColor }};">
                                    {{ $rentalStatusLabel }}
                                </p>
                            @endif
                        </div>
                    </div>
                </button>
            </form>

            <!-- Tombol Pesan Lagi -->
            @if($transaction->motorbikeRental && $transaction->motorbikeRental->slug)
                <div style="margin-top: 8px">
                    <a href="{{ route('motor.show', $transaction->motorbikeRental->slug) }}"
                       style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; border-radius: 12px; padding: 12px 0; background: #000000; color: #fff; font-weight: bold; font-size: 1rem; box-shadow: none; transition: border 0.2s, box-shadow 0.2s; cursor: pointer;">
                        Pesan Lagi
                    </a>
                </div>
            @endif
        </div>
    @empty
        <p class="text-center text-white">Belum ada history pemesanan.</p>
    @endforelse
</div>

@endsection
