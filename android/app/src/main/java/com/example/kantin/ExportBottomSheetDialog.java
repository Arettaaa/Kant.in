package com.example.kantin;

import android.graphics.Color;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.EditText;
import android.widget.ImageButton;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;

import com.example.kantin.R;
import com.google.android.material.bottomsheet.BottomSheetDialogFragment;
import com.google.android.material.button.MaterialButton;
import com.google.android.material.card.MaterialCardView;
import com.google.android.material.datepicker.MaterialDatePicker;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Locale;

public class ExportBottomSheetDialog extends BottomSheetDialogFragment {

    // Deklarasi Komponen View
    private MaterialCardView cardPdf, cardCsv;
    private TextView tvPdfText, tvCsvText;
    private EditText etTanggalMulai, etTanggalSelesai;
    private MaterialButton btnDownload;
    private ImageButton btnClose;

    // Variabel untuk menyimpan format yang sedang dipilih
    private String selectedFormat = "";

    // Konfigurasi Warna
    private final String COLOR_SELECTED = "#FF6D00"; // Oranye untuk garis dan teks aktif
    private final String COLOR_UNSELECTED = "#E0E0E0"; // Abu-abu untuk garis tidak aktif
    private final String COLOR_TEXT_UNSELECTED = "#757575"; // Abu-abu untuk teks tidak aktif

    private final String BG_SELECTED = "#FFF7ED"; // Latar belakang oranye sangat muda
    private final String BG_UNSELECTED = "#FFFFFF"; // Latar belakang putih standar

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        // Hubungkan dengan file XML bottom_sheet_export.xml
        View view = inflater.inflate(R.layout.bottom_sheet_export, container, false);

        // 1. Inisialisasi semua ID
        cardPdf = view.findViewById(R.id.cardPdf);
        cardCsv = view.findViewById(R.id.cardCsv);
        tvPdfText = view.findViewById(R.id.tvPdfText);
        tvCsvText = view.findViewById(R.id.tvCsvText);
        etTanggalMulai = view.findViewById(R.id.etTanggalMulai);
        etTanggalSelesai = view.findViewById(R.id.etTanggalSelesai);
        btnDownload = view.findViewById(R.id.btnDownload);
        btnClose = view.findViewById(R.id.btnClose);

        // 2. Aktifkan semua aksi klik
        setupListeners();

        // 3. Atur PDF sebagai pilihan default saat jendela pertama kali muncul
        selectFormat("PDF");

        return view;
    }

    private void setupListeners() {
        // Tutup pop-up saat tombol 'X' ditekan
        btnClose.setOnClickListener(v -> dismiss());

        // Aksi saat kartu format ditekan
        cardPdf.setOnClickListener(v -> selectFormat("PDF"));
        cardCsv.setOnClickListener(v -> selectFormat("CSV"));

        // Aksi saat kolom tanggal ditekan (Memunculkan kalender)
        etTanggalMulai.setOnClickListener(v -> showDatePicker(etTanggalMulai, "Pilih Tanggal Mulai"));
        etTanggalSelesai.setOnClickListener(v -> showDatePicker(etTanggalSelesai, "Pilih Tanggal Selesai"));

        // Aksi eksekusi akhir saat tombol utama "Unduh" ditekan
        btnDownload.setOnClickListener(v -> {
            String startDate = etTanggalMulai.getText().toString();
            String endDate = etTanggalSelesai.getText().toString();

            // Validasi: Pastikan tanggal sudah diisi
            if (startDate.isEmpty() || endDate.isEmpty()) {
                Toast.makeText(getContext(), "Pilih rentang tanggal terlebih dahulu", Toast.LENGTH_SHORT).show();
                return;
            }

            // TODO: Tambahkan logika ekspor/download file (API call atau query database) di sini

            Toast.makeText(getContext(), "Memproses unduhan " + selectedFormat + "...", Toast.LENGTH_SHORT).show();
            dismiss(); // Tutup jendela setelah tombol ditekan
        });
    }

    // Fungsi untuk mengatur visualisasi format yang dipilih
    private void selectFormat(String format) {
        selectedFormat = format;

        if (format.equals("PDF")) {
            // --- NYALAKAN PDF ---
            cardPdf.setStrokeColor(Color.parseColor(COLOR_SELECTED));
            cardPdf.setStrokeWidth(4);
            cardPdf.setCardBackgroundColor(Color.parseColor(BG_SELECTED));
            tvPdfText.setTextColor(Color.parseColor(COLOR_SELECTED));

            // --- MATIKAN CSV ---
            cardCsv.setStrokeColor(Color.parseColor(COLOR_UNSELECTED));
            cardCsv.setStrokeWidth(2);
            cardCsv.setCardBackgroundColor(Color.parseColor(BG_UNSELECTED));
            tvCsvText.setTextColor(Color.parseColor(COLOR_TEXT_UNSELECTED));

            // Ubah teks tombol utama
            btnDownload.setText("Unduh PDF");

        } else if (format.equals("CSV")) {
            // --- NYALAKAN CSV ---
            cardCsv.setStrokeColor(Color.parseColor(COLOR_SELECTED));
            cardCsv.setStrokeWidth(4);
            cardCsv.setCardBackgroundColor(Color.parseColor(BG_SELECTED));
            tvCsvText.setTextColor(Color.parseColor(COLOR_SELECTED));

            // --- MATIKAN PDF ---
            cardPdf.setStrokeColor(Color.parseColor(COLOR_UNSELECTED));
            cardPdf.setStrokeWidth(2);
            cardPdf.setCardBackgroundColor(Color.parseColor(BG_UNSELECTED));
            tvPdfText.setTextColor(Color.parseColor(COLOR_TEXT_UNSELECTED));

            // Ubah teks tombol utama
            btnDownload.setText("Unduh CSV");
        }
    }

    // Fungsi memunculkan Date Picker Material Design
    private void showDatePicker(EditText targetEditText, String title) {
        MaterialDatePicker<Long> datePicker = MaterialDatePicker.Builder.datePicker()
                .setTitleText(title)
                .setSelection(MaterialDatePicker.todayInUtcMilliseconds())
                .build();

        datePicker.addOnPositiveButtonClickListener(selection -> {
            // Format tanggal (contoh: 25 Okt 2023)
            SimpleDateFormat sdf = new SimpleDateFormat("dd MMM yyyy", new Locale("id", "ID"));
            String dateString = sdf.format(new Date(selection));
            targetEditText.setText(dateString);
        });

        datePicker.show(getParentFragmentManager(), "DATE_PICKER");
    }
}