package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.widget.ImageView;
import android.widget.LinearLayout;
import androidx.appcompat.app.AppCompatActivity;

public class ActiveOrdersActivity extends AppCompatActivity {

    private ImageView btnBack;
    private LinearLayout tabRiwayat;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_activeorders);

        btnBack = findViewById(R.id.btnBack);
        tabRiwayat = findViewById(R.id.tabRiwayat);

        // 1. Tombol Kembali (IKUT HALAMAN SEBELUMNYA)
        btnBack.setOnClickListener(v -> {
            // Ini akan menjalankan fungsi 'Back' bawaan HP
            onBackPressed();
        });

        // 2. Klik Tab Riwayat (Tetap pakai finish agar tab tidak menumpuk)
        tabRiwayat.setOnClickListener(v -> {
            Intent intent = new Intent(ActiveOrdersActivity.this, HistoryActivity.class);
            startActivity(intent);
            overridePendingTransition(0, 0); // Efek tab instan
            finish();
        });
    }
}