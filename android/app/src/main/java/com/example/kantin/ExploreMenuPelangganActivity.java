package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.widget.ImageView;
import android.widget.LinearLayout;
import androidx.appcompat.app.AppCompatActivity;

public class ExploreMenuPelangganActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        // Menghilangkan ActionBar (opsional, tapi disarankan agar UI penuh)
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_exploremenupelanggan);

        // Tombol Back
        ImageView btnBack = findViewById(R.id.btnBackExploreMenu);
        btnBack.setOnClickListener(v -> onBackPressed());

        // Klik Item Nasi Goreng
        LinearLayout itemNasiGoreng = findViewById(R.id.itemNasiGoreng);
        itemNasiGoreng.setOnClickListener(v -> {
            Intent intent = new Intent(this, DetailMenuActivity.class);
            startActivity(intent);
        });

        // Navigasi Bawah
        findViewById(R.id.navHome).setOnClickListener(v -> finish());
    }
}