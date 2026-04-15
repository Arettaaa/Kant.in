package com.example.kantin;

import android.os.Bundle;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.bumptech.glide.Glide;
import com.example.kantin.model.response.MenuDetailResponse;
import com.example.kantin.model.response.MenuListResponse;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;

import java.text.NumberFormat;
import java.util.Locale;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class DetailMenuActivity extends AppCompatActivity {

    private ImageView imgFood, btnBack, btnMinus, btnPlus;
    private TextView tvNamaMenu, tvHarga, tvDeskripsi, tvQuantity, tvEstimasiWaktu;
    private LinearLayout btnTambahKeranjang;

    private int quantity = 1;
    private double basePrice = 0;
    private String menuName = "";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_detailmakananpelanggan);

        // Inisialisasi view (sama seperti sebelumnya + tambahan)
        btnBack            = findViewById(R.id.btnBack);
        btnMinus           = findViewById(R.id.btnMinus);
        btnPlus            = findViewById(R.id.btnPlus);
        tvQuantity         = findViewById(R.id.tvQuantity);
        btnTambahKeranjang = findViewById(R.id.btnTambahKeranjang);
        imgFood            = findViewById(R.id.imgFood);
        tvNamaMenu         = findViewById(R.id.tvNamaMenu);
        tvHarga            = findViewById(R.id.tvHarga);
        tvDeskripsi        = findViewById(R.id.tvDeskripsi);
        tvEstimasiWaktu = findViewById(R.id.tvEstimasiWaktu);

        // Tombol back — sama seperti sebelumnya
        btnBack.setOnClickListener(v -> onBackPressed());

        // Kurangi qty — sama seperti sebelumnya
        btnMinus.setOnClickListener(v -> {
            if (quantity > 1) {
                quantity--;
                tvQuantity.setText(String.valueOf(quantity));
            }
        });

        // Tambah qty — sama seperti sebelumnya
        btnPlus.setOnClickListener(v -> {
            quantity++;
            tvQuantity.setText(String.valueOf(quantity));
        });

        // Tambah ke keranjang
        btnTambahKeranjang.setOnClickListener(v -> {
            Toast.makeText(this, quantity + "x " + menuName + " masuk ke keranjang!", Toast.LENGTH_SHORT).show();
            finish();
        });

        // Ambil MENU_ID dari Intent, lalu fetch ke API
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
                            bindData(response.body().getData());
                        } else {
                            Toast.makeText(DetailMenuActivity.this, "Gagal memuat detail menu", Toast.LENGTH_SHORT).show();
                        }
                    }

                    @Override
                    public void onFailure(Call<MenuDetailResponse> call, Throwable t) {
                        Toast.makeText(DetailMenuActivity.this, "Gagal terhubung ke server", Toast.LENGTH_SHORT).show();
                    }
                });
    }

    private void bindData(MenuListResponse.MenuItem menu) {
        menuName  = menu.getName();
        basePrice = menu.getPriceAsDouble();

        tvNamaMenu.setText(menuName);
        tvDeskripsi.setText(menu.getDescription());
        tvHarga.setText(formatRupiah(basePrice));

        // Estimasi waktu
        tvEstimasiWaktu.setText("Siap dalam " + menu.getEstimatedCookingTime() + " mnt");

        Glide.with(this)
                .load(menu.getImage())
                .placeholder(R.drawable.makanan)
                .into(imgFood);
    }

    private String formatRupiah(double harga) {
        NumberFormat fmt = NumberFormat.getCurrencyInstance(new Locale("id", "ID"));
        return fmt.format(harga).replace(",00", "");
    }
}