package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.widget.ImageView;
import android.widget.LinearLayout;
import androidx.appcompat.app.AppCompatActivity;
import androidx.cardview.widget.CardView;

public class ExploreKantinPelangganActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        // Menghilangkan ActionBar bawaan agar tampilan full seperti desain
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_explorekantinpelanggan);

        // --- INISIALISASI VIEW ---
        ImageView btnBack = findViewById(R.id.btnBackExplore);
        CardView cvWarungBuAni = findViewById(R.id.cvWarungBuAni);

        LinearLayout navBeranda = findViewById(R.id.navBeranda);
        LinearLayout navPesanan = findViewById(R.id.navPesanan);
        LinearLayout navProfil = findViewById(R.id.navProfil);

        // --- LOGIKA KLIK ---

        // 1. Tombol Kembali
        btnBack.setOnClickListener(v -> onBackPressed());

        // 2. Klik Kartu Kantin (Masuk ke Detail)
        cvWarungBuAni.setOnClickListener(v -> {
            // Pastikan kamu sudah buat DetailKantinActivity
            Intent intent = new Intent(this, DetailKantinActivity.class);
            startActivity(intent);
        });

        // 3. Navigasi Bawah: Ke Beranda
        navBeranda.setOnClickListener(v -> {
            Intent intent = new Intent(this, BerandaPelangganActivity.class);
            intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP); // Agar tidak menumpuk halaman
            startActivity(intent);
        });

        // 4. Navigasi Bawah: Ke Pesanan/History
        navPesanan.setOnClickListener(v -> {
            Intent intent = new Intent(this, HistoryActivity.class); // Sesuaikan nama Activity-mu
            startActivity(intent);
        });

        // 5. Navigasi Bawah: Ke Profil
        navProfil.setOnClickListener(v -> {
            Intent intent = new Intent(this, ProfilPelangganActivity.class); // Sesuaikan nama Activity-mu
            startActivity(intent);
        });
    }
}