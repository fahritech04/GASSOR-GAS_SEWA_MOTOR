@extends('layouts.app')

@section('title', 'Beri Review - GASSOR')

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
        <p class="font-semibold text-white px-6 py-2 rounded-full backdrop-blur-sm" style="background: rgba(0, 0, 0, 0.100);">Beri Review</p>
        <button
            class="w-12 h-12 flex items-center justify-center shrink-0 rounded-full overflow-hidden">
        </button>
    </div>
    <div id="Gallery" class="w-full overflow-x-hidden -mb-[38px]">
        <div class="flex shrink-0 w-full h-[430px] overflow-hidden">
            @if($transaction->motorcycle->images->first())
                <img src="{{ asset('storage/' . $transaction->motorcycle->images->first()->image) }}" class="w-full h-full object-cover"
                    alt="{{ $transaction->motorcycle->name }}">
            @else
                <div class="w-full h-full bg-[#D9D9D9] flex items-center justify-center">
                    <span class="text-gray-500">No Image</span>
                </div>
            @endif
        </div>
    </div>
    <main id="Details" class="relative flex flex-col rounded-t-[40px] py-5 pb-[10px] gap-4 bg-white z-10">
        <div id="Title" class="flex items-center justify-between gap-2 px-5">
            <h1 class="font-bold text-[22px] leading-[33px]">{{ $transaction->motorcycle->name }}</h1>
        </div>
        <hr class="border-[#F1F2F6] mx-5">
        <div id="Features" class="grid grid-cols-1 gap-x-[10px] gap-y-4 px-5">
            <div class="flex items-center gap-[6px]">
                <img src="{{ asset('assets/images/icons/location.svg') }}" class="w-[26px] h-[26px] flex shrink-0" alt="icon">
                <p class="text-gassor-grey">{{ $transaction->motorcycle->motorbikeRental->name }}</p>
            </div>
            <div class="flex items-center gap-[6px]">
                <img src="{{ asset('assets/images/icons/notes.svg') }}" class="w-[26px] h-[26px] flex shrink-0" alt="icon">
                <p class="text-gassor-grey">Kode Booking: {{ $transaction->code }}</p>
            </div>
        </div>
        <hr class="border-[#F1F2F6] mx-5">

        <form action="{{ route('review.store', $transaction) }}" method="POST" class="px-5">
            @csrf

            <div id="RatingSection" style="margin-bottom: 24px;">
                <h2 class="font-bold mb-4">Berikan Rating</h2>
                <div class="flex justify-center gap-2 mb-4">
                    <div class="rating-container flex gap-1" id="rating-container">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" class="star-btn w-12 h-12 flex items-center justify-center" data-rating="{{ $i }}" tabindex="0">
                                <svg class="w-8 h-8 star-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </button>
                        @endfor
                    </div>
                </div>
                <input type="hidden" name="rating" id="rating-input" required>
                @error('rating')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <hr class="border-[#F1F2F6]" style="margin-bottom: 24px;">

            <div id="CommentSection" style="margin-bottom: 24px;">
                <h2 class="font-bold mb-4">Tulis Komentar (Opsional)</h2>
                <textarea
                    name="comment"
                    id="comment"
                    rows="4"
                    class="w-full p-4 border border-[#F1F2F6] rounded-[12px] resize-none focus:outline-none focus:border-gassor-orange"
                    placeholder="Bagikan pengalaman Anda menggunakan motor ini..."
                    maxlength="1000"
                >{{ old('comment') }}</textarea>
            </div>
        </form>
    </main>

    <!-- Bottom Navigation -->
    <div id="BottomNav" class="relative flex w-full h-[138px] shrink-0">
        <div class="bottom-5 w-full max-w-[640px] px-5 z-10">
            <div class="flex gap-3">
                <a href="{{ route('history-booking') }}" class="flex-1 py-[14px] px-5 bg-gassor-black text-white text-center font-bold rounded-full">
                    Batal
                </a>
                <button type="button" onclick="confirmSubmitReview()" form="review-form" class="flex-1 py-[14px] px-5 bg-gassor-orange text-white text-center font-bold rounded-full">
                    Kirim Review
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.star-btn {
    transition: all 0.2s ease;
}

.star-icon {
    color: #e5e7eb;
    transition: color 0.2s ease;
}

.star-btn.active .star-icon,
.star-btn:hover .star-icon {
    color: #fbbf24;
}
</style>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Create review page loaded, SweetAlert available:', typeof Swal !== 'undefined');

    const stars = document.querySelectorAll('.star-btn');
    const ratingInput = document.getElementById('rating-input');
    const form = document.querySelector('form');
    let currentRating = 0;

    form.id = 'review-form';

    stars.forEach((star, index) => {
        star.addEventListener('mouseover', function() {
            highlightStars(index + 1);
        });

        star.addEventListener('mouseout', function() {
            highlightStars(currentRating);
        });

        star.addEventListener('click', function() {
            currentRating = index + 1;
            ratingInput.value = currentRating;
            highlightStars(currentRating);
        });
    });

    function highlightStars(rating) {
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.add('active');
            } else {
                star.classList.remove('active');
            }
        });
    }

    @if(session('success'))
        console.log('Success message found');
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonColor: '#E6A43B',
                customClass: {
                    popup: 'rounded-lg shadow-lg text-black',
                    title: 'text-gassor-black font-bold',
                    content: 'text-gassor-grey',
                    confirmButton: 'rounded-full'
                }
            });
        } else {
            alert('Berhasil! {{ session('success') }}');
        }
    @endif

    @if(session('error'))
        console.log('Error message found');
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Error!',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonColor: '#d33',
                customClass: {
                    popup: 'rounded-lg shadow-lg text-black',
                    title: 'text-gassor-black font-bold',
                    content: 'text-gassor-grey',
                    confirmButton: 'rounded-full'
                }
            });
        } else {
            alert('Error! {{ session('error') }}');
        }
    @endif
});

function confirmSubmitReview() {
    console.log('confirmSubmitReview called');

    const rating = document.getElementById('rating-input').value;
    const comment = document.getElementById('comment').value;

    console.log('Rating:', rating, 'Comment:', comment);

    if (!rating) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Rating Diperlukan',
                text: 'Silakan pilih rating terlebih dahulu!',
                icon: 'warning',
                confirmButtonColor: '#E6A43B',
                customClass: {
                    popup: 'rounded-lg shadow-lg text-black',
                    title: 'text-gassor-black font-bold',
                    content: 'text-gassor-grey',
                    confirmButton: 'rounded-full px-6 py-2 font-bold'
                }
            });
        } else {
            alert('Rating diperlukan! Silakan pilih rating terlebih dahulu!');
        }
        return;
    }

    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Kirim Review',
            text: 'Apakah Anda yakin ingin mengirim review ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#E6A43B',
            cancelButtonColor: '#95A5A6',
            confirmButtonText: 'Ya, Kirim!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-lg shadow-lg text-black',
                title: 'text-gassor-black font-bold',
                content: 'text-gassor-grey',
                confirmButton: 'rounded-full px-6 py-2 font-bold',
                cancelButton: 'rounded-full px-6 py-2 font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('review-form').submit();
            }
        });
    } else {
        if (confirm('Apakah Anda yakin ingin mengirim review ini?')) {
            document.getElementById('review-form').submit();
        }
    }
}
</script>
@endsection
