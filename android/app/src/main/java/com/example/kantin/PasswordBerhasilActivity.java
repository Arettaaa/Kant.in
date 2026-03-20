package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.widget.LinearLayout; // Import LinearLayout, bukan Button
import androidx.appcompat.app.AppCompatActivity;

public class PasswordBerhasilActivity extends AppCompatActivity {

    private LinearLayout btnGoToLogin;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        if (getSupportActionBar() != null) getSupportActionBar().hide();

        setContentView(R.layout.activity_password_berhasil);

        // Sekarang ini cocok dengan XML (LinearLayout ketemu LinearLayout)
        btnGoToLogin = findViewById(R.id.btnGoToLogin);

        btnGoToLogin.setOnClickListener(v -> {
            Intent intent = new Intent(PasswordBerhasilActivity.this, LoginActivity.class);
            // Flag ini membersihkan semua tumpukan halaman agar balik ke login dengan bersih
            intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
            startActivity(intent);
            finish();
        });
    }
}