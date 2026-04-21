package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;

import com.example.kantin.AdminMenuAdapter;
import com.example.kantin.model.response.MenuDetailResponse;
import com.example.kantin.model.response.MenuListResponse;
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

public class KelolaMenuActivity extends AppCompatActivity implements AdminMenuAdapter.OnMenuActionListener {

    private RecyclerView rvMenu;
    private AdminMenuAdapter adapter;
    private List<MenuListResponse.MenuItem> menuList = new ArrayList<>();
    private SwipeRefreshLayout swipeRefresh;
    private LinearLayout layoutEmpty;
    private TextView tvCountMenu;

    private String token, canteenId;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_kelola_menu);

        // 1. Ambil Data Session
        SessionManager sessionManager = new SessionManager(this);
        token = sessionManager.getToken();
        canteenId = sessionManager.getCanteenId();

        // 2. Inisialisasi View
        rvMenu = findViewById(R.id.rvMenu);
        swipeRefresh = findViewById(R.id.swipeRefresh);
        layoutEmpty = findViewById(R.id.layoutEmpty);
        tvCountMenu = findViewById(R.id.tvCountMenu);

        // 3. Setup RecyclerView
        adapter = new AdminMenuAdapter(this, menuList, this);
        rvMenu.setLayoutManager(new LinearLayoutManager(this));
        rvMenu.setAdapter(adapter);

        // 4. Listeners
        findViewById(R.id.btnKeluar).setOnClickListener(v -> finish());
        findViewById(R.id.fabAdd).setOnClickListener(v -> {
            // Intent intent = new Intent(this, FormMenuActivity.class);
            // startActivity(intent);
            Toast.makeText(this, "Buka Form Tambah Menu", Toast.LENGTH_SHORT).show();
        });

        if (swipeRefresh != null) {
            swipeRefresh.setOnRefreshListener(this::loadMenuData);
        }

        loadMenuData();
    }

    private void loadMenuData() {
        if (swipeRefresh != null) swipeRefresh.setRefreshing(true);

        // Menggunakan getMenuByCanteen sesuai ApiService
        ApiClient.getClient().create(ApiService.class)
                .getMenuByCanteen(canteenId)
                .enqueue(new Callback<MenuListResponse>() {
                    @Override
                    public void onResponse(@NonNull Call<MenuListResponse> call, @NonNull Response<MenuListResponse> response) {
                        if (swipeRefresh != null) swipeRefresh.setRefreshing(false);

                        if (response.isSuccessful() && response.body() != null) {
                            menuList = response.body().getData();
                            adapter.updateData(menuList);

                            // Update Teks Jumlah Menu
                            if (tvCountMenu != null) {
                                tvCountMenu.setText("Menampilkan " + menuList.size() + " Menu");
                            }

                            layoutEmpty.setVisibility(menuList.isEmpty() ? View.VISIBLE : View.GONE);
                        }
                    }

                    @Override
                    public void onFailure(@NonNull Call<MenuListResponse> call, @NonNull Throwable t) {
                        if (swipeRefresh != null) swipeRefresh.setRefreshing(false);
                        Toast.makeText(KelolaMenuActivity.this, "Koneksi Gagal", Toast.LENGTH_SHORT).show();
                    }
                });
    }

    @Override
    public void onEditClicked(MenuListResponse.MenuItem menu) {
        // Logika Edit
        Toast.makeText(this, "Edit: " + menu.getName(), Toast.LENGTH_SHORT).show();
    }

    @Override
    public void onStatusChanged(MenuListResponse.MenuItem menu, boolean isChecked) {
        // SESUAIKAN DENGAN ApiService: @Multipart @POST ... updateMenuAvailability
        ApiService api = ApiClient.getAuthClient(token).create(ApiService.class);

        // Bungkus data ke RequestBody karena di ApiService menggunakan @Part
        RequestBody method = RequestBody.create(MediaType.parse("text/plain"), "PUT");
        RequestBody status = RequestBody.create(MediaType.parse("text/plain"), isChecked ? "1" : "0");

        api.updateMenuAvailability(canteenId, menu.getId(), method, status)
                .enqueue(new Callback<MenuDetailResponse>() { // Pakai MenuDetailResponse sesuai ApiService kamu
                    @Override
                    public void onResponse(@NonNull Call<MenuDetailResponse> call, @NonNull Response<MenuDetailResponse> response) {
                        if (response.isSuccessful()) {
                            menu.setAvailable(isChecked);
                            Toast.makeText(KelolaMenuActivity.this, "Status Diperbarui", Toast.LENGTH_SHORT).show();
                        } else {
                            adapter.notifyDataSetChanged(); // Revert switch UI
                            Toast.makeText(KelolaMenuActivity.this, "Gagal Update", Toast.LENGTH_SHORT).show();
                        }
                    }

                    @Override
                    public void onFailure(@NonNull Call<MenuDetailResponse> call, @NonNull Throwable t) {
                        adapter.notifyDataSetChanged();
                        Toast.makeText(KelolaMenuActivity.this, "Kesalahan Jaringan", Toast.LENGTH_SHORT).show();
                    }
                });
    }
}