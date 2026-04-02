package com.example.kantin;

import android.content.Intent;
import android.graphics.Typeface;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.SwitchCompat;
import androidx.core.content.ContextCompat;
import androidx.fragment.app.Fragment;

import com.example.kantin.fragments.OrderMasukFragment;
import com.example.kantin.fragments.OrderProsesFragment;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;
import com.example.kantin.model.response.BaseResponse;
import com.example.kantin.utils.SessionManager;

import okhttp3.MediaType;
import okhttp3.RequestBody;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class DashboardAdmin extends AppCompatActivity {

    private LinearLayout tabMasuk, tabProses;
    private TextView tvTabMasukLabel, tvTabProsesLabel, tvStatusBadge;
    private SwitchCompat switchStatusKantin;
    private View btnKeluar;
    private SessionManager sessionManager;

    // Tambahkan variabel untuk navigasi bottom
    private View menuOrder, menuMenu, menuProfile;

    // ID Kantin (Nanti ambil dari SessionManager/SharedPreference hasil login)
    private String canteenId = "ID_KANTIN_KAMU";
    private String token = "TOKEN_HASIL_LOGIN";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_dashboard_admin);
        sessionManager = new SessionManager(this);

        initViews();
        setupBottomNavigation();

        if (savedInstanceState == null) {
            loadFragment(new OrderMasukFragment());
            updateTabUI(true);
        }

        tabMasuk.setOnClickListener(v -> {
            loadFragment(new OrderMasukFragment());
            updateTabUI(true);
        });

        tabProses.setOnClickListener(v -> {
            loadFragment(new OrderProsesFragment());
            updateTabUI(false);
        });

        // 4. Sinkronisasi Status Kantin ke Server
        switchStatusKantin.setOnCheckedChangeListener((buttonView, isChecked) -> {
            updateStatusKantinUI(isChecked);
            toggleCanteenAvailability(isChecked);
        });

        btnKeluar.setOnClickListener(v -> {
            // 1. Hapus data sesi dari aplikasi
            sessionManager.clearSession();

            // 2. Munculkan pesan
            Toast.makeText(this, "Berhasil Keluar", Toast.LENGTH_SHORT).show();

            // 3. Pindah ke halaman Login dan hapus riwayat tombol "Back"
            Intent intent = new Intent(DashboardAdmin.this, LoginActivity.class);
            intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
            startActivity(intent);
            finish();
        });    }

    private void initViews() {
        tabMasuk = findViewById(R.id.tabMasuk);
        tabProses = findViewById(R.id.tabProses);
        tvTabMasukLabel = findViewById(R.id.tvTabMasukLabel);
        tvTabProsesLabel = findViewById(R.id.tvTabProsesLabel);
        tvStatusBadge = findViewById(R.id.tvStatusBadge);
        switchStatusKantin = findViewById(R.id.switchStatusKantin);
        btnKeluar = findViewById(R.id.btnKeluar);

        // Inisialisasi Bottom Nav dari include layout
        menuOrder = findViewById(R.id.menuOrder);
        menuMenu = findViewById(R.id.menuMenu);
        menuProfile = findViewById(R.id.menuProfile);
    }

    // 5. Listener untuk Bottom Navigation
    private void setupBottomNavigation() {
        menuOrder.setOnClickListener(v -> {
            loadFragment(new OrderMasukFragment());
            updateTabUI(true);
        });

        menuMenu.setOnClickListener(v -> {
            Toast.makeText(this, "Ke Halaman Kelola Menu", Toast.LENGTH_SHORT).show();
            // Intent intent = new Intent(this, KelolaMenuActivity.class);
            // startActivity(intent);
        });

        menuProfile.setOnClickListener(v -> {
            Toast.makeText(this, "Ke Halaman Profil", Toast.LENGTH_SHORT).show();
            // Intent intent = new Intent(this, ProfileAdminActivity.class);
            // startActivity(intent);
        });
    }

    // Fungsi Mengirim Status ke API Laravel
    private void toggleCanteenAvailability(boolean isOpen) {
        ApiService apiService = ApiClient.getAuthClient(token).create(ApiService.class);

        // Laravel PUT via POST Multipart menggunakan _method PUT
        RequestBody method = RequestBody.create(MediaType.parse("text/plain"), "PUT");
        RequestBody status = RequestBody.create(MediaType.parse("text/plain"), isOpen ? "1" : "0");

        apiService.toggleCanteenOpen(canteenId, method, status).enqueue(new Callback<BaseResponse>() {
            @Override
            public void onResponse(Call<BaseResponse> call, Response<BaseResponse> response) {
                if (response.isSuccessful()) {
                    Log.d("API_STATUS", "Status Berhasil Diubah");
                } else {
                    Toast.makeText(DashboardAdmin.this, "Gagal sinkron server", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<BaseResponse> call, Throwable t) {
                Log.e("API_ERROR", t.getMessage());
            }
        });
    }

    private void loadFragment(Fragment fragment) {
        getSupportFragmentManager()
                .beginTransaction()
                .replace(R.id.containerDashboard, fragment)
                .commit();
    }

    private void updateTabUI(boolean isMasuk) {
        if (isMasuk) {
            tabMasuk.setBackgroundResource(R.drawable.admin_tab_indicator_active);
            tabProses.setBackground(null);
            tvTabMasukLabel.setTextColor(ContextCompat.getColor(this, R.color.black));
            tvTabMasukLabel.setTypeface(null, Typeface.BOLD);
            tvTabProsesLabel.setTextColor(ContextCompat.getColor(this, R.color.gray_text));
            tvTabProsesLabel.setTypeface(null, Typeface.NORMAL);
        } else {
            tabProses.setBackgroundResource(R.drawable.admin_tab_indicator_active);
            tabMasuk.setBackground(null);
            tvTabProsesLabel.setTextColor(ContextCompat.getColor(this, R.color.black));
            tvTabProsesLabel.setTypeface(null, Typeface.BOLD);
            tvTabMasukLabel.setTextColor(ContextCompat.getColor(this, R.color.gray_text));
            tvTabMasukLabel.setTypeface(null, Typeface.NORMAL);
        }
    }

    private void updateStatusKantinUI(boolean isOpen) {
        if (isOpen) {
            tvStatusBadge.setText("MENERIMA PESANAN");
            tvStatusBadge.setTextColor(ContextCompat.getColor(this, R.color.green_primary));
            tvStatusBadge.setBackgroundResource(R.drawable.admin_badge_status_open);
        } else {
            tvStatusBadge.setText("DIJEDA");
            tvStatusBadge.setTextColor(ContextCompat.getColor(this, R.color.gray_badge_text));
            tvStatusBadge.setBackgroundResource(R.drawable.admin_badge_status_closed);
        }
    }
}