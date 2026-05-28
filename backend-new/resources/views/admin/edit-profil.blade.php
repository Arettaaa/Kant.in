@extends('layouts.app')

@section('title', 'Edit Info Kantin - Kant.in')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <style>
        /* Styling khusus area potong gambar */
        .cropper-container {
            max-height: 60vh;
            width: 100%;
            background-color: #f3f4f6;
        }

        .cropper-view-box,
        .cropper-face {
            border-radius: 8px;
        }
    </style>
@endpush

@section('content')
    <div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden text-start">

        {{-- SIDEBAR --}}
        @include('admin.partials.sidebar')

        {{-- MAIN CONTENT --}}
        <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-[#F9FAFB] text-start">

            {{-- Header Sticky --}}
            <div
                class="sticky top-0 z-10 w-full flex items-center gap-4 px-10 py-6 bg-white/90 backdrop-blur-md border-b border-gray-100 shadow-sm">
                <a href="{{ route('admin.profil') }}"
                    class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 hover:bg-gray-50 transition-all">
                    <i class="fa-solid fa-arrow-left text-gray-400"></i>
                </a>
                <div class="text-start">
                    <h2 class="text-xl font-extrabold text-gray-900 leading-none mb-1">Ubah Data Kantin</h2>
                    <p class="text-[12px] text-gray-400 font-medium">Perbarui detail warung & profilmu</p>
                </div>
            </div>

            {{-- FORM UPDATE --}}
            {{-- TAMBAHAN: ID profilForm ditambahkan di sini --}}
            <form id="profilForm" action="{{ route('admin.profil.update') }}" method="POST" enctype="multipart/form-data"
                class="px-10 py-10 space-y-8 max-w-6xl mx-auto w-full pb-20">
                @csrf
                @method('PUT')

                {{-- Pesan Error Validasi --}}
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-100 text-red-500 p-4 rounded-2xl text-sm font-bold">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- ================= BAGIAN 1: IDENTITAS KANTIN ================= --}}
                <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-sm">
                    <h3
                        class="text-sm font-black text-[#FF6900] uppercase tracking-widest border-b border-gray-50 pb-4 mb-6">
                        Identitas Kantin</h3>

                    <div class="flex flex-col lg:flex-row gap-10">
                        {{-- Kiri: Logo Kantin --}}
                        <div class="flex flex-col items-center flex-shrink-0 lg:w-48">
                            <div class="relative group cursor-pointer">
                                @php
                                    $canteenImage = $canteen->image
                                        ? asset('storage/' . $canteen->image)
                                        : 'https://ui-avatars.com/api/?name=' . urlencode($canteen->name ?? 'Kantin') . '&color=FF6900&background=FFF3E8&size=200';
                                @endphp
                                <img id="logoPreview" src="{{ $canteenImage }}"
                                    class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-md transition-all group-hover:brightness-75"
                                    alt="Logo Kantin">
                                <label for="logoInput"
                                    class="absolute bottom-1 right-1 w-10 h-10 bg-[#FF6900] rounded-full border-4 border-white flex items-center justify-center cursor-pointer hover:scale-110 transition-all">
                                    <i class="fa-solid fa-camera text-sm text-white"></i>
                                </label>
                                <input type="file" name="image" id="logoInput" class="hidden upload-cropper"
                                    accept="image/*" data-target="logoPreview" data-ratio="1">
                            </div>
                            <p class="text-[11px] text-gray-400 font-bold mt-4 uppercase tracking-widest text-center">Ganti
                                Logo</p>
                        </div>

                        {{-- Kanan: Form Identitas Kantin --}}
                        <div class="flex-1 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Input Nama Kantin (READ-ONLY) --}}
                                <div>
                                    <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Nama
                                        Kantin <span class="text-gray-300 normal-case ml-1">(Paten)</span></p>
                                    <div class="relative">
                                        <span class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"><i
                                                class="fa-solid fa-store"></i></span>
                                        <input type="text" value="{{ $canteen->name }}" readonly
                                            class="w-full pl-12 pr-4 py-3.5 rounded-2xl bg-gray-100 border border-gray-200 shadow-sm font-bold text-gray-500 cursor-not-allowed outline-none">
                                    </div>
                                </div>

                                {{-- Input Lokasi Kantin (READ-ONLY) --}}
                                <div>
                                    <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Lokasi /
                                        Alamat <span class="text-gray-300 normal-case ml-1">(Paten)</span></p>
                                    <div class="relative">
                                        <span class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"><i
                                                class="fa-solid fa-location-dot"></i></span>
                                        <input type="text" value="{{ $canteen->location }}" readonly
                                            class="w-full pl-12 pr-4 py-3.5 rounded-2xl bg-gray-100 border border-gray-200 shadow-sm font-bold text-gray-500 cursor-not-allowed outline-none">
                                    </div>
                                </div>
                            </div>

                            {{-- Input Deskripsi --}}
                            <div>
                                <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Deskripsi
                                    Kantin</p>
                                <textarea name="description" placeholder="Deskripsi singkat kantin..." rows="3"
                                    class="w-full px-5 py-4 rounded-2xl bg-white border border-gray-100 shadow-sm focus:outline-none focus:border-[#FF6900] font-bold text-gray-800 transition-all">{{ old('description', $canteen->description) }}</textarea>
                            </div>

                            {{-- Ongkir & Jam Operasional --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Ongkir
                                        (Rp)</p>
                                    <input type="number" name="delivery_fee_flat"
                                        value="{{ old('delivery_fee_flat', $canteen->delivery_fee_flat) }}"
                                        placeholder="2000"
                                        class="w-full px-5 py-3.5 rounded-2xl bg-white border border-gray-100 shadow-sm focus:outline-none focus:border-[#FF6900] font-bold text-gray-800 transition-all">
                                </div>
                                <div>
                                    <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Jam
                                        Operasional (Buka - Tutup)</p>
                                    <div class="flex items-center gap-3">
                                        <input type="time" name="operating_hours[open]"
                                            value="{{ old('operating_hours.open', $canteen->operating_hours['open'] ?? '') }}"
                                            class="w-full px-4 py-3.5 rounded-2xl bg-white border border-gray-100 shadow-sm focus:outline-none focus:border-[#FF6900] font-bold text-gray-800 text-center transition-all">
                                        <span class="font-black text-gray-300">-</span>
                                        <input type="time" name="operating_hours[close]"
                                            value="{{ old('operating_hours.close', $canteen->operating_hours['close'] ?? '') }}"
                                            class="w-full px-4 py-3.5 rounded-2xl bg-white border border-gray-100 shadow-sm focus:outline-none focus:border-[#FF6900] font-bold text-gray-800 text-center transition-all">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Grid 2 Kolom untuk Profil Pemilik & QRIS --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                    {{-- ================= BAGIAN 2: PROFIL PEMILIK ================= --}}
                    <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-sm space-y-6">
                        <h3
                            class="text-sm font-black text-[#FF6900] uppercase tracking-widest border-b border-gray-50 pb-4">
                            Profil Pemilik</h3>

                        <div class="flex items-center gap-5">
                            <div class="relative group cursor-pointer flex-shrink-0">
                                @php
                                    $userImage = $user->photo_profile
                                        ? asset('storage/' . $user->photo_profile)
                                        : 'https://ui-avatars.com/api/?name=' . urlencode($user->name ?? 'Admin') . '&background=random&size=100';
                                @endphp
                                <img id="adminPreview" src="{{ $userImage }}"
                                    class="w-20 h-20 rounded-full object-cover border-2 border-gray-100 shadow-sm transition-all group-hover:brightness-75"
                                    alt="Foto Admin">
                                <label for="adminInput"
                                    class="absolute bottom-0 right-0 w-7 h-7 bg-[#FF6900] rounded-full border-2 border-white flex items-center justify-center cursor-pointer hover:scale-110 transition-all">
                                    <i class="fa-solid fa-camera text-[10px] text-white"></i>
                                </label>
                                <input type="file" name="photo_profile" id="adminInput" class="hidden upload-cropper"
                                    accept="image/*" data-target="adminPreview" data-ratio="1">
                            </div>
                            <div class="w-full">
                                <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Nama
                                    Pengelola</p>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                    placeholder="Nama Lengkap Admin"
                                    class="w-full px-5 py-3.5 rounded-2xl bg-gray-50 border border-gray-100 focus:outline-none focus:bg-white focus:border-[#FF6900] font-bold text-gray-800 transition-all"
                                    required>
                            </div>
                        </div>

                        {{-- Email Pemilik (READ-ONLY) --}}
                        <div>
                            <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Email Kantin
                                <span class="text-gray-300 normal-case ml-1">(Paten)</span>
                            </p>
                            <div class="relative">
                                <span class="absolute left-5 top-1/2 -translate-y-1/2 text-[#FF6900]"><i
                                        class="fa-solid fa-envelope"></i></span>
                                <input type="email" value="{{ $user->email }}" readonly
                                    class="w-full pl-12 pr-4 py-3.5 rounded-2xl bg-gray-100 border border-gray-200 shadow-sm font-bold text-gray-500 cursor-not-allowed outline-none">
                            </div>
                        </div>

                        {{-- No Telepon Pemilik --}}
                        <div>
                            <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Nomor HP /
                                WhatsApp Aktif</p>
                            <div class="relative">
                                <span class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"><i
                                        class="fa-solid fa-phone"></i></span>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="08..."
                                    class="w-full pl-12 pr-4 py-3.5 rounded-2xl bg-white border border-gray-100 shadow-sm focus:outline-none focus:border-[#FF6900] font-bold text-gray-800 transition-all">
                            </div>
                        </div>
                    </div>

                    {{-- ================= BAGIAN 3: INFO PEMBAYARAN (QRIS) ================= --}}
                    <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-sm flex flex-col">
                        <h3
                            class="text-sm font-black text-[#FF6900] uppercase tracking-widest border-b border-gray-50 pb-4 mb-6">
                            Info Pembayaran</h3>

                        <div class="flex-1 flex flex-col items-center justify-center">
                            <label for="qrisInput"
                                class="group cursor-pointer w-full h-full min-h-[200px] flex flex-col items-center justify-center p-6 border-2 border-dashed border-gray-200 rounded-[24px] bg-gray-50 hover:bg-orange-50 hover:border-[#FF6900] transition-all relative overflow-hidden">

                                {{-- Preview Image --}}
                                <img id="qrisPreview"
                                    src="{{ $canteen->qris_image ? asset('storage/' . $canteen->qris_image) : '' }}"
                                    class="absolute inset-0 w-full h-full object-contain p-2 {{ $canteen->qris_image ? '' : 'hidden' }} bg-white z-10">

                                {{-- Overlay Ganti QRIS --}}
                                <div id="qrisChangeOverlay"
                                    class="absolute inset-0 bg-black/50 z-20 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-all {{ $canteen->qris_image ? '' : 'hidden' }}">
                                    <div
                                        class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-[#FF6900] mb-2">
                                        <i class="fa-solid fa-camera text-lg"></i>
                                    </div>
                                    <span class="text-white font-bold text-sm">Ganti QRIS</span>
                                </div>

                                {{-- Tampilan Kosong --}}
                                <div id="qrisEmptyState"
                                    class="flex flex-col items-center justify-center z-0 {{ $canteen->qris_image ? 'opacity-0' : '' }}">
                                    <i
                                        class="fa-solid fa-qrcode text-4xl text-gray-300 mb-4 group-hover:text-[#FF6900] transition-colors"></i>
                                    <p class="text-sm font-bold text-gray-500 mb-1">Ketuk untuk upload QRIS</p>
                                    <p class="text-[11px] text-gray-400 font-medium text-center">Pastikan kotak QR pas di
                                        tengah<br>dan tidak terpotong.</p>
                                </div>

                                <input type="file" name="qris_image" id="qrisInput" class="hidden upload-cropper"
                                    accept="image/*" data-target="qrisPreview" data-ratio="NaN">
                            </label>

                            <p id="qrisBadge"
                                class="hidden mt-4 px-4 py-1.5 bg-orange-50 text-[#FF6900] text-[11px] font-black tracking-widest uppercase rounded-full">
                                <i class="fa-solid fa-triangle-exclamation mr-1"></i> QRIS baru belum disimpan
                            </p>
                        </div>
                    </div>

                </div>

                {{-- Tombol Simpan --}}
                <div class="flex justify-end pt-4">
                    <button type="submit" id="btnSubmit"
                        class="px-8 py-4 bg-[#FF6900] text-white rounded-2xl font-black text-[15px] shadow-lg shadow-orange-200 hover:brightness-110 transition-all flex items-center justify-center gap-3">
                        <i class="fa-solid fa-check"></i> Simpan Perubahan
                    </button>
                </div>

            </form>
        </main>
    </div>

    {{-- ================= MODAL CROPPER ================= --}}
    <div id="cropperModal"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-6">
        <div class="bg-white w-full max-w-2xl rounded-[32px] p-8 shadow-2xl flex flex-col">
            <h3 class="text-xl font-black text-gray-900 mb-6">Sesuaikan Posisi Gambar</h3>

            <div class="w-full flex justify-center bg-gray-50 rounded-2xl overflow-hidden mb-6 border border-gray-100"
                style="max-height: 60vh;">
                <img id="imageToCrop" src="" class="max-w-full block">
            </div>

            <div class="flex gap-4">
                <button type="button" onclick="closeCropper()"
                    class="flex-1 py-4 bg-gray-100 text-gray-600 rounded-2xl font-black text-sm hover:bg-gray-200 transition-all">Batal</button>
                <button type="button" onclick="cropAndSave()"
                    class="flex-1 py-4 bg-[#FF6900] text-white rounded-2xl font-black text-sm shadow-lg shadow-orange-200 hover:brightness-110 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-crop-simple"></i> Potong & Simpan
                </button>
            </div>
        </div>
    </div>

    {{-- ================= MODAL SUKSES ================= --}}
    <div id="successPopup"
        class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-6 transition-opacity duration-300">
        <div
            class="bg-white rounded-[36px] p-8 max-w-sm w-full flex flex-col items-center text-center shadow-2xl transform scale-100 transition-transform duration-300">
            <div
                class="w-20 h-20 bg-[#F0FDF4] rounded-full flex items-center justify-center text-[#22C55E] mb-5 shadow-inner">
                <i class="fa-solid fa-check text-4xl"></i>
            </div>
            <h3 class="text-2xl font-black text-gray-900 mb-2">Berhasil!</h3>
            <p class="text-sm font-bold text-gray-500 mb-2">Profil dan data kantinmu<br>telah berhasil diperbarui.</p>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

        <script>
            let cropper;
            let currentInputId = '';
            let currentPreviewId = '';

            // Objek untuk menyimpan gambar yang sudah dicrop secara sementara
            let croppedBlobs = {};

            const cropperModal = document.getElementById('cropperModal');
            const imageToCrop = document.getElementById('imageToCrop');

            // Menangani semua input file
            document.querySelectorAll('.upload-cropper').forEach(input => {
                input.addEventListener('change', function (e) {
                    if (this.files && this.files[0]) {
                        const file = this.files[0];
                        currentInputId = this.id;
                        currentPreviewId = this.dataset.target;
                        const ratio = parseFloat(this.dataset.ratio);

                        const reader = new FileReader();
                        reader.onload = function (event) {
                            imageToCrop.src = event.target.result;
                            cropperModal.classList.remove('hidden');

                            if (cropper) { cropper.destroy(); }

                            cropper = new Cropper(imageToCrop, {
                                aspectRatio: isNaN(ratio) ? NaN : ratio,
                                viewMode: 1,
                                autoCropArea: 1,
                                background: false
                            });
                        };
                        reader.readAsDataURL(file);
                    }
                });
            });

            function closeCropper() {
                cropperModal.classList.add('hidden');
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
                document.getElementById(currentInputId).value = '';
            }

            function cropAndSave() {
                if (!cropper) return;

                cropper.getCroppedCanvas().toBlob((blob) => {
                    // 1. Simpan blob ke dalam variabel global
                    croppedBlobs[currentInputId] = blob;

                    // 2. Tampilkan di UI
                    const url = URL.createObjectURL(blob);
                    const previewImg = document.getElementById(currentPreviewId);

                    previewImg.src = url;
                    previewImg.classList.remove('hidden');

                    if (currentInputId === 'qrisInput') {
                        document.getElementById('qrisChangeOverlay').classList.remove('hidden');
                        document.getElementById('qrisEmptyState').classList.add('opacity-0');
                        document.getElementById('qrisBadge').classList.remove('hidden');
                    }

                    closeCropper();
                }, 'image/jpeg');
            }

            // =========================================================================
            // INTERCEPT FORM SUBMIT: KIRM DATA VIA FETCH API UNTUK MENGHINDARI BUG BROWSER
            // =========================================================================
            document.getElementById('profilForm').addEventListener('submit', async function (e) {
                e.preventDefault();

                const submitBtn = document.getElementById('btnSubmit');
                const originalBtnText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';
                submitBtn.disabled = true;

                // Ambil semua data teks dari form
                let formData = new FormData(this);

                // Timpa data gambar dengan blob hasil crop (jika ada)
                if (croppedBlobs['logoInput']) {
                    formData.set('image', croppedBlobs['logoInput'], 'logo.jpg');
                }
                if (croppedBlobs['adminInput']) {
                    formData.set('photo_profile', croppedBlobs['adminInput'], 'admin.jpg');
                }
                if (croppedBlobs['qrisInput']) {
                    formData.set('qris_image', croppedBlobs['qrisInput'], 'qris.jpg');
                }

                try {
                    let response = await fetch(this.action, {
                        method: 'POST', // Method asli dari form, Laravel membaca @method('PUT') dari token
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (response.ok || response.redirected) {
                        // 1. Munculkan Pop-up Sukses yang cantik
                        document.getElementById('successPopup').classList.remove('hidden');
                        submitBtn.innerHTML = '<i class="fa-solid fa-check"></i> Tersimpan';

                        // 2. Tunggu 1.5 detik agar user bisa melihat popup, lalu otomatis redirect
                        setTimeout(() => {
                            window.location.href = "{{ route('admin.profil') }}";
                        }, 1500);

                    } else {
                        alert("Gagal menyimpan data. Pastikan format file sesuai (Maks 2MB).");
                        submitBtn.innerHTML = originalBtnText;
                        submitBtn.disabled = false;
                    }
                } catch (error) {
                    alert("Terjadi kesalahan jaringan.");
                    submitBtn.innerHTML = originalBtnText;
                    submitBtn.disabled = false;
                }
            });
        </script>
    @endpush
@endsection