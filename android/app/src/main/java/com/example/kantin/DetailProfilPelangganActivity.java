package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.widget.ImageView;
import android.widget.TextView;
import androidx.appcompat.app.AppCompatActivity;

import com.bumptech.glide.Glide;
import com.example.kantin.utils.SessionManager;

public class DetailProfilPelangganActivity extends AppCompatActivity {

    private ImageView btnBack, ivDetailFoto;
    private TextView tvDetailNama, tvDetailEmail, tvDetailPhone, btnEditProfil;
    private SessionManager sessionManager;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_detailprofilpelanggan); // Pastikan nama file XML-nya cocok

        sessionManager = new SessionManager(this);

        // 1. Inisialisasi View
        btnBack = findViewById(R.id.btnBack);
        ivDetailFoto = findViewById(R.id.ivDetailFoto);
        tvDetailNama = findViewById(R.id.tvDetailNama);
        tvDetailEmail = findViewById(R.id.tvDetailEmail);
        tvDetailPhone = findViewById(R.id.tvDetailPhone);
        btnEditProfil = findViewById(R.id.btnEditProfil);

        // 2. Klik Back
        btnBack.setOnClickListener(v -> onBackPressed());

        // 3. Klik Tombol Edit
        btnEditProfil.setOnClickListener(v -> {
            startActivity(new Intent(this, UbahProfilPelangganActivity.class));
        });
    }

    @Override
    protected void onResume() {
        super.onResume();
        loadDataDariSession();
    }

    private void loadDataDariSession() {
        // Ambil data yang tersimpan di HP
        String name = sessionManager.getUserName();
        String email = sessionManager.getUserEmail();
        String phone = sessionManager.getUserPhone();
        String photoUrl = sessionManager.getPhotoUrl();

        // Tampilkan ke teks
        tvDetailNama.setText(name != null && !name.isEmpty() ? name : "Belum diatur");
        tvDetailEmail.setText(email != null && !email.isEmpty() ? email : "Belum diatur");
        tvDetailPhone.setText(phone != null && !phone.isEmpty() ? phone : "Belum diatur");

        // Tampilkan Foto pakai Glide
        if (photoUrl != null && !photoUrl.isEmpty()) {
            Glide.with(this)
                    .load(photoUrl)
                    .circleCrop()
                    .placeholder(R.drawable.user) // Muncul saat loading
                    .error(R.drawable.user)       // Muncul kalau link gambar rusak
                    .into(ivDetailFoto);
        } else {
            ivDetailFoto.setImageResource(R.drawable.user);
        }
    }
}