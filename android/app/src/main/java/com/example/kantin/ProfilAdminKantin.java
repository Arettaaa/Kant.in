package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
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

// ✅ FIX 1: Ganti ProfileResponse → ProfileAdminResponse (sesuai ApiService)
import com.example.kantin.model.response.ProfileAdminResponse;

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

        ViewCompat.setOnApplyWindowInsetsListener(findViewById(android.R.id.content), (v, insets) -> {
            Insets systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars());
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom);
            return insets;
        });

        initViews();

        sessionManager = new SessionManager(this);
        apiService = ApiClient.getAuthClient(sessionManager.getToken()).create(ApiService.class);

        FooterAdmin.setupFooter(this);
        fetchProfileData();
        setupListeners();
    }

    private void initViews() {
        ivCanteenImage = findViewById(R.id.ivCanteenImage);
        ivOwnerPhoto   = findViewById(R.id.ivOwnerPhoto);
        tvCanteenName  = findViewById(R.id.tvCanteenName);
        tvOwnerName    = findViewById(R.id.tvOwnerName);
        btnEditCanteen = findViewById(R.id.btnEditCanteen);
        btnHistory     = findViewById(R.id.btnHistory);
        btnLogout      = findViewById(R.id.btnLogout);
    }

    private void fetchProfileData() {
        // Tampilkan nama kantin dari session lokal dulu
        String canteenName = sessionManager.getCanteenName();
        tvCanteenName.setText(canteenName != null ? canteenName : "Kantin Saya");

        // ✅ FIX 2: Ganti ProfileResponse → ProfileAdminResponse
        apiService.getProfile().enqueue(new Callback<ProfileAdminResponse>() {
            @Override
            public void onResponse(Call<ProfileAdminResponse> call, Response<ProfileAdminResponse> response) {
                if (response.isSuccessful() && response.body() != null && response.body().isSuccess()) {

                    // ✅ FIX 3: Pakai ProfileAdminResponse.UserData (bukan ProfileResponse.UserData)
                    ProfileAdminResponse.AdminProfile userData = response.body().getData();
                    if (userData != null) {
                        // Set Nama Owner dari API
                        tvOwnerName.setText(userData.getName());

                        // Simpan ke session supaya bisa dipakai di halaman lain
                        sessionManager.saveUserInfo(
                                userData.getName(),
                                userData.getEmail(),
                                userData.getPhone()
                        );

                        // Load foto profil pakai Glide
                        String photoUrl = userData.getPhotoProfile();
                        if (photoUrl != null && !photoUrl.isEmpty()) {
                            Glide.with(ProfilAdminKantin.this)
                                    .load(photoUrl)
                                    .circleCrop()
                                    .placeholder(R.drawable.user)
                                    .error(R.drawable.user)
                                    .into(ivOwnerPhoto);
                        }
                    }
                } else {
                    Toast.makeText(ProfilAdminKantin.this,
                            "Gagal mengambil data profil", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<ProfileAdminResponse> call, Throwable t) {
                Toast.makeText(ProfilAdminKantin.this,
                        "Koneksi Error: " + t.getMessage(), Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void setupListeners() {
        btnHistory.setOnClickListener(v -> {
            // startActivity(new Intent(this, TransaksiActivity.class));
            Toast.makeText(this, "Buka Riwayat Transaksi", Toast.LENGTH_SHORT).show();
        });

        btnEditCanteen.setOnClickListener(v -> {
            // startActivity(new Intent(this, EditProfilActivity.class));
            Toast.makeText(this, "Buka Edit Profil", Toast.LENGTH_SHORT).show();
        });

        // ✅ FIX 4: Ganti sessionManager.logout() → sessionManager.clearSession()
        btnLogout.setOnClickListener(v -> {
            sessionManager.clearSession();

            Intent intent = new Intent(ProfilAdminKantin.this, LoginActivity.class);
            intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
            startActivity(intent);
            finish();
        });
    }
}