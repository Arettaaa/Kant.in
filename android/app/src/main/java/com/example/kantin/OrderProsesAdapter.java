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
import androidx.core.content.ContextCompat;
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

        if (order.getCustomerSnapshot() != null) {
            String fullName = order.getCustomerSnapshot().getName();
            String firstName = (fullName != null && fullName.contains(" "))
                    ? fullName.split(" ")[0] : fullName;
            holder.tvCustomerName.setText(firstName);        }
        else {
            holder.tvCustomerName.setText("Pelanggan");
        }

        // 2. Kode order & waktu
        holder.tvOrderId.setText(order.getOrderCode());
        String rawDate = order.getCreatedAt();
        holder.tvTime.setText(rawDate != null && rawDate.length() >= 16 ? rawDate.substring(11, 16) : "-");

        // 3. Total
        holder.tvTotal.setText(formatRupiah(order.getTotalAmount()));

        // 4. Badge status — warna dinamis
        String status = order.getStatus();
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

        // 5. Delivery badge
        if (order.getDeliveryDetails() != null && "pickup".equals(order.getDeliveryDetails().getMethod())) {
            holder.tvDeliveryType.setText("Ambil Sendiri");
            holder.tvDeliveryType.setTextColor(Color.parseColor("#9C27B0"));
            holder.tvDeliveryType.setBackgroundResource(R.drawable.bg_order_label);
        } else {
            holder.tvDeliveryType.setText("Antar Kurir");
            holder.tvDeliveryType.setTextColor(Color.parseColor("#1976D2"));
            holder.tvDeliveryType.setBackgroundResource(R.drawable.bg_order_label_blue);
        }

        // 6. Menu list — maks 2 item
        if (holder.llMenuContainer != null) {
            holder.llMenuContainer.removeAllViews();
            List<OrderItem> items = order.getItems();

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

                if (items.size() > 2) {
                    holder.tvMoreItems.setText("+ " + (items.size() - 2) + " item lainnya");
                    holder.tvMoreItems.setVisibility(View.VISIBLE);
                } else {
                    holder.tvMoreItems.setVisibility(View.GONE);
                }
            }
        }

        // 7. Klik card ke detail
        holder.itemView.setOnClickListener(v -> {
            SessionManager session = new SessionManager(context);
            Intent intent = new Intent(context, DetailPesanan.class);
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