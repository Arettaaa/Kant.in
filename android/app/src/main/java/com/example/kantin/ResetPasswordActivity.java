package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.widget.Button; // Tambahkan ini
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Toast;
import androidx.appcompat.app.AppCompatActivity;

public class ResetPasswordActivity extends AppCompatActivity {

    private ImageView btnBack;
    private Button btnSubmit; // Ubah dari LinearLayout ke Button
    private EditText etNewPassword, etConfirmPassword;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        if (getSupportActionBar() != null) getSupportActionBar().hide();

        setContentView(R.layout.activity_reset_password);

        btnBack = findViewById(R.id.btnBack);
        btnSubmit = findViewById(R.id.btnSubmit);
        etNewPassword = findViewById(R.id.etNewPassword);
        etConfirmPassword = findViewById(R.id.etConfirmPassword);

        btnBack.setOnClickListener(v -> {
            finish();
        });

        btnSubmit.setOnClickListener(v -> {
            String newPassword = etNewPassword.getText().toString().trim();
            String confirmPassword = etConfirmPassword.getText().toString().trim();

            if (newPassword.isEmpty()) {
                etNewPassword.setError("Kata sandi tidak boleh kosong");
                etNewPassword.requestFocus();
                return;
            }

            if (newPassword.length() < 6) {
                etNewPassword.setError("Kata sandi minimal 6 karakter");
                etNewPassword.requestFocus();
                return;
            }

            if (confirmPassword.isEmpty()) {
                etConfirmPassword.setError("Konfirmasi kata sandi tidak boleh kosong");
                etConfirmPassword.requestFocus();
                return;
            }

            if (!newPassword.equals(confirmPassword)) {
                etConfirmPassword.setError("Kata sandi tidak cocok!");
                etConfirmPassword.requestFocus();
                return;
            }

            // Pindah ke halaman sukses
            Intent intent = new Intent(ResetPasswordActivity.this, PasswordBerhasilActivity.class);
            startActivity(intent);
            finish();
        });
    }
}