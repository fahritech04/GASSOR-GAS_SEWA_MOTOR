@extends('layouts.app')

@section('content')
<div style="position: absolute; top: 0; width: 100%; height: 230px; border-bottom-left-radius: 75px; border-bottom-right-radius: 75px; background: linear-gradient(180deg, #e6a43b 0%, #e6a43b 100%)"></div>
<div id="TopNav" class="relative flex items-center justify-between px-5 mt-[60px]">
    <div class="flex flex-col gap-1">
        <p>
            Halo,
            @php
                $hour = now()->setTimezone('Asia/Jakarta')->format('H');
                if ($hour >= 5 && $hour < 12) {
                    $greeting = 'selamat pagi';
                } elseif ($hour >= 12 && $hour < 15) {
                    $greeting = 'selamat siang';
                } elseif ($hour >= 15 && $hour < 18) {
                    $greeting = 'selamat sore';
                } else {
                    $greeting = 'selamat malam';
                }
            @endphp
            {{ $greeting }}
        </p>
        <h1 class="font-bold text-xl leading-[30px]">
            @auth
                @if (empty(Auth::user()->name))
                    Pengguna GASSOR
                @else
                    {{ Auth::user()->name }}
                @endif
            @else
                Pengguna GASSOR
            @endauth
        </h1>
    </div>

    <div class="flex items-center gap-2">
        <a href="https://wa.me/6285942572814" target="_blank" rel="noopener" class="flex items-center justify-center w-12 h-12 overflow-hidden bg-white rounded-full shrink-0">
            <img src="{{ asset('assets/images/icons/whatsapp.svg') }}" class="w-[28px] h-[28px]" alt="contact person" />
        </a>
        @auth
        <a href="{{ route('profile.penyewa') }}"
            class="w-12 h-12 flex items-center justify-center shrink-0 rounded-full overflow-hidden bg-white">
            <img src="{{ asset('assets/images/icons/profile-2user.svg') }}" class="w-[28px] h-[28px]" alt="Profile">
        </a>
        @endauth
    </div>
</div>
<div id="Categories" class="swiper w-full overflow-x-hidden mt-[30px]">
    <div class="swiper-wrapper">

        @foreach ($categories as $category)
        <div class="swiper-slide !w-fit pb-[30px]">
            <a href="{{ route('category.show', $category->slug) }}" class="card">
                <div
                    class="flex flex-col items-center w-[120px] shrink-0 rounded-[40px] p-4 pb-5 gap-3 bg-white shadow-[0px_12px_30px_0px_#0000000D] text-center">
                    <div class="w-[70px] h-[70px] rounded-full flex shrink-0 overflow-hidden">
                        <img src="{{ asset('storage/' . $category->image) }}" class="w-full h-full object-cover"
                            alt="thumbnail">
                    </div>
                    <div class="flex flex-col gap-[2px]">
                        <h3 class="font-semibold">{{ $category->name }}</h3>
                        <p class="text-sm text-gassor-grey">{{ $category->motorcycles_count }} Motor</p>
                    </div>
                </div>
            </a>
        </div>
        @endforeach

    </div>
</div>
<section id="Popular" class="flex flex-col gap-4">
    <div class="flex items-center justify-between px-5">
        <h2 class="font-bold">Rental Populer</h2>
    </div>
    <div class="swiper w-full overflow-x-hidden">
        <div class="swiper-wrapper">
            @forelse ($popularMotorbikeRentals as $motorbikeRental)
            <div class="swiper-slide !w-fit">
                <a href="{{ route('motor.show', $motorbikeRental->slug) }}" class="card">
                    <div
                        class="flex flex-col w-[250px] shrink-0 rounded-[30px] border border-[#F1F2F6] p-4 pb-5 gap-[10px] hover:border-[#E6A43B] transition-all duration-300">
                        <div class="flex w-full h-[150px] shrink-0 rounded-[30px] bg-[#D9D9D9] overflow-hidden">
                            <img src="{{ asset('storage/' . $motorbikeRental->thumbnail) }}" class="w-full h-full object-cover"
                                alt="thumbnail">
                        </div>
                        <div class="flex flex-col gap-3">
                            <h3 class="font-semibold text-lg leading-[27px] line-clamp-2 min-h-[54px]">{{ $motorbikeRental->name }}</h3>
                            <hr class="border-[#F1F2F6]">
                            <div class="flex items-center gap-[6px]">
                                <img src="{{ asset('assets/images/icons/location.svg') }}" class="w-5 h-5 flex shrink-0"
                                    alt="icon">
                                <p class="text-sm text-gassor-grey">{{ $motorbikeRental->city->name }}</p>
                            </div>
                            <hr class="border-[#F1F2F6]">
                        </div>
                    </div>
                </a>
            </div>
            @empty
            <div class="w-full px-5">
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <img src="{{ asset('assets/images/icons/note-favorite-grey.svg') }}" class="w-16 h-16 mb-4 opacity-50" alt="empty">
                    <p class="text-gassor-grey font-medium">Rental populer belum tersedia</p>
                    <p class="text-sm text-gassor-grey mt-1">Silakan coba lagi nanti</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>
<section id="Cities" class="flex flex-col p-5 gap-4 bg-[#F5F6F8] mt-[30px]">
    <div class="flex items-center justify-between">
        <h2 class="font-bold">Sesuai Wilayah</h2>
    </div>
    <div class="grid grid-cols-2 gap-4">
        @foreach ($cities as $city)
        <a href="{{ route('city.show', $city->slug) }}" class="card">
            <div
                class="flex items-center rounded-[22px] p-[10px] gap-3 bg-white border border-white overflow-hidden hover:border-[#E6A43B] transition-all duration-300">
                <div
                    class="w-[55px] h-[55px] flex shrink-0 rounded-full border-4 border-white ring-1 ring-[#F1F2F6] overflow-hidden">
                    <img src="{{ asset('storage/' . $city->image) }}" class="w-full h-full object-cover"
                        alt="icon">
                </div>
                <div class="flex flex-col gap-[2px]">
                    <h3 class="font-semibold">{{ $city->name }}</h3>
                    <p class="text-sm text-gassor-grey">{{ $city->motorcycles_count }} Motor</p>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</section>
<section id="Best" class="flex flex-col gap-4 px-5 mt-[30px]">
    <div class="flex items-center justify-between">
        <h2 class="font-bold">Semua Jenis Motor</h2>
    </div>
    <div class="flex flex-col gap-4">
        @forelse ($motorcycles as $motorcycle)
        <a href="{{ route('motor.show', $motorcycle->motorbikeRental->slug) }}" class="card">
            <div
                class="flex rounded-[30px] border border-[#F1F2F6] p-4 gap-4 bg-white hover:border-[#E6A43B] transition-all duration-300">
                <div class="flex w-[120px] h-[183px] shrink-0 rounded-[30px] bg-[#D9D9D9] overflow-hidden">
                    @if($motorcycle->images->count() > 0)
                        <img src="{{ asset('storage/' . $motorcycle->images->first()->image) }}" class="w-full h-full object-cover" alt="motorcycle image">
                    @else
                        <img src="{{ asset('storage/' . $motorcycle->motorbikeRental->thumbnail) }}" class="w-full h-full object-cover" alt="motorcycle image">
                    @endif
                </div>
                <div class="flex flex-col gap-3 w-full">
                    <h3 class="font-semibold text-lg leading-[27px] line-clamp-2 min-h-[54px]">{{ $motorcycle->name }}</h3>
                    <hr class="border-[#F1F2F6]">
                    <div class="flex items-center gap-[6px]">
                        <img src="{{ asset('assets/images/icons/3dcube.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon">
                        <p class="text-sm text-gassor-grey">Rental : {{ $motorcycle->motorbikeRental->name }}</p>
                    </div>
                    <div class="flex items-center gap-[6px]">
                        <img src="{{ asset('assets/images/icons/location.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon">
                        <p class="text-sm text-gassor-grey">Wilayah {{ $motorcycle->motorbikeRental->city->name }}</p>
                    </div>
                    <div class="flex items-center gap-[6px]">
                        <img src="{{ asset('assets/images/icons/3dcube.svg') }}" class="w-5 h-5 flex shrink-0"
                            alt="icon">
                        <p class="text-sm text-gassor-grey">Kategori {{ $motorcycle->category->name }}</p>
                    </div>
                    <div class="flex items-center gap-[6px]">
                        <img src="{{ asset('assets/images/icons/receipt-text.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon">
                        <p class="text-sm font-semibold text-gassor-orange">Rp {{ number_format($motorcycle->price_per_day, 0, ',', '.') }}/hari</p>
                    </div>
                    <hr class="border-[#F1F2F6]">
                </div>
            </div>
        </a>
        @empty
        <div class="flex flex-col items-center justify-center py-12 text-center">
            <img src="{{ asset('assets/images/icons/3dcube.svg') }}" class="w-16 h-16 mb-4 opacity-50" alt="empty">
            <p class="text-gassor-grey font-medium">Motor belum tersedia</p>
            <p class="text-sm text-gassor-grey mt-1">Silakan coba lagi nanti</p>
        </div>
        @endforelse
    </div>
</section>

@include('includes.navigation')
@endsection
