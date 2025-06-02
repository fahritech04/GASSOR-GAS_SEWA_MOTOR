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
            <img src="{{ asset('assets/images/icons/arrow-left.svg') }}" class="w-[28px] h-[28px]" alt="icon" />
        </a>
        <p class="font-semibold">Edit Akun</p>
        <div class="w-12 dummy-btn"></div>
        </div>

        @if(session('success'))
            <div class="alert alert-success" style="background:#d1fae5;color:#065f46;padding:12px 20px;border-radius:8px;margin-bottom:16px;">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger" style="background:#fee2e2;color:#991b1b;padding:12px 20px;border-radius:8px;margin-bottom:16px;">
                {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger" style="background:#fee2e2;color:#991b1b;padding:12px 20px;border-radius:8px;margin-bottom:16px;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('editprofile.penyewa.update') }}" enctype="multipart/form-data" class="relative flex flex-col gap-5 px-5 mt-8">
            @csrf
            <!-- Profile Image Upload with Preview & Edit/Delete -->
            <div style="display: flex; flex-direction: column; gap: 0.75rem">
                <label for="profile_image" class="text-sm font-medium">Foto Profil</label>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div id="profile-image-preview-container" style="width: 120px; height: 120px; border-radius: 50%; overflow: hidden; background: #F5F6F8; display: flex; align-items: center; justify-content: center; border: 2px solid #e6a43b; margin-right: 2rem;">
                        <img id="profile-image-preview"
                            src="{{ old('profile_image_url', isset($user) && $user->profile_image_url ? (Str::startsWith($user->profile_image_url, 'http') ? $user->profile_image_url : asset('storage/' . $user->profile_image_url)) : asset('assets/images/icons/profile-2user.svg')) }}"
                            alt="Preview"
                            style="object-fit: cover; border-radius: 50%; width: {{ (!isset($user) || !$user->profile_image_url) ? '70px' : '100%' }}; height: {{ (!isset($user) || !$user->profile_image_url) ? '70px' : '100%' }}; transition: width 0.2s, height 0.2s; background: #fff;" />
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <input type="file" id="profile_image" name="profile_image" accept="image/*" style="display: none;" onchange="previewProfileImage(event)" />
                        <button type="button" onclick="document.getElementById('profile_image').click()" class="px-4 py-2 rounded-full bg-gassor-orange text-white font-semibold">Ganti Foto</button>
                        <button type="button" onclick="removeProfileImage()" class="px-4 py-2 rounded-full bg-red-400 text-white font-semibold">Hapus Foto</button>
                        <input type="hidden" name="remove_profile_image" id="remove_profile_image" value="0" />
                    </div>
                </div>
            </div>
            <!-- End Profile Image Upload with Preview & Edit/Delete -->

            <!-- Full Name (single column) -->
            <div style="display: flex; flex-direction: column; gap: 0.25rem">
                <label for="fullname" class="text-sm font-medium">Nama Lengkap <span style="color: #dc3545;">*</span></label>
                <input type="text" id="fullname" name="name" value="{{ old('name', isset($user) ? $user->name : '') }}" placeholder="Masukan nama lengkap" class="w-full p-4 rounded-full bg-[#F5F6F8] border-none outline-none focus:outline-none focus:ring-0 focus:border-none focus:shadow-none" data-rule-required="true" required />
            </div>
            <div style="display: flex; flex-direction: column; gap: 0.25rem">
                <label for="username" class="text-sm font-medium">Nama Pengguna <span style="color: #dc3545;">*</span></label>
                <input type="text" id="username" name="username" value="{{ old('username', isset($user) ? $user->username : '') }}" placeholder="Masukan nama pengguna" class="w-full p-4 rounded-full bg-[#F5F6F8] border-none outline-none focus:outline-none focus:ring-0 focus:border-none focus:shadow-none" data-rule-required="true" required />
            </div>

            <!-- Tempat & Tanggal Lahir (two columns) -->
            <div class="form-row">
                <div class="form-col">
                    <label for="tempat_lahir" class="text-sm font-medium text-gray-700 mb-1">Tempat Lahir <span style="color: #dc3545;">*</span></label>
                    <input type="text" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir', isset($user) ? $user->tempat_lahir : '') }}" placeholder="Masukkan tempat lahir Anda" class="w-full p-4 rounded-full bg-[#F5F6F8] border border-gray-200 focus:border-[#91BF77] focus:ring-2 focus:ring-[#91BF77] transition-all duration-200 text-gray-800 placeholder-gray-400" data-rule-required="true" required />
                </div>
                <div class="form-col">
                    <label for="tanggal_lahir" class="text-sm font-medium text-gray-700 mb-1">Tanggal Lahir <span style="color: #dc3545;">*</span></label>
                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', isset($user) && $user->tanggal_lahir ? $user->tanggal_lahir->format('Y-m-d') : '') }}" class="w-full p-4 rounded-full bg-[#F5F6F8] border border-gray-200 focus:border-[#91BF77] focus:ring-2 focus:ring-[#91BF77] transition-all duration-200 text-gray-800 placeholder-gray-400" data-rule-required="true" required />
                </div>
            </div>

            <!-- Email and Phone Number (two columns) -->
            <div class="form-row">
                <div class="form-col">
                    <label for="email" class="text-sm font-medium">Email <span style="color: #dc3545;">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email', isset($user) ? $user->email : '') }}" placeholder="Enter your email" class="w-full p-4 rounded-full bg-[#F5F6F8] border-none outline-none focus:outline-none focus:ring-0 focus:border-none focus:shadow-none" data-rule-required="true" required />
                </div>
                <div class="form-col">
                    <label for="phone" class="text-sm font-medium">Nomor Telepon <span style="color: #dc3545;">*</span></label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone', isset($user) ? $user->phone : '') }}" placeholder="Enter your phone number" class="w-full p-4 rounded-full bg-[#F5F6F8] border-none outline-none focus:outline-none focus:ring-0 focus:border-none focus:shadow-none" data-rule-required="true" required oninput="formatPhoneInput(this)" />
                </div>
            </div>

            <button type="submit" class="w-full p-4 mt-4 font-bold text-white rounded-full bg-gassor-orange">Simpan Akun</button>
        </form>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function formatPhoneInput(input) {
        let val = input.value;
        // Jika dimulai dengan 0, ganti dengan 62
        if (val.startsWith('0')) {
            val = '62' + val.substring(1);
        }
        // Jika tidak dimulai dengan 62, tambahkan 62 di depan
        if (!val.startsWith('62')) {
            val = '62' + val.replace(/^\D+/, '').replace(/^62+/, '');
        }
        input.value = val;
    }
    function previewProfileImage(event) {
        const input = event.target;
        const preview = document.getElementById('profile-image-preview');
        const removeInput = document.getElementById('remove_profile_image');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.width = '100%';
                preview.style.height = '100%';
                removeInput.value = '0';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    function removeProfileImage() {
        const preview = document.getElementById('profile-image-preview');
        const fileInput = document.getElementById('profile_image');
        const removeInput = document.getElementById('remove_profile_image');
        preview.src = "{{ asset('assets/images/icons/profile-2user.svg') }}";
        preview.style.width = '70px';
        preview.style.height = '70px';
        fileInput.value = '';
        removeInput.value = '1';
    }
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: "{{ session('success') }}",
                timer: 2000,
                showConfirmButton: false
            });
        @endif
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: "{{ session('error') }}",
                timer: 2500,
                showConfirmButton: false
            });
        @endif
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ $errors->first() }}",
                timer: 2500,
                showConfirmButton: false
            });
        @endif
    });
</script>
@endsection
