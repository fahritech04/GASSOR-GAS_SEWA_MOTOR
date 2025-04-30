@extends('layouts.app')

@section('content')
    <div id="Background" style="position: absolute; top: 0; width: 100%; height: 100%; background: linear-gradient(180deg, #e6a43b 0%, #f5f5f5 100%)"></div>
    <div class="relative px-5" style="margin-top: 50px">
        <div class="flex flex-col items-center">
            <h1 class="text-3xl font-bold text-center">Masuk Gassor</h1>
            <p class="mt-2 text-center text-gassor-grey">Nggak pakai ribet sewa motor di Gassor langsung gas</p>
        </div>
        <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-5 mt-6">
            @csrf
            <div class="flex flex-col gap-1">
                <label for="email" class="text-sm font-medium">Email</label>
                <input type="email" name="email" id="email" placeholder="Masukkan email anda"
                    class="w-full p-4 bg-[#F5F6F8] border-none outline-none focus:outline-none focus:ring-0 focus:border-none focus:shadow-none"
                    style="border-radius: 12px;"
                    required />
            </div>
            <div class="flex flex-col gap-1">
                <label for="password" class="text-sm font-medium">Kata Sandi</label>
                <input type="password" name="password" id="password" placeholder="Masukkan kata sandi anda"
                    class="w-full p-4 bg-[#F5F6F8] border-none outline-none focus:outline-none focus:ring-0 focus:border-none focus:shadow-none"
                    style="border-radius: 12px;"
                    required />
            </div>
            <div class="flex flex-col gap-1">
                <label for="role" class="text-sm font-medium">Masuk Sebagai</label>
                <select name="role" id="role"
                    class="w-full p-4 bg-[#F5F6F8] border-none outline-none focus:outline-none focus:ring-0 focus:border-none focus:shadow-none"
                    style="border-radius: 12px;"
                    required>
                    <option value="penyewa">Penyewa</option>
                    <option value="pemilik">Pemilik</option>
                </select>
            </div>
            {{-- Alert placeholder --}}
            <div id="alert-placeholder"></div>
            <button type="submit" class="w-full p-4 mt-4 font-bold text-white" style="background-color: #ff801a; border-radius: 12px;">Masuk</button>
            <div class="flex items-center my-4" style="gap: 12px">
                <div style="flex: 1; height: 1px; background-color: #ffffff"></div>
                <p style="font-size: 0.875rem; line-height: 1.25rem; color: #9ca3af; position: relative; padding: 0 8px">atau</p>
                <div style="flex: 1; height: 1px; background-color: #ffffff"></div>
            </div>
        </form>
        {{-- Google Login Form DILUAR form utama, di bawah OR dan di atas Register --}}
        <form id="google-login-form" method="POST" action="{{ route('google.login') }}" class="mt-5">
            @csrf
            <input type="hidden" name="role" id="google-role-input" />
            <button type="button"
                onclick="submitGoogleLogin()"
                style="display: flex; align-items: center; justify-content: center; width: 100%; gap: 12px; padding: 16px; font-weight: 500; border: 1px solid #e6a43b; border-radius: 12px; background-color: transparent">
                <img src="{{ asset('assets/images/icons/google.svg') }}" style="width: 20px; height: 20px" alt="google icon" />
                Masuk Dengan Google
            </button>
        </form>
        <p class="mt-5 text-sm text-center">
            Belum punya akun?
            <a href="{{ route('register') }}" style="font-medium; color: #f97316">Daftar</a>
        </p>
    </div>
@endsection

@section('scripts')
    <script>
        function submitGoogleLogin() {
            var roleSelect = document.getElementById('role');
            var googleRoleInput = document.getElementById('google-role-input');
            if (roleSelect && googleRoleInput) {
                googleRoleInput.value = roleSelect.value;
                document.getElementById('google-login-form').submit();
            }
        }

        @if ($errors->any())
            Swal.fire({
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
                width: 630,
                heightAuto: false,
                position: 'center',
                background: '#fff',
                html: `
                    <div style="display: flex; align-items: center; height: 100px;">
                        <div>
                            <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                                <circle cx="24" cy="24" r="24" fill="#F87171"/>
                                <path d="M16 16L32 32M32 16L16 32" stroke="white" stroke-width="3" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <div style="margin-left: 24px; text-align: left;">
                            <div style="font-weight: bold; font-size: 1.25rem; color: #111827;">Oops...</div>
                            <div style="font-size: 1rem; color: #374151; margin-top: 2px;">{{ $errors->first() }}</div>
                        </div>
                    </div>
                `,
                didOpen: () => {
                    document.querySelector('.swal2-popup').style.height = '150px';
                }
            });
        @endif

        @if (session('success'))
            Swal.fire({
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
                width: 630,
                heightAuto: false,
                position: 'center',
                background: '#fff',
                html: `
                    <div style="display: flex; align-items: center; height: 100px;">
                        <div>
                            <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                                <circle cx="24" cy="24" r="24" fill="#34D399"/>
                                <path d="M16 24L22 30L32 18" stroke="white" stroke-width="3" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <div style="margin-left: 24px; text-align: left;">
                            <div style="font-weight: bold; font-size: 1.25rem; color: #111827;">Success</div>
                            <div style="font-size: 1rem; color: #374151; margin-top: 2px;">{{ session('success') }}</div>
                        </div>
                    </div>
                `,
                didOpen: () => {
                    document.querySelector('.swal2-popup').style.height = '150px';
                }
            });
        @endif
    </script>
@endsection
