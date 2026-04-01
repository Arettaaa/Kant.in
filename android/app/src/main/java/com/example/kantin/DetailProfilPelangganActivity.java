package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.widget.ImageView;
import android.widget.TextView;
import androidx.appcompat.app.AppCompatActivity;

import com.bumptech.glide.Glide;
import com.example.kantin.utils.SessionManager;

public class DetailProfilPelangganActivity extends AppCompatActivity {

    // 1. PASTIKAN URL INI ADA ISINYA DAN DIAKHIRI SLASH (/)
    private static final String BASE_URL_STORAGE = "https://nonephemerally-nonrevolving-judie.ngrok-free.dev/storage/";

    private ImageView btnBack, ivDetailFoto;
    private TextView tvDetailNama, tvDetailEmail, tvDetailPhone, tvDetailRole, btnEditProfil;
    private SessionManager sessionManager;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_detailprofilpelanggan);

        sessionManager = new SessionManager(this);

        // Inisialisasi View
        btnBack = findViewById(R.id.btnBack);
        ivDetailFoto = findViewById(R.id.ivDetailFoto);
        tvDetailNama = findViewById(R.id.tvDetailNama);
        tvDetailEmail = findViewById(R.id.tvDetailEmail);
        tvDetailPhone = findViewById(R.id.tvDetailPhone);
        tvDetailRole = findViewById(R.id.tvDetailRole);
        btnEditProfil = findViewById(R.id.btnEditProfil);

        btnBack.setOnClickListener(v -> onBackPressed());

        btnEditProfil.setOnClickListener(v -> {
            startActivity(new Intent(this, UbahProfilPelangganActivity.class));
        });
    }

    @Override
    protected void onResume() {
        super.onResume();
        // Setiap kali balik dari Edit, kita muat ulang data dari Session
        loadDataDariSession();
    }

    private void loadDataDariSession() {
        String name = sessionManager.getUserName();
        String email = sessionManager.getUserEmail();
        String phone = sessionManager.getUserPhone();
        String role = sessionManager.getUserRole();
        String photoPath = sessionManager.getPhotoUrl();

        // Set Teks
        tvDetailNama.setText(name != null && !name.isEmpty() ? name : "Belum diatur");
        tvDetailEmail.setText(email != null && !email.isEmpty() ? email : "Belum diatur");
        tvDetailPhone.setText(phone != null && !phone.isEmpty() ? phone : "Belum diatur");

        // --- LOGIKA ROLE ---
        if (role != null && role.equalsIgnoreCase("buyer")) {
            tvDetailRole.setText("Pembeli");
        } else {
            tvDetailRole.setText("Pelanggan");
        }

        // --- LOGIKA TAMPILKAN FOTO ---
        if (photoPath != null && !photoPath.isEmpty()) {
            // Hilangkan padding bawaan agar foto penuh
            ivDetailFoto.setPadding(0, 0, 0, 0);
            ivDetailFoto.clearColorFilter();

            // Gabungkan URL
            String fullPhotoUrl = photoPath.startsWith("http") ? photoPath : BASE_URL_STORAGE + photoPath;

            Log.d("PHOTO_DEBUG", "Loading URL: " + fullPhotoUrl);

            Glide.with(this)
                    .load(fullPhotoUrl)
                    .circleCrop()
                    .placeholder(R.drawable.userorg)
                    .error(R.drawable.userorg)
                    .into(ivDetailFoto);
        } else {
            // Jika tidak ada foto, balik ke icon default
            ivDetailFoto.setImageResource(R.drawable.userorg);
            // Beri padding lagi supaya icon default tidak terlalu besar (sesuaikan desain)
            int p = (int) (22 * getResources().getDisplayMetrics().density);
            ivDetailFoto.setPadding(p, p, p, p);
        }
    }
}