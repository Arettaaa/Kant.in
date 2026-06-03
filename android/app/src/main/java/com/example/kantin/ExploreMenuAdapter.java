package com.example.kantin;

import android.content.Context;
import android.content.Intent;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;
import com.bumptech.glide.Glide;
import com.example.kantin.model.response.MenuListResponse;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;

import java.text.NumberFormat;
import java.util.ArrayList;
import java.util.List;
import java.util.Locale;

public class ExploreMenuAdapter extends RecyclerView.Adapter<ExploreMenuAdapter.ViewHolder> {
    private Context context;
    private List<MenuListResponse.MenuItem> listMenu;
    private List<MenuListResponse.MenuItem> listOriginal;
    private final String BASE_URL_STORAGE = "https://nonephemerally-nonrevolving-judie.ngrok-free.dev/storage/";

    private final java.util.Map<String, String> canteenNameCache = new java.util.HashMap<>();


    public ExploreMenuAdapter(Context context, List<MenuListResponse.MenuItem> listMenu) {
        this.context = context;
        this.listMenu = new ArrayList<>(listMenu);
        this.listOriginal = new ArrayList<>(listMenu);
    }

    public void filter(String query, String category) {
        List<MenuListResponse.MenuItem> filtered = new ArrayList<>();
        for (MenuListResponse.MenuItem menu : listOriginal) {
            boolean matchSearch = menu.getName().toLowerCase().contains(query.toLowerCase());
            boolean matchCategory = category.equals("Semua") || category.equalsIgnoreCase(menu.getCategory());
            if (matchSearch && matchCategory) {
                filtered.add(menu);
            }
        }
        filtered.sort((a, b) -> {
            if (a.isAvailable() != b.isAvailable()) return a.isAvailable() ? -1 : 1;
            return Double.compare(b.getAverageRating(), a.getAverageRating());
        });
        listMenu = filtered;
        notifyDataSetChanged();
    }
    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(context).inflate(R.layout.item_explore_menu, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        MenuListResponse.MenuItem menu = listMenu.get(position);

        holder.tvNamaMenu.setText(menu.getName());

        double harga = menu.getPriceAsDouble();
        NumberFormat formatRupiah = NumberFormat.getCurrencyInstance(new Locale("id", "ID"));
        holder.tvHarga.setText(formatRupiah.format(harga).replace(",00", ""));

        String imageUrl = menu.getImage();
        if (imageUrl != null && !imageUrl.startsWith("http")) {
            imageUrl = BASE_URL_STORAGE + imageUrl;
        }
        Glide.with(context).load(imageUrl).placeholder(R.drawable.makanan).into(holder.ivMenu);

        String canteenId = menu.getCanteenId();
        if (canteenId != null && canteenNameCache.containsKey(canteenId)) {
            holder.tvNamaKantin.setText(canteenNameCache.get(canteenId));
        } else {
            holder.tvNamaKantin.setText("Memuat...");
        }

        // Klik item
        if (menu.isAvailable()) {
            holder.itemView.setOnClickListener(v -> {
                String menuId = menu.getId();
                if (menuId == null || menuId.isEmpty()) {
                    Toast.makeText(context, "ID menu tidak valid", Toast.LENGTH_SHORT).show();
                    return;
                }
                Intent intent = new Intent(context, DetailMenuActivity.class);
                intent.putExtra("MENU_ID", menuId);
                context.startActivity(intent);
            });
        } else {
            holder.itemView.setOnClickListener(null);
        }

        // Rating
        double rating = menu.getAverageRating();
        int totalReviews = menu.getTotalReviews();
        if (totalReviews > 0) {
            holder.tvRatingMenu.setText(String.format(Locale.getDefault(), "%.1f", rating));
        } else {
            holder.tvRatingMenu.setText("Baru");
        }

        if (!menu.isAvailable()) {
            holder.itemView.setAlpha(0.5f);
            holder.tvStatusExplore.setVisibility(View.VISIBLE);
            holder.itemView.setBackgroundTintList(
                    android.content.res.ColorStateList.valueOf(
                            android.graphics.Color.parseColor("#E5E7EB")
                    )
            );
        } else {
            holder.itemView.setAlpha(1.0f);
            holder.tvStatusExplore.setVisibility(View.GONE);
            holder.itemView.setBackgroundTintList(
                    android.content.res.ColorStateList.valueOf(
                            android.graphics.Color.parseColor("#FDFDFD")
                    )
            );
        }
    }
    @Override
    public int getItemCount() { return listMenu != null ? listMenu.size() : 0; }

    public static class ViewHolder extends RecyclerView.ViewHolder {
        ImageView ivMenu;
        TextView tvNamaMenu, tvNamaKantin, tvHarga, tvRatingMenu;
        TextView tvStatusExplore;

        public ViewHolder(@NonNull View itemView) {
            super(itemView);
            ivMenu          = itemView.findViewById(R.id.imgMenuExplore);
            tvNamaMenu      = itemView.findViewById(R.id.tvNamaMenuExplore);
            tvNamaKantin    = itemView.findViewById(R.id.tvNamaKantinExplore);
            tvHarga         = itemView.findViewById(R.id.tvHargaExplore);
            tvRatingMenu    = itemView.findViewById(R.id.tvRatingExplore);
            tvStatusExplore = itemView.findViewById(R.id.tvStatusExplore);
        }
    }

    public void updateData(List<MenuListResponse.MenuItem> newData) {
        List<MenuListResponse.MenuItem> sorted = new ArrayList<>(newData);
        sorted.sort((a, b) -> {
            if (a.isAvailable() != b.isAvailable()) {
                return a.isAvailable() ? -1 : 1;
            }
            return Double.compare(b.getAverageRating(), a.getAverageRating());
        });

        this.listOriginal = sorted;
        this.listMenu = new ArrayList<>(sorted);
        for (MenuListResponse.MenuItem menu : newData) {
            String canteenId = menu.getCanteenId();
            if (canteenId != null && !canteenNameCache.containsKey(canteenId)) {
                canteenNameCache.put(canteenId, "Memuat..."); // placeholder dulu
                ApiClient.getClient().create(ApiService.class)
                        .getCanteenDetail(canteenId)
                        .enqueue(new retrofit2.Callback<com.example.kantin.model.response.CanteenDetailResponse>() {
                            @Override
                            public void onResponse(retrofit2.Call<com.example.kantin.model.response.CanteenDetailResponse> call,
                                                   retrofit2.Response<com.example.kantin.model.response.CanteenDetailResponse> response) {
                                if (response.isSuccessful() && response.body() != null) {
                                    canteenNameCache.put(canteenId, response.body().getData().getName());
                                    notifyDataSetChanged(); // update nama kantin di UI
                                }
                            }
                            @Override
                            public void onFailure(retrofit2.Call<com.example.kantin.model.response.CanteenDetailResponse> call, Throwable t) {
                                canteenNameCache.put(canteenId, "Kantin");
                            }
                        });
            }
        }
        notifyDataSetChanged();
    }
}