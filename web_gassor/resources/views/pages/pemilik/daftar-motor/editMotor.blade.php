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
            <p class="font-semibold">Edit Motor</p>
            <div class="dummy-btn w-12"></div>
        </div>
        @if(session('error'))
            <div style="background:#ffdddd;color:#a94442;padding:16px;border-radius:8px;margin-bottom:16px;">
                <b>Error:</b> {{ session('error') }}
            </div>
        @endif
        <form method="POST" action="{{ route('pemilik.update-motor', $motorcycle->id) }}" enctype="multipart/form-data" class="flex flex-col">
            @csrf
            <!-- Tab Navigation -->
            <div class="tab-buttons">
                <button type="button" class="tab-button active" onclick="showTab('informasi-umum')">Informasi Umum</button>
                <button type="button" class="tab-button" onclick="showTab('bonus-sewa')">Bonus Sewa</button>
                <button type="button" class="tab-button" onclick="showTab('motor')">Motor</button>
            </div>
            <!-- Tab 1: Informasi Umum -->
            <div id="informasi-umum" class="tab-content active">
                <div class="flex flex-col gap-4">
                    <div>
                        <label class="form-label">Thumbnail <span style="color: #dc3545;">*</span></label>
                        <div class="upload-area" onclick="document.getElementById('thumbnail').click()">
                            <span>Seret & Lepas file Anda atau <b>Telusuri</b></span>
                            <input type="file" id="thumbnail" name="thumbnail" accept="image/*" style="display: none;" onchange="previewImage(event, 'thumbnail-preview')" />
                        </div>
                        <div id="thumbnail-preview" style="display: {{ $motorbikeRental && $motorbikeRental->thumbnail ? 'block' : 'none' }};">
                            @if($motorbikeRental && $motorbikeRental->thumbnail)
                                <img class="image-preview" src="{{ asset('storage/'.$motorbikeRental->thumbnail) }}" alt="Pratinjau">
                            @endif
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Nama <span style="color: #dc3545;">*</span></label>
                        <input type="text" name="name" id="rental-name" class="form-input" value="{{ old('name', $motorbikeRental->name ?? '') }}" required oninput="generateSlug()" />
                    </div>
                    <div>
                        <label class="form-label">Slug <span style="color: #dc3545;">*</span></label>
                        <input type="text" name="slug" id="rental-slug" class="form-input" value="{{ old('slug', $motorbikeRental->slug ?? '') }}" required readonly/>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <label class="form-label">Kota <span style="color: #dc3545;">*</span></label>
                            <select name="city_id" class="form-select" required>
                                <option value="">Pilih kota</option>
                                <option value="1" {{ old('city_id', $motorbikeRental->city_id ?? '')==1 ? 'selected' : '' }}>Bojongsoang</option>
                                <option value="2" {{ old('city_id', $motorbikeRental->city_id ?? '')==2 ? 'selected' : '' }}>Sukapura</option>
                                <option value="3" {{ old('city_id', $motorbikeRental->city_id ?? '')==3 ? 'selected' : '' }}>Sukabirus</option>
                            </select>
                        </div>
                        <div class="form-col">
                            <label class="form-label">Kategori <span style="color: #dc3545;">*</span></label>
                            <select name="category_id" class="form-select" required>
                                <option value="">Pilih kategori</option>
                                <option value="1" {{ old('category_id', $motorbikeRental->category_id ?? '')==1 ? 'selected' : '' }}>Matic</option>
                                <option value="2" {{ old('category_id', $motorbikeRental->category_id ?? '')==2 ? 'selected' : '' }}>Sport</option>
                                <option value="3" {{ old('category_id', $motorbikeRental->category_id ?? '')==3 ? 'selected' : '' }}>Cub</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Deskripsi <span style="color: #dc3545;">*</span></label>
                        <textarea name="description" class="form-textarea" rows="4" required>{{ old('description', $motorbikeRental->description ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="form-label">Alamat <span style="color: #dc3545;">*</span></label>
                        <textarea name="address" class="form-textarea" rows="3" required>{{ old('address', $motorbikeRental->address ?? '') }}</textarea>
                    </div>
                </div>
            </div>
            <!-- Tab 2: Bonus Sewa -->
            <div id="bonus-sewa" class="tab-content">
                <div id="bonus-container">
                    @php $bonusCount = 0; @endphp
                    @if(isset($motorbikeRental) && $motorbikeRental->bonuses && count($motorbikeRental->bonuses))
                        @foreach($motorbikeRental->bonuses as $bonus)
                        <div class="bonus-item">
                            <div class="flex flex-col gap-4">
                                <div>
                                    <label class="form-label">Gambar<span style="color: #dc3545;">*</span></label>
                                    <div class="upload-area" onclick="document.getElementById('bonus_image_{{ $bonusCount }}').click()">
                                        <span>Seret & Lepas file Anda atau <b>Telusuri</b></span>
                                        <input type="file" id="bonus_image_{{ $bonusCount }}" name="bonuses[{{ $bonusCount }}][image]" accept="image/*" style="display: none;" onchange="previewImage(event, 'bonus_preview_{{ $bonusCount }}')" />
                                    </div>
                                    <div id="bonus_preview_{{ $bonusCount }}" style="display: {{ $bonus->image ? 'block' : 'none' }};">
                                        @if($bonus->image)
                                            <img class="image-preview" src="{{ asset('storage/'.$bonus->image) }}" alt="Pratinjau">
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label">Nama Bonus<span style="color: #dc3545;">*</span></label>
                                    <input type="text" name="bonuses[{{ $bonusCount }}][name]" class="form-input" value="{{ old('bonuses.'.$bonusCount.'.name', $bonus->name) }}" />
                                </div>
                                <div>
                                    <label class="form-label">Deskripsi<span style="color: #dc3545;">*</span></label>
                                    <input type="text" name="bonuses[{{ $bonusCount }}][description]" class="form-input" value="{{ old('bonuses.'.$bonusCount.'.description', $bonus->description) }}" />
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
                                    <label class="form-label">Gambar<span style="color: #dc3545;">*</span></label>
                                    <div class="upload-area" onclick="document.getElementById('bonus_image_0').click()">
                                        <span>Seret & Lepas file Anda atau <b>Telusuri</b></span>
                                        <input type="file" id="bonus_image_0" name="bonuses[0][image]" accept="image/*" style="display: none;" onchange="previewImage(event, 'bonus_preview_0')" />
                                    </div>
                                    <div id="bonus_preview_0" style="display: none;"></div>
                                </div>
                                <div>
                                    <label class="form-label">Nama Bonus<span style="color: #dc3545;">*</span></label>
                                    <input type="text" name="bonuses[0][name]" class="form-input" placeholder="Contoh: Helm" />
                                </div>
                                <div>
                                    <label class="form-label">Deskripsi<span style="color: #dc3545;">*</span></label>
                                    <input type="text" name="bonuses[0][description]" class="form-input" placeholder="Contoh: 1 Helm" />
                                </div>
                                <button type="button" class="remove-btn self-end" onclick="removeBonus(this)">Hapus</button>
                            </div>
                        </div>
                    @endif
                </div>
                <button type="button" class="add-btn" onclick="addBonus()">Tambah Bonus</button>
            </div>
            <!-- Tab 3: Motor -->
            <div id="motor" class="tab-content">
                <div id="motor-container">
                    <div class="motor-item">
                        <div class="flex flex-col gap-4">
                            <div>
                                <label class="form-label">Pemilik<span style="color: #dc3545;">*</span></label>
                                <select name="owner_id" class="form-select" required readonly>
                                    <option value="{{ auth()->id() }}">{{ auth()->user()->name }}</option>
                                </select>
                            </div>
                            <div class="form-row">
                                <div class="form-col">
                                    <label class="form-label">Nama Motor<span style="color: #dc3545;">*</span></label>
                                    <input type="text" name="name" class="form-input" value="{{ old('name', $motorcycle->name) }}" required />
                                </div>
                                <div class="form-col">
                                    <label class="form-label">Tipe Motor<span style="color: #dc3545;">*</span></label>
                                    <input type="text" name="motorcycle_type" class="form-input" value="{{ old('motorcycle_type', $motorcycle->motorcycle_type) }}" required />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-col">
                                    <label class="form-label">Nomor Polisi<span style="color: #dc3545;">*</span></label>
                                    <input type="text" name="vehicle_number_plate" class="form-input" value="{{ old('vehicle_number_plate', $motorcycle->vehicle_number_plate) }}" required />
                                </div>
                                <div class="form-col">
                                    <label class="form-label">STNK<span style="color: #dc3545;">*</span></label>
                                    <input type="text" name="stnk" class="form-input" value="{{ old('stnk', $motorcycle->stnk) }}" required />
                                </div>
                            </div>
                            <div>
                                <label class="form-label">STNK (Depan & Belakang)<span style="color: #dc3545;">*</span></label>
                                <div class="upload-area" onclick="document.getElementById('stnk_images_0').click()">
                                    <span>Seret & Lepas file Anda atau <b>Telusuri</b></span>
                                    <input type="file" id="stnk_images_0" name="stnk_images[]" accept="image/*" multiple style="display: none;" onchange="previewMultipleImages(event, 'stnk_preview_0')" />
                                </div>
                                <div id="stnk_preview_0" style="display: {{ $motorcycle->stnk_images ? 'block' : 'none' }};">
                                    @if($motorcycle->stnk_images)
                                        <div class="flex gap-2 mt-2">
                                            @foreach($motorcycle->stnk_images as $img)
                                                <img src="{{ asset('storage/'.$img) }}" style="width:60px;height:60px;object-fit:cover;border-radius:8px;" />
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <label class="form-label">Harga per Hari<span style="color: #dc3545;">*</span></label>
                                <div class="relative">
                                    <span style="position:absolute;left:16px;top:50%;transform:translateY(-50%);color:#e6a43b;font-weight:bold;">Rp</span>
                                    <input type="number" name="price_per_day" class="form-input" style="padding-left:60px;" value="{{ old('price_per_day', $motorcycle->price_per_day) }}" required />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-col">
                                    <label class="form-label">Stok Total<span style="color: #dc3545;">*</span></label>
                                    <input type="number" name="stock" class="form-input" value="{{ old('stock', $motorcycle->stock ?? 1) }}" min="1" required />
                                    <small class="text-gray-500">Total unit motor yang dimiliki</small>
                                </div>
                                <div class="form-col">
                                    <label class="form-label">Stok Tersedia</label>
                                    <input type="number" name="available_stock" class="form-input" value="{{ old('available_stock', $motorcycle->available_stock ?? 1) }}" min="0" />
                                    <small class="text-gray-500">Unit yang tersedia untuk disewa</small>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-col">
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" name="has_gps" value="1" {{ old('has_gps', $motorcycle->has_gps) ? 'checked' : '' }} style="accent-color:#e6a43b;">
                                        <span class="form-label" style="margin-bottom:0;">Ada GPS IoT?</span>
                                    </label>
                                </div>
                            </div>
                            {{-- <div>
                                <label class="form-label">Status<span style="color: #dc3545;">*</span></label>
                                <select name="status" class="form-select" required>
                                    <option value="on_going" @if(old('status', $motorcycle->status)==='on_going') selected @endif>Sedang Berjalan</option>
                                    <option value="finished" @if(old('status', $motorcycle->status)==='finished') selected @endif>Selesai</option>
                                </select>
                            </div> --}}
                            <div>
                                <label class="form-label">Gambar Motor (upload baru untuk ganti semua)</label>
                                <div class="upload-area" onclick="document.getElementById('motor_images_0').click()">
                                    <span>Seret & Lepas file Anda atau <b>Telusuri</b></span>
                                    <input type="file" id="motor_images_0" name="images[]" accept="image/*" multiple style="display: none;" onchange="previewMultipleImages(event, 'motor_preview_0')" />
                                </div>
                                <div id="motor_preview_0" style="display: {{ $motorcycle->images ? 'block' : 'none' }};">
                                    @if($motorcycle->images)
                                        <div class="flex gap-2 mt-2">
                                            @foreach($motorcycle->images as $img)
                                                <img src="{{ asset('storage/'.$img->image) }}" style="width:60px;height:60px;object-fit:cover;border-radius:8px;" />
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Submit Buttons -->
            <div class="flex gap-3 mt-8 mb-2">
                <button type="button" class="gassor-btn-secondary" onclick="window.history.back()">Batal</button>
                <button type="submit" class="gassor-btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let bonusCount = {{ isset($bonusCount) ? $bonusCount : 1 }};
    let motorCount = 1;
    function showTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
        document.getElementById(tabName).classList.add('active');
        event.target.classList.add('active');
    }
    function previewImage(event, previewId) {
        const input = event.target;
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img class='image-preview' alt='Preview' src='${e.target.result}'><button type='button' class='remove-btn' style='margin-left:12px;margin-top:8px;' onclick='removeSingleImage(this, "${input.id}", "${previewId}")'>Remove</button>`;
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
    function previewMultipleImages(event, previewId) {
        const input = event.target;
        const preview = document.getElementById(previewId);
        preview.innerHTML = '';
        if (input.files) {
            Array.from(input.files).forEach((file, idx) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const wrapper = document.createElement('div');
                    wrapper.style.position = 'relative';
                    wrapper.style.display = 'inline-block';
                    wrapper.style.marginRight = '8px';
                    wrapper.innerHTML = `<img class='image-preview' src='${e.target.result}' alt='Preview'><button type='button' class='remove-btn' style='position:absolute;top:0;right:0;padding:2px 8px;font-size:12px;' onclick='removeMultiImage(this, "${input.id}", ${idx}, "${previewId}")'>×</button>`;
                    preview.appendChild(wrapper);
                    preview.style.display = 'flex';
                };
                reader.readAsDataURL(file);
            });
            input._files = Array.from(input.files);
        }
    }
    function removeMultiImage(btn, inputId, idx, previewId) {
        const input = document.getElementById(inputId);
        let files = input._files || Array.from(input.files);
        files.splice(idx, 1);
        const dt = new DataTransfer();
        files.forEach(f => dt.items.add(f));
        input.files = dt.files;
        input._files = files;
        previewMultipleImages({ target: input }, previewId);
    }
    function addBonus() {
        const container = document.getElementById('bonus-container');
        const bonusHtml = `
            <div class="bonus-item">
                <div class="flex flex-col gap-4">
                    <div>
                        <label class="form-label">Gambar<span style=\"color: #dc3545;\">*</span></label>
                        <div class="upload-area" onclick=\"document.getElementById('bonus_image_${bonusCount}').click()">
                            <span>Seret & Lepas file Anda atau <b>Telusuri</b></span>
                            <input type=\"file\" id=\"bonus_image_${bonusCount}\" name=\"bonuses[${bonusCount}][image]\" accept=\"image/*\" style=\"display: none;\" onchange=\"previewImage(event, 'bonus_preview_${bonusCount}')\" />
                        </div>
                        <div id=\"bonus_preview_${bonusCount}\" style=\"display: none;\"></div>
                    </div>
                    <div>
                        <label class="form-label">Nama Bonus<span style=\"color: #dc3545;\">*</span></label>
                        <input type="text" name="bonuses[${bonusCount}][name]" class="form-input" placeholder="Contoh: Helm" />
                    </div>
                    <div>
                        <label class="form-label">Deskripsi<span style=\"color: #dc3545;\">*</span></label>
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
    function generateSlug() {
        const name = document.getElementById('rental-name').value;
        const slug = name
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
        document.getElementById('rental-slug').value = slug;
    }
</script>
@endsection
