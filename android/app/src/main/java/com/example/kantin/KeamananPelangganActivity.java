package com.example.kantin;

import android.os.Bundle;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Toast;
import androidx.appcompat.app.AppCompatActivity;
import com.google.android.material.button.MaterialButton;

public class KeamananPelangganActivity extends AppCompatActivity {

    private ImageView btnBack;
    private EditText etOldPassword, etNewPassword, etConfirmPassword;
    private MaterialButton btnBatal, btnSimpan;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        // Sembunyikan ActionBar
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_keamananpelanggan);

        // 1. Inisialisasi View
        btnBack = findViewById(R.id.btnBack);
        etOldPassword = findViewById(R.id.etOldPassword);
        etNewPassword = findViewById(R.id.etNewPassword);
        etConfirmPassword = findViewById(R.id.etConfirmPassword);
        btnBatal = findViewById(R.id.btnBatal);
        btnSimpan = findViewById(R.id.btnSimpan);

        // 2. Aksi Tombol Kembali & Batal (Fungsinya sama: Tutup Halaman)
        btnBack.setOnClickListener(v -> finish());
        btnBatal.setOnClickListener(v -> finish());

        // 3. Aksi Tombol Simpan (Ganti Password)
        btnSimpan.setOnClickListener(v -> {
            String oldPass = etOldPassword.getText().toString();
            String newPass = etNewPassword.getText().toString();
            String confirmPass = etConfirmPassword.getText().toString();

            // Validasi Sederhana
            if (oldPass.isEmpty() || newPass.isEmpty() || confirmPass.isEmpty()) {
                Toast.makeText(this, "Semua kolom harus diisi!", Toast.LENGTH_SHORT).show();
            } else if (newPass.length() < 6) {
                Toast.makeText(this, "Kata sandi baru minimal 6 karakter!", Toast.LENGTH_SHORT).show();
            } else if (!newPass.equals(confirmPass)) {
                Toast.makeText(this, "Konfirmasi kata sandi tidak cocok!", Toast.LENGTH_SHORT).show();
            } else {
                // Simulasi Berhasil
                Toast.makeText(this, "Kata sandi berhasil diperbarui!", Toast.LENGTH_LONG).show();
                finish(); // Tutup halaman setelah simpan
            }
        });
    }
}