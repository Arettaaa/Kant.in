package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.text.InputType;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;
import androidx.appcompat.app.AppCompatActivity;
import com.example.kantin.model.request.ForgotPasswordRequest;
import com.example.kantin.model.request.ResetPasswordRequest;
import com.example.kantin.model.response.BaseResponse;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;

public class ResetPasswordActivity extends AppCompatActivity {

    private ImageView btnBack;
    private Button btnSubmit;
    private EditText etNewPassword, etConfirmPassword;
    private ImageView ivToggleNewPassword, ivToggleConfirmPassword; // Tambahan variabel
    private View bar1, bar2, bar3, bar4;
    private TextView tvStrengthLabel;

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


        String emailFromIntent = getIntent().getStringExtra("email");

        // --- AKSI KLIK UNTUK IKON MATA ---
        ivToggleNewPassword.setOnClickListener(v -> togglePassword(etNewPassword, ivToggleNewPassword));
        ivToggleConfirmPassword.setOnClickListener(v -> togglePassword(etConfirmPassword, ivToggleConfirmPassword));

        bar1 = findViewById(R.id.bar1);
        bar2 = findViewById(R.id.bar2);
        bar3 = findViewById(R.id.bar3);
        bar4 = findViewById(R.id.bar4);
        tvStrengthLabel = findViewById(R.id.tvStrengthLabel);

        etNewPassword.addTextChangedListener(new android.text.TextWatcher() {
            @Override public void beforeTextChanged(CharSequence s, int start, int count, int after) {}
            @Override public void afterTextChanged(android.text.Editable s) {}
            @Override public void onTextChanged(CharSequence s, int start, int before, int count) {
                checkStrength(s.toString());
            }
        });

        btnSubmit.setOnClickListener(v -> {
            String newPassword     = etNewPassword.getText().toString().trim();
            String confirmPassword = etConfirmPassword.getText().toString().trim();

            if (newPassword.isEmpty()) {
                etNewPassword.setError("Kata sandi tidak boleh kosong");
                etNewPassword.requestFocus(); return;
            }
            if (newPassword.length() < 6) {
                etNewPassword.setError("Kata sandi minimal 6 karakter");
                etNewPassword.requestFocus(); return;
            }
            if (confirmPassword.isEmpty()) {
                etConfirmPassword.setError("Konfirmasi kata sandi tidak boleh kosong");
                etConfirmPassword.requestFocus(); return;
            }
            if (!newPassword.equals(confirmPassword)) {
                etConfirmPassword.setError("Kata sandi tidak cocok!");
                etConfirmPassword.requestFocus(); return;
            }

            btnSubmit.setEnabled(false);

            ApiService api = ApiClient.getClient().create(ApiService.class);
            api.resetPassword(new ResetPasswordRequest(emailFromIntent, newPassword, confirmPassword))
                    .enqueue(new retrofit2.Callback<BaseResponse>() {
                        @Override
                        public void onResponse(retrofit2.Call<BaseResponse> call, retrofit2.Response<BaseResponse> response) {
                            btnSubmit.setEnabled(true);
                            if (response.isSuccessful()) {
                                Intent intent = new Intent(ResetPasswordActivity.this, PasswordBerhasilActivity.class);
                                startActivity(intent);
                                finish();
                            } else {
                                etNewPassword.setError("Gagal menyimpan kata sandi, coba lagi");
                            }
                        }

                        @Override
                        public void onFailure(retrofit2.Call<BaseResponse> call, Throwable t) {
                            btnSubmit.setEnabled(true);
                            etNewPassword.setError("Koneksi gagal, coba lagi");
                        }
                    });
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

    private void checkStrength(String p) {
        int score = 0;
        if (p.length() >= 8) score++;
        if (p.matches(".*[A-Z].*") && p.matches(".*[a-z].*")) score++;
        if (p.matches(".*[0-9].*")) score++;
        if (p.matches(".*[^A-Za-z0-9].*")) score++;

        int gray = android.graphics.Color.parseColor("#E5E7EB");
        int red = android.graphics.Color.parseColor("#EF4444");
        int green = android.graphics.Color.parseColor("#22C55E");

        bar1.setBackgroundColor(gray); bar2.setBackgroundColor(gray);
        bar3.setBackgroundColor(gray); bar4.setBackgroundColor(gray);

        if (p.isEmpty()) {
            tvStrengthLabel.setText("");
        } else {
            tvStrengthLabel.setText(score < 2 ? "Lemah" : score < 4 ? "Kuat" : "Sangat Kuat");
            tvStrengthLabel.setTextColor(score < 2 ? red : green);
            if (score >= 1) bar1.setBackgroundColor(score < 2 ? red : green);
            if (score >= 2) bar2.setBackgroundColor(green);
            if (score >= 3) bar3.setBackgroundColor(green);
            if (score >= 4) bar4.setBackgroundColor(green);
        }
    }
}