package com.example.kantin;

import android.graphics.Typeface;
import android.os.Bundle;
import android.view.View;
import android.widget.LinearLayout;
import android.widget.TextView;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.SwitchCompat;
import androidx.core.content.ContextCompat;
import androidx.fragment.app.Fragment;
import com.example.kantin.fragments.OrderMasukFragment;
import com.example.kantin.fragments.OrderProsesFragment;

public class DashboardAdmin extends AppCompatActivity {

    private LinearLayout tabMasuk, tabProses;
    private TextView tvTabMasukLabel, tvTabProsesLabel, tvStatusBadge;
    private SwitchCompat switchStatusKantin;
    private View btnKeluar;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_dashboard_admin);

        // 1. Inisialisasi View dari activity_dashboard_admin.xml
        initViews();

        // 2. Load Fragment default (Pesanan Masuk) saat pertama kali buka
        if (savedInstanceState == null) {
            loadFragment(new OrderMasukFragment());
            updateTabUI(true);
        }

        // 3. Logika Navigasi Tab
        tabMasuk.setOnClickListener(v -> {
            loadFragment(new OrderMasukFragment());
            updateTabUI(true);
        });

        tabProses.setOnClickListener(v -> {
            loadFragment(new OrderProsesFragment());
            updateTabUI(false);
        });

        // 4. Logika Toggle Status Operasional (Buka/Tutup) [cite: 247]
        switchStatusKantin.setOnCheckedChangeListener((buttonView, isChecked) -> {
            updateStatusKantinUI(isChecked);
        });

        // 5. Tombol Keluar
        btnKeluar.setOnClickListener(v -> {
            // Logika Logout (Contoh: finish atau pindah ke LoginActivity)
            finish();
        });
    }

    private void initViews() {
        tabMasuk = findViewById(R.id.tabMasuk);
        tabProses = findViewById(R.id.tabProses);
        tvTabMasukLabel = findViewById(R.id.tvTabMasukLabel);
        tvTabProsesLabel = findViewById(R.id.tvTabProsesLabel);
        tvStatusBadge = findViewById(R.id.tvStatusBadge);
        switchStatusKantin = findViewById(R.id.switchStatusKantin);
        btnKeluar = findViewById(R.id.btnKeluar);
    }

    private void loadFragment(Fragment fragment) {
        getSupportFragmentManager()
                .beginTransaction()
                .replace(R.id.containerDashboard, fragment)
                .commit();
    }

    // Mengubah tampilan Tab saat diklik (Indikator Aktif/Teks Bold)
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

    // Mengubah Badge Status sesuai Switch (Buka/Tutup)
    private void updateStatusKantinUI(boolean isOpen) {
        if (isOpen) {
            tvStatusBadge.setText("MENERIMA PESANAN");
            tvStatusBadge.setTextColor(ContextCompat.getColor(this, R.color.green_primary));
            tvStatusBadge.setBackgroundResource(R.drawable.admin_badge_status_open);
        } else {
            tvStatusBadge.setText("DIJEDA");
            tvStatusBadge.setTextColor(ContextCompat.getColor(this, R.color.gray_badge_text));
            tvStatusBadge.setBackgroundResource(R.drawable.admin_badge_status_closed); // Pastikan drawable ini ada
        }
    }
}