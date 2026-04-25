package com.example.kantin;

import android.content.Intent;
import android.graphics.Color;
import android.graphics.drawable.Drawable;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;
import androidx.core.content.ContextCompat;
import androidx.core.graphics.drawable.DrawableCompat;

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

        Intent intent        = getIntent();
        String orderCode     = intent.getStringExtra("order_code");
        String customerName  = intent.getStringExtra("customer_name");
        String status        = intent.getStringExtra("status");
        String createdAt     = intent.getStringExtra("created_at");
        double totalAmount   = intent.getDoubleExtra("total_amount", 0);
        double subtotal      = intent.getDoubleExtra("subtotal_amount", 0);
        double deliveryFee   = intent.getDoubleExtra("delivery_fee", 0);
        String paymentMethod = intent.getStringExtra("payment_method");

        TextView tvHeaderOrderId        = findViewById(R.id.tvHeaderOrderId);
        TextView tvTotalPembayaranUtama = findViewById(R.id.tvTotalPembayaranUtama);
        TextView tvTanggalWaktu         = findViewById(R.id.tvTanggalWaktu);
        TextView tvNamaPelanggan        = findViewById(R.id.tvNamaPelanggan);
        TextView tvIdPesanan            = findViewById(R.id.tvIdPesanan);
        TextView tvSubtotal             = findViewById(R.id.tvSubtotal);
        TextView tvBiayaAplikasi        = findViewById(R.id.tvBiayaAplikasi);
        TextView tvTotalAkhir           = findViewById(R.id.tvTotalAkhir);
        TextView tvStatusBadge          = findViewById(R.id.tvStatusBadge);
        LinearLayout containerMenuList  = findViewById(R.id.containerMenuList);
        ImageView btnBack               = findViewById(R.id.btnBack);

        btnBack.setOnClickListener(v -> finish());

        NumberFormat rupiah = NumberFormat.getCurrencyInstance(new Locale("in", "ID"));

        tvHeaderOrderId.setText(orderCode);
        tvIdPesanan.setText(orderCode);
        tvNamaPelanggan.setText(customerName);
        tvTotalPembayaranUtama.setText(rupiah.format(totalAmount));
        tvSubtotal.setText(rupiah.format(subtotal));
        tvBiayaAplikasi.setText(rupiah.format(deliveryFee));
        tvTotalAkhir.setText(rupiah.format(totalAmount));

        // ── Status Badge ──────────────────────────────────────────
        boolean isCancelled = "cancelled".equalsIgnoreCase(status);

        if (isCancelled) {
            tvStatusBadge.setText("Dibatalkan");
            tvStatusBadge.setTextColor(Color.parseColor("#F44336"));
            tvStatusBadge.setBackgroundResource(R.drawable.bg_badge_red_light);

            // ✅ Pakai warna asli drawable 'close' — tidak perlu tint
            Drawable iconClose = ContextCompat.getDrawable(this, R.drawable.close);
            tvStatusBadge.setCompoundDrawablesWithIntrinsicBounds(iconClose, null, null, null);

            // Harga dicoret
            tvTotalPembayaranUtama.setPaintFlags(
                    tvTotalPembayaranUtama.getPaintFlags() | android.graphics.Paint.STRIKE_THRU_TEXT_FLAG);
            tvTotalPembayaranUtama.setTextColor(Color.parseColor("#9E9E9E"));
            tvTotalAkhir.setPaintFlags(
                    tvTotalAkhir.getPaintFlags() | android.graphics.Paint.STRIKE_THRU_TEXT_FLAG);
            tvTotalAkhir.setTextColor(Color.parseColor("#9E9E9E"));

        } else {
            tvStatusBadge.setText("Selesai");
            tvStatusBadge.setTextColor(Color.parseColor("#28A745"));
            tvStatusBadge.setBackgroundResource(R.drawable.bg_badge_green_light);

            // ic_check tetap pakai mutate+tint karena warnanya perlu di-set
            Drawable iconCheck = ContextCompat.getDrawable(this, R.drawable.ic_check);
            if (iconCheck != null) {
                iconCheck = DrawableCompat.wrap(iconCheck).mutate();
                DrawableCompat.setTint(iconCheck, Color.parseColor("#28A745"));
                tvStatusBadge.setCompoundDrawablesWithIntrinsicBounds(iconCheck, null, null, null);
            } else {
                tvStatusBadge.setCompoundDrawablesWithIntrinsicBounds(null, null, null, null);
            }
        }

        // ── Format tanggal (WIB, 24 jam) ─────────────────────────
        if (createdAt != null && !createdAt.isEmpty()) {
            try {
                SimpleDateFormat sdfIn = new SimpleDateFormat("yyyy-MM-dd'T'HH:mm:ss.SSSSSS'Z'", Locale.getDefault());
                sdfIn.setTimeZone(TimeZone.getTimeZone("UTC"));
                SimpleDateFormat sdfOut = new SimpleDateFormat("dd MMM yyyy\nHH:mm", Locale.getDefault());
                sdfOut.setTimeZone(TimeZone.getTimeZone("Asia/Jakarta"));
                Date date = sdfIn.parse(createdAt);
                tvTanggalWaktu.setText(date != null ? sdfOut.format(date) : createdAt);
            } catch (ParseException e) {
                tvTanggalWaktu.setText(createdAt);
            }
        }

        // ── List menu ─────────────────────────────────────────────
        ArrayList<String>  itemNames     = intent.getStringArrayListExtra("item_names");
        ArrayList<String>  itemPrices    = intent.getStringArrayListExtra("item_prices");
        ArrayList<Integer> itemQtys      = intent.getIntegerArrayListExtra("item_qtys");
        ArrayList<String>  itemNotes     = intent.getStringArrayListExtra("item_notes");
        ArrayList<String>  itemSubtotals = intent.getStringArrayListExtra("item_subtotals");

        if (itemNames != null) {
            for (int i = 0; i < itemNames.size(); i++) {
                View row = LayoutInflater.from(this)
                        .inflate(R.layout.item_menu_detail_with_note, containerMenuList, false);

                ((TextView) row.findViewById(R.id.tvMenuNama)).setText(itemNames.get(i));
                ((TextView) row.findViewById(R.id.tvMenuQty)).setText(itemQtys.get(i) + "x");
                ((TextView) row.findViewById(R.id.tvMenuHarga)).setText(
                        rupiah.format(Double.parseDouble(itemPrices.get(i))));

                TextView tvNote = row.findViewById(R.id.tvMenuNote);
                String note = (itemNotes != null && i < itemNotes.size()) ? itemNotes.get(i) : "";
                if (note != null && !note.isEmpty()) {
                    tvNote.setText(note);
                    tvNote.setVisibility(View.VISIBLE);
                } else {
                    tvNote.setVisibility(View.GONE);
                }

                containerMenuList.addView(row);
            }
        }
    }
}