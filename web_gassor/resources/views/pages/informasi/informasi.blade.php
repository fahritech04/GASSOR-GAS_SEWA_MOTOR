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

      {{-- <div class="relative z-10 flex flex-col gap-5 px-5 pb-40" style="margin-top: 20px">
        <a href="#" class="flex items-center justify-between rounded-[30px] p-5 bg-[#F5F6F8]">
          <div class="flex items-center gap-3">
            <img src="{{ asset('assets/images/icons/tentang.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
            <p class="text-lg font-semibold">Tentang Gassor</p>
          </div>
          <img src="{{ asset('assets/images/icons/arrow-right.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
        </a>
        <a href="#" class="flex items-center justify-between rounded-[30px] p-5 bg-[#F5F6F8]">
          <div class="flex items-center gap-3">
            <img src="{{ asset('assets/images/icons/syarat.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
            <p class="text-lg font-semibold">Syarat & Ketentuan</p>
          </div>
          <img src="{{ asset('assets/images/icons/arrow-right.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
        </a>
        <a href="#" class="flex items-center justify-between rounded-[30px] p-5 bg-[#F5F6F8]">
          <div class="flex items-center gap-3">
            <img src="{{ asset('assets/images/icons/privasi.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
            <p class="text-lg font-semibold">Privasi & Kebijakan</p>
          </div>
          <img src="{{ asset('assets/images/icons/arrow-right.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
        </a>
        <a href="#" class="flex items-center justify-between rounded-[30px] p-5 bg-[#F5F6F8]">
          <div class="flex items-center gap-3">
            <img src="{{ asset('assets/images/icons/bantuan.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
            <p class="text-lg font-semibold">Bantuan</p>
          </div>
          <img src="{{ asset('assets/images/icons/arrow-right.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
        </a>
      </div> --}}

        <div class="relative z-10 flex flex-col gap-5 px-5 pb-40" style="margin-top: 50px">
            <div class="accordion group flex flex-col rounded-[30px] p-5 bg-[#F5F6F8] overflow-hidden has-[:checked]:!h-[68px] transition-all duration-300">
                <label class="relative flex items-center justify-between">
                    <p class="text-lg font-semibold">Tentang Gassor</p>
                    <img src="{{ asset('assets/images/icons/arrow-up.svg') }}" class="w-[28px] h-[28px] flex shrink-0 group-has-[:checked]:rotate-180 transition-all duration-300" alt="icon" />
                    <input type="checkbox" class="absolute hidden" />
                </label>
                {{-- <div class="flex flex-col gap-4 pt-[22px]">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('assets/images/icons/profile-2user.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
                            <p class="text-gassor-grey">Full Name</p>
                        </div>
                        <p class="font-semibold">{{ Auth::user()->name }}</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('assets/images/icons/sms.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
                            <p class="text-gassor-grey">Email</p>
                        </div>
                        <p class="font-semibold">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('assets/images/icons/call.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
                            <p class="text-gassor-grey">Phone</p>
                        </div>
                        <p class="font-semibold">628123982138</p>
                    </div>
                    <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('assets/images/icons/location.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
                        <p class="text-gassor-grey">Alamat</p>
                    </div>
                    <p class="font-semibold text-right"></p>
                    </div>
                </div> --}}
            </div>

            <div class="accordion group flex flex-col rounded-[30px] p-5 bg-[#F5F6F8] overflow-hidden has-[:checked]:!h-[68px] transition-all duration-300">
                <label class="relative flex items-center justify-between">
                    <p class="text-lg font-semibold">Privasi & Kebijakan</p>
                    <img src="{{ asset('assets/images/icons/arrow-up.svg') }}" class="w-[28px] h-[28px] flex shrink-0 group-has-[:checked]:rotate-180 transition-all duration-300" alt="icon" />
                    <input type="checkbox" class="absolute hidden" />
                </label>
                {{-- <div class="flex flex-col gap-4 pt-[22px]">
                    <a href="#" class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('assets/images/icons/key.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
                            <p class="text-gassor-grey">Change Password</p>
                        </div>
                        <img src="{{ asset('assets/images/icons/arrow-right.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
                    </a>
                    <a href="#" class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('assets/images/icons/notification.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
                            <p class="text-gassor-grey">Notification Settings</p>
                        </div>
                        <img src="{{ asset('assets/images/icons/arrow-right.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
                    </a>
                </div> --}}
            </div>

            <div class="accordion group flex flex-col rounded-[30px] p-5 bg-[#F5F6F8] overflow-hidden has-[:checked]:!h-[68px] transition-all duration-300">
                <label class="relative flex items-center justify-between">
                    <p class="text-lg font-semibold">Syarat & Ketentuan</p>
                    <img src="{{ asset('assets/images/icons/arrow-up.svg') }}" class="w-[28px] h-[28px] flex shrink-0 group-has-[:checked]:rotate-180 transition-all duration-300" alt="icon" />
                    <input type="checkbox" class="absolute hidden" />
                </label>
                {{-- <div class="flex flex-col gap-4 pt-[22px]">
                    <a href="#" class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('assets/images/icons/key.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
                            <p class="text-gassor-grey">Change Password</p>
                        </div>
                        <img src="{{ asset('assets/images/icons/arrow-right.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
                    </a>
                    <a href="#" class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('assets/images/icons/notification.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
                            <p class="text-gassor-grey">Notification Settings</p>
                        </div>
                        <img src="{{ asset('assets/images/icons/arrow-right.svg') }}" class="flex w-6 h-6 shrink-0" alt="icon" />
                    </a>
                </div> --}}
            </div>
        </div>

    </div>

@include('includes.navigation')
@endsection

@section('scripts')
<script src="{{ asset('assets/js/accodion.js') }}"></script>
@endsection
