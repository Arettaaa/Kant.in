package com.example.kantin;

import android.content.Intent;
import android.content.res.ColorStateList;
import android.graphics.Color;
import android.os.Bundle;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RadioButton;
import android.widget.TextView;
import android.widget.Toast;
import androidx.appcompat.app.AppCompatActivity;

public class KeranjangPelangganActivity extends AppCompatActivity {

    private ImageView btnBack, btnDelete;
    private TextView btnMinus1, btnPlus1, tvQty1, btnCheckout;
    private LinearLayout layoutAmbilSendiri, layoutAntarKurir;
    private RadioButton radioAmbilSendiri, radioAntarKurir;

    // Data dummy awal
    private int quantity1 = 2;
    private final int hargaSatuan = 25000;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        // Menghilangkan action bar bawaan
        if (getSupportActionBar() != null) getSupportActionBar().hide();

        setContentView(R.layout.activity_keranjangpelanggan);

        // Inisialisasi ID dari XML
        btnBack = findViewById(R.id.btnBack);
        btnDelete = findViewById(R.id.btnDelete);
        btnMinus1 = findViewById(R.id.btnMinus1);
        btnPlus1 = findViewById(R.id.btnPlus1);
        tvQty1 = findViewById(R.id.tvQty1);
        btnCheckout = findViewById(R.id.btnCheckout);

        layoutAmbilSendiri = findViewById(R.id.layoutAmbilSendiri);
        layoutAntarKurir = findViewById(R.id.layoutAntarKurir);
        radioAmbilSendiri = findViewById(R.id.radioAmbilSendiri);
        radioAntarKurir = findViewById(R.id.radioAntarKurir);

        // 1. FITUR TAMBAH QUANTITY
        btnPlus1.setOnClickListener(v -> {
            quantity1++;
            tvQty1.setText(String.valueOf(quantity1));
            // Kamu bisa tambah fungsi update total harga di sini jika mau
        });

        // 2. FITUR KURANG QUANTITY
        btnMinus1.setOnClickListener(v -> {
            if (quantity1 > 1) {
                quantity1--;
                tvQty1.setText(String.valueOf(quantity1));
            } else {
                Toast.makeText(this, "Minimal pesanan adalah 1", Toast.LENGTH_SHORT).show();
            }
        });

        // 3. PILIH METODE AMBIL SENDIRI
        layoutAmbilSendiri.setOnClickListener(v -> selectAmbilSendiri());
        radioAmbilSendiri.setOnClickListener(v -> selectAmbilSendiri());

        // 4. PILIH METODE ANTAR KURIR
        layoutAntarKurir.setOnClickListener(v -> selectAntarKurir());
        radioAntarKurir.setOnClickListener(v -> selectAntarKurir());

        // 5. TOMBOL BACK
        btnBack.setOnClickListener(v -> finish());

        // 6. TOMBOL DELETE (Belum Tersedia)
        btnDelete.setOnClickListener(v -> {
            Toast.makeText(this, "Fitur hapus belum tersedia", Toast.LENGTH_SHORT).show();
        });

        // 7. TOMBOL CHECKOUT
        btnCheckout.setOnClickListener(v -> {
            Intent intent = new Intent(KeranjangPelangganActivity.this, CheckoutActivity.class);
            startActivity(intent);
        });
    }

    private void selectAmbilSendiri() {
        radioAmbilSendiri.setChecked(true);
        radioAntarKurir.setChecked(false);

        // Ganti Warna Radio Button
        radioAmbilSendiri.setButtonTintList(ColorStateList.valueOf(Color.parseColor("#F97316"))); // Orange
        radioAntarKurir.setButtonTintList(ColorStateList.valueOf(Color.parseColor("#D1D5DB")));  // Abu-abu

        // Ganti Warna Border Box
        layoutAmbilSendiri.setBackgroundResource(R.drawable.bg_border_orange);
        layoutAntarKurir.setBackgroundResource(R.drawable.bg_border_gray);
    }

    private void selectAntarKurir() {
        radioAntarKurir.setChecked(true);
        radioAmbilSendiri.setChecked(false);

        // Ganti Warna Radio Button
        radioAntarKurir.setButtonTintList(ColorStateList.valueOf(Color.parseColor("#F97316"))); // Orange
        radioAmbilSendiri.setButtonTintList(ColorStateList.valueOf(Color.parseColor("#D1D5DB")));  // Abu-abu

        // Ganti Warna Border Box
        layoutAntarKurir.setBackgroundResource(R.drawable.bg_border_orange);
        layoutAmbilSendiri.setBackgroundResource(R.drawable.bg_border_gray);
    }
}