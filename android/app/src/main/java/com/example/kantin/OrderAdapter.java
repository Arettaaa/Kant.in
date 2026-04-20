package com.example.kantin;

import android.content.Context;
import android.content.Intent;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.appcompat.widget.AppCompatButton;
import androidx.core.content.ContextCompat;
import androidx.recyclerview.widget.RecyclerView;

import java.util.List;

public class OrderAdapter extends RecyclerView.Adapter<OrderAdapter.OrderViewHolder> {

    private final Context context;
    private final List<Order> orderList;
    private final boolean isProsesTab; // true jika di tab Diproses, false jika di tab Pesanan Masuk

    public OrderAdapter(Context context, List<Order> orderList, boolean isProsesTab) {
        this.context = context;
        this.orderList = orderList;
        this.isProsesTab = isProsesTab;
    }

    @NonNull
    @Override
    public OrderViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        // Memilih layout berdasarkan tab yang aktif
        int layoutRes = isProsesTab ? R.layout.item_order_proses : R.layout.item_order_masuk;
        View view = LayoutInflater.from(context).inflate(layoutRes, parent, false);
        return new OrderViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull OrderViewHolder holder, int position) {
        Order order = orderList.get(position);

        // 1. SET DATA UMUM (Nama, ID, Waktu)
        if (holder.tvCustomerName != null) {
            holder.tvCustomerName.setText(order.getCustomerName());
        }
        if (holder.tvOrderId != null) {
            holder.tvOrderId.setText(order.getOrderId());
        }
        if (holder.tvOrderTime != null) {
            holder.tvOrderTime.setText(order.getTime());
        }

        // 2. LOGIKA TAB PESANAN MASUK (Harga & Ringkasan Menu)
        if (!isProsesTab) {
            if (holder.tvTotalPrice != null) {
                holder.tvTotalPrice.setText(order.getTotalHarga());
            }
        }

        // 3. LOGIKA TAB DIPROSES (Warna Status Dinamis)
        if (isProsesTab && holder.tvStatusText != null) {
            String status = order.getStatus(); // Ambil status dari model

            if ("processing".equalsIgnoreCase(status) || "Dimasak".equalsIgnoreCase(status)) {
                holder.tvStatusText.setText("Dimasak");
                holder.tvStatusText.setTextColor(ContextCompat.getColor(context, R.color.orange_primary));
                holder.ivStatusIcon.setImageResource(R.drawable.flame); // Ikon api
                holder.ivStatusIcon.setColorFilter(ContextCompat.getColor(context, R.color.orange_primary));
                holder.layoutStatusBadge.setBackgroundResource(R.drawable.admin_badge_orange_light);
            }
            else if ("ready".equalsIgnoreCase(status) || "Siap".equalsIgnoreCase(status)) {
                holder.tvStatusText.setText("Siap");
                holder.tvStatusText.setTextColor(ContextCompat.getColor(context, R.color.green_primary));
                holder.ivStatusIcon.setImageResource(R.drawable.checkcirclee); // Ikon centang
                holder.ivStatusIcon.setColorFilter(ContextCompat.getColor(context, R.color.green_primary));
                holder.layoutStatusBadge.setBackgroundResource(R.drawable.admin_badge_green_light);
            }
        }

        // 4. LOGIKA KLIK KARTU KE DETAIL PESANAN
        holder.itemView.setOnClickListener(v -> {
            Intent intent = new Intent(context, DetailPesanan.class);
            intent.putExtra("ORDER_ID", order.getOrderId()); // Kirim ID ke Detail
            context.startActivity(intent);
        });

        // 5. TOMBOL AKSI (Hanya Tab Masuk)
        if (!isProsesTab && holder.btnTerima != null) {
            holder.btnTerima.setOnClickListener(v -> {
                // Panggil API Laravel verifyPayment
            });
            holder.btnTolak.setOnClickListener(v -> {
                // Panggil API Laravel rejectPayment
            });
        }
    }

    @Override
    public int getItemCount() {
        return orderList.size();
    }

    public static class OrderViewHolder extends RecyclerView.ViewHolder {
        // Variabel gabungan untuk kedua layout
        TextView tvCustomerName, tvOrderId, tvOrderTime, tvTotalPrice;
        TextView tvStatusText;
        View btnTerima, btnTolak;
        ImageView ivStatusIcon;
        LinearLayout layoutStatusBadge;

        public OrderViewHolder(@NonNull View itemView) {
            super(itemView);
            // ID Umum (Coba mapping sesuai layout yang tersedia)
            
            // Tab Masuk uses underscores, Tab Proses uses CamelCase
            tvCustomerName = itemView.findViewById(R.id.tv_customer_name);
            if (tvCustomerName == null) tvCustomerName = itemView.findViewById(R.id.tvCustomerNameProses);
            
            tvOrderId = itemView.findViewById(R.id.tv_order_id);
            if (tvOrderId == null) tvOrderId = itemView.findViewById(R.id.tvOrderIdProses);
            
            tvOrderTime = itemView.findViewById(R.id.tv_order_time);
            if (tvOrderTime == null) tvOrderTime = itemView.findViewById(R.id.tvOrderTimeProses);

            // ID Khusus Tab Masuk
            tvTotalPrice = itemView.findViewById(R.id.tv_total_price);
            btnTerima = itemView.findViewById(R.id.btn_terima);
            btnTolak = itemView.findViewById(R.id.btn_tolak);

            // ID Khusus Tab Proses
            tvStatusText = itemView.findViewById(R.id.tvStatusText);
            ivStatusIcon = itemView.findViewById(R.id.ivStatusIcon);
            layoutStatusBadge = itemView.findViewById(R.id.layoutStatusBadge);
        }
    }
}
