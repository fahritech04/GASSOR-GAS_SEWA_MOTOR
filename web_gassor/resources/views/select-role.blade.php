@extends('layouts.app')

@section('content')
    <div id="Background" style="position: absolute; top: 0; width: 100%; height: 100%; background: linear-gradient(180deg, #e6a43b 0%, #f5f5f5 100%)"></div>
    <div class="relative flex flex-col items-center justify-center min-h-screen px-5">
        <div class="flex flex-col items-center gap-2 mb-8">
            <img src="{{ asset('assets/images/icons/motorbike.svg') }}" alt="Motor Icon" style="width: 80px; height: 80px; background: #fff3e0; border-radius: 50%; box-shadow: 0 4px 24px #e6a43b33; padding: 18px; margin-bottom: 8px;">
            <h1 class="text-3xl font-bold text-center">Pilih Peran Anda</h1>
            <p class="mt-2 text-center text-gassor-grey">Masuk / Daftar Sebagai :</p>
        </div>
        <div class="flex flex-row gap-6 mt-6 w-full max-w-md justify-center">
            <a href="{{ route('login') }}?role=penyewa" class="flex-1 p-4 font-bold text-white text-center hover:scale-105 transition-transform duration-200" style="background-color: #ff801a; border-radius: 12px; box-shadow: 0 2px 8px #e6a43b33;">
                <img src="{{ asset('assets/images/icons/user.svg') }}" alt="Penyewa Icon" style="width: 28px; height: 28px; background: #fff; border-radius: 50%; margin-bottom: 6px; display: inline-block;">
                <div>Penyewa</div>
                <div class="text-xs font-normal mt-1 text-white/80">Sewa motor dengan mudah dan cepat</div>
            </a>
            <a href="{{ route('login') }}?role=pemilik" class="flex-1 p-4 font-bold text-white text-center hover:scale-105 transition-transform duration-200" style="background-color: #ff801a; border-radius: 12px; box-shadow: 0 2px 8px #e6a43b33;">
                <img src="{{ asset('assets/images/icons/garage.svg') }}" alt="Pemilik Icon" style="width: 28px; height: 28px; background: #fff; border-radius: 50%; margin-bottom: 6px; display: inline-block;">
                <div>Pemilik</div>
                <div class="text-xs font-normal mt-1 text-white/80">Kelola motor dan pesanan Anda</div>
            </a>
        </div>
        <div class="flex flex-col items-center w-full max-w-md" style="margin-top: 80px; margin-bottom: 24px;">
            <ul class="w-full flex flex-wrap justify-center items-center gap-4 text-sm text-gassor-grey">
                <li class="flex items-center gap-2 bg-white/80 rounded-lg p-3 shadow-sm min-w-[180px] justify-center"><img src="{{ asset('assets/images/icons/check.svg') }}" style="width:18px;"> Proses cepat & mudah</li>
                <li class="flex items-center gap-2 bg-white/80 rounded-lg p-3 shadow-sm min-w-[180px] justify-center"><img src="{{ asset('assets/images/icons/check.svg') }}" style="width:18px;"> Banyak pilihan motor</li>
                <li class="flex items-center gap-2 bg-white/80 rounded-lg p-3 shadow-sm min-w-[180px] justify-center"><img src="{{ asset('assets/images/icons/check.svg') }}" style="width:18px;"> Harga transparan</li>
                <li class="flex items-center gap-2 bg-white/80 rounded-lg p-3 shadow-sm min-w-[180px] justify-center"><img src="{{ asset('assets/images/icons/check.svg') }}" style="width:18px;"> Support customer 24/7</li>
            </ul>
        </div>
    </div>
@endsection
