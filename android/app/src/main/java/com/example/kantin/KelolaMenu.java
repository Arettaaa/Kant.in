package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;
import androidx.recyclerview.widget.RecyclerView;

import com.example.kantin.MenuAdminAdapter;
import com.example.kantin.model.response.MenuListResponse;
import com.example.kantin.model.response.MenuDetailResponse;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;
import com.example.kantin.utils.SessionManager;

import java.util.ArrayList;
import java.util.List;

import okhttp3.MediaType;
import okhttp3.RequestBody;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class KelolaMenu extends AppCompatActivity {

    private RecyclerView rvMenu;
    private TextView tvCountMenu;
    private LinearLayout btnKeluar;
    private android.widget.ImageView fabAdd;

    private ApiService apiService;
    private SessionManager sessionManager;
    private MenuAdminAdapter adapter;
    private List<MenuListResponse.MenuItem> menuList = new ArrayList<>();

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_kelola_menu);

        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.main), (v, insets) -> {
            Insets systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars());
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom);
            return insets;
        });

        sessionManager = new SessionManager(this);
        apiService = ApiClient.getAuthClient(sessionManager.getToken()).create(ApiService.class);

        initViews();
        setupAdapter();
        setupListeners();
        FooterAdmin.setupFooter(this);

        fetchMenus();
    }

    @Override
    protected void onResume() {
        super.onResume();
        // Refresh list setiap kali kembali ke halaman ini (misal setelah edit/tambah menu)
        fetchMenus();
    }

    private void initViews() {
        rvMenu      = findViewById(R.id.rvMenu);
        tvCountMenu = findViewById(R.id.tvCountMenu);
        btnKeluar   = findViewById(R.id.btnKeluar);
        fabAdd      = findViewById(R.id.fabAdd);
    }

    private void setupAdapter() {
        // ✅ BENAR
        adapter = new MenuAdminAdapter(this, menuList, new MenuAdminAdapter.OnMenuActionListener() {
            @Override
            public void onEditClick(MenuListResponse.MenuItem menu) {
                // Buka halaman Edit Menu, kirim ID menu
                Intent intent = new Intent(KelolaMenu.this, EditMenu.class);
                intent.putExtra("menu_id", menu.getId());
                intent.putExtra("menu_name", menu.getName());
                intent.putExtra("menu_description", menu.getDescription());
                intent.putExtra("menu_price", menu.getPriceAsDouble());
                intent.putExtra("menu_category", menu.getCategory());
                intent.putExtra("menu_image", menu.getImage());
                intent.putExtra("menu_cooking_time", menu.getEstimatedCookingTime());
                startActivity(intent);
            }

            @Override
            public void onToggleAvailability(MenuListResponse.MenuItem menu, boolean isAvailable) {
                toggleMenuAvailability(menu, isAvailable);
            }
        });
        rvMenu.setAdapter(adapter);
    }

    private void setupListeners() {
        // Tombol Keluar (Logout)
        if (btnKeluar != null) {
            btnKeluar.setOnClickListener(v -> {
                sessionManager.clearSession();
                Intent intent = new Intent(this, LoginActivity.class);
                intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
                startActivity(intent);
                finish();
            });
        }

        // Tombol Tambah Menu
        fabAdd.setOnClickListener(v -> {
            startActivity(new Intent(this, TambahMenu.class));
        });
    }

    private void fetchMenus() {
        String canteenId = sessionManager.getCanteenId();
        if (canteenId == null || canteenId.isEmpty()) {
            Toast.makeText(this, "ID Kantin tidak ditemukan", Toast.LENGTH_SHORT).show();
            return;
        }

        apiService.getMenus(canteenId, null, null).enqueue(new Callback<MenuListResponse>() {
            @Override
            public void onResponse(Call<MenuListResponse> call, Response<MenuListResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    List<MenuListResponse.MenuItem> data = response.body().getData();
                    if (data != null) {
                        menuList.clear();
                        menuList.addAll(data);
                        adapter.notifyDataSetChanged();
                        tvCountMenu.setText("Menampilkan " + data.size() + " Menu");
                    }
                } else {
                    Toast.makeText(KelolaMenu.this, "Gagal memuat menu", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<MenuListResponse> call, Throwable t) {
                Toast.makeText(KelolaMenu.this, "Koneksi error: " + t.getMessage(), Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void toggleMenuAvailability(MenuListResponse.MenuItem menu, boolean isAvailable) {
        String canteenId = sessionManager.getCanteenId();

        RequestBody methodPut   = RequestBody.create(MediaType.parse("text/plain"), "PUT");
        RequestBody availability = RequestBody.create(MediaType.parse("text/plain"), isAvailable ? "1" : "0");

        apiService.toggleMenuAvailability(canteenId, menu.getId(), methodPut, availability)
                .enqueue(new Callback<MenuDetailResponse>() {
                    @Override
                    public void onResponse(Call<MenuDetailResponse> call, Response<MenuDetailResponse> response) {
                        if (response.isSuccessful()) {
                            String status = isAvailable ? "Tersedia" : "Habis";
                            Toast.makeText(KelolaMenu.this,
                                    menu.getName() + " → " + status, Toast.LENGTH_SHORT).show();
                        } else {
                            Toast.makeText(KelolaMenu.this,
                                    "Gagal update status", Toast.LENGTH_SHORT).show();
                            // Rollback switch jika gagal
                            fetchMenus();
                        }
                    }

                    @Override
                    public void onFailure(Call<MenuDetailResponse> call, Throwable t) {
                        Toast.makeText(KelolaMenu.this,
                                "Koneksi error", Toast.LENGTH_SHORT).show();
                        fetchMenus(); // Rollback
                    }
                });
    }
}