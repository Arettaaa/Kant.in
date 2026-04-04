package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.cardview.widget.CardView;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.bumptech.glide.Glide;
import com.example.kantin.model.response.CanteenListResponse;
import com.example.kantin.model.response.MenuListResponse;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;
import com.example.kantin.utils.SessionManager;

import java.util.List;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class BerandaPelangganActivity extends AppCompatActivity {

    private RecyclerView rvKantin, rvMenuPopuler;
    private KantinAdapter kantinAdapter;
    private MenuPopulerAdapter menuPopulerAdapter;
    private SessionManager sessionManager;
    private ImageView ivFotoProfil;
    private TextView tvHaloUser;

    // Pastikan BASE_URL sesuai dengan link Ngrok aktif
    private final String BASE_URL_STORAGE = "https://nonephemerally-nonrevolving-judie.ngrok-free.dev/storage/";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_berandapelanggan);

        sessionManager = new SessionManager(this);

        // --- 1. INISIALISASI VIEW ---
        tvHaloUser = findViewById(R.id.tv_halo_user);
        ivFotoProfil = findViewById(R.id.ivFotoProfil);
        rvKantin = findViewById(R.id.rv_kantin);
        rvMenuPopuler = findViewById(R.id.rv_menu_populer);

        ImageView btnHistoryTop = findViewById(R.id.btn_history_top);
        FrameLayout btnKeranjang = findViewById(R.id.btn_keranjang);
        CardView btnProfilTop = findViewById(R.id.btn_profil_top);
        TextView tvLihatSemuaMenu = findViewById(R.id.tv_lihat_semua_menu);
        TextView tvLihatSemuaKantin = findViewById(R.id.tv_lihat_semua_kantin);

        LinearLayout navBeranda = findViewById(R.id.nav_beranda);
        LinearLayout navPesanan = findViewById(R.id.nav_pesanan);
        LinearLayout navProfil = findViewById(R.id.nav_profil);

        // --- 2. SETUP RECYCLERVIEW ---
        rvKantin.setLayoutManager(new LinearLayoutManager(this));
        rvMenuPopuler.setLayoutManager(new LinearLayoutManager(this, LinearLayoutManager.HORIZONTAL, false));

        // --- 3. LOAD DATA ---
        updateProfileUI();
        fetchKantinBeranda();
        fetchMenuPopuler();

        // --- 4. LOGIKA KLIK ---
        btnHistoryTop.setOnClickListener(v -> startActivity(new Intent(this, HistoryActivity.class)));
        btnKeranjang.setOnClickListener(v -> startActivity(new Intent(this, KeranjangPelangganActivity.class)));
        btnProfilTop.setOnClickListener(v -> startActivity(new Intent(this, ProfilPelangganActivity.class)));
        tvLihatSemuaMenu.setOnClickListener(v -> startActivity(new Intent(this, ExploreMenuPelangganActivity.class)));
        tvLihatSemuaKantin.setOnClickListener(v -> startActivity(new Intent(this, ExploreKantinPelangganActivity.class)));

        navPesanan.setOnClickListener(v -> startActivity(new Intent(this, HistoryActivity.class)));
        navProfil.setOnClickListener(v -> startActivity(new Intent(this, ProfilPelangganActivity.class)));
    }

    private void updateProfileUI() {
        String fullName = sessionManager.getUserName();
        if (fullName != null && !fullName.isEmpty()) {
            tvHaloUser.setText("Halo, " + fullName.split(" ")[0] + "! 👋");
        } else {
            tvHaloUser.setText("Halo, Sobat Kant.in! 👋");
        }

        String path = sessionManager.getPhotoUrl();
        if (path != null && !path.isEmpty()) {
            ivFotoProfil.setPadding(0, 0, 0, 0);
            String fullUrl = path.startsWith("http") ? path : BASE_URL_STORAGE + path;
            Glide.with(this).load(fullUrl).circleCrop().placeholder(R.drawable.userorg).into(ivFotoProfil);
        } else {
            ivFotoProfil.setImageResource(R.drawable.userorg);
            int p = (int) (7 * getResources().getDisplayMetrics().density);
            ivFotoProfil.setPadding(p, p, p, p);
        }
    }

    private void fetchKantinBeranda() {
        ApiService apiService = ApiClient.getClient().create(ApiService.class);
        apiService.getAllCanteens().enqueue(new Callback<CanteenListResponse>() {
            @Override
            public void onResponse(Call<CanteenListResponse> call, Response<CanteenListResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    List<CanteenListResponse.CanteenData> allKantin = response.body().getData();
                    if (allKantin != null && !allKantin.isEmpty()) {
                        List<CanteenListResponse.CanteenData> displayList = allKantin.size() > 5 ? allKantin.subList(0, 5) : allKantin;
                        kantinAdapter = new KantinAdapter(BerandaPelangganActivity.this, displayList);
                        rvKantin.setAdapter(kantinAdapter);
                    }
                }
            }
            @Override public void onFailure(Call<CanteenListResponse> call, Throwable t) {
                Log.e("API_ERROR", "Fetch Kantin: " + t.getMessage());
            }
        });
    }

    private void fetchMenuPopuler() {
        ApiService apiService = ApiClient.getClient().create(ApiService.class);
        apiService.getAllMenus().enqueue(new Callback<MenuListResponse>() {
            @Override
            public void onResponse(Call<MenuListResponse> call, Response<MenuListResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    List<MenuListResponse.MenuItem> allMenu = response.body().getData();
                    if (allMenu != null && !allMenu.isEmpty()) {
                        List<MenuListResponse.MenuItem> displayList = allMenu.size() > 3 ? allMenu.subList(0, 3) : allMenu;
                        menuPopulerAdapter = new MenuPopulerAdapter(BerandaPelangganActivity.this, displayList);
                        rvMenuPopuler.setAdapter(menuPopulerAdapter);
                    }
                }
            }
            @Override public void onFailure(Call<MenuListResponse> call, Throwable t) {
                Log.e("API_ERROR", "Fetch Menu Populer: " + t.getMessage());
            }
        });
    }

    @Override
    protected void onResume() {
        super.onResume();
        updateProfileUI();
    }
}