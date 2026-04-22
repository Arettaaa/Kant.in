package com.example.kantin;

import android.annotation.SuppressLint;
import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.LinearLayout;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.example.kantin.model.OrderItem;
import com.example.kantin.model.OrderModel;
import com.example.kantin.utils.SessionManager;

import java.text.NumberFormat;
import java.util.List;
import java.util.Locale;

public class OrderProsesAdapter extends RecyclerView.Adapter<OrderProsesAdapter.ViewHolder> {

    private final Context context;
    private final List<OrderModel> orderList;

    public OrderProsesAdapter(Context context, List<OrderModel> orderList) {
        this.context = context;
        this.orderList = orderList;
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(context).inflate(R.layout.item_order_proses, parent, false);
        return new ViewHolder(view);
    }

    @SuppressLint("SetTextI18n")
    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        OrderModel order = orderList.get(position);

        // Mencegah data kosong (Crash Protection)
        if (order == null) return;

        // 1. Customer Name
        if (holder.tvCustomerName != null) {
            if (order.getCustomerSnapshot() != null && order.getCustomerSnapshot().getName() != null) {
                String fullName = order.getCustomerSnapshot().getName();
                String firstName = fullName.contains(" ") ? fullName.split(" ")[0] : fullName;
                holder.tvCustomerName.setText(firstName);
            } else {
                holder.tvCustomerName.setText("Pelanggan");
            }
        }

        // 2. Kode order
        if (holder.tvOrderId != null) {
            holder.tvOrderId.setText(order.getOrderCode() != null ? order.getOrderCode() : "-");
        }

        // 3. Waktu (Otomatis WIB)
        if (holder.tvTime != null) {
            holder.tvTime.setText(formatWaktuWIB(order.getCreatedAt()));
        }

        // 4. Total
        if (holder.tvTotal != null) {
            holder.tvTotal.setText(formatRupiah(order.getTotalAmount()));
        }

        // 5. Badge Status
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

        // 6. Delivery Type
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

        // 7. Menu List Container
        if (holder.llMenuContainer != null) {
            holder.llMenuContainer.removeAllViews();
            List<OrderItem> items = order.getItems();

            if (items != null && !items.isEmpty()) {
                int maxItemsToShow = Math.min(items.size(), 2);
                for (int i = 0; i < maxItemsToShow; i++) {
                    OrderItem item = items.get(i);
                    View rowView = LayoutInflater.from(context).inflate(R.layout.item_menu_row, holder.llMenuContainer, false);
                    TextView tvQty = rowView.findViewById(R.id.tv_qty);
                    TextView tvMenuName = rowView.findViewById(R.id.tv_menu_name);

                    if (tvQty != null) tvQty.setText(item.getQuantity() + "x");
                    if (tvMenuName != null) tvMenuName.setText(item.getName() != null ? item.getName() : "Menu");

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

        // 8. Klik card ke detail
        holder.itemView.setOnClickListener(v -> {
            SessionManager session = new SessionManager(context);
            Intent intent = new Intent(context, UpdateStatusPesananActivity.class);
            // Ambil ID dari OrderModel
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
        TextView tvCustomerName, tvTime, tvOrderId, tvDeliveryType;
        TextView tvStatusBadge, tvTotal, tvMoreItems;
        LinearLayout llMenuContainer;

        public ViewHolder(@NonNull View itemView) {
            super(itemView);
            tvCustomerName  = itemView.findViewById(R.id.tvCustomerNameProses);
            tvTime          = itemView.findViewById(R.id.tvOrderTimeProses);
            tvOrderId       = itemView.findViewById(R.id.tvOrderIdProses);
            tvDeliveryType  = itemView.findViewById(R.id.tvDeliveryTypeProses);
            tvStatusBadge   = itemView.findViewById(R.id.tvStatusText);
            tvTotal         = itemView.findViewById(R.id.tvTotalProses);
            tvMoreItems     = itemView.findViewById(R.id.tvMoreItemsProses);
            llMenuContainer = itemView.findViewById(R.id.llMenuContainerProses);
        }
    }
}