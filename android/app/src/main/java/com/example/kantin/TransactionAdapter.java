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
import java.util.ArrayList;
import java.util.List;
import java.util.Locale;

public class TransactionAdapter extends RecyclerView.Adapter<TransactionAdapter.ViewHolder> {

    private List<TransactionOrder> orders;

    public TransactionAdapter(List<TransactionOrder> orders) {
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

        // --- Kode Order + Jumlah Item ---
        int itemCount = order.getItemCount();
        String detailText = order.getOrderCode() + "  •  " + itemCount + " item";
        holder.tvDetailPesanan.setText(detailText);

        if (holder.tvJumlahItem != null) {
            holder.tvJumlahItem.setVisibility(View.GONE);
        }

        // --- Total Harga ---
        NumberFormat nf = NumberFormat.getCurrencyInstance(new Locale("id", "ID"));
        holder.tvTotalHarga.setText(nf.format(order.getTotalAmount()));

        // --- STATUS & WARNA ---
        if (isCancelled) {
            holder.tvStatus.setText("DIBATALKAN");
            holder.tvStatus.setTextColor(Color.parseColor("#F44336"));

            holder.tvTotalHarga.setPaintFlags(
                    holder.tvTotalHarga.getPaintFlags() | Paint.STRIKE_THRU_TEXT_FLAG);
            holder.tvTotalHarga.setTextColor(Color.parseColor("#9E9E9E"));

            holder.cardView.setStrokeColor(Color.parseColor("#FFCDD2"));
            holder.cardView.setCardBackgroundColor(Color.parseColor("#FFF8F8"));

            holder.ivIcon.setBackgroundResource(R.drawable.bg_circle_lightred);
            holder.ivIcon.setImageResource(R.drawable.receipt_red);

        } else {
            // completed
            holder.tvStatus.setText("SELESAI");
            holder.tvStatus.setTextColor(Color.parseColor("#00C853"));

            holder.tvTotalHarga.setPaintFlags(
                    holder.tvTotalHarga.getPaintFlags() & ~Paint.STRIKE_THRU_TEXT_FLAG);
            holder.tvTotalHarga.setTextColor(Color.parseColor("#1A1A1A"));

            holder.cardView.setStrokeColor(Color.parseColor("#EEEEEE"));
            holder.cardView.setCardBackgroundColor(Color.WHITE);

            holder.ivIcon.setBackgroundResource(R.drawable.bg_circle_lightgreen);
            holder.ivIcon.setImageResource(R.drawable.receipt_green);
        }

        // --- Klik item → kirim semua data yang dibutuhkan DetaiTransaksiActivity ---
        holder.itemView.setOnClickListener(v -> {
            Intent intent = new Intent(v.getContext(), DetaiTransaksiActivity.class);

            // Data utama
            intent.putExtra("order_code",      order.getOrderCode());
            intent.putExtra("customer_name",   order.getCustomerName());
            intent.putExtra("status",          order.getStatus());
            intent.putExtra("created_at",      order.getCreatedAt());
            intent.putExtra("total_amount",    order.getTotalAmount());
            intent.putExtra("subtotal_amount", order.getSubtotalAmount());

            // Delivery fee
            double deliveryFee = 0;
            if (order.getDeliveryDetails() != null) {
                deliveryFee = order.getDeliveryDetails().fee;
            }
            intent.putExtra("delivery_fee", deliveryFee);

            // Payment method
            String paymentMethod = "";
            if (order.getPayment() != null && order.getPayment().method != null) {
                paymentMethod = order.getPayment().method;
            }
            intent.putExtra("payment_method", paymentMethod);

            // List item menu
            ArrayList<String>  itemNames     = new ArrayList<>();
            ArrayList<String>  itemPrices    = new ArrayList<>();
            ArrayList<Integer> itemQtys      = new ArrayList<>();
            ArrayList<String>  itemNotes     = new ArrayList<>();
            ArrayList<String>  itemSubtotals = new ArrayList<>();

            if (order.getItems() != null) {
                for (TransactionOrder.OrderItem item : order.getItems()) {
                    itemNames.add(item.name != null ? item.name : "");
                    itemPrices.add(String.valueOf(item.price));
                    itemQtys.add(item.quantity);
                    itemNotes.add(item.notes != null ? item.notes : "");
                    itemSubtotals.add(String.valueOf(item.subtotal));
                }
            }

            intent.putStringArrayListExtra("item_names",     itemNames);
            intent.putStringArrayListExtra("item_prices",    itemPrices);
            intent.putIntegerArrayListExtra("item_qtys",     itemQtys);
            intent.putStringArrayListExtra("item_notes",     itemNotes);
            intent.putStringArrayListExtra("item_subtotals", itemSubtotals);

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
            cardView        = (MaterialCardView) itemView;
            ivIcon          = itemView.findViewById(R.id.ivIcon);
            tvNamaPelanggan = itemView.findViewById(R.id.tvNamaPelanggan);
            tvDetailPesanan = itemView.findViewById(R.id.tvDetailPesanan);
            tvJumlahItem    = itemView.findViewById(R.id.tvJumlahItem);
            tvTotalHarga    = itemView.findViewById(R.id.tvTotalHarga);
            tvStatus        = itemView.findViewById(R.id.tvStatus);
        }
    }
}