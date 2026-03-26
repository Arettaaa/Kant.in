package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.text.InputType;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;

import com.example.kantin.model.request.RegisterAdminKantinRequest;
import com.example.kantin.model.response.BaseResponse;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;
import com.google.android.material.button.MaterialButton;
import com.google.android.material.dialog.MaterialAlertDialogBuilder;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class RegisterAdminActivity extends AppCompatActivity {

    private EditText etNamaLengkap, etEmail, etPassword, etPhone, etNamaKantin;
    private ImageView ivTogglePassword;
    private MaterialButton btnRegister;
    private TextView tabPelanggan, tvLogin;
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

        // Logika Tombol Daftar Admin
        btnRegister.setOnClickListener(v -> {
            validasiDanDaftar();
        });
    }

    private void initializeViews() {
        etNamaLengkap   = findViewById(R.id.etNamaLengkap); // Sesuaikan ID di XML
        etEmail         = findViewById(R.id.etEmail);
        etPassword      = findViewById(R.id.etPassword);
        etPhone         = findViewById(R.id.etPhone);
        etNamaKantin    = findViewById(R.id.etNamaKantin); // Khusus Admin
        ivTogglePassword = findViewById(R.id.ivTogglePassword);
        btnRegister     = findViewById(R.id.btnRegister);
        tabPelanggan    = findViewById(R.id.tabPelanggan);
        tvLogin         = findViewById(R.id.tvLogin);
    }

    private void togglePassword() {
        if (isPasswordVisible) {
            etPassword.setInputType(InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_PASSWORD);
            ivTogglePassword.setImageResource(R.drawable.eye_close);
            isPasswordVisible = false;
        } else {
            etPassword.setInputType(InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_VISIBLE_PASSWORD);
            ivTogglePassword.setImageResource(R.drawable.eye);
            isPasswordVisible = true;
        }
        etPassword.setSelection(etPassword.getText().length());
    }

    private void validasiDanDaftar() {
        String nama = etNamaLengkap.getText().toString().trim();
        String email = etEmail.getText().toString().trim();
        String password = etPassword.getText().toString().trim();
        String phone = etPhone.getText().toString().trim();
        String namaKantin = etNamaKantin.getText().toString().trim();

        if (nama.isEmpty() || email.isEmpty() || password.isEmpty() || namaKantin.isEmpty()) {
            tampilkanError("Mohon lengkapi semua data yang wajib diisi (Nama, Email, Password, dan Nama Kantin).");
            return;
        }

        if (password.length() < 6) {
            etPassword.setError("Password minimal 6 karakter");
            return;
        }

        // Jika validasi sukses, panggil API
        prosesRegisterAdmin(nama, email, password, phone, namaKantin);
    }

    private void prosesRegisterAdmin(String nama, String email, String password, String phone, String namaKantin) {
        btnRegister.setEnabled(false);
        btnRegister.setText("Memproses Pendaftaran...");

        ApiService apiService = ApiClient.getClient().create(ApiService.class);
        RegisterAdminKantinRequest request = new RegisterAdminKantinRequest(nama, email, password, phone, namaKantin);

        apiService.registerAdminKantin(request).enqueue(new Callback<BaseResponse>() {
            @Override
            public void onResponse(Call<BaseResponse> call, Response<BaseResponse> response) {
                btnRegister.setEnabled(true);
                btnRegister.setText("DAFTAR SEKARANG");

                if (response.isSuccessful()) {
                    tampilkanDialogSukses();
                } else {
                    tampilkanError("Pendaftaran gagal. Pastikan email belum terdaftar atau periksa kembali data Anda.");
                }
            }

            @Override
            public void onFailure(Call<BaseResponse> call, Throwable t) {
                btnRegister.setEnabled(true);
                btnRegister.setText("DAFTAR SEKARANG");
                tampilkanError("Terjadi kesalahan koneksi: " + t.getMessage());
            }
        });
    }

    private void tampilkanDialogSukses() {
        new MaterialAlertDialogBuilder(this)
                .setTitle("Pendaftaran Berhasil")
                .setMessage("Akun Admin Kantin Anda telah berhasil dibuat. Silakan tunggu verifikasi dari Admin Global sebelum Anda dapat masuk ke aplikasi.")
                .setPositiveButton("Ke Halaman Login", (dialog, which) -> {
                    startActivity(new Intent(this, LoginActivity.class));
                    finish();
                })
                .setCancelable(false)
                .show();
    }

    private void tampilkanError(String pesan) {
        new MaterialAlertDialogBuilder(this)
                .setTitle("Pendaftaran Gagal")
                .setMessage(pesan)
                .setPositiveButton("Tutup", null)
                .show();
    }
}