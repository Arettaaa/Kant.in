package com.example.kantin;

import android.os.Bundle;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;
import androidx.appcompat.app.AppCompatActivity;

public class DetailMenuActivity extends AppCompatActivity {

    private ImageView btnBack, btnMinus, btnPlus;
    private TextView tvQuantity;
    private LinearLayout btnTambahKeranjang;
    private int quantity = 1;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_detailmakananpelanggan);

        // 1. Inisialisasi ID
        btnBack = findViewById(R.id.btnBack);
        btnMinus = findViewById(R.id.btnMinus); // Pastikan ID ini ada di XML
        btnPlus = findViewById(R.id.btnPlus);   // Pastikan ID ini ada di XML
        tvQuantity = findViewById(R.id.tvQuantity); // Pastikan ID ini ada di XML
        btnTambahKeranjang = findViewById(R.id.btnTambahKeranjang); // Beri ID pada LinearLayout tombol orange

        // 2. Tombol Kembali
        btnBack.setOnClickListener(v -> onBackPressed());

        // 3. Logika Kurangi Jumlah
        btnMinus.setOnClickListener(v -> {
            if (quantity > 1) {
                quantity--;
                tvQuantity.setText(String.valueOf(quantity));
            }
        });

        // 4. Logika Tambah Jumlah
        btnPlus.setOnClickListener(v -> {
            quantity++;
            tvQuantity.setText(String.valueOf(quantity));
        });

        // 5. Tambah ke Keranjang
        btnTambahKeranjang.setOnClickListener(v -> {
            Toast.makeText(this, quantity + " Nasi Goreng masuk ke keranjang!", Toast.LENGTH_SHORT).show();
            finish(); // Kembali ke halaman sebelumnya setelah berhasil tambah
        });
    }
}