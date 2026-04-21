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
    private int currentRating = 0;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_history);

        btnBack = findViewById(R.id.btnBack);
        tabSedangDiproses = findViewById(R.id.tabSedangDiproses);
        rvHistory = findViewById(R.id.rvHistory);

        // Setup RecyclerView
        adapter = new HistoryAdapter(this, historyList);
        rvHistory.setLayoutManager(new LinearLayoutManager(this));
        rvHistory.setAdapter(adapter);

        btnBack.setOnClickListener(v -> onBackPressed());

        tabSedangDiproses.setOnClickListener(v -> {
            Intent intent = new Intent(HistoryActivity.this, ActiveOrdersActivity.class);
            startActivity(intent);
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
                    public void onResponse(Call<OrderListResponse> call, Response<OrderListResponse> response) {
                        if (response.isSuccessful() && response.body() != null) {
                            List<OrderListResponse.OrderItem> allOrders = response.body().getData();
                            historyList.clear();

                            // Filter hanya completed dan cancelled
                            for (OrderListResponse.OrderItem order : allOrders) {
                                String status = order.getStatus();
                                if ("completed".equals(status) || "cancelled".equals(status)) {
                                    historyList.add(order);
                                }
                            }
                            adapter.notifyDataSetChanged();
                        } else {
                            Toast.makeText(HistoryActivity.this, "Gagal memuat riwayat", Toast.LENGTH_SHORT).show();
                        }
                    }

                    @Override
                    public void onFailure(Call<OrderListResponse> call, Throwable t) {
                        Toast.makeText(HistoryActivity.this, "Error jaringan", Toast.LENGTH_SHORT).show();
                    }
                });
    }

    // Public agar bisa dipanggil dari adapter
    public void showRatingDialog() {
        Dialog dialog = new Dialog(this);
        dialog.requestWindowFeature(Window.FEATURE_NO_TITLE);
        dialog.setContentView(R.layout.dialog_rating);

        if (dialog.getWindow() != null) {
            dialog.getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));
            dialog.getWindow().setLayout(ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.WRAP_CONTENT);
        }

        ImageView star1 = dialog.findViewById(R.id.star1);
        ImageView star2 = dialog.findViewById(R.id.star2);
        ImageView star3 = dialog.findViewById(R.id.star3);
        ImageView star4 = dialog.findViewById(R.id.star4);
        ImageView star5 = dialog.findViewById(R.id.star5);

        ImageView[] stars = {star1, star2, star3, star4, star5};
        currentRating = 0;

        for (int i = 0; i < stars.length; i++) {
            final int ratingValue = i + 1;
            stars[i].setOnClickListener(v -> {
                currentRating = ratingValue;
                for (int j = 0; j < stars.length; j++) {
                    if (j < currentRating) {
                        stars[j].setImageResource(R.drawable.starfill);
                    } else {
                        stars[j].setImageResource(R.drawable.star);
                    }
                }
            });
        }

        TextView btnNantiSaja = dialog.findViewById(R.id.btnNantiSaja);
        TextView btnKirimPenilaian = dialog.findViewById(R.id.btnKirimPenilaian);

        btnNantiSaja.setOnClickListener(v -> dialog.dismiss());

        btnKirimPenilaian.setOnClickListener(v -> {
            if (currentRating == 0) {
                Toast.makeText(this, "Pilih bintang dulu ya!", Toast.LENGTH_SHORT).show();
            } else {
                Toast.makeText(this, "Terima kasih atas penilaiannya!", Toast.LENGTH_SHORT).show();
                dialog.dismiss();
            }
        });

        dialog.show();
    }
}