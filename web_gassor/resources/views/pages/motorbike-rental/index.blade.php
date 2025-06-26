@extends('layouts.app')

@section('content')
<div id="Background"
    class="absolute top-0 w-full h-[570px] rounded-b-[75px] bg-[linear-gradient(180deg,#F2F9E6_0%,#D2EDE4_100%)]"></div>
<div id="TopNav" class="relative flex items-center justify-between px-5 mt-[60px]">
    <a href="{{ route('find-motor') }}"
        class="w-12 h-12 flex items-center justify-center shrink-0 rounded-full overflow-hidden bg-white">
        <img src="{{ asset('assets/images/icons/arrow-left.svg') }}" class="w-[28px] h-[28px]" alt="icon">
    </a>
    <p class="font-semibold">Hasil Pencarian</p>
    <div class="dummy-btn w-12"></div>
</div>
<div id="Header" class="relative flex items-center justify-between gap-2 px-5 mt-[18px]">
    <div class="flex flex-col gap-[6px]">
        <h1 class="font-bold text-[32px] leading-[48px]">Hasil Pencarian</h1>
        <p class="text-gassor-grey">Tersedia {{ $motorcycles->count() }} Motor</p>
    </div>
</div>
<section id="Result" class=" relative flex flex-col gap-4 px-5 mt-5 mb-9">
    @foreach ($motorcycles as $motorcycle)
    <a href="{{ route('motor.show', $motorcycle->motorbikeRental->slug) }}" class="card">
        <div
            class="flex rounded-[30px] border border-[#F1F2F6] p-4 gap-4 bg-white hover:border-[#E6A43B] transition-all duration-300">
            <div class="flex w-[120px] h-[183px] shrink-0 rounded-[30px] bg-[#D9D9D9] overflow-hidden">
                @if($motorcycle->images->count() > 0)
                    <img src="{{ asset('storage/' . $motorcycle->images->first()->image) }}" class="w-full h-full object-cover"
                        alt="motorcycle image">
                @else
                    <img src="{{ asset('storage/' . $motorcycle->motorbikeRental->thumbnail) }}" class="w-full h-full object-cover"
                        alt="motorcycle image">
                @endif
            </div>
            <div class="flex flex-col gap-3 w-full">
                <h3 class="font-semibold text-lg leading-[27px] line-clamp-2 min-h-[54px]">
                    {{ $motorcycle->name }}
                </h3>
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
                    <p class="text-sm text-gassor-grey">Kategori {{ $motorcycle->motorbikeRental->category->name }}</p>
                </div>
                <div class="flex items-center gap-[6px]">
                    <img src="{{ asset('assets/images/icons/receipt-text.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon">
                    <p class="text-sm font-semibold text-gassor-orange">Rp {{ number_format($motorcycle->price_per_day, 0, ',', '.') }}/hari</p>
                </div>
                <hr class="border-[#F1F2F6]">
            </div>
        </div>
    </a>
    @endforeach
</section>
@endsection
