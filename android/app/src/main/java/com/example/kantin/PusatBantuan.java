package com.example.kantin;

import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.view.View;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

public class PusatBantuan extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_pusat_bantuan);

        // 1. Pengaturan Tombol Back
        findViewById(R.id.btn_back).setOnClickListener(v -> finish());

        // 2. Pengaturan 5 Card Artikel (Accordion System)
        // Artikel 1
        setupAccordion(
                findViewById(R.id.header_article_1),
                findViewById(R.id.content_article_1),
                findViewById(R.id.icon_article_1)
        );

        // Artikel 2
        setupAccordion(
                findViewById(R.id.header_article_2),
                findViewById(R.id.content_article_2),
                findViewById(R.id.icon_article_2)
        );

        // Artikel 3
        setupAccordion(
                findViewById(R.id.header_article_3),
                findViewById(R.id.content_article_3),
                findViewById(R.id.icon_article_3)
        );

        // Artikel 4
        setupAccordion(
                findViewById(R.id.header_article_4),
                findViewById(R.id.content_article_4),
                findViewById(R.id.icon_article_4)
        );

        // Artikel 5
        setupAccordion(
                findViewById(R.id.header_article_5),
                findViewById(R.id.content_article_5),
                findViewById(R.id.icon_article_5)
        );

        // 3. Pengaturan Tombol Hubungi (WhatsApp)
        findViewById(R.id.btn_hubungi).setOnClickListener(v -> {
            String nomorWa = "6281262729503"; // Menggunakan format internasional tanpa tanda +
            String pesan = "Halo Manajer Akun Kant.in, saya membutuhkan bantuan terkait kendala operasional.";

            try {
                // Mencoba membuka WhatsApp melalui URL scheme
                String url = "https://api.whatsapp.com/send?phone=" + nomorWa + "&text=" + Uri.encode(pesan);
                Intent intent = new Intent(Intent.ACTION_VIEW);
                intent.setData(Uri.parse(url));
                startActivity(intent);
            } catch (Exception e) {
                // Jika aplikasi WA tidak terinstall, munculkan pesan error
                Toast.makeText(this, "Aplikasi WhatsApp tidak ditemukan", Toast.LENGTH_SHORT).show();
            }
        });
    }


    /**
     * Metode helper untuk mengatur logika buka-tutup (expand/collapse) pada setiap artikel
     */
    private void setupAccordion(LinearLayout header, final TextView content, final ImageView icon) {
        // Menggunakan Lambda (v ->) agar kode lebih bersih
        header.setOnClickListener(v -> {
            if (content.getVisibility() == View.GONE) {
                // Tampilkan jawaban dengan animasi rotasi ikon
                content.setVisibility(View.VISIBLE);
                icon.animate().rotation(180f).setDuration(200).start();
            } else {
                // Sembunyikan jawaban
                content.setVisibility(View.GONE);
                icon.animate().rotation(0f).setDuration(200).start();
            }
        });
    }
}
