package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
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

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_exploremenupelanggan);

        rvExploreMenu = findViewById(R.id.rvExploreMenu);
        etSearchMenu = findViewById(R.id.etSearchMenu);
        chipCategoryContainer = findViewById(R.id.chipCategoryContainer);
        ImageView btnBack = findViewById(R.id.btnBackExploreMenu);

        LinearLayout navHome = findViewById(R.id.navHome);
        LinearLayout navHistory = findViewById(R.id.navHistory);
        LinearLayout navProfile = findViewById(R.id.navProfile);

        rvExploreMenu.setLayoutManager(new LinearLayoutManager(this));
        adapter = new ExploreMenuAdapter(this, new ArrayList<>());
        rvExploreMenu.setAdapter(adapter);

        fetchAllMenus();

        etSearchMenu.addTextChangedListener(new TextWatcher() {
            @Override public void beforeTextChanged(CharSequence s, int start, int count, int after) {}
            @Override public void afterTextChanged(Editable s) {}
            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
                if (adapter != null) adapter.filter(s.toString(), activeCategory);
            }
        });

        btnBack.setOnClickListener(v -> onBackPressed());
        navHome.setOnClickListener(v -> {
            Intent intent = new Intent(this, BerandaPelangganActivity.class);
            intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_SINGLE_TOP);
            startActivity(intent);
            finish();
        });
        navHistory.setOnClickListener(v -> startActivity(new Intent(this, HistoryActivity.class)));
        navProfile.setOnClickListener(v -> startActivity(new Intent(this, ProfilPelangganActivity.class)));
    }

    private void fetchAllMenus() {
        ApiClient.getClient().create(ApiService.class).getAllMenus().enqueue(new Callback<MenuListResponse>() {
            @Override
            public void onResponse(Call<MenuListResponse> call, Response<MenuListResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    List<MenuListResponse.MenuItem> data = response.body().getData();

                    adapter = new ExploreMenuAdapter(ExploreMenuPelangganActivity.this, data);
                    rvExploreMenu.setAdapter(adapter);

                    // Build chip dinamis dari kategori unik di data
                    buildCategoryChips(data);
                }
            }

            @Override
            public void onFailure(Call<MenuListResponse> call, Throwable t) {
                Toast.makeText(ExploreMenuPelangganActivity.this, "Gagal ambil semua menu", Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void buildCategoryChips(List<MenuListResponse.MenuItem> data) {
        chipCategoryContainer.removeAllViews();

        // Kumpulkan kategori unik
        List<String> categories = new ArrayList<>();
        categories.add("Semua");
        for (MenuListResponse.MenuItem item : data) {
            String cat = item.getCategory();
            if (cat != null && !cat.isEmpty() && !categories.contains(cat)) {
                categories.add(cat);
            }
        }

        // Buat TextView chip untuk setiap kategori
        for (String category : categories) {
            TextView chip = new TextView(this);

            LinearLayout.LayoutParams params = new LinearLayout.LayoutParams(
                    LinearLayout.LayoutParams.WRAP_CONTENT,
                    dpToPx(36)
            );
            params.setMarginEnd(dpToPx(8));
            chip.setLayoutParams(params);

            chip.setText(capitalize(category));
            chip.setTextSize(13);
            chip.setPadding(dpToPx(16), 0, dpToPx(16), 0);
            chip.setGravity(android.view.Gravity.CENTER);

            // Style aktif/inaktif
            if (category.equals(activeCategory)) {
                chip.setBackgroundResource(R.drawable.bg_chip_active);
                chip.setTextColor(android.graphics.Color.WHITE);
            } else {
                chip.setBackgroundResource(R.drawable.bg_chip_inactive);
                chip.setTextColor(android.graphics.Color.parseColor("#6B7280"));
            }

            chip.setOnClickListener(v -> setCategory(category));
            chipCategoryContainer.addView(chip);
        }
    }

    private void setCategory(String category) {
        activeCategory = category;

        // Update style semua chip
        for (int i = 0; i < chipCategoryContainer.getChildCount(); i++) {
            TextView chip = (TextView) chipCategoryContainer.getChildAt(i);
            String chipText = chip.getText().toString();
            String chipCategory = (i == 0) ? "Semua" : chipText.toLowerCase();

            if (chipCategory.equalsIgnoreCase(category)) {
                chip.setBackgroundResource(R.drawable.bg_chip_active);
                chip.setTextColor(android.graphics.Color.WHITE);
            } else {
                chip.setBackgroundResource(R.drawable.bg_chip_inactive);
                chip.setTextColor(android.graphics.Color.parseColor("#6B7280"));
            }
        }

        if (adapter != null) adapter.filter(etSearchMenu.getText().toString(), activeCategory);
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