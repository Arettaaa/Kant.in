package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import androidx.appcompat.app.AppCompatActivity;

import com.example.kantin.utils.SessionManager; // Import SessionManager
import com.google.android.material.dialog.MaterialAlertDialogBuilder; // Biar Alert-nya cantik

public class ProfilPelangganActivity extends AppCompatActivity {

    private ImageView btnBack;
    private TextView btnUbahProfil;
    private LinearLayout menuDataDiri, menuKeamanan, btnKeluar;

    // Inisialisasi SessionManager
    private SessionManager sessionManager;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_profilpelanggan);

        // 1. Panggil SessionManager
        sessionManager = new SessionManager(this);

        btnBack = findViewById(R.id.btnBack);
        btnUbahProfil = findViewById(R.id.btnUbahProfil);
        menuDataDiri = findViewById(R.id.menuDataDiri);
        menuKeamanan = findViewById(R.id.menuKeamanan);
        btnKeluar = findViewById(R.id.btnKeluar);

        // BACK KE BERANDA
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

        // 2. Logika Keluar (Logout) yang Benar
        btnKeluar.setOnClickListener(v -> {
            showLogoutConfirmation();
        });
    }

    private void showLogoutConfirmation() {
        new MaterialAlertDialogBuilder(this)
                .setTitle("Konfirmasi Keluar")
                .setMessage("Apakah Anda yakin ingin keluar dari akun ini?")
                .setPositiveButton("Keluar", (dialog, which) -> {
                    // HAPUS SEMUA DATA SESI (Token, Role, dll)
                    sessionManager.clearSession();

                    // Pindah ke Login dan bersihkan tumpukan Activity
                    Intent intent = new Intent(this, LoginActivity.class);
                    intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
                    startActivity(intent);
                    finish();
                })
                .setNegativeButton("Batal", null)
                .show();
    }
}