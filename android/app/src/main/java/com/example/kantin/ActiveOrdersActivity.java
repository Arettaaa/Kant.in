package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.Toast;
import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import com.example.kantin.model.response.OrderListResponse;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;
import com.example.kantin.utils.SessionManager;
import java.util.ArrayList;
import java.util.List;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class ActiveOrdersActivity extends AppCompatActivity {

    private ImageView btnBack;
    private LinearLayout tabRiwayat;
    private RecyclerView rvActiveOrders;
    private ActiveOrderAdapter adapter;
    private List<OrderListResponse.OrderItem> activeOrderList = new ArrayList<>();

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_activeorders);

        btnBack = findViewById(R.id.btnBack);
        tabRiwayat = findViewById(R.id.tabRiwayat);
        rvActiveOrders = findViewById(R.id.rvActiveOrders);

        // Setup RecyclerView
        adapter = new ActiveOrderAdapter(this, activeOrderList);
        rvActiveOrders.setLayoutManager(new LinearLayoutManager(this));
        rvActiveOrders.setAdapter(adapter);

        btnBack.setOnClickListener(v -> onBackPressed());

        tabRiwayat.setOnClickListener(v -> {
            Intent intent = new Intent(ActiveOrdersActivity.this, HistoryActivity.class);
            startActivity(intent);
            overridePendingTransition(0, 0);
            finish();
        });

        // Load data dari API
        loadActiveOrders();
    }

    @Override
    protected void onResume() {
        super.onResume();
        loadActiveOrders(); // Refresh setiap kali halaman aktif
    }

    private void loadActiveOrders() {
        String token = new SessionManager(this).getToken();
        ApiClient.getAuthClient(token).create(ApiService.class)
                .getOrderHistory()
                .enqueue(new Callback<OrderListResponse>() {
                    @Override
                    public void onResponse(Call<OrderListResponse> call, Response<OrderListResponse> response) {
                        if (response.isSuccessful() && response.body() != null) {
                            List<OrderListResponse.OrderItem> allOrders = response.body().getData();
                            activeOrderList.clear();

                            // Filter hanya pending, processing, ready
                            for (OrderListResponse.OrderItem order : allOrders) {
                                String status = order.getStatus();
                                if ("pending".equals(status) || "processing".equals(status) || "ready".equals(status)) {
                                    activeOrderList.add(order);
                                }
                            }
                            adapter.notifyDataSetChanged();
                        } else {
                            Toast.makeText(ActiveOrdersActivity.this, "Gagal memuat pesanan", Toast.LENGTH_SHORT).show();
                        }
                    }

                    @Override
                    public void onFailure(Call<OrderListResponse> call, Throwable t) {
                        Toast.makeText(ActiveOrdersActivity.this, "Error jaringan", Toast.LENGTH_SHORT).show();
                    }
                });
    }
}