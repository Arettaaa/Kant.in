package com.example.kantin;

import android.os.Bundle;
import android.text.InputType;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;
import androidx.appcompat.app.AppCompatActivity;

public class LoginActivity extends AppCompatActivity {

    private EditText etEmail, etPassword;
    private TextView tvTogglePassword, tvForgotPassword, tvRegister, tvKantinOwner;
    private Button btnLogin;
    private boolean isPasswordVisible = false;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        if (getSupportActionBar() != null) getSupportActionBar().hide();

        setContentView(R.layout.activity_login);

        etEmail          = findViewById(R.id.etEmail);
        etPassword       = findViewById(R.id.etPassword);
        tvTogglePassword = findViewById(R.id.tvTogglePassword);
        tvForgotPassword = findViewById(R.id.tvForgotPassword);
        tvRegister       = findViewById(R.id.tvRegister);
        tvKantinOwner    = findViewById(R.id.tvKantinOwner);
        btnLogin         = findViewById(R.id.btnLogin);

        // Toggle show/hide password
        tvTogglePassword.setOnClickListener(v -> {
            if (isPasswordVisible) {
                etPassword.setInputType(InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_PASSWORD);
                tvTogglePassword.setText("👁");
                isPasswordVisible = false;
            } else {
                etPassword.setInputType(InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_VISIBLE_PASSWORD);
                tvTogglePassword.setText("🙈");
                isPasswordVisible = true;
            }
            etPassword.setSelection(etPassword.getText().length());
        });

        // Tombol Masuk
        btnLogin.setOnClickListener(v -> {
            String email    = etEmail.getText().toString().trim();
            String password = etPassword.getText().toString().trim();

            if (email.isEmpty()) {
                etEmail.setError("Email tidak boleh kosong");
                etEmail.requestFocus(); return;
            }
            if (!android.util.Patterns.EMAIL_ADDRESS.matcher(email).matches()) {
                etEmail.setError("Format email tidak valid");
                etEmail.requestFocus(); return;
            }
            if (password.isEmpty()) {
                etPassword.setError("Kata sandi tidak boleh kosong");
                etPassword.requestFocus(); return;
            }
            if (password.length() < 6) {
                etPassword.setError("Kata sandi minimal 6 karakter");
                etPassword.requestFocus(); return;
            }

            Toast.makeText(this, "Masuk sebagai: " + email, Toast.LENGTH_SHORT).show();
            // Intent intent = new Intent(this, MainActivity.class);
            // startActivity(intent); finish();
        });

        tvForgotPassword.setOnClickListener(v ->
                Toast.makeText(this, "Fitur belum tersedia", Toast.LENGTH_SHORT).show());

        tvRegister.setOnClickListener(v ->
                Toast.makeText(this, "Halaman daftar", Toast.LENGTH_SHORT).show());

        tvKantinOwner.setOnClickListener(v ->
                Toast.makeText(this, "Masuk sebagai Pemilik Kantin", Toast.LENGTH_SHORT).show());
    }
}