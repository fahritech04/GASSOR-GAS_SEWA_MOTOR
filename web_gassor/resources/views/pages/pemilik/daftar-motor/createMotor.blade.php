@extends('layouts.app')

@section('vendor-style')
<style>
    body, #Content-Container {
        background: #dae1e9;
    }
    .gassor-card {
        background: #e6a43b;
        padding: 2.5rem 2rem 2rem 2rem;
        color: #fff;
    }
    .tab-buttons {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 2rem;
    }
    .tab-button {
        padding: 12px 32px;
        background: #e0caa5;
        border: none;
        border-radius: 12px 12px 0 0;
        cursor: pointer;
        font-weight: 500;
        color: #2d2d2d;
        transition: all 0.3s;
        outline: none;
    }
    .tab-button.active {
        background: #23242b;
        color: #ffffff;
    }
    .tab-content {
        display: none;
        padding: 1rem 1rem 1rem 1rem;
        margin-bottom: 1rem;
    }
    .tab-content.active {
        display: block;
    }
    .form-label {
        color: #2d2d2d;
        font-weight: 500;
        margin-bottom: 0.25rem;
    }
    .form-input, .form-select, .form-textarea {
        background: #ffffff;
        color: #2d2d2d;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
        width: 100%;
        outline: none;
        font-size: 1rem;
    }
    .form-input:focus, .form-select:focus, .form-textarea:focus {
        border-color: #e6a43b;
        box-shadow: 0 0 0 2px #e6a43b33;
    }
    .upload-area {
        border: 2px dashed #e6a43b;
        border-radius: 12px;
        padding: 40px;
        text-align: center;
        background: #ffffff;
        cursor: pointer;
        transition: all 0.3s ease;
        color: #2d2d2d;
    }
    .image-preview {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #e6a43b;
        margin-top: 0.5rem;
    }
    .bonus-item, .motor-item {
        background: #e0caa5;
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        color: #fff;
    }
    .remove-btn {
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 8px 16px;
        cursor: pointer;
        font-size: 14px;
        margin-top: 1rem;
    }
    .add-btn {
        background: #23242b;
        color: #ffffff;
        border: none;
        border-radius: 8px;
        padding: 14px 14px;
        cursor: pointer;
        font-weight: 600;
        margin-top: 1rem;
    }
    .form-row {
        display: flex;
        gap: 1rem;
    }
    .form-col {
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .gassor-btn-primary {
        background: #23242b;
        color: #ffffff;
        border: none;
        border-radius: 16px;
        font-weight: bold;
        padding: 1rem 0;
        width: 100%;
        margin-right: 0.5rem;
        transition: background 0.2s;
    }
    .gassor-btn-secondary {
        background: #dc3545;
        color: #ffffff;
        border: 2px solid #e6a43b;
        border-radius: 16px;
        font-weight: bold;
        padding: 1rem 0;
        width: 100%;
        margin-left: 0.5rem;
        transition: background 0.2s;
    }
    @media (max-width: 600px) {
        .gassor-card { padding: 1rem 0.5rem; }
        .tab-buttons { flex-wrap: wrap; gap: 8px; }
        .tab-button { padding: 10px 12px; font-size: 14px; }
        .tab-content { padding: 1rem 0.5rem; }
    }
    .info-card {
        background: linear-gradient(135deg, #f8faff 0%, #e8f2ff 100%);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1rem;
    }
    .info-card h3 {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .info-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    @media (min-width: 640px) {
        .info-grid {
            grid-template-columns: 1fr 1fr;
        }
    }
    .info-item strong {
        color: #374151;
        font-weight: 600;
        display: block;
        margin-bottom: 0.25rem;
    }
    .info-item p {
        color: #111827;
        margin: 0;
        word-wrap: break-word;
    }
</style>
@endsection

@section('content')
<div id="Content-Container" class="relative flex flex-col w-full max-w-[640px] min-h-screen mx-auto bg-transparent overflow-x-hidden">
    <div class="gassor-card">
        <div id="TopNav" class="relative flex items-center justify-between" style="margin-bottom: 20px; margin-top: 20px;">
            <a href="{{ route('pemilik.daftar-motor') }}"
                class="w-12 h-12 flex items-center justify-center shrink-0 rounded-full overflow-hidden bg-white">
                <img src="{{ asset('assets/images/icons/arrow-left.svg') }}" class="w-[28px] h-[28px]" alt="icon">
            </a>
            <p class="font-semibold">Tambah Motor</p>
            <div class="dummy-btn w-12"></div>
        </div>
        <form method="POST" action="{{ route('pemilik.daftar-motor.store') }}" enctype="multipart/form-data" class="flex flex-col">
            @csrf
            @if(isset($hasExistingRental) && $hasExistingRental && $existingRental)
                <input type="hidden" name="use_existing_rental" value="1">
                <input type="hidden" name="existing_rental_id" value="{{ $existingRental->id }}">
                <div style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border: 2px solid #86efac; color: #166534; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; font-weight: 500;">
                    <strong>Info:</strong> Anda sudah memiliki rental "<strong>{{ $existingRental->name }}</strong>" yang aktif.
                    Motor baru akan ditambahkan ke rental tersebut.
                </div>
                <div class="tab-buttons">
                    <button type="button" class="tab-button active" onclick="showTab('motor')">Motor</button>
                    <button type="button" class="tab-button" onclick="showTab('bonus-sewa')">Bonus Sewa</button>
                    <button type="button" class="tab-button" onclick="showTab('checklist-fisik')">Checklist Fisik</button>
                    <button type="button" class="tab-button" onclick="showTab('informasi-umum')" style="opacity: 0.7;">Informasi Umum (Auto)</button>
                </div>
            @else
                <div class="tab-buttons">
                    <button type="button" class="tab-button active" onclick="showTab('informasi-umum')">Informasi Umum</button>
                    <button type="button" class="tab-button" onclick="showTab('bonus-sewa')">Bonus Sewa</button>
                    <button type="button" class="tab-button" onclick="showTab('checklist-fisik')">Checklist Fisik</button>
                    <button type="button" class="tab-button" onclick="showTab('motor')">Motor</button>
                </div>
            @endif
            <!-- Tab Informasi Umum -->
            @if(isset($hasExistingRental) && $hasExistingRental && $existingRental)
                <div id="informasi-umum" class="tab-content">
                    <div class="info-card">
                        <h3 style="color: #000000;">Informasi Rental Saat Ini</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <strong>Nama Rental:</strong>
                                <p>{{ $existingRental->name }}</p>
                            </div>
                            <div class="info-item">
                                <strong>Alamat:</strong>
                                <p>{{ $existingRental->address }}</p>
                            </div>
                            <div class="info-item">
                                <strong>Deskripsi:</strong>
                                <p>{{ Str::limit($existingRental->description, 100) }}</p>
                            </div>
                            <div class="info-item">
                                <strong>Thumbnail:</strong>
                                @if($existingRental->thumbnail)
                                    <img src="{{ asset('storage/'.$existingRental->thumbnail) }}" alt="Thumbnail" class="image-preview">
                                @else
                                    <p style="color: #6b7280;">Tidak ada thumbnail</p>
                                @endif
                            </div>
                        </div>
                        <p style="color: #000000; font-size: 0.9rem; margin-top: 1rem; font-weight: 500;">
                            Motor baru akan ditambahkan ke rental yang sudah ada.
                            Untuk mengedit informasi rental, gunakan menu Edit Motor.
                        </p>
                    </div>
                </div>
            @else
                <!-- Untuk pengguna baru formulir lengkap -->
                <div id="informasi-umum" class="tab-content active">
                    <div class="flex flex-col gap-4">
                        <div>
                            <label class="form-label">Thumbnail <span style="color: #dc3545;">*</span></label>
                            <div class="upload-area" onclick="document.getElementById('thumbnail').click()">
                                <span>Seret & Lepas file Anda atau <b>Telusuri</b></span>
                                <input type="file" id="thumbnail" name="thumbnail" accept="image/*" style="display: none;" onchange="previewImage(event, 'thumbnail-preview')" required />
                            </div>
                            <div id="thumbnail-preview" style="display: none;">
                                <img class="image-preview" alt="Pratinjau">
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Nama <span style="color: #dc3545;">*</span></label>
                            <input type="text" name="name" id="rental-name" class="form-input" placeholder="Masukkan nama rental" required oninput="generateSlug()" />
                        </div>
                        <div>
                            <label class="form-label">Slug <span style="color: #dc3545;">*</span></label>
                            <input type="text" name="slug" id="rental-slug" class="form-input" placeholder="Slug otomatis" required readonly/>
                        </div>
                        <div class="form-row">
                            <div class="form-col">
                                <label class="form-label">Kota <span style="color: #dc3545;">*</span></label>
                                <select name="city_id" class="form-select" required>
                                    <option value="">Pilih kota</option>
                                    <option value="1">Bojongsoang</option>
                                    <option value="2">Sukapura</option>
                                    <option value="3">Sukabirus</option>
                                </select>
                            </div>
                            {{-- <div class="form-col">
                                <label class="form-label">Kategori <span style="color: #dc3545;">*</span></label>
                            </div> --}}
                        </div>
                        <div>
                            <label class="form-label">Deskripsi <span style="color: #dc3545;">*</span></label>
                            <textarea name="description" class="form-textarea" rows="4" placeholder="Masukkan deskripsi" required></textarea>
                        </div>
                        <div>
                            <label class="form-label">Alamat <span style="color: #dc3545;">*</span></label>
                            <textarea name="address" class="form-textarea" rows="3" placeholder="Masukkan alamat lengkap" required></textarea>
                        </div>
                    </div>
                </div>
            @endif
            <!-- Tab Bonus Sewa -->
            <div id="bonus-sewa" class="tab-content">
                @if(isset($hasExistingRental) && $hasExistingRental && $existingRental && $existingRental->bonuses && count($existingRental->bonuses))
                    <div style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border: 2px solid #86efac; color: #166534; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; font-weight: 500;"><strong>Info:</strong> Data bonus berikut diambil dari rental "<strong>{{ $existingRental->name }}</strong>" yang sudah ada. Anda dapat mengedit atau menambah bonus baru di bawah ini.</div>
                @endif
                <div id="bonus-container">
                    @php $bonusCount = 0; @endphp
                    @if(isset($hasExistingRental) && $hasExistingRental && $existingRental && $existingRental->bonuses && count($existingRental->bonuses))
                        @foreach($existingRental->bonuses as $bonus)
                        <div class="bonus-item">
                            <div class="flex flex-col gap-4">
                                <div>
                                    <label class="form-label">Gambar <span style="color: #888;">(opsional)</span></label>
                                    <div class="upload-area" onclick="document.getElementById('bonus_image_{{ $bonusCount }}').click()">
                                        <span>Seret & Lepas file Anda atau <b>Telusuri</b></span>
                                        <input type="file" id="bonus_image_{{ $bonusCount }}" name="bonuses[{{ $bonusCount }}][image]" accept="image/*" style="display: none;" onchange="previewImage(event, 'bonus_preview_{{ $bonusCount }}')" />
                                    </div>
                                    <div id="bonus_preview_{{ $bonusCount }}" style="display: {{ $bonus->image ? 'block' : 'none' }};">
                                        @if($bonus->image)
                                            <img class="image-preview" src="{{ asset('storage/'.$bonus->image) }}" alt="Pratinjau">
                                            <button type='button' class='remove-btn' style='margin-left:12px;margin-top:8px;' onclick='removeSingleImage(this, "bonus_image_{{ $bonusCount }}", "bonus_preview_{{ $bonusCount }}")'>Hapus</button>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label">Nama Bonus <span style="color: #888;">(opsional)</span></label>
                                    <input type="text" name="bonuses[{{ $bonusCount }}][name]" class="form-input" value="{{ old('bonuses.'.$bonusCount.'.name', $bonus->name) }}" placeholder="Contoh: Helm" />
                                </div>
                                <div>
                                    <label class="form-label">Deskripsi <span style="color: #888;">(opsional)</span></label>
                                    <input type="text" name="bonuses[{{ $bonusCount }}][description]" class="form-input" value="{{ old('bonuses.'.$bonusCount.'.description', $bonus->description) }}" placeholder="Contoh: 1 Helm" />
                                </div>
                                <button type="button" class="remove-btn self-end" onclick="removeBonus(this)">Hapus</button>
                            </div>
                        </div>
                        @php $bonusCount++; @endphp
                        @endforeach
                    @else
                        <div class="bonus-item">
                            <div class="flex flex-col gap-4">
                                <div>
                                    <label class="form-label">Gambar <span style="color: #888;">(opsional)</span></label>
                                    <div class="upload-area" onclick="document.getElementById('bonus_image_0').click()">
                                        <span>Seret & Lepas file Anda atau <b>Telusuri</b></span>
                                        <input type="file" id="bonus_image_0" name="bonuses[0][image]" accept="image/*" style="display: none;" onchange="previewImage(event, 'bonus_preview_0')" />
                                    </div>
                                    <div id="bonus_preview_0" style="display: none;">
                                        <img class="image-preview" alt="Pratinjau">
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label">Nama Bonus <span style="color: #888;">(opsional)</span></label>
                                    <input type="text" name="bonuses[0][name]" class="form-input" placeholder="Contoh: Helm" />
                                </div>
                                <div>
                                    <label class="form-label">Deskripsi <span style="color: #888;">(opsional)</span></label>
                                    <input type="text" name="bonuses[0][description]" class="form-input" placeholder="Contoh: 1 Helm" />
                                </div>
                                <button type="button" class="remove-btn self-end" onclick="removeBonus(this)">Hapus</button>
                            </div>
                        </div>
                    @endif
                </div>
                <button type="button" class="add-btn" onclick="addBonus()">Tambah Bonus</button>
            </div>
            <!-- Tab Checklist Fisik -->
            <div id="checklist-fisik" class="tab-content">
                <div class="info-card">
                    <h3 style="color: #000000;">Checklist Pemeriksaan Fisik Motor</h3>
                    <div class="flex flex-col gap-2" id="checklist-fisik-list">
                        <label style="color:#2d2d2d;font-weight:500;"><input type="checkbox" class="checklist-fisik-item" name="checklist_fisik[]" value="ban"> Ban</label>
                        <label style="color:#2d2d2d;font-weight:500;"><input type="checkbox" class="checklist-fisik-item" name="checklist_fisik[]" value="baret"> Baret/Bodi Lecet</label>
                        <label style="color:#2d2d2d;font-weight:500;"><input type="checkbox" class="checklist-fisik-item" name="checklist_fisik[]" value="rem"> Rem</label>
                        <label style="color:#2d2d2d;font-weight:500;"><input type="checkbox" class="checklist-fisik-item" name="checklist_fisik[]" value="oli"> Oli</label>
                        <label style="color:#2d2d2d;font-weight:500;"><input type="checkbox" class="checklist-fisik-item" name="checklist_fisik[]" value="lampu"> Lampu</label>
                        <label style="color:#2d2d2d;font-weight:500;"><input type="checkbox" class="checklist-fisik-item" name="checklist_fisik[]" value="spion"> Spion</label>
                        <label style="color:#2d2d2d;font-weight:500;"><input type="checkbox" class="checklist-fisik-item" name="checklist_fisik[]" value="klakson"> Klakson</label>
                        <label style="color:#2d2d2d;font-weight:500;"><input type="checkbox" class="checklist-fisik-item" name="checklist_fisik[]" value="standar"> Standar Samping/Tengah</label>
                        <label style="color:#2d2d2d;font-weight:500;"><input type="checkbox" class="checklist-fisik-item" name="checklist_fisik[]" value="kunci"> Kunci Kontak</label>
                        <label style="color:#2d2d2d;font-weight:500;"><input type="checkbox" class="checklist-fisik-item" name="checklist_fisik[]" value="body"> Body Motor</label>
                        <label style="color:#2d2d2d;font-weight:500;"><input type="checkbox" class="checklist-fisik-item" name="checklist_fisik[]" value="speedometer"> Speedometer</label>
                        <label style="color:#2d2d2d;font-weight:500;"><input type="checkbox" class="checklist-fisik-item" name="checklist_fisik[]" value="rantai"> Rantai/Drive Belt</label>
                        <label style="color:#2d2d2d;font-weight:500;"><input type="checkbox" class="checklist-fisik-item" name="checklist_fisik[]" value="knalpot"> Knalpot</label>
                        <label style="color:#2d2d2d;font-weight:500;"><input type="checkbox" class="checklist-fisik-item" name="checklist_fisik[]" value="radiator"> Radiator/Coolant</label>
                        <label style="color:#2d2d2d;font-weight:500;"><input type="checkbox" class="checklist-fisik-item" name="checklist_fisik[]" value="busi"> Busi</label>
                        <label style="color:#2d2d2d;font-weight:500;"><input type="checkbox" class="checklist-fisik-item" name="checklist_fisik[]" value="accu"> Accu/Aki</label>
                        <label style="color:#2d2d2d;font-weight:500;"><input type="checkbox" class="checklist-fisik-item" name="checklist_fisik[]" value="tutup_tangki"> Tutup Tangki</label>
                        <label style="color:#2d2d2d;font-weight:500;"><input type="checkbox" class="checklist-fisik-item" name="checklist_fisik[]" value="ban_serep"> Ban Serep (jika ada)</label>
                    </div>
                    <div style="margin-top:1.5rem;">
                        <label class="form-label">Upload Video Pemeriksaan Fisik Motor <span style="color:#dc3545;">*</span></label>
                        <div class="upload-area" onclick="document.getElementById('video_fisik').click()">
                            <span>Pilih dari Galeri / Kamera</span>
                            <input type="file" id="video_fisik" name="video_fisik" accept="video/mp4,video/3gp,video/mov" capture="environment" style="display:none;" required onchange="previewVideo(event)" />
                        </div>
                        <div id="video_fisik_preview" style="display:none;margin-top:10px;"></div>
                        <small style="color:#374151;">Format: mp4, mov, 3gp. Maksimal 100MB. Wajib upload video dari HP (kamera/galeri).</small>
                    </div>
                    <div id="checklist-fisik-warning" style="color:#dc3545;display:none;margin-top:8px;font-weight:500;">Checklist & video harus lengkap sebelum menambah motor!</div>
                </div>
            </div>
            <!-- Tab Motor -->
            <div id="motor" class="tab-content @if(isset($hasExistingRental) && $hasExistingRental) active @endif">
                <div id="motor-container">
                    <div class="motor-item">
                        <div class="flex flex-col gap-4">
                            <div>
                                <label class="form-label">Pemilik<span style="color: #dc3545;">*</span></label>
                                <select name="motorcycles[0][owner_id]" class="form-select" required readonly>
                                    <option value="{{ auth()->id() }}">{{ auth()->user()->name }}</option>
                                </select>
                            </div>
                            <div class="form-row">
                                <div class="form-col">
                                    <label class="form-label">Nama Motor<span style="color: #dc3545;">*</span></label>
                                    <input type="text" name="motorcycles[0][name]" class="form-input" placeholder="Masukkan nama motor" required />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-col">
                                    <label class="form-label">Nomor Polisi<span style="color: #dc3545;">*</span></label>
                                    <input type="text" name="motorcycles[0][vehicle_number_plate]" class="form-input" placeholder="Contoh: B 1234 KZT" required />
                                </div>
                                <div class="form-col">
                                    <label class="form-label">Status STNK</label>
                                    <div class="form-input" style="background: #f8f9fa; color: #6c757d; display: flex; align-items: center;">
                                        <span id="stnk_status_0">❌ Belum Tersedia</span>
                                        <small style="margin-left: 8px; font-size: 12px;">(Otomatis tersedia setelah upload gambar)</small>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">STNK (Depan & Belakang)<span style="color: #dc3545;">*</span></label>
                                <div class="upload-area" onclick="document.getElementById('stnk_images_0').click()">
                                    <span>Pilih dari Galeri</span>
                                    <input type="file" id="stnk_images_0" name="motorcycles[0][stnk_images][]" accept="image/*" multiple style="display: none;" onchange="handleMultipleFiles(event, 'stnk_preview_0', 'stnk_images_0')" />
                                </div>
                                <div class="upload-area" onclick="captureFromCamera('stnk_preview_0', 'stnk_images_0')" style="margin-top: 8px; background: linear-gradient(135deg, #e8f5e8 0%, #f0f8ff 100%); border-color: #666866;">
                                    <span>Ambil dari Kamera</span>
                                    <input type="file" id="stnk_camera_0" accept="image/*" capture="environment" style="display: none;" onchange="handleCameraCapture(event, 'stnk_preview_0', 'stnk_images_0')" />
                                </div>
                                <div id="stnk_preview_0" style="display: none; margin-top: 10px;">
                                    <div style="display: flex; flex-wrap: wrap; gap: 8px;" id="stnk_preview_container_0"></div>
                                    <p style="font-size: 12px; color: #666; margin-top: 8px;">
                                        <strong>Tips:</strong> Untuk kamera HP, ambil foto satu per satu. Klik "Ambil dari Kamera" lagi untuk foto berikutnya.
                                    </p>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">Harga per Hari<span style="color: #dc3545;">*</span></label>
                                <div class="relative">
                                    <span style="position:absolute;left:16px;top:50%;transform:translateY(-50%);color:#e6a43b;font-weight:bold;">Rp</span>
                                    <input type="number" name="motorcycles[0][price_per_day]" class="form-input" style="padding-left:60px;" placeholder="50000" required />
                                </div>
                            </div>
                            <div>
                                <label class="form-label">Stok Motor<span style="color: #dc3545;">*</span></label>
                                <input type="number" name="motorcycles[0][stock]" class="form-input" placeholder="1" min="1" value="1" required readonly />
                            </div>
                            <div class="form-row">
                                <div class="form-col">
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" name="motorcycles[0][has_gps]" value="1" style="accent-color:#e6a43b;">
                                        <span class="form-label" style="margin-bottom:0;">Ada GPS IoT?</span>
                                    </label>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">Gambar Motor<span style="color: #dc3545;">*</span></label>
                                <div class="upload-area" onclick="document.getElementById('motor_images_0').click()">
                                    <span>Pilih dari Galeri</span>
                                    <input type="file" id="motor_images_0" name="motorcycles[0][images][]" accept="image/*" multiple style="display: none;" onchange="handleMultipleFiles(event, 'motor_preview_0', 'motor_images_0')" />
                                </div>
                                <div class="upload-area" onclick="captureFromCamera('motor_preview_0', 'motor_images_0')" style="margin-top: 8px; background: linear-gradient(135deg, #e8f5e8 0%, #f0f8ff 100%); border-color: #666866;">
                                    <span>Ambil dari Kamera</span>
                                    <input type="file" id="motor_camera_0" accept="image/*" capture="environment" style="display: none;" onchange="handleCameraCapture(event, 'motor_preview_0', 'motor_images_0')" />
                                </div>
                                <div id="motor_preview_0" style="display: none; margin-top: 10px;">
                                    <div style="display: flex; flex-wrap: wrap; gap: 8px;" id="motor_preview_container_0"></div>
                                    <p style="font-size: 12px; color: #666; margin-top: 8px;">
                                        <strong>Tips:</strong> Untuk kamera HP, ambil foto satu per satu. Klik "Ambil dari Kamera" lagi untuk foto berikutnya.
                                    </p>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-col">
                                    <label class="form-label">Jam Awal Bisa Pinjam <span style="color: #dc3545;">*</span></label>
                                    <input type="time" name="motorcycles[0][start_rent_hour]" class="form-input" required value="08:00" />
                                </div>
                                <div class="form-col">
                                    <label class="form-label">Jam Akhir Bisa Pinjam <span style="color: #dc3545;">*</span></label>
                                    <input type="time" name="motorcycles[0][end_rent_hour]" class="form-input" required value="20:00" />
                                </div>
                            </div>
                            <div>
                                <label class="form-label">Kategori <span style="color: #dc3545;">*</span></label>
                                <select name="motorcycles[0][category_id]" class="form-select" required>
                                    <option value="">Pilih kategori</option>
                                    <option value="1">Matic</option>
                                    <option value="2">Sport</option>
                                    <option value="3">Cub</option>
                                </select>
                            </div>
                            <button type="button" class="remove-btn self-end" onclick="removeMotor(this)">Hapus</button>
                        </div>
                    </div>
                </div>
                <button type="button" class="add-btn" onclick="addMotor()">Tambah Motor</button>
            </div>
            <div class="flex gap-3 mt-8 mb-2">
                <button type="button" class="gassor-btn-secondary" onclick="window.history.back()">Batal</button>
                <button type="submit" class="gassor-btn-primary" id="btn-simpan-motor">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let bonusCount = {{ isset($hasExistingRental) && $hasExistingRental && $existingRental && $existingRental->bonuses ? count($existingRental->bonuses) : 1 }};
    let motorCount = 1;

    document.addEventListener('DOMContentLoaded', function() {
        @if(isset($hasExistingRental) && $hasExistingRental)
            showTabWithoutEvent('motor');
            const motorBtn = document.querySelector('button[onclick="showTab(\'motor\')"]');
            if (motorBtn) motorBtn.classList.add('active');
        @else
            showTabWithoutEvent('informasi-umum');
            const infoBtn = document.querySelector('button[onclick="showTab(\'informasi-umum\')"]');
            if (infoBtn) infoBtn.classList.add('active');
        @endif
    });
    function showTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
        document.getElementById(tabName).classList.add('active');
        event.target.classList.add('active');
    }
    function showTabWithoutEvent(tabName) {
        document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
        document.getElementById(tabName).classList.add('active');
    }
    // Satu gambar preview hapus
    function previewImage(event, previewId) {
        const input = event.target;
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img class='image-preview' alt='Preview' src='${e.target.result}'><button type='button' class='remove-btn' style='margin-left:12px;margin-top:8px;' onclick='removeSingleImage(this, "${input.id}", "${previewId}")'>Hapus</button>`;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    function removeSingleImage(btn, inputId, previewId) {
        const input = document.getElementById(inputId);
        input.value = '';
        const preview = document.getElementById(previewId);
        preview.innerHTML = '';
        preview.style.display = 'none';
    }
    // Fungsi untuk menangani multiple files dari galeri
    function handleMultipleFiles(event, previewId, inputId) {
        const files = event.target.files;
        if (files && files.length > 0) {
            const preview = document.getElementById(previewId);
            const container = document.getElementById(previewId.replace('_preview_', '_preview_container_'));
            const existingFiles = getExistingFiles(inputId);
            const allFiles = [...existingFiles, ...Array.from(files)];
            updatePreview(container, allFiles, inputId, previewId);
            updateInputFiles(inputId, allFiles);
            preview.style.display = 'block';

            // Update status STNK jika ini adalah upload STNK
            if (inputId.includes('stnk_images')) {
                updateStnkStatus(inputId, allFiles.length > 0);
            }
        }
    }
    // Fungsi untuk capture dari kamera (satu foto)
    function captureFromCamera(previewId, inputId) {
        const cameraInput = document.getElementById(inputId.replace('images', 'camera'));
        cameraInput.click();
    }
    // Fungsi untuk menangani hasil capture kamera
    function handleCameraCapture(event, previewId, inputId) {
        const file = event.target.files[0];
        if (file) {
            const preview = document.getElementById(previewId);
            const container = document.getElementById(previewId.replace('_preview_', '_preview_container_'));
            const existingFiles = getExistingFiles(inputId);
            const allFiles = [...existingFiles, file];
            updatePreview(container, allFiles, inputId, previewId);
            updateInputFiles(inputId, allFiles);
            preview.style.display = 'block';
            event.target.value = '';

            // Update status STNK jika ini adalah upload STNK
            if (inputId.includes('stnk_images')) {
                updateStnkStatus(inputId, allFiles.length > 0);
            }
        }
    }
    // Fungsi untuk mengupdate status STNK
    function updateStnkStatus(inputId, hasFiles) {
        const motorIndex = inputId.match(/\d+/)[0]; // Ambil index motor
        const statusElement = document.getElementById(`stnk_status_${motorIndex}`);
        if (statusElement) {
            if (hasFiles) {
                statusElement.innerHTML = '✅ Tersedia';
                statusElement.style.color = '#28a745';
            } else {
                statusElement.innerHTML = '❌ Belum Tersedia';
                statusElement.style.color = '#dc3545';
            }
        }
    }
    // Fungsi untuk mendapatkan files yang sudah ada
    function getExistingFiles(inputId) {
        const input = document.getElementById(inputId);
        return input._allFiles || [];
    }
    // Fungsi untuk update input files
    function updateInputFiles(inputId, allFiles) {
        const input = document.getElementById(inputId);
        const dt = new DataTransfer();

        allFiles.forEach(file => {
            dt.items.add(file);
        });

        input.files = dt.files;
        input._allFiles = allFiles;
    }
    // Fungsi untuk update preview container
    function updatePreview(container, allFiles, inputId, previewId) {
        container.innerHTML = '';

        allFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const wrapper = document.createElement('div');
                wrapper.style.position = 'relative';
                wrapper.style.display = 'inline-block';
                wrapper.style.marginRight = '8px';
                wrapper.innerHTML = `
                    <img src="${e.target.result}" style="width:80px;height:80px;object-fit:cover;border-radius:8px;border:2px solid #e6a43b;" alt="Preview">
                    <button type="button" onclick="removeFileFromCollection(${index}, '${inputId}', '${previewId}')"
                            style="position:absolute;top:-5px;right:-5px;background:#dc3545;color:white;border:none;border-radius:50%;width:20px;height:20px;font-size:12px;cursor:pointer;display:flex;align-items:center;justify-content:center;">×</button>
                `;
                container.appendChild(wrapper);
            };
            reader.readAsDataURL(file);
        });
    }
    // Fungsi untuk menghapus file dari koleksi
    function removeFileFromCollection(index, inputId, previewId) {
        const existingFiles = getExistingFiles(inputId);
        existingFiles.splice(index, 1);

        const container = document.getElementById(previewId.replace('_preview_', '_preview_container_'));
        updatePreview(container, existingFiles, inputId, previewId);
        updateInputFiles(inputId, existingFiles);

        if (existingFiles.length === 0) {
            document.getElementById(previewId).style.display = 'none';
        }

        // Update status STNK jika ini adalah hapus file STNK
        if (inputId.includes('stnk_images')) {
            updateStnkStatus(inputId, existingFiles.length > 0);
        }
    }
    // Gambar banyak preview hapus (function lama - untuk backward compatibility)
    function previewMultipleImages(event, previewId) {
        const inputId = event.target.id;
        handleMultipleFiles(event, previewId, inputId);
    }
    // Hapus satu gambar dari input banyak gambar
    function removeMultiImage(btn, inputId, idx, previewId) {
        const input = document.getElementById(inputId);
        let files = input._files || Array.from(input.files);
        files.splice(idx, 1);
        // Buat DataTransfer baru untuk memperbarui input
        const dt = new DataTransfer();
        files.forEach(f => dt.items.add(f));
        input.files = dt.files;
        input._files = files;
        // Render ulang
        previewMultipleImages({ target: input }, previewId);
    }
    function addBonus() {
        const container = document.getElementById('bonus-container');
        const bonusHtml = `
            <div class=\"bonus-item\">
                <div class=\"flex flex-col gap-4\">
                    <div>
                        <label class=\"form-label\">Gambar <span style=\"color: #888;\">(opsional)</span></label>
                        <div class=\"upload-area\" onclick=\"document.getElementById('bonus_image_${bonusCount}').click()\">
                            <span>Seret & Lepas file Anda atau <b>Telusuri</b></span>
                            <input type=\"file\" id=\"bonus_image_${bonusCount}\" name=\"bonuses[${bonusCount}][image]\" accept=\"image/*\" style=\"display: none;\" onchange=\"previewImage(event, 'bonus_preview_${bonusCount}')\" />
                        </div>
                        <div id=\"bonus_preview_${bonusCount}\" style=\"display: none;\"></div>
                    </div>
                    <div>
                        <label class=\"form-label\">Nama Bonus <span style=\"color: #888;\">(opsional)</span></label>
                        <input type=\"text\" name=\"bonuses[${bonusCount}][name]\" class=\"form-input\" placeholder=\"Contoh: Helm\" />
                    </div>
                    <div>
                        <label class=\"form-label\">Deskripsi <span style="color: #888;">(opsional)</span></label>
                        <input type="text" name="bonuses[${bonusCount}][description]" class="form-input" placeholder="Contoh: 1 Helm" />
                    </div>
                    <button type="button" class="remove-btn self-end" onclick="removeBonus(this)">Hapus</button>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', bonusHtml);
        bonusCount++;
    }
    function removeBonus(btn) {
        btn.closest('.bonus-item').remove();
    }
    function addMotor() {
        const container = document.getElementById('motor-container');
        const idx = motorCount;
        const userId = {{ auth()->id() }};
        const userName = "{{ auth()->user()->name }}";
        const motorHtml = `
            <div class="motor-item">
                <div class="flex flex-col gap-4">
                    <div>
                        <label class="form-label">Pemilik<span style="color: #dc3545;">*</span></label>
                        <select name="motorcycles[${idx}][owner_id]" class="form-select" required readonly>
                            <option value="${userId}">${userName}</option>
                        </select>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <label class="form-label">Nama Motor<span style="color: #dc3545;">*</span></label>
                            <input type="text" name="motorcycles[${idx}][name]" class="form-input" placeholder="Contoh: Scoopy 125cc" required />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <label class="form-label">Nomor Polisi<span style="color: #dc3545;">*</span></label>
                            <input type="text" name="motorcycles[${idx}][vehicle_number_plate]" class="form-input" placeholder="Contoh: B 1234 KZT" required />
                        </div>
                        <div class="form-col">
                            <label class="form-label">Status STNK</label>
                            <div class="form-input" style="background: #f8f9fa; color: #6c757d; display: flex; align-items: center;">
                                <span id="stnk_status_${idx}">❌ Belum Tersedia</span>
                                <small style="margin-left: 8px; font-size: 12px;">(Otomatis tersedia setelah upload gambar)</small>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">STNK (Depan & Belakang)<span style="color: #dc3545;">*</span></label>

                        {{-- Upload area untuk file browser --}}
                        <div class="upload-area" onclick="document.getElementById('stnk_images_${idx}').click()">
                            <span>Pilih dari Galeri</span>
                            <input type="file" id="stnk_images_${idx}" name="motorcycles[${idx}][stnk_images][]" accept="image/*" multiple style="display: none;" onchange="handleMultipleFiles(event, 'stnk_preview_${idx}', 'stnk_images_${idx}')" />
                        </div>

                        {{-- Upload area untuk kamera --}}
                        <div class="upload-area" onclick="captureFromCamera('stnk_preview_${idx}', 'stnk_images_${idx}')" style="margin-top: 8px; background: linear-gradient(135deg, #e8f5e8 0%, #f0f8ff 100%); border-color: #4ade80;">
                            <span>Ambil dari Kamera</span>
                            <input type="file" id="stnk_camera_${idx}" accept="image/*" capture="environment" style="display: none;" onchange="handleCameraCapture(event, 'stnk_preview_${idx}', 'stnk_images_${idx}')" />
                        </div>

                        <div id="stnk_preview_${idx}" style="display: none; margin-top: 10px;">
                            <div style="display: flex; flex-wrap: wrap; gap: 8px;" id="stnk_preview_container_${idx}"></div>
                            <p style="font-size: 12px; color: #666; margin-top: 8px;">
                                <strong>Tips:</strong> Untuk kamera HP, ambil foto satu per satu. Klik "Ambil dari Kamera" lagi untuk foto berikutnya.
                            </p>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Harga per Hari<span style="color: #dc3545;">*</span></label>
                        <div class="relative">
                            <span style="position:absolute;left:16px;top:50%;transform:translateY(-50%);color:#e6a43b;font-weight:bold;">Rp</span>
                            <input type="number" name="motorcycles[${idx}][price_per_day]" class="form-input"
                                style="padding-left:60px;" placeholder="50000" required />
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Stok Motor<span style="color: #dc3545;">*</span></label>
                        <input type="number" name="motorcycles[${idx}][stock]" class="form-input" placeholder="1" min="1" value="1" required readonly />
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="motorcycles[${idx}][has_gps]" value="1"
                                    style="accent-color:#e6a43b;">
                                <span class="form-label" style="margin-bottom:0;">Ada GPS IoT?</span>
                            </label>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Gambar Motor<span style="color: #dc3545;">*</span></label>

                        {{-- Upload area untuk file browser --}}
                        <div class="upload-area" onclick="document.getElementById('motor_images_${idx}').click()">
                            <span>Pilih dari Galeri</span>
                            <input type="file" id="motor_images_${idx}" name="motorcycles[${idx}][images][]" accept="image/*" multiple style="display: none;" onchange="handleMultipleFiles(event, 'motor_preview_${idx}', 'motor_images_${idx}')" />
                        </div>

                        {{-- Upload area untuk kamera --}}
                        <div class="upload-area" onclick="captureFromCamera('motor_preview_${idx}', 'motor_images_${idx}')" style="margin-top: 8px; background: linear-gradient(135deg, #e8f5e8 0%, #f0f8ff 100%); border-color: #4ade80;">
                            <span>Ambil dari Kamera</span>
                            <input type="file" id="motor_camera_${idx}" accept="image/*" capture="environment" style="display: none;" onchange="handleCameraCapture(event, 'motor_preview_${idx}', 'motor_images_${idx}')" />
                        </div>

                        <div id="motor_preview_${idx}" style="display: none; margin-top: 10px;">
                            <div style="display: flex; flex-wrap: wrap; gap: 8px;" id="motor_preview_container_${idx}"></div>
                            <p style="font-size: 12px; color: #666; margin-top: 8px;">
                                <strong>Tips:</strong> Untuk kamera HP, ambil foto satu per satu. Klik "Ambil dari Kamera" lagi untuk foto berikutnya.
                            </p>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <label class="form-label">Jam Awal Bisa Pinjam <span style="color: #dc3545;">*</span></label>
                            <input type="time" name="motorcycles[${idx}][start_rent_hour]" class="form-input" required value="08:00" />
                        </div>
                        <div class="form-col">
                            <label class="form-label">Jam Akhir Bisa Pinjam <span style="color: #dc3545;">*</span></label>
                            <input type="time" name="motorcycles[${idx}][end_rent_hour]" class="form-input" required value="20:00" />
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Kategori <span style="color: #dc3545;">*</span></label>
                        <select name="motorcycles[${idx}][category_id]" class="form-select" required>
                            <option value="">Pilih kategori</option>
                            <option value="1">Matic</option>
                            <option value="2">Sport</option>
                            <option value="3">Cub</option>
                        </select>
                    </div>
                    <button type="button" class="remove-btn self-end" onclick="removeMotor(this)">Hapus</button>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', motorHtml);
        motorCount++;
    }
    function removeMotor(btn) {
        btn.closest('.motor-item').remove();
    }
    function generateSlug() {
        const name = document.getElementById('rental-name').value;
        const slug = name
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
        document.getElementById('rental-slug').value = slug;
    }

    // Checklist Fisik Motor - Validasi sebelum submit
    function isChecklistFisikLengkap() {
        const items = document.querySelectorAll('.checklist-fisik-item');
        for (let i = 0; i < items.length; i++) {
            if (!items[i].checked) return false;
        }
        // Video wajib
        const video = document.getElementById('video_fisik');
        if (!video || !video.files || video.files.length === 0) return false;
        return true;
    }
    function updateChecklistFisikState() {
        const btn = document.getElementById('btn-simpan-motor');
        if (!isChecklistFisikLengkap()) {
            btn.disabled = true;
            btn.style.opacity = 0.6;
        } else {
            btn.disabled = false;
            btn.style.opacity = 1;
        }
    }
    document.addEventListener('DOMContentLoaded', function() {
        // ...existing code...
        updateChecklistFisikState();
        document.querySelectorAll('.checklist-fisik-item').forEach(cb => {
            cb.addEventListener('change', updateChecklistFisikState);
        });
        document.querySelector('form').addEventListener('submit', function(e) {
            if (!isChecklistFisikLengkap()) {
                e.preventDefault();
                document.getElementById('checklist-fisik-warning').style.display = 'block';
                showTab('checklist-fisik');
                setTimeout(() => {
                    document.getElementById('checklist-fisik-warning').style.display = 'none';
                }, 3000);
            }
        });
    });
    function previewVideo(event) {
        const input = event.target;
        const preview = document.getElementById('video_fisik_preview');
        if (input.files && input.files[0]) {
            const url = URL.createObjectURL(input.files[0]);
            preview.innerHTML = `<video width='320' height='240' controls style='border-radius:12px;border:2px solid #e6a43b;'><source src='${url}' type='video/mp4'>Video tidak didukung.</video>`;
            preview.style.display = 'block';
        }
    }
</script>
<script>
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            html: `{!! session('error') !!}`,
            confirmButtonColor: '#e6a43b',
            customClass: {
                popup: 'text-black',
                confirmButton: 'rounded-full'
            },
            color: '#000000'
        });
    @endif
    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Validasi Gagal',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            confirmButtonColor: '#e6a43b',
            customClass: {
                popup: 'text-black',
                confirmButton: 'rounded-full'
            },
            color: '#000000'
        });
    @endif
    @if(session('success') && request()->isMethod('post'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: {!! json_encode(session('success')) !!},
            confirmButtonColor: '#e6a43b',
            timer: 4000,
            timerProgressBar: true,
            showConfirmButton: true,
            customClass: {
                popup: 'text-black',
                confirmButton: 'rounded-full'
            },
            color: '#000000'
        });
    @endif
</script>
@parent
@endsection
