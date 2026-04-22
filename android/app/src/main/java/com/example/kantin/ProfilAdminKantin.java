package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.AppCompatButton;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

import com.bumptech.glide.Glide;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;
import com.example.kantin.utils.SessionManager;

// Pastikan kamu import model ProfileResponse yang sesuai
import com.example.kantin.model.response.ProfileResponse;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class ProfilAdminKantin extends AppCompatActivity {

    private ImageView ivCanteenImage, ivOwnerPhoto;
    private TextView tvCanteenName, tvOwnerName;
    private RelativeLayout btnEditCanteen, btnHistory;
    private AppCompatButton btnLogout;

    private ApiService apiService;
    private SessionManager sessionManager;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_profil_admin_kantin);

        // Mencegah konten tertutup notch/system bar
        ViewCompat.setOnApplyWindowInsetsListener(findViewById(android.R.id.content), (v, insets) -> {
            Insets systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars());
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom);
            return insets;
        });

        initViews();

        sessionManager = new SessionManager(this);
        apiService = ApiClient.getAuthClient(sessionManager.getToken()).create(ApiService.class);

        // 🔥 1. HIDUPKAN FOOTER
        FooterAdmin.setupFooter(this);

        // 🔥 2. AMBIL DATA DARI SERVER (API)
        fetchProfileData();

        // 🔥 3. AKTIFKAN TOMBOL-TOMBOL
        setupListeners();
    }

    private void initViews() {
        ivCanteenImage = findViewById(R.id.ivCanteenImage);
        ivOwnerPhoto = findViewById(R.id.ivOwnerPhoto);
        tvCanteenName = findViewById(R.id.tvCanteenName);
        tvOwnerName = findViewById(R.id.tvOwnerName);
        btnEditCanteen = findViewById(R.id.btnEditCanteen);
        btnHistory = findViewById(R.id.btnHistory);
        btnLogout = findViewById(R.id.btnLogout);
    }

    private void fetchProfileData() {
        // Tampilkan nama kantin dari session lokal terlebih dahulu
        String canteenName = sessionManager.getCanteenName();
        tvCanteenName.setText(canteenName != null ? canteenName : "Kantin Saya");

        // Panggil API Profil
        apiService.getProfile().enqueue(new Callback<ProfileResponse>() {
            @Override
            public void onResponse(Call<ProfileResponse> call, Response<ProfileResponse> response) {
                if (response.isSuccessful() && response.body() != null && response.body().isSuccess()) {
                    ProfileResponse.UserData userData = response.body().getData();

                    if (userData != null) {
                        // Set Nama Owner
                        tvOwnerName.setText(userData.getName());

                        // Set Foto Profil Owner pakai Glide
                        if (userData.getPhotoProfile() != null && !userData.getPhotoProfile().isEmpty()) {
                            Glide.with(ProfilAdminKantin.this)
                                    .load(userData.getPhotoProfile())
                                    .circleCrop()
                                    .placeholder(R.drawable.user)
                                    .into(ivOwnerPhoto);
                        }
                    }
                } else {
                    Toast.makeText(ProfilAdminKantin.this, "Gagal mengambil data profil", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<ProfileResponse> call, Throwable t) {
                Toast.makeText(ProfilAdminKantin.this, "Koneksi Error: " + t.getMessage(), Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void setupListeners() {
        // Klik Riwayat Transaksi
        btnHistory.setOnClickListener(v -> {
            // Uncomment kalau udah punya halamannya
            // startActivity(new Intent(this, TransaksiActivity.class));
            Toast.makeText(this, "Buka Riwayat Transaksi", Toast.LENGTH_SHORT).show();
        });

        // Klik Ubah Data Kantin
        btnEditCanteen.setOnClickListener(v -> {
            // Uncomment kalau udah punya halamannya
            // startActivity(new Intent(this, EditProfilActivity.class));
            Toast.makeText(this, "Buka Edit Profil", Toast.LENGTH_SHORT).show();
        });

        // Klik Log Out
        btnLogout.setOnClickListener(v -> {
            // Hapus sesi lokal
            sessionManager.logout();

            // Pindah ke halaman Login dan hapus tumpukan backstack
            Intent intent = new Intent(ProfilAdminKantin.this, LoginActivity.class);
            intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
            startActivity(intent);
            finish();
        });
    }
}