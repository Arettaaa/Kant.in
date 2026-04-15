package com.example.kantin;

import android.os.Bundle;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;
import android.widget.ImageView;
import android.widget.TextView;
import androidx.appcompat.app.AppCompatActivity;

public class CancelOrderActivity extends AppCompatActivity {

    private ImageView btnBack;
    private TextView tvOrderId, tvCustomerName, tvTime, tvQuantity, tvMenuName, tvNotes;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_cancel_order);

        // 1. Inisialisasi View
        initViews();

        // 2. Set listener untuk tombol kembali
        btnBack.setOnClickListener(v -> {
            // Menutup activity ini dan kembali ke halaman sebelumnya (misal: Detail Pesanan atau Home)
            finish();
        });

        // 3. (Opsional) Mengambil data dari Intent yang dikirim oleh halaman sebelumnya
        // loadDataFromIntent();
    }

    private void initViews() {
        btnBack = findViewById(R.id.btn_back);
        tvOrderId = findViewById(R.id.tv_order_id);
        tvCustomerName = findViewById(R.id.tv_customer_name);
        tvTime = findViewById(R.id.tv_time);
        tvQuantity = findViewById(R.id.tv_quantity);
        tvMenuName = findViewById(R.id.tv_menu_name);
        tvNotes = findViewById(R.id.tv_notes);
    }

    private void loadDataFromIntent() {
        // Contoh implementasi jika data dikirim melalui intent
        if (getIntent() != null) {
            String orderId = getIntent().getStringExtra("ORDER_ID");
            String customerName = getIntent().getStringExtra("CUSTOMER_NAME");
            String menuName = getIntent().getStringExtra("MENU_NAME");
            String notes = getIntent().getStringExtra("NOTES");

            if(orderId != null) tvOrderId.setText(orderId);
            if(customerName != null) tvCustomerName.setText(customerName);
            if(menuName != null) tvMenuName.setText(menuName);
            if(notes != null) tvNotes.setText("Catatan: " + notes);
        }
    }
}