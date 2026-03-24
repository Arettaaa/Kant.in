package com.example.kantin;

import android.content.Intent;
import android.content.res.ColorStateList;
import android.graphics.Color;
import android.os.Bundle;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RadioButton;
import android.widget.TextView;
import android.widget.Toast;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.AppCompatCheckBox; // Ingat import yang ini
import java.text.NumberFormat;
import java.util.Locale;

public class KeranjangPelangganActivity extends AppCompatActivity {

    private ImageView btnBack, btnDelete;
    private TextView btnMinus1, btnPlus1, tvQty1, btnCheckout;
    private TextView tvTotalBayar, tvTotalBottom; // Tambahan untuk total
    private LinearLayout layoutAmbilSendiri, layoutAntarKurir;
    private RadioButton radioAmbilSendiri, radioAntarKurir;
    private AppCompatCheckBox cbSelectAll, cbSelectItem1; // Tambahan CheckBox

    // Data dummy awal
    private int quantity1 = 2;
    private final int hargaSatuan = 25000;
    private int biayaOngkir = 0; // Default 0 (Ambil Sendiri)

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        // Menghilangkan action bar bawaan
        if (getSupportActionBar() != null) getSupportActionBar().hide();

        setContentView(R.layout.activity_keranjangpelanggan);

        // Inisialisasi ID dari XML
        btnBack = findViewById(R.id.btnBack);
        btnDelete = findViewById(R.id.btnDelete);
        btnMinus1 = findViewById(R.id.btnMinus1);
        btnPlus1 = findViewById(R.id.btnPlus1);
        tvQty1 = findViewById(R.id.tvQty1);
        btnCheckout = findViewById(R.id.btnCheckout);

        layoutAmbilSendiri = findViewById(R.id.layoutAmbilSendiri);
        layoutAntarKurir = findViewById(R.id.layoutAntarKurir);
        radioAmbilSendiri = findViewById(R.id.radioAmbilSendiri);
        radioAntarKurir = findViewById(R.id.radioAntarKurir);

        // ID Baru untuk CheckBox dan Total
        cbSelectAll = findViewById(R.id.cbSelectAll);
        cbSelectItem1 = findViewById(R.id.cbSelectItem1);
        tvTotalBayar = findViewById(R.id.tvTotalBayar);
        tvTotalBottom = findViewById(R.id.tvTotalBottom);

        // Hitung total pertama kali saat halaman dibuka
        hitungTotalHarga();

        // --- LOGIKA CHECKBOX ---

        // 1. Jika "Pilih Semua" diklik
        cbSelectAll.setOnClickListener(v -> {
            boolean isChecked = cbSelectAll.isChecked();
            cbSelectItem1.setChecked(isChecked);
            hitungTotalHarga();
        });

        // 2. Jika "Item Makanan" diklik
        cbSelectItem1.setOnClickListener(v -> {
            boolean isChecked = cbSelectItem1.isChecked();
            // Kalau item dilepas centangnya, otomatis "Pilih Semua" juga dilepas
            if (!isChecked) {
                cbSelectAll.setChecked(false);
            } else {
                cbSelectAll.setChecked(true); // Karena cuma 1 item, kalau dicentang = pilih semua
            }
            hitungTotalHarga();
        });

        // --- LOGIKA QUANTITY ---

        btnPlus1.setOnClickListener(v -> {
            quantity1++;
            tvQty1.setText(String.valueOf(quantity1));
            hitungTotalHarga(); // Update harga saat ditambah
        });

        btnMinus1.setOnClickListener(v -> {
            if (quantity1 > 1) {
                quantity1--;
                tvQty1.setText(String.valueOf(quantity1));
                hitungTotalHarga(); // Update harga saat dikurang
            } else {
                Toast.makeText(this, "Minimal pesanan adalah 1", Toast.LENGTH_SHORT).show();
            }
        });

        // --- LOGIKA METODE PESANAN ---

        layoutAmbilSendiri.setOnClickListener(v -> selectAmbilSendiri());
        radioAmbilSendiri.setOnClickListener(v -> selectAmbilSendiri());

        layoutAntarKurir.setOnClickListener(v -> selectAntarKurir());
        radioAntarKurir.setOnClickListener(v -> selectAntarKurir());

        // --- LOGIKA TOMBOL LAINNYA ---

        btnBack.setOnClickListener(v -> onBackPressed()); // Pakai onBackPressed biar sinkron sama sebelumnya

        btnDelete.setOnClickListener(v -> {
            // Cek apakah CheckBox item makanan sedang dicentang
            if (cbSelectItem1.isChecked()) {
                // Jika dicentang, tampilkan pop-up modal hapus
                tampilkanDialogHapus();
            } else {
                // Jika belum ada yang dicentang, kasih peringatan ke user
                Toast.makeText(this, "Pilih item yang ingin dihapus terlebih dahulu!", Toast.LENGTH_SHORT).show();
            }
        });

        btnCheckout.setOnClickListener(v -> {
            if (!cbSelectItem1.isChecked()) {
                Toast.makeText(this, "Pilih makanan yang ingin di-checkout terlebih dahulu!", Toast.LENGTH_SHORT).show();
            } else {
                Intent intent = new Intent(KeranjangPelangganActivity.this, CheckoutActivity.class);
                startActivity(intent);
            }
        });
    }

    // --- FUNGSI UPDATE HARGA ---
    private void hitungTotalHarga() {
        int totalBayar = 0;

        // Cek apakah item makanan dicentang?
        if (cbSelectItem1.isChecked()) {
            // Hitung harga makanan: qty * harga satuan
            int subtotal = quantity1 * hargaSatuan;
            // Tambahkan ongkir (kalau Antar Kurir = 5000, kalau Ambil Sendiri = 0)
            totalBayar = subtotal + biayaOngkir;
        } else {
            // Kalau centang dilepas, total bayar jadi 0
            totalBayar = 0;
        }

        // Format angka jadi Rupiah (contoh: 55000 -> Rp 55.000)
        NumberFormat formatRupiah = NumberFormat.getNumberInstance(new Locale("in", "ID"));
        String hargaFormatted = "Rp " + formatRupiah.format(totalBayar);

        // Ubah teks di layar
        tvTotalBayar.setText(hargaFormatted);
        tvTotalBottom.setText(hargaFormatted);
    }

    // --- FUNGSI GANTI METODE ---

    private void selectAmbilSendiri() {
        biayaOngkir = 0; // Nol rupiah
        radioAmbilSendiri.setChecked(true);
        radioAntarKurir.setChecked(false);

        radioAmbilSendiri.setButtonTintList(ColorStateList.valueOf(Color.parseColor("#F97316")));
        radioAntarKurir.setButtonTintList(ColorStateList.valueOf(Color.parseColor("#D1D5DB")));

        layoutAmbilSendiri.setBackgroundResource(R.drawable.bg_border_orange);
        layoutAntarKurir.setBackgroundResource(R.drawable.bg_border_gray);

        hitungTotalHarga(); // Langsung update harga
    }

    private void selectAntarKurir() {
        biayaOngkir = 5000; // Nambah ceban
        radioAntarKurir.setChecked(true);
        radioAmbilSendiri.setChecked(false);

        radioAntarKurir.setButtonTintList(ColorStateList.valueOf(Color.parseColor("#F97316")));
        radioAmbilSendiri.setButtonTintList(ColorStateList.valueOf(Color.parseColor("#D1D5DB")));

        layoutAntarKurir.setBackgroundResource(R.drawable.bg_border_orange);
        layoutAmbilSendiri.setBackgroundResource(R.drawable.bg_border_gray);

        hitungTotalHarga(); // Langsung update harga
    }

    private void tampilkanDialogHapus() {
        // Membuat Dialog
        android.app.Dialog dialog = new android.app.Dialog(this);
        dialog.setContentView(R.layout.dialog_hapus);

        // Membuat background bawaan dialog jadi transparan agar sudut lengkung CardView terlihat
        dialog.getWindow().setBackgroundDrawable(new android.graphics.drawable.ColorDrawable(android.graphics.Color.TRANSPARENT));

        // Mengatur lebar dialog agar ada jarak margin di kiri kanan layarnya
        dialog.getWindow().setLayout(android.view.ViewGroup.LayoutParams.MATCH_PARENT, android.view.ViewGroup.LayoutParams.WRAP_CONTENT);

        // Inisialisasi tombol di dalam dialog
        TextView btnDialogBatal = dialog.findViewById(R.id.btnDialogBatal);
        TextView btnDialogHapus = dialog.findViewById(R.id.btnDialogHapus);

        // Kalau "Batal" diklik, tutup pop-up nya
        btnDialogBatal.setOnClickListener(v -> {
            dialog.dismiss();
        });

        // Kalau "Ya, Hapus" diklik
        btnDialogHapus.setOnClickListener(v -> {
            // Logika menghapus keranjang:
            // Misalnya kita uncheck semua dan set harganya jadi 0
            cbSelectItem1.setChecked(false);
            cbSelectAll.setChecked(false);
            hitungTotalHarga();

            // Tutup dialog
            dialog.dismiss();

            Toast.makeText(this, "Keranjang berhasil dikosongkan", Toast.LENGTH_SHORT).show();
        });

        // Tampilkan dialognya ke layar
        dialog.show();
    }
}