package com.example.kantin;

import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.Log;
import android.view.View;
import android.widget.EditText;
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

import java.util.ArrayList;
import java.util.List;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class DetailKantinActivity extends AppCompatActivity {

    private ImageView btnBackWarung, imgCover;
    private TextView tvNamaWarung, tvDeskripsiWarung, tvLokasiKantin, tvRatingWarung, tvJamOperasional, tvMenuKosong;
    private RecyclerView rvMenu;
    private EditText etSearchMenu;
    private MenuAdapter menuAdapter;
    private String canteenId;
    private boolean isErrorShown = false;
    private boolean canteenIsOpen = true;

    // List untuk menyimpan data menu asli
    private List<MenuListResponse.MenuItem> originalMenuList = new ArrayList<>();

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
        tvJamOperasional = findViewById(R.id.tvJamOperasional);
        etSearchMenu = findViewById(R.id.etSearchMenu);
        tvMenuKosong = findViewById(R.id.tvMenuKosong);

        // Setup RecyclerView Menu
        rvMenu = findViewById(R.id.rvMenuDinamis);
        rvMenu.setLayoutManager(new LinearLayoutManager(this));
        rvMenu.setNestedScrollingEnabled(false); // Agar scroll smooth di dalam ScrollView

        // Nilai Default
        tvRatingWarung.setText("5.0");
        tvLokasiKantin.setText("Sekolah Vokasi IPB");

        // Tambahkan TextWatcher untuk mendeteksi ketikan di kolom pencarian
        etSearchMenu.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {}

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {}

            @Override
            public void afterTextChanged(Editable s) {
                // Panggil fungsi filter setiap kali teks berubah
                filterMenu(s.toString());
            }
        });
    }

    // Fungsi untuk memfilter list berdasarkan teks pencarian
    private void filterMenu(String text) {
        List<MenuListResponse.MenuItem> filteredList = new ArrayList<>();

        for (MenuListResponse.MenuItem item : originalMenuList) {
            if (item.getName().toLowerCase().contains(text.toLowerCase())) {
                filteredList.add(item);
            }
        }

        // Cek apakah hasil pencarian kosong
        if (filteredList.isEmpty()) {
            // Jika kosong: sembunyikan RecyclerView, tampilkan pesan kosong
            rvMenu.setVisibility(View.GONE);
            tvMenuKosong.setVisibility(View.VISIBLE);
        } else {
            // Jika ada menu: tampilkan RecyclerView, sembunyikan pesan kosong
            rvMenu.setVisibility(View.VISIBLE);
            tvMenuKosong.setVisibility(View.GONE);
        }

        if (menuAdapter != null) {
            menuAdapter.filterList(filteredList);
        }
    }

    private void showErrorOnce(String message) {
        if (!isErrorShown) {
            isErrorShown = true;
            Toast.makeText(this, message, Toast.LENGTH_SHORT).show();
        }
    }

    private void fetchDetailKantin() {
        ApiService apiService = ApiClient.getClient().create(ApiService.class);
        apiService.getCanteenDetail(canteenId).enqueue(new Callback<CanteenDetailResponse>() {
            @Override
            public void onResponse(Call<CanteenDetailResponse> call, Response<CanteenDetailResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    CanteenDetailResponse.CanteenDetail data = response.body().getData();

                    tvNamaWarung.setText(data.getName());
                    tvDeskripsiWarung.setText(data.getDescription());
                    tvLokasiKantin.setText(data.getLocation());

                    if (data.getOperatingHours() != null) {
                        String jam = data.getOperatingHours().getOpen() + " - " + data.getOperatingHours().getClose();
                        tvJamOperasional.setText(jam);
                    } else {
                        tvJamOperasional.setText("Jam tidak tersedia");
                    }

                    String imageUrl = data.getImage();
                    if (imageUrl != null && !imageUrl.startsWith("http")) {
                        imageUrl = "https://nonephemerally-nonrevolving-judie.ngrok-free.dev/storage/" + imageUrl;
                    }
                    Glide.with(DetailKantinActivity.this)
                            .load(imageUrl)
                            .placeholder(R.drawable.makanan)
                            .error(R.drawable.makanan)
                            .centerCrop()
                            .into(imgCover);

                    canteenIsOpen = data.isOpen();
                                       fetchMenuKantin();

                }

            }


            @Override

            public void onFailure(Call<CanteenDetailResponse> call, Throwable t) {
                Log.e("API_ERROR", "Detail Kantin: " + t.getMessage());
                // tetap fetch menu meski detail gagal
                fetchMenuKantin();
            }
        });
    }
    private void fetchMenuKantin() {
        ApiService apiService = ApiClient.getClient().create(ApiService.class);
        apiService.getCanteenMenus(canteenId).enqueue(new Callback<MenuListResponse>() {
            @Override
            public void onResponse(Call<MenuListResponse> call, Response<MenuListResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    // Simpan data ke originalMenuList agar bisa difilter nanti
                    originalMenuList = response.body().getData();
                    menuAdapter = new MenuAdapter(DetailKantinActivity.this, originalMenuList, canteenIsOpen);
                    rvMenu.setAdapter(menuAdapter);
                }
            }

            @Override
            public void onFailure(Call<MenuListResponse> call, Throwable t) {
                Log.e("API_ERROR", "Menu Kantin: " + t.getMessage());
                showErrorOnce("Gagal memuat menu");
            }
        });
    }


}