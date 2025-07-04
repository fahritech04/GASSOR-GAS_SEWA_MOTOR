@extends('layouts.app')

@section('content')
    <div id="Background" style="position: absolute; top: 0; width: 100%; height: 100%; background: linear-gradient(180deg, #e6a43b 0%, #f5f5f5 100%)"></div>
    <div class="flex items-center justify-center min-h-screen relative px-5">
        <div class="w-full max-w-md">
            <div class="flex flex-col items-center">
                <h2 class="text-3xl font-bold text-center">Reset Kata Sandi</h2>
                <p class="mt-2 text-center text-gassor-grey">Masukkan email dan kata sandi baru anda untuk mengatur ulang kata sandi
                </p>
            </div>
            <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-5 mt-6">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="flex flex-col gap-1">
                    <label for="email" class="text-sm font-medium">Email</label>
                    <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" required
                        class="w-full p-4 bg-[#F5F6F8] border-none outline-none focus:outline-none focus:ring-0 focus:border-none focus:shadow-none"
                        style="border-radius: 12px;" />
                </div>
                <div class="flex flex-col gap-1">
                    <label for="password" class="text-sm font-medium">Kata Sandi Baru</label>
                    <div style="position: relative;">
                        <input id="password" type="password" name="password" required
                            class="w-full p-4 bg-[#F5F6F8] border-none outline-none focus:outline-none focus:ring-0 focus:border-none focus:shadow-none"
                            style="border-radius: 12px;" />
                        <span onclick="togglePassword()"
                            style="position: absolute; right: 18px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                            <img id="eye-icon" src="{{ asset('assets/images/icons/eye.svg') }}" alt="Show Password"
                                style="width: 22px; height: 22px;">
                        </span>
                    </div>
                </div>
                <div class="flex flex-col gap-1">
                    <label for="password-confirm" class="text-sm font-medium">Konfirmasi Kata Sandi</label>
                    <input id="password-confirm" type="password" name="password_confirmation" required
                        class="w-full p-4 bg-[#F5F6F8] border-none outline-none focus:outline-none focus:ring-0 focus:border-none focus:shadow-none"
                        style="border-radius: 12px;" />
                </div>
                <button type="submit"
                    class="w-full p-4 mt-4 font-bold text-white"
                    style="background-color: #ff801a; border-radius: 12px;">Reset Kata Sandi</button>
            </form>
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
        @if (session('status'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('status') }}',
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
@endsection
