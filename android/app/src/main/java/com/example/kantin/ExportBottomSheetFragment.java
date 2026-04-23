package com.example.kantin;

import android.graphics.Color;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.EditText;
import android.widget.ImageButton;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;

import com.example.kantin.utils.ReportDownloadHelper;
import com.example.kantin.utils.SessionManager;
import com.google.android.material.bottomsheet.BottomSheetDialogFragment;
import com.google.android.material.button.MaterialButton;
import com.google.android.material.card.MaterialCardView;
import com.google.android.material.datepicker.MaterialDatePicker;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Locale;

public class ExportBottomSheetFragment extends BottomSheetDialogFragment {

    // Deklarasi Komponen View
    private MaterialCardView cardPdf, cardExcel;
    private TextView tvPdfText, tvExcelText;
    private ImageView icPdf, icExcel;
    private EditText etTanggalMulai, etTanggalSelesai;
    private MaterialButton btnDownload;
    private ImageButton btnClose;

    // Variabel penyimpan format (default pdf)
    private String selectedFormat = "pdf";

    // Konstanta Warna (Format HEX)
    private final String COLOR_ORANGE = "#FF6D00";
    private final String COLOR_GRAY = "#757575";
    private final String COLOR_GRAY_STROKE = "#E0E0E0";
    private final String BG_ORANGE_LIGHT = "#FFF7ED";
    private final String BG_WHITE = "#FFFFFF";

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.bottom_sheet_export, container, false);

        // 1. Inisialisasi ID
        cardPdf = view.findViewById(R.id.cardPdf);
        // Sesuaikan ID dengan XML kamu (jika di XML namanya cardCsv, biarkan seperti ini)
        cardExcel = view.findViewById(R.id.cardCsv);
        tvPdfText = view.findViewById(R.id.tvPdfText);
        tvExcelText = view.findViewById(R.id.tvCsvText);
        icPdf = view.findViewById(R.id.icPdf);
        icExcel = view.findViewById(R.id.icCsv);
        etTanggalMulai = view.findViewById(R.id.etTanggalMulai);
        etTanggalSelesai = view.findViewById(R.id.etTanggalSelesai);
        btnDownload = view.findViewById(R.id.btnDownload);
        btnClose = view.findViewById(R.id.btnClose);

        // 2. Pasang Listener
        setupListeners();

        // 3. Set Default UI ke PDF
        updateUI("pdf");

        return view;
    }

    private void setupListeners() {
        // Tombol Close
        btnClose.setOnClickListener(v -> dismiss());

        // Pilihan Format
        cardPdf.setOnClickListener(v -> updateUI("pdf"));
        cardExcel.setOnClickListener(v -> updateUI("xlsx"));

        // Memilih Tanggal
        etTanggalMulai.setOnClickListener(v -> showDatePicker(etTanggalMulai, "Pilih Tanggal Mulai"));
        etTanggalSelesai.setOnClickListener(v -> showDatePicker(etTanggalSelesai, "Pilih Tanggal Selesai"));

        // Tombol Download
        btnDownload.setOnClickListener(v -> {
            String startDate = etTanggalMulai.getText().toString();
            String endDate = etTanggalSelesai.getText().toString();

            if (startDate.isEmpty() || endDate.isEmpty()) {
                Toast.makeText(getContext(), "Pilih rentang tanggal terlebih dahulu", Toast.LENGTH_SHORT).show();
                return;
            }

            // Panggil fungsi eksekusi
            executeDownload(startDate, endDate);
            dismiss(); // Tutup bottom sheet
        });
    }

    private void updateUI(String format) {
        selectedFormat = format;

        if (format.equals("pdf")) {
            // Aktifkan PDF
            cardPdf.setStrokeColor(Color.parseColor(COLOR_ORANGE));
            cardPdf.setStrokeWidth(5);
            cardPdf.setCardBackgroundColor(Color.parseColor(BG_ORANGE_LIGHT));
            tvPdfText.setTextColor(Color.parseColor(COLOR_ORANGE));
            if(icPdf != null) icPdf.setColorFilter(Color.parseColor(COLOR_ORANGE));

            // Matikan Excel
            cardExcel.setStrokeColor(Color.parseColor(COLOR_GRAY_STROKE));
            cardExcel.setStrokeWidth(2);
            cardExcel.setCardBackgroundColor(Color.parseColor(BG_WHITE));
            tvExcelText.setTextColor(Color.parseColor(COLOR_GRAY));
            if(icExcel != null) icExcel.setColorFilter(Color.parseColor(COLOR_GRAY));

            btnDownload.setText("Unduh PDF");
        } else {
            // Aktifkan Excel
            cardExcel.setStrokeColor(Color.parseColor(COLOR_ORANGE));
            cardExcel.setStrokeWidth(5);
            cardExcel.setCardBackgroundColor(Color.parseColor(BG_ORANGE_LIGHT));
            tvExcelText.setTextColor(Color.parseColor(COLOR_ORANGE));
            if(icExcel != null) icExcel.setColorFilter(Color.parseColor(COLOR_ORANGE));

            // Matikan PDF
            cardPdf.setStrokeColor(Color.parseColor(COLOR_GRAY_STROKE));
            cardPdf.setStrokeWidth(2);
            cardPdf.setCardBackgroundColor(Color.parseColor(BG_WHITE));
            tvPdfText.setTextColor(Color.parseColor(COLOR_GRAY));
            if(icPdf != null) icPdf.setColorFilter(Color.parseColor(COLOR_GRAY));

            btnDownload.setText("Unduh Excel");
        }
    }

    private void showDatePicker(EditText targetEditText, String title) {
        MaterialDatePicker<Long> datePicker = MaterialDatePicker.Builder.datePicker()
                .setTitleText(title)
                .setSelection(MaterialDatePicker.todayInUtcMilliseconds())
                .build();

        datePicker.addOnPositiveButtonClickListener(selection -> {
            // PENTING: Format wajib yyyy-MM-dd sesuai request Laravel
            SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd", Locale.getDefault());
            String dateString = sdf.format(new Date(selection));
            targetEditText.setText(dateString);
        });

        datePicker.show(getParentFragmentManager(), "DATE_PICKER");
    }

    private void executeDownload(String startDate, String endDate) {
        SessionManager sessionManager = new SessionManager(requireContext());
        String token = sessionManager.getToken();
        String canteenId = sessionManager.getCanteenId();

        if (token == null || canteenId == null || token.isEmpty()) {
            Toast.makeText(getContext(), "Sesi tidak valid, silakan login ulang.", Toast.LENGTH_SHORT).show();
            return;
        }

        // Panggil Helper class untuk memisahkan logika jaringan dari UI
        ReportDownloadHelper.downloadLaporan(
                requireContext(),
                selectedFormat,
                startDate,
                endDate,
                canteenId,
                token
        );
    }
}