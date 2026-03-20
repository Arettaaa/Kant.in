@extends('layouts.app')

@section('title', 'Atur Jam Operasional - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

    /* Styling native time picker agar bersih */
    input[type="time"] {
        position: relative;
    }
    input[type="time"]::-webkit-calendar-picker-indicator {
        position: absolute;
        right: 0;
        cursor: pointer;
        filter: invert(48%) sepia(13%) saturate(3207%) hue-rotate(1deg) brightness(95%) contrast(80%);
    }
</style>
@endpush

@section('content')
<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden text-start">

    {{-- SIDEBAR --}}
    <aside class="w-[240px] h-screen bg-white flex flex-col py-8 px-6 shadow-sm flex-shrink-0 z-20 border-r border-gray-100">
        <div class="flex items-center gap-3 mb-10 px-2 text-start">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg" style="background-color:#FF6900;">
                <i class="fa-solid fa-store text-lg text-white"></i>
            </div>
            <span class="text-xl font-extrabold text-gray-900 tracking-tight text-start">Kantin</span>
        </div>
        <nav class="flex flex-col gap-2 flex-1 text-start">
            <a href="/admin/pesanan" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Pesanan
            </a>
            <a href="/admin/menu" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                Kelola Menu
            </a>
            <a href="/admin/riwayat" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all text-start">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                Riwayat Transaksi
            </a>
            <a href="/admin/profil" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold transition-all" style="background-color:#FFF3E8;color:#FF6900;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Profil Kantin
            </a>
        </nav>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 flex flex-col h-screen overflow-hidden bg-[#F9FAFB] text-start">
        
        {{-- Header --}}
        <div class="sticky top-0 z-10 w-full flex items-center justify-between px-10 py-6 bg-white border-b border-gray-100">
            <div class="flex items-center gap-4 text-start">
                <a href="/admin/profil" class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 hover:bg-gray-50 transition-all text-start">
                    <i class="fa-solid fa-arrow-left text-gray-400"></i>
                </a>
                <div class="text-start">
                    <h2 class="text-xl font-extrabold text-gray-900 leading-none mb-1 text-start">Jam Operasional</h2>
                    <p class="text-[12px] text-gray-400 font-medium text-start">Atur waktu buka toko Anda</p>
                </div>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto hide-scrollbar px-10 py-8">
            <div class="max-w-6xl mx-auto space-y-6 pb-32">
                
                {{-- Sinkronkan Waktu Card --}}
                <div class="bg-[#FFF8F3] rounded-3xl p-6 border border-[#FFE0CC] flex items-center justify-between text-start">
                    <div class="flex items-center gap-4 text-start">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center bg-white text-[#FF6900] shadow-sm text-start">
                            <i class="fa-regular fa-calendar-days text-xl"></i>
                        </div>
                        <div class="text-start">
                            <h4 class="text-sm font-black text-gray-900">Sinkronkan Waktu</h4>
                            <p class="text-[11px] text-gray-500 font-medium">Salin waktu Senin ke semua hari</p>
                        </div>
                    </div>
                    <button type="button" onclick="syncAllDays()" class="px-5 py-2.5 rounded-xl border border-[#FF6900] text-[#FF6900] font-bold text-xs hover:bg-[#FF6900] hover:text-white transition-all flex items-center gap-2">
                        <i class="fa-regular fa-copy"></i> Salin
                    </button>
                </div>

                {{-- Grid Layout 2 Kolom --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-start">
                    @php
                        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                    @endphp

                    @foreach($days as $day)
                    <div id="card-{{ $day }}" class="day-card bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 flex flex-col gap-5 transition-all duration-300 text-start">
                        
                        {{-- Atas: Nama Hari & Switch --}}
                        <div class="flex items-center justify-between text-start">
                            <div class="flex items-center gap-2 text-start">
                                <i class="fa-regular fa-clock text-[#FF6900] text-sm"></i>
                                <h4 class="day-name text-base font-black text-gray-800">{{ $day }}</h4>
                            </div>
                            <button type="button" onclick="toggleDayStatus('{{ $day }}')" class="toggle-btn relative inline-flex items-center w-11 h-6 rounded-full transition-all bg-[#FF6900]">
                                <span class="toggle-circle absolute w-4 h-4 bg-white rounded-full transition-all left-[24px]"></span>
                            </button>
                        </div>

                        {{-- Bawah: Input Jam (Menyamping sesuai design) --}}
                        <div class="jam-container flex items-center gap-3 text-start">
                            <div class="flex-1 text-start">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Buka</p>
                                <div class="relative bg-gray-50 rounded-2xl px-4 py-3 border border-gray-100 text-start">
                                    <input type="time" id="open-{{ $day }}" value="08:00" class="time-input w-full bg-transparent text-sm font-black text-gray-800 focus:outline-none text-center">
                                </div>
                            </div>
                            
                            <span class="text-gray-300 mt-6 font-bold">-</span>

                            <div class="flex-1 text-start">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Tutup</p>
                                <div class="relative bg-gray-50 rounded-2xl px-4 py-3 border border-gray-100 text-start">
                                    <input type="time" id="close-{{ $day }}" value="17:00" class="time-input w-full bg-transparent text-sm font-black text-gray-800 focus:outline-none text-center">
                                </div>
                            </div>
                        </div>

                        {{-- Pesan Tutup (Hidden by Default) --}}
                        <div class="tutup-pesan hidden h-[68px] flex items-center justify-center bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                            <span class="text-[11px] font-black tracking-widest text-gray-400 uppercase">Tutup</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Fixed Button Simpan --}}
        <div class="fixed bottom-0 right-0 left-[240px] p-6 bg-white/80 backdrop-blur-md border-t border-gray-100 z-20 flex justify-center text-start">
            <button type="submit" class="w-full max-w-sm py-4 bg-[#FF6900] text-white rounded-2xl font-black text-sm shadow-xl hover:brightness-110 transition-all flex items-center justify-center gap-3">
                <i class="fa-solid fa-check"></i>
                Simpan Jadwal
            </button>
        </div>

    </main>
</div>

@push('scripts')
<script>
    function toggleDayStatus(day) {
        const card = document.getElementById('card-' + day);
        const btn = card.querySelector('.toggle-btn');
        const circle = card.querySelector('.toggle-circle');
        const jamContainer = card.querySelector('.jam-container');
        const tutupPesan = card.querySelector('.tutup-pesan');

        if (!card.classList.contains('is-closed')) {
            // SET KE TUTUP
            card.classList.add('is-closed', 'opacity-60');
            btn.classList.replace('bg-[#FF6900]', 'bg-gray-200');
            circle.style.left = '4px';
            jamContainer.classList.add('hidden');
            tutupPesan.classList.remove('hidden');
        } else {
            // SET KE BUKA
            card.classList.remove('is-closed', 'opacity-60');
            btn.classList.replace('bg-gray-200', 'bg-[#FF6900]');
            circle.style.left = '24px';
            jamContainer.classList.remove('hidden');
            tutupPesan.classList.add('hidden');
        }
    }

    function syncAllDays() {
        const openVal = document.getElementById('open-Senin').value;
        const closeVal = document.getElementById('close-Senin').value;
        const days = ['Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        days.forEach(day => {
            document.getElementById('open-' + day).value = openVal;
            document.getElementById('close-' + day).value = closeVal;
            
            const card = document.getElementById('card-' + day);
            if(card.classList.contains('is-closed')) {
                toggleDayStatus(day); // Buka kembali kalau sedang tutup biar sinkron
            }
        });
        alert('Jadwal Senin berhasil disalin!');
    }
</script>
@endpush
@endsection