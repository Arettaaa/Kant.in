package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;
import androidx.appcompat.app.AppCompatActivity;

public class ProfilPelangganActivity extends AppCompatActivity {

    private ImageView btnBack;
    private TextView btnUbahProfil;
    private LinearLayout menuDataDiri, menuKeamanan, btnKeluar;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_profilpelanggan);

        btnBack = findViewById(R.id.btnBack);
        btnUbahProfil = findViewById(R.id.btnUbahProfil);
        menuDataDiri = findViewById(R.id.menuDataDiri);
        menuKeamanan = findViewById(R.id.menuKeamanan);
        btnKeluar = findViewById(R.id.btnKeluar);

        // BACK KE BERANDA (Menggunakan onBackPressed agar konsisten)
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

        btnKeluar.setOnClickListener(v -> {
            Intent intent = new Intent(this, LoginActivity.class);
            intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
            startActivity(intent);
            Toast.makeText(this, "Berhasil Keluar", Toast.LENGTH_SHORT).show();
        });
    }
}