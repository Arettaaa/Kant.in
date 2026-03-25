package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.os.CountDownTimer;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;
import androidx.appcompat.app.AppCompatActivity;
import com.google.android.material.bottomsheet.BottomSheetDialog;
import com.google.android.material.button.MaterialButton;

public class CancelPaymentActivity extends AppCompatActivity {

    private ImageView btnBack;
    private TextView tvTimer;
    private MaterialButton btnCancelOrder;
    private CountDownTimer countDownTimer;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        if (getSupportActionBar() != null) {
            getSupportActionBar().hide();
        }
        setContentView(R.layout.activity_cancelpayment);

        btnBack = findViewById(R.id.btnBack);
        tvTimer = findViewById(R.id.tvTimer);
        btnCancelOrder = findViewById(R.id.btnCancelOrder);

        btnBack.setOnClickListener(v -> {
            onBackPressed(); // Berfungsi sama dengan tombol back bawaan HP
        });

        startTimer(31000); // Set timer 30 detik

        btnCancelOrder.setOnClickListener(v -> showCancelBottomSheetDialog());
    }

    // --- FUNGSI TIMER MUNDUR ---
    private void startTimer(long durationInMillis) {
        countDownTimer = new CountDownTimer(durationInMillis, 1000) {
            @Override
            public void onTick(long millisUntilFinished) {
                // Konversi milidetik ke detik
                long secondsLeft = millisUntilFinished / 1000;

                // Format teks menjadi "00:XX"
                String timeFormatted = String.format("00:%02d", secondsLeft);
                tvTimer.setText(timeFormatted);
            }

            // DI SINI LETAK onFinish() YANG BENAR (Di dalam CountDownTimer)
            @Override
            public void onFinish() {
                tvTimer.setText("00:00");

                // Langsung pindah ke halaman Validasi Admin otomatis
                Intent intent = new Intent(CancelPaymentActivity.this, ValidasiAdminActivity.class);
                startActivity(intent);
                finish(); // Tutup halaman cancel agar user tidak bisa back ke halaman ini lagi
            }
        }.start();
    }

    // --- FUNGSI MEMUNCULKAN BOTTOM SHEET DIALOG ---
    private void showCancelBottomSheetDialog() {
        BottomSheetDialog bottomSheetDialog = new BottomSheetDialog(this);

        // Hubungkan dengan layout dialog_cancel_order.xml
        View dialogView = getLayoutInflater().inflate(R.layout.dialog_cancel_order, null);
        bottomSheetDialog.setContentView(dialogView);

        // PENTING: Buat background bawaan dialog menjadi transparan
        if (bottomSheetDialog.getWindow() != null) {
            bottomSheetDialog.getWindow().setBackgroundDrawable(new android.graphics.drawable.ColorDrawable(android.graphics.Color.TRANSPARENT));
        }

        // Inisialisasi tombol di dalam dialog
        MaterialButton btnConfirmCancel = dialogView.findViewById(R.id.btnConfirmCancel);
        MaterialButton btnBackFromDialog = dialogView.findViewById(R.id.btnBackFromDialog);

        // Aksi jika user klik "Ya, Batalkan"
        btnConfirmCancel.setOnClickListener(v -> {
            bottomSheetDialog.dismiss();
            if (countDownTimer != null) countDownTimer.cancel();

            Toast.makeText(this, "Pesanan dibatalkan", Toast.LENGTH_SHORT).show();

            // UBAH BAGIAN INI
            Intent intent = new Intent(CancelPaymentActivity.this, PesananDibatalkanActivity.class);
            startActivity(intent);
            finish();
        });

        // Aksi jika user klik "Kembali" di dialog
        btnBackFromDialog.setOnClickListener(v -> {
            bottomSheetDialog.dismiss();
        });

        // Tampilkan dialog ke layar
        bottomSheetDialog.show();
    }

    // FUNGSI INI WAJIB ADA AGAR TIMER BERHENTI SAAT PINDAH HALAMAN
    @Override
    protected void onDestroy() {
        super.onDestroy();
        // Pastikan timer dihentikan saat halaman ditutup agar tidak bocor di background
        if (countDownTimer != null) {
            countDownTimer.cancel();
        }
    }
}