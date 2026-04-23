package com.example.kantin;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.CheckBox;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.bumptech.glide.Glide;
import com.example.kantin.model.request.UpdateCartRequest;
import com.example.kantin.model.response.CartResponse;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;
import com.example.kantin.utils.SessionManager;

import java.text.NumberFormat;
import java.util.ArrayList;
import java.util.List;
import java.util.Locale;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

import android.widget.EditText;
public class CartAdapter extends RecyclerView.Adapter<CartAdapter.CartViewHolder> {

    public interface OnCartChangedListener {
        void onCartChanged();
        void onSelectionChanged();
    }

    private final Context context;
    private final List<CartResponse.CartItem> items;
    private final List<Boolean> selectedStates;
    private final OnCartChangedListener listener;
    private final String token;
    private final String BASE_URL_STORAGE = "https://nonephemerally-nonrevolving-judie.ngrok-free.dev/storage/";

    public boolean isItemSelected(int position) {
        return selectedStates.get(position);
    }

    public CartAdapter(Context context, List<CartResponse.CartItem> items, OnCartChangedListener listener) {
        this.context = context;
        this.items = items;
        this.listener = listener;
        this.token = new SessionManager(context).getToken();

        this.selectedStates = new ArrayList<>();
        for (int i = 0; i < items.size(); i++) {
            selectedStates.add(true);
        }
    }

    @NonNull
    @Override
    public CartViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(context).inflate(R.layout.item_cart, parent, false);
        return new CartViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull CartViewHolder holder, int position) {
        CartResponse.CartItem item = items.get(position);

        holder.tvNama.setText(item.getName());
        holder.tvHarga.setText(formatRupiah(item.getPrice()));
        holder.tvQty.setText(String.valueOf(item.getQuantity()));

        holder.etNoteItem.setTag(position);
        holder.etNoteItem.removeTextChangedListener((android.text.TextWatcher) holder.etNoteItem.getTag(R.id.tag_watcher));
        holder.etNoteItem.setText(item.getNotes() != null ? item.getNotes() : "");

// ← TAMBAH WATCHER INI
        android.text.TextWatcher watcher = new android.text.TextWatcher() {
            @Override public void beforeTextChanged(CharSequence s, int start, int count, int after) {}
            @Override public void onTextChanged(CharSequence s, int start, int before, int count) {}
            @Override
            public void afterTextChanged(android.text.Editable s) {
                int pos = (int) holder.etNoteItem.getTag();
                items.get(pos).setNotes(s.toString());
            }
        };
        holder.etNoteItem.setTag(R.id.tag_watcher, watcher);
        holder.etNoteItem.addTextChangedListener(watcher);

        // ==========================================
        // LOGIKA GROUPING KANTIN ALA SHOPEE
        // ==========================================
        boolean isFirstOfKantin = false;

        // Cek apakah ini item pertama dari suatu kantin
        if (position == 0) {
            isFirstOfKantin = true;
        } else {
            CartResponse.CartItem prevItem = items.get(position - 1);
            if (!item.getCanteenId().equals(prevItem.getCanteenId())) {
                isFirstOfKantin = true;
            }
        }

        if (isFirstOfKantin) {
            holder.layoutHeaderKantin.setVisibility(View.VISIBLE);
            holder.tvNamaKantin.setText(item.getCanteenName());

            // Set status checkbox header kantin
            boolean isAllKantinSelected = isAllItemsInKantinSelected(item.getCanteenId());
            holder.cbKantin.setOnCheckedChangeListener(null);
            holder.cbKantin.setChecked(isAllKantinSelected);

            // Aksi kalau checkbox header kantin diklik
            holder.cbKantin.setOnCheckedChangeListener((buttonView, isChecked) -> {
                selectAllItemsInKantin(item.getCanteenId(), isChecked);
            });
        } else {
            // Sembunyikan header jika bukan item pertama di kantin tersebut
            holder.layoutHeaderKantin.setVisibility(View.GONE);
        }
        // ==========================================

        // Set state checkbox item
        holder.cbSelectItem.setOnCheckedChangeListener(null);
        holder.cbSelectItem.setChecked(selectedStates.get(position));
        holder.cbSelectItem.setOnCheckedChangeListener((buttonView, isChecked) -> {
            selectedStates.set(position, isChecked);
            // Paksa adapter refresh untuk mengupdate status centang Header Kantin
            notifyItemRangeChanged(0, items.size());
            listener.onSelectionChanged();
        });

        // Gambar
        String imageUrl = item.getImage();
        if (imageUrl != null && !imageUrl.startsWith("http")) {
            imageUrl = BASE_URL_STORAGE + imageUrl;
        }
        Glide.with(context).load(imageUrl).placeholder(R.drawable.makanan).error(R.drawable.makanan).centerCrop().into(holder.imgMenu);

        holder.btnPlus.setOnClickListener(v -> updateItem(item.getMenuId(), item.getQuantity() + 1));
        holder.btnMinus.setOnClickListener(v -> {
            if (item.getQuantity() <= 1) {
                removeItem(item.getMenuId());
            } else {
                updateItem(item.getMenuId(), item.getQuantity() - 1);
            }
        });
    }

    // --- HELPER UNTUK CHECKBOX KANTIN ---
    private boolean isAllItemsInKantinSelected(String canteenId) {
        for (int i = 0; i < items.size(); i++) {
            if (items.get(i).getCanteenId().equals(canteenId)) {
                if (!selectedStates.get(i)) {
                    return false; // Ada 1 item yang gak kecentang, kembalikan false
                }
            }
        }
        return true;
    }

    private void selectAllItemsInKantin(String canteenId, boolean isChecked) {
        for (int i = 0; i < items.size(); i++) {
            if (items.get(i).getCanteenId().equals(canteenId)) {
                selectedStates.set(i, isChecked);
            }
        }
        notifyDataSetChanged(); // Refresh UI
        listener.onSelectionChanged(); // Hitung ulang total bayar
    }
    // -------------------------------------

    public double getSelectedSubtotal() {
        double total = 0;
        for (int i = 0; i < items.size(); i++) {
            if (selectedStates.get(i)) {
                total += items.get(i).getSubtotal();
            }
        }
        return total;
    }

    public boolean hasSelectedItem() {
        for (Boolean selected : selectedStates) {
            if (selected) return true;
        }
        return false;
    }

    public void setSelectAll(boolean selectAll) {
        for (int i = 0; i < selectedStates.size(); i++) {
            selectedStates.set(i, selectAll);
        }
        notifyDataSetChanged();
    }

    public boolean isAllSelected() {
        for (Boolean selected : selectedStates) {
            if (!selected) return false;
        }
        return true;
    }

    private void updateItem(String menuId, int newQty) {
        ApiClient.getAuthClient(token).create(ApiService.class)
                .updateCartItem(menuId, new UpdateCartRequest(newQty))
                .enqueue(new Callback<CartResponse>() {
                    @Override
                    public void onResponse(Call<CartResponse> call, Response<CartResponse> response) {
                        if (response.isSuccessful()) listener.onCartChanged();
                    }
                    @Override
                    public void onFailure(Call<CartResponse> call, Throwable t) {
                        Toast.makeText(context, "Gagal update item", Toast.LENGTH_SHORT).show();
                    }
                });
    }

    private void removeItem(String menuId) {
        ApiClient.getAuthClient(token).create(ApiService.class)
                .removeCartItem(menuId)
                .enqueue(new Callback<CartResponse>() {
                    @Override
                    public void onResponse(Call<CartResponse> call, Response<CartResponse> response) {
                        if (response.isSuccessful()) listener.onCartChanged();
                    }
                    @Override
                    public void onFailure(Call<CartResponse> call, Throwable t) {
                        Toast.makeText(context, "Gagal hapus item", Toast.LENGTH_SHORT).show();
                    }
                });
    }

    @Override
    public int getItemCount() { return items != null ? items.size() : 0; }

    private String formatRupiah(double harga) {
        NumberFormat fmt = NumberFormat.getCurrencyInstance(new Locale("id", "ID"));
        return fmt.format(harga).replace(",00", "");
    }

    static class CartViewHolder extends RecyclerView.ViewHolder {
        CheckBox cbSelectItem, cbKantin;
        ImageView imgMenu;
        TextView tvNama, tvHarga, tvQty, btnPlus, btnMinus, tvNamaKantin;
        LinearLayout layoutHeaderKantin;
        EditText etNoteItem; // ← TAMBAH INI

        CartViewHolder(@NonNull View itemView) {
            super(itemView);
            layoutHeaderKantin = itemView.findViewById(R.id.layoutHeaderKantin);
            cbKantin           = itemView.findViewById(R.id.cbKantin);
            tvNamaKantin       = itemView.findViewById(R.id.tvNamaKantin);
            cbSelectItem       = itemView.findViewById(R.id.cbSelectItem);
            imgMenu            = itemView.findViewById(R.id.imgMenu);
            tvNama             = itemView.findViewById(R.id.tvNamaMenu);
            tvHarga            = itemView.findViewById(R.id.tvHargaMenu);
            tvQty              = itemView.findViewById(R.id.tvQty);
            btnPlus            = itemView.findViewById(R.id.btnPlus);
            btnMinus           = itemView.findViewById(R.id.btnMinus);
            etNoteItem         = itemView.findViewById(R.id.etNoteItem); // ← TAMBAH INI
        }
    }
}