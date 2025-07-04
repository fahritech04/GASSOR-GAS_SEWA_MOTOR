@extends('layouts.app')

@section('vendor-style')
<style>
    .form-row { display: flex; gap: 20px; }
    .form-col { flex: 1; display: flex; flex-direction: column; gap: 0.25rem; }
</style>
@endsection

@section('content')
    <div id="Content-Container" class="relative flex flex-col w-full max-w-[640px] min-h-screen mx-auto bg-white overflow-x-hidden">
      <div id="Background" style="position: absolute; top: 0; width: 100%; height: 100%; background: linear-gradient(180deg, #e6a43b 0%, #f5f5f5 100%)"></div>
      <div class="relative flex items-center justify-between px-5 mt-[60px]">
        <a href="{{ route('login') }}" class="flex items-center justify-center w-12 h-12 overflow-hidden bg-white rounded-full shrink-0">
          <img src="{{ asset('assets/images/icons/arrow-left.svg') }}" class="w-[28px] h-[28px]" alt="icon" />
        </a>
        <p class="font-semibold">Buat Akun</p>
        <div class="w-12 dummy-btn"></div>
      </div>
      <div style="position: relative; display: flex; flex-direction: column; padding-left: 1.25rem; padding-right: 1.25rem; margin-top: 2rem">
        <h1 style="font-size: 1.5rem; line-height: 2rem; font-weight: 700">Daftar</h1>
      </div>
      <form method="POST" action="{{ route('register') }}" class="relative flex flex-col gap-5 px-5 mt-8">
        @csrf
        <div class="form-col">
            <label for="email" class="text-sm font-medium">Email</label>
            <input type="email" id="email" name="email" placeholder="Masukkan email anda" class="w-full p-4 rounded-full bg-[#F5F6F8] border-none outline-none focus:outline-none focus:ring-0 focus:border-none focus:shadow-none" style="border-radius: 12px;" required />
        </div>
        <div class="form-col">
            <label for="password" class="text-sm font-medium">Kata Sandi</label>
            <div style="position: relative;">
            <input type="password" id="password" name="password" placeholder="Masukkan kata sandi anda" class="w-full p-4 rounded-full bg-[#F5F6F8] border-none outline-none focus:outline-none focus:ring-0 focus:border-none focus:shadow-none" style="border-radius: 12px;" required />
            <span onclick="togglePassword()" style="position: absolute; right: 18px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                <img id="eye-icon" src="{{ asset('assets/images/icons/eye.svg') }}" alt="Show Password" style="width: 22px; height: 22px;">
            </span>
            </div>
        </div>
        <input type="hidden" name="role" id="role" value="{{ request('role') }}" />
        <button type="submit" class="w-full p-4 mt-4 font-bold text-white rounded-full" style="background-color: #ff801a; border-radius: 12px;">Buat Akun</button>
      </form>
      <p style="margin-top: 1rem; margin-bottom: 2.5rem; font-size: 0.875rem; line-height: 1.25rem; text-align: center">
        Sudah punya akun?
        <a href="{{ route('login') }}" style="font-weight: 500; color: #f97316">Masuk</a>
      </p>
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

    function submitGoogleRegister() {
        var roleSelect = document.getElementById('role');
        var googleRoleInput = document.getElementById('google-register-role-input');
        if (roleSelect && googleRoleInput) {
            googleRoleInput.value = roleSelect.value;
            document.getElementById('google-register-form').submit();
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
@endsection
