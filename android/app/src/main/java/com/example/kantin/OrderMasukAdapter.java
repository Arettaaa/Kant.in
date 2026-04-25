package com.example.kantin;

import android.annotation.SuppressLint;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.bumptech.glide.Glide;
import com.example.kantin.model.OrderModel;
import com.example.kantin.model.OrderItem;

import java.text.NumberFormat;
import java.util.List;
import java.util.Locale;

public class OrderMasukAdapter extends RecyclerView.Adapter<OrderMasukAdapter.ViewHolder> {

    private static final String BASE_URL_STORAGE = "https://nonephemerally-nonrevolving-judie.ngrok-free.dev/storage/";

    private final Context context;
    private final List<OrderModel> orderList;

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
        OrderModel order = orderList.get(position);
        if (order == null) return;

        // 1. Nama pelanggan (nama depan saja)
        if (order.getCustomerSnapshot() != null) {
            String fullName = order.getCustomerSnapshot().getName();
            String firstName = (fullName != null && fullName.contains(" "))
                    ? fullName.split(" ")[0] : fullName;
            holder.tvCustomerName.setText(firstName != null ? firstName : "Pelanggan");

            // ✅ FOTO PROFIL — load dari customer_snapshot.photo_profile
            if (holder.ivCustomerPhoto != null) {
                String photoPath = order.getCustomerSnapshot().getPhotoProfile();
                if (photoPath != null && !photoPath.isEmpty()) {
                    String fullUrl = photoPath.startsWith("http")
                            ? photoPath
                            : BASE_URL_STORAGE + photoPath;
                    Glide.with(context)
                            .load(fullUrl)
                            .circleCrop()
                            .placeholder(R.drawable.avatar)
                            .error(R.drawable.avatar)
                            .into(holder.ivCustomerPhoto);
                } else {
                    // Tidak ada foto → tampilkan avatar default
                    holder.ivCustomerPhoto.setImageResource(R.drawable.avatar);
                }
            }
        } else {
            holder.tvCustomerName.setText("Pelanggan");
            if (holder.ivCustomerPhoto != null) {
                holder.ivCustomerPhoto.setImageResource(R.drawable.avatar);
            }
        }

        // 2. Order code & waktu
        holder.tvOrderId.setText(order.getOrderCode());
        if (holder.tvTime != null) {
            holder.tvTime.setText(formatWaktuWIB(order.getCreatedAt()));
        }

        // 3. Total
        holder.tvTotal.setText(formatRupiah(order.getTotalAmount()));

        // 4. Badge delivery
        if (order.getDeliveryDetails() != null && "pickup".equals(order.getDeliveryDetails().getMethod())) {
            holder.tvDeliveryType.setText("Ambil Sendiri");
            holder.tvDeliveryType.setTextColor(Color.parseColor("#9C27B0"));
            holder.tvDeliveryType.setBackgroundResource(R.drawable.bg_order_label);
        } else {
            holder.tvDeliveryType.setText("Antar Kurir");
            holder.tvDeliveryType.setTextColor(Color.parseColor("#1976D2"));
            holder.tvDeliveryType.setBackgroundResource(R.drawable.bg_order_label_blue);
        }

        // 5. Menu list (maks 2 item)
        holder.llMenuContainer.removeAllViews();
        List<OrderItem> items = order.getItems();
        if (items != null) {
            int maxItemsToShow = Math.min(items.size(), 2);
            for (int i = 0; i < maxItemsToShow; i++) {
                OrderItem item = items.get(i);
                View rowView = LayoutInflater.from(context)
                        .inflate(R.layout.item_menu_row, holder.llMenuContainer, false);
                ((TextView) rowView.findViewById(R.id.tv_qty)).setText(item.getQuantity() + "x");
                ((TextView) rowView.findViewById(R.id.tv_menu_name)).setText(item.getName());
                holder.llMenuContainer.addView(rowView);
            }

            if (items.size() > 2) {
                holder.tvMoreItems.setText("+ " + (items.size() - 2) + " item lainnya");
                holder.tvMoreItems.setVisibility(View.VISIBLE);
            } else {
                holder.tvMoreItems.setVisibility(View.GONE);
            }
        }

        // 6. Klik card → detail pesanan
        holder.itemView.setOnClickListener(v -> {
            Intent intent = new Intent(context, DetailPesanan.class);
            SharedPreferences prefs = context.getSharedPreferences("KantinApp", Context.MODE_PRIVATE);
            intent.putExtra("CANTEEN_ID", prefs.getString("CANTEEN_ID", ""));
            intent.putExtra("ORDER_DATA", order);
            context.startActivity(intent);
        });
    }

    @Override
    public int getItemCount() {
        return orderList == null ? 0 : orderList.size();
    }

    private String formatRupiah(int number) {
        NumberFormat formatRupiah = NumberFormat.getCurrencyInstance(new Locale("in", "ID"));
        return formatRupiah.format(number).replace("Rp", "Rp ");
    }

    private String formatWaktuWIB(String createdAt) {
        if (createdAt == null || createdAt.isEmpty()) return "-";
        try {
            String cleanDate = createdAt.split("\\.")[0].replace("T", " ");
            java.text.SimpleDateFormat inputFormat = new java.text.SimpleDateFormat("yyyy-MM-dd HH:mm:ss", Locale.getDefault());
            inputFormat.setTimeZone(java.util.TimeZone.getTimeZone("UTC"));
            java.util.Date date = inputFormat.parse(cleanDate);
            java.text.SimpleDateFormat outputFormat = new java.text.SimpleDateFormat("HH:mm", new Locale("id", "ID"));
            outputFormat.setTimeZone(java.util.TimeZone.getTimeZone("Asia/Jakarta"));
            return outputFormat.format(date);
        } catch (Exception e) {
            return createdAt.length() >= 16 ? createdAt.substring(11, 16) : "-";
        }
    }

    public static class ViewHolder extends RecyclerView.ViewHolder {
        ImageView ivCustomerPhoto;   // ✅ foto profil
        TextView tvCustomerName, tvTime, tvOrderId, tvDeliveryType, tvMoreItems, tvTotal;
        LinearLayout llMenuContainer;

        public ViewHolder(@NonNull View itemView) {
            super(itemView);
            ivCustomerPhoto = itemView.findViewById(R.id.iv_customer_profile); // sesuaikan ID dengan XML
            tvCustomerName  = itemView.findViewById(R.id.tv_customer_name);
            tvTime          = itemView.findViewById(R.id.tv_order_time);
            tvOrderId       = itemView.findViewById(R.id.tv_order_id);
            tvDeliveryType  = itemView.findViewById(R.id.tv_delivery_type);
            llMenuContainer = itemView.findViewById(R.id.ll_menu_container);
            tvMoreItems     = itemView.findViewById(R.id.tv_more_items);
            tvTotal         = itemView.findViewById(R.id.tv_total_price);
        }
    }
}