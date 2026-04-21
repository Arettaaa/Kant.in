package com.example.kantin;

import android.content.Context;
import android.graphics.Color;
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
import com.example.kantin.model.response.MenuListResponse;

import java.text.NumberFormat;
import java.util.List;
import java.util.Locale;

public class AdminMenuAdapter extends RecyclerView.Adapter<AdminMenuAdapter.MenuViewHolder> {

    private Context context;
    private List<MenuListResponse.MenuItem> menuList;
    private OnMenuActionListener listener;

    private final String BASE_URL_STORAGE = "https://nonephemerally-nonrevolving-judie.ngrok-free.dev/storage/";

    // Interface khusus aksi Admin Kantin
    public interface OnMenuActionListener {
        void onEditClicked(MenuListResponse.MenuItem menu);
        void onStatusChanged(MenuListResponse.MenuItem menu, boolean isChecked);
    }

    public AdminMenuAdapter(Context context, List<MenuListResponse.MenuItem> menuList, OnMenuActionListener listener) {
        this.context = context;
        this.menuList = menuList;
        this.listener = listener;
    }

    @NonNull
    @Override
    public MenuViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        // Menggunakan layout milik admin
        View view = LayoutInflater.from(context).inflate(R.layout.activity_item_menu, parent, false);
        return new MenuViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull MenuViewHolder holder, int position) {
        MenuListResponse.MenuItem menu = menuList.get(position);

        holder.tvMenuName.setText(menu.getName() != null ? menu.getName() : "Tanpa Nama");

        // Format Harga ke Rupiah
        NumberFormat formatRupiah = NumberFormat.getCurrencyInstance(new Locale("id", "ID"));
        holder.tvMenuPrice.setText(formatRupiah.format(menu.getPriceAsDouble()).replace(",00", ""));

        // Logika Gambar sama seperti milik temanmu
        String menuImageUrl = menu.getImage();
        if (menuImageUrl != null && !menuImageUrl.isEmpty()) {
            if (!menuImageUrl.startsWith("http")) {
                menuImageUrl = BASE_URL_STORAGE + menuImageUrl;
            }
            Glide.with(context)
                    .load(menuImageUrl)
                    .placeholder(R.drawable.makanan)
                    .error(R.drawable.makanan)
                    .centerCrop()
                    .into(holder.ivMenuImage);
        } else {
            holder.ivMenuImage.setImageResource(R.drawable.makanan);
        }

        // Reset listener switch agar tidak bentrok saat di-scroll
        holder.switchAvailability.setOnCheckedChangeListener(null);
        holder.switchAvailability.setChecked(menu.isAvailable());

        // Atur label teks berdasarkan status awal
        if (menu.isAvailable()) {
            holder.tvStatusLabel.setText("TERSEDIA");
            holder.tvStatusLabel.setTextColor(Color.parseColor("#00C950")); // Hijau
        } else {
            holder.tvStatusLabel.setText("HABIS");
            holder.tvStatusLabel.setTextColor(Color.parseColor("#EF4444")); // Merah
        }

        // Aksi Klik Tombol Edit
        holder.btnEditMenu.setOnClickListener(v -> {
            if (listener != null) listener.onEditClicked(menu);
        });

        // Aksi Toggle Switch Availability
        holder.switchAvailability.setOnCheckedChangeListener((buttonView, isChecked) -> {
            if (listener != null) listener.onStatusChanged(menu, isChecked);

            // Ubah UI seketika
            if (isChecked) {
                holder.tvStatusLabel.setText("TERSEDIA");
                holder.tvStatusLabel.setTextColor(Color.parseColor("#00C950"));
            } else {
                holder.tvStatusLabel.setText("HABIS");
                holder.tvStatusLabel.setTextColor(Color.parseColor("#EF4444"));
            }
        });
    }

    @Override
    public int getItemCount() {
        return menuList != null ? menuList.size() : 0;
    }

    public void updateData(List<MenuListResponse.MenuItem> newMenuList) {
        this.menuList = newMenuList;
        notifyDataSetChanged();
    }

    public static class MenuViewHolder extends RecyclerView.ViewHolder {
        ImageView ivMenuImage, btnEditMenu;
        TextView tvMenuName, tvMenuPrice, tvStatusLabel;
        SwitchCompat switchAvailability;

        public MenuViewHolder(@NonNull View itemView) {
            super(itemView);
            // Binding ke ID yang ada di activity_item_menu.xml
            ivMenuImage = itemView.findViewById(R.id.ivMenuImage);
            tvMenuName = itemView.findViewById(R.id.tvMenuName);
            btnEditMenu = itemView.findViewById(R.id.btnEditMenu);
            tvMenuPrice = itemView.findViewById(R.id.tvMenuPrice);
            tvStatusLabel = itemView.findViewById(R.id.tvStatusLabel);
            switchAvailability = itemView.findViewById(R.id.switchAvailability);
        }
    }
}