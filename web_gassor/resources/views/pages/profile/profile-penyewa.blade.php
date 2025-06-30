@extends('layouts.app')

@section('content')
    <div id="Content-Container" class="relative flex flex-col w-full max-w-[640px] min-h-screen mx-auto bg-white overflow-x-hidden">
        <div style="position: absolute; top: 0; width: 100%; height: 310px; border-bottom-left-radius: 75px; border-bottom-right-radius: 75px; background: linear-gradient(180deg, #e6a43b 0%, #e6a43b 100%)"></div>

        <div id="TopNav" class="relative flex items-center justify-between px-5 mt-[60px]">
            <a href="{{ route('home') }}" class="flex items-center justify-center w-12 h-12 overflow-hidden bg-white rounded-full shrink-0">
                <img src="{{ asset('assets/images/icons/arrow-left.svg') }}" class="w-[28px] h-[28px]" alt="icon" />
            </a>
            <p class="font-semibold">Profil Saya</p>
            <div class="w-12 dummy-btn"></div>
        </div>

        <div class="relative flex flex-col items-center justify-center gap-2 px-5" style="margin-top: 10px;">
            <div class="shrink-0" style="width: 90px; height: 90px; border-radius: 100%; overflow: hidden; border: 4px solid white; display: flex; justify-content: center; align-items: center">
                <img src="{{ Auth::user()->profile_image_url ? (Str::startsWith(Auth::user()->profile_image_url, 'http') ? Auth::user()->profile_image_url : asset('storage/' . Auth::user()->profile_image_url)) : asset('assets/images/photos/default_profil.jpg') }}" style="width: 100%; height: 100%; object-fit: cover" alt="profile picture" />
            </div>
            <div class="text text-center">
                <p class="text-xl font-semibold break-all">{{ Auth::user()->username ? Auth::user()->username : Auth::user()->name }}</p>
                <p class="text-sm text-gassor-grey break-all">{{ Auth::user()->email }}</p>
            </div>
        </div>

        <div class="flex flex-col gap-5 px-5 pb-40" style="margin-top: 60px">
            <div class="accordion group flex flex-col rounded-[30px] p-5 bg-[#F5F6F8] overflow-hidden has-[:checked]:!h-[68px] transition-all duration-300">
                <label class="relative flex items-center justify-between">
                    <p class="text-lg font-semibold">Informasi Pribadi</p>
                    <img src="{{ asset('assets/images/icons/arrow-up.svg') }}" class="w-[28px] h-[28px] flex shrink-0 group-has-[:checked]:rotate-180 transition-all duration-300" alt="icon" />
                    <input type="checkbox" class="absolute hidden" />
                </label>
                <div class="flex flex-col gap-4 pt-[22px]">
                    <div class="flex flex-col sm:grid sm:grid-cols-2 items-center w-full gap-2">
                        <div class="flex items-center gap-3 w-full">
                            <img src="{{ asset('assets/images/icons/profile-2user.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
                            <p class="text-gassor-grey text-left">Nama Lengkap</p>
                        </div>
                        <p class="font-semibold w-full break-all text-right">{{ Auth::user()->name }}</p>
                    </div>
                    <div class="flex flex-col sm:grid sm:grid-cols-2 items-center w-full gap-2">
                        <div class="flex items-center gap-3 w-full">
                            <img src="{{ asset('assets/images/icons/sms.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
                            <p class="text-gassor-grey text-left">Email</p>
                        </div>
                        <p class="font-semibold w-full break-all text-right">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="flex flex-col sm:grid sm:grid-cols-2 items-center w-full gap-2">
                        <div class="flex items-center gap-3 w-full">
                            <img src="{{ asset('assets/images/icons/call.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
                            <p class="text-gassor-grey text-left">Nomor Telepon</p>
                        </div>
                        <p class="font-semibold w-full break-all text-right">{{ Auth::user()->phone }}</p>
                    </div>
                    {{-- <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('assets/images/icons/location.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
                        <p class="text-gassor-grey">Alamat</p>
                    </div>
                    <p class="font-semibold text-right"></p>
                    </div> --}}
                </div>
            </div>

            <div class="accordion group flex flex-col rounded-[30px] p-5 bg-[#F5F6F8] overflow-hidden has-[:checked]:!h-[68px] transition-all duration-300">
                <label class="relative flex items-center justify-between">
                    <p class="text-lg font-semibold">Pengaturan Akun</p>
                    <img src="{{ asset('assets/images/icons/arrow-up.svg') }}" class="w-[28px] h-[28px] flex shrink-0 group-has-[:checked]:rotate-180 transition-all duration-300" alt="icon" />
                    <input type="checkbox" class="absolute hidden" />
                </label>
                <div class="flex flex-col gap-4 pt-[22px]">
                    <a href="{{ route('editprofile.penyewa') }}" class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('assets/images/icons/key.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
                            <p class="text-gassor-grey">Akun Saya</p>
                        </div>
                        <img src="{{ asset('assets/images/icons/arrow-right.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
                    </a>
                    <a href="{{ route('history-booking') }}" class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('assets/images/icons/key.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
                            <p class="text-gassor-grey">Riwayat Pemesanan</p>
                        </div>
                        <img src="{{ asset('assets/images/icons/arrow-right.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
                    </a>
                </div>
            </div>

            <div id="BottomButton" class="flex w-full h-[98px] shrink-0 mt-8 justify-center items-center">
                <form method="POST" action="{{ route('logout') }}" class="w-full max-w-[640px] px-5">
                    @csrf
                    <button type="submit" class="flex w-full justify-center rounded-full p-[14px_20px] font-bold text-white"
                        style="background: #ff801a;">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/accodion.js') }}"></script>
@endsection
