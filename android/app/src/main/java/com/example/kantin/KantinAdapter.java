package com.example.kantin;

import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.cardview.widget.CardView;
import androidx.recyclerview.widget.RecyclerView;

import com.bumptech.glide.Glide;
import com.example.kantin.model.response.CanteenListResponse;

import java.util.List;

public class KantinAdapter extends RecyclerView.Adapter<KantinAdapter.KantinViewHolder> {

    private Context context;
    private List<CanteenListResponse.CanteenData> listKantin;

    public KantinAdapter(Context context, List<CanteenListResponse.CanteenData> listKantin) {
        this.context = context;
        this.listKantin = listKantin;
    }

    @NonNull
    @Override
    public KantinViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(context).inflate(R.layout.item_kantin, parent, false);
        return new KantinViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull KantinViewHolder holder, int position) {
        CanteenListResponse.CanteenData kantin = listKantin.get(position);

        // 1. Set Nama Kantin
        holder.tvNamaKantin.setText(kantin.getName());

        // 2. Set Jam Operasional
        if (kantin.getOperatingHours() != null) {
            String open = kantin.getOperatingHours().getOpen();
            String close = kantin.getOperatingHours().getClose();
            holder.tvJamOperasional.setText(open + " - " + close);
        } else {
            holder.tvJamOperasional.setText("-");
        }

        // 3. Set Status (Buka/Tutup)
        if (kantin.isOpen()) {
            holder.tvStatusKantin.setText("Buka");
            holder.tvStatusKantin.setTextColor(Color.parseColor("#10B981"));
            holder.cvStatusKantin.setCardBackgroundColor(Color.parseColor("#D1FAE5"));
            holder.ivStatusIcon.setColorFilter(Color.parseColor("#10B981"));
        } else {
            holder.tvStatusKantin.setText("Tutup");
            holder.tvStatusKantin.setTextColor(Color.parseColor("#EF4444"));
            holder.cvStatusKantin.setCardBackgroundColor(Color.parseColor("#FEE2E2"));
            holder.ivStatusIcon.setColorFilter(Color.parseColor("#EF4444"));
        }

        // 4. Set Gambar (Perbaikan link dobel)
        String imageUrl = kantin.getImage();
        // Cek jika imageUrl tidak null dan tidak mengandung link lengkap, tambahkan baseUrl
        if (imageUrl != null && !imageUrl.startsWith("http")) {
            imageUrl = "https://nonephemerally-nonrevolving-judie.ngrok-free.dev/storage/" + imageUrl;
        }

        Glide.with(context)
                .load(imageUrl)
                .placeholder(android.R.color.darker_gray)
                .error(android.R.color.darker_gray)
                .centerCrop()
                .into(holder.ivKantin);

        // 5. Klik ke Detail Kantin
        holder.itemView.setOnClickListener(v -> {
            Intent intent = new Intent(context, DetailKantinActivity.class);
            intent.putExtra("CANTEEN_ID", kantin.getId());
            context.startActivity(intent);
        });
    }

    @Override
    public int getItemCount() {
        return listKantin != null ? listKantin.size() : 0;
    }

    public static class KantinViewHolder extends RecyclerView.ViewHolder {
        ImageView ivKantin, ivStatusIcon;
        TextView tvNamaKantin, tvStatusKantin, tvJamOperasional;
        CardView cvStatusKantin;

        public KantinViewHolder(@NonNull View itemView) {
            super(itemView);
            ivKantin = itemView.findViewById(R.id.iv_kantin);
            tvNamaKantin = itemView.findViewById(R.id.tv_nama_kantin);
            tvStatusKantin = itemView.findViewById(R.id.tv_status_kantin);
            tvJamOperasional = itemView.findViewById(R.id.tv_jam_operasional);
            cvStatusKantin = itemView.findViewById(R.id.cv_status_kantin);
            ivStatusIcon = itemView.findViewById(R.id.iv_status_icon);
        }
    }
}