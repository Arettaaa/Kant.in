package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.text.InputType;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import androidx.appcompat.app.AppCompatActivity;

public class ResetPasswordActivity extends AppCompatActivity {

    private ImageView btnBack;
    private Button btnSubmit;
    private EditText etNewPassword, etConfirmPassword;
    private ImageView ivToggleNewPassword, ivToggleConfirmPassword; // Tambahan variabel

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        if (getSupportActionBar() != null) getSupportActionBar().hide();

        setContentView(R.layout.activity_reset_password);

        btnBack = findViewById(R.id.btnBack);
        btnSubmit = findViewById(R.id.btnSubmit);
        etNewPassword = findViewById(R.id.etNewPassword);
        etConfirmPassword = findViewById(R.id.etConfirmPassword);

        // Inisialisasi ikon mata
        ivToggleNewPassword = findViewById(R.id.ivToggleNewPassword);
        ivToggleConfirmPassword = findViewById(R.id.ivToggleConfirmPassword);

        btnBack.setOnClickListener(v -> finish());

        // --- AKSI KLIK UNTUK IKON MATA ---
        ivToggleNewPassword.setOnClickListener(v -> togglePassword(etNewPassword, ivToggleNewPassword));
        ivToggleConfirmPassword.setOnClickListener(v -> togglePassword(etConfirmPassword, ivToggleConfirmPassword));

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

    // --- FUNGSI UNTUK MENGUBAH VISIBILITAS PASSWORD ---
    private void togglePassword(EditText editText, ImageView eyeIcon) {
        if (editText.getInputType() == (InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_PASSWORD)) {
            // Jika sedang tertutup -> Buka password
            editText.setInputType(InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_VISIBLE_PASSWORD);
            eyeIcon.setImageResource(R.drawable.eye_close); // Pastikan Anda punya gambar eye_close.xml atau .png
            eyeIcon.setAlpha(1.0f); // Bikin ikon sedikit lebih terang saat diklik (opsional)
        } else {
            // Jika sedang terbuka -> Tutup password
            editText.setInputType(InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_PASSWORD);
            eyeIcon.setImageResource(R.drawable.eye);
            eyeIcon.setAlpha(0.5f); // Kembalikan transparansi
        }

        // Pindahkan kursor ke ujung teks agar user tidak bingung saat lanjut mengetik
        editText.setSelection(editText.getText().length());
    }
}