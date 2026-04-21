package com.example.kantin;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.example.kantin.R;
import com.example.kantin.model.OrderItem;

import java.util.List;

public class CancelOrderMenuAdapter extends RecyclerView.Adapter<CancelOrderMenuAdapter.ViewHolder> {

    private List<OrderItem> items;

    public CancelOrderMenuAdapter(List<OrderItem> items) {
        this.items = items;
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.item_order_menu, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        OrderItem item = items.get(position);

        holder.tvQuantity.setText(String.valueOf(item.getQuantity()));
        holder.tvMenuName.setText(item.getName());

        if (item.getNotes() != null && !item.getNotes().isEmpty()) {
            holder.tvNotes.setVisibility(View.VISIBLE);
            holder.tvNotes.setText("Catatan: " + item.getNotes());
        } else {
            holder.tvNotes.setVisibility(View.GONE);
        }
    }

    @Override
    public int getItemCount() {
        return items != null ? items.size() : 0;
    }

    public static class ViewHolder extends RecyclerView.ViewHolder {
        TextView tvQuantity, tvMenuName, tvNotes;

        public ViewHolder(@NonNull View itemView) {
            super(itemView);
            // Sesuaikan ID ini dengan yang ada di item_order_menu.xml kamu
            tvQuantity = itemView.findViewById(R.id.tv_quantity);
            tvMenuName = itemView.findViewById(R.id.tv_menu_name);
            tvNotes = itemView.findViewById(R.id.tv_notes);
        }
    }
}
