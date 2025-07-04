@extends('layouts.app')

@section('content')
<div style="position: absolute; top: 0; width: 100%; height: 570px; border-bottom-left-radius: 75px; border-bottom-right-radius: 75px; background: linear-gradient(180deg, #e6a43b 0%, #e6a43b 100%)"></div>
<div id="TopNav" class="relative flex items-center justify-between px-5 mt-[60px]">
    <a href="{{ route('home') }}"
        class="w-12 h-12 flex items-center justify-center shrink-0 rounded-full overflow-hidden bg-white">
        <img src="{{ asset('assets/images/icons/arrow-left.svg') }}" class="w-[28px] h-[28px]" alt="icon">
    </a>
    <p class="font-semibold">Motor Sesuai Wilayah</p>
    <div class="dummy-btn w-12"></div>
</div>
<div id="Header" class="relative flex items-center justify-between gap-2 px-5 mt-[18px]">
    <div class="flex flex-col gap-[6px]">
        <h1 class="font-bold text-[32px] leading-[48px]">Wilayah {{ $city->name }}</h1>
        <p class="text-gassor-grey">Tersedia {{ $motorcycles->count() }} Motor</p>
    </div>
</div>
<div style="position: relative; padding: 0 20px; margin-top: 20px;">
    <form method="GET" action="{{ request()->url() }}" id="filterForm">
        <div style="background: #ffffff; border-radius: 16px; padding: 20px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border: 1px solid #f0f0f0;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                <label for="category_filter" style="font-size: 14px; font-weight: 600; color: #374151; margin: 0;">
                    Cari Motor di Wilayah Ini
                </label>
                @if(request('category_id') || request('search'))
                    <a href="{{ route('city.show', $city->slug) }}" style="font-size: 12px; color: #e6a43b; font-weight: 500; text-decoration: none; padding: 4px 8px; border-radius: 6px; background: #fef3e2; transition: all 0.2s;">
                        Reset Filter
                    </a>
                @endif
            </div>
            <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama motor..." style="flex:2; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 14px; color: #374151; background: #ffffff; outline: none; transition: all 0.3s; min-width:180px;" />
                <button type="submit" id="filterButton"
                        style="padding: 12px 20px; background: #e6a43b; color: #ffffff; border: none; border-radius: 12px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.3s; box-shadow: 0 2px 4px rgba(230, 164, 59, 0.2); white-space: nowrap; min-width:90px;"
                        onmouseover="this.style.background='#d4932f'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(230, 164, 59, 0.3)';"
                        onmouseout="this.style.background='#e6a43b'; this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(230, 164, 59, 0.2)';">
                    Cari
                </button>
            </div>
            @if(request('category_id') || request('search'))
                <div style="margin-top: 12px; padding: 8px 12px; background: #f9fafb; border-radius: 8px;">
                    <p style="font-size: 12px; color: #6b7280; margin: 0;">
                        @if(request('category_id'))
                        Menampilkan motor dengan kategori: <span style="font-weight: 600; color: #e6a43b;">{{ ucfirst(optional(\App\Models\Category::find(request('category_id')))->name) }}</span>
                        @endif
                        @if(request('search'))
                        @if(request('category_id'))<br>@endif
                        Pencarian: <span style="font-weight: 600; color: #e6a43b;">{{ request('search') }}</span>
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </form>
</div>
<section id="Result" class=" relative flex flex-col gap-4 px-5 mt-5 mb-9">
    @foreach ($motorcycles as $motorcycle)
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
    @endforeach
</section>
@endsection
