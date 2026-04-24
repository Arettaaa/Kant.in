package com.example.kantin;

import android.app.Dialog;
import android.content.Intent;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.view.ViewGroup;
import android.view.Window;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.example.kantin.model.response.BaseResponse;
import com.example.kantin.model.response.OrderListResponse;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;
import com.example.kantin.utils.SessionManager;

import java.util.ArrayList;
import java.util.List;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class HistoryActivity extends AppCompatActivity {

    private ImageView btnBack;
    private LinearLayout tabSedangDiproses;
    private RecyclerView rvHistory;
    private HistoryAdapter adapter;
    private List<OrderListResponse.OrderItem> historyList = new ArrayList<>();

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_history);

        btnBack           = findViewById(R.id.btnBack);
        tabSedangDiproses = findViewById(R.id.tabSedangDiproses);
        rvHistory         = findViewById(R.id.rvHistory);

        adapter = new HistoryAdapter(this, historyList);
        rvHistory.setLayoutManager(new LinearLayoutManager(this));
        rvHistory.setAdapter(adapter);

        btnBack.setOnClickListener(v -> onBackPressed());

        tabSedangDiproses.setOnClickListener(v -> {
            startActivity(new Intent(HistoryActivity.this, ActiveOrdersActivity.class));
            overridePendingTransition(0, 0);
            finish();
        });

        loadHistory();
    }

    @Override
    protected void onResume() {
        super.onResume();
        loadHistory();
    }

    private void loadHistory() {
        String token = new SessionManager(this).getToken();
        ApiClient.getAuthClient(token).create(ApiService.class)
                .getOrderHistory()
                .enqueue(new Callback<OrderListResponse>() {
                    @Override
                    public void onResponse(Call<OrderListResponse> call,
                                           Response<OrderListResponse> response) {
                        if (response.isSuccessful() && response.body() != null) {
                            historyList.clear();
                            for (OrderListResponse.OrderItem order : response.body().getData()) {
                                String status = order.getStatus();
                                if ("completed".equals(status) || "cancelled".equals(status)) {
                                    historyList.add(order);
                                }
                            }
                            adapter.notifyDataSetChanged();
                        } else {
                            Toast.makeText(HistoryActivity.this,
                                    "Gagal memuat riwayat", Toast.LENGTH_SHORT).show();
                        }
                    }

                    @Override
                    public void onFailure(Call<OrderListResponse> call, Throwable t) {
                        Toast.makeText(HistoryActivity.this,
                                "Error jaringan", Toast.LENGTH_SHORT).show();
                    }
                });
    }

    // ── Dialog rating — dipanggil dari adapter ─────────────────

    public void showRatingDialog(String orderId, HistoryAdapter.ViewHolder holder) {
        Dialog dialog = new Dialog(this);
        dialog.requestWindowFeature(Window.FEATURE_NO_TITLE);
        dialog.setContentView(R.layout.dialog_rating);

        if (dialog.getWindow() != null) {
            dialog.getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));
            dialog.getWindow().setLayout(
                    ViewGroup.LayoutParams.MATCH_PARENT,
                    ViewGroup.LayoutParams.WRAP_CONTENT);
        }

        ImageView star1 = dialog.findViewById(R.id.star1);
        ImageView star2 = dialog.findViewById(R.id.star2);
        ImageView star3 = dialog.findViewById(R.id.star3);
        ImageView star4 = dialog.findViewById(R.id.star4);
        ImageView star5 = dialog.findViewById(R.id.star5);
        ImageView[] stars      = {star1, star2, star3, star4, star5};
        final int[] currentRating = {0};

        for (int i = 0; i < stars.length; i++) {
            final int ratingValue = i + 1;
            stars[i].setOnClickListener(v -> {
                currentRating[0] = ratingValue;
                for (int j = 0; j < stars.length; j++) {
                    stars[j].setImageResource(
                            j < currentRating[0] ? R.drawable.starfill : R.drawable.star);
                }
            });
        }

        TextView btnNantiSaja      = dialog.findViewById(R.id.btnNantiSaja);
        TextView btnKirimPenilaian = dialog.findViewById(R.id.btnKirimPenilaian);

        btnNantiSaja.setOnClickListener(v -> dialog.dismiss());

        btnKirimPenilaian.setOnClickListener(v -> {
            if (currentRating[0] == 0) {
                Toast.makeText(this, "Pilih bintang dulu ya!", Toast.LENGTH_SHORT).show();
                return;
            }
            kirimRating(orderId, currentRating[0], dialog, holder);
        });

        dialog.show();
    }

    // ── Kirim rating ke API ────────────────────────────────────

    private void kirimRating(String orderId, int rating, Dialog dialog,
                             HistoryAdapter.ViewHolder holder) {
        String token = new SessionManager(this).getToken();
        ApiClient.getAuthClient(token).create(ApiService.class)
                .submitRating(orderId, rating)
                .enqueue(new Callback<BaseResponse>() {
                    @Override
                    public void onResponse(Call<BaseResponse> call,
                                           Response<BaseResponse> response) {
                        dialog.dismiss();
                        if (response.isSuccessful()) {
                            Toast.makeText(HistoryActivity.this,
                                    "Terima kasih atas penilaiannya!", Toast.LENGTH_SHORT).show();
                            // Update tombol Nilai → Sudah Dinilai tanpa reload seluruh list
                            adapter.onRatingSubmitted(holder, rating); // pass rating yang dikirim                        } else {
                            Toast.makeText(HistoryActivity.this,
                                    "Gagal mengirim rating", Toast.LENGTH_SHORT).show();
                        }
                    }

                    @Override
                    public void onFailure(Call<BaseResponse> call, Throwable t) {
                        dialog.dismiss();
                        Toast.makeText(HistoryActivity.this,
                                "Error jaringan", Toast.LENGTH_SHORT).show();
                    }
                });
    }
}