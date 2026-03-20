package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;
import androidx.cardview.widget.CardView;

public class BerandaPelangganActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_berandapelanggan);

        // 1. Inisialisasi ID dari XML ke Java

        // Header (Atas)
        ImageView btnHistoryTop = findViewById(R.id.btn_history_top);
        FrameLayout btnKeranjang = findViewById(R.id.btn_keranjang);
        CardView btnProfilTop = findViewById(R.id.btn_profil_top);

        // Teks "Lihat Semua"
        TextView tvLihatSemuaMenu = findViewById(R.id.tv_lihat_semua_menu);
        TextView tvLihatSemuaKantin = findViewById(R.id.tv_lihat_semua_kantin);

        // Item Dummy (Tengah)
        CardView cvMenuItem = findViewById(R.id.cv_menu_item);
        CardView cvKantinItem = findViewById(R.id.cv_kantin_item);

        // Bottom Navigation (Bawah)
        // nav_beranda tidak perlu diberi intent karena kita sedang berada di Beranda
        LinearLayout navPesanan = findViewById(R.id.nav_pesanan);
        LinearLayout navProfil = findViewById(R.id.nav_profil);

        // 2. Memberikan Aksi Klik (Intent)

        // --- Aksi Bagian Header ---
        btnHistoryTop.setOnClickListener(v -> {
            Intent intent = new Intent(BerandaPelangganActivity.this, HistoryActivity.class);
            startActivity(intent);
        });

        btnKeranjang.setOnClickListener(v -> {
            Intent intent = new Intent(BerandaPelangganActivity.this, KeranjangPelangganActivity.class);
            startActivity(intent);
        });

        btnProfilTop.setOnClickListener(v -> {
            Intent intent = new Intent(BerandaPelangganActivity.this, ProfilPelangganActivity.class);
            startActivity(intent);
        });

        // --- Aksi Teks "Lihat Semua" ---
        tvLihatSemuaMenu.setOnClickListener(v -> {
            Intent intent = new Intent(BerandaPelangganActivity.this, ExploreMenuPelangganActivity.class);
            startActivity(intent);
        });

        tvLihatSemuaKantin.setOnClickListener(v -> {
            Intent intent = new Intent(BerandaPelangganActivity.this, ExploreKantinPelangganActivity.class);
            startActivity(intent);
        });

        // --- Aksi Item Dummy (Menu & Kantin) ---
        cvMenuItem.setOnClickListener(v -> {
            Intent intent = new Intent(BerandaPelangganActivity.this, DetailMenuActivity.class);
            startActivity(intent);
        });

        cvKantinItem.setOnClickListener(v -> {
            Intent intent = new Intent(BerandaPelangganActivity.this, DetailKantinActivity.class);
            startActivity(intent);
        });

        // --- Aksi Bottom Navigation ---
        navPesanan.setOnClickListener(v -> {
            Intent intent = new Intent(BerandaPelangganActivity.this, HistoryActivity.class);
            startActivity(intent);
            finish(); // Opsional: Tutup Beranda agar tidak menumpuk saat di-back
        });

        navProfil.setOnClickListener(v -> {
            Intent intent = new Intent(BerandaPelangganActivity.this, ProfilPelangganActivity.class);
            startActivity(intent);
            finish(); // Opsional: Tutup Beranda agar tidak menumpuk saat di-back
        });
    }
}