package com.example.kantin;

import android.content.Intent;
import android.content.res.ColorStateList;
import android.graphics.Color;
import android.os.Bundle;
import android.os.Handler;
import android.text.Editable;
import android.text.InputType;
import android.text.TextWatcher;
import android.util.Patterns;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;
import com.example.kantin.model.request.RegisterPelangganRequest;
import com.example.kantin.model.response.RegisterResponse;

import org.json.JSONObject;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class RegisterActivity extends AppCompatActivity {

    EditText etName, etEmail, etPhone, etPassword;
    ImageView ivTogglePassword;
    TextView tvLogin, tabPelanggan, tabPemilik, tvStrengthLabel;
    Button btnRegister;
    View bar1, bar2, bar3, bar4;
    boolean isPasswordVisible = false;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_registerpelanggan);

        // Inisialisasi View
        etName     = findViewById(R.id.etName);
        etEmail    = findViewById(R.id.etEmail);
        etPhone    = findViewById(R.id.etPhone);
        etPassword = findViewById(R.id.etPassword);
        ivTogglePassword = findViewById(R.id.ivTogglePassword);
        tvLogin    = findViewById(R.id.tvLogin);
        btnRegister = findViewById(R.id.btnRegister);
        tabPelanggan = findViewById(R.id.tabPelanggan);
        tabPemilik   = findViewById(R.id.tabPemilik);

        // Inisialisasi View Indikator Password
        bar1 = findViewById(R.id.bar1);
        bar2 = findViewById(R.id.bar2);
        bar3 = findViewById(R.id.bar3);
        bar4 = findViewById(R.id.bar4);
        tvStrengthLabel = findViewById(R.id.tvStrengthLabel);

        // Tombol Mata Password
        ivTogglePassword.setOnClickListener(v -> {
            if (isPasswordVisible) {
                etPassword.setInputType(InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_PASSWORD);
                ivTogglePassword.setImageResource(R.drawable.eye); // Pastikan nama drawable benar
                isPasswordVisible = false;
            } else {
                etPassword.setInputType(InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_VISIBLE_PASSWORD);
                ivTogglePassword.setImageResource(R.drawable.eye_close);
                isPasswordVisible = true;
            }
            etPassword.setSelection(etPassword.getText().length());
        });

        // TextWatcher untuk ngecek kekuatan password secara real-time
        etPassword.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {}

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
                checkPasswordStrength(s.toString());
            }

            @Override
            public void afterTextChanged(Editable s) {}
        });

        // Klik Tombol Register
        btnRegister.setOnClickListener(this::onClick);

        // Pindah ke halaman admin
        tabPemilik.setOnClickListener(v -> {
            startActivity(new Intent(RegisterActivity.this, RegisterAdminActivity.class));
            finish();
        });

        // Pindah ke halaman login
        tvLogin.setOnClickListener(v -> {
            startActivity(new Intent(RegisterActivity.this, LoginActivity.class));
            finish();
        });
    }

    // Fungsi Cek Kekuatan Password
    private void checkPasswordStrength(String password) {
        int score = 0;

        if (password.length() >= 8) score++;
        if (password.matches(".*[a-z].*") && password.matches(".*[A-Z].*")) score++;
        if (password.matches(".*[0-9].*")) score++;
        if (password.matches(".*[^A-Za-z0-9].*")) score++;

        // Warna Hex
        int colorGray   = Color.parseColor("#E5E7EB");
        int colorRed    = Color.parseColor("#EF4444");
        int colorOrange = Color.parseColor("#F97316");
        int colorYellow = Color.parseColor("#EAB308");
        int colorGreen  = Color.parseColor("#22C55E");

        // Reset semua ke abu-abu dulu
        bar1.setBackgroundColor(colorGray);
        bar2.setBackgroundColor(colorGray);
        bar3.setBackgroundColor(colorGray);
        bar4.setBackgroundColor(colorGray);

        if (password.isEmpty()) {
            tvStrengthLabel.setText("");
        } else if (score <= 1) {
            tvStrengthLabel.setText("Terlalu Lemah");
            tvStrengthLabel.setTextColor(colorRed);
            bar1.setBackgroundColor(colorRed);
        } else if (score == 2) {
            tvStrengthLabel.setText("Lumayan");
            tvStrengthLabel.setTextColor(colorOrange);
            bar1.setBackgroundColor(colorOrange);
            bar2.setBackgroundColor(colorOrange);
        } else if (score == 3) {
            tvStrengthLabel.setText("Kuat");
            tvStrengthLabel.setTextColor(colorYellow);
            bar1.setBackgroundColor(colorYellow);
            bar2.setBackgroundColor(colorYellow);
            bar3.setBackgroundColor(colorYellow);
        } else {
            tvStrengthLabel.setText("Sangat Kuat");
            tvStrengthLabel.setTextColor(colorGreen);
            bar1.setBackgroundColor(colorGreen);
            bar2.setBackgroundColor(colorGreen);
            bar3.setBackgroundColor(colorGreen);
            bar4.setBackgroundColor(colorGreen);
        }
    }

    private void onClick(View v) {
        String nama     = etName.getText().toString().trim();
        String email    = etEmail.getText().toString().trim();
        String phone    = etPhone.getText().toString().trim();
        String password = etPassword.getText().toString().trim();

        // Validasi dasar lokal
        if (nama.isEmpty()) {
            etName.setError("Nama tidak boleh kosong");
            etName.requestFocus();
            return;
        }
        if (email.isEmpty() || !Patterns.EMAIL_ADDRESS.matcher(email).matches()) {
            etEmail.setError("Email tidak valid");
            etEmail.requestFocus();
            return;
        }
        if (phone.isEmpty()) {
            etPhone.setError("Nomor HP tidak boleh kosong");
            etPhone.requestFocus();
            return;
        }
        if (password.length() < 6) {
            etPassword.setError("Password minimal 6 karakter");
            etPassword.requestFocus();
            return;
        }

        // Tampilan loading pada tombol
        btnRegister.setEnabled(false);
        btnRegister.setText("Memproses...");

        RegisterPelangganRequest request = new RegisterPelangganRequest(
                nama, email, phone, password, "pembeli"
        );

        ApiService apiService = ApiClient.getClient().create(ApiService.class);
        apiService.registerPelanggan(request).enqueue(new Callback<RegisterResponse>() {
            @Override
            public void onResponse(Call<RegisterResponse> call, Response<RegisterResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    // Berhasil -> Ubah tombol jadi hijau dan beri delay
                    btnRegister.setText("Berhasil! Mengalihkan...");
                    btnRegister.setBackgroundTintList(ColorStateList.valueOf(Color.parseColor("#22C55E"))); // Hijau

                    new Handler().postDelayed(() -> {
                        startActivity(new Intent(RegisterActivity.this, LoginActivity.class));
                        finish();
                    }, 2000);

                } else {
                    // Gagal -> Kembalikan tombol ke kondisi semula (Orange)
                    btnRegister.setEnabled(true);
                    btnRegister.setText("Buat Akun  ›");
                    btnRegister.setBackgroundTintList(ColorStateList.valueOf(Color.parseColor("#F97316")));

                    if (response.code() == 422) {
                        // Tangkap error validasi dari Laravel
                        try {
                            String errorBody = response.errorBody().string();
                            JSONObject json = new JSONObject(errorBody);

                            if (json.has("errors")) {
                                JSONObject errors = json.getJSONObject("errors");

                                // Tembak pesan error ke form spesifik
                                if (errors.has("name")) etName.setError(errors.getJSONArray("name").getString(0));
                                if (errors.has("email")) etEmail.setError(errors.getJSONArray("email").getString(0));
                                if (errors.has("phone")) etPhone.setError(errors.getJSONArray("phone").getString(0));
                                if (errors.has("password")) etPassword.setError(errors.getJSONArray("password").getString(0));
                            } else {
                                String msg = json.optString("message", "Data tidak valid");
                                Toast.makeText(RegisterActivity.this, msg, Toast.LENGTH_SHORT).show();
                            }
                        } catch (Exception e) {
                            Toast.makeText(RegisterActivity.this, "Gagal memproses peringatan validasi", Toast.LENGTH_SHORT).show();
                        }
                    } else {
                        Toast.makeText(RegisterActivity.this, "Terjadi kesalahan server", Toast.LENGTH_SHORT).show();
                    }
                }
            }

            @Override
            public void onFailure(Call<RegisterResponse> call, Throwable t) {
                // Gagal koneksi -> Kembalikan tombol ke kondisi semula
                btnRegister.setEnabled(true);
                btnRegister.setText("Buat Akun  ›");
                btnRegister.setBackgroundTintList(ColorStateList.valueOf(Color.parseColor("#F97316")));
                Toast.makeText(RegisterActivity.this, "Koneksi gagal: " + t.getMessage(), Toast.LENGTH_SHORT).show();
            }
        });
    }
}