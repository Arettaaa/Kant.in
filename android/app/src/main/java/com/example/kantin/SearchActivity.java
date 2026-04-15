package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.view.View;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.example.kantin.model.response.CanteenListResponse;
import com.example.kantin.model.response.MenuListResponse;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;

import java.util.ArrayList;
import java.util.List;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class SearchActivity extends AppCompatActivity {

    private EditText etSearchGlobal;
    private RecyclerView rvSearchMenu, rvSearchKantin;
    private LinearLayout layoutEmptyState;
    private TextView tvJumlahHasil, tvEmptyMessage, tabMenu, tabKantin;

    private ExploreMenuAdapter menuAdapter;
    private KantinAdapter kantinAdapter;

    private List<MenuListResponse.MenuItem> allMenus = new ArrayList<>();
    private List<CanteenListResponse.CanteenData> allKantins = new ArrayList<>();

    private boolean isTabMenu = true;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_search);

        // Init views
        etSearchGlobal  = findViewById(R.id.etSearchGlobal);
        rvSearchMenu    = findViewById(R.id.rvSearchMenu);
        rvSearchKantin  = findViewById(R.id.rvSearchKantin);
        layoutEmptyState = findViewById(R.id.layoutEmptyState);
        tvJumlahHasil   = findViewById(R.id.tvJumlahHasil);
        tvEmptyMessage  = findViewById(R.id.tvEmptyMessage);
        tabMenu         = findViewById(R.id.tabMenu);
        tabKantin       = findViewById(R.id.tabKantin);
        ImageView btnBack = findViewById(R.id.btnBackSearch);

        // Setup RecyclerView
        menuAdapter = new ExploreMenuAdapter(this, new ArrayList<>());
        rvSearchMenu.setLayoutManager(new LinearLayoutManager(this));
        rvSearchMenu.setAdapter(menuAdapter);

        kantinAdapter = new KantinAdapter(this, new ArrayList<>());
        rvSearchKantin.setLayoutManager(new LinearLayoutManager(this));
        rvSearchKantin.setAdapter(kantinAdapter);

        // Ambil query dari intent (dari beranda)
        String queryDariIntent = getIntent().getStringExtra("QUERY");
        if (queryDariIntent != null && !queryDariIntent.isEmpty()) {
            etSearchGlobal.setText(queryDariIntent);
            etSearchGlobal.setSelection(queryDariIntent.length());
        }

        // Fetch data dulu, baru filter
        fetchAllData();

        // Tab klik
        tabMenu.setOnClickListener(v -> switchTab(true));
        tabKantin.setOnClickListener(v -> switchTab(false));

        // Search listener
        etSearchGlobal.addTextChangedListener(new TextWatcher() {
            @Override public void beforeTextChanged(CharSequence s, int start, int count, int after) {}
            @Override public void afterTextChanged(Editable s) {}
            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
                applyFilter(s.toString());
            }
        });

        btnBack.setOnClickListener(v -> onBackPressed());
    }

    private void fetchAllData() {
        ApiService api = ApiClient.getClient().create(ApiService.class);

        // Fetch menu
        api.getAllMenus().enqueue(new Callback<MenuListResponse>() {
            @Override
            public void onResponse(Call<MenuListResponse> call, Response<MenuListResponse> response) {
                // CEK NULL SAFETY DI SINI
                if (response.isSuccessful() && response.body() != null) {
                    if (response.body().getData() != null) {
                        allMenus = response.body().getData();
                    }
                    applyFilter(etSearchGlobal.getText().toString());
                }
            }
            @Override
            public void onFailure(Call<MenuListResponse> call, Throwable t) {
                Toast.makeText(SearchActivity.this, "Gagal memuat menu", Toast.LENGTH_SHORT).show();
            }
        });

        // Fetch kantin
        api.getAllCanteens().enqueue(new Callback<CanteenListResponse>() {
            @Override
            public void onResponse(Call<CanteenListResponse> call, Response<CanteenListResponse> response) {
                // CEK NULL SAFETY DI SINI
                if (response.isSuccessful() && response.body() != null) {
                    if (response.body().getData() != null) {
                        allKantins = response.body().getData();
                    }
                    applyFilter(etSearchGlobal.getText().toString());
                }
            }
            @Override
            public void onFailure(Call<CanteenListResponse> call, Throwable t) {
                Toast.makeText(SearchActivity.this, "Gagal memuat kantin", Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void applyFilter(String query) {
        // Filter menu
        List<MenuListResponse.MenuItem> filteredMenu = new ArrayList<>();
        for (MenuListResponse.MenuItem item : allMenus) {
            // PASTIKAN NAMA TIDAK NULL SEBELUM TO_LOWER_CASE
            if (item.getName() != null && item.getName().toLowerCase().contains(query.toLowerCase())) {
                filteredMenu.add(item);
            }
        }

        // Filter kantin
        List<CanteenListResponse.CanteenData> filteredKantin = new ArrayList<>();
        for (CanteenListResponse.CanteenData kantin : allKantins) {
            // PASTIKAN NAMA TIDAK NULL SEBELUM TO_LOWER_CASE
            if (kantin.getName() != null && kantin.getName().toLowerCase().contains(query.toLowerCase())) {
                filteredKantin.add(kantin);
            }
        }

        // Update adapter
        menuAdapter = new ExploreMenuAdapter(this, filteredMenu);
        rvSearchMenu.setAdapter(menuAdapter);

        kantinAdapter = new KantinAdapter(this, filteredKantin);
        rvSearchKantin.setAdapter(kantinAdapter);

        // Update info jumlah hasil
        int totalHasil = isTabMenu ? filteredMenu.size() : filteredKantin.size();
        boolean isEmpty = totalHasil == 0;

        layoutEmptyState.setVisibility(isEmpty ? View.VISIBLE : View.GONE);
        rvSearchMenu.setVisibility(!isEmpty && isTabMenu ? View.VISIBLE : View.GONE);
        rvSearchKantin.setVisibility(!isEmpty && !isTabMenu ? View.VISIBLE : View.GONE);

        if (query.isEmpty()) {
            tvJumlahHasil.setText("Menampilkan semua hasil");
        } else {
            tvJumlahHasil.setText(totalHasil + " hasil untuk \"" + query + "\"");
        }

        if (isEmpty) {
            tvEmptyMessage.setText("Tidak ada " + (isTabMenu ? "menu" : "kantin") + " untuk \"" + query + "\"");
        }
    }

    private void switchTab(boolean toMenu) {
        isTabMenu = toMenu;

        // Update style tab
        tabMenu.setBackgroundResource(toMenu ? R.drawable.bg_chip_active : android.R.color.transparent);
        tabMenu.setTextColor(toMenu ? android.graphics.Color.WHITE : android.graphics.Color.parseColor("#6B7280"));

        tabKantin.setBackgroundResource(!toMenu ? R.drawable.bg_chip_active : android.R.color.transparent);
        tabKantin.setTextColor(!toMenu ? android.graphics.Color.WHITE : android.graphics.Color.parseColor("#6B7280"));

        // Refresh tampilan dengan query saat ini
        applyFilter(etSearchGlobal.getText().toString());
    }
}