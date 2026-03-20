package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.widget.Button; // 1. Import Button
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;
import androidx.appcompat.app.AppCompatActivity;

public class LupaPasswordActivity extends AppCompatActivity {

    private ImageView btnBack;
    private Button btnSubmit; // 2. Ubah LinearLayout menjadi Button di sini
    private TextView tvLoginLink;
    private EditText etEmailInput;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        // Menghilangkan action bar bawaan
        if (getSupportActionBar() != null) getSupportActionBar().hide();

        setContentView(R.layout.activity_lupa_password);

        // Kenalkan ID dari XML ke Java
        btnBack = findViewById(R.id.btnBack);
        btnSubmit = findViewById(R.id.btnSubmit); // Sekarang ini aman karena sama-sama Button
        tvLoginLink = findViewById(R.id.tvLoginLink);
        etEmailInput = findViewById(R.id.etEmailInput);

        // 1. Aksi Tombol Kembali (Panah Kiri)
        btnBack.setOnClickListener(v -> {
            finish(); // Menutup halaman ini dan kembali ke halaman sebelumnya
        });

        // 2. Aksi Tulisan "Masuk di sini"
        tvLoginLink.setOnClickListener(v -> {
            Intent intent = new Intent(LupaPasswordActivity.this, LoginActivity.class);
            // Menambahkan flag ini agar tidak menumpuk halaman Login berkali-kali
            intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
            startActivity(intent);
            finish();
        });

        // 3. Aksi Tombol "Lanjutkan"
        btnSubmit.setOnClickListener(v -> {
            String email = etEmailInput.getText().toString().trim();

            // Validasi: Cek apakah email kosong
            if (email.isEmpty()) {
                etEmailInput.setError("Email atau No HP tidak boleh kosong");
                etEmailInput.requestFocus();
                return; // Hentikan proses jika kosong
            }

            // Jika sudah diisi, pindah ke halaman Reset Password
            Intent intent = new Intent(LupaPasswordActivity.this, ResetPasswordActivity.class);
            startActivity(intent);
        });
    }
}