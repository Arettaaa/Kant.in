package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.TextView;
import androidx.appcompat.app.AppCompatActivity;
import com.example.kantin.model.request.ForgotPasswordRequest;
import com.example.kantin.model.response.BaseResponse;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;

public class LupaPasswordActivity extends AppCompatActivity {

    private ImageView btnBack;
    private Button btnSubmit;
    private TextView tvLoginLink;
    private EditText etEmailInput;
    private ProgressBar progressBar; // ← deklarasi di sini, bukan di dalam method

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_lupa_password);

        btnBack      = findViewById(R.id.btnBack);
        btnSubmit    = findViewById(R.id.btnSubmit);
        tvLoginLink  = findViewById(R.id.tvLoginLink);
        etEmailInput = findViewById(R.id.etEmailInput);
        progressBar  = findViewById(R.id.progressBar); // ← pastikan id ini ada di XML

        btnBack.setOnClickListener(v -> finish());

        tvLoginLink.setOnClickListener(v -> {
            Intent intent = new Intent(LupaPasswordActivity.this, LoginActivity.class);
            intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
            startActivity(intent);
            finish();
        });

        btnSubmit.setOnClickListener(v -> {
            String email = etEmailInput.getText().toString().trim();

            if (email.isEmpty()) {
                etEmailInput.setError("Email atau No HP tidak boleh kosong");
                etEmailInput.requestFocus();
                return;
            }

            btnSubmit.setEnabled(false);
            progressBar.setVisibility(View.VISIBLE);

            ApiService api = ApiClient.getClient().create(ApiService.class);
            api.forgotPassword(new ForgotPasswordRequest(email))
                    .enqueue(new retrofit2.Callback<BaseResponse>() {
                        @Override
                        public void onResponse(retrofit2.Call<BaseResponse> call, retrofit2.Response<BaseResponse> response) {
                            progressBar.setVisibility(View.GONE);
                            btnSubmit.setEnabled(true);

                            if (response.isSuccessful()) {
                                Intent intent = new Intent(LupaPasswordActivity.this, ResetPasswordActivity.class);
                                intent.putExtra("email", email);
                                startActivity(intent);
                            } else {
                                etEmailInput.setError("Email atau No HP tidak ditemukan");
                                etEmailInput.requestFocus();
                            }
                        }

                        @Override
                        public void onFailure(retrofit2.Call<BaseResponse> call, Throwable t) {
                            progressBar.setVisibility(View.GONE);
                            btnSubmit.setEnabled(true);
                            etEmailInput.setError("Koneksi gagal, coba lagi");
                        }
                    });
        });
    }
}