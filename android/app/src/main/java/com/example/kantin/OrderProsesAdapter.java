package com.example.kantin;

import android.annotation.SuppressLint;
import android.content.Context;
import android.content.Intent;
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
import com.example.kantin.model.OrderItem;
import com.example.kantin.model.OrderModel;
import com.example.kantin.utils.SessionManager;

import java.text.NumberFormat;
import java.util.List;
import java.util.Locale;

public class OrderProsesAdapter extends RecyclerView.Adapter<OrderProsesAdapter.ViewHolder> {

    private static final String BASE_URL_STORAGE =
            "https://nonephemerally-nonrevolving-judie.ngrok-free.dev/storage/";

    private final Context context;
    private final List<OrderModel> orderList;

    public OrderProsesAdapter(Context context, List<OrderModel> orderList) {
        this.context = context;
        this.orderList = orderList;
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(context).inflate(R.layout.item_order_diproses, parent, false);
        return new ViewHolder(view);
    }

    @SuppressLint("SetTextI18n")
    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        OrderModel order = orderList.get(position);
        if (order == null) return;

        // 1. Nama pelanggan + foto profil
        if (order.getCustomerSnapshot() != null) {
            String fullName = order.getCustomerSnapshot().getName();
            String firstName = (fullName != null && fullName.contains(" "))
                    ? fullName.split(" ")[0] : fullName;
            if (holder.tvCustomerName != null)
                holder.tvCustomerName.setText(firstName != null ? firstName : "Pelanggan");

            // ✅ Load foto profil — ID: iv_customer_profile_proses (dari XML yang sudah diperbaiki)
            String photoPath = order.getCustomerSnapshot().getPhotoProfile();
            if (holder.ivCustomerProfile != null) {
                if (photoPath != null && !photoPath.isEmpty()) {
                    String fullUrl = photoPath.startsWith("http")
                            ? photoPath : BASE_URL_STORAGE + photoPath;
                    Glide.with(context)
                            .load(fullUrl)
                            .circleCrop()
                            .placeholder(R.drawable.avatar)
                            .error(R.drawable.avatar)
                            .into(holder.ivCustomerProfile);
                } else {
                    holder.ivCustomerProfile.setImageResource(R.drawable.avatar);
                }
            }
        } else {
            if (holder.tvCustomerName != null) holder.tvCustomerName.setText("Pelanggan");
            if (holder.ivCustomerProfile != null)
                holder.ivCustomerProfile.setImageResource(R.drawable.avatar);
        }

        // 2. Kode order & waktu
        if (holder.tvOrderId != null)
            holder.tvOrderId.setText(order.getOrderCode() != null ? order.getOrderCode() : "-");
        if (holder.tvTime != null)
            holder.tvTime.setText(formatWaktuWIB(order.getCreatedAt()));

        // 3. Total
        if (holder.tvTotal != null)
            holder.tvTotal.setText(formatRupiah(order.getTotalAmount()));

        // 4. Badge status
        if (holder.tvStatusBadge != null) {
            String status = order.getStatus() != null ? order.getStatus() : "";
            if ("processing".equalsIgnoreCase(status)) {
                holder.tvStatusBadge.setText("Dimasak");
                holder.tvStatusBadge.setTextColor(Color.parseColor("#F97316"));
                holder.tvStatusBadge.setBackgroundResource(R.drawable.admin_badge_orange_light);
            } else if ("ready".equalsIgnoreCase(status)) {
                holder.tvStatusBadge.setText("Siap Diambil");
                holder.tvStatusBadge.setTextColor(Color.parseColor("#00B050"));
                holder.tvStatusBadge.setBackgroundResource(R.drawable.admin_badge_green_light);
            } else {
                holder.tvStatusBadge.setText(status);
                holder.tvStatusBadge.setTextColor(Color.parseColor("#888888"));
            }
        }

        // 5. Badge delivery
        if (holder.tvDeliveryType != null) {
            if (order.getDeliveryDetails() != null && "pickup".equals(order.getDeliveryDetails().getMethod())) {
                holder.tvDeliveryType.setText("Ambil Sendiri");
                holder.tvDeliveryType.setTextColor(Color.parseColor("#9C27B0"));
                holder.tvDeliveryType.setBackgroundResource(R.drawable.bg_order_label);
            } else {
                holder.tvDeliveryType.setText("Antar Kurir");
                holder.tvDeliveryType.setTextColor(Color.parseColor("#1976D2"));
                holder.tvDeliveryType.setBackgroundResource(R.drawable.bg_order_label_blue);
            }
        }

        // 6. Menu list (maks 2)
        if (holder.llMenuContainer != null) {
            holder.llMenuContainer.removeAllViews();
            List<OrderItem> items = order.getItems();
            if (items != null && !items.isEmpty()) {
                int maxItemsToShow = Math.min(items.size(), 2);
                for (int i = 0; i < maxItemsToShow; i++) {
                    OrderItem item = items.get(i);
                    View rowView = LayoutInflater.from(context)
                            .inflate(R.layout.item_menu_row, holder.llMenuContainer, false);
                    TextView tvQty = rowView.findViewById(R.id.tv_qty);
                    TextView tvMenuName = rowView.findViewById(R.id.tv_menu_name);
                    if (tvQty != null) tvQty.setText(item.getQuantity() + "x");
                    if (tvMenuName != null)
                        tvMenuName.setText(item.getName() != null ? item.getName() : "Menu");
                    holder.llMenuContainer.addView(rowView);
                }
                if (holder.tvMoreItems != null) {
                    if (items.size() > 2) {
                        holder.tvMoreItems.setText("+ " + (items.size() - 2) + " item lainnya");
                        holder.tvMoreItems.setVisibility(View.VISIBLE);
                    } else {
                        holder.tvMoreItems.setVisibility(View.GONE);
                    }
                }
            }
        }

        // 7. Klik card → UpdateStatusPesananActivity
        holder.itemView.setOnClickListener(v -> {
            SessionManager session = new SessionManager(context);
            Intent intent = new Intent(context, UpdateStatusPesananActivity.class);
            intent.putExtra("ORDER_ID", order.getId());
            intent.putExtra("CANTEEN_ID", session.getCanteenId());
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
            java.text.SimpleDateFormat inputFormat =
                    new java.text.SimpleDateFormat("yyyy-MM-dd HH:mm:ss", Locale.getDefault());
            inputFormat.setTimeZone(java.util.TimeZone.getTimeZone("UTC"));
            java.util.Date date = inputFormat.parse(cleanDate);
            java.text.SimpleDateFormat outputFormat =
                    new java.text.SimpleDateFormat("HH:mm", new Locale("id", "ID"));
            outputFormat.setTimeZone(java.util.TimeZone.getTimeZone("Asia/Jakarta"));
            return outputFormat.format(date);
        } catch (Exception e) {
            return createdAt.length() >= 16 ? createdAt.substring(11, 16) : "-";
        }
    }

    public static class ViewHolder extends RecyclerView.ViewHolder {
        ImageView ivCustomerProfile; // ✅ ID: iv_customer_profile_proses (dari XML)
        TextView tvCustomerName, tvTime, tvOrderId, tvDeliveryType;
        TextView tvStatusBadge, tvTotal, tvMoreItems;
        LinearLayout llMenuContainer;

        public ViewHolder(@NonNull View itemView) {
            super(itemView);
            ivCustomerProfile = itemView.findViewById(R.id.iv_customer_profile_proses); 
            tvCustomerName    = itemView.findViewById(R.id.tvCustomerNameProses);
            tvTime            = itemView.findViewById(R.id.tvOrderTimeProses);
            tvOrderId         = itemView.findViewById(R.id.tvOrderIdProses);
            tvDeliveryType    = itemView.findViewById(R.id.tvDeliveryTypeProses);
            tvStatusBadge     = itemView.findViewById(R.id.tvStatusText);
            tvTotal           = itemView.findViewById(R.id.tvTotalProses);
            tvMoreItems       = itemView.findViewById(R.id.tvMoreItemsProses);
            llMenuContainer   = itemView.findViewById(R.id.llMenuContainerProses);
        }
    }
}