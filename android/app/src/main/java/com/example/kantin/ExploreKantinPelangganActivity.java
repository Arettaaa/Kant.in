package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.example.kantin.model.response.CanteenListResponse;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;

import java.util.List;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class ExploreKantinPelangganActivity extends AppCompatActivity {

    private RecyclerView rvExploreKantin;
    private KantinAdapter adapter;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_explorekantinpelanggan);

        // --- INISIALISASI VIEW ---
        ImageView btnBack = findViewById(R.id.btnBackExplore);
        rvExploreKantin = findViewById(R.id.rvExploreKantin);

        LinearLayout navBeranda = findViewById(R.id.navBeranda);
        LinearLayout navPesanan = findViewById(R.id.navPesanan);
        LinearLayout navProfil = findViewById(R.id.navProfil);

        // --- SETUP RECYCLERVIEW ---
        rvExploreKantin.setLayoutManager(new LinearLayoutManager(this));

        // --- AMBIL DATA KANTIN DARI API ---
        fetchSemuaKantin();

        // --- LOGIKA KLIK ---
        btnBack.setOnClickListener(v -> onBackPressed());

        navBeranda.setOnClickListener(v -> {
            Intent intent = new Intent(this, BerandaPelangganActivity.class);
            intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
            startActivity(intent);
        });

        navPesanan.setOnClickListener(v -> {
            startActivity(new Intent(this, HistoryActivity.class));
        });

        navProfil.setOnClickListener(v -> {
            startActivity(new Intent(this, ProfilPelangganActivity.class));
        });
    }

    private void fetchSemuaKantin() {
        ApiService apiService = ApiClient.getClient().create(ApiService.class);
        apiService.getAllCanteens().enqueue(new Callback<CanteenListResponse>() {
            @Override
            public void onResponse(Call<CanteenListResponse> call, Response<CanteenListResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    List<CanteenListResponse.CanteenData> list = response.body().getData();
                    // Pakai KantinAdapter yang sudah kita buat tadi
                    adapter = new KantinAdapter(ExploreKantinPelangganActivity.this, list);
                    rvExploreKantin.setAdapter(adapter);
                }
            }

            @Override
            public void onFailure(Call<CanteenListResponse> call, Throwable t) {
                Toast.makeText(ExploreKantinPelangganActivity.this, "Gagal memuat kantin", Toast.LENGTH_SHORT).show();
            }
        });
    }
}