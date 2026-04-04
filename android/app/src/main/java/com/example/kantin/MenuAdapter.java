package com.example.kantin;

import android.content.Context;
import android.content.Intent;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.bumptech.glide.Glide;
import com.example.kantin.model.response.MenuListResponse;

import java.text.NumberFormat;
import java.util.List;
import java.util.Locale;

public class MenuAdapter extends RecyclerView.Adapter<MenuAdapter.MenuViewHolder> {

    private Context context;
    private List<MenuListResponse.MenuItem> listMenu;
    private final String BASE_URL_STORAGE = "https://nonephemerally-nonrevolving-judie.ngrok-free.dev/storage/";

    public MenuAdapter(Context context, List<MenuListResponse.MenuItem> listMenu) {
        this.context = context;
        this.listMenu = listMenu;
    }

    @NonNull
    @Override
    public MenuViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        // Menggunakan layout item_menu_detail (yang bentuknya horizontal list)
        View view = LayoutInflater.from(context).inflate(R.layout.item_menu_detail, parent, false);
        return new MenuViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull MenuViewHolder holder, int position) {
        MenuListResponse.MenuItem menu = listMenu.get(position);

        // 1. Set Nama dan Deskripsi
        holder.tvNamaMenu.setText(menu.getName());
        holder.tvDeskripsiMenu.setText(menu.getDescription());

        // 2. Format Harga ke Rupiah
        double harga = menu.getPriceAsDouble();
        NumberFormat formatRupiah = NumberFormat.getCurrencyInstance(new Locale("id", "ID"));
        holder.tvHargaMenu.setText(formatRupiah.format(harga).replace(",00", ""));

        // 3. Logika Gambar (Handle URL dari Laravel)
        String menuImageUrl = menu.getImage();
        if (menuImageUrl != null && !menuImageUrl.startsWith("http")) {
            menuImageUrl = BASE_URL_STORAGE + menuImageUrl;
        }

        Glide.with(context)
                .load(menuImageUrl)
                .placeholder(R.drawable.makanan)
                .error(R.drawable.makanan)
                .centerCrop()
                .into(holder.imgMenu);

        // 4. Klik Item untuk ke Detail Menu
        holder.itemView.setOnClickListener(v -> {
            Intent intent = new Intent(context, DetailMenuActivity.class);
            intent.putExtra("MENU_ID", String.valueOf(menu.getId()));
            context.startActivity(intent);
        });

        // 5. Tombol Tambah (Add to Cart)
        holder.btnAddMenu.setOnClickListener(v -> {
            // Logika Keranjang (Akan diimplementasikan nanti)
        });
    }

    @Override
    public int getItemCount() {
        return listMenu != null ? listMenu.size() : 0;
    }

    public static class MenuViewHolder extends RecyclerView.ViewHolder {
        ImageView imgMenu;
        TextView tvNamaMenu, tvDeskripsiMenu, tvHargaMenu;
        View btnAddMenu;

        public MenuViewHolder(@NonNull View itemView) {
            super(itemView);
            imgMenu = itemView.findViewById(R.id.imgMenu);
            tvNamaMenu = itemView.findViewById(R.id.tvNamaMenu);
            tvDeskripsiMenu = itemView.findViewById(R.id.tvDeskripsiMenu);
            tvHargaMenu = itemView.findViewById(R.id.tvHargaMenu);
            btnAddMenu = itemView.findViewById(R.id.btnAddMenu);
        }
    }
}