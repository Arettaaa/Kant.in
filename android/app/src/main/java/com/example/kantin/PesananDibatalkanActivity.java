package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import androidx.appcompat.app.AppCompatActivity;
import com.google.android.material.button.MaterialButton;

public class PesananDibatalkanActivity extends AppCompatActivity {

    private MaterialButton btnLihatRiwayat, btnKembaliBeranda;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        // Sembunyikan action bar bawaan
        if (getSupportActionBar() != null) {
            getSupportActionBar().hide();
        }
        setContentView(R.layout.activity_pesanandibatalkan);

        btnLihatRiwayat = findViewById(R.id.btnLihatRiwayat);
        btnKembaliBeranda = findViewById(R.id.btnKembaliBeranda);

        // Aksi Tombol "Lihat Riwayat Pesanan"
        btnLihatRiwayat.setOnClickListener(v -> {
            Intent intent = new Intent(PesananDibatalkanActivity.this, HistoryActivity.class);
            intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
            startActivity(intent);
            finish();
        });

        // Aksi Tombol "Kembali ke Beranda"
        btnKembaliBeranda.setOnClickListener(v -> {
            Intent intent = new Intent(PesananDibatalkanActivity.this, BerandaPelangganActivity.class);
            intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
            startActivity(intent);
            finish();
        });
    }
}