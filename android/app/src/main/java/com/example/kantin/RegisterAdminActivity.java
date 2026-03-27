package com.example.kantin;

import android.content.Intent;
import android.content.res.ColorStateList;
import android.graphics.Color;
import android.os.Bundle;
import android.os.Handler;
import android.text.Editable;
import android.text.InputType;
import android.text.TextWatcher;
import android.view.View;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.example.kantin.model.request.RegisterAdminKantinRequest;
import com.example.kantin.model.response.BaseResponse;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;
import com.google.android.material.button.MaterialButton;

import org.json.JSONObject;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class RegisterAdminActivity extends AppCompatActivity {

    private EditText etNamaLengkap, etEmail, etPassword, etPhone, etNamaKantin;
    private ImageView ivTogglePassword;
    private MaterialButton btnRegister;
    private TextView tabPelanggan, tvLogin, tvStrengthLabel;
    private View bar1, bar2, bar3, bar4;
    private boolean isPasswordVisible = false;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_registeradminkantin);

        initializeViews();

        // Fitur Buka/Tutup Password
        ivTogglePassword.setOnClickListener(v -> togglePassword());

        // Pindah ke register pelanggan
        tabPelanggan.setOnClickListener(v -> {
            startActivity(new Intent(this, RegisterActivity.class));
            finish();
        });

        // Ke halaman login
        tvLogin.setOnClickListener(v -> {
            startActivity(new Intent(this, LoginActivity.class));
            finish();
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

        // Logika Tombol Daftar Admin
        btnRegister.setOnClickListener(v -> {
            validasiDanDaftar();
        });
    }

    private void initializeViews() {
        etNamaLengkap   = findViewById(R.id.etNamaLengkap);
        etEmail         = findViewById(R.id.etEmail);
        etPassword      = findViewById(R.id.etPassword);
        etPhone         = findViewById(R.id.etPhone);
        etNamaKantin    = findViewById(R.id.etNamaKantin);
        ivTogglePassword = findViewById(R.id.ivTogglePassword);
        btnRegister     = findViewById(R.id.btnRegister);
        tabPelanggan    = findViewById(R.id.tabPelanggan);
        tvLogin         = findViewById(R.id.tvLogin);

        // Inisialisasi View Indikator Password (pastikan ID ini ada di XML Register Admin juga)
        bar1 = findViewById(R.id.bar1);
        bar2 = findViewById(R.id.bar2);
        bar3 = findViewById(R.id.bar3);
        bar4 = findViewById(R.id.bar4);
        tvStrengthLabel = findViewById(R.id.tvStrengthLabel);
    }

    private void togglePassword() {
        if (isPasswordVisible) {
            etPassword.setInputType(InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_PASSWORD);
            ivTogglePassword.setImageResource(R.drawable.eye_close); // Pastikan drawable ini ada
            isPasswordVisible = false;
        } else {
            etPassword.setInputType(InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_VISIBLE_PASSWORD);
            ivTogglePassword.setImageResource(R.drawable.eye);
            isPasswordVisible = true;
        }
        etPassword.setSelection(etPassword.getText().length());
    }

    // Fungsi Cek Kekuatan Password
    private void checkPasswordStrength(String password) {
        int score = 0;

        if (password.length() >= 8) score++;
        if (password.matches(".*[a-z].*") && password.matches(".*[A-Z].*")) score++;
        if (password.matches(".*[0-9].*")) score++;
        if (password.matches(".*[^A-Za-z0-9].*")) score++;

        int colorGray   = Color.parseColor("#E5E7EB");
        int colorRed    = Color.parseColor("#EF4444");
        int colorOrange = Color.parseColor("#F97316");
        int colorYellow = Color.parseColor("#EAB308");
        int colorGreen  = Color.parseColor("#22C55E");

        // Reset semua ke abu-abu dulu
        if (bar1 != null) bar1.setBackgroundColor(colorGray);
        if (bar2 != null) bar2.setBackgroundColor(colorGray);
        if (bar3 != null) bar3.setBackgroundColor(colorGray);
        if (bar4 != null) bar4.setBackgroundColor(colorGray);

        if (tvStrengthLabel != null) {
            if (password.isEmpty()) {
                tvStrengthLabel.setText("");
            } else if (score <= 1) {
                tvStrengthLabel.setText("Terlalu Lemah");
                tvStrengthLabel.setTextColor(colorRed);
                if (bar1 != null) bar1.setBackgroundColor(colorRed);
            } else if (score == 2) {
                tvStrengthLabel.setText("Lumayan");
                tvStrengthLabel.setTextColor(colorOrange);
                if (bar1 != null) bar1.setBackgroundColor(colorOrange);
                if (bar2 != null) bar2.setBackgroundColor(colorOrange);
            } else if (score == 3) {
                tvStrengthLabel.setText("Kuat");
                tvStrengthLabel.setTextColor(colorYellow);
                if (bar1 != null) bar1.setBackgroundColor(colorYellow);
                if (bar2 != null) bar2.setBackgroundColor(colorYellow);
                if (bar3 != null) bar3.setBackgroundColor(colorYellow);
            } else {
                tvStrengthLabel.setText("Sangat Kuat");
                tvStrengthLabel.setTextColor(colorGreen);
                if (bar1 != null) bar1.setBackgroundColor(colorGreen);
                if (bar2 != null) bar2.setBackgroundColor(colorGreen);
                if (bar3 != null) bar3.setBackgroundColor(colorGreen);
                if (bar4 != null) bar4.setBackgroundColor(colorGreen);
            }
        }
    }

    private void validasiDanDaftar() {
        String nama = etNamaLengkap.getText().toString().trim();
        String email = etEmail.getText().toString().trim();
        String password = etPassword.getText().toString().trim();
        String phone = etPhone.getText().toString().trim();
        String namaKantin = etNamaKantin.getText().toString().trim();

        if (nama.isEmpty()) {
            etNamaLengkap.setError("Nama tidak boleh kosong");
            etNamaLengkap.requestFocus();
            return;
        }
        if (email.isEmpty()) {
            etEmail.setError("Email tidak boleh kosong");
            etEmail.requestFocus();
            return;
        }
        if (namaKantin.isEmpty()) {
            etNamaKantin.setError("Nama Kantin tidak boleh kosong");
            etNamaKantin.requestFocus();
            return;
        }
        if (password.length() < 6) {
            etPassword.setError("Password minimal 6 karakter");
            etPassword.requestFocus();
            return;
        }

        prosesRegisterAdmin(nama, email, password, phone, namaKantin);
    }

    private void prosesRegisterAdmin(String nama, String email, String password, String phone, String namaKantin) {
        btnRegister.setEnabled(false);
        btnRegister.setText("Memproses...");

        ApiService apiService = ApiClient.getClient().create(ApiService.class);
        RegisterAdminKantinRequest request = new RegisterAdminKantinRequest(nama, email, password, phone, namaKantin);

        apiService.registerAdminKantin(request).enqueue(new Callback<BaseResponse>() {
            @Override
            public void onResponse(Call<BaseResponse> call, Response<BaseResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    // Tampilan Sukses pada Tombol
                    btnRegister.setText("Berhasil! Mengalihkan...");
                    btnRegister.setBackgroundTintList(ColorStateList.valueOf(Color.parseColor("#22C55E"))); // Hijau

                    // Pesan khusus admin (harus nunggu verifikasi) ditampilkan via Toast yang agak panjang
                    Toast.makeText(RegisterAdminActivity.this, "Pendaftaran Berhasil! Silakan tunggu verifikasi admin untuk masuk.", Toast.LENGTH_LONG).show();

                    // Delay 2.5 Detik
                    new Handler().postDelayed(() -> {
                        startActivity(new Intent(RegisterAdminActivity.this, LoginActivity.class));
                        finish();
                    }, 2500);

                } else {
                    // Kalau gagal, kembalikan tombol
                    btnRegister.setEnabled(true);
                    btnRegister.setText("DAFTAR SEKARANG");
                    btnRegister.setBackgroundTintList(ColorStateList.valueOf(Color.parseColor("#F97316"))); // Orange

                    // Tangkap validasi 422 dari Laravel
                    if (response.code() == 422) {
                        try {
                            String errorBody = response.errorBody().string();
                            JSONObject json = new JSONObject(errorBody);

                            if (json.has("errors")) {
                                JSONObject errors = json.getJSONObject("errors");

                                if (errors.has("name")) etNamaLengkap.setError(errors.getJSONArray("name").getString(0));
                                if (errors.has("email")) etEmail.setError(errors.getJSONArray("email").getString(0));
                                if (errors.has("phone")) etPhone.setError(errors.getJSONArray("phone").getString(0));
                                if (errors.has("password")) etPassword.setError(errors.getJSONArray("password").getString(0));
                                // Asumsi field nama kantin di Laravel adalah "canteen_name". Ganti kalau berbeda!
                                if (errors.has("canteen_name")) etNamaKantin.setError(errors.getJSONArray("canteen_name").getString(0));
                            } else {
                                String msg = json.optString("message", "Data tidak valid");
                                Toast.makeText(RegisterAdminActivity.this, msg, Toast.LENGTH_SHORT).show();
                            }
                        } catch (Exception e) {
                            Toast.makeText(RegisterAdminActivity.this, "Gagal memproses peringatan validasi", Toast.LENGTH_SHORT).show();
                        }
                    } else {
                        Toast.makeText(RegisterAdminActivity.this, "Terjadi kesalahan server", Toast.LENGTH_SHORT).show();
                    }
                }
            }

            @Override
            public void onFailure(Call<BaseResponse> call, Throwable t) {
                btnRegister.setEnabled(true);
                btnRegister.setText("DAFTAR SEKARANG");
                btnRegister.setBackgroundTintList(ColorStateList.valueOf(Color.parseColor("#F97316"))); // Orange
                Toast.makeText(RegisterAdminActivity.this, "Koneksi gagal: " + t.getMessage(), Toast.LENGTH_SHORT).show();
            }
        });
    }
}