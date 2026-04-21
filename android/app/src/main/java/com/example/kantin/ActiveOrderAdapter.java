package com.example.kantin;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;
import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;
import com.example.kantin.model.response.BaseResponse;
import com.example.kantin.model.response.OrderListResponse;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;
import com.example.kantin.utils.SessionManager;
import java.text.NumberFormat;
import java.util.List;
import java.util.Locale;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class ActiveOrderAdapter extends RecyclerView.Adapter<ActiveOrderAdapter.ViewHolder> {

    private Context context;
    private List<OrderListResponse.OrderItem> orderList;

    public ActiveOrderAdapter(Context context, List<OrderListResponse.OrderItem> orderList) {
        this.context = context;
        this.orderList = orderList;
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(context).inflate(R.layout.item_active_order, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        final OrderListResponse.OrderItem order = orderList.get(position);
        // Order code & total
        holder.tvOrderCode.setText(order.getOrderCode());
        holder.tvTotalBayar.setText(formatRupiah(order.getTotalAmount()));

        // Tanggal
        holder.tvTanggal.setText(formatTanggal(order.getCreatedAt()));

        // Nama menu (gabungkan semua item)
        if (order.getItems() != null && !order.getItems().isEmpty()) {
            StringBuilder menuNames = new StringBuilder();
            for (int i = 0; i < order.getItems().size(); i++) {
                OrderListResponse.OrderItemDetail item = order.getItems().get(i);
                menuNames.append(item.getQuantity()).append("x ").append(item.getName());
                if (i < order.getItems().size() - 1) menuNames.append(", ");
            }
            holder.tvNamaMenu.setText(menuNames.toString());
        }

        // Nama kantin — sementara pakai canteen_id, nanti bisa diganti nama kantin
        holder.tvNamaKantin.setText(order.getCanteenName() != null ? order.getCanteenName() : "Kantin");

        // Update step tracker berdasarkan status
        updateStepTracker(holder, order.getStatus(), order);
    }

    private void updateStepTracker(ViewHolder holder, String status, OrderListResponse.OrderItem order) {
        // Reset semua ke abu-abu dulu
        setStepInactive(holder.iconMenunggu, holder.tvMenunggu);
        setStepInactive(holder.iconDimasak, holder.tvDimasak);
        setStepInactive(holder.iconSiap, holder.tvSiap);
        holder.line1.setBackgroundColor(context.getResources().getColor(android.R.color.darker_gray));
        holder.line2.setBackgroundColor(context.getResources().getColor(android.R.color.darker_gray));

        switch (status) {
            case "pending":
                setStepActive(holder.iconMenunggu, holder.tvMenunggu);
                holder.tvStatusText.setText("Menunggu verifikasi pembayaran...");
                holder.btnKonfirmasiTerima.setVisibility(View.GONE); // tambah ini
                break;

            case "processing":
                setStepActive(holder.iconMenunggu, holder.tvMenunggu);
                setStepActive(holder.iconDimasak, holder.tvDimasak);
                holder.line1.setBackgroundColor(0xFFF97316);
                holder.tvStatusText.setText("Pesanan sedang dimasak...");
                holder.btnKonfirmasiTerima.setVisibility(View.GONE); // tambah ini
                break;

            case "ready":
                setStepActive(holder.iconMenunggu, holder.tvMenunggu);
                setStepActive(holder.iconDimasak, holder.tvDimasak);
                setStepActive(holder.iconSiap, holder.tvSiap);
                holder.line1.setBackgroundColor(0xFFF97316);
                holder.line2.setBackgroundColor(0xFFF97316);
                holder.tvStatusText.setText("Pesanan siap diambil!");

                // Tampilkan tombol konfirmasi
                holder.btnKonfirmasiTerima.setVisibility(View.VISIBLE);
                holder.btnKonfirmasiTerima.setOnClickListener(v -> {
                    holder.btnKonfirmasiTerima.setEnabled(false);
                    holder.btnKonfirmasiTerima.setText("Memproses...");

                    String token = new SessionManager(context).getToken();
                    ApiClient.getAuthClient(token).create(ApiService.class)
                            .completeOrder(order.getId() != null ? order.getId() : order.getIdAlias())                            .enqueue(new Callback<BaseResponse>() {
                                @Override
                                public void onResponse(Call<BaseResponse> call, Response<BaseResponse> response) {
                                    if (response.isSuccessful()) {
                                        Toast.makeText(context, "Pesanan dikonfirmasi!", Toast.LENGTH_SHORT).show();
                                        // Hapus dari list
                                        int pos = orderList.indexOf(order);
                                        if (pos != -1) {
                                            orderList.remove(pos);
                                            notifyItemRemoved(pos);
                                        }
                                    } else {
                                        holder.btnKonfirmasiTerima.setEnabled(true);
                                        holder.btnKonfirmasiTerima.setText("Konfirmasi Terima Pesanan");
                                        Toast.makeText(context, "Gagal konfirmasi", Toast.LENGTH_SHORT).show();
                                    }
                                }

                                @Override
                                public void onFailure(Call<BaseResponse> call, Throwable t) {
                                    holder.btnKonfirmasiTerima.setEnabled(true);
                                    holder.btnKonfirmasiTerima.setText("Konfirmasi Terima Pesanan");
                                    Toast.makeText(context, "Error jaringan", Toast.LENGTH_SHORT).show();
                                }
                            });
                });
                break;
        }
    }

    private void setStepActive(ImageView icon, TextView label) {
        icon.setBackgroundResource(R.drawable.bg_circle_orange_outline);
        icon.setColorFilter(context.getResources().getColor(android.R.color.holo_orange_dark));
        label.setTextColor(0xFFF97316);
    }

    private void setStepInactive(ImageView icon, TextView label) {
        icon.setBackgroundResource(R.drawable.bg_circle_gray_outline);
        icon.setColorFilter(context.getResources().getColor(android.R.color.darker_gray));
        label.setTextColor(0xFF9CA3AF);
    }

    private String formatRupiah(double harga) {
        NumberFormat fmt = NumberFormat.getCurrencyInstance(new Locale("id", "ID"));
        return fmt.format(harga).replace(",00", "");
    }

    private String formatTanggal(String createdAt) {
        try {
            // Format: "2026-04-21T01:20:39.980000Z" → "21 Apr 2026 • 08:20 AM"
            java.text.SimpleDateFormat inputFormat = new java.text.SimpleDateFormat("yyyy-MM-dd'T'HH:mm:ss", Locale.getDefault());
            inputFormat.setTimeZone(java.util.TimeZone.getTimeZone("UTC"));
            java.util.Date date = inputFormat.parse(createdAt);

            java.text.SimpleDateFormat outputFormat = new java.text.SimpleDateFormat("dd MMM yyyy • hh:mm a", new Locale("id", "ID"));
            outputFormat.setTimeZone(java.util.TimeZone.getTimeZone("Asia/Jakarta"));
            return outputFormat.format(date);
        } catch (Exception e) {
            return createdAt;
        }
    }

    @Override
    public int getItemCount() {
        return orderList != null ? orderList.size() : 0;
    }

    public static class ViewHolder extends RecyclerView.ViewHolder {
        TextView tvOrderCode, tvTotalBayar, tvTanggal, tvNamaKantin, tvNamaMenu, tvStatusText;
        ImageView iconMenunggu, iconDimasak, iconSiap;
        View line1, line2;
        TextView tvMenunggu, tvDimasak, tvSiap;

        com.google.android.material.button.MaterialButton btnKonfirmasiTerima;


        public ViewHolder(@NonNull View itemView) {
            super(itemView);
            tvOrderCode = itemView.findViewById(R.id.tvOrderCode);
            tvTotalBayar = itemView.findViewById(R.id.tvTotalBayar);
            tvTanggal = itemView.findViewById(R.id.tvTanggal);
            tvNamaKantin = itemView.findViewById(R.id.tvNamaKantin);
            tvNamaMenu = itemView.findViewById(R.id.tvNamaMenu);
            tvStatusText = itemView.findViewById(R.id.tvStatusText);
            iconMenunggu = itemView.findViewById(R.id.iconMenunggu);
            iconDimasak = itemView.findViewById(R.id.iconDimasak);
            iconSiap = itemView.findViewById(R.id.iconSiap);
            line1 = itemView.findViewById(R.id.line1);
            line2 = itemView.findViewById(R.id.line2);
            tvMenunggu = itemView.findViewById(R.id.tvMenunggu);
            tvDimasak = itemView.findViewById(R.id.tvDimasak);
            tvSiap = itemView.findViewById(R.id.tvSiap);
            btnKonfirmasiTerima = itemView.findViewById(R.id.btnKonfirmasiTerima);

        }
    }
}


//pending → masih nunggu verifikasi (user sudah di halaman ValidasiAdmin)
//processing → sudah diverifikasi, sedang dimasak
//ready → siap diambil