package com.example.kantin;

import android.annotation.SuppressLint;
import android.os.Bundle;
import android.text.SpannableString;
import android.text.style.BackgroundColorSpan;
import android.text.style.ForegroundColorSpan;
import android.graphics.Color;
import android.view.View;
import android.widget.CheckBox;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.viewpager2.widget.ViewPager2;

import com.bumptech.glide.Glide;
import com.example.kantin.model.response.BaseResponse;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;
import com.example.kantin.utils.SessionManager;
import com.google.android.material.tabs.TabLayout;
import com.google.android.material.tabs.TabLayoutMediator;

import okhttp3.MediaType;
import okhttp3.RequestBody;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

@SuppressLint("SetTextI18n")
public class AdminDashboardActivity extends AppCompatActivity {

    private CheckBox switchKantin;
    private View overlayTutup;
    private TextView tvStatusBadge, tvShopName;
    private ViewPager2 viewPager;
    private TabLayout tabLayout;

    private String canteenId;
    private ApiService apiService;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_admin_dashboard);

        initViews();

        SessionManager sessionManager = new SessionManager(this);
        String token = sessionManager.getToken();
        canteenId = sessionManager.getCanteenId();

        String fullName = sessionManager.getUserName();
        String firstName;
        if (fullName != null && fullName.split(" ").length >= 2) {
            String[] parts = fullName.split(" ");
            firstName = parts[0] + " " + parts[1]; // "Bu Vivi"
        } else {
            firstName = fullName;
        }
        tvShopName.setText(firstName != null ? firstName : "Admin");
        // Foto profil
        ImageView ivFotoAdmin = findViewById(R.id.iv_shop_icon);
        String BASE_URL_STORAGE = "https://nonephemerally-nonrevolving-judie.ngrok-free.dev/storage/";
        String path = sessionManager.getPhotoUrl();
        if (path != null && !path.isEmpty()) {
            String fullUrl = path.startsWith("http") ? path : BASE_URL_STORAGE + path;
            Glide.with(this).load(fullUrl).circleCrop()
                    .placeholder(R.drawable.avatar).into(ivFotoAdmin);
        }

        apiService = ApiClient.getAuthClient(token).create(ApiService.class);

        // Setup ViewPager2 + Tab
        AdminPagerAdapter pagerAdapter = new AdminPagerAdapter(this);
        viewPager.setAdapter(pagerAdapter);
        new TabLayoutMediator(tabLayout, viewPager, (tab, position) -> {
            View view = getLayoutInflater().inflate(R.layout.tab_item, tabLayout, false);
            TextView title = view.findViewById(R.id.tv_tab_title);
            TextView badge = view.findViewById(R.id.tv_badge);

            title.setText(position == 0 ? "Pesanan Masuk" : "Diproses");

            tab.setCustomView(view);
        }).attach();

        setupSwitchListener();
        FooterAdmin.setupFooter(this);
    }

    private void initViews() {
        switchKantin = findViewById(R.id.switch_kantin_status);
        overlayTutup = findViewById(R.id.view_overlay_tutup);
        tvStatusBadge = findViewById(R.id.tv_status_badge);
        tvShopName = findViewById(R.id.tv_shop_name);
        viewPager = findViewById(R.id.view_pager);
        tabLayout = findViewById(R.id.tab_layout);
    }

    public void updateTabCount(int tabIndex, int count) {
        TabLayout.Tab tab = tabLayout.getTabAt(tabIndex);
        if (tab != null && tab.getCustomView() != null) {
            TextView badge = tab.getCustomView().findViewById(R.id.tv_badge);

            if (count > 0) {
                badge.setText(String.valueOf(count));
                badge.setVisibility(View.VISIBLE);
            } else {
                badge.setVisibility(View.GONE);
            }
        }
    }
    @SuppressWarnings("deprecation")
    private void setupSwitchListener() {
        switchKantin.setOnCheckedChangeListener((buttonView, isChecked) -> {
            updateKantinUI(isChecked);

            RequestBody method = RequestBody.create(MediaType.parse("text/plain"), "PUT");
            RequestBody isOpen = RequestBody.create(MediaType.parse("text/plain"), isChecked ? "1" : "0");

            apiService.toggleCanteenOpen(canteenId, method, isOpen).enqueue(new Callback<>() {
                @Override
                public void onResponse(@NonNull Call<BaseResponse> call, @NonNull Response<BaseResponse> response) {
                    if (response.isSuccessful() && response.body() != null && response.body().isSuccess()) {
                        Toast.makeText(AdminDashboardActivity.this, response.body().getMessage(), Toast.LENGTH_SHORT).show();
                    } else {
                        switchKantin.setChecked(!isChecked);
                        Toast.makeText(AdminDashboardActivity.this, "Gagal mengubah status kantin", Toast.LENGTH_SHORT).show();
                    }
                }

                @Override
                public void onFailure(@NonNull Call<BaseResponse> call, @NonNull Throwable t) {
                    switchKantin.setChecked(!isChecked);
                    Toast.makeText(AdminDashboardActivity.this, "Koneksi Error", Toast.LENGTH_SHORT).show();
                }
            });
        });
    }

    private void updateKantinUI(boolean isChecked) {
        if (isChecked) {
            overlayTutup.setVisibility(View.GONE);
            tvStatusBadge.setText("MENERIMA PESANAN");
            tvStatusBadge.setTextColor(0xFF00B050);
        } else {
            overlayTutup.setVisibility(View.VISIBLE);
            tvStatusBadge.setText("DIJEDA");
            tvStatusBadge.setTextColor(0xFF888888);
        }
    }
}