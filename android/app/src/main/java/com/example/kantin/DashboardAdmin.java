package com.example.kantin;

import android.content.Intent;
import android.graphics.Color;
import android.graphics.Typeface;
import android.os.Bundle;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.SwitchCompat;
import androidx.fragment.app.Fragment;

import com.example.kantin.fragments.OrderMasukFragment;
import com.example.kantin.fragments.OrderProsesFragment;
import com.example.kantin.utils.SessionManager;

public class DashboardAdmin extends AppCompatActivity {

    // Variable Header & Tabs
    private LinearLayout tabMasuk, tabProses;
    private TextView tvTabMasukLabel, tvTabProsesLabel, tvStatusBadge, btnKeluar;
    private SwitchCompat switchStatusKantin;

    private SessionManager sessionManager;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_dashboard_admin);

        sessionManager = new SessionManager(this);

        // 1. Inisialisasi View (Khusus Header dan Tab saja)
        initViews();

        // 2. PANGGIL PAWANG FOOTER DI SINI! (Sangat praktis)
        FooterAdmin.setupFooter(this);

        // 3. Load Fragment Awal (Pesanan Masuk)
        if (savedInstanceState == null) {
            loadFragment(new OrderMasukFragment());
            updateTabUI(true);
        }

        // 4. Logika Tab Klik
        tabMasuk.setOnClickListener(v -> {
            loadFragment(new OrderMasukFragment());
            updateTabUI(true);
        });

        tabProses.setOnClickListener(v -> {
            loadFragment(new OrderProsesFragment());
            updateTabUI(false);
        });

        // 5. Logika Switch Status
        switchStatusKantin.setOnCheckedChangeListener((buttonView, isChecked) -> {
            updateStatusKantinUI(isChecked);
        });

        // 6. Logika Logout
        btnKeluar.setOnClickListener(v -> {
            sessionManager.clearSession();
            Toast.makeText(this, "Berhasil Keluar", Toast.LENGTH_SHORT).show();
            Intent intent = new Intent(DashboardAdmin.this, LoginActivity.class);
            intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
            startActivity(intent);
            finish();
        });
    }

    private void initViews() {
        // Hanya perlu menghubungkan ID punya Dashboard saja
        btnKeluar = findViewById(R.id.btnKeluar);
        tvStatusBadge = findViewById(R.id.tvStatusBadge);
        switchStatusKantin = findViewById(R.id.switchStatusKantin);
        tabMasuk = findViewById(R.id.tabMasuk);
        tabProses = findViewById(R.id.tabProses);
        tvTabMasukLabel = findViewById(R.id.tvTabMasukLabel);
        tvTabProsesLabel = findViewById(R.id.tvTabProsesLabel);
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
            tvTabMasukLabel.setTextColor(Color.parseColor("#111827"));
            tvTabMasukLabel.setTypeface(null, Typeface.BOLD);
            tvTabProsesLabel.setTextColor(Color.parseColor("#6B7280"));
            tvTabProsesLabel.setTypeface(null, Typeface.NORMAL);
        } else {
            tabProses.setBackgroundResource(R.drawable.admin_tab_indicator_active);
            tabMasuk.setBackground(null);
            tvTabProsesLabel.setTextColor(Color.parseColor("#111827"));
            tvTabProsesLabel.setTypeface(null, Typeface.BOLD);
            tvTabMasukLabel.setTextColor(Color.parseColor("#6B7280"));
            tvTabMasukLabel.setTypeface(null, Typeface.NORMAL);
        }
    }

    private void updateStatusKantinUI(boolean isOpen) {
        if (isOpen) {
            tvStatusBadge.setText("MENERIMA PESANAN");
            tvStatusBadge.setTextColor(Color.parseColor("#10B981"));
            tvStatusBadge.setBackgroundResource(R.drawable.admin_badge_status_open);
        } else {
            tvStatusBadge.setText("DIJEDA");
            tvStatusBadge.setTextColor(Color.parseColor("#6B7280"));
            tvStatusBadge.setBackgroundResource(R.drawable.admin_badge_status_closed);
        }
    }
}