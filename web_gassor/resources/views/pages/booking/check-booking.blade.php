@extends('layouts.app')

@section('content')
<div style="position: absolute; top: 0; width: 100%; height: 430px; border-bottom-left-radius: 75px; border-bottom-right-radius: 75px; background: linear-gradient(180deg, #e6a43b 0%, #e6a43b 100%)"></div>
<div class="relative flex flex-col gap-[30px] my-[60px] px-5">
    <h1 class="font-bold text-[30px] leading-[45px] text-center">Periksa Rincian<br>Pemesanan Anda</h1>
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
                            @php
                                $status = strtoupper($transaction->payment_status);
                                $statusColor = match($status) {
                                    'SUCCESS' => '#27ae60',
                                    'FAILED' => '#eb5757',
                                    'CANCELED' => '#bdbdbd',
                                    'PENDING' => '#f2994a',
                                    'EXPIRED' => '#9b51e0',
                                    default => '#828282',
                                };
                            @endphp
                            <p class="rounded-full p-[6px_12px] font-bold text-xs leading-[18px] text-white" style="background: {{ $statusColor }};">
                                {{ $status }}
                            </p>
                        </div>
                    </div>
                </button>
            </form>
            @if(in_array(strtoupper($transaction->payment_status), ['FAILED','EXPIRED','PENDING']))
                <div style="display: flex; gap: 16px; margin-top: 32px; margin-bottom: 8px;">
                    <form action="{{ route('booking.retry-payment', $transaction->code) }}" method="POST" style="flex: 1;">
                        @csrf
                        <button type="submit" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; border-radius: 12px; padding: 12px 0; background: #fff; border: 2px solid #fff; color: #F2994A; font-weight: bold; font-size: 1rem; box-shadow: none; transition: border 0.2s, box-shadow 0.2s; cursor: pointer;"
                        onmouseover="this.style.borderColor='#F2994A'" onmouseout="this.style.borderColor='#fff'">
                            <svg xmlns='http://www.w3.org/2000/svg' style='height:20px;width:20px' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z' /></svg>
                            Bayar Ulang
                        </button>
                    </form>
                    <form action="{{ route('booking.cancel', $transaction->code) }}" method="POST" style="flex: 1;">
                        @csrf
                        <button type="submit" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; border-radius: 12px; padding: 12px 0; background: #fff; border: 2px solid #fff; color: #EB5757; font-weight: bold; font-size: 1rem; box-shadow: none; transition: border 0.2s, box-shadow 0.2s; cursor: pointer;"
                        onmouseover="this.style.borderColor='#EB5757'" onmouseout="this.style.borderColor='#fff'">
                            <svg xmlns='http://www.w3.org/2000/svg' style='height:20px;width:20px' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 18L18 6M6 6l12 12' /></svg>
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
