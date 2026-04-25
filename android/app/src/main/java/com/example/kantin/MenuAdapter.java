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

import java.text.NumberFormat;
import java.util.List;
import java.util.Locale;

import com.example.kantin.model.request.AddToCartRequest;
import com.example.kantin.model.response.CartResponse;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;
import com.example.kantin.utils.SessionManager;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class MenuAdapter extends RecyclerView.Adapter<MenuAdapter.MenuViewHolder> {

    private Context context;
    private List<MenuListResponse.MenuItem> listMenu;
    private boolean isCanteenOpen; // ← tambah ini

    private final String BASE_URL_STORAGE = "https://nonephemerally-nonrevolving-judie.ngrok-free.dev/storage/";

    public MenuAdapter(Context context, List<MenuListResponse.MenuItem> listMenu, boolean canteenIsOpen) {
        this.context = context;
        this.listMenu = listMenu;
        this.isCanteenOpen = canteenIsOpen; // ← pastikan ini ada!
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
            String menuId = menu.getId(); // atau String.valueOf(menu.getId()) kalau tipenya int

            // Guard: jangan buka activity kalau ID tidak valid
            if (menuId == null || menuId.isEmpty() || menuId.equals("0")) {
                Toast.makeText(context, "ID menu tidak valid", Toast.LENGTH_SHORT).show();
                return;
            }

            Intent intent = new Intent(context, DetailMenuActivity.class);
            intent.putExtra("MENU_ID", menuId);
            context.startActivity(intent);
        });

        // Rating
        double rating = menu.getAverageRating();
        int totalReviews = menu.getTotalReviews();

        if (totalReviews > 0) {
            holder.tvRatingMenu.setText(String.format(Locale.getDefault(), "%.1f", rating));
        } else {
            holder.tvRatingMenu.setText("Baru");
        }

        // 5. Tombol Tambah (Add to Cart)
        holder.btnAddMenu.setOnClickListener(v -> {
            if (!isCanteenOpen) {
                Toast.makeText(context, "Kantin sedang tutup", Toast.LENGTH_SHORT).show();
                return;
            }
            // tambah ke keranjang
            String menuId = menu.getId();
            String token = new SessionManager(context).getToken();
            AddToCartRequest request = new AddToCartRequest(menuId, 1);

            ApiClient.getAuthClient(token).create(ApiService.class)
                    .addToCart(request)
                    .enqueue(new Callback<CartResponse>() {
                        @Override
                        public void onResponse(Call<CartResponse> call, Response<CartResponse> response) {
                            if (response.isSuccessful() && response.body() != null && response.body().isSuccess()) {
                                Toast.makeText(context, "1x " + menu.getName() + " berhasil ditambahkan!", Toast.LENGTH_SHORT).show();
                            } else {
                                Toast.makeText(context, "Gagal menambahkan ke keranjang", Toast.LENGTH_SHORT).show();
                            }
                        }

                        @Override
                        public void onFailure(Call<CartResponse> call, Throwable t) {
                            Toast.makeText(context, "Gagal terhubung ke server", Toast.LENGTH_SHORT).show();
                        }
                    });
        });

// tampilan tombol sesuai status kantin
        if (isCanteenOpen) {
            holder.btnAddMenu.setAlpha(1.0f);
            holder.btnAddMenu.setClickable(true);
            holder.btnAddMenu.setCardBackgroundColor(android.graphics.Color.parseColor("#FFF7ED"));
        } else {
            holder.btnAddMenu.setAlpha(0.4f);
            holder.btnAddMenu.setClickable(false);
            holder.btnAddMenu.setCardBackgroundColor(android.graphics.Color.parseColor("#E5E7EB"));
        }

    }

    @Override
    public int getItemCount() {
        return listMenu != null ? listMenu.size() : 0;
    }

    public void filterList(List<MenuListResponse.MenuItem> filteredList) {
        this.listMenu = filteredList;
        notifyDataSetChanged(); // Refresh tampilan adapter dengan data baru
    }

    public static class MenuViewHolder extends RecyclerView.ViewHolder {
        ImageView imgMenu;
        TextView tvNamaMenu, tvDeskripsiMenu, tvHargaMenu, tvRatingMenu;
        androidx.cardview.widget.CardView btnAddMenu;

        public MenuViewHolder(@NonNull View itemView) {
            super(itemView);
            imgMenu         = itemView.findViewById(R.id.imgMenu);
            tvNamaMenu      = itemView.findViewById(R.id.tvNamaMenu);
            tvDeskripsiMenu = itemView.findViewById(R.id.tvDeskripsiMenu);
            tvHargaMenu     = itemView.findViewById(R.id.tvHargaMenu);
            btnAddMenu      = itemView.findViewById(R.id.btnAddMenu);
            tvRatingMenu    = itemView.findViewById(R.id.tvRatingMenu);
        }
    }
    public void setCanteenOpen(boolean isOpen) {
        this.isCanteenOpen = isOpen;
        notifyDataSetChanged();
    }
}