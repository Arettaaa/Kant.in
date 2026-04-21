package com.example.kantin; // Sesuaikan package kamu

import android.graphics.Color;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.example.kantin.model.TransactionOrder;

import java.text.NumberFormat;
import java.util.ArrayList;
import java.util.List;
import java.util.Locale;

public class TransactionAdapter extends RecyclerView.Adapter<TransactionAdapter.ViewHolder> {

    private List<TransactionOrder> orders;
    private List<TransactionOrder> ordersFiltered; // List untuk hasil filter/pencarian

    public TransactionAdapter(List<TransactionOrder> orders) {
        this.orders = orders;
        this.ordersFiltered = new ArrayList<>(orders);
    }

    // INI ADALAH FUNGSI YANG BIKIN ERROR SEBELUMNYA (Sekarang sudah ditambahkan)
    public void updateData(List<TransactionOrder> newOrders) {
        this.ordersFiltered = new ArrayList<>(newOrders);
        notifyDataSetChanged();
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View v = LayoutInflater.from(parent.getContext()).inflate(R.layout.item_transaksi, parent, false);
        return new ViewHolder(v);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        TransactionOrder order = ordersFiltered.get(position);

        holder.tvNama.setText(order.getCustomerName());
        holder.tvDetail.setText(order.getOrderCode() + " • " + order.getItemCount() + " items");

        NumberFormat formatRupiah = NumberFormat.getCurrencyInstance(new Locale("in", "ID"));
        holder.tvHarga.setText(formatRupiah.format(order.getTotalAmount()));

        String status = order.getStatus();
        if (status != null) {
            status = status.toUpperCase();
            holder.tvStatus.setText(status);

            // Warna status
            if (status.equals("COMPLETED") || status.equals("SELESAI")) {
                holder.tvStatus.setTextColor(Color.parseColor("#00C853")); // Hijau
            } else if (status.equals("CANCELLED")) {
                holder.tvStatus.setTextColor(Color.parseColor("#F44336")); // Merah
            } else {
                holder.tvStatus.setTextColor(Color.parseColor("#FF9800")); // Oranye
            }
        }
    }

    @Override
    public int getItemCount() {
        return ordersFiltered.size();
    }

    public static class ViewHolder extends RecyclerView.ViewHolder {
        TextView tvNama, tvDetail, tvHarga, tvStatus;
        public ViewHolder(View itemView) {
            super(itemView);
            tvNama = itemView.findViewById(R.id.tvNamaPelanggan);
            tvDetail = itemView.findViewById(R.id.tvDetailOrder);
            tvHarga = itemView.findViewById(R.id.tvTotalHarga);
            tvStatus = itemView.findViewById(R.id.tvStatus);
        }
    }
}