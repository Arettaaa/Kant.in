package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.widget.LinearLayout;
import androidx.appcompat.app.AppCompatActivity;

public class SuccessPaymentActivity extends AppCompatActivity {

    private LinearLayout btnCekStatus;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        // Sembunyikan ActionBar jika ada
        if (getSupportActionBar() != null) getSupportActionBar().hide();

        setContentView(R.layout.activity_successpayment);

        // Inisialisasi tombol
        btnCekStatus = findViewById(R.id.btnCekStatus);

        // Aksi klik tombol "Cek Status Pesanan"
        btnCekStatus.setOnClickListener(v -> {
            // Pindah ke halaman pesanan aktif (ActiveOrdersActivity)
            Intent intent = new Intent(SuccessPaymentActivity.this, HistoryActivity.class);

            // Tambahkan FLAG agar saat user menekan Back, tidak kembali ke halaman sukses lagi
            intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);

            startActivity(intent);
            finish();
        });
    }
}