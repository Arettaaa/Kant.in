package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;
import com.google.android.material.button.MaterialButton;

public class ValidasiAdminActivity extends AppCompatActivity {

    private ImageView btnBack;
    private MaterialButton btnLihatPesanan;
    private TextView tvOrderId;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_validasiadmin);

        btnBack = findViewById(R.id.btnBack);
        btnLihatPesanan = findViewById(R.id.btnLihatPesanan);
        tvOrderId = findViewById(R.id.tvOrderId);

        // Tampilkan order code dari intent
        String orderCode = getIntent().getStringExtra("ORDER_CODE");
        if (orderCode != null) {
            tvOrderId.setText(orderCode);
        }
        btnBack.setOnClickListener(v -> onBackPressed());

        btnLihatPesanan.setOnClickListener(v -> {
            Intent intent = new Intent(ValidasiAdminActivity.this, ActiveOrdersActivity.class);
            intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
            startActivity(intent);
            finish();
        });
    }
}