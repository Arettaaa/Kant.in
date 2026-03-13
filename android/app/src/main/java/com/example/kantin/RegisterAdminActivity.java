package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;

public class RegisterAdminActivity extends AppCompatActivity {

    TextView tabPelanggan, tvLogin;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_registeradminkantin);

        tabPelanggan = findViewById(R.id.tabPelanggan);
        tvLogin = findViewById(R.id.tvLogin);

        // pindah ke register pelanggan
        tabPelanggan.setOnClickListener(v -> {
            Intent intent = new Intent(RegisterAdminActivity.this, RegisterActivity.class);
            startActivity(intent);
            finish();
        });

        // ke login
        tvLogin.setOnClickListener(v -> {
            Intent intent = new Intent(RegisterAdminActivity.this, LoginActivity.class);
            startActivity(intent);
            finish();
        });
    }
}