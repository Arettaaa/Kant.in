package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.text.InputType;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;

public class RegisterAdminActivity extends AppCompatActivity {

    TextView tabPelanggan, tvLogin;

    // 1. Tambahkan variabel untuk Password dan Toggle
    EditText etPassword;
    ImageView ivTogglePassword;

    // 2. Tambahkan status apakah password sedang terlihat
    boolean isPasswordVisible = false;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        // Sembunyikan Action Bar bawaan (opsional biar rapi seperti file sebelumnya)
        if (getSupportActionBar() != null) getSupportActionBar().hide();

        setContentView(R.layout.activity_registeradminkantin);

        // 3. Hubungkan ID
        tabPelanggan = findViewById(R.id.tabPelanggan);
        tvLogin = findViewById(R.id.tvLogin);
        etPassword = findViewById(R.id.etPassword); // Pastikan ID di XML-nya etPassword
        ivTogglePassword = findViewById(R.id.ivTogglePassword); // Pastikan ID di XML-nya ivTogglePassword

        // 4. Fitur Buka/Tutup Password beserta ganti icon
        if (ivTogglePassword != null && etPassword != null) {
            ivTogglePassword.setOnClickListener(v -> {
                if (isPasswordVisible) {
                    // Jika password sedang terlihat, maka SEMBUNYIKAN
                    etPassword.setInputType(InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_PASSWORD);

                    // Ganti icon ke mata tertutup
                    ivTogglePassword.setImageResource(R.drawable.eye_close);

                    isPasswordVisible = false;
                } else {
                    // Jika password sedang tersembunyi, maka TAMPILKAN
                    etPassword.setInputType(InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_VISIBLE_PASSWORD);

                    // Ganti icon ke mata terbuka
                    ivTogglePassword.setImageResource(R.drawable.eye);

                    isPasswordVisible = true;
                }

                // Supaya kursor ketikan tetap berada di ujung kanan teks
                etPassword.setSelection(etPassword.getText().length());
            });
        }

        // Pindah ke register pelanggan
        tabPelanggan.setOnClickListener(v -> {
            Intent intent = new Intent(RegisterAdminActivity.this, RegisterActivity.class);
            startActivity(intent);
            finish();
        });

        // Ke halaman login
        tvLogin.setOnClickListener(v -> {
            Intent intent = new Intent(RegisterAdminActivity.this, LoginActivity.class);
            startActivity(intent);
            finish();
        });
    }
}