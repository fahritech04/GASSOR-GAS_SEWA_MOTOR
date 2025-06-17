@extends('layouts.app')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/dropzone/dropzone.css')}}" />
<style>
    .form-row { display: flex; gap: 20px; }
    .form-col { flex: 1; display: flex; flex-direction: column; gap: 0.25rem; }

    @media (max-width: 600px) {
      .ktp-sim-ktm-upload-col {
        width: 100% !important;
        max-width: 100% !important;
        min-width: 0 !important;
      }
      .ktp-sim-ktm-upload-imgbox {
        width: 100% !important;
        height: 110px !important;
        min-width: 0 !important;
        max-width: 100% !important;
      }
      .ktp-sim-ktm-upload-btn {
        width: 100% !important;
        min-width: 0 !important;
        max-width: 100% !important;
      }
    }
</style>
@endsection

@section('content')
    <div id="Content-Container" class="relative flex flex-col w-full max-w-[640px] min-h-screen mx-auto bg-white overflow-x-hidden">
        <div id="Background" style="position: absolute; top: 0; width: 100%; height: 100%; background: linear-gradient(180deg, #e6a43b 0%, #f5f5f5 100%)"></div>

        <div class="relative flex items-center justify-between px-5 mt-[60px]">
        <a href="{{ route('profile.pemilik') }}" class="flex items-center justify-center w-12 h-12 overflow-hidden bg-white rounded-full shrink-0">
            <img src="{{ asset('assets/images/icons/arrow-left.svg') }}" class="w-[28px] h-[28px]" alt="icon" />
        </a>
        <p class="font-semibold">Akun Saya</p>
        <div class="w-12 dummy-btn"></div>
        </div>

        <form method="POST" action="{{ route('editprofile.pemilik.update') }}" enctype="multipart/form-data" class="relative flex flex-col gap-5 px-5 mt-8">
            @csrf
            <div style="display: flex; flex-direction: column; gap: 0.75rem">
                <label for="profile_image" class="text-sm font-medium">Foto Profil</label>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div id="profile-image-preview-container" style="width: 120px; height: 120px; border-radius: 50%; overflow: hidden; background: #F5F6F8; display: flex; align-items: center; justify-content: center; border: 2px solid #e6a43b; margin-right: 2rem;">
                        <img id="profile-image-preview"
                            src="{{ old('profile_image_url', isset($user) && $user->profile_image_url ? (Str::startsWith($user->profile_image_url, 'http') ? $user->profile_image_url : asset('storage/' . $user->profile_image_url)) : asset('assets/images/photos/default_profil.jpg')) }}"
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

            <div style="display: flex; flex-direction: column; gap: 0.25rem">
                <label for="fullname" class="text-sm font-medium">Nama Lengkap <span style="color: #dc3545;">*</span></label>
                <input type="text" id="fullname" name="name" value="{{ old('name', isset($user) ? $user->name : '') }}" placeholder="Masukan nama lengkap" class="w-full p-4 rounded-full bg-[#F5F6F8] border-none outline-none focus:outline-none focus:ring-0 focus:border-none focus:shadow-none" data-rule-required="true" required />
            </div>
            <div style="display: flex; flex-direction: column; gap: 0.25rem">
                <label for="username" class="text-sm font-medium">Nama Pengguna <span style="color: #dc3545;">*</span></label>
                <input type="text" id="username" name="username" value="{{ old('username', isset($user) ? $user->username : '') }}" placeholder="Masukan nama pengguna" class="w-full p-4 rounded-full bg-[#F5F6F8] border-none outline-none focus:outline-none focus:ring-0 focus:border-none focus:shadow-none" data-rule-required="true" required />
            </div>

            <div class="form-row">
                <div class="form-col">
                    <label for="tempat_lahir" class="text-sm font-medium text-gray-700 mb-1">Tempat Lahir <span style="color: #dc3545;">*</span></label>
                    <input type="text" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir', isset($user) ? $user->tempat_lahir : '') }}" placeholder="Masukkan tempat lahir Anda" class="w-full p-4 rounded-full bg-[#F5F6F8] border border-gray-200 focus:border-[#E6A43B] focus:ring-2 focus:ring-[#E6A43B] transition-all duration-200 text-gray-800 placeholder-gray-400" data-rule-required="true" required />
                </div>
                <div class="form-col">
                    <label for="tanggal_lahir" class="text-sm font-medium text-gray-700 mb-1">Tanggal Lahir <span style="color: #dc3545;">*</span></label>
                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', isset($user) && $user->tanggal_lahir ? $user->tanggal_lahir->format('Y-m-d') : '') }}" class="w-full p-4 rounded-full bg-[#F5F6F8] border border-gray-200 focus:border-[#E6A43B] focus:ring-2 focus:ring-[#E6A43B] transition-all duration-200 text-gray-800 placeholder-gray-400" data-rule-required="true" required />
                </div>
            </div>

            <div class="form-row">
                <div class="form-col">
                    <label for="email" class="text-sm font-medium">Email <span style="color: #dc3545;">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email', isset($user) ? $user->email : '') }}" placeholder="Enter your email" class="w-full p-4 rounded-full bg-[#F5F6F8] border-none outline-none focus:outline-none focus:ring-0 focus:border-none focus:shadow-none" data-rule-required="true" required />
                </div>
                <div class="form-col">
                    <label for="phone" class="text-sm font-medium">Nomor Telepon <span style="color: #dc3545;">*</span></label>
                    <input type="number" id="phone" name="phone" value="{{ old('phone', isset($user) ? $user->phone : '') }}" placeholder="Masukkan dengan 62" class="w-full p-4 rounded-full bg-[#F5F6F8] border-none outline-none focus:outline-none focus:ring-0 focus:border-none focus:shadow-none" data-rule-required="true" required oninput="formatPhoneInput(this)" />
                </div>
            </div>

            <div class="form-row" style="gap: 24px; margin-bottom: 1.5rem; flex-wrap: wrap;">
                <div class="form-col ktp-sim-ktm-upload-col" style="align-items:center; min-width:180px; max-width:180px;">
                    <label for="ktp_image" class="text-sm font-medium mb-2">Upload KTP</label>
                    <div style="width: 180px; display: flex; flex-direction: column; align-items: center;">
                        <div class="ktp-sim-ktm-upload-imgbox" style="width: 180px; height: 120px; border-radius: 10px; border: 1.5px solid #e6a43b; background: #f5f5f5; display: flex; align-items: center; justify-content: center; cursor: pointer; position: relative; overflow: hidden; margin-bottom: 8px;" onclick="document.getElementById('ktp_image').click()">
                            <img id="ktp-preview" src="{{ old('ktp_image_url', isset($user) && $user->ktp_image_url ? asset('storage/' . $user->ktp_image_url) : '' ) }}" style="max-width: 100%; max-height: 100%; object-fit: cover; display: {{ (isset($user) && $user->ktp_image_url) ? 'block' : 'none' }};" alt="Preview KTP" />
                            <div id="ktp-placeholder" style="display:{{ (isset($user) && $user->ktp_image_url) ? 'none' : 'flex' }}; align-items:center; justify-content:center; width:100%; height:100%; color:#aaa; font-size:14px;">Klik untuk upload KTP</div>
                        </div>
                        <input type="file" name="ktp_image" id="ktp_image" accept="image/*" style="display:none;" onchange="previewImage(event, 'ktp-preview')">
                        {{-- <button type="button" class="btn btn-danger w-100 ktp-sim-ktm-upload-btn" style="width: 100%; margin-top: 2px;" onclick="removeImage('ktp')">Hapus Foto</button> --}}
                        <input type="hidden" name="remove_ktp_image" id="remove_ktp_image" value="0" />
                    </div>
                </div>
                <div class="form-col ktp-sim-ktm-upload-col" style="align-items:center; min-width:180px; max-width:180px;">
                    <label for="sim_image" class="text-sm font-medium mb-2">Upload SIM</label>
                    <div style="width: 180px; display: flex; flex-direction: column; align-items: center;">
                        <div class="ktp-sim-ktm-upload-imgbox" style="width: 180px; height: 120px; border-radius: 10px; border: 1.5px solid #e6a43b; background: #f5f5f5; display: flex; align-items: center; justify-content: center; cursor: pointer; position: relative; overflow: hidden; margin-bottom: 8px;" onclick="document.getElementById('sim_image').click()">
                            <img id="sim-preview" src="{{ old('sim_image_url', isset($user) && $user->sim_image_url ? asset('storage/' . $user->sim_image_url) : '' ) }}" style="max-width: 100%; max-height: 100%; object-fit: cover; display: {{ (isset($user) && $user->sim_image_url) ? 'block' : 'none' }};" alt="Preview SIM" />
                            <div id="sim-placeholder" style="display:{{ (isset($user) && $user->sim_image_url) ? 'none' : 'flex' }}; align-items:center; justify-content:center; width:100%; height:100%; color:#aaa; font-size:14px;">Klik untuk upload SIM</div>
                        </div>
                        <input type="file" name="sim_image" id="sim_image" accept="image/*" style="display:none;" onchange="previewImage(event, 'sim-preview')">
                        {{-- <button type="button" class="btn btn-danger w-100 ktp-sim-ktm-upload-btn" style="width: 100%; margin-top: 2px;" onclick="removeImage('sim')">Hapus Foto</button> --}}
                        <input type="hidden" name="remove_sim_image" id="remove_sim_image" value="0" />
                    </div>
                </div>
                <div class="form-col ktp-sim-ktm-upload-col" style="align-items:center; min-width:180px; max-width:180px;">
                    <label for="ktm_image" class="text-sm font-medium mb-2">Upload KTM</label>
                    <div style="width: 180px; display: flex; flex-direction: column; align-items: center;">
                        <div class="ktp-sim-ktm-upload-imgbox" style="width: 180px; height: 120px; border-radius: 10px; border: 1.5px solid #e6a43b; background: #f5f5f5; display: flex; align-items: center; justify-content: center; cursor: pointer; position: relative; overflow: hidden; margin-bottom: 8px;" onclick="document.getElementById('ktm_image').click()">
                            <img id="ktm-preview" src="{{ old('ktm_image_url', isset($user) && $user->ktm_image_url ? asset('storage/' . $user->ktm_image_url) : '' ) }}" style="max-width: 100%; max-height: 100%; object-fit: cover; display: {{ (isset($user) && $user->ktm_image_url) ? 'block' : 'none' }};" alt="Preview KTM" />
                            <div id="ktm-placeholder" style="display:{{ (isset($user) && $user->ktm_image_url) ? 'none' : 'flex' }}; align-items:center; justify-content:center; width:100%; height:100%; color:#aaa; font-size:14px;">Klik untuk upload KTM</div>
                        </div>
                        <input type="file" name="ktm_image" id="ktm_image" accept="image/*" style="display:none;" onchange="previewImage(event, 'ktm-preview')">
                        {{-- <button type="button" class="btn btn-danger w-100 ktp-sim-ktm-upload-btn" style="width: 100%; margin-top: 2px;" onclick="removeImage('ktm')">Hapus Foto</button> --}}
                        <input type="hidden" name="remove_ktm_image" id="remove_ktm_image" value="0" />
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full p-4 mt-4 font-bold text-white rounded-full bg-gassor-orange">Simpan Akun</button>
        </form>
    </div>
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/dropzone/dropzone.js')}}"></script>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function formatPhoneInput(input) {
        let val = input.value;
        if (val.startsWith('0')) {
            val = '62' + val.substring(1);
        }
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
        preview.src = "{{ asset('assets/images/photos/default_profil.jpg') }}";
        preview.style.width = '70px';
        preview.style.height = '70px';
        fileInput.value = '';
        removeInput.value = '1';
    }
    function previewImage(event, previewId) {
        const input = event.target;
        const preview = document.getElementById(previewId);
        const placeholder = document.getElementById(previewId.replace('-preview', '-placeholder'));
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                if (placeholder) placeholder.style.display = 'none';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    function removeImage(type) {
        const preview = document.getElementById(type+'-preview');
        const placeholder = document.getElementById(type+'-placeholder');
        const input = document.getElementById(type+'_image');
        const removeInput = document.getElementById('remove_'+type+'_image');
        if (preview) {
            preview.src = '';
            preview.style.display = 'none';
        }
        if (placeholder) placeholder.style.display = 'flex';
        if (input) input.value = '';
        if (removeInput) removeInput.value = '1';
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
        // Validasi wajib isi semua kolom (termasuk upload KTP, SIM, KTM)
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            let requiredFields = [
                'name', 'username', 'tempat_lahir', 'tanggal_lahir', 'email', 'phone'
            ];
            let isValid = true;
            let firstEmpty = null;
            // Cek input text, email, dll
            requiredFields.forEach(function(field) {
                let input = document.getElementsByName(field)[0];
                if (input && input.value.trim() === '') {
                    isValid = false;
                    if (!firstEmpty) firstEmpty = input;
                }
            });
            // Cek upload KTP, SIM, KTM wajib ada (preview harus ada gambarnya)
            ['ktp', 'sim', 'ktm'].forEach(function(type) {
                let preview = document.getElementById(type+'-preview');
                let removeInput = document.getElementById('remove_'+type+'_image');
                // Cek jika tidak ada gambar sama sekali (src kosong atau default)
                if (!preview || !preview.src || preview.src === '' || preview.style.display === 'none' || (removeInput && removeInput.value === '1')) {
                    isValid = false;
                    if (!firstEmpty) firstEmpty = document.getElementById(type+'_image');
                }
            });
            if (!isValid) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Semua kolom wajib diisi!!!',
                    timer: 2000,
                    showConfirmButton: false
                });
                if (firstEmpty) firstEmpty.focus();
            }
        });
    });
</script>
@endsection
