package com.example.kantin;

import android.os.Bundle;
import android.util.Log;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.bumptech.glide.Glide;
import com.example.kantin.model.request.AddToCartRequest;
import com.example.kantin.model.response.CanteenDetailResponse;
import com.example.kantin.model.response.CartResponse;
import com.example.kantin.model.response.MenuDetailResponse;
import com.example.kantin.model.response.MenuListResponse;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;
import com.example.kantin.utils.SessionManager;

import java.text.NumberFormat;
import java.util.Locale;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class DetailMenuActivity extends AppCompatActivity {

    private ImageView imgFood, btnBack, btnMinus, btnPlus;
    private TextView tvNamaMenu, tvHarga, tvDeskripsi, tvQuantity, tvEstimasiWaktu;
    private LinearLayout btnTambahKeranjang;
    private boolean isErrorShown = false;
    private boolean isCanteenOpen = true; // ← tambah ini
    private TextView tvTambahKeranjang;

    private TextView tvRatingMenu, tvJumlahUlasan;



    private int quantity = 1;
    private double basePrice = 0;
    private String menuName = "";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_detailmakananpelanggan);

        btnBack            = findViewById(R.id.btnBack);
        btnMinus           = findViewById(R.id.btnMinus);
        btnPlus            = findViewById(R.id.btnPlus);
        tvQuantity         = findViewById(R.id.tvQuantity);
        btnTambahKeranjang = findViewById(R.id.btnTambahKeranjang);
        imgFood            = findViewById(R.id.imgFood);
        tvNamaMenu         = findViewById(R.id.tvNamaMenu);
        tvHarga            = findViewById(R.id.tvHarga);
        tvDeskripsi        = findViewById(R.id.tvDeskripsi);
        tvEstimasiWaktu    = findViewById(R.id.tvEstimasiWaktu);

        tvTambahKeranjang = findViewById(R.id.tvTambahKeranjang);

        tvRatingMenu   = findViewById(R.id.tvRatingMenu);
        tvJumlahUlasan = findViewById(R.id.tvJumlahUlasan);

        btnBack.setOnClickListener(v -> onBackPressed());

        btnMinus.setOnClickListener(v -> {
            if (quantity > 1) {
                quantity--;
                tvQuantity.setText(String.valueOf(quantity));
            }
        });

        btnPlus.setOnClickListener(v -> {
            quantity++;
            tvQuantity.setText(String.valueOf(quantity));
        });

        btnTambahKeranjang.setOnClickListener(v -> {
            // ← cek dulu apakah kantin buka
            if (!isCanteenOpen) {
                Toast.makeText(this, "Kantin sedang tutup", Toast.LENGTH_SHORT).show();
                return;
            }

            String menuId = getIntent().getStringExtra("MENU_ID");
            String token = new SessionManager(this).getToken();
            AddToCartRequest request = new AddToCartRequest(menuId, quantity);

            ApiClient.getAuthClient(token).create(ApiService.class)
                    .addToCart(request)
                    .enqueue(new Callback<CartResponse>() {
                        @Override
                        public void onResponse(Call<CartResponse> call, Response<CartResponse> response) {
                            if (response.isSuccessful() && response.body() != null && response.body().isSuccess()) {
                                Toast.makeText(DetailMenuActivity.this,
                                        quantity + "x " + menuName + " berhasil ditambahkan!",
                                        Toast.LENGTH_SHORT).show();
                                finish();
                            } else {
                                try {
                                    String errorBody = response.errorBody().string();
                                    Log.e("CART_ERROR", "error: " + errorBody);
                                } catch (Exception e) {
                                    Log.e("CART_ERROR", "Gagal baca error body");
                                }
                                Toast.makeText(DetailMenuActivity.this,
                                        "Gagal menambahkan ke keranjang", Toast.LENGTH_SHORT).show();
                            }
                        }

                        @Override
                        public void onFailure(Call<CartResponse> call, Throwable t) {
                            Toast.makeText(DetailMenuActivity.this,
                                    "Gagal terhubung ke server", Toast.LENGTH_SHORT).show();
                        }
                    });
        });

        String menuId = getIntent().getStringExtra("MENU_ID");
        if (menuId != null) {
            fetchMenuDetail(menuId);
        } else {
            Toast.makeText(this, "Menu tidak ditemukan", Toast.LENGTH_SHORT).show();
            finish();
        }
    }

    private void fetchMenuDetail(String menuId) {
        ApiClient.getClient().create(ApiService.class)
                .getMenuDetail(menuId)
                .enqueue(new Callback<MenuDetailResponse>() {
                    @Override
                    public void onResponse(Call<MenuDetailResponse> call, Response<MenuDetailResponse> response) {
                        if (response.isSuccessful() && response.body() != null) {
                            MenuListResponse.MenuItem menu = response.body().getData();
                            bindData(menu);

                            // ← setelah dapat menu, fetch status kantin
                            if (menu.getCanteenId() != null) {
                                fetchCanteenStatus(menu.getCanteenId());
                            }
                        } else {
                            showErrorOnce("Gagal memuat detail menu");
                        }
                    }

                    @Override
                    public void onFailure(Call<MenuDetailResponse> call, Throwable t) {
                        Log.e("API_ERROR", "Detail Menu: " + t.getMessage());
                        showErrorOnce("Gagal terhubung ke server");
                    }
                });
    }

    // ← fungsi baru untuk cek status kantin
    private void fetchCanteenStatus(String canteenId) {
        ApiClient.getClient().create(ApiService.class)
                .getCanteenDetail(canteenId)
                .enqueue(new Callback<CanteenDetailResponse>() {
                    @Override
                    public void onResponse(Call<CanteenDetailResponse> call, Response<CanteenDetailResponse> response) {
                        if (response.isSuccessful() && response.body() != null) {
                            isCanteenOpen = response.body().getData().isOpen();
                            updateTambahKeranjangButton();
                        }
                    }

                    @Override
                    public void onFailure(Call<CanteenDetailResponse> call, Throwable t) {
                        Log.e("API_ERROR", "Canteen status: " + t.getMessage());
                    }
                });
    }

    // ← update tampilan tombol sesuai status kantin
    private void updateTambahKeranjangButton() {
        if (isCanteenOpen) {
            btnTambahKeranjang.setAlpha(1.0f);
            btnTambahKeranjang.setEnabled(true);
            tvTambahKeranjang.setText("Tambah Keranjang");
        } else {
            btnTambahKeranjang.setAlpha(0.4f);
            btnTambahKeranjang.setEnabled(false);
            tvTambahKeranjang.setText("Kantin Sedang Tutup");
        }
    }

    private void bindData(MenuListResponse.MenuItem menu) {
        menuName  = menu.getName();
        basePrice = menu.getPriceAsDouble();

        tvNamaMenu.setText(menuName);
        tvDeskripsi.setText(menu.getDescription());
        tvHarga.setText(formatRupiah(basePrice));
        tvEstimasiWaktu.setText("Siap dalam " + menu.getEstimatedCookingTime() + " menit");

        double rating = menu.getAverageRating();
        int totalReviews = menu.getTotalReviews();

        if (totalReviews > 0) {
            tvRatingMenu.setText(String.format(Locale.getDefault(), "%.1f", rating));
            tvJumlahUlasan.setText("(" + totalReviews + " ulasan)");
        } else {
            tvRatingMenu.setText("Belum dinilai");
            tvJumlahUlasan.setText("Belum ada ulasan");
        }

        String imageUrl = menu.getImage();
        if (imageUrl != null && !imageUrl.startsWith("http")) {
            imageUrl = "https://nonephemerally-nonrevolving-judie.ngrok-free.dev/storage/" + imageUrl;
        }

        Glide.with(DetailMenuActivity.this)
                .load(imageUrl)
                .placeholder(R.drawable.makanan)
                .error(R.drawable.makanan)
                .into(imgFood);
    }

    private void showErrorOnce(String message) {
        if (!isErrorShown) {
            isErrorShown = true;
            Toast.makeText(this, message, Toast.LENGTH_SHORT).show();
        }
    }

    private String formatRupiah(double harga) {
        NumberFormat fmt = NumberFormat.getCurrencyInstance(new Locale("id", "ID"));
        return fmt.format(harga).replace(",00", "");
    }
}