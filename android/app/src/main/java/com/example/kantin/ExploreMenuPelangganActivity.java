package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.Log;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.example.kantin.model.response.MenuListResponse;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;

import java.util.ArrayList;
import java.util.List;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class ExploreMenuPelangganActivity extends AppCompatActivity {

    private RecyclerView rvExploreMenu;
    private ExploreMenuAdapter adapter;
    private EditText etSearchMenu;
    private LinearLayout chipCategoryContainer;

    private String activeCategory = "Semua";
    // Tentukan kategori tetap di sini agar sama dengan Beranda
    private final String[] fixedCategories = {"Semua", "makanan", "minuman", "camilan"};

    private boolean isErrorShown = false; // ← tambahkan di atas onCreate

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_exploremenupelanggan);

        rvExploreMenu = findViewById(R.id.rvExploreMenu);
        etSearchMenu = findViewById(R.id.etSearchMenu);
        chipCategoryContainer = findViewById(R.id.chipCategoryContainer);
        ImageView btnBack = findViewById(R.id.btnBackExploreMenu);

        rvExploreMenu.setLayoutManager(new LinearLayoutManager(this));
        adapter = new ExploreMenuAdapter(this, new ArrayList<>());
        rvExploreMenu.setAdapter(adapter);

        // --- 1. AMBIL DATA DARI INTENT ---
        String kategoriIntent = getIntent().getStringExtra("KATEGORI");
        if (kategoriIntent != null) activeCategory = kategoriIntent;

        String queryIntent = getIntent().getStringExtra("QUERY");
        if (queryIntent != null) {
            etSearchMenu.setText(queryIntent);
            etSearchMenu.setSelection(queryIntent.length());
        }

        // --- 2. BUAT CHIP SEKARANG (Tanpa nunggu API) ---
        setupFixedCategoryChips();

        // --- 3. JALANKAN API (Nanti datanya otomatis "cocok" ke filter) ---
        fetchAllMenus();

        // --- 4. SEARCH LISTENER ---
        etSearchMenu.addTextChangedListener(new TextWatcher() {
            @Override public void beforeTextChanged(CharSequence s, int start, int count, int after) {}
            @Override public void afterTextChanged(Editable s) {}
            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
                if (adapter != null) adapter.filter(s.toString(), activeCategory);
            }
        });

        btnBack.setOnClickListener(v -> onBackPressed());
    }

    private void setupFixedCategoryChips() {
        chipCategoryContainer.removeAllViews();

        for (String category : fixedCategories) {
            TextView chip = new TextView(this);
            LinearLayout.LayoutParams params = new LinearLayout.LayoutParams(
                    LinearLayout.LayoutParams.WRAP_CONTENT, dpToPx(36));
            params.setMarginEnd(dpToPx(8));
            chip.setLayoutParams(params);

            chip.setText(capitalize(category));
            chip.setTextSize(13);
            chip.setTypeface(null, android.graphics.Typeface.BOLD);
            chip.setPadding(dpToPx(16), 0, dpToPx(16), 0);
            chip.setGravity(android.view.Gravity.CENTER);

            // Cek kondisi aktif
            if (category.equalsIgnoreCase(activeCategory)) {
                chip.setBackgroundResource(R.drawable.bg_chip_active);
                chip.setTextColor(android.graphics.Color.WHITE);
            } else {
                chip.setBackgroundResource(R.drawable.bg_chip_inactive);
                chip.setTextColor(android.graphics.Color.parseColor("#6B7280"));
            }

            chip.setOnClickListener(v -> {
                activeCategory = category;
                refreshChipStyles();
                if (adapter != null) adapter.filter(etSearchMenu.getText().toString(), activeCategory);
            });

            chipCategoryContainer.addView(chip);
        }
    }

    private void refreshChipStyles() {
        for (int i = 0; i < chipCategoryContainer.getChildCount(); i++) {
            TextView chip = (TextView) chipCategoryContainer.getChildAt(i);
            String category = fixedCategories[i];

            if (category.equalsIgnoreCase(activeCategory)) {
                chip.setBackgroundResource(R.drawable.bg_chip_active);
                chip.setTextColor(android.graphics.Color.WHITE);
            } else {
                chip.setBackgroundResource(R.drawable.bg_chip_inactive);
                chip.setTextColor(android.graphics.Color.parseColor("#6B7280"));
            }
        }
    }

    // ✅ Taruh di sini — sejajar dengan fetchAllMenus(), setupFixedCategoryChips(), dll.
    private void showErrorOnce(String message) {
        if (!isErrorShown) {
            isErrorShown = true;
            Toast.makeText(this, message, Toast.LENGTH_SHORT).show();
        }
    }

    private void fetchAllMenus() {
        ApiClient.getClient().create(ApiService.class).getAllMenus().enqueue(new Callback<MenuListResponse>() {
            @Override
            public void onResponse(Call<MenuListResponse> call, Response<MenuListResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    List<MenuListResponse.MenuItem> data = response.body().getData();
                    adapter = new ExploreMenuAdapter(ExploreMenuPelangganActivity.this, data);
                    rvExploreMenu.setAdapter(adapter);
                    adapter.filter(etSearchMenu.getText().toString(), activeCategory);
                }
            }

            @Override
            public void onFailure(Call<MenuListResponse> call, Throwable t) {
                android.util.Log.e("API_ERROR", "Explore Menu: " + t.getMessage());
                showErrorOnce("Gagal ambil menu"); // ← sekarang bisa diakses
            }
        });
    }

    private String capitalize(String text) {
        if (text == null || text.isEmpty()) return text;
        return text.substring(0, 1).toUpperCase() + text.substring(1).toLowerCase();
    }

    private int dpToPx(int dp) {
        float density = getResources().getDisplayMetrics().density;
        return Math.round(dp * density);
    }
}