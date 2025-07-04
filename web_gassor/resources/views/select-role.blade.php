@extends('layouts.app')

@section('content')
    <div id="Background" style="position: absolute; top: 0; width: 100%; height: 100%; background: linear-gradient(180deg, #e6a43b 0%, #f5f5f5 100%)"></div>
    <div class="relative flex flex-col items-center justify-center min-h-screen px-5">
        <div class="flex flex-col items-center gap-2" style="margin-bottom: 40px;">
            <img src="{{ asset('assets/images/photos/gassor1.png') }}" alt="Motor Icon" style="width: 150px; height: 150px; border-radius: 100%; box-shadow: 0 4px 24px #e6a43b33; padding: 18px; margin-bottom: 8px;">
            <h1 class="text-3xl font-bold text-center">Pilih Peran Anda</h1>
            <p class="mt-2 text-center text-gassor-black">Masuk / Daftar Sebagai :</p>
        </div>
        <div class="flex flex-row gap-6 mt-6 w-full max-w-md justify-center" style="margin-bottom: 40px;">
            <a href="{{ route('login') }}?role=penyewa" class="flex-1 p-4 font-bold text-white text-center hover:scale-105 transition-transform duration-200" style="background-color: #ff801a; border-radius: 12px; box-shadow: 0 2px 8px #e6a43b33;">
                <i class="fas fa-user" style="width: 50px; height: 50px; background: #fff; border-radius: 100%; margin-bottom: 6px; display: inline-flex; align-items: center; justify-content: center; font-size: 20px; color: #ff801a;"></i>
                <div>Penyewa</div>
                <div class="text-xs font-normal mt-1 text-white/80">Sewa motor dengan mudah dan cepat</div>
            </a>
            <a href="{{ route('login') }}?role=pemilik" class="flex-1 p-4 font-bold text-white text-center hover:scale-105 transition-transform duration-200" style="background-color: #ff801a; border-radius: 12px; box-shadow: 0 2px 8px #e6a43b33;">
                <i class="fas fa-warehouse" style="width: 50px; height: 50px; background: #fff; border-radius: 100%; margin-bottom: 6px; display: inline-flex; align-items: center; justify-content: center; font-size: 20px; color: #ff801a;"></i>
                <div>Pemilik</div>
                <div class="text-xs font-normal mt-1 text-white/80">Kelola motor dan pesanan Anda</div>
            </a>
        </div>
        <a href="{{ route('home') }}"
                   class="w-full p-4 mt-2 font-bold text-center text-white block"
                   style="background-color: #000000; border-radius: 12px; text-decoration: none;">Kembali ke halaman utama</a>
    </div>
@endsection
