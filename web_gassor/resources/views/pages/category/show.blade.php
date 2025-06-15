@extends('layouts.app')

@section('content')
<div style="position: absolute; top: 0; width: 100%; height: 570px; border-bottom-left-radius: 75px; border-bottom-right-radius: 75px; background: linear-gradient(180deg, #e6a43b 0%, #e6a43b 100%)"></div>
<div id="TopNav" class="relative flex items-center justify-between px-5 mt-[60px]">
    <a href="{{ route('home') }}"
        class="w-12 h-12 flex items-center justify-center shrink-0 rounded-full overflow-hidden bg-white">
        <img src="{{ asset('assets/images/icons/arrow-left.svg') }}" class="w-[28px] h-[28px]" alt="icon">
    </a>
    <p class="font-semibold">Jenis Motor Yang Ada</p>
    <div class="dummy-btn w-12"></div>
</div>
<div id="Header" class="relative flex items-center justify-between gap-2 px-5 mt-[18px]">
    <div class="flex flex-col gap-[6px]">
        <h1 class="font-bold text-[32px] leading-[48px]">Motor {{ $category->name }}</h1>
        <p class="text-gassor-grey">Tersedia {{ $category->motorbikeRentals->count() }} Motor</p>
    </div>
    {{-- <button class="flex flex-col items-center text-center shrink-0 rounded-[22px] p-[10px_20px] gap-2 bg-white">
        <img src="{{ asset('assets/images/icons/star.svg') }}" class="w-6 h-6" alt="icon">
        <p class="font-bold text-sm">4/5</p>
    </button> --}}
</div>
<section id="Result" class=" relative flex flex-col gap-4 px-5 mt-5 mb-9">
    @foreach ($motorbikeRentals as $motorbikeRental)
    <a href="{{ route('motor.show', $motorbikeRental->slug) }}" class="card">
        <div
            class="flex rounded-[30px] border border-[#F1F2F6] p-4 gap-4 bg-white hover:border-[#91BF77] transition-all duration-300">
            <div class="flex w-[120px] h-[183px] shrink-0 rounded-[30px] bg-[#D9D9D9] overflow-hidden">
                <img src="{{ asset('storage/' . $motorbikeRental->thumbnail ) }}" class="w-full h-full object-cover" alt="icon">
            </div>
            <div class="flex flex-col gap-3 w-full">
                <h3 class="font-semibold text-lg leading-[27px] line-clamp-2 min-h-[54px]">{{ $motorbikeRental->name }}</h3>
                <hr class="border-[#F1F2F6]">
                <div class="flex items-center gap-[6px]">
                    <img src="{{ asset('assets/images/icons/location.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon">
                    <p class="text-sm text-gassor-grey">{{ $motorbikeRental->city->name }}</p>
                </div>
                <div class="flex items-center gap-[6px]">
                    <img src="{{ asset('assets/images/icons/3dcube.svg') }}" class="w-5 h-5 flex shrink-0"
                        alt="icon">
                    <p class="text-sm text-gassor-grey">Kategori {{ $motorbikeRental->category->name }}</p>
                </div>
                <hr class="border-[#F1F2F6]">
            </div>
        </div>
    </a>
    @endforeach
</section>
@endsection
