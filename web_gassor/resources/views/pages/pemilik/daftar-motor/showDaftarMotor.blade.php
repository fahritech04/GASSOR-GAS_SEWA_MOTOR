@extends('layouts.app')

@section('content')
<div id="Background"
    class="absolute top-0 w-full h-[570px] rounded-b-[75px]"
    style="background: linear-gradient(180deg, #e6a43b 0%, #e6a43b 100%);">
</div>
<div id="Header" class="relative flex items-center justify-between gap-2 px-5 mt-[18px]">
    <div class="flex flex-col gap-[6px]">
        <h1 class="font-bold text-[32px] leading-[48px]">Daftar Motor Anda</h1>
        <p class="text-gassor-grey">Tersedia {{ $motorcycles->count() }} Motor</p>
    </div>
</div>
<section id="Result" class="relative flex flex-col gap-4 px-5 mt-5 mb-9">
    <h2 class="font-bold text-lg mb-2">Motor yang dimiliki {{ auth()->user()->name }}</h2>
    @forelse ($motorcycles as $motorcycle)
        <div class="card flex rounded-[30px] border border-[#F1F2F6] p-4 gap-4 bg-white hover:border-[#E6A43B] transition-all duration-300">
            <div class="flex w-[120px] h-[90px] shrink-0 rounded-[18px] bg-[#D9D9D9] overflow-hidden">
                <img src="{{ asset('storage/' . ($motorcycle->images->first()->image ?? 'default.png')) }}" class="object-cover w-full h-full" alt="icon">
            </div>
            <div class="flex flex-col gap-2 w-full">
                <h3 class="font-semibold text-lg leading-[27px] line-clamp-2 min-h-[27px]">{{ $motorcycle->name }}</h3>
                <div class="flex items-center gap-[6px]">
                    <img src="{{ asset('assets/images/icons/status.svg') }}" class="w-4 h-4" alt="status" />
                    <span class="text-sm text-gassor-grey">{{ $motorcycle->is_available ? 'Tersedia' : 'Tidak Tersedia' }}</span>
                </div>
                <p class="text-sm text-gassor-grey">Harga • Rp {{ number_format($motorcycle->price_per_day, 0, ',', '.') }}/hari</p>
            </div>
        </div>
    @empty
        <p class="text-center text-gassor-grey">Belum ada motor terdaftar.</p>
    @endforelse
</section>

@include('includes.navigation_pemilik')
@endsection
