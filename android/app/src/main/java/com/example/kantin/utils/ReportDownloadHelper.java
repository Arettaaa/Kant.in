package com.example.kantin.utils;

import android.app.DownloadManager;
import android.content.Context;
import android.net.Uri;
import android.os.Environment;
import android.widget.Toast;

import com.example.kantin.network.ApiClient;

public class ReportDownloadHelper {

    public static void downloadLaporan(Context context, String format, String startDate, String endDate, String canteenId, String token) {

        // 1. Siapkan URL
        String url = ApiClient.BASE_URL + "canteens/" + canteenId + "/export" +
                "?format=" + format +
                "&start_date=" + startDate +
                "&end_date=" + endDate;

        // 2. Siapkan Request
        DownloadManager.Request request = new DownloadManager.Request(Uri.parse(url));
        request.addRequestHeader("Authorization", "Bearer " + token);
        request.addRequestHeader("Accept", "application/json");

        // 3. Nama File
        String ext = format.equals("pdf") ? ".pdf" : ".xlsx";
        String fileName = "Laporan_Kantin_" + startDate.replace("-", "") + "_sd_" + endDate.replace("-", "") + ext;

        // 4. Tampilan Notifikasi & Lokasi Simpan
        request.setTitle("Laporan Penjualan Kant.in");
        request.setDescription("Mengunduh " + fileName);
        request.setNotificationVisibility(DownloadManager.Request.VISIBILITY_VISIBLE_NOTIFY_COMPLETED);
        request.setDestinationInExternalPublicDir(Environment.DIRECTORY_DOWNLOADS, fileName);

        // 5. Eksekusi
        DownloadManager manager = (DownloadManager) context.getSystemService(Context.DOWNLOAD_SERVICE);
        if (manager != null) {
            manager.enqueue(request);
            Toast.makeText(context, "Unduhan " + format.toUpperCase() + " dimulai...", Toast.LENGTH_SHORT).show();
        } else {
            Toast.makeText(context, "Gagal memulai sistem unduhan", Toast.LENGTH_SHORT).show();
        }
    }
}