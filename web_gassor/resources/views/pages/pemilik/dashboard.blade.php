@extends('layouts.app')

@section('content')
    <div id="Content-Container" class="relative flex flex-col w-full max-w-[640px] min-h-screen mx-auto bg-white overflow-x-hidden">
        <div style="position: absolute; top: 0; width: 100%; height: 230px; border-bottom-left-radius: 75px; border-bottom-right-radius: 75px; background: linear-gradient(180deg, #e6a43b 0%, #ff9d00 100%)"></div>
        <div id="TopNav" class="relative flex items-center justify-between px-5 mt-[60px]">
            <div class="flex flex-col gap-1">
            <p>Selamat datang kembali</p>
            <h1 class="font-bold text-xl leading-[30px]">{{ auth()->user()->name }}</h1>
            </div>
            <a href="{{ route('map') }}" class="flex items-center justify-center w-12 h-12 overflow-hidden bg-white rounded-full shrink-0">
            <img src="{{ asset('assets/images/icons/profile-2user.svg') }}" class="w-[28px] h-[28px]" alt="icon" />
            </a>
        </div>

        <section id="Best" class="relative flex flex-col gap-4 px-5 mt-[30px]">
            <div class="flex flex-col gap-4">
            <div class="card">
                <div class="flex justify-center gap-4 rounded-[30px] p-4 bg-white" style="border: 1.5px solid #e6a43b">
                <div class="flex flex-col items-center flex-1 max-w-[140px] p-4">
                    <img src="{{ asset('assets/images/icons/total-motor.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
                    <span class="text-lg font-bold">5</span>
                    <span class="text-xs text-gassor-grey">Total Motor</span>
                </div>
                <div class="flex flex-col items-center flex-1 max-w-[140px] p-4">
                    <img src="{{ asset('assets/images/icons/pesanan-aktif.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
                    <span class="text-lg font-bold">2</span>
                    <span class="text-xs text-gassor-grey">Pesanan Aktif</span>
                </div>
                <div class="flex flex-col items-center flex-1 max-w-[140px] p-4">
                    <img src="{{ asset('assets/images/icons/pendapatan.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
                    <span class="text-lg font-bold">Rp 2.500.000</span>
                    <span class="text-xs text-gassor-grey">Pendapatan</span>
                </div>
                </div>
            </div>
            </div>
        </section>

        <section id="Popular" class="flex flex-col gap-4 mt-[30px]">
            <div class="flex items-center justify-between px-5">
            <h2 class="font-bold">Daftar Motor Anda</h2>
            {{-- <a href="#">
                <div class="flex items-center gap-2">
                <span>See all</span>
                <img src="{{ asset('assets/images/icons/arrow-right.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
                </div>
            </a> --}}
            </div>
            <div class="w-full overflow-x-hidden swiper">
            <div id="TabsContent" class="px-5">
                <div id="Bonus-Tab" class="flex flex-col gap-5 tab-content">
                <div class="flex flex-col gap-4">
                    <div class="bonus-card flex items-center rounded-[22px] border border-[#F1F2F6] p-[10px] gap-3">
                    <div class="flex w-[120px] h-[90px] shrink-0 rounded-[18px] bg-[#D9D9D9] overflow-hidden">
                        <img src="{{ asset('assets/images/thumbnails/bonus-1.png') }}" class="object-cover w-full h-full" alt="thumbnails" />
                    </div>
                    <div>
                        <p class="font-semibold">Honda Beat</p>
                        <div class="flex items-center gap-2 text-sm text-gassor-grey">
                        <img src="{{ asset('assets/images/icons/status.svg') }}" class="w-4 h-4" alt="status" />
                        <span>Tersedia</span>
                        </div>
                        <p class="text-sm text-gassor-grey">Harga • Rp 50.000/hari</p>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </section>

        <section id="Cities" class="flex flex-col p-5 gap-4 bg-[#F5F6F8] mt-[30px]">
            <div class="flex items-center justify-between">
            <h2 class="font-bold">Pesanan Terbaru</h2>
            {{-- <a href="#">
                <div class="flex items-center gap-2">
                <span>See all</span>
                <img src="{{ asset('assets/images/icons/arrow-right.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
                </div>
            </a> --}}
            </div>
            <div id="Bonus-Tab" class="flex flex-col gap-5 tab-content">
            <div class="flex flex-col gap-4">
                <div class="bonus-card flex items-center justify-between rounded-[22px] border border-[#000000] p-[10px] gap-3">
                <div>
                    <p class="font-semibold">Honda Beat</p>
                    <p class="text-sm text-gassor-grey">Disewa Oleh: <span class="font-semibold">Fahri</span></p>
                    <p class="text-sm text-gassor-grey">Tanggal: 29 April 2025</p>
                </div>
                <p class="rounded-full p-[6px_12px] bg-gassor-orange font-bold text-xs leading-[18px]">PENDING</p>
                </div>
            </div>
            </div>
        </section>

        {{-- Tombol Logout di tengah bawah, tidak fixed --}}
        <div id="BottomButton" class="flex w-full h-[98px] shrink-0 mt-8 justify-center items-center">
            <form method="POST" action="{{ route('logout') }}" class="w-full max-w-[640px] px-5">
                @csrf
                <button type="submit" class="flex w-full justify-center rounded-full p-[14px_20px] font-bold text-white"
                    style="background: #ff801a;">
                    Logout
                </button>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="js/index.js"></script>
@endsection

