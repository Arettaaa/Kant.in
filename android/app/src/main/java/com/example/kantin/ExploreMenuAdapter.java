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
import java.util.ArrayList;
import java.util.List;
import java.util.Locale;

public class ExploreMenuAdapter extends RecyclerView.Adapter<ExploreMenuAdapter.ViewHolder> {
    private Context context;
    private List<MenuListResponse.MenuItem> listMenu;
    private List<MenuListResponse.MenuItem> listOriginal;

    public ExploreMenuAdapter(Context context, List<MenuListResponse.MenuItem> listMenu) {
        this.context = context;
        this.listMenu = new ArrayList<>(listMenu);
        this.listOriginal = new ArrayList<>(listMenu);
    }

    public void filter(String query, String category) {
        listMenu.clear();
        for (MenuListResponse.MenuItem menu : listOriginal) {
            boolean matchSearch = menu.getName().toLowerCase().contains(query.toLowerCase());
            boolean matchCategory = category.equals("Semua") || category.equalsIgnoreCase(menu.getCategory());
            if (matchSearch && matchCategory) {
                listMenu.add(menu);
            }
        }
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
        holder.tvNamaKantin.setText("Kantin Terdekat");

        double harga = menu.getPriceAsDouble();
        NumberFormat formatRupiah = NumberFormat.getCurrencyInstance(new Locale("id", "ID"));
        holder.tvHarga.setText(formatRupiah.format(harga).replace(",00", ""));

        Glide.with(context)
                .load(menu.getImage())
                .placeholder(R.drawable.makanan)
                .into(holder.ivMenu);

        holder.itemView.setOnClickListener(v -> {
            Intent intent = new Intent(context, DetailMenuActivity.class);
            intent.putExtra("MENU_ID", menu.getId());
            context.startActivity(intent);
        });
    }

    @Override
    public int getItemCount() { return listMenu != null ? listMenu.size() : 0; }

    public static class ViewHolder extends RecyclerView.ViewHolder {
        ImageView ivMenu;
        TextView tvNamaMenu, tvNamaKantin, tvHarga;

        public ViewHolder(@NonNull View itemView) {
            super(itemView);
            ivMenu = itemView.findViewById(R.id.imgMenuExplore);
            tvNamaMenu = itemView.findViewById(R.id.tvNamaMenuExplore);
            tvNamaKantin = itemView.findViewById(R.id.tvNamaKantinExplore);
            tvHarga = itemView.findViewById(R.id.tvHargaExplore);
        }
    }
}