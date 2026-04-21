package com.example.kantin;

import android.content.Intent;
import android.graphics.Color;
import android.os.Bundle;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.example.kantin.CancelOrderMenuAdapter;
import com.example.kantin.model.OrderModel;
import com.example.kantin.model.request.UpdateStatusOrderRequest;
import com.example.kantin.model.response.BaseResponse;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;
import com.example.kantin.utils.SessionManager;
import com.google.android.material.button.MaterialButton;
import com.google.android.material.card.MaterialCardView;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class UpdateStatusPesananActivity extends AppCompatActivity {

    private ImageView btnBack;
    private TextView tvOrderId, tvCustomerName, tvOrderType, tvTime, tvStatusKeterangan;
    private MaterialCardView cardDimasak, cardSiap;
    private ImageView ivDimasak, ivSiap;
    private TextView tvDimasak, tvSiap;
    private MaterialButton btnSelesaiPesanan;
    private RecyclerView rvMenuPesanan;

    private String orderId = "";
    private String canteenId = "";
    private OrderModel currentOrder;
    private ApiService apiService;

    // Warna State UI
    private final String COLOR_ORANGE = "#FF6F00";
    private final String COLOR_GREEN = "#00C950";
    private final String COLOR_WHITE = "#FFFFFF";
    private final String COLOR_GREY = "#BDBDBD";
    private final String COLOR_GREY_BORDER = "#E0E0E0";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_update_status_pesanan);

        initViews();
        getIntentData();
        setupListeners();
    }

    private void initViews() {
        btnBack = findViewById(R.id.btnBack);
        tvOrderId = findViewById(R.id.tvOrderId);
        tvStatusKeterangan = findViewById(R.id.tvStatusKeterangan);
        tvCustomerName = findViewById(R.id.tvCustomerName);
        tvOrderType = findViewById(R.id.tvOrderType);
        tvTime = findViewById(R.id.tvTime);

        cardDimasak = findViewById(R.id.cardDimasak);
        ivDimasak = findViewById(R.id.ivDimasak);
        tvDimasak = findViewById(R.id.tvDimasak);

        cardSiap = findViewById(R.id.cardSiap);
        ivSiap = findViewById(R.id.ivSiap);
        tvSiap = findViewById(R.id.tvSiap);

        btnSelesaiPesanan = findViewById(R.id.btnSelesaiPesanan);

        rvMenuPesanan = findViewById(R.id.rvMenuPesanan);
        rvMenuPesanan.setLayoutManager(new LinearLayoutManager(this));
        rvMenuPesanan.setNestedScrollingEnabled(false);

        // 🔥 INI YANG HARUS DIGANTI
        SessionManager session = new SessionManager(this);
        apiService = ApiClient.getAuthClient(session.getToken()).create(ApiService.class);
    }
    private void getIntentData() {
        Intent intent = getIntent();

        if (intent != null) {
            orderId = intent.getStringExtra("ORDER_ID");
            currentOrder = (OrderModel) intent.getSerializableExtra("ORDER_DATA");
        }

        // 🔥 ambil dari session (fix utama)
        SessionManager session = new SessionManager(this);
        canteenId = session.getCanteenId();

        updateUIByStatus("processing");
        populateOrderData();
    }

    private void populateOrderData() {
        if (currentOrder != null) {
            // 1. Cek aman untuk Order Code
            tvOrderId.setText(currentOrder.getOrderCode() != null ? currentOrder.getOrderCode() : "-");

            // 2. Cek aman untuk Customer Name
            if (currentOrder.getCustomerSnapshot() != null && currentOrder.getCustomerSnapshot().getName() != null) {
                tvCustomerName.setText(currentOrder.getCustomerSnapshot().getName());
            } else {
                tvCustomerName.setText("Pelanggan");
            }

            // 3. Cek aman untuk Delivery Method
            if (currentOrder.getDeliveryDetails() != null && currentOrder.getDeliveryDetails().getMethod() != null) {
                String method = currentOrder.getDeliveryDetails().getMethod();
                tvOrderType.setText(method.equals("delivery") ? "Antar Kurir" : "Ambil Sendiri");
            } else {
                tvOrderType.setText("Ambil Sendiri"); // Default kalau kosong
            }

            // 4. Waktu
            if (currentOrder.getCreatedAt() != null && currentOrder.getCreatedAt().length() >= 16) {
                tvTime.setText(currentOrder.getCreatedAt().substring(11, 16));
            } else {
                tvTime.setText("-");
            }

            // 5. Menu
            if (currentOrder.getItems() != null) {
                CancelOrderMenuAdapter adapter = new CancelOrderMenuAdapter(currentOrder.getItems());
                rvMenuPesanan.setAdapter(adapter);
            }
        }
    }
    private void setupListeners() {
        btnBack.setOnClickListener(v -> getOnBackPressedDispatcher().onBackPressed());

        // Klik kartu SIAP (Mengubah status di DB menjadi 'ready')
        cardSiap.setOnClickListener(v -> updateStatusToBackend("ready"));

        // Klik tombol SELESAI (Mengubah status di DB menjadi 'completed')
        btnSelesaiPesanan.setOnClickListener(v -> updateStatusToBackend("completed"));
    }

    private void updateStatusToBackend(String newStatus) {
        UpdateStatusOrderRequest request = new UpdateStatusOrderRequest(newStatus);

        // Memanggil PUT /canteens/{id}/orders/{orderId}/statuses
        Call<BaseResponse> call = apiService.updateOrderStatus(canteenId, orderId, request);
        call.enqueue(new Callback<BaseResponse>() {
            @Override
            public void onResponse(Call<BaseResponse> call, Response<BaseResponse> response) {
                if (response.isSuccessful() && response.body() != null && response.body().isSuccess()) {
                    updateUIByStatus(newStatus);

                    if (newStatus.equals("completed")) {
                        Toast.makeText(UpdateStatusPesananActivity.this, "Pesanan Selesai & Masuk Riwayat", Toast.LENGTH_SHORT).show();
                        finish();
                    }
                } else {
                    Toast.makeText(UpdateStatusPesananActivity.this, "Gagal memperbarui status", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<BaseResponse> call, Throwable t) {
                Toast.makeText(UpdateStatusPesananActivity.this, "Kesalahan Jaringan: " + t.getMessage(), Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void updateUIByStatus(String status) {
        if (status.equals("processing")) {
            // UI State: DIMASAK (Aktif Oranye)
            cardDimasak.setCardBackgroundColor(Color.parseColor(COLOR_ORANGE));
            ivDimasak.setColorFilter(Color.parseColor(COLOR_WHITE));
            tvDimasak.setTextColor(Color.parseColor(COLOR_WHITE));
            cardDimasak.setCardElevation(8f);

            // UI State: SIAP (Mati)
            cardSiap.setCardBackgroundColor(Color.parseColor(COLOR_WHITE));
            ivSiap.setColorFilter(Color.parseColor(COLOR_GREY));
            tvSiap.setTextColor(Color.parseColor(COLOR_GREY));
            cardSiap.setStrokeColor(Color.parseColor(COLOR_GREY_BORDER));
            cardSiap.setCardElevation(0f);

            tvStatusKeterangan.setText("Pelanggan melihat: Pesananmu sedang disiapkan...");
            btnSelesaiPesanan.setVisibility(View.GONE);

        } else if (status.equals("ready")) {
            // UI State: DIMASAK (Mati)
            cardDimasak.setCardBackgroundColor(Color.parseColor(COLOR_WHITE));
            ivDimasak.setColorFilter(Color.parseColor(COLOR_GREY));
            tvDimasak.setTextColor(Color.parseColor(COLOR_GREY));
            cardDimasak.setStrokeColor(Color.parseColor(COLOR_GREY_BORDER));
            cardDimasak.setCardElevation(0f);

            // UI State: SIAP (Aktif Hijau)
            cardSiap.setCardBackgroundColor(Color.parseColor(COLOR_GREEN));
            ivSiap.setColorFilter(Color.parseColor(COLOR_WHITE));
            tvSiap.setTextColor(Color.parseColor(COLOR_WHITE));
            cardSiap.setCardElevation(8f);

            tvStatusKeterangan.setText("Pelanggan melihat: Makananmu sudah siap!");
            btnSelesaiPesanan.setVisibility(View.VISIBLE);
        }
    }
}