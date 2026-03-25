package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.widget.ImageView;
import androidx.appcompat.app.AppCompatActivity;
import com.google.android.material.button.MaterialButton;

public class ValidasiAdminActivity extends AppCompatActivity {

    private ImageView btnBack;
    private MaterialButton btnLihatPesanan;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        // Sembunyikan action bar
        if (getSupportActionBar() != null) {
            getSupportActionBar().hide();
        }
        setContentView(R.layout.activity_validasiadmin);

        btnBack = findViewById(R.id.btnBack);
        btnLihatPesanan = findViewById(R.id.btnLihatPesanan);

        // Aksi Tombol Back
        btnBack.setOnClickListener(v -> onBackPressed());

        // Aksi Tombol "Lihat Pesanan Saya"
        btnLihatPesanan.setOnClickListener(v -> {
            // Pindah ke halaman daftar pesanan (History / Active)
            Intent intent = new Intent(ValidasiAdminActivity.this, HistoryActivity.class);
            // Hapus tumpukan halaman agar tidak menumpuk di memori
            intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
            startActivity(intent);
            finish();
        });
    }
}