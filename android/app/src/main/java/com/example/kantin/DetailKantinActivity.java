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
    private TextView tvNamaWarung, tvDeskripsiWarung, tvLokasiKantin, tvRatingWarung, tvJamOperasional;
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
        tvJamOperasional = findViewById(R.id.tvJamOperasional); // Inisialisasi ID baru

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

                    // 1. Set Nama dan Deskripsi
                    tvNamaWarung.setText(data.getName());
                    tvDeskripsiWarung.setText(data.getDescription());

                    // 2. Set Lokasi secara dinamis (Menggantikan "Sekolah Vokasi IPB")
                    tvLokasiKantin.setText(data.getLocation());

                    if (data.getOperatingHours() != null) {
                        String jam = data.getOperatingHours().getOpen() + " - " + data.getOperatingHours().getClose();
                        tvJamOperasional.setText(jam);
                    } else {
                        tvJamOperasional.setText("Jam tidak tersedia");
                    }

                    // --- BAGIAN JAM OPERASIONAL DIHAPUS / TIDAK DIPAKAI ---
                    // tvEstimasiWaktu.setText(...) dihapus sesuai request kamu

                    // 3. Logika URL Gambar
                    String imageUrl = data.getImage();
                    if (imageUrl != null && !imageUrl.startsWith("http")) {
                        imageUrl = "https://nonephemerally-nonrevolving-judie.ngrok-free.dev/storage/" + imageUrl;
                    }

                    // 4. Muat Gambar ke imgCover
                    Glide.with(DetailKantinActivity.this)
                            .load(imageUrl)
                            .placeholder(R.drawable.makanan)
                            .error(R.drawable.makanan)
                            .centerCrop()
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