package com.example.kantin;

import android.annotation.SuppressLint;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.LinearLayout;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.example.kantin.model.OrderModel; // Import Model
import com.example.kantin.model.OrderItem;  // Import OrderItem

import java.text.NumberFormat;
import java.util.List;
import java.util.Locale;

public class OrderMasukAdapter extends RecyclerView.Adapter<OrderMasukAdapter.ViewHolder> {

    private final Context context;
    private final List<OrderModel> orderList; // Gunakan OrderModel

    // Constructor
    public OrderMasukAdapter(Context context, List<OrderModel> orderList) {
        this.context = context;
        this.orderList = orderList;
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(context).inflate(R.layout.item_order_masuk, parent, false);
        return new ViewHolder(view);
    }

    @SuppressLint("SetTextI18n")
    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        OrderModel order = orderList.get(position); // Gunakan OrderModel

        if (order == null) return; // Mencegah crash

        // 1. Set Header Info
        if (order.getCustomerSnapshot() != null) {
            String fullName = order.getCustomerSnapshot().getName();
            String firstName = (fullName != null && fullName.contains(" "))
                    ? fullName.split(" ")[0] : fullName;
            holder.tvCustomerName.setText(firstName);
        } else {
            holder.tvCustomerName.setText("Pelanggan");
        }

        holder.tvOrderId.setText(order.getOrderCode());
        holder.tvTotal.setText(formatRupiah(order.getTotalAmount()));

        // 2. Set Waktu (Otomatis konversi ke WIB)
        if (holder.tvTime != null) {
            holder.tvTime.setText(formatWaktuWIB(order.getCreatedAt()));
        }

        // 3. Set Delivery Badge
        if (order.getDeliveryDetails() != null && "pickup".equals(order.getDeliveryDetails().getMethod())) {
            holder.tvDeliveryType.setText("Ambil Sendiri");
            holder.tvDeliveryType.setTextColor(Color.parseColor("#9C27B0"));
            holder.tvDeliveryType.setBackgroundResource(R.drawable.bg_order_label);
        } else {
            holder.tvDeliveryType.setText("Antar Kurir");
            holder.tvDeliveryType.setTextColor(Color.parseColor("#1976D2"));
            holder.tvDeliveryType.setBackgroundResource(R.drawable.bg_order_label_blue);
        }

        // 4. Logic Maksimal 2 Menu List
        holder.llMenuContainer.removeAllViews();
        List<OrderItem> items = order.getItems(); // Gunakan OrderItem

        if (items != null) {
            int maxItemsToShow = Math.min(items.size(), 2);

            for (int i = 0; i < maxItemsToShow; i++) {
                OrderItem item = items.get(i);
                View rowView = LayoutInflater.from(context).inflate(R.layout.item_menu_row, holder.llMenuContainer, false);

                TextView tvQty = rowView.findViewById(R.id.tv_qty);
                TextView tvMenuName = rowView.findViewById(R.id.tv_menu_name);

                tvQty.setText(item.getQuantity() + "x");
                tvMenuName.setText(item.getName());

                holder.llMenuContainer.addView(rowView);
            }

            // 5. Logic "+ X item lainnya"
            if (items.size() > 2) {
                int remaining = items.size() - 2;
                holder.tvMoreItems.setText("+ " + remaining + " item lainnya");
                holder.tvMoreItems.setVisibility(View.VISIBLE);
            } else {
                holder.tvMoreItems.setVisibility(View.GONE);
            }
        }

        // 6. Aksi Klik Card (Lempar ke DetailPesanan)
        holder.itemView.setOnClickListener(v -> {
            Intent intent = new Intent(context, DetailPesanan.class); // Nama Activity Detail

            // Ambil Canteen ID yang tersimpan di SharedPreferences
            SharedPreferences prefs = context.getSharedPreferences("KantinApp", Context.MODE_PRIVATE);
            String canteenId = prefs.getString("CANTEEN_ID", "");

            intent.putExtra("CANTEEN_ID", canteenId);
            intent.putExtra("ORDER_DATA", order); // Mengirim seluruh data OrderModel

            context.startActivity(intent);
        });
    }

    @Override
    public int getItemCount() {
        return orderList == null ? 0 : orderList.size();
    }

    private String formatRupiah(int number) {
        Locale localeID = new Locale("in", "ID");
        NumberFormat formatRupiah = NumberFormat.getCurrencyInstance(localeID);
        return formatRupiah.format(number).replace("Rp", "Rp ");
    }

    // Fungsi Ajaib Konversi UTC ke WIB (Jam:Menit)
    private String formatWaktuWIB(String createdAt) {
        if (createdAt == null || createdAt.isEmpty()) return "-";
        try {
            // Bersihkan format (Misal "2026-04-21T01:20:39.980000Z" jadi "2026-04-21 01:20:39")
            String cleanDate = createdAt.split("\\.")[0].replace("T", " ");

            // Beritahu Android kalau waktu aslinya adalah UTC
            java.text.SimpleDateFormat inputFormat = new java.text.SimpleDateFormat("yyyy-MM-dd HH:mm:ss", java.util.Locale.getDefault());
            inputFormat.setTimeZone(java.util.TimeZone.getTimeZone("UTC"));
            java.util.Date date = inputFormat.parse(cleanDate);

            // Ubah ke WIB (Asia/Jakarta) dengan format HH:mm (24 Jam)
            java.text.SimpleDateFormat outputFormat = new java.text.SimpleDateFormat("HH:mm", new java.util.Locale("id", "ID"));
            outputFormat.setTimeZone(java.util.TimeZone.getTimeZone("Asia/Jakarta"));

            return outputFormat.format(date);
        } catch (Exception e) {
            // Kalau gagal, kembali ke cara potong string biasa
            return createdAt.length() >= 16 ? createdAt.substring(11, 16) : "-";
        }
    }

    public static class ViewHolder extends RecyclerView.ViewHolder {
        TextView tvCustomerName, tvTime, tvOrderId, tvDeliveryType, tvMoreItems, tvTotal;
        LinearLayout llMenuContainer;

        public ViewHolder(@NonNull View itemView) {
            super(itemView);
            tvCustomerName = itemView.findViewById(R.id.tv_customer_name);
            tvTime = itemView.findViewById(R.id.tv_order_time);
            tvOrderId = itemView.findViewById(R.id.tv_order_id);
            tvDeliveryType = itemView.findViewById(R.id.tv_delivery_type);
            llMenuContainer = itemView.findViewById(R.id.ll_menu_container);
            tvMoreItems = itemView.findViewById(R.id.tv_more_items);
            tvTotal = itemView.findViewById(R.id.tv_total_price);
        }
    }
}