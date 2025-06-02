@extends('layouts.app')

@section('content')
<div id="Background"
    class="absolute top-0 w-full h-[230px] rounded-b-[75px] bg-[linear-gradient(180deg,#F2F9E6_0%,#D2EDE4_100%)]">
</div>
<div id="TopNav" class="relative flex items-center justify-between px-5 mt-[60px]">
    <a href="{{ route('motor.motorcycles', $motorbikeRental->slug) }}"
        class="w-12 h-12 flex items-center justify-center shrink-0 rounded-full overflow-hidden bg-white">
        <img src="{{ asset('assets/images/icons/arrow-left.svg') }}" class="w-[28px] h-[28px]" alt="icon">
    </a>
    <p class="font-semibold">Informasi Pelanggan</p>
    <div class="dummy-btn w-12"></div>
</div>
<div id="Header" class="relative flex items-center justify-between gap-2 px-5 mt-[18px]">
    <div class="flex flex-col w-full rounded-[30px] border border-[#F1F2F6] p-4 gap-4 bg-white">
        <div class="flex gap-4">
            <div class="flex w-[120px] h-[132px] shrink-0 rounded-[30px] bg-[#D9D9D9] overflow-hidden">
                <img src="{{ asset('storage/' . $motorbikeRental->thumbnail) }}" class="w-full h-full object-cover"
                    alt="icon">
            </div>
            <div class="flex flex-col gap-3 w-full">
                <p class="font-semibold text-lg leading-[27px] line-clamp-2 min-h-[54px]">
                    {{ $motorbikeRental->name }}
                </p>
                <hr class="border-[#F1F2F6]">
                <div class="flex items-center gap-[6px]">
                    <img src="{{ asset('assets/images/icons/location.svg') }}" class="w-5 h-5 flex shrink-0"
                        alt="icon">
                    <p class="text-sm text-gassor-grey">Wilayah {{ $motorbikeRental->city->name }}</p>
                </div>
                <div class="flex items-center gap-[6px]">
                    <img src="{{ asset('assets/images/icons/profile-2user.svg') }}" class="w-5 h-5 flex shrink-0"
                        alt="icon">
                    <p class="text-sm text-gassor-grey">Kategori {{ $motorbikeRental->category->name }}</p>
                </div>
            </div>
        </div>
        <hr class="border-[#F1F2F6]">
        <div class="flex gap-4">
            <div class="flex w-[120px] h-[156px] shrink-0 rounded-[30px] bg-[#D9D9D9] overflow-hidden">
                <img src="{{ asset('storage/' . $motorcycle->images->first()->image) }}" class="w-full h-full object-cover" alt="icon">
            </div>
            <div class="flex flex-col gap-3 w-full">
                <p class="font-semibold text-lg leading-[27px]">{{ $motorcycle->name }}</p>
                <hr class="border-[#F1F2F6]">
                {{-- <div class="flex items-center gap-[6px]">
                    <img src="{{ asset('assets/images/icons/profile-2user.svg') }}" class="w-5 h-5 flex shrink-0"
                        alt="icon">
                    <p class="text-sm text-gassor-grey">{{ $motorcycle->capacity }} Orang</p>
                </div> --}}
                <hr class="border-[#F1F2F6]">
                <p class="font-semibold text-lg text-gassor-orange">Rp
                    {{ number_format($motorcycle->price_per_day, 0, ',', '.') }}<span
                        class="text-sm text-gassor-grey font-normal">/hari</span>
                </p>
            </div>
        </div>
    </div>
</div>
<form action="{{ route('booking.information.save', $motorbikeRental->slug) }}"
    class="relative flex flex-col gap-6 mt-5 pt-5 bg-[#F5F6F8]" method="POST">
    @csrf
    <div class="flex flex-col gap-[6px] px-5">
        <h1 class="font-semibold text-lg">Informasi Kamu</h1>
        <p class="text-sm text-gassor-grey">Isi kolom di bawah ini dengan data Anda yang valid</p>
    </div>
    <div id="InputContainer" class="flex flex-col gap-[18px]">
        <div class="flex flex-col w-full gap-2 px-5">
            <p class="font-semibold">Nama Lengkap</p>
            <label
                class="flex items-center w-full rounded-full p-[14px_20px] gap-3 bg-white focus-within:ring-1 focus-within:ring-[#91BF77] transition-all duration-300 @error('name') border-red-500 @enderror">
                <img src="{{ asset('assets/images/icons/profile-2user.svg') }}" class="w-5 h-5 flex shrink-0"
                    alt="icon">
                <input type="text" name="name" id=""
                    class="appearance-none outline-none w-full font-semibold placeholder:text-gassor-grey placeholder:font-normal"
                    placeholder="Ketik nama kamu" value="{{ old('name', Auth::user() ? Auth::user()->name : '') }}">
            </label>
            @error('name')
            <p class="text-sm" style="color: red;">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex flex-col w-full gap-2 px-5">
            <p class="font-semibold">Email</p>
            <label
                class="flex items-center w-full rounded-full p-[14px_20px] gap-3 bg-white focus-within:ring-1 focus-within:ring-[#91BF77] transition-all duration-300 @error('email') border-red-500 @enderror">
                <img src="{{ asset('assets/images/icons/sms.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon">
                <input type="email" name="email" id=""
                    class="appearance-none outline-none w-full font-semibold placeholder:text-gassor-grey placeholder:font-normal"
                    placeholder="Ketik email kamu" value="{{ old('email', Auth::user() ? Auth::user()->email : '') }}">
            </label>
            @error('email')
            <p class="text-sm" style="color: red;">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex flex-col w-full gap-2 px-5">
            <p class="font-semibold">Nomor Telepon</p>
            <label
                class="flex items-center w-full rounded-full p-[14px_20px] gap-3 bg-white focus-within:ring-1 focus-within:ring-[#91BF77] transition-all duration-300 @error('phone') border-red-500 @enderror">
                <img src="{{ asset('assets/images/icons/call.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon">
                <input type="tel" name="phone_number" id=""
                    class="appearance-none outline-none w-full font-semibold placeholder:text-gassor-grey placeholder:font-normal"
                    placeholder="Ketik nomor telepon kamu" value="{{ old('phone_number', Auth::user() ? Auth::user()->phone : '') }}">
            </label>
            @error('phone_number')
            <p class="text-sm" style="color: red;">{{ $message }}</p>
            @enderror
        </div>
        {{-- <div class="flex items-center justify-between px-5">
            <p class="font-semibold">Durasi dalam Hari</p>
            <div class="relative flex items-center gap-[10px] w-fit">
                <button type="button" id="Minus" class="w-12 h-12 flex-shrink-0">
                    <img src="{{ asset('assets/images/icons/minus.svg') }}" alt="icon">
                </button>
                <input id="Duration" type="text" value="1" name="duration"
                    class="appearance-none outline-none !bg-transparent w-[42px] text-center font-semibold text-[22px] leading-[33px]"
                    inputmode="numeric" pattern="[0-9]*" autocomplete="off">
                    <div class="relative flex items-center gap-[10px] w-fit">
                        <input id="Duration" type="hidden" value="1" name="duration">
                        <span class="font-semibold text-[22px] leading-[33px]">1</span>
                        <span class="ml-1">hari</span>
                    </div>
                <button type="button" id="Plus" class="w-12 h-12 flex-shrink-0">
                    <img src="{{ asset('assets/images/icons/plus.svg') }}" alt="icon">
                </button>
            </div>
        </div> --}}
        <div class="flex items-center px-5">
            <p class="font-semibold">Durasi sewa hanya bisa dalam : </p>
            <div class="flex items-center gap-2">
                <input id="Duration" type="hidden" value="1" name="duration">
                <span class="font-semibold text-[22px] leading-[33px]"> 1</span>
                <span class="ml-1">hari</span>
            </div>
        </div>
        <div class="flex flex-col gap-2">
            <p class="font-semibold px-5">Pilih Tanggal & Jam Sewa</p>
            <div class="swiper w-full overflow-x-hidden">
                <div class="swiper-wrapper select-dates">
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-2 px-5 mt-2">
                <div class="flex flex-col w-full md:w-1/2">
                    <label class="font-semibold mb-1">Jam Mulai</label>
                    <input type="time" name="start_time" id="start_time" class="appearance-none outline-none w-full font-semibold rounded-full p-[14px_20px] bg-white border border-[#F1F2F6] focus:ring-1 focus:ring-[#91BF77] transition-all duration-300" required>
                </div>
                <div class="flex flex-col w-full md:w-1/2">
                    <label class="font-semibold mb-1">Jam Selesai (Otomatis 24 Jam)</label>
                    <input type="time" name="end_time" id="end_time" class="appearance-none outline-none w-full font-semibold rounded-full p-[14px_20px] bg-white border border-[#F1F2F6] focus:ring-1 focus:ring-[#91BF77] transition-all duration-300" readonly>
                </div>
            </div>
        </div>
    </div>
    <div id="BottomNav" class="relative flex w-full h-[132px] shrink-0 bg-white">
        <div class="fixed bottom-5 w-full max-w-[640px] px-5 z-10">
            <div class="flex items-center justify-between rounded-[40px] py-4 px-6 bg-gassor-black">
                <div class="flex flex-col gap-[2px]">
                    <p id="price" class="font-bold text-xl leading-[30px] text-white">
                        <!-- price dari js -->
                    </p>
                    <span class="text-sm text-white">Total Keseluruhan</span>
                </div>
                <button type="submit"
                    class="flex shrink-0 rounded-full py-[14px] px-5 bg-gassor-orange font-bold text-white">Bayar Sekarang</button>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    const defaultPrice = parseFloat('{{ $motorcycle->price_per_day }}');
</script>
<script src="{{ asset('assets/js/cust-info.js') }}"></script>
@endsection
