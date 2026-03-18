@extends('layouts.app')

@section('title', 'Pusat Bantuan - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .faq-answer {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out, padding 0.3s ease;
    }
    .faq-item.active .faq-answer {
        max-height: 200px; /* Adjust as needed */
        padding-top: 1rem;
    }
    .faq-item.active .chevron-icon {
        transform: rotate(180deg);
        color: #FF6900;
    }
    .faq-item.active {
        border-color: #FF6900;
        background-color: #FFF8F3;
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
            <a href="/admin/riwayat" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
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
        <div class="sticky top-0 z-10 w-full flex items-center gap-4 px-10 py-6 bg-white border-b border-gray-100 text-start">
            <a href="/admin/profil" class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 hover:bg-gray-50 transition-all text-start text-start">
                <i class="fa-solid fa-arrow-left text-gray-400 text-start"></i>
            </a>
            <div class="text-start">
                <h2 class="text-xl font-extrabold text-gray-900 leading-none mb-1 text-start">Pusat Bantuan</h2>
                <p class="text-[12px] text-gray-400 font-medium text-start">Temukan jawaban untuk kendala Anda</p>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto px-10 py-8">
            <div class="max-w-4xl mx-auto space-y-8">

                {{-- FAQ Section --}}
                <div class="space-y-4 text-start">
                    <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest ml-2 mb-4 text-start">Pertanyaan Populer</h3>

                    @php
                        $faqs = [
                            [
                                'q' => 'Bagaimana cara mengubah status menu menjadi "Habis"?',
                                'a' => 'Buka menu "Kelola Menu", cari item yang ingin diubah, lalu tekan tombol switch di bagian kanan bawah card menu tersebut hingga berwarna abu-abu.'
                            ],
                            [
                                'q' => 'Apakah saya bisa mengatur jam buka yang berbeda setiap harinya?',
                                'a' => 'Tentu! Masuk ke Profil Kantin > Jam Operasional. Anda bisa mengatur jam buka-tutup secara manual untuk tiap hari atau menggunakan fitur "Salin ke Semua Hari" untuk mempercepat.'
                            ],
                            [
                                'q' => 'Pesanan pelanggan tidak muncul di tab "Masuk", apa yang harus dilakukan?',
                                'a' => 'Pastikan koneksi internet Anda stabil. Coba segarkan (refresh) halaman. Jika masih terkendala, pastikan status Kantin Anda di Profil sedang dalam kondisi "Buka".'
                            ],
                            [
                                'q' => 'Bagaimana cara menghapus menu yang sudah tidak dijual lagi?',
                                'a' => 'Pada halaman "Kelola Menu", klik ikon tempat sampah (warna merah) di pojok kanan atas card menu. Anda akan diminta melakukan konfirmasi sebelum menu benar-benar dihapus.'
                            ],
                            [
                                'q' => 'Dimana saya bisa melihat total pendapatan harian?',
                                'a' => 'Semua transaksi yang sudah selesai dapat Anda lihat secara mendalam pada menu "Riwayat Transaksi" di sidebar sebelah kiri.'
                            ]
                        ];
                    @endphp

                    @foreach($faqs as $index => $faq)
                    <div class="faq-item group bg-white rounded-[28px] border border-gray-100 shadow-sm p-6 cursor-pointer transition-all duration-300" onclick="toggleFaq(this)">
                        <div class="flex items-center justify-between gap-4 text-start">
                            <h4 class="text-[15px] font-bold text-gray-800 leading-tight text-start">{{ $faq['q'] }}</h4>
                            <i class="fa-solid fa-chevron-down text-gray-300 text-sm transition-transform duration-300 chevron-icon text-start"></i>
                        </div>
                        <div class="faq-answer text-start">
                            <p class="text-sm text-gray-500 font-medium leading-relaxed text-start border-t border-gray-50 pt-4">
                                {{ $faq['a'] }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Footer Help --}}
                <div class="py-10 text-center text-start">
                    <p class="text-sm text-gray-400 font-medium text-start text-center">Masih butuh bantuan lain? Hubungi Admin Utama.</p>
                </div>
            </div>
        </div>
    </main>
</div>

@push('scripts')
<script>
    function toggleFaq(element) {
        // Close other FAQ items
        document.querySelectorAll('.faq-item').forEach(item => {
            if (item !== element) {
                item.classList.remove('active');
            }
        });

        // Toggle current item
        element.classList.toggle('active');
    }
</script>
@endpush
@endsection