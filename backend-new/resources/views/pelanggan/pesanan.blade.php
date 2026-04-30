@extends('layouts.app')

@section('title', 'Pesanan Saya - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .tab-underline {
        position: relative;
        transition: color 0.2s ease;
    }

    .tab-underline.active {
        color: #FF6900;
        font-weight: 800;
    }

    .tab-underline.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        height: 2px;
        background-color: #FF6900;
        border-radius: 2px;
    }

    .tab-underline:not(.active) {
        color: #9ca3af;
        font-weight: 600;
    }

    /* ---- Step tracker ---- */
    .step-track {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
    }

    .step-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        flex: 0 0 auto;
    }

    .step-circle {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        border: 2px solid #e5e7eb;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #d1d5db;
        font-size: 13px;
        flex-shrink: 0;
        transition: all 0.2s;
    }

    .step-circle.done {
        border-color: #FF6900;
        color: #FF6900;
    }

    .step-circle.active {
        border-color: #FF6900;
        background-color: #FF6900;
        color: white;
    }

    .step-label {
        font-size: 11px;
        font-weight: 600;
        color: #9ca3af;
        white-space: nowrap;
    }

    .step-label.active {
        font-weight: 800;
        color: #FF6900;
    }

    .step-label.done {
        font-weight: 600;
        color: #9ca3af;
    }

    .step-connector {
        flex: 1;
        height: 2px;
        background-color: #e5e7eb;
        margin: 0 3px;
        position: relative;
        top: 17px;
        /* center with circle */
        flex-shrink: 1;
    }

    .step-connector.done {
        background-color: #FF6900;
    }

    .order-card {
        transition: all 0.2s ease;
    }

    .order-card:hover {
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.07);
    }

    .riwayat-badge-selesai {
        background-color: #F0FDF4;
        color: #16a34a;
    }

    .riwayat-badge-batal {
        background-color: #FEF2F2;
        color: #dc2626;
    }

    .riwayat-card {
        transition: all 0.2s ease;
    }

    .riwayat-card:hover {
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.07);
    }

    .action-btn {
        transition: all 0.15s ease;
    }

    .action-btn:hover {
        filter: brightness(0.95);
        transform: translateY(-1px);
    }

    .star-rating .star {
        cursor: pointer;
        transition: transform 0.15s ease;
        color: #d1d5db;
        font-size: 2.5rem;
    }

    .star-rating .star:hover,
    .star-rating .star.active {
        color: #f59e0b;
        transform: scale(1.15);
    }

    @keyframes modalIn {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(10px);
        }

        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    .modal-card {
        animation: modalIn 0.22s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(40px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideOut {
        from {
            opacity: 1;
            transform: translateX(0);
        }

        to {
            opacity: 0;
            transform: translateX(40px);
        }
    }
</style>
@endpush

@section('content')

<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden">

    @include ('pelanggan.partials.sidebar', ['currentPath' => 'pesanan'])

    {{-- MAIN --}}
    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-[#F9FAFB]">
        <div class="px-10 py-8 flex flex-col gap-6">

            <h1 class="text-2xl font-extrabold text-gray-900">Pesanan Saya</h1>

            {{-- TABS --}}
            <div class="border-b border-gray-200">
                <div class="flex gap-8">
                    <button id="tabAktifBtn" onclick="switchTab('aktif')"
                        class="tab-underline active pb-3 text-sm tracking-wide">Aktif</button>
                    <button id="tabRiwayatBtn" onclick="switchTab('riwayat')"
                        class="tab-underline pb-3 text-sm tracking-wide">Riwayat</button>
                </div>
            </div>

            {{-- ===== TAB AKTIF ===== --}}
            <div id="tabAktif" class="grid grid-cols-2 gap-4 pb-8">
                @php
                $aktifStatuses = ['pending', 'processing', 'ready'];
                $ordersAktif = array_filter($orders, fn($o) => in_array($o['status'] ?? '', $aktifStatuses));
                @endphp

                @forelse($ordersAktif as $order)
                @php
                $status = $order['status'] ?? 'pending';

                $steps = [
                'pending' => 0,
                'processing' => 1,
                'ready' => 2,
                ];

                $activeStep = $steps[$status] ?? 0;

                $itemNames = collect($order['items'] ?? [])
                ->map(fn($i) => $i['quantity'].'x '.$i['name'])
                ->implode(', ');

                $createdAt = $order['created_at_formatted'] ?? '-';
                @endphp
                <div class="order-card bg-white rounded-3xl p-5 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-xl flex items-center justify-center"
                                style="background-color:#FFF3E8;">
                                <i class="fa-solid fa-receipt text-xs" style="color:#FF6900;"></i>
                            </div>
                            <span class="text-sm font-extrabold text-gray-800">{{ $order['order_code'] ?? '-' }}</span>
                        </div>
                        <span class="text-sm font-extrabold" style="color:#FF6900;">
                            Rp {{ number_format($order['total_amount'] ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-400 font-medium mb-4">{{ $createdAt }}</p>

                    <div class="bg-gray-50 rounded-2xl px-4 py-3 mb-5 flex items-start gap-2">
                        <i class="fa-solid fa-store text-gray-300 text-sm mt-0.5 flex-shrink-0"></i>
                        <div>
                            <p class="text-sm font-bold text-gray-700">{{ $order['canteen_name'] ?? '-' }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $itemNames }}</p>
                        </div>
                    </div>

                    {{-- STEP TRACKER --}}
                    <div class="step-track">

                        {{-- Menunggu --}}
                        <div class="step-item">
                            <div
                                class="step-circle {{ $activeStep >= 0 ? ($activeStep > 0 ? 'done' : 'active') : '' }}">
                                <i class="fa-regular fa-clock text-xs"></i>
                            </div>
                            <span class="step-label {{ $activeStep === 0 ? 'active' : 'done' }}">Menunggu</span>
                        </div>
                        <div class="step-connector {{ $activeStep > 0 ? 'done' : '' }}"></div>

                        {{-- Dimasak --}}
                        <div class="step-item">
                            <div
                                class="step-circle {{ $activeStep >= 1 ? ($activeStep > 1 ? 'done' : 'active') : '' }}">
                                <i class="fa-solid fa-fire text-xs"></i>
                            </div>
                            <span
                                class="step-label {{ $activeStep === 1 ? 'active' : ($activeStep > 1 ? 'done' : '') }}">Dimasak</span>
                        </div>
                        <div class="step-connector {{ $activeStep > 1 ? 'done' : '' }}"></div>

                        {{-- Siap --}}
                        <div class="step-item">
                            <div class="step-circle {{ $activeStep >= 2 ? 'active' : '' }}">
                                <i class="fa-solid fa-box-open text-xs"></i>
                            </div>
                            <span class="step-label {{ $activeStep === 2 ? 'active' : '' }}">Siap</span>
                        </div>

                    </div>

                    @if($status === 'ready')
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <button onclick="konfirmasiPesanan('{{ $order['_id'] ?? $order['id'] ?? '' }}')"
                            data-order-btn="{{ $order['_id'] ?? '' }}"
                            class="action-btn w-full py-3 rounded-2xl text-sm font-bold text-white flex items-center justify-center gap-2"
                            style="background:linear-gradient(135deg,#FF6900,#ea580c);">
                            <i class="fa-solid fa-check text-sm"></i>
                            Pesanan Diterima
                        </button>
                    </div>
                    @endif
                </div>
                @empty
                <div class="col-span-2 flex flex-col items-center justify-center py-20 gap-3">
                    <div class="w-16 h-16 rounded-full bg-orange-50 flex items-center justify-center">
                        <i class="fa-solid fa-receipt text-2xl" style="color:#FF6900;"></i>
                    </div>
                    <p class="text-base font-extrabold text-gray-700">Tidak ada pesanan aktif</p>
                    <p class="text-sm text-gray-400 font-medium">Yuk pesan makanan sekarang!</p>
                    <a href="/jelajah" class="mt-2 px-6 py-2.5 rounded-2xl text-sm font-bold text-white"
                        style="background-color:#FF6900;">
                        Cari Makanan
                    </a>
                </div>
                @endforelse
            </div>

            {{-- ===== TAB RIWAYAT ===== --}}
            <div id="tabRiwayat" class="hidden flex flex-col gap-4 pb-8">
                @php
                $riwayatStatuses = ['completed', 'cancelled'];
                $ordersRiwayat = array_filter($orders, fn($o) => in_array($o['status'] ?? '', $riwayatStatuses));
                @endphp
                <div class="grid grid-cols-2 gap-4">
                    @forelse($ordersRiwayat as $order)
                    @php
                    $status = $order['status'] ?? '';
                    $selesai = $status === 'completed';
                    $itemNames = collect($order['items'] ?? [])->map(fn($i) => $i['quantity'].'x
                    '.$i['name'])->implode(', ');
                    $createdAt = $order['created_at_formatted'] ?? '-'; $orderId = $order['_id'] ?? '';
                    $canteenId = $order['canteen_id'] ?? '';
                    $items = $order['items'] ?? [];
                    $itemCount = count($items);
                    $firstMenuId = $items[0]['menu_id'] ?? null;
                    @endphp
                    <div
                        class="riwayat-card bg-white rounded-3xl p-5 shadow-sm border border-gray-100 flex flex-col gap-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs text-gray-400 font-medium mb-2">{{ $createdAt }}</p>
                                <div class="flex items-center gap-2 mb-1">
                                    <i class="fa-solid fa-store text-gray-300 text-xs"></i>
                                    <p class="text-sm font-extrabold text-gray-900">{{ $order['canteen_name'] ?? '-' }}
                                    </p>
                                </div>
                                <p class="text-xs text-gray-400 ml-5">{{ $itemNames }}</p>
                            </div>
                            @if($selesai)
                            <span
                                class="inline-flex items-center gap-1 text-[11px] font-black px-2.5 py-1 rounded-xl riwayat-badge-selesai flex-shrink-0">
                                <i class="fa-solid fa-circle-check text-[10px]"></i> Selesai
                            </span>
                            @else
                            <span
                                class="inline-flex items-center gap-1 text-[11px] font-black px-2.5 py-1 rounded-xl riwayat-badge-batal flex-shrink-0">
                                <i class="fa-solid fa-circle-xmark text-[10px]"></i> Dibatalkan
                            </span>
                            @endif
                        </div>

                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                            <div>
                                <p class="text-[11px] text-gray-400 font-medium">Total Belanja</p>
                                <p class="text-base font-extrabold text-gray-900">
                                    Rp {{ number_format($order['total_amount'] ?? 0, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                @if($selesai)
                                @if($order['has_rated'] ?? false)
                                {{-- Sudah dinilai — tampilkan bintang --}}
                                <div class="flex items-center gap-0.5">
                                    @for($i = 1; $i <= 5; $i++) <i
                                        class="fa-solid fa-star text-sm {{ $i <= ($order['rating_value'] ?? 0) ? 'text-amber-400' : 'text-gray-200' }}">
                                        </i>
                                        @endfor
                                </div>
                                @else
                                {{-- Belum dinilai — tampilkan tombol nilai --}}
                                <button onclick="openRating('{{ $order['_id'] ?? $order['id'] ?? '' }}')"
                                    data-order-id="{{ $order['_id'] ?? $order['id'] ?? '' }}"
                                    class="action-btn flex items-center gap-1.5 px-3 py-2 rounded-2xl text-xs font-bold border border-amber-200 text-amber-500 bg-amber-50">
                                    <i class="fa-regular fa-star text-xs"></i> Nilai
                                </button>
                                @endif
                                @endif
                                <a href="{{ $itemCount === 1 
                                    ? '/menu/' . $firstMenuId 
                                    : '/kantin/' . $canteenId }}"
                                    class="action-btn flex items-center gap-1.5 px-3 py-2 rounded-2xl text-xs font-bold border border-orange-200 bg-orange-50"
                                    style="color:#FF6900;">
                                    <i class="fa-solid fa-rotate-right text-xs"></i> Pesan Lagi
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-2 flex flex-col items-center justify-center py-20 gap-3">
                        <div class="w-16 h-16 rounded-full bg-orange-50 flex items-center justify-center">
                            <i class="fa-solid fa-clock-rotate-left text-2xl" style="color:#FF6900;"></i>
                        </div>
                        <p class="text-base font-extrabold text-gray-700">Belum ada riwayat pesanan</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </main>
</div>

{{-- MODAL RATING --}}
<div id="ratingModal" class="fixed inset-0 z-50 hidden items-center justify-center"
    style="background:rgba(0,0,0,0.4); backdrop-filter:blur(5px);">
    <div class="modal-card bg-white rounded-3xl shadow-2xl w-[420px] mx-4 overflow-hidden">
        <div class="px-8 pt-8 pb-7 flex flex-col items-center gap-5">
            <div class="text-center">
                <h2 class="text-xl font-extrabold text-gray-900 mb-2">Beri Nilai Pesanan</h2>
                <p class="text-sm text-gray-400 font-medium leading-relaxed">
                    Bagaimana makanan Anda? Penilaian Anda<br>membantu orang lain menemukan makanan terbaik!
                </p>
            </div>
            <div class="star-rating flex items-center gap-2 py-2" id="starContainer">
                <i class="fa-solid fa-star star" data-val="1" onclick="setRating(1)"></i>
                <i class="fa-solid fa-star star" data-val="2" onclick="setRating(2)"></i>
                <i class="fa-solid fa-star star" data-val="3" onclick="setRating(3)"></i>
                <i class="fa-solid fa-star star" data-val="4" onclick="setRating(4)"></i>
                <i class="fa-solid fa-star star" data-val="5" onclick="setRating(5)"></i>
            </div>
            <div class="flex gap-3 w-full mt-1">
                <button onclick="closeRating()"
                    class="flex-1 py-3.5 rounded-2xl border-2 border-gray-200 text-sm font-bold text-gray-600 hover:bg-gray-50 transition-all">Batal</button>
                <button onclick="submitRating()"
                    class="flex-1 py-3.5 rounded-2xl text-white text-sm font-extrabold shadow-md hover:brightness-110 transition-all"
                    style="background:linear-gradient(135deg,#FF6900,#ea580c);">Kirim Penilaian</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL ALERT SUCCESS --}}
<div id="successModal" class="fixed inset-0 z-50 hidden items-center justify-center"
    style="background:rgba(0,0,0,0.4); backdrop-filter:blur(4px);">

    <div class="bg-white rounded-3xl shadow-2xl w-[340px] mx-4 overflow-hidden" style="animation: modalIn 0.2s ease;">

        <div class="flex flex-col items-center pt-8 pb-6 px-6 text-center">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-4" style="background-color:#F0FDF4;">
                <i class="fa-solid fa-circle-check text-xl text-green-500"></i>
            </div>

            <h3 class="text-base font-extrabold text-gray-900 mb-1">
                Berhasil!
            </h3>

            <p class="text-sm text-gray-400 font-medium">
                Penilaian kamu berhasil dikirimkan. Terima kasih atas feedback-nya!
            </p>
        </div>

        <div class="px-6 pb-6">
            <button onclick="closeSuccessModal()"
                class="w-full py-3 rounded-2xl text-sm font-bold text-white transition-all hover:brightness-110"
                style="background-color:#FF6900;">
                Oke
            </button>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
    function switchTab(tab) {
    const aktif   = document.getElementById('tabAktif');
    const riwayat = document.getElementById('tabRiwayat');
    const btnA    = document.getElementById('tabAktifBtn');
    const btnR    = document.getElementById('tabRiwayatBtn');
    if (tab === 'aktif') {
        aktif.classList.remove('hidden'); riwayat.classList.add('hidden');
        btnA.classList.add('active');     btnR.classList.remove('active');
    } else {
        riwayat.classList.remove('hidden'); aktif.classList.add('hidden');
        btnR.classList.add('active');        btnA.classList.remove('active');
    }
}

let currentRating = 0;
let currentOrderId = null;

function openRating(orderId) {
    currentRating = 0;
    currentOrderId = orderId;
    document.querySelectorAll('.star-rating .star').forEach(s => {
        s.style.color = '#d1d5db';
        s.style.transform = 'scale(1)';
    });
    document.getElementById('ratingModal').classList.remove('hidden');
    document.getElementById('ratingModal').classList.add('flex');
}

function closeRating() {
    document.getElementById('ratingModal').classList.add('hidden');
    document.getElementById('ratingModal').classList.remove('flex');
}

function setRating(val) {
    currentRating = val;
    document.querySelectorAll('.star-rating .star').forEach((s, i) => {
        s.style.color = i < val ? '#f59e0b' : '#d1d5db';
        s.style.transform = i < val ? 'scale(1.15)' : 'scale(1)';
    });
}

function konfirmasiPesanan(orderId) {
    if (!confirm('Konfirmasi bahwa pesanan sudah kamu terima?')) return;

    fetch(`/pesanan/${orderId}/complete`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
    })
    .then(r => r.json())
    .then(data => {
        if (data.success !== false) {
            // Reload halaman supaya card pindah ke tab Riwayat
            window.location.reload();
        } else {
            alert(data.message ?? 'Gagal mengkonfirmasi pesanan.');
        }
    })
    .catch(() => alert('Terjadi kesalahan.'));
}

// Hover effect
document.querySelectorAll('.star-rating .star').forEach((star, idx) => {
    star.addEventListener('mouseenter', () => {
        document.querySelectorAll('.star-rating .star').forEach((s, i) => {
            s.style.color = i <= idx ? '#fbbf24' : '#d1d5db';
        });
    });
    star.addEventListener('mouseleave', () => {
        document.querySelectorAll('.star-rating .star').forEach((s, i) => {
            s.style.color = i < currentRating ? '#f59e0b' : '#d1d5db';
        });
    });
});

function openSuccessModal() {
    const modal = document.getElementById('successModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeSuccessModal() {
    const modal = document.getElementById('successModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function submitRating() {
    if (currentRating === 0) { alert('Pilih bintang terlebih dahulu!'); return; }

    fetch(`/rating/${currentOrderId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ rating: currentRating }),
    })
    .then(r => r.json())
   .then(data => {
    closeRating();

    if (data.success !== false) {
        const btn = document.querySelector(`[data-order-id="${currentOrderId}"]`);
        if (btn) {
            btn.outerHTML = `<div class="flex items-center gap-0.5">
                ${[1,2,3,4,5].map(i => `<i class="fa-solid fa-star ${i <= currentRating ? 'text-amber-400' : 'text-gray-200'} text-sm"></i>`).join('')}
            </div>`;
        }

        // ✅ tampilkan modal sukses
        openSuccessModal();

    } else {
        alert(data.message ?? 'Gagal mengirim rating.');
    }
})
.catch(() => {
    alert('Terjadi kesalahan.');
});
}

document.getElementById('ratingModal').addEventListener('click', function(e) {
    if (e.target === this) closeRating();
});

function showToast(message, type = 'success') {
    const existing = document.getElementById('toastNotif');
    if (existing) existing.remove();

    const colors = type === 'success'
        ? 'background: linear-gradient(135deg, #22c55e, #16a34a);'
        : 'background: linear-gradient(135deg, #ef4444, #dc2626);';

    const icon = type === 'success' ? 'fa-check' : 'fa-xmark';

    const toast = document.createElement('div');
    toast.id = 'toastNotif';
    toast.style.cssText = `
        position: fixed; top: 24px; right: 24px; z-index: 9999;
        display: flex; align-items: center; gap: 12px;
        padding: 14px 20px; border-radius: 16px; color: white;
        font-size: 14px; font-weight: 700; box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        animation: slideIn 0.3s ease; ${colors}
    `;
    toast.innerHTML = `<i class="fa-solid ${icon}"></i><span>${message}</span>`;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease forwards';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

function konfirmasiPesanan(orderId) {
    fetch(`/pesanan/${orderId}/complete`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
    })
    .then(r => r.json())
    .then(data => {
        if (data.success !== false) {
            showToast('Pesanan berhasil dikonfirmasi!', 'success');
            setTimeout(() => window.location.reload(), 1500);
        } else {
            showToast(data.message ?? 'Gagal mengkonfirmasi pesanan.', 'error');
        }
    })
    .catch(() => showToast('Terjadi kesalahan.', 'error'));
}
</script>
@endpush