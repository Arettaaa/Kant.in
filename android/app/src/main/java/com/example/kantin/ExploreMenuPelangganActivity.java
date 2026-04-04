package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.example.kantin.model.response.MenuListResponse;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;

import java.util.ArrayList;
import java.util.List;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class ExploreMenuPelangganActivity extends AppCompatActivity {

    private RecyclerView rvExploreMenu;
    private ExploreMenuAdapter adapter;
    private EditText etSearchMenu;
    private List<MenuListResponse.MenuItem> allMenuList = new ArrayList<>();

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_exploremenupelanggan);

        // --- 1. INISIALISASI VIEW ---
        rvExploreMenu = findViewById(R.id.rvExploreMenu); // ID sesuai XML yang kita ubah tadi
        etSearchMenu = findViewById(R.id.etSearchMenu);
        ImageView btnBack = findViewById(R.id.btnBackExploreMenu);

        // Navbar
        LinearLayout navHome = findViewById(R.id.navHome);
        LinearLayout navHistory = findViewById(R.id.navHistory);
        LinearLayout navProfile = findViewById(R.id.navProfile);

        // --- 2. SETUP RECYCLERVIEW ---
        rvExploreMenu.setLayoutManager(new LinearLayoutManager(this));
        // Kita set adapter kosong dulu sementara nunggu API
        adapter = new ExploreMenuAdapter(this, new ArrayList<>());
        rvExploreMenu.setAdapter(adapter);

        // --- 3. AMBIL DATA DARI API ---
        fetchAllMenus();

        // --- 4. LOGIKA KLIK & NAVIGASI ---

        btnBack.setOnClickListener(v -> onBackPressed());

        navHome.setOnClickListener(v -> {
            // Balik ke BerandaPelangganActivity
            Intent intent = new Intent(this, BerandaPelangganActivity.class);
            intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_SINGLE_TOP);
            startActivity(intent);
            finish();
        });

        navHistory.setOnClickListener(v -> {
            startActivity(new Intent(this, HistoryActivity.class));
        });

        navProfile.setOnClickListener(v -> {
            startActivity(new Intent(this, ProfilPelangganActivity.class));
        });
    }

    /**
     * Fungsi untuk mengambil semua data menu dari server
     */
    private void fetchAllMenus() {
        ApiClient.getClient().create(ApiService.class).getAllMenus().enqueue(new Callback<MenuListResponse>() {
            @Override
            public void onResponse(Call<MenuListResponse> call, Response<MenuListResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    // Tampilkan semua menu di RecyclerView
                    adapter = new ExploreMenuAdapter(ExploreMenuPelangganActivity.this, response.body().getData());
                    rvExploreMenu.setAdapter(adapter);
                }
            }

            @Override
            public void onFailure(Call<MenuListResponse> call, Throwable t) {
                Toast.makeText(ExploreMenuPelangganActivity.this, "Gagal ambil semua menu", Toast.LENGTH_SHORT).show();
            }
        });
    }
}