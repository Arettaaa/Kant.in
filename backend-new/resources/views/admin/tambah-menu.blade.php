@extends('layouts.app')

@section('title', 'Tambah Menu Baru - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    /* Custom Dropdown Styling */
    .custom-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239ca3af'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1.2em;
    }
</style>
@endpush

@section('content')
<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden text-start">

    {{-- Memanggil Sidebar dari partials --}}
    @include('admin.partials.sidebar')

    {{-- MAIN CONTENT --}}
    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-[#F9FAFB]">
        
        
        <div class="sticky top-0 z-10 w-full flex items-center gap-4 px-10 py-6 bg-white/90 backdrop-blur-md border-b border-gray-100">
            <a href="{{ route('admin.menu') }}" class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 hover:bg-gray-50 transition-all">
                <i class="fa-solid fa-arrow-left text-gray-400"></i>
            </a>
            <h2 class="text-xl font-extrabold text-gray-900 leading-none">Tambah Menu Baru</h2>
        </div>

        
        <form id="menuForm" action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data" class="px-10 py-8 space-y-8 pb-32">
            @csrf
            
            
            <div class="w-full">
                <p class="text-xs font-black text-gray-900 uppercase tracking-widest mb-4">Gambar Menu</p>
                <label for="imageUpload" class="group cursor-pointer w-full h-48 border-2 border-dashed border-gray-200 rounded-[32px] bg-white flex flex-col items-center justify-center gap-3 hover:border-[#FF6900] transition-all overflow-hidden relative">
                    <div id="previewContainer" class="flex flex-col items-center">
                        <div class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center text-[#FF6900] group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-cloud-arrow-up text-xl"></i>
                        </div>
                        <div class="text-center mt-2">
                            <p class="text-sm font-bold text-gray-800">Ketuk untuk mengunggah gambar</p>
                            <p class="text-[11px] text-gray-400 font-medium">Ukuran yang disarankan: 1:1 (Persegi)</p>
                        </div>
                    </div>
                    <img id="imagePreview" class="hidden absolute inset-0 w-full h-full object-cover rounded-[30px]" />
                    <input type="file" name="image" id="imageUpload" class="hidden" accept="image/*" required>
                </label>
                @error('image') <span class="text-red-500 text-xs font-bold mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
              
                <div class="space-y-6 text-start">
                    <div>
                        <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3">Nama Menu</p>
                        <input type="text" name="name" id="namaMenu" placeholder="cth. Nasi Goreng Spesial" value="{{ old('name') }}" required class="w-full px-6 py-4 rounded-2xl bg-white border border-gray-100 shadow-sm focus:outline-none focus:border-[#FF6900] font-bold text-gray-800 transition-all">
                        @error('name') <span class="text-red-500 text-xs font-bold mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3">Harga (IDR)</p>
                        <div class="relative">
                            <span class="absolute left-6 top-1/2 -translate-y-1/2 font-bold text-gray-400">Rp</span>
                            <input type="number" name="price" id="hargaMenu" placeholder="25000" value="{{ old('price') }}" required class="w-full pl-14 pr-6 py-4 rounded-2xl bg-white border border-gray-100 shadow-sm focus:outline-none focus:border-[#FF6900] font-bold text-gray-800 transition-all">
                        </div>
                        @error('price') <span class="text-red-500 text-xs font-bold mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3">Kategori</p>
                        <select name="category" id="kategoriMenu" required class="custom-select w-full px-6 py-4 rounded-2xl bg-white border border-gray-100 shadow-sm focus:outline-none focus:border-[#FF6900] font-bold text-gray-800 transition-all">
                            <option value="" disabled {{ old('category') ? '' : 'selected' }}>Pilih Kategori</option>
                            <option value="Makanan" {{ old('category') == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                            <option value="Minuman" {{ old('category') == 'Minuman' ? 'selected' : '' }}>Minuman</option>
                            <option value="Camilan" {{ old('category') == 'Camilan' ? 'selected' : '' }}>Camilan</option>
                        </select>
                        @error('category') <span class="text-red-500 text-xs font-bold mt-1">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3">Estimasi Waktu Masak</p>
                        <div class="relative">
                            <input type="number" name="estimated_cooking_time" id="estimasiMasak" placeholder="15" value="{{ old('estimated_cooking_time') }}" min="1" class="w-full pl-6 pr-20 py-4 rounded-2xl bg-white border border-gray-100 shadow-sm focus:outline-none focus:border-[#FF6900] font-bold text-gray-800 transition-all">
                            <span class="absolute right-6 top-1/2 -translate-y-1/2 font-bold text-gray-400">Menit</span>
                        </div>
                        @error('estimated_cooking_time') <span class="text-red-500 text-xs font-bold mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

               
                <div class="space-y-6 text-start">
                    <div>
                        <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3">Deskripsi</p>
                        <textarea name="description" id="deskripsiMenu" rows="4" placeholder="Jelaskan menu Anda..." class="w-full px-6 py-4 rounded-3xl bg-white border border-gray-100 shadow-sm focus:outline-none focus:border-[#FF6900] font-bold text-gray-800 transition-all">{{ old('description') }}</textarea>
                        @error('description') <span class="text-red-500 text-xs font-bold mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="bg-white p-6 rounded-[32px] border border-gray-100 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-sm font-black text-gray-800">Ketersediaan</p>
                            <p class="text-[11px] text-gray-400 font-medium">Matikan jika item habis</p>
                        </div>
                        
                       
                        <input type="hidden" name="is_available" id="isAvailableInput" value="1">
                        
                       
                        <button type="button" onclick="toggleSwitch()" id="switchBtn" class="relative inline-flex h-6 w-12 items-center rounded-full transition-colors duration-300 ease-in-out bg-[#22c55e]">
                            <span id="switchCircle" class="inline-block h-5 w-5 transform rounded-full bg-white shadow-sm transition-transform duration-300 ease-in-out translate-x-6"></span>
                        </button>
                    </div>
                </div>
            </div>

          
            <div class="fixed bottom-0 right-0 left-[240px] p-6 bg-white/80 backdrop-blur-md border-t border-gray-100 z-20 flex justify-center">
                <button type="submit" id="submitBtn" disabled 
                    class="w-full max-w-lg py-4 bg-[#FF6900] text-white rounded-2xl font-black text-sm shadow-xl flex items-center justify-center gap-3 transition-all duration-300 opacity-30 cursor-not-allowed">
                    <i class="fa-solid fa-check"></i>
                    Tambah Menu
                </button>
            </div>

        </form>
    </main>
</div>

@push('scripts')
<script>
    
    let isAvailable = true;
    function toggleSwitch() {
        isAvailable = !isAvailable;
        const btn = document.getElementById('switchBtn');
        const circle = document.getElementById('switchCircle');
        const hiddenInput = document.getElementById('isAvailableInput');
        
        if (isAvailable) {
            btn.classList.replace('bg-gray-300', 'bg-[#22c55e]');
            circle.classList.replace('translate-x-1', 'translate-x-6');
            hiddenInput.value = '1';
        } else {
            btn.classList.replace('bg-[#22c55e]', 'bg-gray-300');
            circle.classList.replace('translate-x-6', 'translate-x-1');
            hiddenInput.value = '0';
        }
    }

    const imgInput = document.getElementById('imageUpload');
    const imgPreview = document.getElementById('imagePreview');
    const previewContainer = document.getElementById('previewContainer');

    imgInput.onchange = (e) => {
        const [file] = imgInput.files;
        if (file) {
            imgPreview.src = URL.createObjectURL(file);
            imgPreview.classList.remove('hidden');
            previewContainer.classList.add('hidden');
            validateForm();
        }
    }

    const formInputs = ['namaMenu', 'hargaMenu', 'kategoriMenu'];
    
    function validateForm() {
        const btn = document.getElementById('submitBtn');
        const isImgFilled = imgInput.files.length > 0;
        const isFormFilled = formInputs.every(id => document.getElementById(id).value.trim() !== "");

        if (isImgFilled && isFormFilled) {
            btn.disabled = false;
            btn.classList.remove('opacity-30', 'cursor-not-allowed');
            btn.classList.add('hover:brightness-110');
        } else {
            btn.disabled = true;
            btn.classList.add('opacity-30', 'cursor-not-allowed');
            btn.classList.remove('hover:brightness-110');
        }
    }

    formInputs.forEach(id => {
        document.getElementById(id).addEventListener('input', validateForm);
        document.getElementById(id).addEventListener('change', validateForm);
    });

    document.addEventListener('DOMContentLoaded', validateForm);
</script>
@endpush
@endsection