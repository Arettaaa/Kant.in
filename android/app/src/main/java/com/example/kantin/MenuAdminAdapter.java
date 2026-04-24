package com.example.kantin;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.appcompat.widget.SwitchCompat;
import androidx.recyclerview.widget.RecyclerView;

import com.bumptech.glide.Glide;
import com.example.kantin.R;
import com.example.kantin.model.response.MenuListResponse.MenuItem;

import java.text.NumberFormat;
import java.util.List;
import java.util.Locale;

public class MenuAdminAdapter extends RecyclerView.Adapter<MenuAdminAdapter.MenuViewHolder> {

    public interface OnMenuActionListener {
        void onEditClick(MenuItem menu);
        void onToggleAvailability(MenuItem menu, boolean isAvailable);
    }

    private final Context context;
    private final List<MenuItem> menuList;
    private final OnMenuActionListener listener;

    public MenuAdminAdapter(Context context, List<MenuItem> menuList, OnMenuActionListener listener) {
        this.context  = context;
        this.menuList = menuList;
        this.listener = listener;
    }

    @NonNull
    @Override
    public MenuViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(context).inflate(R.layout.activity_item_menu, parent, false);
        return new MenuViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull MenuViewHolder holder, int position) {
        MenuItem menu = menuList.get(position);

        holder.tvMenuName.setText(menu.getName());
        holder.tvMenuPrice.setText(formatRupiah(menu.getPriceAsDouble()));

        // Rating
        int totalReviews = menu.getTotalReviews();
        if (totalReviews > 0) {
            holder.tvRatingMenu.setText(String.format(Locale.getDefault(), "%.1f", menu.getAverageRating()));
            holder.tvTotalUlasan.setText("(" + totalReviews + " ulasan)");
        } else {
            holder.tvRatingMenu.setText("Baru");
            holder.tvTotalUlasan.setText("(Belum ada ulasan)");
        }

        // Status label & switch
        boolean available = menu.isAvailable();
        holder.switchAvailability.setOnCheckedChangeListener(null);
        holder.switchAvailability.setChecked(available);
        updateStatusLabel(holder.tvStatusLabel, available);

        holder.switchAvailability.setOnCheckedChangeListener((btn, isChecked) -> {
            updateStatusLabel(holder.tvStatusLabel, isChecked);
            listener.onToggleAvailability(menu, isChecked);
        });

        if (menu.getImage() != null && !menu.getImage().isEmpty()) {
            Glide.with(context)
                    .load(menu.getImage())
                    .placeholder(R.drawable.makanan)
                    .error(R.drawable.makanan)
                    .centerCrop()
                    .into(holder.ivMenuImage);
        } else {
            holder.ivMenuImage.setImageResource(R.drawable.makanan);
        }

        holder.btnEditMenu.setOnClickListener(v -> listener.onEditClick(menu));
    }

    @Override
    public int getItemCount() { return menuList.size(); }

    private void updateStatusLabel(TextView label, boolean available) {
        if (available) {
            label.setText("TERSEDIA");
            label.setTextColor(0xFF10B981); // hijau
        } else {
            label.setText("HABIS");
            label.setTextColor(0xFFEF4444); // merah
        }
    }

    private String formatRupiah(double amount) {
        NumberFormat format = NumberFormat.getCurrencyInstance(new Locale("id", "ID"));
        return format.format(amount).replace(",00", "");
    }

    static class MenuViewHolder extends RecyclerView.ViewHolder {
        ImageView ivMenuImage, btnEditMenu;
        TextView tvMenuName, tvMenuPrice, tvStatusLabel, tvRatingMenu, tvTotalUlasan; // tambah dua ini
        android.widget.CheckBox switchAvailability;

        MenuViewHolder(@NonNull View itemView) {
            super(itemView);
            ivMenuImage        = itemView.findViewById(R.id.ivMenuImage);
            btnEditMenu        = itemView.findViewById(R.id.btnEditMenu);
            tvMenuName         = itemView.findViewById(R.id.tvMenuName);
            tvMenuPrice        = itemView.findViewById(R.id.tvMenuPrice);
            tvStatusLabel      = itemView.findViewById(R.id.tvStatusLabel);
            switchAvailability = itemView.findViewById(R.id.switchAvailability);
            tvRatingMenu       = itemView.findViewById(R.id.tvRatingMenu);
            tvTotalUlasan      = itemView.findViewById(R.id.tvTotalUlasan);
        }
    }
}