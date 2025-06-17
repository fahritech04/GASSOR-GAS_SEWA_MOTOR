@extends('layouts.app')

@section('content')
<div id="Content-Container"
    class="relative flex flex-col w-full max-w-[640px] min-h-screen mx-auto bg-white overflow-x-hidden">
    <div id="ForegroundFade"
        class="absolute top-0 w-full h-[143px] bg-[linear-gradient(180deg,#070707_0%,rgba(7,7,7,0)_100%)] z-10">
    </div>
    <div id="TopNavAbsolute" class="absolute top-[60px] flex items-center justify-between w-full px-5 z-10">
        <a href="{{ route('home') }}"
            class="w-12 h-12 flex items-center justify-center shrink-0 rounded-full overflow-hidden bg-white/10 backdrop-blur-sm">
            <img src="{{ asset('assets/images/icons/arrow-left-transparent.svg') }}" class="w-8 h-8" alt="icon">
        </a>
        <p class="font-semibold text-white px-6 py-2 rounded-full backdrop-blur-sm" style="background: rgba(0, 0, 0, 0.100);">Detail Motor</p>
        <button
            {{-- class="w-12 h-12 flex items-center justify-center shrink-0 rounded-full overflow-hidden bg-white/10 backdrop-blur-sm"> --}}
            class="w-12 h-12 flex items-center justify-center shrink-0 rounded-full overflow-hidden">
            {{-- <img src="{{ asset('assets/images/icons/like.svg') }}" class="w-[26px] h-[26px]" alt=""> --}}
        </button>
    </div>
    <div id="Gallery" class="swiper-gallery w-full overflow-x-hidden -mb-[38px]">
        {{-- <div class="swiper-wrapper">
            @foreach ($motorbikeRental->motorcycles as $motorcycle)
            @foreach ($motorcycle->images as $image)
            <div class="swiper-slide !w-fit">
                <div class="flex shrink-0 w-[320px] h-[430px] overflow-hidden">
                    <img src="{{ asset('storage/' . $image->image) }}" class="w-full h-full object-cover"
                        alt="gallery thumbnails">
                </div>
            </div>
            @endforeach
            @endforeach
        </div> --}}
        <div class="swiper-wrapper">
            @foreach ($motorbikeRental->motorcycles as $motorcycle)
                @foreach ($motorcycle->images as $image)
                    <div class="swiper-slide !w-fit">
                        <div class="flex shrink-0 w-[320px] h-[430px] overflow-hidden">
                            <img src="{{ asset('storage/' . $image->image) }}" class="w-full h-full object-cover"
                                alt="gallery thumbnails">
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>
    <main id="Details" class="relative flex flex-col rounded-t-[40px] py-5 pb-[10px] gap-4 bg-white z-10">
        <div id="Title" class="flex items-center justify-between gap-2 px-5">
            <h1 class="font-bold text-[22px] leading-[33px]">{{ $motorbikeRental->name }}</h1>
            {{-- <div
                class="flex flex-col items-center text-center shrink-0 rounded-[22px] border border-[#F1F2F6] p-[10px_20px] gap-2 bg-white">
                <img src="{{ asset('assets/images/icons/star.svg') }}" class="w-6 h-6" alt="icon">
                <p class="font-bold text-sm">4/5</p>
            </div> --}}
        </div>
        <hr class="border-[#F1F2F6] mx-5">
        <div id="Features" class="grid grid-cols-2 gap-x-[10px] gap-y-4 px-5">
            <div class="flex items-center gap-[6px]">
                <img src="{{ asset('assets/images/icons/location.svg') }}" class="w-[26px] h-[26px] flex shrink-0" alt="icon">
                <p class="text-gassor-grey">Wilayah {{ $motorbikeRental->city->name }}</p>
            </div>
            <div class="flex items-center gap-[6px]">
                <img src="{{ asset('assets/images/icons/3dcube.svg') }}" class="w-[26px] h-[26px] flex shrink-0" alt="icon">
                <p class="text-gassor-grey">Kategori {{ $motorbikeRental->category->name }}</p>
            </div>
            {{-- <div class="flex items-center gap-[6px]">
                <img src="{{ asset('assets/images/icons/profile-2user.svg') }}" class="w-[26px] h-[26px] flex shrink-0" alt="icon">
                <p class="text-gassor-grey">4 Orang</p>
            </div>
            <div class="flex items-center gap-[6px]">
                <img src="{{ asset('assets/images/icons/shield-tick.svg') }}" class="w-[26px] h-[26px] flex shrink-0" alt="icon">
                <p class="text-gassor-grey">Privacy 100%</p>
            </div> --}}
        </div>
        <hr class="border-[#F1F2F6] mx-5">
        <div id="About" class="flex flex-col gap-[6px] px-5">
            <h2 class="font-bold">Tentang</h2>
            <p class="leading-[30px]">{!! $motorbikeRental->description !!}</p>
        </div>
        <div id="Tabs" class="swiper-tab w-full overflow-x-hidden">
            <div class="swiper-wrapper">
                <div class="swiper-slide !w-fit">
                    <button
                        class="tab-link rounded-full p-[8px_14px] border border-[#F1F2F6] text-sm font-semibold hover:bg-gassor-black hover:text-white transition-all duration-300 !bg-gassor-black !text-white"
                        data-target-tab="#Bonus-Tab">Bonus Motor</button>
                </div>
                <div class="swiper-slide !w-fit">
                    <button
                        class="tab-link rounded-full p-[8px_14px] border border-[#F1F2F6] text-sm font-semibold hover:bg-gassor-black hover:text-white transition-all duration-300"
                        data-target-tab="#Contact-Tab">kontak</button>
                </div>
            </div>
        </div>
        <div id="TabsContent" class="px-5">
            <div id="Bonus-Tab" class="tab-content flex flex-col gap-5">
                <div class="flex flex-col gap-4">
                    @foreach ($motorbikeRental->bonuses as $bonus)
                    <div
                        class="bonus-card flex items-center rounded-[22px] border border-[#F1F2F6] p-[10px] gap-3 hover:border-[#E6A43B] transition-all duration-300">
                        <div class="flex w-[120px] h-[90px] shrink-0 rounded-[18px] bg-[#D9D9D9] overflow-hidden">
                            <img src="{{ asset('storage/' . $bonus->image) }}" class="w-full h-full object-cover"
                                alt="thumbnails">
                        </div>
                        <div>
                            <p class="font-semibold">{{ $bonus->name }}</p>
                            <p class="text-sm text-gassor-grey">{{ $bonus->description }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div id="Contact-Tab" class="tab-content flex flex-col gap-5 hidden">
                <div class="flex flex-col gap-4">
                    @foreach ($motorbikeRental->contacts ?? [$motorbikeRental] as $contact)
                    <div
                        onclick="window.open('https://wa.me/{{ preg_replace('/[^0-9]/', '', $contact->contact) }}', '_blank')"
                        class="bonus-card flex items-center rounded-[22px] border border-[#F1F2F6] p-[10px] gap-3 hover:border-[#25D366] transition-all duration-300 cursor-pointer"
                        style="cursor:pointer;"
                    >
                        <div class="flex w-[120px] h-[90px] shrink-0 rounded-[18px] bg-[#ffffff] overflow-hidden items-center justify-center">
                            <img src="{{ asset('assets/images/icons/whatsapp.svg') }}" class="w-12 h-12" alt="WhatsApp">
                        </div>
                        <div>
                            <p class="font-semibold">WhatsApp -
                                @php
                                    $owners = $motorbikeRental->owners;
                                    $ownerNames = $owners->pluck('name')->unique()->implode(', ');
                                @endphp
                                {{ $ownerNames }}
                            </p>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contact->contact) }}" target="_blank" class="text-orange-600 font-bold hover:underline flex items-center gap-1">
                                {{ $contact->contact }}
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </main>
    <div id="BottomNav" class="relative flex w-full h-[138px] shrink-0">
        <div class="fixed bottom-5 w-full max-w-[640px] px-5 z-10">
            {{-- <div class="flex items-center justify-between rounded-[40px] py-4 px-6 bg-gassor-black">
                <p class="font-bold text-xl leading-[30px] text-white">
                    Rp {{ number_format($motorbikeRental->price, 0, ',', '.') }}
                    <br>
                    <span class="text-sm font-normal">/hari</span>
                </p>
            </div> --}}
            <a href="{{ route('motor.motorcycles', $motorbikeRental->slug ) }}"
                class="flex shrink-0 rounded-full py-[14px] px-5 bg-gassor-orange font-bold text-white">Pesan Sekarang</a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/details.js') }}"></script>
@endsection
