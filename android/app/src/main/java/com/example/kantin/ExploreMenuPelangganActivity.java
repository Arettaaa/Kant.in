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
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_exploremenupelanggan);

        // --- Inisialisasi Tombol ---
        ImageView btnBack = findViewById(R.id.btnBackExploreMenu);
        LinearLayout itemNasiGoreng = findViewById(R.id.itemNasiGoreng);

        // Inisialisasi Navbar
        LinearLayout navHome = findViewById(R.id.navHome);
        LinearLayout navHistory = findViewById(R.id.navHistory);
        LinearLayout navProfile = findViewById(R.id.navProfile);

        // --- Logika Klik ---

        // Tombol Back
        btnBack.setOnClickListener(v -> onBackPressed());

        // Klik Item Menu (Nasi Goreng)
        itemNasiGoreng.setOnClickListener(v -> {
            Intent intent = new Intent(this, DetailMenuActivity.class);
            startActivity(intent);
        });

        // --- Navigasi Bawah (Bottom Bar) ---

        // Ke Beranda (Biasanya menutup activity cari agar balik ke Home)
        navHome.setOnClickListener(v -> {
            // Jika Beranda adalah MainActivity
            Intent intent = new Intent(this, MainActivity.class);
            intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_SINGLE_TOP);
            startActivity(intent);
        });

        // Ke Halaman Pesanan (Riwayat)
        navHistory.setOnClickListener(v -> {
            Intent intent = new Intent(this, HistoryActivity.class);
            startActivity(intent);
        });

        // Ke Halaman Profil
        navProfile.setOnClickListener(v -> {
            Intent intent = new Intent(this, ProfilPelangganActivity.class);
            startActivity(intent);
        });
    }
}