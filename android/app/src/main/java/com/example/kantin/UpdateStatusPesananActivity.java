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

    // 🔥 Variabel untuk nyimpen status sementara sebelum tombol ditekan
    private String selectedStatus = "processing";

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

        SessionManager session = new SessionManager(this);
        apiService = ApiClient.getAuthClient(session.getToken()).create(ApiService.class);
    }

    private void getIntentData() {
        Intent intent = getIntent();

        if (intent != null) {
            orderId = intent.getStringExtra("ORDER_ID");
            currentOrder = (OrderModel) intent.getSerializableExtra("ORDER_DATA");
        }

        SessionManager session = new SessionManager(this);
        canteenId = session.getCanteenId();

        updateUIByStatus("processing");
        populateOrderData();
    }

    private void populateOrderData() {
        if (currentOrder != null) {
            tvOrderId.setText(currentOrder.getOrderCode() != null ? currentOrder.getOrderCode() : "-");

            if (currentOrder.getCustomerSnapshot() != null && currentOrder.getCustomerSnapshot().getName() != null) {
                tvCustomerName.setText(currentOrder.getCustomerSnapshot().getName());
            } else {
                tvCustomerName.setText("Pelanggan");
            }

            if (currentOrder.getDeliveryDetails() != null && currentOrder.getDeliveryDetails().getMethod() != null) {
                String method = currentOrder.getDeliveryDetails().getMethod();
                tvOrderType.setText(method.equals("delivery") ? "Antar Kurir" : "Ambil Sendiri");
            } else {
                tvOrderType.setText("Ambil Sendiri");
            }

            if (currentOrder.getCreatedAt() != null && currentOrder.getCreatedAt().length() >= 16) {
                tvTime.setText(currentOrder.getCreatedAt().substring(11, 16));
            } else {
                tvTime.setText("-");
            }

            if (currentOrder.getItems() != null) {
                CancelOrderMenuAdapter adapter = new CancelOrderMenuAdapter(currentOrder.getItems());
                rvMenuPesanan.setAdapter(adapter);
            }
        }
    }

    private void setupListeners() {
        btnBack.setOnClickListener(v -> getOnBackPressedDispatcher().onBackPressed());

        // 🔥 KLIK KARTU DIMASAK: Hanya ubah UI jadi oranye, tombol ilang
        cardDimasak.setOnClickListener(v -> {
            selectedStatus = "processing";
            updateUIByStatus("processing");
        });

        // 🔥 KLIK KARTU SIAP: Hanya ubah UI jadi hijau, munculin tombol (API BELUM DITEMBAK)
        cardSiap.setOnClickListener(v -> {
            selectedStatus = "ready";
            updateUIByStatus("ready");
        });

        // 🔥 KLIK TOMBOL BAWAH: Nembak API untuk ubah jadi "ready"
        btnSelesaiPesanan.setOnClickListener(v -> {
            if (selectedStatus.equals("ready")) {
                updateStatusToBackend("ready");
            }
        });
    }

    private void updateStatusToBackend(String newStatus) {
        // Matikan tombol sementara biar gak diklik dobel
        btnSelesaiPesanan.setEnabled(false);
        btnSelesaiPesanan.setText("Menyimpan Status...");

        UpdateStatusOrderRequest request = new UpdateStatusOrderRequest(newStatus);

        Call<BaseResponse> call = apiService.updateOrderStatus(canteenId, orderId, request);
        call.enqueue(new Callback<BaseResponse>() {
            @Override
            public void onResponse(Call<BaseResponse> call, Response<BaseResponse> response) {
                btnSelesaiPesanan.setEnabled(true);

                if (response.isSuccessful() && response.body() != null && response.body().isSuccess()) {
                    Toast.makeText(UpdateStatusPesananActivity.this, "Pesanan siap diambil/diantar!", Toast.LENGTH_SHORT).show();
                    finish(); // 🔥 Balik otomatis ke halaman sebelumnya (Dashboard)
                } else {
                    btnSelesaiPesanan.setText("Konfirmasi Pesanan Siap");
                    Toast.makeText(UpdateStatusPesananActivity.this, "Gagal memperbarui status", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<BaseResponse> call, Throwable t) {
                btnSelesaiPesanan.setEnabled(true);
                btnSelesaiPesanan.setText("Konfirmasi Pesanan Siap");
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
            btnSelesaiPesanan.setVisibility(View.GONE); // Sembunyikan tombol

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

            tvStatusKeterangan.setText("Klik tombol di bawah untuk memberitahu pelanggan.");
            btnSelesaiPesanan.setText("Konfirmasi Pesanan Siap"); // Ubah teks tombol
            btnSelesaiPesanan.setVisibility(View.VISIBLE); // Munculkan tombol
        }
    }
}