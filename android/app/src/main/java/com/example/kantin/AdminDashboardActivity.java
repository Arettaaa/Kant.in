package com.example.kantin;

import android.annotation.SuppressLint;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.view.View;
import android.widget.CheckBox;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.example.kantin.model.response.AdminOrderListResponse;
import com.example.kantin.model.response.BaseResponse;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;

import java.util.ArrayList;
import java.util.List;

import okhttp3.MediaType;
import okhttp3.RequestBody;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

@SuppressLint("SetTextI18n")
public class AdminDashboardActivity extends AppCompatActivity {

    private CheckBox switchKantin;
    private View overlayTutup;
    private TextView tvStatusBadge;
    private RecyclerView rvOrders;

    private OrderMasukAdapter adapter;
    private final List<ApiOrder> pendingOrders = new ArrayList<>();

    private String canteenId;
    private ApiService apiService;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_admin_dashboard);

        initViews();

        SharedPreferences prefs = getSharedPreferences("KantinApp", MODE_PRIVATE);
        String token = prefs.getString("TOKEN", "");
        canteenId = prefs.getString("CANTEEN_ID", "");

        apiService = ApiClient.getAuthClient(token).create(ApiService.class);

        rvOrders.setLayoutManager(new LinearLayoutManager(this));
        adapter = new OrderMasukAdapter(this, pendingOrders);
        rvOrders.setAdapter(adapter);

        setupSwitchListener();
        fetchPendingOrders();
    }

    private void initViews() {
        switchKantin = findViewById(R.id.switch_kantin_status);
        overlayTutup = findViewById(R.id.view_overlay_tutup);
        tvStatusBadge = findViewById(R.id.tv_status_badge);
        rvOrders = findViewById(R.id.rv_orders);
    }

    // ==========================================
    // 1. API CALL: Ambil Pesanan Masuk (Pending)
    // ==========================================
    private void fetchPendingOrders() {
        // PERBAIKAN: Menggunakan diamond <> (singkat) sesuai saran Android Studio
        apiService.getAdminOrders(canteenId, "pending").enqueue(new Callback<>() {
            @SuppressLint("NotifyDataSetChanged")
            @Override
            public void onResponse(@NonNull Call<AdminOrderListResponse> call, @NonNull Response<AdminOrderListResponse> response) {
                if (response.isSuccessful() && response.body() != null && response.body().isSuccess()) {
                    pendingOrders.clear();

                    if (response.body().getData() != null) {
                        pendingOrders.addAll(response.body().getData());
                    }
                    adapter.notifyDataSetChanged();
                } else {
                    Toast.makeText(AdminDashboardActivity.this, "Gagal memuat pesanan", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(@NonNull Call<AdminOrderListResponse> call, @NonNull Throwable t) {
                Toast.makeText(AdminDashboardActivity.this, "Koneksi Error: " + t.getMessage(), Toast.LENGTH_SHORT).show();
            }
        });
    }

    // ==========================================
    // 2. API CALL: Update Status Kantin
    // ==========================================
    @SuppressWarnings("deprecation")
    private void setupSwitchListener() {
        switchKantin.setOnCheckedChangeListener((buttonView, isChecked) -> {
            updateKantinUI(isChecked);

            RequestBody method = RequestBody.create(MediaType.parse("text/plain"), "PUT");
            RequestBody isOpen = RequestBody.create(MediaType.parse("text/plain"), isChecked ? "1" : "0");

            // PERBAIKAN: Menggunakan diamond <> (singkat) sesuai saran Android Studio
            apiService.toggleCanteenOpen(canteenId, method, isOpen).enqueue(new Callback<>() {
                @Override
                public void onResponse(@NonNull Call<BaseResponse> call, @NonNull Response<BaseResponse> response) {
                    if (response.isSuccessful() && response.body() != null && response.body().isSuccess()) {
                        Toast.makeText(AdminDashboardActivity.this, response.body().getMessage(), Toast.LENGTH_SHORT).show();
                    } else {
                        switchKantin.setChecked(!isChecked);
                        Toast.makeText(AdminDashboardActivity.this, "Gagal mengubah status kantin", Toast.LENGTH_SHORT).show();
                    }
                }

                @Override
                public void onFailure(@NonNull Call<BaseResponse> call, @NonNull Throwable t) {
                    switchKantin.setChecked(!isChecked);
                    Toast.makeText(AdminDashboardActivity.this, "Koneksi Error", Toast.LENGTH_SHORT).show();
                }
            });
        });
    }

    private void updateKantinUI(boolean isChecked) {
        if (isChecked) {
            overlayTutup.setVisibility(View.GONE);
            tvStatusBadge.setText("MENERIMA PESANAN");
            tvStatusBadge.setTextColor(0xFF00B050);
        } else {
            overlayTutup.setVisibility(View.VISIBLE);
            tvStatusBadge.setText("DIJEDA");
            tvStatusBadge.setTextColor(0xFF888888);
        }
    }
}