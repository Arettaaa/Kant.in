package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.os.CountDownTimer;
import android.util.Log;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;
import androidx.appcompat.app.AppCompatActivity;

import com.example.kantin.model.response.BaseResponse;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;
import com.example.kantin.utils.SessionManager;
import com.google.android.material.bottomsheet.BottomSheetDialog;
import com.google.android.material.button.MaterialButton;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class CancelPaymentActivity extends AppCompatActivity {

    private ImageView btnBack;
    private TextView tvTimer;
    private TextView tvOrderId; // tambah ini

    private MaterialButton btnCancelOrder;
    private CountDownTimer countDownTimer;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_cancelpayment);

        btnBack = findViewById(R.id.btnBack);
        tvTimer = findViewById(R.id.tvTimer);
        tvOrderId = findViewById(R.id.tvOrderId);
        btnCancelOrder = findViewById(R.id.btnCancelOrder);

        // Tampilkan ORDER_CODE di UI (bukan ORDER_ID)
        // Di onCreate, setelah ambil intent
        String orderCode = getIntent().getStringExtra("ORDER_CODE");
        String orderId = getIntent().getStringExtra("ORDER_ID");
        Log.d("CANCEL_DEBUG", "ORDER_CODE: " + orderCode);
        Log.d("CANCEL_DEBUG", "ORDER_ID: " + orderId);
        if (orderCode != null) {
            tvOrderId.setText(orderCode);
        }
        btnBack.setOnClickListener(v -> onBackPressed());
        startTimer(31000);
        btnCancelOrder.setOnClickListener(v -> showCancelBottomSheetDialog());
    }

    // --- FUNGSI TIMER MUNDUR ---
    private void startTimer(long durationInMillis) {
        countDownTimer = new CountDownTimer(durationInMillis, 1000) {
            @Override
            public void onTick(long millisUntilFinished) {
                long secondsLeft = millisUntilFinished / 1000;
                tvTimer.setText(String.format("00:%02d", secondsLeft));
            }

            @Override
            public void onFinish() {
                tvTimer.setText("00:00");
                Intent intent = new Intent(CancelPaymentActivity.this, ValidasiAdminActivity.class);
                intent.putExtra("ORDER_CODE", getIntent().getStringExtra("ORDER_CODE"));
                intent.putExtra("ORDER_ID", getIntent().getStringExtra("ORDER_ID"));
                startActivity(intent);
                finish();
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

            String orderId = getIntent().getStringExtra("ORDER_ID");
            Log.d("CANCEL_DEBUG", "ORDER_ID yang dikirim ke API: " + orderId); // tambah ini

            String token = new SessionManager(this).getToken();
            Log.d("CANCEL_DEBUG", "Token: " + token); // tambah ini
            ApiClient.getAuthClient(token).create(ApiService.class)
                    .cancelOrder(orderId)
                    .enqueue(new Callback<BaseResponse>() {
                        @Override
                        public void onResponse(Call<BaseResponse> call, Response<BaseResponse> response) {
                            if (response.isSuccessful()) {
                                Toast.makeText(CancelPaymentActivity.this, "Pesanan dibatalkan", Toast.LENGTH_SHORT).show();
                            } else {
                                Toast.makeText(CancelPaymentActivity.this, "Gagal membatalkan, tapi tetap lanjut", Toast.LENGTH_SHORT).show();
                            }
                            Intent intent = new Intent(CancelPaymentActivity.this, PesananDibatalkanActivity.class);
                            startActivity(intent);
                            finish();
                        }

                        @Override
                        public void onFailure(Call<BaseResponse> call, Throwable t) {
                            Toast.makeText(CancelPaymentActivity.this, "Error jaringan", Toast.LENGTH_SHORT).show();
                            Intent intent = new Intent(CancelPaymentActivity.this, PesananDibatalkanActivity.class);
                            startActivity(intent);
                            finish();
                        }
                    });
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