@extends('layouts.app')

@section('content')
<div id="Background" class="absolute top-0 w-full h-[430px] rounded-b-[75px] bg-[linear-gradient(180deg,#F2F9E6_0%,#D2EDE4_100%)]"></div>
<div class="relative flex flex-col gap-[30px] my-[60px] px-5 items-center justify-center min-h-[60vh]">
    <div class="w-full max-w-xl bg-white rounded-[30px] shadow-lg p-8 flex flex-col items-center gap-6 z-10">
        @if($status === 'success')
            <div class="flex flex-col items-center gap-2">
                <div class="w-16 h-16 rounded-full bg-orange-100 flex items-center justify-center mb-2">
                    <svg class="w-10 h-10 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                </div>
                <h1 class="text-2xl font-bold text-orange-600 mb-2">Pembayaran Berhasil</h1>
                <p class="text-center text-gassor-grey">Pembayaran Anda telah <b>berhasil</b>. Silakan cek detail pesanan Anda.</p>
            </div>
        @elseif($status === 'pending')
            <div class="flex flex-col items-center gap-2">
                <div class="w-16 h-16 rounded-full bg-yellow-100 flex items-center justify-center mb-2">
                    <svg class="w-10 h-10 text-yellow-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12A9 9 0 1 1 3 12a9 9 0 0 1 18 0z" /></svg>
                </div>
                <h1 class="text-2xl font-bold text-yellow-500 mb-2">Pembayaran Pending</h1>
                <p class="text-center text-gassor-grey">Pembayaran Anda masih <b>pending</b>. Silakan selesaikan pembayaran di Midtrans.</p>
                <p class="text-center text-sm text-gray-500">Halaman ini akan otomatis memeriksa status pembayaran setiap 5 detik.</p>
            </div>
        @elseif($status === 'failed')
            <div class="flex flex-col items-center gap-2">
                <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mb-2">
                    <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </div>
                <h1 class="text-2xl font-bold text-red-500 mb-2">Pembayaran Gagal</h1>
                <p class="text-center text-gassor-grey">Transaksi Anda <b>gagal</b>. Silakan coba bayar ulang atau pilih metode pembayaran lain.</p>
            </div>
        @elseif($status === 'canceled')
            <div class="flex flex-col items-center gap-2">
                <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center mb-2">
                    <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-500 mb-2">Pesanan Dibatalkan</h1>
                <p class="text-center text-gassor-grey">Pesanan ini sudah <b>dibatalkan</b>. Silakan buat pesanan baru jika ingin menyewa kembali.</p>
            </div>
        @elseif($status === 'expired')
            <div class="flex flex-col items-center gap-2">
                <div class="w-16 h-16 rounded-full bg-purple-100 flex items-center justify-center mb-2">
                    <svg class="w-10 h-10 text-purple-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12A9 9 0 1 1 3 12a9 9 0 0 1 18 0z" /></svg>
                </div>
                <h1 class="text-2xl font-bold text-purple-500 mb-2">Pembayaran Expired</h1>
                <p class="text-center text-gassor-grey">Waktu pembayaran sudah <b>expired</b>. Silakan lakukan pemesanan ulang.</p>
            </div>
        @else
            <div class="flex flex-col items-center gap-2">
                <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center mb-2">
                    <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01" /></svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-500 mb-2">Status: {{ strtoupper($status) }}</h1>
                <p class="text-center text-gassor-grey">Status pembayaran: <b>{{ strtoupper($status) }}</b></p>
            </div>
        @endif

        {{-- <div class="w-full flex flex-col gap-4 mt-2">
            <div class="flex flex-col gap-1">
                <span class="text-sm text-gray-400">ID Pemesanan</span>
                <div class="flex items-center rounded-full p-[10px_18px] gap-2 bg-[#F5F6F8] w-fit mx-auto">
                    <img src="/assets/images/icons/note-favorite-orange.svg" class="w-5 h-5 flex shrink-0" alt="icon">
                    <span class="font-semibold">{{ $transaction->code }}</span>
                </div>
            </div>
            <div class="flex flex-col gap-1">
                <span class="text-sm text-gray-400">Nama Motor</span>
                <span class="font-semibold text-center">{{ $transaction->motorcycle->name ?? '-' }}</span>
            </div>
            <div class="flex flex-col gap-1">
                <span class="text-sm text-gray-400">Tanggal Sewa</span>
                <span class="font-semibold text-center">
                    {{ \Carbon\Carbon::parse($transaction->start_date)->isoFormat('D MMMM YYYY') }} - {{ \Carbon\Carbon::createFromFormat('H:i:s', $transaction->start_time)->format('H:i') }} WIB
                    &nbsp;|&nbsp;
                    {{ \Carbon\Carbon::parse($transaction['start_date'])->addDays(intval($transaction['duration']))->isoFormat('D MMMM YYYY') }} - {{ \Carbon\Carbon::createFromFormat('H:i:s', $transaction->end_time)->format('H:i') }} WIB
                </span>
            </div>
        </div> --}}

        <div id="Header" class="relative flex items-center justify-between gap-2">
            <div class="flex flex-col w-full rounded-[30px] border border-[#F1F2F6] p-4 gap-4 bg-white">
                <div class="flex gap-4">
                    <div class="flex w-[120px] h-[132px] shrink-0 rounded-[30px] bg-[#D9D9D9] overflow-hidden">
                        <img src="{{ asset('storage/' . $transaction->motorbikeRental->thumbnail ) }}" class="w-full h-full object-cover" alt="icon">
                    </div>
                    <div class="flex flex-col gap-3 w-full">
                        <p class="font-semibold text-lg leading-[27px] line-clamp-2 min-h-[54px]">{{ $transaction->motorbikeRental->name }}</p>
                        <hr class="border-[#F1F2F6]">
                        <div class="flex items-center gap-[6px]">
                            <img src="assets/images/icons/location.svg" class="w-5 h-5 flex shrink-0" alt="icon">
                            <p class="text-sm text-gassor-grey">Wilayah {{ $transaction->motorbikeRental->city->name }}</p>
                        </div>
                        <div class="flex items-center gap-[6px]">
                            <img src="assets/images/icons/profile-2user.svg" class="w-5 h-5 flex shrink-0" alt="icon">
                            <p class="text-sm text-gassor-grey">Kategori {{ $transaction->motorbikeRental->category->name }}</p>
                        </div>
                    </div>
                </div>
                <hr class="border-[#F1F2F6]">
                <div class="flex gap-4">
                    <div class="flex w-[120px] h-[138px] shrink-0 rounded-[30px] bg-[#D9D9D9] overflow-hidden">
                        <img src="{{ asset('storage/' . $transaction->motorcycle->images->first()->image ) }}" class="w-full h-full object-cover" alt="icon">
                    </div>
                    <div class="flex flex-col gap-3 w-full">
                        <p class="font-semibold text-lg leading-[27px]">{{ $transaction->motorcycle->name }}</p>
                        <hr class="border-[#F1F2F6]">
                        <div class="flex items-center gap-[6px]">
                            <img src="assets/images/icons/profile-2user.svg" class="w-5 h-5 flex shrink-0" alt="icon">
                            <p class="text-sm text-gassor-grey">STNK : {{ $transaction->motorcycle->stnk }}</p>
                        </div>
                        <div class="flex items-center gap-[6px]">
                            <img src="assets/images/icons/3dcube.svg" class="w-5 h-5 flex shrink-0" alt="icon">
                            <p class="text-sm text-gassor-grey">Nomor Polisi : {{ $transaction->motorcycle->vehicle_number_plate }}</p>
                        </div>
                        <div class="flex items-center gap-[6px]">
                            <img src="assets/images/icons/calendar.svg" class="w-5 h-5 flex shrink-0" alt="icon">
                            <p class="text-sm text-gassor-grey">
                                {{ \Carbon\Carbon::parse($transaction->start_date)->isoFormat('D MMMM YYYY') }} - {{ \Carbon\Carbon::createFromFormat('H:i:s', $transaction->start_time)->format('H:i') }} WIB
                                &nbsp;|&nbsp;
                                {{ \Carbon\Carbon::parse($transaction['start_date'])->addDays(intval($transaction['duration']))->isoFormat('D MMMM YYYY') }} - {{ \Carbon\Carbon::createFromFormat('H:i:s', $transaction->end_time)->format('H:i') }} WIB
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-[18px]">
            <p class="font-semibold">ID Pemesanan Kamu</p>
            <div class="flex items-center rounded-full p-[14px_20px] gap-3 bg-[#F5F6F8]">
                <img src="assets/images/icons/note-favorite-orange.svg" class="w-5 h-5 flex shrink-0" alt="icon">
                <p class="font-semibold">{{ $transaction->code }}</p>
            </div>
        </div>

        <div class="flex flex-col gap-3 w-full mt-6">
            @if(in_array($status, ['pending','failed','expired']))
            <form action="{{ route('booking.retry-payment', $transaction->code) }}" method="POST" class="w-full">
                @csrf
                <button type="submit" class="w-full rounded-full p-[14px_20px] bg-gassor-orange text-white font-bold hover:bg-orange-600 transition">Bayar Ulang / Ganti Metode</button>
            </form>
            @endif
            @if($status === 'pending')
            <button onclick="window.location.reload()" class="w-full rounded-full p-[14px_20px] bg-gray-200 text-gassor-black font-bold border-2 border-gray-400 hover:bg-gray-300 hover:border-gray-600 transition flex items-center justify-center gap-2 mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582M20 20v-5h-.581M5 19A9 9 0 1 1 19 5M12 7v5l3 3" /></svg>
                Refresh Manual
            </button>
            @endif
            @if(in_array($status, ['pending','failed','expired']))
            <form action="{{ route('booking.cancel', $transaction->code) }}" method="POST" class="w-full mt-2">
                @csrf
                <button type="submit" class="w-full rounded-full p-[14px_20px] bg-white text-red-600 font-bold border-2 border-red-500 hover:bg-red-500 hover:text-white transition flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    Batalkan Pesanan
                </button>
            </form>
            @endif
            <a href="{{ route('check-booking') }}" class="w-full rounded-full p-[14px_20px] text-center font-bold text-white bg-gassor-black">Cek Status Pesanan</a>
        </div>
    </div>
</div>
@if($status === 'pending')
<script>
    setTimeout(function() {
        window.location.reload();
    }, 5000);
</script>
@endif
@endsection
