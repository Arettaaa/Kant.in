package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

public class EmptyCartActivity extends AppCompatActivity {

    private ImageView btnBack, btnTrash;
    private TextView btnCariMakanan;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        // Sembunyikan Action Bar bawaan
        if (getSupportActionBar() != null) getSupportActionBar().hide();

        // Sesuaikan nama layout XML kamu
        setContentView(R.layout.activity_empty_cart);

        // 1. Hubungkan ID dari XML
        btnBack = findViewById(R.id.btnBack);
        btnTrash = findViewById(R.id.btnTrash);
        btnCariMakanan = findViewById(R.id.btnCariMakanan);

        // 2. Aksi Tombol Back (Kembali ke halaman sebelumnya)
        btnBack.setOnClickListener(v -> {
            finish(); // Menutup halaman ini
        });

        // 3. Aksi Tombol Trash (Karena keranjang kosong, beri Toast saja)
        btnTrash.setOnClickListener(v -> {
            Toast.makeText(this, "Keranjang sudah kosong!", Toast.LENGTH_SHORT).show();
        });

        // 4. Aksi Tombol Cari Makanan (Pindah ke halaman Home/Menu)
        btnCariMakanan.setOnClickListener(v -> {
            // Ganti 'HomeActivity.class' dengan nama halaman Menu/Utama milikmu
            Intent intent = new Intent(EmptyCartActivity.this, BerandaPelangganActivity.class);
            // Tambahkan flag agar tidak numpuk halamannya (opsional)
            intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
            startActivity(intent);
            finish();
        });
    }
}