package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.text.InputType;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

public class RegisterActivity extends AppCompatActivity {

    EditText etName, etEmail, etPhone, etPassword;
    ImageView ivTogglePassword;
    TextView tvLogin, tabPelanggan, tabPemilik;
    Button btnRegister;

    boolean isPasswordVisible = false;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        if (getSupportActionBar() != null) getSupportActionBar().hide();

        setContentView(R.layout.activity_registerpelanggan);

        etName = findViewById(R.id.etName);
        etEmail = findViewById(R.id.etEmail);
        etPhone = findViewById(R.id.etPhone);
        etPassword = findViewById(R.id.etPassword);
        ivTogglePassword = findViewById(R.id.ivTogglePassword);
        tvLogin = findViewById(R.id.tvLogin);
        btnRegister = findViewById(R.id.btnRegister);

        tabPelanggan = findViewById(R.id.tabPelanggan);
        tabPemilik = findViewById(R.id.tabPemilik);

        // Toggle password (buka/tutup mata)
        ivTogglePassword.setOnClickListener(v -> {

            if (isPasswordVisible) {
                // Jika password sedang terlihat, maka SEMBUNYIKAN
                etPassword.setInputType(InputType.TYPE_CLASS_TEXT |
                        InputType.TYPE_TEXT_VARIATION_PASSWORD);

                // Ubah ikon ke mata tertutup
                ivTogglePassword.setImageResource(R.drawable.eye_close);

                isPasswordVisible = false;

            } else {
                // Jika password sedang tersembunyi, maka TAMPILKAN
                etPassword.setInputType(InputType.TYPE_CLASS_TEXT |
                        InputType.TYPE_TEXT_VARIATION_VISIBLE_PASSWORD);

                // Ubah ikon ke mata terbuka
                ivTogglePassword.setImageResource(R.drawable.eye);

                isPasswordVisible = true;
            }

            // Pindahkan kursor ke akhir teks
            etPassword.setSelection(etPassword.getText().length());
        });

        // Tombol daftar
        btnRegister.setOnClickListener(v -> {

            String nama = etName.getText().toString().trim();
            String email = etEmail.getText().toString().trim();
            String phone = etPhone.getText().toString().trim();
            String password = etPassword.getText().toString().trim();

            if (nama.isEmpty()) {
                etName.setError("Nama tidak boleh kosong");
                etName.requestFocus();
                return;
            }

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

            if (phone.isEmpty()) {
                etPhone.setError("Nomor HP tidak boleh kosong");
                etPhone.requestFocus();
                return;
            }

            if (phone.length() < 10) {
                etPhone.setError("Nomor HP tidak valid");
                etPhone.requestFocus();
                return;
            }

            if (password.isEmpty()) {
                etPassword.setError("Password tidak boleh kosong");
                etPassword.requestFocus();
                return;
            }

            if (password.length() < 6) {
                etPassword.setError("Password minimal 6 karakter");
                etPassword.requestFocus();
                return;
            }

            Toast.makeText(this, "Akun berhasil dibuat!", Toast.LENGTH_SHORT).show();
            // TODO: Tambahkan Intent ke halaman Home/Menu utama jika berhasil register
        });

        // pindah ke register admin
        tabPemilik.setOnClickListener(v -> {
            Intent intent = new Intent(RegisterActivity.this, RegisterAdminActivity.class);
            startActivity(intent);
            finish();
        });

        // ke login
        tvLogin.setOnClickListener(v -> {
            Intent intent = new Intent(RegisterActivity.this, LoginActivity.class);
            startActivity(intent);
            finish();
        });
    }
}