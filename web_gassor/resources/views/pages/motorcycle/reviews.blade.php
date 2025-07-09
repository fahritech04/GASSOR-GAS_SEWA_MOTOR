@extends('layouts.app')

@section('title', 'Review ' . $motorcycle->name . ' - GASSOR')

@section('content')
<div id="Content-Container"
    class="relative flex flex-col w-full max-w-[640px] min-h-screen mx-auto bg-white overflow-x-hidden">
    <div id="ForegroundFade"
        class="absolute top-0 w-full h-[143px] bg-[linear-gradient(180deg,#070707_0%,rgba(7,7,7,0)_100%)] z-10">
    </div>
    <div id="TopNavAbsolute" class="absolute top-[60px] flex items-center justify-between w-full px-5 z-10">
        <a href="{{ route('history-booking') }}"
            class="w-12 h-12 flex items-center justify-center shrink-0 rounded-full overflow-hidden bg-white/10 backdrop-blur-sm">
            <img src="{{ asset('assets/images/icons/arrow-left-transparent.svg') }}" class="w-8 h-8" alt="icon">
        </a>
        <p class="font-semibold text-white px-6 py-2 rounded-full backdrop-blur-sm" style="background: rgba(0, 0, 0, 0.100);">Review Motor</p>
        <button
            class="w-12 h-12 flex items-center justify-center shrink-0 rounded-full overflow-hidden">
        </button>
    </div>
    <div id="Gallery" class="w-full overflow-x-hidden -mb-[38px]">
        <div class="flex shrink-0 w-full h-[430px] overflow-hidden">
            @if($motorcycle->images->first())
                <img src="{{ asset('storage/' . $motorcycle->images->first()->image) }}" class="w-full h-full object-cover"
                    alt="{{ $motorcycle->name }}">
            @else
                <div class="w-full h-full bg-[#D9D9D9] flex items-center justify-center">
                    <span class="text-gray-500">No Image</span>
                </div>
            @endif
        </div>
    </div>
    <main id="Details" class="relative flex flex-col rounded-t-[40px] py-5 pb-[10px] gap-4 bg-white z-10">
        <div id="Title" class="flex items-center justify-between gap-2 px-5">
            <h1 class="font-bold text-[22px] leading-[33px]">{{ $motorcycle->name }}</h1>
        </div>
        <hr class="border-[#F1F2F6] mx-5">
        <div id="Features" class="grid grid-cols-2 gap-x-[10px] gap-y-4 px-5">
            <div class="flex items-center gap-[6px]">
                <img src="{{ asset('assets/images/icons/3dcube.svg') }}" class="w-[26px] h-[26px] flex shrink-0" alt="icon">
                <p class="text-gassor-grey">{{ $motorcycle->motorbikeRental->name }}</p>
            </div>
            <div class="flex items-center gap-[6px]">
                <img src="{{ asset('assets/images/icons/notes.svg') }}" class="w-[26px] h-[26px] flex shrink-0" alt="icon">
                <p class="text-gassor-grey">Rp {{ number_format($motorcycle->price_per_day, 0, ',', '.') }}/hari</p>
            </div>
            @if($motorcycle->reviews->count() > 0)
            <div class="flex items-center gap-[6px]">
                <img src="{{ asset('assets/images/icons/star.svg') }}" class="w-[26px] h-[26px] flex shrink-0" alt="icon" style="filter: brightness(0) saturate(100%) invert(94%) sepia(6%) saturate(0%) hue-rotate(180deg) brightness(97%) contrast(10%);">
                <p class="text-gassor-grey">{{ number_format($motorcycle->average_rating, 1) }} ({{ $motorcycle->total_reviews }} review)</p>
            </div>
            @endif
            <div class="flex items-center gap-[6px]">
                <img src="{{ asset('assets/images/icons/3dcube.svg') }}" class="w-[26px] h-[26px] flex shrink-0" alt="icon">
                <p class="text-gassor-grey">{{ $motorcycle->category->name }}</p>
            </div>
        </div>
        <hr class="border-[#F1F2F6] mx-5">

        @if($motorcycle->reviews->count() > 0)
            <div id="RatingSummary" class="px-5">
                <h2 class="font-bold mb-4">Rating & Review</h2>

                <div class="rounded-[22px] border border-[#F1F2F6] mb-4" style="padding: 40px;">
                    <div class="flex items-center justify-center" style="gap: 10px;">
                        <div class="flex flex-col items-center justify-center min-w-[120px]">
                            <div class="text-4xl font-bold text-gassor-black text-center">
                                {{ number_format($motorcycle->average_rating, 1) }}
                            </div>
                            <div class="flex items-center justify-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="text-xl {{ $i <= floor($motorcycle->average_rating) ? 'opacity-100' : 'opacity-30' }}">⭐</span>
                                @endfor
                            </div>
                            <div class="text-center">
                                <div class="text-sm font-bold text-gray-700">{{ $motorcycle->total_reviews }} ulasan</div>
                                <p class="text-xs text-gray-400">dari penyewa</p>
                            </div>
                        </div>

                        <div style="width: 2px; height: 100px; background-color: #E5E7EB; margin: 0 20px;"></div>

                        <div class="flex-1 w-full">
                            <div class="flex justify-between items-center w-full" style="gap: 16px;">
                                @foreach($motorcycle->rating_distribution as $star => $count)
                                    <div class="flex flex-col items-center justify-center flex-1" style="gap: 8px;">
                                        <div class="flex items-center justify-center" style="gap: 4px;">
                                            <span class="text-xs font-medium">{{ $star }}</span>
                                            <span class="text-sm">⭐</span>
                                        </div>
                                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                            <div class="bg-gassor-orange h-2 rounded-full transition-all duration-300"
                                                 style="width: {{ $motorcycle->total_reviews > 0 ? ($count / $motorcycle->total_reviews) * 100 : 0 }}%">
                                            </div>
                                        </div>
                                        <span class="text-xs text-gray-600 font-medium text-center">{{ $count }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-[#F1F2F6] mx-5">

            <div id="ReviewsList" class="px-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold">Semua Review ({{ $motorcycle->total_reviews }})</h2>
                    @if(auth()->check())
                        {{-- <div class="text-xs text-gray-500">
                            Login sebagai: {{ auth()->user()->name }} (ID: {{ auth()->id() }})
                        </div> --}}
                    @else
                        <div class="text-xs text-red-500">
                            Belum login
                        </div>
                    @endif
                </div>

                <div class="flex flex-col" style="gap: 20px;">
                    @foreach($motorcycle->reviews as $review)
                        <div class="rounded-[22px] border border-[#F1F2F6] hover:border-gassor-orange transition-all duration-300 bg-white" style="padding: 16px 20px;">
                            <div class="flex items-start" style="gap: 12px;">
                                <div class="flex-shrink-0" style="width: 48px; height: 48px; border-radius: 50%; background-color: #E6A43B; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative;">
                                    <span class="text-white font-bold text-lg" style="z-index: 2;">{{ substr($review->user->name, 0, 1) }}</span>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="w-full flex items-start justify-between">
                                        <div class="flex-1 min-w-0" style="max-width: calc(100% - 140px);">
                                            <h5 class="font-bold text-gray-900 text-base sm:text-lg truncate" style="line-height: 1.2;">{{ $review->user->name }}</h5>
                                            <span class="text-xs sm:text-sm text-gray-500 truncate" style="margin-top: 2px;">{{ $review->created_at->diffForHumans() }}</span>
                                        </div>

                                        <div class="flex flex-shrink-0" style="min-width: 130px;">
                                            <div class="hidden sm:flex items-center gap-2">
                                                <div class="flex items-center gap-0.5">
                                                    @for($i = 1; $i <= $review->rating; $i++)
                                                        <span class="text-lg opacity-100">⭐</span>
                                                    @endfor
                                                </div>

                                                <span class="text-xs font-medium px-3 py-1 rounded-full whitespace-nowrap">
                                                    @if($review->rating == 5) Sangat Puas
                                                    @elseif($review->rating == 4) Puas
                                                    @elseif($review->rating == 3) Cukup
                                                    @elseif($review->rating == 2) Kurang
                                                    @else Tidak Puas
                                                    @endif
                                                </span>
                                            </div>

                                            <!-- LAYAR <640px: VERTIKAL -->
                                            <div class="flex flex-col items-end gap-1 sm:hidden">
                                                <div class="flex items-center gap-0.5">
                                                    @for($i = 1; $i <= $review->rating; $i++)
                                                        <span class="text-sm opacity-100">⭐</span>
                                                    @endfor
                                                </div>

                                                <span class="text-xs font-medium px-2 py-0.5 rounded-full whitespace-nowrap">
                                                    @if($review->rating == 5) Sangat Puas
                                                    @elseif($review->rating == 4) Puas
                                                    @elseif($review->rating == 3) Cukup
                                                    @elseif($review->rating == 2) Kurang
                                                    @else Tidak Puas
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    @if($review->comment)
                                        <div class="bg-gray-50 rounded-[16px]">
                                            <p class="text-gray-700 leading-relaxed">{{ $review->comment }}</p>
                                        </div>
                                    @endif

                                    @if(auth()->check() && auth()->id() === $review->user_id)
                                        <div class="flex justify-end border-t border-gray-100" style="padding-top: 12px;">
                                            <form action="{{ route('review.destroy', $review) }}" method="POST" id="delete-form-{{ $review->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                        class="flex items-center bg-gassor-orange text-white font-medium text-xs rounded-full hover:bg-orange-600 transition-all duration-300 shadow-md"
                                                        style="gap: 4px; padding: 6px 10px;"
                                                        onclick="confirmDelete({{ $review->id }})">
                                                    <span class="text-xs">Hapus Review</span>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="px-5">
                <div class="text-center py-12 bg-gray-50 rounded-[22px] border border-[#F1F2F6]">
                    <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg text-gray-700 mb-2">Belum Ada Review</h3>
                    <p class="text-gray-500 text-sm mb-4">Motor ini belum memiliki ulasan dari penyewa</p>
                    <p class="text-xs text-gray-400">Jadilah yang pertama memberikan review setelah menyewa!</p>
                </div>
            </div>
        @endif
    </main>

    <div id="BottomNav" class="relative flex w-full h-[98px] shrink-0"></div>
</div>
@endsection

@section('scripts')
<script>
    console.log('SweetAlert available:', typeof Swal !== 'undefined');

    function confirmDelete(reviewId) {
        console.log('confirmDelete called with reviewId:', reviewId);

        if (typeof Swal === 'undefined') {
            if (confirm('Apakah Anda yakin ingin menghapus review ini?')) {
                document.getElementById('delete-form-' + reviewId).submit();
            }
            return;
        }

        Swal.fire({
            title: 'Hapus Review',
            text: 'Apakah Anda yakin ingin menghapus review ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#E6A43B',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + reviewId).submit();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    confirmButtonColor: '#059669',
                    confirmButtonText: 'OK'
                });
            } else {
                alert('Berhasil! {{ session('success') }}');
            }
        @endif

        @if(session('error'))
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    icon: 'error',
                    confirmButtonColor: '#dc2626',
                    confirmButtonText: 'OK'
                });
            } else {
                alert('Error! {{ session('error') }}');
            }
        @endif
    });
</script>
@endsection
