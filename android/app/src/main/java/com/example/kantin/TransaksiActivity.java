package com.example.kantin;

import android.graphics.Color;
import android.graphics.Typeface;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.Log;
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
import java.util.TimeZone;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class TransaksiActivity extends AppCompatActivity {

    // ✅ TAG untuk Logcat — filter dengan: adb logcat -s TRANSAKSI
    private static final String TAG = "TRANSAKSI";

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

        Log.d(TAG, "onCreate: TransaksiActivity dimulai");

        initViews();
        setupRecyclerView();
        setupListeners();
        setCurrentDateLabel();
        fetchTransactions();
    }

    private void initViews() {
        tvTotalPendapatan     = findViewById(R.id.tvTotalPendapatan);
        tvTotalPesananSelesai = findViewById(R.id.tvTotalPesananSelesai);
        tvDate                = findViewById(R.id.tvDate);
        tabHariIni            = findViewById(R.id.tabHariIni);
        tabMingguIni          = findViewById(R.id.tabMingguIni);
        tabBulanIni           = findViewById(R.id.tabBulanIni);
        etSearch              = findViewById(R.id.etSearch);
        rvTransaksi           = findViewById(R.id.rvTransaksi);
        btnBack               = findViewById(R.id.btnBack);
        btnFilter             = findViewById(R.id.btnFilter);
        btnDownload           = findViewById(R.id.btnDownload);
    }

    private void setupRecyclerView() {
        rvTransaksi.setLayoutManager(new LinearLayoutManager(this));
        adapter = new TransactionAdapter(new ArrayList<>());
        rvTransaksi.setAdapter(adapter);
        Log.d(TAG, "setupRecyclerView: adapter terpasang");
    }

    private void setCurrentDateLabel() {
        SimpleDateFormat sdf = new SimpleDateFormat("MMM dd, yyyy", Locale.getDefault());
        tvDate.setText(sdf.format(new Date()));
    }

    private void setupListeners() {
        btnBack.setOnClickListener(v -> finish());

        btnFilter.setOnClickListener(v -> {
            Log.d(TAG, "btnFilter: membuka filter, status saat ini = " + currentStatusFilter);
            FilterBottomSheetFragment filterSheet = new FilterBottomSheetFragment(currentStatusFilter, status -> {
                Log.d(TAG, "onFilterApplied: status dipilih = " + status);
                this.currentStatusFilter = status;
                applyFilters(etSearch.getText().toString(), currentPeriod);
            });
            filterSheet.show(getSupportFragmentManager(), "FilterSheet");
        });

        btnDownload.setOnClickListener(v -> {
            ExportBottomSheetFragment exportSheet = new ExportBottomSheetFragment();
            exportSheet.show(getSupportFragmentManager(), "ExportSheet");
        });

        tabHariIni.setOnClickListener(v  -> updateTabSelection(tabHariIni,  "hari_ini"));
        tabMingguIni.setOnClickListener(v -> updateTabSelection(tabMingguIni, "minggu_ini"));
        tabBulanIni.setOnClickListener(v  -> updateTabSelection(tabBulanIni,  "bulan_ini"));

        etSearch.addTextChangedListener(new TextWatcher() {
            @Override public void beforeTextChanged(CharSequence s, int start, int count, int after) {}
            @Override public void afterTextChanged(Editable s) {}

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
                Log.d(TAG, "search: query = \"" + s + "\"");
                applyFilters(s.toString(), currentPeriod);
            }
        });
    }

    private void updateTabSelection(TextView selectedTab, String period) {
        Log.d(TAG, "updateTabSelection: periode = " + period);
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
    // API
    // ====================================================================
    private void fetchTransactions() {
        SessionManager sessionManager = new SessionManager(this);
        String token     = sessionManager.getToken();
        String canteenId = sessionManager.getCanteenId();

        Log.d(TAG, "fetchTransactions: token=" + (token.isEmpty() ? "KOSONG" : "ada")
                + ", canteenId=" + canteenId);

        if (token.isEmpty() || canteenId.isEmpty()) {
            Log.e(TAG, "fetchTransactions: sesi tidak valid, keluar");
            Toast.makeText(this, "Sesi tidak valid, silakan login ulang.", Toast.LENGTH_SHORT).show();
            finish();
            return;
        }

        ApiService api = ApiClient.getAuthClient(token).create(ApiService.class);
        api.getTransactions(canteenId).enqueue(new Callback<TransactionListResponse>() {
            @Override
            public void onResponse(@NonNull Call<TransactionListResponse> call,
                                   @NonNull Response<TransactionListResponse> response) {
                Log.d(TAG, "onResponse: HTTP " + response.code());

                if (response.isSuccessful() && response.body() != null) {
                    List<TransactionOrder> orders = response.body().getData().getOrders();
                    allOrders = orders != null ? orders : new ArrayList<>();

                    Log.d(TAG, "onResponse: total order diterima = " + allOrders.size());

                    // Log tiap order untuk debug status
                    for (TransactionOrder o : allOrders) {
                        Log.d(TAG, "  order=" + o.getOrderCode()
                                + " | status=" + o.getStatus()
                                + " | created_at=" + o.getCreatedAt());
                    }

                    applyFilters("", currentPeriod);
                } else {
                    Log.e(TAG, "onResponse: response tidak sukses atau body null");
                    try {
                        if (response.errorBody() != null) {
                            Log.e(TAG, "errorBody: " + response.errorBody().string());
                        }
                    } catch (Exception e) {
                        Log.e(TAG, "gagal baca errorBody", e);
                    }
                }
            }

            @Override
            public void onFailure(@NonNull Call<TransactionListResponse> call, @NonNull Throwable t) {
                Log.e(TAG, "onFailure: " + t.getMessage(), t);
                Toast.makeText(TransaksiActivity.this, "Gagal: " + t.getMessage(), Toast.LENGTH_SHORT).show();
            }
        });
    }

    // ====================================================================
    // FILTER LOGIC
    // ====================================================================
    private void applyFilters(String query, String period) {
        List<TransactionOrder> filteredList = new ArrayList<>();

        // ✅ Parse UTC agar konsisten dengan format backend
        SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd'T'HH:mm:ss.SSSSSS'Z'", Locale.getDefault());
        sdf.setTimeZone(TimeZone.getTimeZone("UTC"));

        Calendar calNow = Calendar.getInstance();

        Log.d(TAG, "applyFilters: query=\"" + query + "\", periode=" + period
                + ", statusFilter=" + currentStatusFilter
                + ", totalOrder=" + allOrders.size());

        for (TransactionOrder order : allOrders) {
            boolean matchesQuery  = true;
            // ✅ FIX UTAMA: default TRUE agar order tetap tampil walau created_at null/gagal parse
            boolean matchesPeriod = true;
            boolean matchesStatus = true;

            // --- 1. Search Filter ---
            if (!query.isEmpty()) {
                String searchLower  = query.toLowerCase();
                String customerName = order.getCustomerName() != null ? order.getCustomerName().toLowerCase() : "";
                String orderCode    = order.getOrderCode()    != null ? order.getOrderCode().toLowerCase()    : "";
                matchesQuery = customerName.contains(searchLower) || orderCode.contains(searchLower);
            }

            // --- 2. Period Filter ---
            if (order.getCreatedAt() != null && !order.getCreatedAt().isEmpty()) {
                try {
                    Date orderDate = sdf.parse(order.getCreatedAt());
                    if (orderDate != null) {
                        Calendar calOrder = Calendar.getInstance();
                        calOrder.setTime(orderDate);

                        // ✅ Reset ke false HANYA setelah Date berhasil di-parse
                        matchesPeriod = false;

                        switch (period) {
                            case "hari_ini":
                                matchesPeriod =
                                        calNow.get(Calendar.YEAR)        == calOrder.get(Calendar.YEAR) &&
                                                calNow.get(Calendar.DAY_OF_YEAR) == calOrder.get(Calendar.DAY_OF_YEAR);
                                break;
                            case "minggu_ini":
                                long diff = calNow.getTimeInMillis() - calOrder.getTimeInMillis();
                                long days = diff / (24L * 60 * 60 * 1000);
                                matchesPeriod = (days >= 0 && days <= 7);
                                break;
                            case "bulan_ini":
                                matchesPeriod =
                                        calNow.get(Calendar.YEAR)  == calOrder.get(Calendar.YEAR) &&
                                                calNow.get(Calendar.MONTH) == calOrder.get(Calendar.MONTH);
                                break;
                        }

                        Log.d(TAG, "  period check [" + order.getOrderCode() + "]: "
                                + order.getCreatedAt() + " → matchesPeriod=" + matchesPeriod);
                    }
                } catch (ParseException e) {
                    // ✅ Kalau format tanggal berbeda dari yang diharapkan, log formatnya
                    Log.w(TAG, "  GAGAL parse created_at [" + order.getOrderCode() + "]: \""
                            + order.getCreatedAt() + "\" — order tetap ditampilkan");
                    // matchesPeriod tetap true (default), jadi order tidak dibuang
                }
            } else {
                Log.w(TAG, "  created_at null/kosong [" + order.getOrderCode() + "] — matchesPeriod=true (tampil semua)");
            }

            // --- 3. Status Filter ---
            if (!currentStatusFilter.equals("semua")) {
                if (order.getStatus() != null) {
                    matchesStatus = order.getStatus().equalsIgnoreCase(currentStatusFilter);
                } else {
                    matchesStatus = false;
                    Log.w(TAG, "  status null [" + order.getOrderCode() + "] — dibuang oleh filter status");
                }
            }

            boolean lolos = matchesQuery && matchesPeriod && matchesStatus;
            Log.d(TAG, "  [" + order.getOrderCode() + "] status=" + order.getStatus()
                    + " | query=" + matchesQuery
                    + " | period=" + matchesPeriod
                    + " | status=" + matchesStatus
                    + " → LOLOS=" + lolos);

            if (lolos) filteredList.add(order);
        }

        Log.d(TAG, "applyFilters: " + filteredList.size() + " order ditampilkan dari " + allOrders.size());

        adapter.updateData(filteredList);
        updateSummaryCards(filteredList);
    }

    private void updateSummaryCards(List<TransactionOrder> list) {
        double totalRevenue  = 0;
        int completedCount   = 0;

        for (TransactionOrder order : list) {
            if (order.getStatus() != null && order.getStatus().equalsIgnoreCase("completed")) {
                totalRevenue += order.getTotalAmount();
                completedCount++;
            }
        }

        Log.d(TAG, "updateSummaryCards: completed=" + completedCount + ", revenue=" + totalRevenue);

        NumberFormat nf = NumberFormat.getCurrencyInstance(new Locale("id", "ID"));
        tvTotalPendapatan.setText(nf.format(totalRevenue));
        tvTotalPesananSelesai.setText(String.valueOf(completedCount));
    }
}