package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.text.InputType;
import android.util.Log;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;

import com.example.kantin.model.request.LoginRequest;
import com.example.kantin.model.response.LoginResponse;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;
import com.example.kantin.utils.SessionManager;
import com.google.android.material.button.MaterialButton;
import com.google.android.material.dialog.MaterialAlertDialogBuilder;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class LoginActivity extends AppCompatActivity {

    private EditText etEmail, etPassword;
    private ImageView ivTogglePassword;
    private TextView tvForgotPassword, tvRegister;
    private MaterialButton btnLogin;
    private boolean isPasswordVisible = false;
    private SessionManager sessionManager;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        // Menyembunyikan Action Bar bawaan agar tampilan lebih bersih
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_login);

        // 1. Inisialisasi SessionManager
        sessionManager = new SessionManager(this);

        // Memeriksa status login sebelumnya (Auto-Login)
        if (sessionManager.isLoggedIn()) {
            pindahKeBeranda();
        }

        // 2. Inisialisasi View
        initViews();

        // 3. Fitur Buka/Tutup Password (Mata)
        ivTogglePassword.setOnClickListener(v -> togglePassword());

        // 4. Logika Tombol Login
        btnLogin.setOnClickListener(v -> {
            String email = etEmail.getText().toString().trim();
            String password = etPassword.getText().toString().trim();

            if (validasiInput(email, password)) {
                prosesLogin(email, password);
            }
        });

        // 5. Navigasi ke Register & Lupa Password
        tvRegister.setOnClickListener(v -> startActivity(new Intent(this, RegisterActivity.class)));
        tvForgotPassword.setOnClickListener(v -> startActivity(new Intent(this, LupaPasswordActivity.class)));
    }

    private void initViews() {
        etEmail          = findViewById(R.id.etEmail);
        etPassword       = findViewById(R.id.etPassword);
        ivTogglePassword = findViewById(R.id.ivTogglePassword);
        tvForgotPassword = findViewById(R.id.tvForgotPassword);
        tvRegister       = findViewById(R.id.tvRegister);
        btnLogin         = findViewById(R.id.btnLogin);
    }

    private void togglePassword() {
        if (isPasswordVisible) {
            etPassword.setInputType(InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_PASSWORD);
            ivTogglePassword.setImageResource(R.drawable.eye);
            isPasswordVisible = false;
        } else {
            etPassword.setInputType(InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_VISIBLE_PASSWORD);
            ivTogglePassword.setImageResource(R.drawable.eye_close);
            isPasswordVisible = true;
        }
        etPassword.setSelection(etPassword.getText().length());
    }

    private boolean validasiInput(String email, String password) {
        if (email.isEmpty()) {
            etEmail.setError("Email tidak boleh kosong");
            return false;
        }
        if (password.length() < 6) {
            etPassword.setError("Password minimal 6 karakter");
            return false;
        }
        return true;
    }

    private void prosesLogin(String email, String password) {
        // Tampilkan loading di tombol
        btnLogin.setEnabled(false);
        btnLogin.setText("Sedang memproses...");

        ApiService apiService = ApiClient.getClient().create(ApiService.class);
        LoginRequest request = new LoginRequest(email, password);

        apiService.login(request).enqueue(new Callback<LoginResponse>() {
            @Override
            public void onResponse(Call<LoginResponse> call, Response<LoginResponse> response) {
                btnLogin.setEnabled(true);
                btnLogin.setText("MASUK");

                if (response.isSuccessful() && response.body() != null) {
                    LoginResponse data = response.body();
                    LoginResponse.UserData user = data.getUser();

                    // 1. CEK STATUS AKTIF DULU (Khusus Admin Kantin)
                    // Jika dia Admin tapi isActive-nya FALSE (atau 0)
                    if (user.getRole().equalsIgnoreCase("admin_kantin") && !user.isActive()) {

                        // JANGAN simpan session, biarkan dia tetap logout
                        // Tampilkan dialog pemberitahuan saja
                        new MaterialAlertDialogBuilder(LoginActivity.this)
                                .setTitle("Akun Belum Aktif")
                                .setMessage("Maaf, akun Admin Kantin Anda belum divalidasi atau belum menyelesaikan pembayaran. Silakan hubungi Admin Global.")
                                .setPositiveButton("Mengerti", null) // Cuma tutup dialog, tetap di Login
                                .show();

                        // Aktifkan kembali tombol login agar bisa coba akun lain
                        btnLogin.setEnabled(true);
                        btnLogin.setText("MASUK");

                    } else {
                        // 2. JIKA PELANGGAN ATAU ADMIN YANG SUDAH AKTIF
                        // Simpan semua data ke SessionManager
                        sessionManager.saveSession(
                                data.getToken(),
                                user.getId(),
                                user.getCanteenId(),
                                user.getRole()
                        );
                        sessionManager.saveUserInfo(user.getName(), user.getEmail(), user.getPhone());
                        sessionManager.savePhotoUrl(user.getPhotoProfile());

                        // Langsung tampilkan sukses dan pindah ke Dashboard/Beranda
                        tampilkanDialogSukses(user.getName());
                    }

                } else {
                    tampilkanDialogError("Login Gagal", "Email atau password salah.");
                }
            }

            @Override
            public void onFailure(Call<LoginResponse> call, Throwable t) {
                btnLogin.setEnabled(true);
                btnLogin.setText("MASUK");
                tampilkanDialogError("Masalah Koneksi", "Gagal terhubung ke server. Pastikan internetmu aktif ya!");
                Log.e("LOGIN_ERROR", t.getMessage());
            }
        });
    }

    // --- DIALOG CANTIK & INDAH ---

    // Dialog khusus untuk Admin yang belum di-approve
    private void tampilkanDialogMenungguValidasi() {
        new MaterialAlertDialogBuilder(this)
                .setTitle("Akun Belum Aktif")
                .setMessage("Pendaftaran Anda sebagai Admin Kantin sedang dalam proses verifikasi oleh Admin Global. Mohon tunggu beberapa saat ya!")
                .setPositiveButton("Cek Status", (dialog, which) -> {
                    // Arahkan ke halaman tunggu
                    startActivity(new Intent(LoginActivity.this, ValidasiAdminActivity.class));
                    finish();
                })
                .setCancelable(false) // User wajib klik tombol
                .show();
    }

    private void tampilkanDialogSukses(String nama) {
        new MaterialAlertDialogBuilder(this)
                .setTitle("Berhasil Masuk")
                .setMessage("Selamat datang, " + nama + "! Klik tombol di bawah untuk lanjut ke Beranda.")
                .setPositiveButton("Lanjut", (dialog, which) -> pindahKeBeranda())
                .setCancelable(false)
                .show();
    }

    private void tampilkanDialogError(String judul, String pesan) {
        new MaterialAlertDialogBuilder(this)
                .setTitle(judul)
                .setMessage(pesan)
                .setPositiveButton("Coba Lagi", null)
                .show();
    }

    private void pindahKeBeranda() {
        // Karena session hanya disimpan jika sudah Aktif/Pelanggan,
        // kita cukup cek role-nya saja di sini.
        if (sessionManager.isAdminKantin()) {
            startActivity(new Intent(LoginActivity.this, ActivityAdminDashboard.class));
        } else {
            startActivity(new Intent(LoginActivity.this, BerandaPelangganActivity.class));
        }
        finish();
    }
}