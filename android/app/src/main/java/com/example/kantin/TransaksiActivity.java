package com.example.kantin;

import android.graphics.Color;
import android.graphics.Typeface;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.widget.EditText;
import android.widget.ImageButton;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.example.kantin.model.TransactionOrder;
import com.example.kantin.model.response.TransactionListResponse;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;
import com.example.kantin.utils.SessionManager;

import java.text.NumberFormat;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.List;
import java.util.Locale;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class TransaksiActivity extends AppCompatActivity {

    // View elements
    private TextView tvTotalPendapatan, tvTotalPesananSelesai, tvDate;
    private TextView tabHariIni, tabMingguIni, tabBulanIni;
    private EditText etSearch;
    private RecyclerView rvTransaksi;
    private ImageButton btnBack, btnFilter, btnDownload;

    private TransactionAdapter adapter;
    private List<TransactionOrder> allOrders = new ArrayList<>();

    // Status Filter Default
    private String currentPeriod = "hari_ini";
    private String currentStatusFilter = "semua"; // "semua", "completed", "cancelled"

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_transaksi_admin);

        initViews();
        setupRecyclerView();
        setupListeners();
        setCurrentDateLabel();

        // Memuat data awal dari API
        fetchTransactions();
    }

    private void initViews() {
        tvTotalPendapatan = findViewById(R.id.tvTotalPendapatan);
        tvTotalPesananSelesai = findViewById(R.id.tvTotalPesananSelesai);
        tvDate = findViewById(R.id.tvDate);

        tabHariIni = findViewById(R.id.tabHariIni);
        tabMingguIni = findViewById(R.id.tabMingguIni);
        tabBulanIni = findViewById(R.id.tabBulanIni);

        etSearch = findViewById(R.id.etSearch);
        rvTransaksi = findViewById(R.id.rvTransaksi);
        btnBack = findViewById(R.id.btnBack);
        btnFilter = findViewById(R.id.btnFilter);
        btnDownload = findViewById(R.id.btnDownload); // Tombol Export Pojok Kanan Atas
    }

    private void setupRecyclerView() {
        rvTransaksi.setLayoutManager(new LinearLayoutManager(this));
        adapter = new TransactionAdapter(new ArrayList<>());
        rvTransaksi.setAdapter(adapter);
    }

    private void setCurrentDateLabel() {
        SimpleDateFormat sdf = new SimpleDateFormat("MMM dd, yyyy", Locale.getDefault());
        tvDate.setText(sdf.format(new Date()));
    }

    private void setupListeners() {
        btnBack.setOnClickListener(v -> finish());

        // 1. PANGGIL FRAGMENT FILTER
        btnFilter.setOnClickListener(v -> {
            FilterBottomSheetFragment filterSheet = new FilterBottomSheetFragment(currentStatusFilter, status -> {
                // Callback ini dipanggil saat user menekan 'Terapkan' di dalam fragment
                this.currentStatusFilter = status;
                applyFilters(etSearch.getText().toString(), currentPeriod);
            });
            filterSheet.show(getSupportFragmentManager(), "FilterSheet");
        });

        // 2. PANGGIL FRAGMENT EXPORT
        btnDownload.setOnClickListener(v -> {
            ExportBottomSheetFragment exportSheet = new ExportBottomSheetFragment();
            exportSheet.show(getSupportFragmentManager(), "ExportSheet");
        });

        // Listener untuk Tabs Periode
        tabHariIni.setOnClickListener(v -> updateTabSelection(tabHariIni, "hari_ini"));
        tabMingguIni.setOnClickListener(v -> updateTabSelection(tabMingguIni, "minggu_ini"));
        tabBulanIni.setOnClickListener(v -> updateTabSelection(tabBulanIni, "bulan_ini"));

        // Listener untuk Pencarian (Search Real-time)
        etSearch.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {}

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
                applyFilters(s.toString(), currentPeriod);
            }

            @Override
            public void afterTextChanged(Editable s) {}
        });
    }

    private void updateTabSelection(TextView selectedTab, String period) {
        currentPeriod = period;

        resetTabStyle(tabHariIni);
        resetTabStyle(tabMingguIni);
        resetTabStyle(tabBulanIni);

        selectedTab.setTextColor(Color.BLACK);
        selectedTab.setTypeface(null, Typeface.BOLD);
        selectedTab.setBackgroundResource(R.drawable.bg_tab_selected);

        applyFilters(etSearch.getText().toString(), currentPeriod);
    }

    private void resetTabStyle(TextView tab) {
        tab.setTextColor(Color.parseColor("#757575"));
        tab.setTypeface(null, Typeface.NORMAL);
        tab.setBackgroundResource(0);
    }

    // ====================================================================
    // API & FILTER LOGIC
    // ====================================================================
    private void fetchTransactions() {
        SessionManager sessionManager = new SessionManager(this);
        String token = sessionManager.getToken();
        String canteenId = sessionManager.getCanteenId(); // ✅ method ini ada di SessionManager kamu

        if (token.isEmpty() || canteenId.isEmpty()) {
            Toast.makeText(this, "Sesi tidak valid, silakan login ulang.", Toast.LENGTH_SHORT).show();
            finish();
            return;
        }

        ApiService api = ApiClient.getAuthClient(token).create(ApiService.class);
        api.getTransactions(canteenId).enqueue(new Callback<TransactionListResponse>() {
            @Override
            public void onResponse(@NonNull Call<TransactionListResponse> call, @NonNull Response<TransactionListResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    allOrders = response.body().getData().getOrders();
                    applyFilters("", currentPeriod);
                }
            }

            @Override
            public void onFailure(@NonNull Call<TransactionListResponse> call, @NonNull Throwable t) {
                Toast.makeText(TransaksiActivity.this, "Gagal: " + t.getMessage(), Toast.LENGTH_SHORT).show();
            }
        });
    }
    private void applyFilters(String query, String period) {
        List<TransactionOrder> filteredList = new ArrayList<>();
        SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd'T'HH:mm:ss.SSSSSS'Z'", Locale.getDefault());
        Calendar calNow = Calendar.getInstance();

        for (TransactionOrder order : allOrders) {
            boolean matchesQuery = true;
            boolean matchesPeriod = false;
            boolean matchesStatus = true;

            // 1. Search Filter
            if (!query.isEmpty()) {
                String searchLower = query.toLowerCase();
                matchesQuery = order.getCustomerName().toLowerCase().contains(searchLower) ||
                        order.getOrderCode().toLowerCase().contains(searchLower);
            }

            // 2. Period Filter
            try {
                if (order.getCreatedAt() != null) {
                    Date orderDate = sdf.parse(order.getCreatedAt());
                    if (orderDate != null) {
                        Calendar calOrder = Calendar.getInstance();
                        calOrder.setTime(orderDate);

                        switch (period) {
                            case "hari_ini":
                                matchesPeriod = calNow.get(Calendar.YEAR) == calOrder.get(Calendar.YEAR) &&
                                        calNow.get(Calendar.DAY_OF_YEAR) == calOrder.get(Calendar.DAY_OF_YEAR);
                                break;
                            case "minggu_ini":
                                long diff = calNow.getTimeInMillis() - calOrder.getTimeInMillis();
                                long days = diff / (24 * 60 * 60 * 1000);
                                matchesPeriod = (days >= 0 && days <= 7);
                                break;
                            case "bulan_ini":
                                matchesPeriod = calNow.get(Calendar.YEAR) == calOrder.get(Calendar.YEAR) &&
                                        calNow.get(Calendar.MONTH) == calOrder.get(Calendar.MONTH);
                                break;
                        }
                    }
                }
            } catch (ParseException e) {
                e.printStackTrace();
            }

            // 3. Status Filter (Dari Bottom Sheet)
            if (!currentStatusFilter.equals("semua")) {
                if (order.getStatus() != null) {
                    matchesStatus = order.getStatus().equalsIgnoreCase(currentStatusFilter);
                } else {
                    matchesStatus = false;
                }
            }

            // Gabungkan
            if (matchesQuery && matchesPeriod && matchesStatus) {
                filteredList.add(order);
            }
        }

        adapter.updateData(filteredList);
        updateSummaryCards(filteredList);
    }

    private void updateSummaryCards(List<TransactionOrder> list) {
        double totalRevenue = 0;
        int completedCount = 0;

        for (TransactionOrder order : list) {
            if (order.getStatus() != null && order.getStatus().equalsIgnoreCase("completed")) {
                totalRevenue += order.getTotalAmount();
                completedCount++;
            }
        }

        NumberFormat nf = NumberFormat.getCurrencyInstance(new Locale("id", "ID"));
        tvTotalPendapatan.setText(nf.format(totalRevenue));
        tvTotalPesananSelesai.setText(String.valueOf(completedCount));
    }
}