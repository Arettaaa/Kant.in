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

        com.example.kantin.utils.SessionManager sessionManager = new com.example.kantin.utils.SessionManager(this);

        TextView tvHaloUser = findViewById(R.id.tv_halo_user);

        String fullName = sessionManager.getUserName();

        if (fullName != null && !fullName.isEmpty()) {
            String firstName = fullName.split(" ")[0];
            tvHaloUser.setText("Halo, " + firstName + "! 👋");
        } else {
            tvHaloUser.setText("Halo, Sobat Kant.in! 👋");
        }

        // 1. Inisialisasi ID
        ImageView btnHistoryTop = findViewById(R.id.btn_history_top);
        FrameLayout btnKeranjang = findViewById(R.id.btn_keranjang);
        CardView btnProfilTop = findViewById(R.id.btn_profil_top);
        TextView tvLihatSemuaMenu = findViewById(R.id.tv_lihat_semua_menu);
        TextView tvLihatSemuaKantin = findViewById(R.id.tv_lihat_semua_kantin);
        CardView cvMenuItem = findViewById(R.id.cv_menu_item);
        CardView cvKantinItem = findViewById(R.id.cv_kantin_item);
        LinearLayout navPesanan = findViewById(R.id.nav_pesanan);
        LinearLayout navProfil = findViewById(R.id.nav_profil);
        // --- Aksi Bagian Header ---
        btnHistoryTop.setOnClickListener(v -> {
            startActivity(new Intent(this, HistoryActivity.class));
        });

        btnKeranjang.setOnClickListener(v -> {
            startActivity(new Intent(this, KeranjangPelangganActivity.class));
        });

        btnProfilTop.setOnClickListener(v -> {
            startActivity(new Intent(this, ProfilPelangganActivity.class));
        });

        // --- Aksi Teks "Lihat Semua" ---
        tvLihatSemuaMenu.setOnClickListener(v -> {
            startActivity(new Intent(this, ExploreMenuPelangganActivity.class));
        });

        tvLihatSemuaKantin.setOnClickListener(v -> {
            startActivity(new Intent(this, ExploreKantinPelangganActivity.class));
        });

        // --- Aksi Item Dummy ---
        cvMenuItem.setOnClickListener(v -> {
            startActivity(new Intent(this, DetailMenuActivity.class));
        });

        cvKantinItem.setOnClickListener(v -> {
            startActivity(new Intent(this, DetailKantinActivity.class));
        });

        // --- Aksi Bottom Navigation (DIPERBAIKI) ---
        navPesanan.setOnClickListener(v -> {
            startActivity(new Intent(this, HistoryActivity.class));
            // Hapus finish() agar Beranda tidak tertutup
        });

        navProfil.setOnClickListener(v -> {
            startActivity(new Intent(this, ProfilPelangganActivity.class));
            // Hapus finish() agar Beranda tidak tertutup
        });
    }
}