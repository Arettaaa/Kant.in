package com.example.kantin;

import android.os.Bundle;
import android.util.Log;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.bumptech.glide.Glide;
import com.example.kantin.model.response.CanteenDetailResponse;
import com.example.kantin.model.response.MenuListResponse;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class DetailKantinActivity extends AppCompatActivity {

    private ImageView btnBackWarung, imgCover;
    private TextView tvNamaWarung, tvDeskripsiWarung, tvLokasiKantin, tvRatingWarung;
    private RecyclerView rvMenu;
    private MenuAdapter menuAdapter;
    private String canteenId;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_detailkantinpelanggan);

        // Ambil ID dari Intent yang dikirim oleh KantinAdapter
        canteenId = getIntent().getStringExtra("CANTEEN_ID");

        initViews();

        if (canteenId != null) {
            fetchDetailKantin();
            fetchMenuKantin();
        } else {
            Toast.makeText(this, "ID Kantin tidak ditemukan", Toast.LENGTH_SHORT).show();
            finish();
        }

        btnBackWarung.setOnClickListener(v -> onBackPressed());
    }

    private void initViews() {
        btnBackWarung = findViewById(R.id.btnBackWarung);
        imgCover = findViewById(R.id.imgCover);
        tvNamaWarung = findViewById(R.id.tvNamaWarung);
        tvDeskripsiWarung = findViewById(R.id.tvDeskripsiWarung);
        tvLokasiKantin = findViewById(R.id.tvLokasiKantin);
        tvRatingWarung = findViewById(R.id.tvRatingWarung);

        // Setup RecyclerView Menu
        rvMenu = findViewById(R.id.rvMenuDinamis);
        rvMenu.setLayoutManager(new LinearLayoutManager(this));
        rvMenu.setNestedScrollingEnabled(false); // Agar scroll smooth di dalam ScrollView

        // Nilai Default
        tvRatingWarung.setText("5.0");
        tvLokasiKantin.setText("Sekolah Vokasi IPB");
    }

    private void fetchDetailKantin() {
        ApiService apiService = ApiClient.getClient().create(ApiService.class);
        apiService.getCanteenDetail(canteenId).enqueue(new Callback<CanteenDetailResponse>() {
            @Override
            public void onResponse(Call<CanteenDetailResponse> call, Response<CanteenDetailResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    CanteenDetailResponse.CanteenDetail data = response.body().getData();

                    // 1. Set Teks Detail
                    tvNamaWarung.setText(data.getName());
                    tvDeskripsiWarung.setText(data.getDescription());

                    // 2. Logika URL Gambar (Sama seperti di KantinAdapter)
                    String imageUrl = data.getImage();

                    // Jika URL tidak diawali http, tambahkan path storage backend kamu
                    if (imageUrl != null && !imageUrl.startsWith("http")) {
                        // Sesuaikan URL ini dengan domain ngrok/server kamu
                        imageUrl = "https://nonephemerally-nonrevolving-judie.ngrok-free.dev/storage/" + imageUrl;
                    }

                    // 3. Muat Gambar ke imgCover menggunakan Glide
                    Glide.with(DetailKantinActivity.this)
                            .load(imageUrl)
                            .placeholder(R.drawable.makanan) // Gambar sementara saat loading
                            .error(R.drawable.makanan)       // Gambar jika URL salah/error
                            .centerCrop()                    // Agar gambar memenuhi header dengan rapi
                            .into(imgCover);
                }
            }

            @Override
            public void onFailure(Call<CanteenDetailResponse> call, Throwable t) {
                Log.e("API_ERROR", "Detail Kantin: " + t.getMessage());
            }
        });
    }
    private void fetchMenuKantin() {
        ApiService apiService = ApiClient.getClient().create(ApiService.class);
        apiService.getCanteenMenus(canteenId).enqueue(new Callback<MenuListResponse>() {
            @Override
            public void onResponse(Call<MenuListResponse> call, Response<MenuListResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    menuAdapter = new MenuAdapter(DetailKantinActivity.this, response.body().getData());
                    rvMenu.setAdapter(menuAdapter);
                }
            }

            @Override
            public void onFailure(Call<MenuListResponse> call, Throwable t) {
                Log.e("API_ERROR", "Menu Kantin: " + t.getMessage());
                Toast.makeText(DetailKantinActivity.this, "Gagal memuat menu", Toast.LENGTH_SHORT).show();
            }
        });
    }
}