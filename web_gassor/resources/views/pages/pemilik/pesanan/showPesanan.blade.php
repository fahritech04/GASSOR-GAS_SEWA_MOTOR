@extends('layouts.app')

@section('content')
<div id="Background"
    class="absolute top-0 w-full h-[570px] rounded-b-[75px]"
    style="background: linear-gradient(180deg, #e6a43b 0%, #e6a43b 100%);">
</div>
<div id="Header" class="relative flex items-center justify-between gap-2 px-5 mt-[18px]">
    <div class="flex flex-col gap-[6px]">
        <h1 class="font-bold text-[32px] leading-[48px]">Pesanan</h1>
        <p class="text-gassor-grey">Tersedia {{ $transactions->count() }} Pesanan</p>
    </div>
</div>
<section id="Result" class="relative flex flex-col gap-4 px-5 mt-5 mb-9">
    <h2 class="font-bold text-lg mb-2">Pesanan yang dimiliki {{ auth()->user()->name }}</h2>
    @forelse ($transactions as $transaction)
        <div class="bonus-card flex items-center justify-between rounded-[22px] border border-[#000000] p-[10px] gap-3 mb-3 bg-white">
            <div>
                <p class="font-semibold">{{ $transaction->motorcycle->name ?? '-' }}</p>
                <p class="text-sm text-gassor-grey">
                    Disewa : <span class="font-semibold">{{ $transaction->name }}</span>
                </p>
                {{-- <p class="text-sm text-gassor-grey">
                    Tanggal : {{ \Carbon\Carbon::parse($transaction->start_date)->isoFormat('D MMMM YYYY') }}
                </p> --}}
                <p class="text-sm text-gassor-grey" style="text-align:left">
                    Mulai : {{ $transaction->start_date ? (\Carbon\Carbon::parse($transaction->start_date)->isoFormat('D MMMM YYYY') . ($transaction->start_time ? ' - ' . (strlen($transaction->start_time) === 5 ? $transaction->start_time : (\Carbon\Carbon::createFromFormat('H:i:s', $transaction->start_time)->format('H:i'))) . ' WIB' : '')) : '-' }}
                    <br>
                    Selesai : {{ $transaction->start_date ? (\Carbon\Carbon::parse($transaction->start_date)->addDays(1)->isoFormat('D MMMM YYYY') . ($transaction->end_time ? ' - ' . (strlen($transaction->end_time) === 5 ? $transaction->end_time : (\Carbon\Carbon::createFromFormat('H:i:s', $transaction->end_time)->format('H:i'))) . ' WIB' : '')) : '-' }}
                </p>
            </div>
            <div class="flex flex-col items-end gap-2 justify-center h-full">
                @php
                    $rentalStatus = $transaction->motorcycle->status ?? null;
                    $rentalStatusLabel = $rentalStatus === 'on_going' ? 'SEDANG BERJALAN' : ($rentalStatus === 'finished' ? 'SELESAI' : null);
                    $rentalStatusColor = $rentalStatus === 'on_going' ? '#f2994a' : ($rentalStatus === 'finished' ? '#bdbdbd' : '#828282');
                @endphp
                <p class="rounded-full p-[6px_12px] font-bold text-xs leading-[18px] break-all text-white text-center" style="background: {{
                    match(strtoupper($transaction->payment_status)) {
                        'SUCCESS' => '#27ae60',
                        'FAILED' => '#eb5757',
                        'CANCELED' => '#bdbdbd',
                        'PENDING' => '#f2994a',
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
        @if($rentalStatus === 'on_going')
            <form method="POST" action="{{ route('pemilik.pesanan.return', $transaction->id) }}">
                @csrf
                <button type="submit" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; border-radius: 12px; padding: 12px 0; background: #27ae60; color: #fff; font-weight: bold; font-size: 1rem; box-shadow: none; transition: border 0.2s, box-shadow 0.2s; cursor: pointer;"
            onmouseover="this.style.borderColor='#F2994A'" onmouseout="this.style.borderColor='#fff'">
                Motor Sudah Dikembalikan
            </button>
            </form>
        @endif
    @empty
        <p class="text-center text-gassor-grey">Belum ada pesanan terbaru.</p>
    @endforelse
</section>

@include('includes.navigation_pemilik')
@endsection
