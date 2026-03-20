package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.text.InputType;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;
import androidx.appcompat.app.AppCompatActivity;

// Import MaterialButton karena di XML kita pakai MaterialButton
import com.google.android.material.button.MaterialButton;

public class LoginActivity extends AppCompatActivity {

    private EditText etEmail, etPassword;
    private ImageView ivTogglePassword;
    private TextView tvForgotPassword, tvRegister;
    private MaterialButton btnLogin; // Diubah dari Button ke MaterialButton
    private boolean isPasswordVisible = false;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        // Menghilangkan ActionBar bawaan agar full design
        if (getSupportActionBar() != null) getSupportActionBar().hide();

        setContentView(R.layout.activity_login);

        // Inisialisasi View sesuai ID di XML
        etEmail          = findViewById(R.id.etEmail);
        etPassword       = findViewById(R.id.etPassword);
        ivTogglePassword = findViewById(R.id.ivTogglePassword);
        tvForgotPassword = findViewById(R.id.tvForgotPassword);
        tvRegister       = findViewById(R.id.tvRegister);
        btnLogin         = findViewById(R.id.btnLogin);

        // 1. Toggle show/hide password
        ivTogglePassword.setOnClickListener(v -> {
            if (isPasswordVisible) {
                // Sembunyikan password
                etPassword.setInputType(InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_PASSWORD);
                ivTogglePassword.setImageResource(R.drawable.eye); // Ganti ke ikon mata normal
                isPasswordVisible = false;
            } else {
                // Tampilkan password
                etPassword.setInputType(InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_VISIBLE_PASSWORD);
                ivTogglePassword.setImageResource(R.drawable.eye); // Ganti ke ikon mata coret (jika ada)
                isPasswordVisible = true;
            }
            // Posisikan kursor tetap di akhir teks
            etPassword.setSelection(etPassword.getText().length());
        });

        // 2. Logika Tombol Login
        btnLogin.setOnClickListener(v -> {
            String email = etEmail.getText().toString().trim();
            String password = etPassword.getText().toString().trim();

            // Validasi Email
            if (email.isEmpty()) {
                etEmail.setError("Email tidak boleh kosong");
                etEmail.requestFocus();
                return;
            }

            if (!android.util.Patterns.EMAIL_ADDRESS.matcher(email).matches()) {
                etEmail.setError("Format email tidak valid");
                etEmail.requestFocus();
                return;
            }

            // Validasi Password
            if (password.isEmpty()) {
                etPassword.setError("Kata sandi tidak boleh kosong");
                etPassword.requestFocus();
                return;
            }

            if (password.length() < 6) {
                etPassword.setError("Minimal 6 karakter");
                etPassword.requestFocus();
                return;
            }

            // Jika validasi lolos
            Toast.makeText(this, "Login berhasil", Toast.LENGTH_SHORT).show();

            // Pindah ke Beranda
            Intent intent = new Intent(LoginActivity.this, BerandaPelangganActivity.class);
            startActivity(intent);
            finish(); // Tutup LoginActivity agar tidak bisa balik lagi pakai tombol back
        });

        // 3. Klik Daftar (Register)
        tvRegister.setOnClickListener(v -> {
            Intent intent = new Intent(LoginActivity.this, RegisterActivity.class);
            startActivity(intent);
        });

        // 4. Klik Lupa Password
        tvForgotPassword.setOnClickListener(v -> {
            Intent intent = new Intent(LoginActivity.this, LupaPasswordActivity.class);
            startActivity(intent);
        });
    }
}