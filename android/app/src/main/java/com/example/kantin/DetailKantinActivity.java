package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.widget.ImageView;
import androidx.appcompat.app.AppCompatActivity;
import androidx.cardview.widget.CardView;

public class DetailKantinActivity extends AppCompatActivity {

    private ImageView btnBackWarung;
    private CardView btnAdd1, btnAdd2, btnAdd3;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        // Sembunyikan ActionBar bawaan
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_detailkantinpelanggan);

        // 1. Inisialisasi View
        btnBackWarung = findViewById(R.id.btnBackWarung);
        btnAdd1 = findViewById(R.id.btnAdd1);
        btnAdd2 = findViewById(R.id.btnAdd2);
        btnAdd3 = findViewById(R.id.btnAdd3);

        // 2. Aksi Tombol Kembali
        btnBackWarung.setOnClickListener(v -> onBackPressed());

        // 3. Aksi Tombol Tambah (Pindah ke Keranjang)
        // Kita gunakan satu fungsi helper agar kode lebih rapi
        btnAdd1.setOnClickListener(v -> pindahKeKeranjang());
        btnAdd2.setOnClickListener(v -> pindahKeKeranjang());
        btnAdd3.setOnClickListener(v -> pindahKeKeranjang());
    }

    private void pindahKeKeranjang() {
        Intent intent = new Intent(DetailKantinActivity.this, KeranjangPelangganActivity.class);
        startActivity(intent);
    }
}