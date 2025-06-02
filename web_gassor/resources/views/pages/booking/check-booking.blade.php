@extends('layouts.app')

@section('content')
<div id="Background"
    class="absolute top-0 w-full h-[430px] rounded-b-[75px] bg-[linear-gradient(180deg,#F2F9E6_0%,#D2EDE4_100%)]">
</div>
<div class="relative flex flex-col gap-[30px] my-[60px] px-5">
    <h1 class="font-bold text-[30px] leading-[45px] text-center">Periksa Rincian<br>Pemesanan Anda</h1>
    {{-- <form action="{{ route('check-booking.show') }}" class="flex flex-col rounded-[30px] border border-[#F1F2F6] p-5 gap-6 bg-white" method="POST">
        @csrf
        <div class="flex flex-col gap-[6px]">
            <h1 class="font-semibold text-lg">Informasi Anda</h1>
            <p class="text-sm text-gassor-grey">Isi kolom di bawah ini dengan data Anda yang valid</p>
        </div>
        <div id="InputContainer" class="flex flex-col gap-[18px]">
            <div class="flex flex-col w-full gap-2">
                <p class="font-semibold">ID Pemesanan</p>
                <label
                    class="flex items-center w-full rounded-full p-[14px_20px] gap-3 bg-white ring-1 ring-[#F1F2F6] focus-within:ring-[#91BF77] transition-all duration-300 @error('code') border-red-500 @enderror">
                    <img src="assets/images/icons/note-favorite-grey.svg" class="w-5 h-5 flex shrink-0"
                        alt="icon">
                    <input type="text" name="code" id=""
                        class="appearance-none outline-none w-full font-semibold placeholder:text-gassor-grey placeholder:font-normal"
                        placeholder="Ketik ID pemesanan kamu" value="{{ old('code') }}">
                </label>
                @error('code')
                <p class="text-sm" style="color: red;">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex flex-col w-full gap-2">
                <p class="font-semibold">Alamat Email</p>
                <label
                    class="flex items-center w-full rounded-full p-[14px_20px] gap-3 bg-white ring-1 ring-[#F1F2F6] focus-within:ring-[#91BF77] transition-all duration-300 @error('code') border-red-500 @enderror">
                    <img src="assets/images/icons/sms.svg" class="w-5 h-5 flex shrink-0" alt="icon">
                    <input type="email" name="email" id=""
                        class="appearance-none outline-none w-full font-semibold placeholder:text-gassor-grey placeholder:font-normal"
                        placeholder="Ketik email kamu">
                </label>
                @error('email')
                <p class="text-sm" style="color: red;">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex flex-col w-full gap-2">
                <p class="font-semibold">Nomor Telepon</p>
                <label
                    class="flex items-center w-full rounded-full p-[14px_20px] gap-3 bg-white ring-1 ring-[#F1F2F6] focus-within:ring-[#91BF77] transition-all duration-300 @error('code') border-red-500 @enderror">
                    <img src="assets/images/icons/call.svg" class="w-5 h-5 flex shrink-0" alt="icon">
                    <input type="tel" name="phone_number" id=""
                        class="appearance-none outline-none w-full font-semibold placeholder:text-gassor-grey placeholder:font-normal"
                        placeholder="Ketik nomor telepon kamu">
                </label>
                @error('phone_number')
                <p class="text-sm" style="color: red;">{{ $message }}</p>
                @enderror
            </div>

            @if (session('error'))
            <p class="text-center" style="color: red;">{{ session('error') }}</p>
            @endif

            <button type="submit"
                class="flex w-full justify-center rounded-full p-[14px_20px] bg-gassor-orange font-bold text-white">Lihat Pesanan Saya</button>
        </div>
    </form> --}}
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
                            Tanggal: {{ \Carbon\Carbon::parse($transaction->start_date)->isoFormat('D MMMM YYYY') }}
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
