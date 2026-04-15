package com.example.kantin;

import android.content.Intent;
import android.graphics.Color;
import android.os.Bundle;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;

import com.bumptech.glide.Glide;
import com.example.kantin.utils.SessionManager;
import com.google.android.material.dialog.MaterialAlertDialogBuilder;

public class ProfilPelangganActivity extends AppCompatActivity {

    private ImageView btnBack, ivFotoProfil;
    private TextView btnUbahProfil, tvNamaProfil, tvEmailProfil, tvPhoneProfil;
    private LinearLayout menuDataDiri, menuKeamanan, btnKeluar;
    private SessionManager sessionManager;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_profilpelanggan);

        // 1. Inisialisasi Session
        sessionManager = new SessionManager(this);

        // 2. Hubungkan View dengan ID di XML
        btnBack = findViewById(R.id.btnBack);
        ivFotoProfil = findViewById(R.id.ivFotoProfil);
        tvNamaProfil = findViewById(R.id.tvNamaProfil);
        tvEmailProfil = findViewById(R.id.tvEmailProfil);
        tvPhoneProfil = findViewById(R.id.tvPhoneProfil);

        btnUbahProfil = findViewById(R.id.btnUbahProfil);
        menuDataDiri = findViewById(R.id.menuDataDiri);
        menuKeamanan = findViewById(R.id.menuKeamanan);
        btnKeluar = findViewById(R.id.btnKeluar);

        // 3. Aksi Tombol / Navigasi
        if (btnBack != null) {
            btnBack.setOnClickListener(v -> onBackPressed());
        }

        btnUbahProfil.setOnClickListener(v -> {
            startActivity(new Intent(this, UbahProfilPelangganActivity.class));
        });

        menuDataDiri.setOnClickListener(v -> {
            startActivity(new Intent(this, DetailProfilPelangganActivity.class));
        });

        menuKeamanan.setOnClickListener(v -> {
            startActivity(new Intent(this, KeamananPelangganActivity.class));
        });

        btnKeluar.setOnClickListener(v -> showLogoutConfirmation());
    }

    // Gunakan onResume agar data langsung ter-update saat kembali dari halaman Edit Profil
    @Override
    protected void onResume() {
        super.onResume();
        tampilkanDataProfil();
    }

    private void tampilkanDataProfil() {
        String name = sessionManager.getUserName();
        String email = sessionManager.getUserEmail();
        String phone = sessionManager.getUserPhone();
        String photoPathFromSession = sessionManager.getPhotoUrl();

        tvNamaProfil.setText(name != null && !name.isEmpty() ? name : "Sobat Kantin");
        tvEmailProfil.setText(email != null && !email.isEmpty() ? email : "Email belum diatur");
        tvPhoneProfil.setText(phone != null && !phone.isEmpty() ? phone : "Belum ada nomor HP");

        int paddingPx = (int) (20 * getResources().getDisplayMetrics().density);

        if (photoPathFromSession != null && !photoPathFromSession.isEmpty()) {
            ivFotoProfil.clearColorFilter();
            ivFotoProfil.setPadding(0, 0, 0, 0);

            // Cek dulu, kalau sudah http langsung pakai, kalau belum baru tambah base URL
            String fullPhotoUrl = photoPathFromSession.startsWith("http")
                    ? photoPathFromSession
                    : "https://nonephemerally-nonrevolving-judie.ngrok-free.dev/storage/" + photoPathFromSession;

            Glide.with(this)
                    .load(fullPhotoUrl)
                    .circleCrop()
                    .placeholder(android.R.color.transparent)
                    .error(android.R.color.transparent)
                    .into(ivFotoProfil);
        } else {
            Glide.with(this).clear(ivFotoProfil);
            ivFotoProfil.setImageResource(android.R.color.transparent);
        }
    }
    private void showLogoutConfirmation() {
        new MaterialAlertDialogBuilder(this)
                .setTitle("Konfirmasi Keluar")
                .setMessage("Apakah Anda yakin ingin keluar dari akun ini?")
                .setPositiveButton("Keluar", (dialog, which) -> {
                    // Bersihkan Session
                    sessionManager.clearSession();

                    // Pindah ke Login
                    Intent intent = new Intent(this, LoginActivity.class);
                    intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
                    startActivity(intent);
                    finish();
                })
                .setNegativeButton("Batal", null)
                .show();
    }
}