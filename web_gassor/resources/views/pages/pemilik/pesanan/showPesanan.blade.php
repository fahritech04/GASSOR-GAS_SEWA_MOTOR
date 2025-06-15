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
                    Disewa Oleh: <span class="font-semibold">{{ $transaction->name }}</span>
                </p>
                <p class="text-sm text-gassor-grey">
                    Tanggal: {{ \Carbon\Carbon::parse($transaction->start_date)->isoFormat('D MMMM YYYY') }}
                </p>
            </div>
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
        </div>
    @empty
        <p class="text-center text-gassor-grey">Belum ada pesanan terbaru.</p>
    @endforelse
</section>

@include('includes.navigation_pemilik')
@endsection
