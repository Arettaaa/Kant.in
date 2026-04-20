package com.example.kantin;

import android.annotation.SuppressLint;
import android.content.Context;
import android.graphics.Color;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.LinearLayout;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import java.text.NumberFormat;
import java.util.List;
import java.util.Locale;

public class OrderMasukAdapter extends RecyclerView.Adapter<OrderMasukAdapter.ViewHolder> {

    // Menambahkan 'final' untuk mengatasi warning
    private final Context context;
    private final List<ApiOrder> orderList;

    // Constructor
    public OrderMasukAdapter(Context context, List<ApiOrder> orderList) {
        this.context = context;
        this.orderList = orderList;
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(context).inflate(R.layout.item_order_masuk, parent, false);
        return new ViewHolder(view);
    }

    // Menambahkan Anotasi ini untuk mematikan warning "Do not concatenate text"
    @SuppressLint("SetTextI18n")
    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        ApiOrder order = orderList.get(position);

        // 1. Set Header Info
        holder.tvCustomerName.setText(order.getCustomerSnapshot().getName());
        holder.tvOrderId.setText(order.getOrderCode());
        holder.tvTotal.setText(formatRupiah(order.getTotalAmount()));
        holder.tvTime.setText(order.getCreatedAt());

        // 2. Set Delivery Badge
        if (order.getDeliveryDetails() != null && "pickup".equals(order.getDeliveryDetails().getMethod())) {
            holder.tvDeliveryType.setText("Ambil Sendiri");
            holder.tvDeliveryType.setTextColor(Color.parseColor("#9C27B0"));
            holder.tvDeliveryType.setBackgroundResource(R.drawable.bg_order_label);
        } else {
            holder.tvDeliveryType.setText("Antar Kurir");
            holder.tvDeliveryType.setTextColor(Color.parseColor("#1976D2"));
            holder.tvDeliveryType.setBackgroundResource(R.drawable.bg_order_label_blue);
        }

        // 3. Logic Maksimal 2 Menu List
        holder.llMenuContainer.removeAllViews();
        List<ApiOrder.OrderItem> items = order.getItems();

        if (items != null) {
            int maxItemsToShow = Math.min(items.size(), 2);

            for (int i = 0; i < maxItemsToShow; i++) {
                ApiOrder.OrderItem item = items.get(i);
                View rowView = LayoutInflater.from(context).inflate(R.layout.item_menu_row, holder.llMenuContainer, false);

                TextView tvQty = rowView.findViewById(R.id.tv_qty);
                TextView tvMenuName = rowView.findViewById(R.id.tv_menu_name);

                tvQty.setText(item.getQuantity() + "x");
                tvMenuName.setText(item.getName());

                holder.llMenuContainer.addView(rowView);
            }

            // 4. Logic "+ X item lainnya"
            if (items.size() > 2) {
                int remaining = items.size() - 2;
                holder.tvMoreItems.setText("+ " + remaining + " item lainnya");
                holder.tvMoreItems.setVisibility(View.VISIBLE);
            } else {
                holder.tvMoreItems.setVisibility(View.GONE);
            }
        }

        // 5. Aksi Klik Card
        holder.itemView.setOnClickListener(v -> {
            // Intent intent = new Intent(context, DetailPesananActivity.class);
            // intent.putExtra("ORDER_ID", order.getId());
            // context.startActivity(intent);
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