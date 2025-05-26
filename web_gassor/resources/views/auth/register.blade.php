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
        <p style="margin-top: 0.5rem; color: #6b7280">Isi formulir di bawah ini untuk membuat akun</p>
      </div>
      <form method="POST" action="{{ route('register') }}" class="relative flex flex-col gap-5 px-5 mt-8">
        @csrf
        <div class="form-col">
            <label for="email" class="text-sm font-medium">Email</label>
            <input type="email" id="email" name="email" placeholder="Masukkan email anda" class="w-full p-4 rounded-full bg-[#F5F6F8] border-none outline-none focus:outline-none focus:ring-0 focus:border-none focus:shadow-none" style="border-radius: 12px;" required />
        </div>
        <div class="form-col">
            <label for="password" class="text-sm font-medium">Kata Sandi</label>
            <input type="password" id="password" name="password" placeholder="Masukkan kata sandi anda" class="w-full p-4 rounded-full bg-[#F5F6F8] border-none outline-none focus:outline-none focus:ring-0 focus:border-none focus:shadow-none" style="border-radius: 12px;" required />
        </div>
        <div class="form-col">
        <label for="role" class="text-sm font-medium">Daftar Sebagai</label>
        <select id="role" name="role" class="w-full p-4 rounded-full bg-[#F5F6F8] border-none outline-none focus:outline-none focus:ring-0 focus:border-none focus:shadow-none" style="border-radius: 12px;" required>
            <option value="penyewa">Penyewa</option>
            <option value="pemilik">Pemilik</option>
        </select>
        </div>
        <button type="submit" class="w-full p-4 mt-4 font-bold text-white rounded-full" style="background-color: #ff801a; border-radius: 12px;">Buat Akun</button>
        <div class="flex items-center my-4" style="gap: 12px">
          <div style="flex: 1; height: 1px; background-color: #ffffff"></div>
          <p style="font-size: 0.875rem; line-height: 1.25rem; color: #9ca3af; position: relative; padding: 0 8px">atau</p>
          <div style="flex: 1; height: 1px; background-color: #ffffff"></div>
        </div>
      </form>
      {{-- Google Register Form DILUAR form utama, di bawah OR dan di atas Login --}}
      <form id="google-register-form" method="POST" action="{{ route('google.login') }}" class="mt-2 px-5">
        @csrf
        <input type="hidden" name="role" id="google-register-role-input" />
        <button type="button"
            onclick="submitGoogleRegister()"
            style="display: flex; align-items: center; justify-content: center; width: 100%; gap: 12px; padding: 16px; font-weight: 500; border: 1px solid #e6a43b; border-radius: 9999px; background-color: transparent">
            <img src="{{ asset('assets/images/icons/google.svg') }}" style="width: 20px; height: 20px border-radius: 12px;" alt="google icon" />
            Daftar dengan Google
        </button>
      </form>
      <p style="margin-top: 1rem; margin-bottom: 2.5rem; font-size: 0.875rem; line-height: 1.25rem; text-align: center">
        Sudah punya akun?
        <a href="{{ route('login') }}" style="font-weight: 500; color: #f97316">Masuk</a>
      </p>
    </div>
@endsection

@section('scripts')
<script>
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
            showConfirmButton: false,
            timer: 3500,
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
            timer: 3500,
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
