package com.example.kantin;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.LinearLayout;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.example.kantin.R;
import com.example.kantin.model.OrderItem; // Pastikan import OrderItem benar

import java.text.NumberFormat;
import java.util.List;
import java.util.Locale;

public class MenuPesananAdapter extends RecyclerView.Adapter<MenuPesananAdapter.ViewHolder> {

    private List<OrderItem> listMenu;

    public MenuPesananAdapter(List<OrderItem> listMenu) {
        this.listMenu = listMenu;
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        // Menghubungkan adapter dengan layout item_menu_pesanan.xml
        View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.item_menu_pesanan, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        OrderItem item = listMenu.get(position);

        // 1. Set Kuantitas (misal: "2x")
        holder.tvQty.setText(item.getQuantity() + "x");

        // 2. Set Nama Menu
        holder.tvMenuName.setText(item.getName());

        // 3. Set Harga Subtotal per Item (Format Rupiah)
        NumberFormat formatRupiah = NumberFormat.getCurrencyInstance(new Locale("in", "ID"));
        holder.tvPrice.setText(formatRupiah.format(item.getSubtotal()));

        // 4. Set Varian/Deskripsi Singkat
        // Karena di JSON Laravel (OrderController) tidak ada field varian khusus,
        // kita kosongkan saja atau sembunyikan jika tidak diperlukan.
        holder.tvVariants.setVisibility(View.GONE);

        // 5. Set Catatan Pelanggan
        if (item.getNotes() != null && !item.getNotes().trim().isEmpty()) {
            holder.layCatatan.setVisibility(View.VISIBLE);
            holder.tvNotes.setText("\"" + item.getNotes() + "\"");
        } else {
            // Sembunyikan kotak kuning jika tidak ada catatan
            holder.layCatatan.setVisibility(View.GONE);
        }
    }

    @Override
    public int getItemCount() {
        return listMenu != null ? listMenu.size() : 0;
    }

    // Kelas ViewHolder untuk mendefinisikan elemen-elemen di dalam item_menu_pesanan.xml
    public static class ViewHolder extends RecyclerView.ViewHolder {
        TextView tvQty, tvMenuName, tvPrice, tvVariants, tvNotes;
        LinearLayout layCatatan;

        public ViewHolder(@NonNull View itemView) {
            super(itemView);

            // Inisialisasi ID sesuai dengan yang ada di item_menu_pesanan.xml
            tvQty = itemView.findViewById(R.id.tvQty);
            tvMenuName = itemView.findViewById(R.id.tvMenuName);
            tvPrice = itemView.findViewById(R.id.tvPrice);
            tvVariants = itemView.findViewById(R.id.tvVariants);
            tvNotes = itemView.findViewById(R.id.tvNotes);
            layCatatan = itemView.findViewById(R.id.layCatatan);
        }
    }
}