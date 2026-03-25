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

        startTimer(30000);

        btnCancelOrder.setOnClickListener(v -> showCancelBottomSheetDialog());
    }

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

            @Override
            public void onFinish() {
                tvTimer.setText("00:00");
                // Opsional: Anda bisa menonaktifkan tombol batal jika waktu habis
                 btnCancelOrder.setEnabled(false);
                 btnCancelOrder.setAlpha(0.5f);
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
        // Agar lengkungan (radius) dari bg_bottom_sheet.xml bisa terlihat
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

            Intent intent = new Intent(CancelPaymentActivity.this, HistoryActivity.class);
            intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
            startActivity(intent);
            finish();
        });

        // Aksi jika user klik "Kembali" di dialog
        btnBackFromDialog.setOnClickListener(v -> {
            bottomSheetDialog.dismiss();
        });

        // Tampilkan dialog ke layar
        bottomSheetDialog.show();
    } // <-- Di kode Anda sebelumnya, ada kode duplikat di bawah kurung kurawal ini

    @Override
    public void onFinish() {
        tvTimer.setText("00:00");

        // Langsung pindah ke halaman Validasi Admin otomatis
        Intent intent = new Intent(CancelPaymentActivity.this, ValidasiAdminActivity.class);
        startActivity(intent);
        finish(); // Tutup halaman cancel agar user tidak bisa back ke halaman ini lagi
    }
}