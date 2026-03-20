package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.widget.ImageView;
import android.widget.TextView;
import androidx.appcompat.app.AppCompatActivity;

public class DetailProfilPelangganActivity extends AppCompatActivity {

    private ImageView btnBack;
    private TextView btnEditProfil;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        // Sembunyikan ActionBar bawaan
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_detailprofilpelanggan);

        // Inisialisasi View
        btnBack = findViewById(R.id.btnBack);
        btnEditProfil = findViewById(R.id.btnEditProfil);

        // 1. Aksi Tombol Kembali (Back)
        if (btnBack != null) {
            btnBack.setOnClickListener(v -> onBackPressed());
        }

        // 2. Aksi Tombol Edit Data Pribadi -> Pindah ke UbahProfilPelangganActivity
        if (btnEditProfil != null) {
            btnEditProfil.setOnClickListener(v -> {
                Intent intent = new Intent(DetailProfilPelangganActivity.this, UbahProfilPelangganActivity.class);
                startActivity(intent);
            });
        }
    }
}