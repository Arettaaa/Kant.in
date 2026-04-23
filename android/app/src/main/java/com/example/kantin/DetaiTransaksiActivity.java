package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;
import java.text.NumberFormat;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.Locale;
import java.util.TimeZone;

public class DetaiTransaksiActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_detai_transaksi);

        // Ambil data dari Intent
        Intent intent = getIntent();
        String orderCode     = intent.getStringExtra("order_code");
        String customerName  = intent.getStringExtra("customer_name");
        String status        = intent.getStringExtra("status");
        String createdAt     = intent.getStringExtra("created_at");
        double totalAmount   = intent.getDoubleExtra("total_amount", 0);
        double subtotal      = intent.getDoubleExtra("subtotal_amount", 0);
        double deliveryFee   = intent.getDoubleExtra("delivery_fee", 0);
        String paymentMethod = intent.getStringExtra("payment_method");
        // Init views
        TextView tvHeaderOrderId        = findViewById(R.id.tvHeaderOrderId);
        TextView tvTotalPembayaranUtama = findViewById(R.id.tvTotalPembayaranUtama);
        TextView tvTanggalWaktu         = findViewById(R.id.tvTanggalWaktu);
        TextView tvNamaPelanggan        = findViewById(R.id.tvNamaPelanggan);
        TextView tvIdPesanan            = findViewById(R.id.tvIdPesanan);
        TextView tvSubtotal             = findViewById(R.id.tvSubtotal);
        TextView tvBiayaAplikasi        = findViewById(R.id.tvBiayaAplikasi);
        TextView tvTotalAkhir           = findViewById(R.id.tvTotalAkhir);
        LinearLayout containerMenuList  = findViewById(R.id.containerMenuList);
        ImageView btnBack               = findViewById(R.id.btnBack);
        btnBack.setOnClickListener(v -> finish());

        NumberFormat rupiah = NumberFormat.getCurrencyInstance(new Locale("in", "ID"));

        // Isi data
        tvHeaderOrderId.setText(orderCode);
        tvIdPesanan.setText(orderCode);
        tvNamaPelanggan.setText(customerName);
        tvTotalPembayaranUtama.setText(rupiah.format(totalAmount));
        tvSubtotal.setText(rupiah.format(subtotal));
        tvBiayaAplikasi.setText(rupiah.format(deliveryFee));
        tvTotalAkhir.setText(rupiah.format(totalAmount));

        // Format tanggal
        try {
            SimpleDateFormat sdfIn  = new SimpleDateFormat("yyyy-MM-dd'T'HH:mm:ss.SSSSSS'Z'", Locale.getDefault());
            sdfIn.setTimeZone(TimeZone.getTimeZone("UTC")); // parse sebagai UTC dulu

            SimpleDateFormat sdfOut = new SimpleDateFormat("dd MMM yyyy\nhh:mm a", Locale.getDefault());
            sdfOut.setTimeZone(TimeZone.getTimeZone("Asia/Jakarta")); // output dalam WIB
            Date date = sdfIn.parse(createdAt);
            tvTanggalWaktu.setText(date != null ? sdfOut.format(date) : createdAt);
        } catch (ParseException e) {
            tvTanggalWaktu.setText(createdAt);
        }

        // List menu — inflate per item
        ArrayList<String> itemNames    = intent.getStringArrayListExtra("item_names");
        ArrayList<String> itemPrices   = intent.getStringArrayListExtra("item_prices");
        ArrayList<Integer> itemQtys    = intent.getIntegerArrayListExtra("item_qtys");
        ArrayList<String> itemNotes = intent.getStringArrayListExtra("item_notes");
        ArrayList<String> itemSubtotals = intent.getStringArrayListExtra("item_subtotals");


        if (itemNames != null) {
            for (int i = 0; i < itemNames.size(); i++) {
                View row = LayoutInflater.from(this).inflate(R.layout.item_menu_detail_with_note, containerMenuList, false);                ((TextView) row.findViewById(R.id.tvMenuNama)).setText(itemNames.get(i));
                ((TextView) row.findViewById(R.id.tvMenuQty)).setText(itemQtys.get(i) + "x");
                ((TextView) row.findViewById(R.id.tvMenuHarga)).setText(rupiah.format(Double.parseDouble(itemPrices.get(i))));

                // Handle note
                TextView tvNote = row.findViewById(R.id.tvMenuNote);
                if (itemNotes != null && itemNotes.get(i) != null && !itemNotes.get(i).isEmpty()) {
                    tvNote.setText("📝 " + itemNotes.get(i));
                    tvNote.setVisibility(View.VISIBLE);
                } else {
                    tvNote.setVisibility(View.GONE);
                }

                containerMenuList.addView(row);
            }
        }
    }
}