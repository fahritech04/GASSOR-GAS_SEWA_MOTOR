@extends('layouts.app')

@section('content')
<div id="Background"
    class="absolute top-0 w-full h-[430px] rounded-b-[75px] bg-[linear-gradient(180deg,#F2F9E6_0%,#D2EDE4_100%)]">
</div>
<div class="relative flex flex-col gap-[30px] my-[60px] px-5">
    <h1 class="font-bold text-[30px] leading-[45px] text-center">Periksa Rincian<br>Pemesanan Anda</h1>
    @forelse ($transactions as $transaction)
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
                            Disewa Oleh: <span class="font-semibold" style="text-align:left">{{ $transaction->name }}</span>
                        </p>
                        <p class="text-sm text-gassor-grey" style="text-align:left">
                            Pemilik motor : <span class="font-semibold" style="text-align:left">{{ $transaction->motorcycle->owner->name ?? '-' }}</span>
                        </p>
                        <p class="text-sm text-gassor-grey" style="text-align:left">
                            Mulai: {{ $transaction->start_date ? (\Carbon\Carbon::parse($transaction->start_date)->isoFormat('D MMMM YYYY') . ($transaction->start_time ? ' - ' . (strlen($transaction->start_time) === 5 ? $transaction->start_time : (\Carbon\Carbon::createFromFormat('H:i:s', $transaction->start_time)->format('H:i'))) . ' WIB' : '')) : '-' }}
                            <br>
                            Selesai: {{ $transaction->start_date ? (\Carbon\Carbon::parse($transaction->start_date)->addDays(1)->isoFormat('D MMMM YYYY') . ($transaction->end_time ? ' - ' . (strlen($transaction->end_time) === 5 ? $transaction->end_time : (\Carbon\Carbon::createFromFormat('H:i:s', $transaction->end_time)->format('H:i'))) . ' WIB' : '')) : '-' }}
                        </p>
                    </div>
                    <div class="flex flex-col items-end gap-2">
                        <p class="rounded-full p-[6px_12px] bg-gassor-orange font-bold text-xs leading-[18px] text-white">
                            {{ strtoupper($transaction->payment_status) }}
                        </p>
                    </div>
                </div>
            </button>
        </form>
    @empty
        <p class="text-center text-gassor-grey">Belum ada pesanan terbaru.</p>
    @endforelse
</div>

@include('includes.navigation')
@endsection
