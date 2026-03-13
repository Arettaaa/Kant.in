package com.example.kantin;

import android.os.Bundle;
import android.text.InputType;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

public class RegisterActivity extends AppCompatActivity {

    EditText etName, etEmail, etPassword;
    ImageView ivTogglePassword;
    TextView tvLogin;
    Button btnRegister;

    boolean isPasswordVisible = false;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        if (getSupportActionBar() != null) getSupportActionBar().hide();

        setContentView(R.layout.activity_registerpelanggan);

        etName = findViewById(R.id.etName);
        etEmail = findViewById(R.id.etEmail);
        etPassword = findViewById(R.id.etPassword);
        ivTogglePassword = findViewById(R.id.ivTogglePassword);
        tvLogin = findViewById(R.id.tvLogin);
        btnRegister = findViewById(R.id.btnRegister);

        // Toggle password
        ivTogglePassword.setOnClickListener(v -> {

            if (isPasswordVisible) {

                etPassword.setInputType(InputType.TYPE_CLASS_TEXT |
                        InputType.TYPE_TEXT_VARIATION_PASSWORD);

                ivTogglePassword.setImageResource(R.drawable.eye);

                isPasswordVisible = false;

            } else {

                etPassword.setInputType(InputType.TYPE_CLASS_TEXT |
                        InputType.TYPE_TEXT_VARIATION_VISIBLE_PASSWORD);

                ivTogglePassword.setImageResource(R.drawable.eye);

                isPasswordVisible = true;
            }

            etPassword.setSelection(etPassword.getText().length());
        });

        // Tombol daftar
        btnRegister.setOnClickListener(v -> {

            String nama = etName.getText().toString().trim();
            String email = etEmail.getText().toString().trim();
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
        });

        // Pindah ke login
        tvLogin.setOnClickListener(v -> {
            finish();
        });

    }
}