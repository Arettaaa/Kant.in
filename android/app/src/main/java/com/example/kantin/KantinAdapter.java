package com.example.kantin;

import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.cardview.widget.CardView;
import androidx.recyclerview.widget.RecyclerView;

import com.bumptech.glide.Glide;
import com.example.kantin.model.response.CanteenListResponse;

import java.util.ArrayList;
import java.util.List;
import com.example.kantin.model.response.MenuListResponse;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;

import java.util.List;
import java.util.Locale;
public class KantinAdapter extends RecyclerView.Adapter<KantinAdapter.KantinViewHolder> {

    private Context context;
    private List<CanteenListResponse.CanteenData> listKantin;
    private List<CanteenListResponse.CanteenData> listOriginal;
    private final java.util.Map<String, String> ratingCache = new java.util.HashMap<>();

    public KantinAdapter(Context context, List<CanteenListResponse.CanteenData> listKantin) {
        this.context = context;
        this.listKantin = new ArrayList<>(listKantin);
        this.listOriginal = new ArrayList<>(listKantin); // simpan data asli
    }

    // Filter berdasarkan query search DAN status filter
    public void filter(String query, String statusFilter) {
        listKantin.clear();
        for (CanteenListResponse.CanteenData kantin : listOriginal) {
            boolean matchSearch = kantin.getName().toLowerCase().contains(query.toLowerCase());
            boolean isBuka = isKantinBuka(kantin); // ← pakai method baru
            boolean matchStatus;
            if (statusFilter.equals("Buka")) {
                matchStatus = isBuka;
            } else if (statusFilter.equals("Tutup")) {
                matchStatus = !isBuka;
            } else {
                matchStatus = true;
            }
            if (matchSearch && matchStatus) {
                listKantin.add(kantin);
            }
        }
        notifyDataSetChanged();
    }

    @NonNull
    @Override
    public KantinViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(context).inflate(R.layout.item_kantin, parent, false);
        return new KantinViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull KantinViewHolder holder, int position) {
        CanteenListResponse.CanteenData kantin = listKantin.get(position);

        holder.tvNamaKantin.setText(kantin.getName());

        // Rating kantin (hitung dari rata-rata menu)
        holder.tvRatingWarung.setText("...");
        fetchRatingKantin(kantin.getId(), holder);

        if (kantin.getOperatingHours() != null) {
            String open = kantin.getOperatingHours().getOpen();
            String close = kantin.getOperatingHours().getClose();
            holder.tvJamOperasional.setText(open + " - " + close);
        } else {
            holder.tvJamOperasional.setText("-");
        }

        boolean isBuka = isKantinBuka(kantin);

        if (isBuka) {
            holder.tvStatusKantin.setText("Buka");
            holder.tvStatusKantin.setTextColor(Color.parseColor("#10B981"));
            holder.cvStatusKantin.setCardBackgroundColor(Color.parseColor("#D1FAE5"));
            holder.ivStatusIcon.setColorFilter(Color.parseColor("#10B981"));
        } else {
            holder.tvStatusKantin.setText("Tutup");
            holder.tvStatusKantin.setTextColor(Color.parseColor("#EF4444"));
            holder.cvStatusKantin.setCardBackgroundColor(Color.parseColor("#FEE2E2"));
            holder.ivStatusIcon.setColorFilter(Color.parseColor("#EF4444"));
        }

        String imageUrl = kantin.getImage();
        if (imageUrl != null && !imageUrl.startsWith("http")) {
            imageUrl = "https://nonephemerally-nonrevolving-judie.ngrok-free.dev/storage/" + imageUrl;
        }

        Glide.with(context)
                .load(imageUrl)
                .placeholder(android.R.color.darker_gray)
                .error(android.R.color.darker_gray)
                .centerCrop()
                .into(holder.ivKantin);

        holder.itemView.setOnClickListener(v -> {
            Intent intent = new Intent(context, DetailKantinActivity.class);
            intent.putExtra("CANTEEN_ID", kantin.getId());
            context.startActivity(intent);
        });
    }

    private boolean isKantinBuka(CanteenListResponse.CanteenData kantin) {
        // Kalau is_open false, langsung tutup
        if (!kantin.isOpen()) return false;

        // Kalau tidak ada jam operasional, fallback ke is_open saja
        if (kantin.getOperatingHours() == null) return true;

        try {
            String openStr = kantin.getOperatingHours().getOpen();   // contoh: "08:00"
            String closeStr = kantin.getOperatingHours().getClose(); // contoh: "17:00"

            java.util.Calendar now = java.util.Calendar.getInstance();
            int nowHour = now.get(java.util.Calendar.HOUR_OF_DAY);
            int nowMinute = now.get(java.util.Calendar.MINUTE);
            int nowTotal = nowHour * 60 + nowMinute;

            String[] openParts = openStr.split(":");
            String[] closeParts = closeStr.split(":");

            int openTotal = Integer.parseInt(openParts[0]) * 60 + Integer.parseInt(openParts[1]);
            int closeTotal = Integer.parseInt(closeParts[0]) * 60 + Integer.parseInt(closeParts[1]);

            if (openTotal < closeTotal) {
                // Normal: misal 08:00 - 17:00
                return nowTotal >= openTotal && nowTotal < closeTotal;
            } else {
                // Melewati tengah malam: misal 23:00 - 07:00
                return nowTotal >= openTotal || nowTotal < closeTotal;
            }
        } catch (Exception e) {
            // Kalau parsing gagal, fallback ke is_open
            return kantin.isOpen();
        }
    }

    @Override
    public int getItemCount() {
        return listKantin != null ? listKantin.size() : 0;
    }

    public static class KantinViewHolder extends RecyclerView.ViewHolder {
        ImageView ivKantin, ivStatusIcon;
        TextView tvNamaKantin, tvStatusKantin, tvJamOperasional, tvRatingWarung;
        CardView cvStatusKantin;

        public KantinViewHolder(@NonNull View itemView) {
            super(itemView);
            ivKantin         = itemView.findViewById(R.id.iv_kantin);
            tvNamaKantin     = itemView.findViewById(R.id.tv_nama_kantin);
            tvStatusKantin   = itemView.findViewById(R.id.tv_status_kantin);
            tvJamOperasional = itemView.findViewById(R.id.tv_jam_operasional);
            cvStatusKantin   = itemView.findViewById(R.id.cv_status_kantin);
            ivStatusIcon     = itemView.findViewById(R.id.iv_status_icon);
            tvRatingWarung   = itemView.findViewById(R.id.tv_rating_kantin); // tambah ini
        }
    }

    private void fetchRatingKantin(String canteenId, KantinViewHolder holder) {
        // Cek cache dulu
        if (ratingCache.containsKey(canteenId)) {
            holder.tvRatingWarung.setText(ratingCache.get(canteenId));
            return;
        }

        ApiClient.getClient().create(ApiService.class)
                .getCanteenMenus(canteenId)
                .enqueue(new retrofit2.Callback<MenuListResponse>() {
                    @Override
                    public void onResponse(retrofit2.Call<MenuListResponse> call,
                                           retrofit2.Response<MenuListResponse> response) {
                        String ratingText = "Baru";
                        if (response.isSuccessful() && response.body() != null
                                && response.body().getData() != null) {
                            List<MenuListResponse.MenuItem> menus = response.body().getData();
                            double total = 0;
                            int count = 0;
                            for (MenuListResponse.MenuItem menu : menus) {
                                if (menu.getTotalReviews() > 0) {
                                    total += menu.getAverageRating();
                                    count++;
                                }
                            }
                            if (count > 0) {
                                ratingText = String.format(Locale.getDefault(), "%.1f", total / count);
                            }
                        }
                        // Simpan ke cache
                        ratingCache.put(canteenId, ratingText);
                        holder.tvRatingWarung.setText(ratingText);
                    }

                    @Override
                    public void onFailure(retrofit2.Call<MenuListResponse> call, Throwable t) {
                        ratingCache.put(canteenId, "Baru");
                        holder.tvRatingWarung.setText("Baru");
                    }
                });
    }
}