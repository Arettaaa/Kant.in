@extends('layouts.app')

@section('title', 'Kantin Mitra - Kant.in Global')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .sidebar-link.active {
        background-color: #FFF3E8;
        color: #FF6900 !important;
    }

    /* Modal Backdrop */
    .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(4px);
        display: none;
        position: fixed;
        inset: 0;
        z-index: 100;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    /* Box Modal dengan Internal Scroll */
    .modal-container {
        background: white;
        border-radius: 44px;
        width: 100%;
        max-width: 480px;
        max-height: 90vh;
        overflow-y: auto;
        position: relative;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    body.modal-open {
        overflow: hidden;
    }

    .select-custom {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23D1D5DB'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1.5rem center;
        background-size: 1.2rem;
    }

    /* Force jam pakai format 24 jam (HH:MM) bukan AM/PM */
    input[type="time"] {
        -webkit-appearance: none;
        appearance: none;
    }
</style>
@endpush

@section('content')
<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden text-start">

    {{-- ======================== SIDEBAR (VERSI AWAL BOS) ======================== --}}
    @include('admin_global.partials.sidebar')


    {{-- ======================== MAIN CONTENT ======================== --}}
    <main class="flex-1 h-screen overflow-y-auto hide-scrollbar flex flex-col text-start">
        <header
            class="w-full bg-white border-b border-gray-100 px-10 py-6 sticky top-0 z-50 flex justify-between items-center shadow-sm">
            <div>
                <h2 class="text-2xl font-black text-gray-900 leading-none mb-1">Kantin Mitra</h2>
                <p id="realtimeDate" class="text-sm text-gray-400 font-bold tracking-wide"></p>
            </div>
            <button onclick="openModal('modalTambah')"
                class="flex items-center gap-2 px-7 py-3.5 rounded-2xl bg-[#FF6900] text-white font-bold text-sm hover:brightness-110 transition-all shadow-lg shadow-orange-100">
                <i class="fa-solid fa-plus text-xs"></i> Tambah Kantin
            </button>
        </header>

        {{-- Flash Message — taruh setelah </header> sebelum <div class="p-10"> --}}
            @if(session('success'))
            <div id="flashSuccess"
                class="mx-10 mt-6 px-6 py-4 bg-green-50 border border-green-100 rounded-2xl flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-check text-green-500 text-xs"></i>
                    </div>
                    <span class="text-sm font-bold text-green-700">{{ session('success') }}</span>
                </div>
                <button onclick="document.getElementById('flashSuccess').remove()"
                    class="text-green-400 hover:text-green-600">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            @endif

            @if($errors->any())
            <div class="mx-10 mt-6 px-6 py-4 bg-red-50 border border-red-100 rounded-2xl">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-xmark text-red-500 text-xs"></i>
                    </div>
                    <span class="text-sm font-bold text-red-600">{{ $errors->first() }}</span>
                </div>
            </div>
            @endif

            <div class="p-10 space-y-10">
                {{-- KARTU STATISTIK --}}
                <div class="flex gap-6 w-full">
                    <div
                        class="w-[180px] bg-white p-6 rounded-[32px] border border-gray-100 shadow-sm flex flex-col items-start transition-all hover:-translate-y-1">
                        <div
                            class="w-10 h-10 bg-orange-50 text-[#FF6900] rounded-xl flex items-center justify-center mb-4">
                            <i class="fa-solid fa-store"></i>
                        </div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">TOTAL KANTIN</p>
                        <h3 class="text-3xl font-black text-gray-900">{{ $totalKantin }}</h3>
                    </div>
                    <div
                        class="w-[180px] bg-white p-6 rounded-[32px] border border-gray-100 shadow-sm flex flex-col items-start transition-all hover:-translate-y-1">
                        <div
                            class="w-10 h-10 bg-green-50 text-[#22C55E] rounded-xl flex items-center justify-center mb-4">
                            <i class="fa-solid fa-bolt-lightning text-start"></i>
                        </div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">KANTIN AKTIF</p>
                        <h3 class="text-3xl font-black text-gray-900">{{ $kantinAktif }}</h3>
                    </div>
                </div>

                {{-- SEARCH BAR --}}
                <div class="relative group">
                    <i
                        class="fa-solid fa-magnifying-glass absolute left-6 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-[#FF6900] transition-colors"></i>
                    <input type="text" id="searchInput" placeholder="Cari kantin atau pemilik..."
                        class="w-full h-[68px] pl-14 pr-8 bg-white rounded-[28px] text-sm font-bold text-gray-700 outline-none border border-gray-100 focus:ring-2 focus:ring-orange-100 transition-all shadow-sm">
                </div>

                {{-- GRID LIST KANTIN --}}
                <div id="kantinGrid" class="grid grid-cols-1 lg:grid-cols-2 gap-8 pb-12 w-full">
                    @foreach($canteens as $kantin)
                    <div class="kantin-card bg-white p-6 rounded-[36px] border border-gray-100 shadow-sm flex items-center justify-between group transition-all duration-300 hover:shadow-md"
                        data-status="{{ $kantin['status'] }}">
                        <div class="flex items-center gap-5">
                            <div
                                class="w-20 h-20 rounded-[28px] overflow-hidden bg-gray-50 border border-orange-50 flex items-center justify-center">
                                @if(!empty($kantin['image']))
                                <img src="{{ $kantin['image'] }}" class="w-full h-full object-cover">
                                @else
                                <span class="font-black text-[#FF6900] text-xl">{{ strtoupper(substr($kantin['name'], 0,
                                    2))
                                    }}</span>
                                @endif
                            </div>
                            <div>
                                <h4 class="name-target text-[17px] font-black text-gray-900 leading-none mb-1">{{
                                    $kantin['name'] }}</h4>
                                {{-- Nama Pemilik (Bu Vivi dkk) --}}
                                <p class="owner-target text-xs text-gray-400 font-bold italic mb-3">
                                    <i class="fa-solid fa-user-tie mr-1"></i> Pemilik: {{ $kantin['admin_name'] ??
                                    'Belum
                                    ada pemilik' }}
                                </p>
                                <div
                                    class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full {{ $kantin['status'] == 'active' ? 'bg-green-50 text-[#22C55E]' : 'bg-red-50 text-red-500' }} text-[10px] font-black uppercase tracking-widest">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full {{ $kantin['status'] == 'active' ? 'bg-[#22C55E]' : 'bg-red-500' }}"></span>
                                    {{ $kantin['status'] == 'active' ? 'Aktif' : 'Nonaktif' }}
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col gap-2.5">
                            {{-- Tombol Edit --}}
                            <button onclick='openEditModal(@json($kantin))'
                                class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-[#6B7280] border border-gray-100 transition-all hover:brightness-95">
                                <i class="fa-solid fa-pencil text-[13px]"></i>
                            </button>
                            {{-- Tombol Hapus --}}
                            <button
                                onclick="confirmDelete('{{ $kantin['_id'] ?? $kantin['id'] ?? 'KOSONG' }}', '{{ $kantin['name'] }}')"
                                class="w-10 h-10 rounded-full bg-[#FEF2F2] flex items-center justify-center text-[#EF4444] border border-[#FEE2E2] transition-all hover:brightness-95">
                                <i class="fa-solid fa-trash-can text-[13px]"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
    </main>
</div>

{{-- ======================== MODAL TAMBAH ======================== --}}
<div id="modalTambah" class="modal-backdrop">
    <div class="modal-container p-10 hide-scrollbar text-start">
        <div class="flex items-center gap-4 mb-8">
            <button onclick="closeModal('modalTambah')"
                class="w-10 h-10 rounded-full border border-gray-100 flex items-center justify-center text-gray-400 hover:bg-gray-50">
                <i class="fa-solid fa-arrow-left"></i>
            </button>
            <h2 class="text-xl font-black text-gray-900 tracking-tight">Daftarkan Mitra Baru</h2>
        </div>

        <form action="{{ route('admin.global.kantin.store') }}" method="POST" enctype="multipart/form-data"
            class="space-y-5">
            @csrf

            {{-- Info Kantin --}}
            <div class="bg-gray-50 p-5 rounded-[32px] space-y-4 shadow-inner">
                <p class="text-[10px] font-black text-[#FF6900] uppercase tracking-widest mb-2 ml-1">Informasi Kantin
                </p>

                <input type="text" name="name" placeholder="Nama Kantin (wajib)"
                    class="w-full px-6 py-4 rounded-2xl border-none font-bold text-sm shadow-sm" required>

                <input type="text" name="location" placeholder="Lokasi / Gedung / Lantai (wajib)"
                    class="w-full px-6 py-4 rounded-2xl border-none font-bold text-sm shadow-sm" required>

                {{-- Field opsional — admin kantin bisa lengkapi nanti --}}
                <input type="number" name="delivery_fee_flat" placeholder="Biaya Ongkir (opsional)"
                    class="w-full px-6 py-4 rounded-2xl border-none font-bold text-sm shadow-sm">

                <div class="flex gap-2">
                    <div class="flex-1">
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1 block">Jam
                            Buka (WIB)</label>
                        <input type="text" name="operating_hours[open]" id="tambah_jam_buka" oninput="validateJam(this)"
                            placeholder="07:00"
                            class="w-full px-6 py-4 rounded-2xl bg-white border border-gray-100 font-bold text-sm shadow-sm"
                            maxlength="5">
                    </div>
                    <div class="flex-1">
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1 block">Jam
                            Tutup (WIB)</label>
                        <input type="text" name="operating_hours[close]" id="tambah_jam_tutup"
                            oninput="validateJam(this)" placeholder="17:00"
                            class="w-full px-6 py-4 rounded-2xl bg-white border border-gray-100 font-bold text-sm shadow-sm"
                            maxlength="5">
                    </div>
                </div>
            </div>

            {{-- Akun Pemilik --}}
            <div class="bg-orange-50/50 p-5 rounded-[32px] space-y-4 border border-orange-100">
                <p class="text-[10px] font-black text-[#FF6900] uppercase tracking-widest mb-2 ml-1">Akun Pemilik
                    (Login)</p>

                <input type="text" name="admin_name" placeholder="Nama Lengkap Pemilik (wajib)"
                    class="w-full px-6 py-4 rounded-2xl border-none font-bold text-sm shadow-sm" required>

                <input type="email" name="admin_email" placeholder="Alamat Email (wajib)"
                    class="w-full px-6 py-4 rounded-2xl border-none font-bold text-sm shadow-sm" required>

                <input type="password" name="admin_password" placeholder="Password Login (wajib)"
                    class="w-full px-6 py-4 rounded-2xl border-none font-bold text-sm shadow-sm" required>

                <input type="text" name="admin_phone" placeholder="No. HP / WhatsApp (opsional)"
                    class="w-full px-6 py-4 rounded-2xl border-none font-bold text-sm shadow-sm">
            </div>

            <button type="submit"
                class="w-full bg-[#FF6900] py-5 text-white font-black rounded-3xl shadow-lg shadow-orange-100 hover:brightness-110 uppercase tracking-widest text-xs transition-all">
                Simpan & Aktifkan Mitra
            </button>
        </form>
    </div>
</div>

<div id="modalEdit" class="modal-backdrop">
    <div class="modal-container p-10 hide-scrollbar text-start">
        <div class="flex items-center gap-4 mb-8">
            <button onclick="closeModal('modalEdit')"
                class="w-10 h-10 rounded-full border border-gray-100 flex items-center justify-center text-gray-400 hover:bg-gray-50">
                <i class="fa-solid fa-arrow-left"></i>
            </button>
            <h2 class="text-xl font-black text-gray-900 tracking-tight">Edit Info Kantin</h2>
        </div>

        <form id="formEdit" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Info Kantin --}}
            <div class="bg-gray-50 p-5 rounded-[32px] space-y-4 shadow-inner">
                <p class="text-[10px] font-black text-[#FF6900] uppercase tracking-widest mb-2 ml-1">Informasi Kantin
                </p>

                <div>
                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1 block">Nama
                        Kantin</label>
                    <input type="text" name="name" id="edit_name"
                        class="w-full px-6 py-4 rounded-2xl bg-white border border-gray-100 font-bold text-sm shadow-sm">
                </div>

                <div>
                    <label
                        class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1 block">Lokasi</label>
                    <input type="text" name="location" id="edit_location"
                        class="w-full px-6 py-4 rounded-2xl bg-white border border-gray-100 font-bold text-sm shadow-sm">
                </div>

                <div>
                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1 block">No. HP
                        Kantin</label>
                    <input type="text" name="phone" id="edit_phone"
                        class="w-full px-6 py-4 rounded-2xl bg-white border border-gray-100 font-bold text-sm shadow-sm"
                        placeholder="Contoh: 08123456789">
                </div>

                <div>
                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1 block">Ongkir
                        Flat</label>
                    <input type="text" id="edit_delivery_fee_display"
                        class="w-full px-6 py-4 rounded-2xl bg-white border border-gray-100 font-bold text-sm shadow-sm"
                        placeholder="Rp 0" oninput="formatRupiah(this, 'edit_delivery_fee_flat')">
                    <input type="hidden" name="delivery_fee_flat" id="edit_delivery_fee_flat">
                </div>


                <div>
                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1 block">Jam
                        Operasional (WIB)</label>
                    <div class="inline-flex gap-2">
                        <input type="text" name="operating_hours[open]" id="edit_jam_buka" oninput="validateJam(this)"
                            placeholder="07:00"
                            class="w-24 px-4 py-4 rounded-2xl bg-white border border-gray-100 font-bold text-sm shadow-sm text-center"
                            maxlength="5">
                        <input type="text" name="operating_hours[close]" id="edit_jam_tutup" oninput="validateJam(this)"
                            placeholder="17:00"
                            class="w-24 px-4 py-4 rounded-2xl bg-white border border-gray-100 font-bold text-sm shadow-sm text-center"
                            maxlength="5">
                    </div>
                </div>
                <div>
                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1 block">Status
                        Kantin</label>
                    <select name="status" id="edit_status"
                        class="w-full px-6 py-4 rounded-2xl bg-white border border-gray-100 font-bold text-sm select-custom">
                        <option value="active">AKTIF</option>
                        <option value="inactive">NONAKTIF</option>
                    </select>
                </div>
            </div>

            {{-- Info Pemilik --}}
            <div class="bg-orange-50/50 p-5 rounded-[32px] space-y-4 border border-orange-100">
                <p class="text-[10px] font-black text-[#FF6900] uppercase tracking-widest mb-2 ml-1">Kontak Pemilik</p>

                <div>
                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1 block">Nama
                        Pemilik</label>
                    <input type="text" id="edit_admin_name" disabled
                        class="w-full px-6 py-4 rounded-2xl bg-gray-100 border border-gray-100 font-bold text-sm text-gray-400 cursor-not-allowed">
                    <p class="text-[9px] text-gray-300 ml-1 mt-1">Nama tidak bisa diubah dari sini</p>
                </div>

                <div>
                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1 block">No. HP
                        Pemilik</label>
                    <input type="text" name="admin_phone" id="edit_admin_phone"
                        class="w-full px-6 py-4 rounded-2xl bg-white border border-gray-100 font-bold text-sm shadow-sm"
                        placeholder="Contoh: 08123456789">
                </div>
            </div>

            <button type="submit"
                class="w-full bg-[#FF6900] py-5 text-white font-black rounded-3xl shadow-lg shadow-orange-100 hover:brightness-110 uppercase tracking-widest text-xs transition-all">
                Simpan Perubahan
            </button>
        </form>
    </div>
</div>

<div id="modalHapus" class="modal-backdrop">
    {{-- ✅ Hapus inline p-10 di sini, biar tidak bentrok dengan modal-container --}}
    <div class="bg-white w-full max-w-sm rounded-[40px] p-10 shadow-2xl text-center">
        <form id="formDelete" method="POST">
            @csrf
            @method('DELETE')

            <div
                class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center text-red-500 mx-auto mb-6 border border-red-100">
                <i class="fa-solid fa-trash-can text-3xl"></i>
            </div>

            <h3 class="text-2xl font-black text-gray-900 mb-3">Hapus Kantin?</h3>
            <p class="text-sm text-gray-500 font-medium leading-relaxed mb-10">
                Kantin <span id="del_name" class="font-black text-gray-900"></span> akan dihapus permanen beserta akun
                pemiliknya.
            </p>

            {{-- ✅ Tombol ini tidak lagi kepotong --}}
            <div class="grid grid-cols-2 gap-4">
                <button type="button" onclick="closeModal('modalHapus')"
                    class="py-4 bg-gray-100 text-gray-600 rounded-2xl font-black text-xs uppercase tracking-widest">
                    Batal
                </button>
                <button type="submit"
                    class="py-4 bg-[#FF3B30] text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-red-100">
                    Ya, Hapus
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
    // --- REALTIME DATE WIB ---
    function updateDateTime() {
        const now = new Date();
        const optDate = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', timeZone: 'Asia/Jakarta' };
        const dateEl = document.getElementById('realtimeDate');
        if (dateEl) dateEl.innerText = now.toLocaleDateString('id-ID', optDate);
    }
    updateDateTime();
    setInterval(updateDateTime, 1000);

    // --- LIVE SEARCH ---
    document.getElementById('searchInput').addEventListener('input', function () {
        let search = this.value.toLowerCase();
        document.querySelectorAll('.kantin-card').forEach(card => {
            let name  = card.querySelector('.name-target').innerText.toLowerCase();
            let owner = card.querySelector('.owner-target').innerText.toLowerCase();
            card.style.display = (name.includes(search) || owner.includes(search)) ? 'flex' : 'none';
        });
    });
});

function formatRupiah(inputDisplay, hiddenId) {
    let raw    = inputDisplay.value.replace(/[^0-9]/g, '');
    let number = parseInt(raw) || 0;
    inputDisplay.value = 'Rp ' + number.toLocaleString('id-ID');
    document.getElementById(hiddenId).value = number;
}

function validateJam(input) {
    let val = input.value.replace(/[^0-9]/g, '');
    if (val.length >= 3) {
        val = val.substring(0, 2) + ':' + val.substring(2, 4);
    }
    input.value = val;
}

function openModal(id) {
    document.getElementById(id).style.display = 'flex';
    document.body.classList.add('modal-open');
}

function closeModal(id) {
    document.getElementById(id).style.display = 'none';
    document.body.classList.remove('modal-open');
}

function confirmDelete(id, name) {
    document.getElementById('formDelete').action = `/admin/global/kantin-mitra/${id}`;
    document.getElementById('del_name').innerText = name;
    openModal('modalHapus');
}

function openEditModal(kantin) {
    const form   = document.getElementById('formEdit');
    let kantinId = kantin._id || kantin.id;
    form.action  = `/admin/global/kantin-mitra/${kantinId}`;

    document.getElementById('edit_name').value     = kantin.name     || '';
    document.getElementById('edit_location').value = kantin.location || '';
    document.getElementById('edit_phone').value    = kantin.phone    || '';
    document.getElementById('edit_status').value   = kantin.status   || 'active';

    document.getElementById('edit_jam_buka').value  = kantin.operating_hours?.open  || '';
    document.getElementById('edit_jam_tutup').value = kantin.operating_hours?.close || '';

    let ongkir = parseInt(kantin.delivery_fee_flat) || 0;
    document.getElementById('edit_delivery_fee_display').value = ongkir > 0
        ? 'Rp ' + ongkir.toLocaleString('id-ID') : '';
    document.getElementById('edit_delivery_fee_flat').value = ongkir;

    document.getElementById('edit_admin_name').value  = kantin.admin_name  || '';
    document.getElementById('edit_admin_phone').value = kantin.admin_phone || '';

    openModal('modalEdit');
}

window.onclick = function (e) {
    if (e.target.classList.contains('modal-backdrop')) {
        e.target.style.display = 'none';
        document.body.classList.remove('modal-open');
    }
}
</script>
@endpush