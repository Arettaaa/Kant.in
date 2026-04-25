package com.example.kantin;

import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.graphics.Paint;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.example.kantin.model.TransactionOrder;
import com.google.android.material.card.MaterialCardView;

import java.text.NumberFormat;
import java.util.List;
import java.util.Locale;

public class TransactionAdapter extends RecyclerView.Adapter<TransactionAdapter.ViewHolder> {

    private List<TransactionOrder> orders;
    private final Context context;

    public TransactionAdapter(Context context, List<TransactionOrder> orders) {
        this.context = context;
        this.orders = orders;
    }

    // Overload constructor tanpa context (backward-compat jika diperlukan)
    public TransactionAdapter(List<TransactionOrder> orders) {
        this.context = null;
        this.orders = orders;
    }

    public void updateData(List<TransactionOrder> newOrders) {
        this.orders = newOrders;
        notifyDataSetChanged();
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.item_transaksi, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        TransactionOrder order = orders.get(position);
        boolean isCancelled = "cancelled".equalsIgnoreCase(order.getStatus());

        // --- Nama Pelanggan ---
        holder.tvNamaPelanggan.setText(order.getCustomerName());

        // --- Kode Order + Jumlah Item (satu baris gabungan) ---
        int itemCount = order.getItemCount();
        String detailText = order.getOrderCode() + "  •  " + itemCount + " item";
        holder.tvDetailPesanan.setText(detailText);

        // Sembunyikan tvJumlahItem karena sudah digabung di atas
        if (holder.tvJumlahItem != null) {
            holder.tvJumlahItem.setVisibility(View.GONE);
        }

        // --- Total Harga ---
        NumberFormat nf = NumberFormat.getCurrencyInstance(new Locale("id", "ID"));
        String formattedPrice = nf.format(order.getTotalAmount());
        holder.tvTotalHarga.setText(formattedPrice);

        // --- STATUS & WARNA ---
        if (isCancelled) {
            // Label status
            holder.tvStatus.setText("DIBATALKAN");
            holder.tvStatus.setTextColor(Color.parseColor("#F44336")); // Merah

            // Harga dicoret (strikethrough)
            holder.tvTotalHarga.setPaintFlags(
                    holder.tvTotalHarga.getPaintFlags() | Paint.STRIKE_THRU_TEXT_FLAG
            );
            holder.tvTotalHarga.setTextColor(Color.parseColor("#9E9E9E")); // Abu-abu

            // Card border merah
            holder.cardView.setStrokeColor(Color.parseColor("#FFCDD2")); // Merah muda
            holder.cardView.setCardBackgroundColor(Color.parseColor("#FFF8F8")); // Background merah sangat muda

            // Icon merah
            holder.ivIcon.setBackgroundResource(R.drawable.bg_circle_lightred); // Buat drawable ini (lihat catatan)
            holder.ivIcon.setImageResource(R.drawable.receipt_red);             // Atau gunakan receipt_green dengan tint

        } else {
            // STATUS SELESAI (completed)
            holder.tvStatus.setText("SELESAI");
            holder.tvStatus.setTextColor(Color.parseColor("#00C853")); // Hijau

            // Pastikan harga tidak dicoret
            holder.tvTotalHarga.setPaintFlags(
                    holder.tvTotalHarga.getPaintFlags() & ~Paint.STRIKE_THRU_TEXT_FLAG
            );
            holder.tvTotalHarga.setTextColor(Color.parseColor("#1A1A1A"));

            // Card normal
            holder.cardView.setStrokeColor(Color.parseColor("#EEEEEE"));
            holder.cardView.setCardBackgroundColor(Color.WHITE);

            // Icon hijau
            holder.ivIcon.setBackgroundResource(R.drawable.bg_circle_lightgreen);
            holder.ivIcon.setImageResource(R.drawable.receipt_green);
        }

        // --- Klik item → buka detail transaksi ---
        holder.itemView.setOnClickListener(v -> {
            Intent intent = new Intent(v.getContext(), DetaiTransaksiActivity.class);
            intent.putExtra("order_id", order.getId());
            v.getContext().startActivity(intent);
        });
    }

    @Override
    public int getItemCount() {
        return orders != null ? orders.size() : 0;
    }

    static class ViewHolder extends RecyclerView.ViewHolder {
        MaterialCardView cardView;
        ImageView ivIcon;
        TextView tvNamaPelanggan, tvDetailPesanan, tvJumlahItem, tvTotalHarga, tvStatus;

        ViewHolder(@NonNull View itemView) {
            super(itemView);
            cardView        = (MaterialCardView) itemView; // Root view adalah MaterialCardView
            ivIcon          = itemView.findViewById(R.id.ivIcon);
            tvNamaPelanggan = itemView.findViewById(R.id.tvNamaPelanggan);
            tvDetailPesanan = itemView.findViewById(R.id.tvDetailPesanan);
            tvJumlahItem    = itemView.findViewById(R.id.tvJumlahItem);
            tvTotalHarga    = itemView.findViewById(R.id.tvTotalHarga);
            tvStatus        = itemView.findViewById(R.id.tvStatus);
        }
    }
}