package com.example.kantin; // Sesuaikan package kamu

import android.content.Intent;
import android.os.Bundle;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.example.kantin.R;
import com.example.kantin.CancelOrderMenuAdapter;
import com.example.kantin.model.OrderModel;

public class CancelOrderActivity extends AppCompatActivity {

    private ImageView btnBack;
    private TextView tvOrderId, tvCustomerName, tvTime;
    private RecyclerView rvOrderItems;

    private OrderModel orderData; // Menyimpan data pesanan yang dikirim dari DetailPesanan

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_cancel_order);

        initViews();
        getIntentData();
        setupListeners();
    }

    private void initViews() {
        btnBack = findViewById(R.id.btn_back);
        tvOrderId = findViewById(R.id.tv_order_id);
        tvCustomerName = findViewById(R.id.tv_customer_name);
        tvTime = findViewById(R.id.tv_time);

        rvOrderItems = findViewById(R.id.rv_order_items);
        rvOrderItems.setLayoutManager(new LinearLayoutManager(this));
        // Mencegah scroll ganda jika RecyclerView berada di dalam NestedScrollView
        rvOrderItems.setNestedScrollingEnabled(false);
    }

    private void getIntentData() {
        Intent intent = getIntent();
        if (intent != null && intent.hasExtra("ORDER_DATA")) {
            // Menangkap objek OrderModel yang dikirim dari DetailPesanan
            orderData = (OrderModel) intent.getSerializableExtra("ORDER_DATA");

            if (orderData != null) {
                populateUI();
            }
        }
    }

    private void populateUI() {
        // 1. Set Nomor Order
        tvOrderId.setText(orderData.getOrderCode() != null ? orderData.getOrderCode() : "#ORD-XXX");

        // 2. Set Nama Customer
        if(orderData.getCustomerSnapshot() != null){
            tvCustomerName.setText(orderData.getCustomerSnapshot().getName());
        }

        // 3. Set Waktu (Format sederhana ambil jam:menit dari created_at)
        if(orderData.getCreatedAt() != null && orderData.getCreatedAt().length() >= 16){
            tvTime.setText(orderData.getCreatedAt().substring(11, 16));
        } else {
            tvTime.setText("-");
        }

        // 4. Set Daftar Menu ke RecyclerView
        if(orderData.getItems() != null && !orderData.getItems().isEmpty()){
            CancelOrderMenuAdapter adapter = new CancelOrderMenuAdapter(orderData.getItems());
            rvOrderItems.setAdapter(adapter);
        }
    }

    private void setupListeners() {
        // Tombol kembali
        btnBack.setOnClickListener(v -> getOnBackPressedDispatcher().onBackPressed());
    }
}