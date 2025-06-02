@extends('layouts.app')

@section('content')
    <div id="Content-Container" class="relative flex flex-col w-full max-w-[640px] min-h-screen mx-auto bg-white overflow-x-hidden">
      <div style="position: absolute; top: 0; width: 100%; height: 230px; border-bottom-left-radius: 75px; border-bottom-right-radius: 75px; background: linear-gradient(180deg, #e6a43b 0%, #e6a43b 100%)"></div>

      <div id="TopNav" class="relative flex items-center justify-between px-5 mt-[60px]">
        <a href="{{ route('home') }}" class="flex items-center justify-center w-12 h-12 overflow-hidden bg-white rounded-full shrink-0">
          <img src="{{ asset('assets/images/icons/arrow-left.svg') }}" class="w-[28px] h-[28px]" alt="icon" />
        </a>
        <p class="font-semibold">Informasi</p>
        <div class="w-12 dummy-btn"></div>
      </div>

        <div class="relative z-10 flex flex-col gap-5 px-5 pb-40" style="margin-top: 50px">
            <div class="accordion group flex flex-col rounded-[30px] p-5 bg-[#F5F6F8] overflow-hidden has-[:checked]:!h-[68px] transition-all duration-300">
                <label class="relative flex items-center justify-between">
                    <p class="text-lg font-semibold">Tentang Gassor</p>
                    <img src="{{ asset('assets/images/icons/arrow-up.svg') }}" class="w-[28px] h-[28px] flex shrink-0 group-has-[:checked]:rotate-180 transition-all duration-300" alt="icon" />
                    <input type="checkbox" class="absolute hidden" />
                </label>
                <div class="flex flex-col gap-4 pt-[22px]">
                    <p>Gassor, Gas Sewa Motor, merupakan website aplikasi sewa motor dikalangan mahasiswa Telkom University, perbedaan dengan sewa motor yang lain disini kamu bisa menyewakan motor mu sendiri untuk orang lain.</p>
                </div>
            </div>

            <div class="accordion group flex flex-col rounded-[30px] p-5 bg-[#F5F6F8] overflow-hidden has-[:checked]:!h-[68px] transition-all duration-300">
                <label class="relative flex items-center justify-between">
                    <p class="text-lg font-semibold">Privasi & Kebijakan</p>
                    <img src="{{ asset('assets/images/icons/arrow-up.svg') }}" class="w-[28px] h-[28px] flex shrink-0 group-has-[:checked]:rotate-180 transition-all duration-300" alt="icon" />
                    <input type="checkbox" class="absolute hidden" />
                </label>
                <div class="flex flex-col gap-4 pt-[22px]">
                    <p>Data pribadi pengguna akan dijaga kerahasiaannya dan hanya digunakan untuk keperluan transaksi sewa motor di Gassor.</p>
                </div>
            </div>

            <div class="accordion group flex flex-col rounded-[30px] p-5 bg-[#F5F6F8] overflow-hidden has-[:checked]:!h-[68px] transition-all duration-300">
                <label class="relative flex items-center justify-between">
                    <p class="text-lg font-semibold">Syarat & Ketentuan</p>
                    <img src="{{ asset('assets/images/icons/arrow-up.svg') }}" class="w-[28px] h-[28px] flex shrink-0 group-has-[:checked]:rotate-180 transition-all duration-300" alt="icon" />
                    <input type="checkbox" class="absolute hidden" />
                </label>
                <div class="flex flex-col gap-4 pt-[22px]">
                    <p>Penyewa dan pemilik motor wajib mematuhi aturan yang berlaku di Gassor serta menjaga keamanan dan kenyamanan selama proses sewa menyewa.</p>
                </div>
            </div>

            <div class="accordion group flex flex-col rounded-[30px] p-5 bg-[#F5F6F8] overflow-hidden has-[:checked]:!h-[68px] transition-all duration-300">
                <label class="relative flex items-center justify-between">
                    <p class="text-lg font-semibold">Dibuat Oleh</p>
                    <img src="{{ asset('assets/images/icons/arrow-up.svg') }}" class="w-[28px] h-[28px] flex shrink-0 group-has-[:checked]:rotate-180 transition-all duration-300" alt="icon" />
                    <input type="checkbox" class="absolute hidden" />
                </label>
                <div class="flex flex-col gap-4 pt-[22px]">
                    <p>Aplikasi karya Proyek Akhir D3 Rekayasa Perangkat Lunak Aplikasi</p>
                    <p>Noval Abdurramadan - NIM 6706223103</p>
                    <p>Muhammad Raihan Fahrifi - NIM 6706223009</p>
                </div>
            </div>


        </div>

    </div>

@include('includes.navigation')
@endsection

@section('scripts')
<script src="{{ asset('assets/js/accodion.js') }}"></script>
@endsection
