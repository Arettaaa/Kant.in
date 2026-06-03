@extends('layouts.app')

{{-- Title dinamis sesuai nama menu --}}
@section('title', 'Edit Menu: ' . $menu['name'] . ' - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
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

    {{-- SIDEBAR --}}
    @include('admin.partials.sidebar')

    {{-- MAIN CONTENT --}}
    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-[#F9FAFB] text-start">
        
        {{-- Header --}}
        <div class="sticky top-0 z-10 w-full flex items-center gap-4 px-10 py-6 bg-white/90 backdrop-blur-md border-b border-gray-100 text-start">
            <a href="{{ route('admin.menu') }}" class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 hover:bg-gray-50 transition-all text-start">
                <i class="fa-solid fa-arrow-left text-gray-400 text-start"></i>
            </a>
            <h2 class="text-xl font-extrabold text-gray-900 leading-none text-start">Edit Menu</h2>
        </div>

        {{-- Form Edit: Action ke update, method POST, tambah @method('PUT') --}}
        <form id="editMenuForm" action="{{ route('admin.menu.update', $menu['_id']) }}" method="POST" enctype="multipart/form-data" class="px-10 py-8 space-y-8 pb-32 text-start">
            @csrf
            @method('PUT')
            
            {{-- Upload Gambar --}}
            <div class="w-full text-start">
                <p class="text-xs font-black text-gray-900 uppercase tracking-widest mb-4 text-start">Gambar Menu</p>
                <label for="imageUpload" class="group cursor-pointer w-full h-48 border-2 border-dashed border-gray-200 rounded-[32px] bg-white flex flex-col items-center justify-center gap-3 hover:border-[#FF6900] transition-all overflow-hidden relative text-start">
                    {{-- Tampilkan gambar lama dari database --}}
                    <img id="imagePreview" src="{{ $menu['image'] ?? 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?w=400' }}" class="absolute inset-0 w-full h-full object-cover rounded-[30px] text-start" />
                    
                    {{-- Beri feedback visual saat di-hover --}}
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-all flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 z-10">
                        <i class="fa-solid fa-camera text-white text-3xl mb-2"></i>
                        <p class="text-white font-bold text-sm">Ganti Gambar</p>
                    </div>

                    <input type="file" name="image" id="imageUpload" class="hidden text-start" accept="image/*">
                </label>
                @error('image') <span class="text-red-500 text-xs font-bold mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 text-start">
                {{-- Kolom Kiri --}}
                <div class="space-y-6 text-start">
                    <div class="text-start">
                        <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3 text-start">Nama Menu</p>
                        <input type="text" name="name" id="namaMenu" value="{{ old('name', $menu['name']) }}" required placeholder="cth. Nasi Goreng Spesial" class="w-full px-6 py-4 rounded-2xl bg-white border border-gray-100 shadow-sm focus:outline-none focus:border-[#FF6900] font-bold text-gray-800 transition-all text-start">
                        @error('name') <span class="text-red-500 text-xs font-bold mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="text-start">
                        <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3 text-start">Harga (IDR)</p>
                        <div class="relative text-start">
                            <span class="absolute left-6 top-1/2 -translate-y-1/2 font-bold text-gray-400 text-start">Rp</span>
                            <input type="number" name="price" id="hargaMenu" value="{{ old('price', $menu['price']) }}" required placeholder="25000" class="w-full pl-14 pr-6 py-4 rounded-2xl bg-white border border-gray-100 shadow-sm focus:outline-none focus:border-[#FF6900] font-bold text-gray-800 transition-all text-start">
                        </div>
                        @error('price') <span class="text-red-500 text-xs font-bold mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="text-start">
                        <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3 text-start">Kategori</p>
                        <select name="category" id="kategoriMenu" required class="custom-select w-full px-6 py-4 rounded-2xl bg-white border border-gray-100 shadow-sm focus:outline-none focus:border-[#FF6900] font-bold text-gray-800 transition-all text-start">
                            <option value="Makanan" {{ old('category', $menu['category'] ?? '') == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                            <option value="Minuman" {{ old('category', $menu['category'] ?? '') == 'Minuman' ? 'selected' : '' }}>Minuman</option>
                            <option value="Camilan" {{ old('category', $menu['category'] ?? '') == 'Camilan' ? 'selected' : '' }}>Camilan</option>
                        </select>
                        @error('category') <span class="text-red-500 text-xs font-bold mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div class="space-y-6 text-start">
                    <div class="text-start">
                        <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3 text-start">Deskripsi</p>
                        <textarea name="description" id="deskripsiMenu" rows="4" placeholder="Jelaskan menu Anda..." class="w-full px-6 py-4 rounded-3xl bg-white border border-gray-100 shadow-sm focus:outline-none focus:border-[#FF6900] font-bold text-gray-800 transition-all text-start">{{ old('description', $menu['description'] ?? '') }}</textarea>
                        @error('description') <span class="text-red-500 text-xs font-bold mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="bg-white p-6 rounded-[32px] border border-gray-100 shadow-sm flex items-center justify-between text-start">
                        <div class="text-start">
                            <p class="text-sm font-black text-gray-800 text-start">Ketersediaan</p>
                            <p class="text-[11px] text-gray-400 font-medium text-start">Matikan jika item habis</p>
                        </div>
                        
                        @php
                            // Ambil status ketersediaan (default ke nilai dari database)
                            $isAvail = old('is_available', $menu['is_available'] ?? true);
                        @endphp
                        
                        {{-- Hidden input --}}
                        <input type="hidden" name="is_available" id="isAvailableInput" value="{{ $isAvail ? '1' : '0' }}">

                        {{-- Toggle Button yang diperbaiki --}}
                        <button type="button" onclick="toggleSwitch()" id="switchBtn" class="relative inline-flex h-6 w-12 items-center rounded-full transition-colors duration-300 ease-in-out {{ $isAvail ? 'bg-[#22c55e]' : 'bg-gray-300' }} text-start">
                            <span id="switchCircle" class="inline-block h-5 w-5 transform rounded-full bg-white shadow-sm transition-transform duration-300 ease-in-out {{ $isAvail ? 'translate-x-6' : 'translate-x-1' }} text-start"></span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Floating Button di Bawah --}}
            <div class="fixed bottom-0 right-0 left-[240px] p-6 bg-white/80 backdrop-blur-md border-t border-gray-100 z-20 flex justify-center text-start">
                <div class="w-full max-w-lg flex gap-4 text-start">
                    {{-- Tombol Buka Modal Hapus (Merah) --}}
                    <button type="button" onclick="openDeleteModal()" class="w-1/3 py-4 bg-red-50 text-red-500 rounded-2xl font-black text-sm flex items-center justify-center gap-3 hover:bg-red-100 transition-all text-start">
                        <i class="fa-solid fa-trash-can text-start"></i>
                        Hapus
                    </button>
                    {{-- Tombol Simpan (Orange) - Ubah ke type submit --}}
                    <button type="submit" class="flex-1 py-4 bg-[#FF6900] text-white rounded-2xl font-black text-sm shadow-xl flex items-center justify-center gap-3 hover:brightness-110 transition-all text-start">
                        <i class="fa-solid fa-floppy-disk text-start"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </div>

        </form>
    </main>
</div>

{{-- ======================== MODAL HAPUS ======================== --}}
<div id="deleteModal" class="fixed inset-0 z-50 flex items-center justify-center hidden text-start">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm text-start" onclick="closeDeleteModal()"></div>
    
    <div class="relative bg-white rounded-[40px] p-10 shadow-2xl w-full max-w-md transform transition-all scale-95 opacity-0 duration-300 text-start" id="modalContent">
        <div class="flex flex-col items-center text-center text-start">
            <div class="w-20 h-20 rounded-full bg-red-50 flex items-center justify-center text-red-500 mb-6 border-4 border-red-100 text-start">
                <i class="fa-solid fa-trash-can text-3xl text-start"></i>
            </div>
            
            <h3 class="text-xl font-black text-gray-900 mb-2 text-start">Hapus Menu Ini?</h3>
            <p class="text-sm text-gray-400 font-medium mb-10 leading-relaxed text-start">
                Menu <span class="font-bold text-gray-800 text-start">{{ $menu['name'] }}</span> akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.
            </p>
            
            <div class="flex gap-4 w-full text-start">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 py-4 bg-gray-100 text-gray-500 rounded-2xl font-bold text-sm hover:bg-gray-200 transition-all text-start flex items-center justify-center">
                    Batal
                </button>
                
                {{-- Form khusus untuk menghapus data --}}
                <form action="{{ route('admin.menu.delete', $menu['_id']) }}" method="POST" class="flex-1 m-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full py-4 bg-red-500 text-white rounded-2xl font-black text-sm hover:bg-red-600 transition-all shadow-lg shadow-red-200 text-start flex items-center justify-center">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Logic Switch Ketersediaan 
    let isAvailable = {{ $isAvail ? 'true' : 'false' }};
    
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

    // Logic Preview Gambar
    const imgInput = document.getElementById('imageUpload');
    const imgPreview = document.getElementById('imagePreview');
    imgInput.onchange = () => {
        const [file] = imgInput.files;
        if (file) imgPreview.src = URL.createObjectURL(file);
    }

    // Logic Modal Hapus
    function openDeleteModal() {
        const modal = document.getElementById('deleteModal');
        const content = document.getElementById('modalContent');
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        const content = document.getElementById('modalContent');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>
@endpush
@endsection