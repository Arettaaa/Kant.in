package com.example.kantin;

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

import com.example.kantin.model.response.OrderListResponse;
import com.example.kantin.model.response.RatingCheckResponse;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;
import com.example.kantin.utils.SessionManager;

import java.text.NumberFormat;
import java.util.List;
import java.util.Locale;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class HistoryAdapter extends RecyclerView.Adapter<HistoryAdapter.ViewHolder> {

    private Context context;
    private List<OrderListResponse.OrderItem> orderList;

    public HistoryAdapter(Context context, List<OrderListResponse.OrderItem> orderList) {
        this.context   = context;
        this.orderList = orderList;
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(context).inflate(R.layout.item_history, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        OrderListResponse.OrderItem order = orderList.get(position);

        holder.tvTanggal.setText(formatTanggal(order.getCreatedAt()));
        holder.tvTotalBayar.setText(formatRupiah(order.getTotalAmount()));
        holder.tvNamaKantin.setText(order.getCanteenName());

        if (order.getItems() != null && !order.getItems().isEmpty()) {
            StringBuilder menuNames = new StringBuilder();
            for (int i = 0; i < order.getItems().size(); i++) {
                OrderListResponse.OrderItemDetail item = order.getItems().get(i);
                menuNames.append(item.getQuantity()).append("x ").append(item.getName());
                if (i < order.getItems().size() - 1) menuNames.append(", ");
            }
            holder.tvNamaMenu.setText(menuNames.toString());
        }

        if ("completed".equals(order.getStatus())) {
            holder.layoutBadgeStatus.setBackgroundResource(R.drawable.bg_badge_green_light);
            holder.imgBadgeIcon.setImageResource(R.drawable.checkcircle);
            holder.imgBadgeIcon.setColorFilter(
                    context.getResources().getColor(android.R.color.holo_green_dark));
            holder.tvBadgeStatus.setText("Selesai");

            holder.layoutBtnCompleted.setVisibility(View.VISIBLE);
            holder.btnPesanLagiCancelled.setVisibility(View.GONE);

            // Set tombol Nilai loading dulu, lalu cek status rating dari API
            setNilaiButtonLoading(holder);
            String orderId = order.getId() != null ? order.getId() : order.getIdAlias();
            cekStatusRating(orderId, holder, order);
            holder.btnPesanLagi.setOnClickListener(v -> pesanLagi(order));

        } else if ("cancelled".equals(order.getStatus())) {
            int redColor = 0xFFEF4444;
            holder.layoutBadgeStatus.setBackgroundResource(R.drawable.bg_badge_red_light);
            holder.imgBadgeIcon.setImageResource(R.drawable.xcircle);
            holder.imgBadgeIcon.setColorFilter(redColor);
            holder.tvBadgeStatus.setText("Dibatalkan");
            holder.tvBadgeStatus.setTextColor(redColor);

            holder.layoutBtnCompleted.setVisibility(View.GONE);
            holder.btnPesanLagiCancelled.setVisibility(View.VISIBLE);
            holder.btnPesanLagiCancelled.setOnClickListener(v -> pesanLagi(order));
        }
    }

    // ── Cek rating dari API ────────────────────────────────────

    private void cekStatusRating(String orderId, ViewHolder holder,
                                 OrderListResponse.OrderItem order) {
        String token = new SessionManager(context).getToken();
        ApiClient.getAuthClient(token).create(ApiService.class)
                .checkRating(orderId)
                .enqueue(new Callback<RatingCheckResponse>() {
                    @Override
                    public void onResponse(Call<RatingCheckResponse> call,
                                           Response<RatingCheckResponse> response) {
                        boolean hasRated = false;
                        if (response.isSuccessful() && response.body() != null
                                && response.body().getData() != null) {
                            hasRated = response.body().getData().isHasRated();
                        }
                        if (hasRated) {
                            setNilaiButtonSudahDinilai(holder);
                        } else {
                            setNilaiButtonAktif(holder, order);
                        }
                    }

                    @Override
                    public void onFailure(Call<RatingCheckResponse> call, Throwable t) {
                        // Kalau gagal network, tampilkan tombol aktif agar user bisa coba
                        setNilaiButtonAktif(holder, order);
                    }
                });
    }

    // ── State tombol Nilai ─────────────────────────────────────

    private void setNilaiButtonLoading(ViewHolder holder) {
        holder.btnNilai.setText("...");
        holder.btnNilai.setEnabled(false);
        holder.btnNilai.setAlpha(0.5f);
    }

    private void setNilaiButtonSudahDinilai(ViewHolder holder) {
        holder.btnNilai.setText("Sudah Dinilai");
        holder.btnNilai.setEnabled(false);
        holder.btnNilai.setAlpha(0.5f);
        holder.btnNilai.setOnClickListener(null);
    }

    private void setNilaiButtonAktif(ViewHolder holder, OrderListResponse.OrderItem order) {
        holder.btnNilai.setText("Nilai");
        holder.btnNilai.setEnabled(true);
        holder.btnNilai.setAlpha(1.0f);
        holder.btnNilai.setOnClickListener(v -> {
            if (context instanceof HistoryActivity) {
                String orderId = order.getId() != null ? order.getId() : order.getIdAlias();
                ((HistoryActivity) context).showRatingDialog(orderId, holder);
            }
        });
    }

    // ── Setelah rating berhasil dikirim ───────────────────────

    public void onRatingSubmitted(ViewHolder holder) {
        setNilaiButtonSudahDinilai(holder);
    }

    // ── Pesan lagi ─────────────────────────────────────────────

    private void pesanLagi(OrderListResponse.OrderItem order) {
        if (order.getItems() != null && order.getItems().size() == 1) {
            String menuId = order.getItems().get(0).getMenuId();
            Intent intent = new Intent(context, DetailMenuActivity.class);
            intent.putExtra("MENU_ID", menuId);
            context.startActivity(intent);
        } else {
            Intent intent = new Intent(context, DetailKantinActivity.class);
            intent.putExtra("CANTEEN_ID", order.getCanteenId());
            context.startActivity(intent);
        }
    }

    // ── Format helpers ─────────────────────────────────────────

    private String formatRupiah(double harga) {
        NumberFormat fmt = NumberFormat.getCurrencyInstance(new Locale("id", "ID"));
        return fmt.format(harga).replace(",00", "");
    }

    private String formatTanggal(String createdAt) {
        try {
            java.text.SimpleDateFormat inputFormat = new java.text.SimpleDateFormat(
                    "yyyy-MM-dd'T'HH:mm:ss.SSSSSS'Z'", Locale.getDefault());
            inputFormat.setTimeZone(java.util.TimeZone.getTimeZone("UTC"));
            java.util.Date date = inputFormat.parse(createdAt);
            java.text.SimpleDateFormat outputFormat = new java.text.SimpleDateFormat(
                    "dd MMM yyyy • HH:mm 'WIB'", new Locale("id", "ID"));
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
        TextView tvTanggal, tvTotalBayar, tvNamaKantin, tvNamaMenu;
        TextView tvBadgeStatus, btnNilai, btnPesanLagi, btnPesanLagiCancelled;
        ImageView imgBadgeIcon;
        LinearLayout layoutBadgeStatus, layoutBtnCompleted;

        public ViewHolder(@NonNull View itemView) {
            super(itemView);
            tvTanggal             = itemView.findViewById(R.id.tvTanggal);
            tvTotalBayar          = itemView.findViewById(R.id.tvTotalBayar);
            tvNamaKantin          = itemView.findViewById(R.id.tvNamaKantin);
            tvNamaMenu            = itemView.findViewById(R.id.tvNamaMenu);
            tvBadgeStatus         = itemView.findViewById(R.id.tvBadgeStatus);
            imgBadgeIcon          = itemView.findViewById(R.id.imgBadgeIcon);
            layoutBadgeStatus     = itemView.findViewById(R.id.layoutBadgeStatus);
            layoutBtnCompleted    = itemView.findViewById(R.id.layoutBtnCompleted);
            btnNilai              = itemView.findViewById(R.id.btnNilai);
            btnPesanLagi          = itemView.findViewById(R.id.btnPesanLagi);
            btnPesanLagiCancelled = itemView.findViewById(R.id.btnPesanLagiCancelled);
        }
    }
}