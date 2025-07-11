@extends('layouts.app')

@section('content')
<div id="Background"
    class="absolute top-0 w-full h-[230px] rounded-b-[75px] bg-[linear-gradient(180deg,#F2F9E6_0%,#D2EDE4_100%)]">
</div>
<div id="TopNav" class="relative flex items-center justify-between px-5 mt-[60px]">
    <a href="{{ route('motor.show', $motorbikeRental->slug) }}"
        class="w-12 h-12 flex items-center justify-center shrink-0 rounded-full overflow-hidden bg-white">
        <img src="{{ asset('assets/images/icons/arrow-left.svg') }}" class="w-[28px] h-[28px]" alt="icon">
    </a>
    <p class="font-semibold">Pilih Sepeda Motor yang Tersedia</p>
    <div class="dummy-btn w-12"></div>
</div>
<div id="Header" class="relative flex items-center justify-between gap-2 px-5 mt-[18px]">
    <div class="flex w-full rounded-[30px] border border-[#F1F2F6] p-4 gap-4 bg-white">
        <div class="flex w-[120px] h-[132px] shrink-0 rounded-[30px] bg-[#D9D9D9] overflow-hidden">
            <img src="{{ asset('storage/' . $motorbikeRental->thumbnail) }}" class="w-full h-full object-cover" alt="icon">
        </div>
        <div class="flex flex-col gap-3 w-full">
            <h1 class="font-semibold text-lg leading-[27px] line-clamp-2 min-h-[54px]">{{ $motorbikeRental->name }}</h1>
            <hr class="border-[#F1F2F6]">
            <div class="flex items-center gap-[6px]">
                <img src="{{ asset('assets/images/icons/location.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon">
                <p class="text-sm text-gassor-grey">Wilayah {{ $motorbikeRental->city->name }}</p>
            </div>
            <div class="flex items-center gap-[6px]">
                <img src="{{ asset('assets/images/icons/3dcube.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon">
                @if($motorbikeRental->hasMultipleCategories())
                    <p class="text-sm text-gassor-grey">Kategori Beragam</p>
                @else
                    <p class="text-sm text-gassor-grey">Kategori {{ $motorbikeRental->getPredominantCategory()?->name ?? 'Tidak Ada' }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
<form action="{{ route('booking', $motorbikeRental->slug) }}" class="relative flex flex-col gap-4 mt-5">
    <input type="hidden" name="motorbike_rental_id" value="{{ $motorbikeRental->id }}">
    <h2 class="font-bold px-5">Motor yang Tersedia</h2>
    <div id="MotorcyclesContainer" class="flex flex-col gap-4 px-5">
        @if($motorbikeRental->motorcycles->isEmpty())
            <p class="text-center text-gassor-grey">Motor tidak tersedia, silahkan cek kembali nanti.</p>
        @else
            @foreach ($motorbikeRental->motorcycles as $motorcycle)
            <label class="relative group">
                <input type="radio" name="motorcycle_id" class="absolute top-1/2 left-1/2 -z-10 opacity-0" value="{{ $motorcycle->id }}" required>
                <div
                    class="flex rounded-[30px] border border-[#F1F2F6] p-4 gap-4 bg-white hover:border-[#E6A43B] group-has-[:checked]:ring-2 group-has-[:checked]:ring-[#E6A43B] transition-all duration-300">
                    <div class="flex w-[120px] h-[156px] shrink-0 rounded-[30px] bg-[#D9D9D9] overflow-hidden">
                        <img src="{{ asset('storage/' . $motorcycle->images->first()->image) }}" class="w-full h-full object-cover" alt="icon">
                    </div>
                    <div class="flex flex-col gap-3 w-full">
                        <h3 class="font-semibold text-lg leading-[27px]">{{ $motorcycle->name }}</h3>
                        <hr class="border-[#F1F2F6]">
                        <div class="flex items-center gap-[6px]">
                            <img src="{{ asset('assets/images/icons/status.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon">
                            <p class="text-sm text-gassor-grey">Stok Tersedia: {{ $motorcycle->available_stock ?? 0 }}</p>
                        </div>
                        <div class="flex items-center gap-[6px]">
                            <img src="{{ asset('assets/images/icons/notes.svg') }}" class="w-5 h-5 flex shrink-0"
                                alt="icon">
                            <p class="text-sm text-gassor-grey">STNK : {{ $motorcycle->stnk == 1 ? 'Tersedia' : 'Tidak Tersedia' }}</p>
                        </div>
                        <div class="flex items-center gap-[6px]">
                            <img src="{{ asset('assets/images/icons/police.svg') }}" class="w-5 h-5 flex shrink-0" alt="icon">
                            <p class="text-sm text-gassor-grey">Nomor Polisi : {{ $motorcycle->vehicle_number_plate }}</p>
                        </div>
                        @if($motorcycle->total_reviews > 0)
                        <div class="flex items-center gap-[6px]">
                            <img src="{{ asset('assets/images/icons/star.svg') }}" class="w-[26px] h-[26px] flex shrink-0" alt="icon" style="filter: brightness(0) saturate(100%) invert(94%) sepia(6%) saturate(0%) hue-rotate(180deg) brightness(97%) contrast(10%);">
                            <p class="text-sm text-gassor-grey">{{ number_format($motorcycle->average_rating, 1) }} ({{ $motorcycle->total_reviews }} review)</p>
                        </div>
                        @endif
                        <hr class="border-[#F1F2F6]">
                        <p class="font-semibold text-lg text-gassor-orange">Rp {{ number_format($motorcycle->price_per_day, 0, ',', '.') }}<span
                                class="text-sm text-gassor-grey font-normal">/hari</span></p>
                    </div>
                </div>
            </label>
            @endforeach
        @endif
    </div>
    <div id="BottomButton" class="relative flex w-full h-[98px] shrink-0">
        <div class="fixed bottom-[30px] w-full max-w-[640px] px-5 z-10">
            <button
                class="w-full rounded-full p-[14px_20px] bg-gassor-orange font-bold text-white text-center">Lanjutkan Pemesanan</button>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                html: `{!! session('error') !!}`,
                confirmButtonColor: '#e6a43b',
                customClass: {
                    popup: 'text-black',
                    confirmButton: 'rounded-full'
                },
                color: '#000000'
            });
        @endif
    });
</script>
@endsection
