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
            max-height: 200px;
            /* Adjust as needed */
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
        <aside
            class="w-[240px] h-screen bg-white flex flex-col py-8 px-6 shadow-sm flex-shrink-0 z-20 border-r border-gray-100">
            <div class="flex items-center gap-3 mb-10 px-2 text-start">
                <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg"
                    style="background-color:#FF6900;">
                    <i class="fa-solid fa-store text-lg text-white"></i>
                </div>
                <span class="text-xl font-extrabold text-gray-900 tracking-tight text-start">Kantin</span>
            </div>
            <nav class="flex flex-col gap-2 flex-1 text-start">
                <a href="/admin/pesanan"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Pesanan
                </a>
                <a href="/admin/menu"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                    Kelola Menu
                </a>
                <a href="/admin/riwayat"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    Riwayat Transaksi
                </a>
                <a href="/admin/profil"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold transition-all"
                    style="background-color:#FFF3E8;color:#FF6900;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Profil Kantin
                </a>
            </nav>
        </aside>

        {{-- MAIN CONTENT --}}
        <main class="flex-1 flex flex-col h-screen overflow-hidden bg-[#F9FAFB] text-start">

            {{-- Header --}}
            <div
                class="sticky top-0 z-10 w-full flex items-center gap-4 px-10 py-6 bg-white border-b border-gray-100 text-start">
                <a href="/admin/profil"
                    class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 hover:bg-gray-50 transition-all text-start">
                    <i class="fa-solid fa-arrow-left text-gray-400"></i>
                </a>
                <div class="text-start">
                    <h2 class="text-xl font-extrabold text-gray-900 leading-none mb-1 text-start">Pusat Bantuan</h2>
                    <p class="text-[12px] text-gray-400 font-medium text-start">Pusat bantuan untuk Mitra Kantin</p>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto px-10 py-8">
                <div class="max-w-4xl mx-auto space-y-8">

                    {{-- FAQ Section --}}
                    <div class="space-y-4 text-start">
                        <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest ml-2 mb-4 text-start">
                            Pertanyaan Populer</h3>

                        @php
                            $faqs = [
                                [
                                    'q' => 'Bagaimana cara menerima pesanan baru yang masuk?',
                                    'a' => "Buka 'Dashboard Admin Kantin' pada tab 'Pesanan Masuk'. Anda bisa melihat rincian pesanan dari pelanggan, lalu tekan tombol hijau 'Terima Pesanan' untuk mulai memprosesnya, atau 'Tolak' jika pesanan tidak dapat dipenuhi."
                                ],
                                [
                                    'q' => 'Bagaimana cara memverifikasi bukti pembayaran pelanggan?',
                                    'a' => "Masuk ke halaman 'Detail Pesanan', lalu gulir ke area 'Bukti Pembayaran'. Anda dapat meninjau foto bukti transfer yang diunggah pelanggan, mencentang konfirmasi verifikasi, dan memilih tombol 'Verifikasi & Terima' untuk melanjutkan pesanan."
                                ],
                                [
                                    'q' => 'Bagaimana cara memberitahu pelanggan bahwa makanan sedang dimasak atau sudah siap?',
                                    'a' => "Pada daftar pesanan yang sedang 'Diproses', masuk ke halaman 'Perbarui Status Pesanan'. Pilih status interaktif 'Dimasak' saat makanan mulai disiapkan, dan pilih 'Siap' ketika pesanan sudah bisa diambil atau diantar kepada pelanggan."
                                ],
                                [
                                    'q' => 'Bagaimana cara mengubah status menu menjadi \'Habis\' jika bahan baku kosong?',
                                    'a' => "Buka halaman 'Kelola Menu', cari item yang dimaksud, lalu tekan toggle switch 'Ketersediaan' untuk mengubah status menu secara instan dari 'TERSEDIA' menjadi 'HABIS'."
                                ],
                                [
                                    'q' => 'Bisakah saya mengunduh laporan riwayat penjualan kantin?',
                                    'a' => "Bisa. Buka halaman 'Daftar Transaksi', lalu tekan ikon unduh di pojok kanan atas. Anda dapat memilih format dokumen (PDF Dokumen atau CSV Excel), menentukan rentang tanggal laporan, lalu menekan 'Unduh Sekarang' untuk menyimpannya ke perangkat."
                                ]
                            ];
                        @endphp

                        @foreach($faqs as $index => $faq)
                            <div class="faq-item group bg-white rounded-[28px] border border-gray-100 shadow-sm p-6 cursor-pointer transition-all duration-300"
                                onclick="toggleFaq(this)">
                                <div class="flex items-center justify-between gap-4 text-start">
                                    <h4 class="text-[15px] font-bold text-gray-800 leading-tight text-start">{{ $faq['q'] }}
                                    </h4>
                                    <i
                                        class="fa-solid fa-chevron-down text-gray-300 text-sm transition-transform duration-300 chevron-icon text-start"></i>
                                </div>
                                <div class="faq-answer text-start">
                                    <p
                                        class="text-sm text-gray-500 font-medium leading-relaxed text-start border-t border-gray-50 pt-4">
                                        {{ $faq['a'] }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Contact Account Manager Card (Fixed Layout & Dynamic WA Link) --}}
                    <div
                        class="w-full bg-[#FF6900] rounded-[32px] p-8 shadow-lg text-center text-white space-y-4 my-10 transition-all hover:shadow-xl">
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto text-3xl">
                            <i class="fa-solid fa-user-tie"></i>
                        </div>

                        <div>
                            <h4 class="text-lg font-black tracking-wide">Manajer Akun Kamu</h4>
                            <p class="text-xs font-bold text-orange-100 tracking-widest uppercase mt-0.5">Mrs. Jeon</p>
                        </div>

                        <p class="text-sm text-orange-50/90 font-medium px-4 leading-relaxed max-w-xl mx-auto">
                            Butuh bantuan bisnis atau ada kendala operasional mendesak?
                        </p>

                        <div class="pt-2 max-w-xs mx-auto">
                            <a href="https://wa.me/6281262729503?text=Halo%20Manajer%20Akun%20Kant.in%2C%20saya%20membutuhkan%20bantuan%20terkait%20kendala%20operasional."
                                target="_blank" id="btn_hubungi"
                                class="w-full flex items-center justify-center gap-3 bg-white text-[#FF6900] hover:bg-orange-50 font-black text-[15px] py-4 px-6 rounded-2xl shadow-md transition-all duration-300">
                                <i class="fa-solid fa-phone text-sm"></i>
                                Hubungi
                            </a>
                        </div>
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