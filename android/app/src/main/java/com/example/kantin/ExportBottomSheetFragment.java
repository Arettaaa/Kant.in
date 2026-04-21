package com.example.kantin; // Sesuaikan dengan package kamu

import android.app.DatePickerDialog;
import android.app.DownloadManager;
import android.content.Context;
import android.graphics.Color;
import android.net.Uri;
import android.os.Bundle;
import android.os.Environment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;

import com.example.kantin.network.ApiClient;
import com.example.kantin.utils.SessionManager;

import com.google.android.material.bottomsheet.BottomSheetDialogFragment;
import com.google.android.material.button.MaterialButton;
import com.google.android.material.card.MaterialCardView;

import java.util.Calendar;

public class ExportBottomSheetFragment extends BottomSheetDialogFragment {

    private String exportFormat = "pdf"; // Default format

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        View v = inflater.inflate(R.layout.bottom_sheet_export, container, false);

        MaterialCardView cardPdf = v.findViewById(R.id.cardPdf);
        MaterialCardView cardCsv = v.findViewById(R.id.cardCsv);
        EditText etMulai = v.findViewById(R.id.etTanggalMulai);
        EditText etSelesai = v.findViewById(R.id.etTanggalSelesai);
        MaterialButton btnDownload = v.findViewById(R.id.btnDownload);

        // --- Logika Ganti Warna & Format ---
        cardPdf.setOnClickListener(view -> {
            exportFormat = "pdf";
            updateUI(v, true);
        });

        cardCsv.setOnClickListener(view -> {
            exportFormat = "excel";
            updateUI(v, false);
        });

        // --- Logika Kalender ---
        etMulai.setOnClickListener(view -> showCalendar(etMulai));
        etSelesai.setOnClickListener(view -> showCalendar(etSelesai));

        // --- Logika Tombol Unduh ---
        btnDownload.setOnClickListener(view -> {
            String startDate = etMulai.getText().toString();
            String endDate = etSelesai.getText().toString();

            if (startDate.isEmpty() || endDate.isEmpty()) {
                Toast.makeText(getContext(), "Pilih rentang tanggal terlebih dahulu!", Toast.LENGTH_SHORT).show();
                return;
            }

            downloadReportFromApi(exportFormat, startDate, endDate);
            dismiss();
        });

        v.findViewById(R.id.btnClose).setOnClickListener(view -> dismiss());

        return v;
    }

    private void updateUI(View v, boolean isPdfSelected) {
        MaterialCardView cardPdf = v.findViewById(R.id.cardPdf);
        MaterialCardView cardCsv = v.findViewById(R.id.cardCsv);
        TextView tvPdf = v.findViewById(R.id.tvPdfText);
        TextView tvCsv = v.findViewById(R.id.tvCsvText);
        ImageView icPdf = v.findViewById(R.id.icPdf);
        ImageView icCsv = v.findViewById(R.id.icCsv);
        MaterialButton btnDownload = v.findViewById(R.id.btnDownload);

        int colorOrange = Color.parseColor("#FF6D00");
        int colorGray = Color.parseColor("#757575");
        int colorGrayStroke = Color.parseColor("#E0E0E0");
        int colorOrangeLight = Color.parseColor("#FFF7ED");
        int colorWhite = Color.parseColor("#FFFFFF");

        if (isPdfSelected) {
            cardPdf.setStrokeColor(colorOrange);
            cardPdf.setStrokeWidth(5);
            cardPdf.setCardBackgroundColor(colorOrangeLight);
            tvPdf.setTextColor(colorOrange);
            icPdf.setColorFilter(colorOrange);

            cardCsv.setStrokeColor(colorGrayStroke);
            cardCsv.setStrokeWidth(2);
            cardCsv.setCardBackgroundColor(colorWhite);
            tvCsv.setTextColor(colorGray);
            icCsv.setColorFilter(colorGray);

            btnDownload.setText("Unduh PDF");
        } else {
            cardCsv.setStrokeColor(colorOrange);
            cardCsv.setStrokeWidth(5);
            cardCsv.setCardBackgroundColor(colorOrangeLight);
            tvCsv.setTextColor(colorOrange);
            icCsv.setColorFilter(colorOrange);

            cardPdf.setStrokeColor(colorGrayStroke);
            cardPdf.setStrokeWidth(2);
            cardPdf.setCardBackgroundColor(colorWhite);
            tvPdf.setTextColor(colorGray);
            icPdf.setColorFilter(colorGray);

            btnDownload.setText("Unduh Excel");
        }
    }

    private void showCalendar(EditText et) {
        Calendar c = Calendar.getInstance();
        new DatePickerDialog(getContext(), (view, y, m, d) -> {
            String formattedDate = String.format("%04d-%02d-%02d", y, (m + 1), d);
            et.setText(formattedDate);
        }, c.get(Calendar.YEAR), c.get(Calendar.MONTH), c.get(Calendar.DAY_OF_MONTH)).show();
    }

    /**
     * Melakukan request download ke API Laravel terintegrasi dengan SessionManager
     */
    private void downloadReportFromApi(String format, String startDate, String endDate) {
        // 1. Panggil SessionManager untuk mengambil Token dan ID Kantin
        // Pastikan nama class dan method (getToken, getCanteenId) sesuai dengan yang ada di class SessionManager-mu
        SessionManager sessionManager = new SessionManager(requireContext());
        String token = sessionManager.getToken();
        String canteenId = sessionManager.getCanteenId(); // Atau sessionManager.getUser().getCanteenId() jika bentuknya object

        // Validasi keamanan kecil
        if (token == null || token.isEmpty() || canteenId == null || canteenId.isEmpty()) {
            Toast.makeText(getContext(), "Sesi tidak valid, silakan login ulang.", Toast.LENGTH_SHORT).show();
            return;
        }

        // 2. Siapkan URL API
        String url = ApiClient.BASE_URL + "canteens/" + canteenId + "/export" +
                "?format=" + format +
                "&start_date=" + startDate +
                "&end_date=" + endDate;

        // 3. Siapkan Request Download
        DownloadManager.Request request = new DownloadManager.Request(Uri.parse(url));

        // 4. Masukkan Token Bearer agar diizinkan Laravel
        request.addRequestHeader("Authorization", "Bearer " + token);

        // 5. Konfigurasi Tampilan Notifikasi
        String ext = format.equals("pdf") ? ".pdf" : ".xlsx";
        String fileName = "Laporan_Kantin_" + startDate + ext;

        request.setTitle("Laporan Kant.in");
        request.setDescription("Mengunduh file " + fileName);
        request.setNotificationVisibility(DownloadManager.Request.VISIBILITY_VISIBLE_NOTIFY_COMPLETED);
        request.setDestinationInExternalPublicDir(Environment.DIRECTORY_DOWNLOADS, fileName);

        // 6. Eksekusi!
        DownloadManager manager = (DownloadManager) getContext().getSystemService(Context.DOWNLOAD_SERVICE);
        if (manager != null) {
            manager.enqueue(request);
            Toast.makeText(getContext(), "Unduhan dimulai. Cek panel notifikasi HP kamu.", Toast.LENGTH_LONG).show();
        }
    }
}