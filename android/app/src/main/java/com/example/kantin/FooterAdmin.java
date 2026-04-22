package com.example.kantin;

import android.app.Activity;
import android.content.Intent;
import android.graphics.Color;
import android.graphics.PorterDuff;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

public class FooterAdmin {

    // Tambahkan parameter String activeTab
    public static void setupFooter(Activity activity, String activeTab) {

        // Cari ID tombol (LinearLayout)
        LinearLayout menuOrder = activity.findViewById(R.id.menuOrder);
        LinearLayout menuMenu = activity.findViewById(R.id.menuMenu);
        LinearLayout menuProfile = activity.findViewById(R.id.menuProfile);

        // Cari ID Icon dan Teks untuk pewarnaan (Sesuaikan ID-nya dengan yang ada di XML kamu)
        ImageView ivNavOrder = activity.findViewById(R.id.ivNavOrder); // misal: id icon order
        TextView tvNavOrder = activity.findViewById(R.id.tvNavOrder);  // misal: id teks order

        ImageView ivNavMenu = activity.findViewById(R.id.ivNavMenu);
        TextView tvNavMenu = activity.findViewById(R.id.tvNavMenu);

        ImageView ivNavProfile = activity.findViewById(R.id.ivNavProfile);
        TextView tvNavProfile = activity.findViewById(R.id.tvNavProfile);

        // --- LOGIKA PEWARNAAN TAB AKTIF (Warna Oranye) ---
        int activeColor = Color.parseColor("#FF6900"); // Oranye

        if (activeTab.equals("MENU") && ivNavMenu != null && tvNavMenu != null) {
            ivNavMenu.setColorFilter(activeColor, PorterDuff.Mode.SRC_IN);
            tvNavMenu.setTextColor(activeColor);
        } else if (activeTab.equals("PROFIL") && ivNavProfile != null && tvNavProfile != null) {
            ivNavProfile.setColorFilter(activeColor, PorterDuff.Mode.SRC_IN);
            tvNavProfile.setTextColor(activeColor);
        } else if (activeTab.equals("ORDER") && ivNavOrder != null && tvNavOrder != null) {
            ivNavOrder.setColorFilter(activeColor, PorterDuff.Mode.SRC_IN);
            tvNavOrder.setTextColor(activeColor);
        }

        // --- LOGIKA KLIK NAVIGASI ---

        // 1. Logika Klik Menu (Kelola Menu)
        if (menuMenu != null) {
            menuMenu.setOnClickListener(v -> {
                if (!(activity instanceof KelolaMenu)) {
                    activity.startActivity(new Intent(activity, KelolaMenu.class));
                    activity.overridePendingTransition(0, 0); // Opsional: hilangkan animasi default
                } else {
                    Toast.makeText(activity, "Kamu sudah di Menu", Toast.LENGTH_SHORT).show();
                }
            });
        }

        // 2. Logika Klik Profil
        if (menuProfile != null) {
            menuProfile.setOnClickListener(v -> {
                if (!(activity instanceof ProfilAdminKantin)) {
                    activity.startActivity(new Intent(activity, ProfilAdminKantin.class));
                    activity.overridePendingTransition(0, 0);
                } else {
                    Toast.makeText(activity, "Kamu sudah di Profil", Toast.LENGTH_SHORT).show();
                }
            });
        }

        // 3. Logika Klik Order (Dashboard)
        if (menuOrder != null) {
            menuOrder.setOnClickListener(v -> {
                if (!(activity instanceof AdminDashboardActivity)) {
                    activity.startActivity(new Intent(activity, AdminDashboardActivity.class));
                    activity.overridePendingTransition(0, 0);
                } else {
                    Toast.makeText(activity, "Kamu sudah di Dashboard Order", Toast.LENGTH_SHORT).show();
                }
            });
        }
    }
}