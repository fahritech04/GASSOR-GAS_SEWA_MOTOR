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
        <a href="{{ route('profile.penyewa') }}" class="flex items-center justify-center w-12 h-12 overflow-hidden bg-white rounded-full shrink-0">
            <img src="assets/images/icons/arrow-left.svg" class="w-[28px] h-[28px]" alt="icon" />
        </a>
        <p class="font-semibold">Edit Akun</p>
        <div class="w-12 dummy-btn"></div>
        </div>

        <div style="position: relative; display: flex; flex-direction: column; padding-left: 1.25rem; padding-right: 1.25rem; margin-top: 2rem">
        <h1 style="font-size: 1.5rem; line-height: 2rem; font-weight: 700"></h1>
        <p style="margin-top: 0.5rem; color: #6b7280">Isi formulir di bawah ini untuk membuat akun</p>
        </div>

        <div class="relative flex flex-col gap-5 px-5 mt-8">
        <!-- Full Name (single column) -->
        <div style="display: flex; flex-direction: column; gap: 0.25rem">
            <label for="fullname" class="text-sm font-medium">Nama Lengkap</label>
            <input type="text" id="fullname" placeholder="Masukan nama lengkap" class="w-full p-4 rounded-full bg-[#F5F6F8] border-none outline-none focus:outline-none focus:ring-0 focus:border-none focus:shadow-none" />
        </div>
        <div style="display: flex; flex-direction: column; gap: 0.25rem">
            <label for="fullname" class="text-sm font-medium">Nama Pengguna</label>
            <input type="text" id="fullname" placeholder="Masukan nama pengguna" class="w-full p-4 rounded-full bg-[#F5F6F8] border-none outline-none focus:outline-none focus:ring-0 focus:border-none focus:shadow-none" />
        </div>

        <!-- Email and Phone Number (two columns) -->
        <div class="form-row">
            <div class="form-col">
            <label for="tempat_lahir" class="text-sm font-semibold text-gray-700 mb-1">Tempat Lahir</label>
            <input type="text" id="tempat_lahir" name="tempat_lahir" placeholder="Masukkan tempat lahir Anda" class="w-full p-4 rounded-full bg-[#F5F6F8] border border-gray-200 focus:border-[#91BF77] focus:ring-2 focus:ring-[#91BF77] transition-all duration-200 text-gray-800 placeholder-gray-400" />
            </div>
            <div class="form-col">
            <label for="tanggal_lahir" class="text-sm font-semibold text-gray-700 mb-1">Tanggal Lahir</label>
            <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="w-full p-4 rounded-full bg-[#F5F6F8] border border-gray-200 focus:border-[#91BF77] focus:ring-2 focus:ring-[#91BF77] transition-all duration-200 text-gray-800 placeholder-gray-400" />
            </div>
        </div>

        <!-- Email and Phone Number (two columns) -->
        <div class="form-row">
            <div class="form-col">
            <label for="email" class="text-sm font-medium">Email</label>
            <input type="email" id="email" placeholder="Enter your email" class="w-full p-4 rounded-full bg-[#F5F6F8] border-none outline-none focus:outline-none focus:ring-0 focus:border-none focus:shadow-none" />
            </div>
            <div class="form-col">
            <label for="phone" class="text-sm font-medium">Nomor Telepon</label>
            <input type="tel" id="phone" placeholder="Enter your phone number" class="w-full p-4 rounded-full bg-[#F5F6F8] border-none outline-none focus:outline-none focus:ring-0 focus:border-none focus:shadow-none" />
            </div>
        </div>

        <button class="w-full p-4 mt-4 font-bold text-white rounded-full bg-gassor-orange">Simpan Akun</button>
        </div>
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
