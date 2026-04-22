package com.example.kantin;

import android.os.Bundle;
import android.widget.LinearLayout;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

public class KelolaMenu extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_kelola_menu);

        // Mengatur padding agar tidak tertutup notch/system bar
        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.main), (v, insets) -> {
            Insets systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars());
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom);
            return insets;
        });

        // 🔥 1. PANGGIL FOOTER DI SINI BIAR BISA DIKLIK 🔥
        FooterAdmin.setupFooter(this);

        // 🔥 2. (Opsional) Sekalian kita idupin tombol Keluar-nya
        LinearLayout btnKeluar = findViewById(R.id.btnKeluar);
        if (btnKeluar != null) {
            btnKeluar.setOnClickListener(v -> {
                Toast.makeText(this, "Tombol Keluar Ditekan", Toast.LENGTH_SHORT).show();
                // Nanti logika logout-nya bisa taruh di sini
            });
        }
    }
}