package com.example.kantin;

import android.graphics.Color;
import android.graphics.Typeface;
import android.os.Bundle;
import android.text.Editable;
import android.text.InputType;
import android.text.TextWatcher;
import android.view.View;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;
import androidx.appcompat.app.AppCompatActivity;

import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;
import com.example.kantin.model.response.BaseResponse;
import com.example.kantin.utils.SessionManager;
import com.google.android.material.button.MaterialButton;

import okhttp3.MediaType;
import okhttp3.RequestBody;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class KeamananPelangganActivity extends AppCompatActivity {

    private EditText etOldPassword, etNewPassword, etConfirmPassword;
    private ImageView ivToggleOld, ivToggleNew, ivToggleConfirm, btnBack;
    private View bar1, bar2, bar3, bar4;
    private TextView tvStrengthLabel;
    private MaterialButton btnSimpan;
    private SessionManager sessionManager;
    private boolean isOldVis = false, isNewVis = false, isConfVis = false;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_keamananpelanggan);

        sessionManager = new SessionManager(this);

        // Inisialisasi View
        btnBack = findViewById(R.id.btnBack);
        btnSimpan = findViewById(R.id.btnSimpan);
        etOldPassword = findViewById(R.id.etOldPassword);
        etNewPassword = findViewById(R.id.etNewPassword);
        etConfirmPassword = findViewById(R.id.etConfirmPassword);
        ivToggleOld = findViewById(R.id.ivToggleOld);
        ivToggleNew = findViewById(R.id.ivToggleNew);
        ivToggleConfirm = findViewById(R.id.ivToggleConfirm);

        bar1 = findViewById(R.id.bar1); bar2 = findViewById(R.id.bar2);
        bar3 = findViewById(R.id.bar3); bar4 = findViewById(R.id.bar4);
        tvStrengthLabel = findViewById(R.id.tvStrengthLabel);

        // Setup Click Listeners
        ivToggleOld.setOnClickListener(v -> togglePassword(etOldPassword, ivToggleOld, 1));
        ivToggleNew.setOnClickListener(v -> togglePassword(etNewPassword, ivToggleNew, 2));
        ivToggleConfirm.setOnClickListener(v -> togglePassword(etConfirmPassword, ivToggleConfirm, 3));

        etNewPassword.addTextChangedListener(new TextWatcher() {
            @Override public void onTextChanged(CharSequence s, int start, int before, int count) {
                checkStrength(s.toString());
            }
            @Override public void beforeTextChanged(CharSequence s, int start, int count, int after) {}
            @Override public void afterTextChanged(Editable s) {}
        });

        btnBack.setOnClickListener(v -> finish());
        btnSimpan.setOnClickListener(v -> validateAndSave());
    }

    private void togglePassword(EditText et, ImageView iv, int mode) {
        boolean visible;
        if (mode == 1) { isOldVis = !isOldVis; visible = isOldVis; }
        else if (mode == 2) { isNewVis = !isNewVis; visible = isNewVis; }
        else { isConfVis = !isConfVis; visible = isConfVis; }

        // Simpan Typeface agar font Inter tidak berubah jadi Monospace
        Typeface existingTypeface = et.getTypeface();

        if (visible) {
            et.setInputType(InputType.TYPE_TEXT_VARIATION_VISIBLE_PASSWORD);
        } else {
            et.setInputType(InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_PASSWORD);
        }

        et.setTypeface(existingTypeface);
        iv.setImageResource(visible ? R.drawable.eye : R.drawable.eye_close);
        et.setSelection(et.getText().length());
    }

    private void checkStrength(String p) {
        int score = 0;
        if (p.length() >= 8) score++;
        if (p.matches(".*[A-Z].*") && p.matches(".*[a-z].*")) score++;
        if (p.matches(".*[0-9].*")) score++;
        if (p.matches(".*[^A-Za-z0-9].*")) score++;

        int gray = Color.parseColor("#E5E7EB"), red = Color.parseColor("#EF4444"), green = Color.parseColor("#22C55E");
        bar1.setBackgroundColor(gray); bar2.setBackgroundColor(gray); bar3.setBackgroundColor(gray); bar4.setBackgroundColor(gray);

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

    private void validateAndSave() {
        String oldP = etOldPassword.getText().toString().trim();
        String newP = etNewPassword.getText().toString().trim();
        String confP = etConfirmPassword.getText().toString().trim();

        if (oldP.isEmpty() || newP.isEmpty()) {
            Toast.makeText(this, "Semua kolom wajib diisi!", Toast.LENGTH_SHORT).show();
            return;
        }
        if (newP.length() < 8) {
            etNewPassword.setError("Minimal 8 karakter");
            return;
        }
        if (!newP.equals(confP)) {
            etConfirmPassword.setError("Konfirmasi sandi tidak cocok");
            return;
        }

        saveToServer(oldP, newP, confP);
    }

    private void saveToServer(String oldP, String newP, String confP) {
        btnSimpan.setEnabled(false);
        btnSimpan.setText("Menyimpan...");

        // Buat RequestBody untuk Multipart
        RequestBody rbOld = RequestBody.create(MediaType.parse("text/plain"), oldP);
        RequestBody rbNew = RequestBody.create(MediaType.parse("text/plain"), newP);
        RequestBody rbConf = RequestBody.create(MediaType.parse("text/plain"), confP);

        String token = "Bearer " + sessionManager.getToken();

        // Pastikan nama method di ApiService adalah updatePasswordBuyers (pakai 's')
        ApiClient.getClient().create(ApiService.class)
                .updatePasswordBuyers(token, rbOld, rbNew, rbConf)
                .enqueue(new Callback<BaseResponse>() {
                    @Override
                    public void onResponse(Call<BaseResponse> call, Response<BaseResponse> response) {
                        btnSimpan.setEnabled(true);
                        btnSimpan.setText("Simpan Perubahan");

                        if (response.isSuccessful()) {
                            Toast.makeText(KeamananPelangganActivity.this, "Kata sandi berhasil diperbarui!", Toast.LENGTH_SHORT).show();
                            finish();
                        } else {
                            // Jika Laravel kirim error (misal sandi lama salah)
                            Toast.makeText(KeamananPelangganActivity.this, "Gagal! Pastikan sandi lama benar.", Toast.LENGTH_LONG).show();
                        }
                    }

                    @Override
                    public void onFailure(Call<BaseResponse> call, Throwable t) {
                        btnSimpan.setEnabled(true);
                        btnSimpan.setText("Simpan Perubahan");
                        Toast.makeText(KeamananPelangganActivity.this, "Koneksi bermasalah: " + t.getMessage(), Toast.LENGTH_SHORT).show();
                    }
                });
    }
}