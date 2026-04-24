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

public class MenuPopulerAdapter extends RecyclerView.Adapter<MenuPopulerAdapter.ViewHolder> {
    private Context context;
    private List<MenuListResponse.MenuItem> list;

    public MenuPopulerAdapter(Context context, List<MenuListResponse.MenuItem> list) {
        this.context = context;
        this.list = list;
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View v = LayoutInflater.from(context).inflate(R.layout.item_menu_populer, parent, false);
        return new ViewHolder(v);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        MenuListResponse.MenuItem item = list.get(position);

        // 1. Set Nama
        holder.tvNama.setText(item.getName());

        // 2. Set Harga (Format Rupiah)
        double harga = item.getPriceAsDouble();
        NumberFormat formatRupiah = NumberFormat.getCurrencyInstance(new Locale("id", "ID"));
        holder.tvHarga.setText(formatRupiah.format(harga).replace(",00", ""));

        // 3. Logika Gambar
        String url = item.getImage();
        if (url != null && !url.startsWith("http")) {
            url = "https://nonephemerally-nonrevolving-judie.ngrok-free.dev/storage/" + url;
        }

        Glide.with(context)
                .load(url)
                .centerCrop()
                .placeholder(R.drawable.makanan)
                .error(R.drawable.makanan)
                .into(holder.img);

        // 4. Logika Klik ke Detail Menu
        holder.itemView.setOnClickListener(v -> {
            Intent intent = new Intent(context, DetailMenuActivity.class);
            // Kirim ID menu agar halaman detail tahu menu mana yang dibuka
            intent.putExtra("MENU_ID", String.valueOf(item.getId()));
            context.startActivity(intent);
        });

        int totalReviews = item.getTotalReviews();
        if (totalReviews > 0) {
            holder.tvRating.setText(String.format(Locale.getDefault(), "%.1f", item.getAverageRating()));
        } else {
            holder.tvRating.setText("Baru");
        }
    }

    @Override
    public int getItemCount() {
        return list != null ? list.size() : 0;
    }

    public static class ViewHolder extends RecyclerView.ViewHolder {
        ImageView img;
        TextView tvNama, tvHarga, tvRating; // tambah tvRating

        public ViewHolder(View v) {
            super(v);
            img      = v.findViewById(R.id.img_menu_populer);
            tvNama   = v.findViewById(R.id.tv_nama_menu_populer);
            tvHarga  = v.findViewById(R.id.tv_harga_menu_populer);
            tvRating = v.findViewById(R.id.tv_rating_menu_populer); // tambah ini
        }
    }
}