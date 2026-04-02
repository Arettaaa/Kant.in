package com.example.kantin;

import android.app.Activity;
import android.content.Intent;
import android.widget.LinearLayout;
import android.widget.Toast;

public class FooterAdmin {

    // Fungsi "Pawang Footer" yang bisa dipanggil dari halaman mana aja
    public static void setupFooter(Activity activity) {

        // Cari ID tombol dari XML yang di-include
        LinearLayout menuOrder = activity.findViewById(R.id.menuOrder);
        LinearLayout menuMenu = activity.findViewById(R.id.menuMenu);
        LinearLayout menuProfile = activity.findViewById(R.id.menuProfile);

        // 1. Logika Klik Menu (Kelola Menu)
        if (menuMenu != null) {
            menuMenu.setOnClickListener(v -> {
                // Cek biar kalau udah di halaman Kelola Menu, gak usah buka halaman baru lagi
                if (!(activity instanceof KelolaMenu)) {
                    activity.startActivity(new Intent(activity, KelolaMenu.class));
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
                } else {
                    Toast.makeText(activity, "Kamu sudah di Profil", Toast.LENGTH_SHORT).show();
                }
            });
        }

        // 3. Logika Klik Order (Dashboard)
        if (menuOrder != null) {
            menuOrder.setOnClickListener(v -> {
                if (!(activity instanceof DashboardAdmin)) {
                    activity.startActivity(new Intent(activity, DashboardAdmin.class));
                } else {
                    Toast.makeText(activity, "Kamu sudah di Dashboard Order", Toast.LENGTH_SHORT).show();
                }
            });
        }
    }
}