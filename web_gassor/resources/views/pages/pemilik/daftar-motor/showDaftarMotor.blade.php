@extends('layouts.app')

@section('content')
<div id="Background"
    class="absolute top-0 w-full h-[570px] rounded-b-[75px]"
    style="background: linear-gradient(180deg, #e6a43b 0%, #e6a43b 100%);">
</div>
<div id="Header" class="relative flex items-center justify-between gap-2 px-5 mt-[18px]">
    <div class="flex flex-col gap-[6px]">
        <h1 class="font-bold text-[32px] leading-[48px]">Daftar Motor Anda</h1>
        <p class="text-gassor-grey">Tersedia {{ $totalMotor }} Motor</p>
    </div>
    <a href="{{ route('pemilik.create-motor') }}"
    style="background-color: #000000; color: #fff; font-weight: 600; padding: 8px 20px; border-radius: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); transition: background 0.2s; text-decoration: none; display: inline-block;">
        Tambah
    </a>
</div>
<section id="Result" class="relative flex flex-col gap-4 px-5 mt-5 mb-9">
    <div class="flex items-center justify-between mb-4">
        <h2 class="font-bold text-lg">Motor yang dimiliki {{ auth()->user()->name }}</h2>
        @if($motorcycles->count() > 0)
            <a href="#" class="btn-hapus-rental"
               data-id="{{ $motorcycles->first()->motorbike_rental_id }}"
               style="background-color: #eb5757; color: #fff; font-weight: 600; padding: 8px 16px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); transition: background 0.2s; text-decoration: none; display: inline-block; text-align: center; font-size: 14px;">
                Hapus Semua
            </a>
            <form id="form-hapus-rental-{{ $motorcycles->first()->motorbike_rental_id }}" action="{{ route('pemilik.destroy-rental', $motorcycles->first()->motorbike_rental_id) }}" method="POST" style="display:none;">
                @csrf
                @method('DELETE')
            </form>
        @endif
    </div>
    @forelse ($motorcycles as $motorcycle)
        <div class="card flex rounded-[30px] border border-[#F1F2F6] p-4 gap-4 bg-white hover:border-[#E6A43B] transition-all duration-300 w-full max-w-[600px] items-center min-h-[140px]">
            <div class="flex w-[120px] h-[90px] shrink-0 rounded-[18px] bg-[#D9D9D9] overflow-hidden items-center justify-center">
                <img src="{{ asset('storage/' . ($motorcycle->images->first()->image ?? 'default.png')) }}" class="object-cover w-full h-full" alt="icon">
            </div>
            <div class="flex flex-col gap-2 w-full items-left justify-center text-left">
                <h3 class="font-semibold text-lg leading-[27px] line-clamp-2 min-h-[27px]">{{ $motorcycle->name }}</h3>
                <div class="flex items-center gap-[6px]">
                    <img src="{{ asset('assets/images/icons/status.svg') }}" class="w-4 h-4" alt="status" />
                    <span class="text-sm text-gassor-grey">Stok: {{ $motorcycle->available_stock ?? 0 }}/{{ $motorcycle->stock ?? 1 }}</span>
                </div>
                <p class="text-sm text-gassor-grey">Harga • Rp {{ number_format($motorcycle->price_per_day, 0, ',', '.') }}/hari</p>
            </div>
            <div class="flex flex-col gap-2 w-full">
                <a href="{{ route('pemilik.edit-motor', $motorcycle->id) }}"
                style="background-color: #e6a43b; color: #fff; font-weight: 600; padding: 8px 20px; border-radius: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); transition: background 0.2s; text-decoration: none; display: inline-block; text-align: center;">
                    Edit
                </a>
                <a href="#" class="btn-hapus-motor"
                   data-id="{{ $motorcycle->id }}"
                   data-name="{{ $motorcycle->name }}"
                   style="background-color: #eb5757; color: #fff; font-weight: 600; padding: 8px 20px; border-radius: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); transition: background 0.2s; text-decoration: none; display: inline-block; text-align: center;">
                    Hapus
                </a>
                <form id="form-hapus-motor-{{ $motorcycle->id }}" action="{{ route('pemilik.destroy-motor', $motorcycle->id) }}" method="POST" style="display:none;">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    @empty
        <p class="text-center text-gassor-grey">Belum ada motor terdaftar.</p>
    @endforelse
    <div class="mt-4 flex justify-center">
        @if ($motorcycles->hasPages())
            <nav style="display: flex; gap: 4px; align-items: center;" aria-label="Pagination">
                @if ($motorcycles->onFirstPage())
                    <span style="padding: 6px 14px; border-radius: 8px; background: #e5e5e5; color: #aaa; font-weight: bold; border: 1px solid #e5e5e5; cursor: not-allowed;">&laquo;</span>
                @else
                    <a href="{{ $motorcycles->previousPageUrl() }}" style="padding: 6px 14px; border-radius: 8px; background: #000000; color: #fff; font-weight: bold; border: 1px solid #000; text-decoration: none; transition: background 0.2s;">&laquo;</a>
                @endif
                @foreach ($motorcycles->getUrlRange(1, $motorcycles->lastPage()) as $page => $url)
                    @if ($page == $motorcycles->currentPage())
                        <span style="padding: 6px 12px; border-radius: 8px; background: #000000; color: #fff; font-weight: bold; border: 1px solid #000;">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" style="padding: 6px 12px; border-radius: 8px; background: #fff; color: #000; font-weight: bold; border: 1px solid #000; text-decoration: none; transition: background 0.2s;">{{ $page }}</a>
                    @endif
                @endforeach
                @if ($motorcycles->hasMorePages())
                    <a href="{{ $motorcycles->nextPageUrl() }}" style="padding: 6px 14px; border-radius: 8px; background: #000000; color: #fff; font-weight: bold; border: 1px solid #000; text-decoration: none; transition: background 0.2s;">&raquo;</a>
                @else
                    <span style="padding: 6px 14px; border-radius: 8px; background: #e5e5e5; color: #aaa; font-weight: bold; border: 1px solid #e5e5e5; cursor: not-allowed;">&raquo;</span>
                @endif
            </nav>
        @endif
    </div>
</section>

@include('includes.navigation_pemilik')
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
@if (session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: {!! json_encode(session('success')) !!},
        confirmButtonColor: '#e6a43b',
        timer: 4000,
        timerProgressBar: true,
        showConfirmButton: true
    });
@endif

@if (session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Oops!',
        text: {!! json_encode(session('error')) !!},
        confirmButtonColor: '#eb5757',
        timer: 5000,
        timerProgressBar: true,
        showConfirmButton: true
    });
@endif

document.querySelectorAll('.btn-hapus-motor').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        var motorId = btn.getAttribute('data-id');
        var motorName = btn.getAttribute('data-name');

        Swal.fire({
            title: 'Hapus Motor "' + motorName + '"?',
            text: 'Motor ini akan dihapus dari rental Anda. Jika ini motor terakhir dalam rental, maka rental juga akan ikut terhapus.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff9800',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Ya, hapus motor ini!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-hapus-motor-' + motorId).submit();
            }
        });
    });
});

document.querySelector('.btn-hapus-rental')?.addEventListener('click', function(e) {
    e.preventDefault();
    var rentalId = this.getAttribute('data-id');

    Swal.fire({
        title: 'Hapus SEMUA Motor dalam Rental Ini?',
        text: 'PERHATIAN: Seluruh rental beserta SEMUA motor, gambar, dan bonus akan dihapus permanen!',
        icon: 'error',
        showCancelButton: true,
        confirmButtonColor: '#eb5757',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Ya, hapus semuanya!',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        focusCancel: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('form-hapus-rental-' + rentalId).submit();
        }
    });
});
</script>
@endsection
