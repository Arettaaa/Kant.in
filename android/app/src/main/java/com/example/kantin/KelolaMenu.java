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
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout; // Tambahan

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

    // --- TAMBAHAN DARI XML ---
    private SwipeRefreshLayout swipeRefresh;
    private LinearLayout layoutEmpty;

    private ApiService apiService;
    private SessionManager sessionManager;
    private MenuAdminAdapter adapter;
    private List<MenuListResponse.MenuItem> menuList = new ArrayList<>();

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_kelola_menu);

        // Tambahan pengaman null check agar tidak crash jika R.id.main belum ada di XML
        View mainView = findViewById(R.id.main);
        if (mainView != null) {
            ViewCompat.setOnApplyWindowInsetsListener(mainView, (v, insets) -> {
                Insets systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars());
                v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom);
                return insets;
            });
        }

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

        // --- TAMBAHAN INISIALISASI ---
        swipeRefresh = findViewById(R.id.swipeRefresh);
        layoutEmpty  = findViewById(R.id.layoutEmpty);
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
        // PERBAIKAN: Karena ini halaman kelola menu, tombol panah kiri seharusnya hanya untuk KEMBALI, bukan LOGOUT.
        if (btnKeluar != null) {
            btnKeluar.setOnClickListener(v -> finish());
        }

        // Tombol Tambah Menu
        if (fabAdd != null) {
            fabAdd.setOnClickListener(v -> {
                startActivity(new Intent(this, TambahMenu.class));
            });
        }

        // --- TAMBAHAN LISTENER SWIPE REFRESH ---
        if (swipeRefresh != null) {
            swipeRefresh.setOnRefreshListener(this::fetchMenus);
        }
    }

    private void fetchMenus() {
        String canteenId = sessionManager.getCanteenId();
        if (canteenId == null || canteenId.isEmpty()) {
            Toast.makeText(this, "ID Kantin tidak ditemukan", Toast.LENGTH_SHORT).show();
            if (swipeRefresh != null) swipeRefresh.setRefreshing(false);
            return;
        }

        // Tampilkan loading saat fetch
        if (swipeRefresh != null) swipeRefresh.setRefreshing(true);

        apiService.getMenus(canteenId, null, null).enqueue(new Callback<MenuListResponse>() {
            @Override
            public void onResponse(Call<MenuListResponse> call, Response<MenuListResponse> response) {
                // Matikan loading
                if (swipeRefresh != null) swipeRefresh.setRefreshing(false);

                if (response.isSuccessful() && response.body() != null) {
                    List<MenuListResponse.MenuItem> data = response.body().getData();
                    menuList.clear();

                    if (data != null && !data.isEmpty()) {
                        menuList.addAll(data);
                        tvCountMenu.setText("Menampilkan " + data.size() + " Menu");

                        // Sembunyikan pesan kosong
                        if (layoutEmpty != null) layoutEmpty.setVisibility(View.GONE);
                        rvMenu.setVisibility(View.VISIBLE);
                    } else {
                        tvCountMenu.setText("Menampilkan 0 Menu");

                        // Tampilkan pesan kosong
                        if (layoutEmpty != null) layoutEmpty.setVisibility(View.VISIBLE);
                        rvMenu.setVisibility(View.GONE);
                    }
                    adapter.notifyDataSetChanged();
                } else {
                    Toast.makeText(KelolaMenu.this, "Gagal memuat menu", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<MenuListResponse> call, Throwable t) {
                if (swipeRefresh != null) swipeRefresh.setRefreshing(false);
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