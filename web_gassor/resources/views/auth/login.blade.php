@extends('layouts.app')

@section('content')
    <div id="Background" style="position: absolute; top: 0; width: 100%; height: 100%; background: linear-gradient(180deg, #e6a43b 0%, #f5f5f5 100%)"></div>
    <div class="flex items-center justify-center min-h-screen relative px-5">
        <div class="w-full max-w-md">
            <div class="flex flex-col items-center">
                <h1 class="text-3xl font-bold text-center">Masuk Gassor</h1>
                <p class="mt-2 text-center text-gassor-black">Nggak pakai ribet sewa motor di Gassor langsung gas</p>
            </div>
            <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-5 mt-6">
                @csrf
                <input type="hidden" name="role" id="role" value="{{ request('role') }}" />
                <div class="flex flex-col gap-1">
                    <label for="email" class="text-sm font-medium">Email</label>
                    <input type="email" name="email" id="email" placeholder="Masukkan email anda"
                        class="w-full p-4 bg-[#F5F6F8] border-none outline-none focus:outline-none focus:ring-0 focus:border-none focus:shadow-none"
                        style="border-radius: 12px;"
                        required />
                </div>
                <div class="flex flex-col gap-1">
                    <label for="password" class="text-sm font-medium">Kata Sandi</label>
                    <div style="position: relative;">
                        <input type="password" name="password" id="password" placeholder="Masukkan kata sandi anda"
                            class="w-full p-4 bg-[#F5F6F8] border-none outline-none focus:outline-none focus:ring-0 focus:border-none focus:shadow-none"
                            style="border-radius: 12px;"
                            required />
                        <span onclick="togglePassword()" style="position: absolute; right: 18px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                            <img id="eye-icon" src="{{ asset('assets/images/icons/eye.svg') }}" alt="Show Password" style="width: 22px; height: 22px;">
                        </span>
                    </div>
                </div>
                <div class="flex justify-end mb-2">
                    <a href="{{ route('password.request') }}" class="text-sm text-orange-500 hover:underline" onclick="sessionStorage.setItem('can_access_forgot_password', '1');">Lupa Password?</a>
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
            {{-- Google Login form diluar form awal --}}
            <form id="google-login-form" method="POST" action="{{ route('google.login') }}" class="mt-5">
                @csrf
                <input type="hidden" name="role" id="google-role-input" value="{{ request('role') }}" />
                <button type="button"
                    onclick="submitGoogleLogin()"
                    style="display: flex; align-items: center; justify-content: center; width: 100%; gap: 12px; padding: 16px; font-weight: 500; border: 1px solid #e6a43b; border-radius: 12px; background-color: transparent">
                    <img src="{{ asset('assets/images/icons/google.svg') }}" style="width: 20px; height: 20px" alt="google icon" />
                    Masuk / Daftar
                </button>
            </form>
            <p class="mt-5 text-sm text-center" style="color: #9a9a9a">
                Dengan memilih mendaftar, saya setuju dengan <br>
                <span style="color: #f97316">Persyaratan Layanan</span> dan <span style="color: #f97316">Kebijakan Privasi</span>
            </p>
            <p class="mt-5 text-sm text-center">
                Belum punya akun?
                <a href="{{ route('register', ['role' => request('role')]) }}" style="font-weight: 500; color: #f97316">Daftar</a>
            </p>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.src = "{{ asset('assets/images/icons/eye-off.svg') }}";
            } else {
                passwordInput.type = 'password';
                eyeIcon.src = "{{ asset('assets/images/icons/eye.svg') }}";
            }
        }

        function submitGoogleLogin() {
            var roleSelect = document.getElementById('role');
            var googleRoleInput = document.getElementById('google-role-input');
            if (roleSelect && googleRoleInput) {
                googleRoleInput.value = roleSelect.value;
                document.getElementById('google-login-form').submit();
            }
        }

        // Set session flag ke server saat klik link lupa password
        document.querySelectorAll('a[href$="password/reset"]').forEach(function(link) {
            link.addEventListener('click', function() {
                fetch('{{ route('password.request') }}', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });
                sessionStorage.setItem('can_access_forgot_password', '1');
            });
        });

        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: '{{ $errors->first() }}',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                customClass: {
                    popup: 'text-black'
                },
                color: '#000000'
            });
        @endif

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                customClass: {
                    popup: 'text-black'
                },
                color: '#000000'
            });
        @endif
    </script>
    <script>
        document.cookie.split(';').forEach(function(c) {
            document.cookie = c
                .replace(/^ +/, '')
                .replace(/=.*/, '=;expires=' + new Date().toUTCString() + ';path=/');
        });

        if (window.sessionStorage) sessionStorage.clear();
        if (window.localStorage) localStorage.clear();
    </script>
@endsection
