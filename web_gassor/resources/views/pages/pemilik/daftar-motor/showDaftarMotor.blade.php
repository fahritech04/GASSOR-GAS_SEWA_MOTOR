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
        + Tambah Motor
    </a>
</div>
<section id="Result" class="relative flex flex-col gap-4 px-5 mt-5 mb-9">
    <h2 class="font-bold text-lg mb-2">Motor yang dimiliki {{ auth()->user()->name }}</h2>
    @forelse ($motorcycles as $motorcycle)
        {{-- <div class="card flex rounded-[30px] border border-[#F1F2F6] p-4 gap-4 bg-white hover:border-[#E6A43B] transition-all duration-300" style="cursor:pointer" onclick="window.location='{{ route('pemilik.edit-motor', $motorcycle->id) }}'"> --}}
        <div class="card flex rounded-[30px] border border-[#F1F2F6] p-4 gap-4 bg-white hover:border-[#E6A43B] transition-all duration-300 w-full max-w-[600px] items-center min-h-[140px]">
            <div class="flex w-[120px] h-[90px] shrink-0 rounded-[18px] bg-[#D9D9D9] overflow-hidden items-center justify-center">
                <img src="{{ asset('storage/' . ($motorcycle->images->first()->image ?? 'default.png')) }}" class="object-cover w-full h-full" alt="icon">
            </div>
            <div class="flex flex-col gap-2 w-full items-left justify-center text-left">
                <h3 class="font-semibold text-lg leading-[27px] line-clamp-2 min-h-[27px]">{{ $motorcycle->name }}</h3>
                <div class="flex items-center gap-[6px]">
                    <img src="{{ asset('assets/images/icons/status.svg') }}" class="w-4 h-4" alt="status" />
                    <span class="text-sm text-gassor-grey">{{ $motorcycle->is_available ? 'Tersedia' : 'Tidak Tersedia' }}</span>
                </div>
                <p class="text-sm text-gassor-grey">Harga • Rp {{ number_format($motorcycle->price_per_day, 0, ',', '.') }}/hari</p>
            </div>
            <div class="flex flex-col gap-2 w-full">
                <a href="{{ route('pemilik.edit-motor', $motorcycle->id) }}"
                style="background-color: #e6a43b; color: #fff; font-weight: 600; padding: 8px 20px; border-radius: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); transition: background 0.2s; text-decoration: none; display: inline-block; text-align: center;">
                    Edit Motor
                </a>
                <a href="#" class="btn-hapus-rental"
                   data-id="{{ $motorcycle->motorbike_rental_id }}"
                   style="background-color: #eb5757; color: #fff; font-weight: 600; padding: 8px 20px; border-radius: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); transition: background 0.2s; text-decoration: none; display: inline-block; text-align: center;">
                    Hapus Motor
                </a>
                <form id="form-hapus-rental-{{ $motorcycle->motorbike_rental_id }}" action="{{ route('pemilik.destroy-rental', $motorcycle->motorbike_rental_id) }}" method="POST" style="display:none;">
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
                {{-- Halaman Sebelumnya --}}
                @if ($motorcycles->onFirstPage())
                    <span style="padding: 6px 14px; border-radius: 8px; background: #e5e5e5; color: #aaa; font-weight: bold; border: 1px solid #e5e5e5; cursor: not-allowed;">&laquo;</span>
                @else
                    <a href="{{ $motorcycles->previousPageUrl() }}" style="padding: 6px 14px; border-radius: 8px; background: #000000; color: #fff; font-weight: bold; border: 1px solid #000; text-decoration: none; transition: background 0.2s;">&laquo;</a>
                @endif

                {{-- halaman angka --}}
                @foreach ($motorcycles->getUrlRange(1, $motorcycles->lastPage()) as $page => $url)
                    @if ($page == $motorcycles->currentPage())
                        <span style="padding: 6px 12px; border-radius: 8px; background: #000000; color: #fff; font-weight: bold; border: 1px solid #000;">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" style="padding: 6px 12px; border-radius: 8px; background: #fff; color: #000; font-weight: bold; border: 1px solid #000; text-decoration: none; transition: background 0.2s;">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Halaman Selanjutnya --}}
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
document.querySelectorAll('.btn-hapus-rental').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        var rentalId = btn.getAttribute('data-id');
        Swal.fire({
            title: 'Yakin ingin menghapus rental dan semua motor terkait?',
            text: 'Semua data motor, gambar, dan bonus akan dihapus permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#eb5757',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-hapus-rental-' + rentalId).submit();
            }
        });
    });
});
</script>
@endsection
