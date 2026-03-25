package com.example.kantin;

import android.app.Dialog;
import android.content.Intent;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.view.ViewGroup;
import android.view.Window;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import androidx.appcompat.app.AppCompatActivity;

public class HistoryActivity extends AppCompatActivity {

    private ImageView btnBack;
    private LinearLayout tabSedangDiproses;
    private TextView btnPesanLagi;
    private TextView btnNilai; // Tambahkan variabel untuk tombol Nilai

    private int currentRating = 0; // Untuk menyimpan nilai rating (1-5)

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_history);

        btnBack = findViewById(R.id.btnBack);
        tabSedangDiproses = findViewById(R.id.tabSedangDiproses);
        btnPesanLagi = findViewById(R.id.btnPesanLagi);
        btnNilai = findViewById(R.id.btnNilai); // Mengambil tombol Nilai (Pastikan ID-nya sudah ada di XML utama)

        // 1. Aksi Tombol Kembali (Balik ke halaman sebelumnya secara otomatis)
        btnBack.setOnClickListener(v -> {
            onBackPressed();
        });

        // 2. Aksi Tab Sedang Diproses
        tabSedangDiproses.setOnClickListener(v -> {
            Intent intent = new Intent(HistoryActivity.this, ActiveOrdersActivity.class);
            startActivity(intent);
            overridePendingTransition(0, 0); // Agar perpindahan tab terlihat instan
            finish();
        });

        // 3. Aksi Tombol Pesan Lagi
        if (btnPesanLagi != null) {
            btnPesanLagi.setOnClickListener(v -> {
                Intent intent = new Intent(HistoryActivity.this, DetailMenuActivity.class);
                startActivity(intent);
            });
        }

        // 4. Aksi Tombol Nilai (Memunculkan Modal)
        if (btnNilai != null) {
            btnNilai.setOnClickListener(v -> {
                showRatingDialog(); // Panggil fungsi modalnya
            });
        }
    }

    // Fungsi untuk menampilkan modal rating
    private void showRatingDialog() {
        Dialog dialog = new Dialog(this);
        dialog.requestWindowFeature(Window.FEATURE_NO_TITLE);

        // Pastikan nama layout XML modal kamu adalah dialog_rating (sesuaikan jika namanya beda)
        dialog.setContentView(R.layout.dialog_rating);

        // Membuat background dialog jadi transparan agar sudut membulatnya terlihat
        if (dialog.getWindow() != null) {
            dialog.getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));
            dialog.getWindow().setLayout(ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.WRAP_CONTENT);
        }

        // Cari ID bintang dari dalam dialog
        ImageView star1 = dialog.findViewById(R.id.star1);
        ImageView star2 = dialog.findViewById(R.id.star2);
        ImageView star3 = dialog.findViewById(R.id.star3);
        ImageView star4 = dialog.findViewById(R.id.star4);
        ImageView star5 = dialog.findViewById(R.id.star5);

        ImageView[] stars = new ImageView[]{star1, star2, star3, star4, star5};
        currentRating = 0; // Reset setiap kali modal dibuka

        // Logika saat bintang diklik
        for (int i = 0; i < stars.length; i++) {
            final int ratingValue = i + 1;
            stars[i].setOnClickListener(v -> {
                currentRating = ratingValue;

                // UBAH BAGIAN INI: Ganti gambarnya jadi starfill
                for (int j = 0; j < stars.length; j++) {
                    if (j < currentRating) {
                        // Bintang berubah jadi penuh (pastikan ada icon starfill di drawable)
                        stars[j].setImageResource(R.drawable.starfill);
                    } else {
                        // Bintang kembali jadi kosong (garis tepi saja)
                        stars[j].setImageResource(R.drawable.star);
                    }
                }
            });
        }

        // Tombol di dalam modal
        TextView btnNantiSaja = dialog.findViewById(R.id.btnNantiSaja);
        TextView btnKirimPenilaian = dialog.findViewById(R.id.btnKirimPenilaian);

        btnNantiSaja.setOnClickListener(v -> dialog.dismiss());

        btnKirimPenilaian.setOnClickListener(v -> {
            if (currentRating == 0) {
                // Beri peringatan kalau user belum pilih bintang
                android.widget.Toast.makeText(this, "Pilih bintang dulu ya!", android.widget.Toast.LENGTH_SHORT).show();
            } else {
                android.widget.Toast.makeText(this, "Terima kasih atas penilaiannya!", android.widget.Toast.LENGTH_SHORT).show();
                // TODO: Nanti kalau mau simpan ke database, ambil dari variabel currentRating
                dialog.dismiss();
            }
        });

        dialog.show();
    }
}