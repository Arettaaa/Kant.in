package com.example.kantin; // Sesuaikan jika kamu menyimpannya di folder 'fragments'

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageButton;
import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import com.google.android.material.bottomsheet.BottomSheetDialogFragment;
import com.google.android.material.button.MaterialButton;
import com.google.android.material.chip.ChipGroup;

public class FilterTransaksiBottomSheet extends BottomSheetDialogFragment {

    private ChipGroup chipGroupStatus;
    private MaterialButton btnReset, btnApply;
    private ImageButton btnClose;
    private OnFilterListener listener;

    // Default status saat bottom sheet pertama kali dibuka
    private String statusTerpilih = "Semua";

    // Interface untuk mengirim data kembali ke Activity/Fragment pemanggil
    public interface OnFilterListener {
        void onFilterSelected(String status);
    }

    public FilterTransaksiBottomSheet(OnFilterListener listener) {
        this.listener = listener;
    }

    // Ini penting agar background pojok melengkung (rounded) bisa terlihat.
    // Pastikan kamu sudah membuat style CustomBottomSheetDialog di themes.xml
    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setStyle(STYLE_NORMAL, R.style.CustomBottomSheetDialog);
    }

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        // Menghubungkan Java dengan layout XML yang baru
        View v = inflater.inflate(R.layout.bottom_sheet_filter_transaksi, container, false);

        // Inisialisasi View
        chipGroupStatus = v.findViewById(R.id.cg_status_filter);
        btnReset = v.findViewById(R.id.btn_reset_filter);
        btnApply = v.findViewById(R.id.btn_apply_filter);
        btnClose = v.findViewById(R.id.btn_close_filter);

        // Aksi Tutup (X)
        btnClose.setOnClickListener(view -> dismiss());

        // Logika saat Chip dipilih (Perhatikan: 'Menunggu' sudah dihapus)
        chipGroupStatus.setOnCheckedStateChangeListener((group, checkedIds) -> {
            if (!checkedIds.isEmpty()) {
                int id = checkedIds.get(0);
                if (id == R.id.chip_semua) {
                    statusTerpilih = "Semua";
                } else if (id == R.id.chip_selesai) {
                    statusTerpilih = "Selesai";
                } else if (id == R.id.chip_dibatalkan) {
                    statusTerpilih = "Dibatalkan";
                }
            }
        });

        // Aksi tombol "Atur Ulang" (Reset ke 'Semua')
        btnReset.setOnClickListener(view -> {
            chipGroupStatus.check(R.id.chip_semua);
            statusTerpilih = "Semua";
        });

        // Aksi tombol "Terapkan"
        btnApply.setOnClickListener(view -> {
            if (listener != null) {
                // Kirim status ke halaman Daftar Transaksi
                listener.onFilterSelected(statusTerpilih);
            }
            dismiss(); // Tutup jendela setelah diterapkan
        });

        return v;
    }
}