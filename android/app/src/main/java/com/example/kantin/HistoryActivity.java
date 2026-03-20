package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import androidx.appcompat.app.AppCompatActivity;

public class HistoryActivity extends AppCompatActivity {

    private ImageView btnBack;
    private LinearLayout tabSedangDiproses;
    private TextView btnPesanLagi;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_history);

        btnBack = findViewById(R.id.btnBack);
        tabSedangDiproses = findViewById(R.id.tabSedangDiproses);
        btnPesanLagi = findViewById(R.id.btnPesanLagi); // Mengambil tombol pertama

    // 1. Aksi Tombol Kembali (Balik ke halaman sebelumnya secara otomatis)
        btnBack.setOnClickListener(v -> {
            onBackPressed();
        });

        tabSedangDiproses.setOnClickListener(v -> {
            Intent intent = new Intent(HistoryActivity.this, ActiveOrdersActivity.class);
            startActivity(intent);
            overridePendingTransition(0, 0); // Agar perpindahan tab terlihat instan
            finish();
        });

        if (btnPesanLagi != null) {
            btnPesanLagi.setOnClickListener(v -> {
                Intent intent = new Intent(HistoryActivity.this, DetailMenuActivity.class);
                startActivity(intent);
            });
        }
    }
}