package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.widget.ImageView;
import android.widget.LinearLayout;
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

import com.example.kantin.model.response.ProfileAdminResponse;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

import com.example.kantin.model.response.CanteenDetailResponse;
import com.example.kantin.model.response.MenuListResponse;
import java.util.List;
import java.util.Locale;

public class ProfilAdminKantin extends AppCompatActivity {

    private ImageView ivCanteenImage, ivOwnerPhoto;
    private TextView tvCanteenName, tvOwnerName, tvCanteenRating; // ← tambah tvCanteenRating
    private RelativeLayout btnEditCanteen, btnHistory;
    private AppCompatButton btnLogout;
    private LinearLayout btnBantuan;
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

        FooterAdmin.setupFooter(this, "PROFIL");
        fetchProfileData();
        setupListeners();
    }

    private void initViews() {
        ivCanteenImage  = findViewById(R.id.ivCanteenImage);
        ivOwnerPhoto    = findViewById(R.id.ivOwnerPhoto);
        tvCanteenName   = findViewById(R.id.tvCanteenName);
        tvOwnerName     = findViewById(R.id.tvOwnerName);
        tvCanteenRating = findViewById(R.id.tvCanteenRating); // ← tambah ini
        btnEditCanteen  = findViewById(R.id.btnEditCanteen);
        btnHistory      = findViewById(R.id.btnHistory);
        btnLogout       = findViewById(R.id.btnLogout);
        btnBantuan      = findViewById(R.id.btnBantuan);
    }

    private void fetchProfileData() {
        // Tampilkan nama kantin dari session lokal dulu
        String canteenName = sessionManager.getCanteenName();
        tvCanteenName.setText(canteenName != null ? canteenName : "Kantin Saya");

        apiService.getProfile().enqueue(new Callback<ProfileAdminResponse>() {
            @Override
            public void onResponse(Call<ProfileAdminResponse> call, Response<ProfileAdminResponse> response) {
                if (response.isSuccessful() && response.body() != null && response.body().isSuccess()) {
                    ProfileAdminResponse.AdminProfile userData = response.body().getData();
                    if (userData != null) {
                        tvOwnerName.setText(userData.getName());
                        sessionManager.saveUserInfo(
                                userData.getName(),
                                userData.getEmail(),
                                userData.getPhone()
                        );

                        // ← TAMBAH INI UNTUK DEBUG
                        String canteenId = userData.getCanteenId();
                        android.util.Log.d("PROFIL_DEBUG", "canteen_id dari API: " + canteenId);
                        android.util.Log.d("PROFIL_DEBUG", "canteen_id dari session: " + sessionManager.getCanteenId());
                        android.util.Log.d("PROFIL_DEBUG", "nama dari API: " + userData.getName());

                        if (canteenId != null && !canteenId.isEmpty()) {
                            fetchCanteenDetail(canteenId);
                            fetchCanteenRating(canteenId);
                        } else {
                            // ← Coba fallback ke session
                            String canteenIdFromSession = sessionManager.getCanteenId();
                            android.util.Log.d("PROFIL_DEBUG", "Pakai canteen_id dari session: " + canteenIdFromSession);
                            if (canteenIdFromSession != null && !canteenIdFromSession.isEmpty()) {
                                fetchCanteenDetail(canteenIdFromSession);
                                fetchCanteenRating(canteenIdFromSession);
                            }
                        }

                        // Load foto owner
                        String photoUrl = userData.getPhotoProfile();
                        if (photoUrl != null && !photoUrl.isEmpty()) {
                            Glide.with(ProfilAdminKantin.this)
                                    .load(photoUrl)
                                    .circleCrop()
                                    .placeholder(R.drawable.user)
                                    .error(R.drawable.user)
                                    .into(ivOwnerPhoto);
                        }

                        // ← Setelah dapat canteen_id, fetch detail kantin & rating
                        if (canteenId != null && !canteenId.isEmpty()) {
                            fetchCanteenDetail(canteenId);
                            fetchCanteenRating(canteenId);
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

    /** Fetch nama kantin + foto kantin dari API */
    private void fetchCanteenDetail(String canteenId) {
        apiService.getCanteenDetail(canteenId).enqueue(new Callback<CanteenDetailResponse>() {
            @Override
            public void onResponse(Call<CanteenDetailResponse> call, Response<CanteenDetailResponse> response) {
                if (response.isSuccessful() && response.body() != null && response.body().isSuccess()) {
                    CanteenDetailResponse.CanteenDetail detail = response.body().getData();
                    if (detail != null) {
                        // Set nama kantin dari API (bukan session lagi)
                        tvCanteenName.setText(detail.getName());
                        sessionManager.saveCanteenName(detail.getName());

                        // Load foto kantin ke avatar atas
                        String imageUrl = detail.getImage();
                        if (imageUrl != null && !imageUrl.isEmpty()) {
                            if (!imageUrl.startsWith("http")) {
                                imageUrl = "https://nonephemerally-nonrevolving-judie.ngrok-free.dev/storage/" + imageUrl;
                            }
                            Glide.with(ProfilAdminKantin.this)
                                    .load(imageUrl)
                                    .centerCrop()
                                    .placeholder(R.drawable.logo_kantin)
                                    .error(R.drawable.logo_kantin)
                                    .into(ivCanteenImage);
                        }
                    }
                }
            }

            @Override
            public void onFailure(Call<CanteenDetailResponse> call, Throwable t) {
                // Biarkan foto default, tidak perlu toast
            }
        });
    }

    /** Hitung rating rata-rata dari semua menu kantin */
    private void fetchCanteenRating(String canteenId) {
        ApiClient.getClient().create(ApiService.class)
                .getCanteenMenus(canteenId)
                .enqueue(new Callback<MenuListResponse>() {
                    @Override
                    public void onResponse(Call<MenuListResponse> call, Response<MenuListResponse> response) {
                        if (response.isSuccessful() && response.body() != null
                                && response.body().getData() != null) {
                            List<MenuListResponse.MenuItem> menus = response.body().getData();
                            double total = 0;
                            int count = 0;
                            for (MenuListResponse.MenuItem menu : menus) {
                                if (menu.getTotalReviews() > 0) {
                                    total += menu.getAverageRating();
                                    count++;
                                }
                            }
                            String ratingText = count > 0
                                    ? String.format(Locale.getDefault(), "%.1f", total / count)
                                    : "Baru";
                            tvCanteenRating.setText(ratingText);
                        } else {
                            tvCanteenRating.setText("Baru");
                        }
                    }

                    @Override
                    public void onFailure(Call<MenuListResponse> call, Throwable t) {
                        tvCanteenRating.setText("Baru");
                    }
                });
    }

    private void setupListeners() {
        btnHistory.setOnClickListener(v ->
                startActivity(new Intent(ProfilAdminKantin.this, TransaksiActivity.class)));

        btnEditCanteen.setOnClickListener(v ->
                startActivity(new Intent(ProfilAdminKantin.this, UbahProfilKantin.class)));

        btnBantuan.setOnClickListener(v ->
                startActivity(new Intent(ProfilAdminKantin.this, PusatBantuan.class)));

        btnLogout.setOnClickListener(v -> {
            sessionManager.clearSession();
            Intent intent = new Intent(ProfilAdminKantin.this, LoginActivity.class);
            intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
            startActivity(intent);
            finish();
        });
    }
}