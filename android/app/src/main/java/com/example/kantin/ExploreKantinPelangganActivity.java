package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.example.kantin.model.response.CanteenListResponse;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;

import java.util.List;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class ExploreKantinPelangganActivity extends AppCompatActivity {

    private RecyclerView rvExploreKantin;
    private KantinAdapter adapter;
    private EditText etSearchKantin;

    // Filter state
    private String activeFilter = "Semua"; // default

    // Chip views
    private TextView chipSemua, chipBuka, chipTutup;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_explorekantinpelanggan);

        ImageView btnBack = findViewById(R.id.btnBackExplore);
        rvExploreKantin = findViewById(R.id.rvExploreKantin);
        etSearchKantin = findViewById(R.id.etSearchKantin);

        chipSemua = findViewById(R.id.chipSemua);
        chipBuka = findViewById(R.id.chipBuka);
        chipTutup = findViewById(R.id.chipTutup);

        LinearLayout navBeranda = findViewById(R.id.navBeranda);
        LinearLayout navPesanan = findViewById(R.id.navPesanan);
        LinearLayout navProfil = findViewById(R.id.navProfil);

        rvExploreKantin.setLayoutManager(new LinearLayoutManager(this));

        fetchSemuaKantin();

        // --- SEARCH LISTENER ---
        etSearchKantin.addTextChangedListener(new TextWatcher() {
            @Override public void beforeTextChanged(CharSequence s, int start, int count, int after) {}
            @Override public void afterTextChanged(Editable s) {}

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
                if (adapter != null) {
                    adapter.filter(s.toString(), activeFilter);
                }
            }
        });

        // --- CHIP FILTER LISTENER ---
        chipSemua.setOnClickListener(v -> setFilter("Semua"));
        chipBuka.setOnClickListener(v -> setFilter("Buka"));
        chipTutup.setOnClickListener(v -> setFilter("Tutup"));

        btnBack.setOnClickListener(v -> onBackPressed());

        navBeranda.setOnClickListener(v -> {
            Intent intent = new Intent(this, BerandaPelangganActivity.class);
            intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
            startActivity(intent);
        });
        navPesanan.setOnClickListener(v -> startActivity(new Intent(this, HistoryActivity.class)));
        navProfil.setOnClickListener(v -> startActivity(new Intent(this, ProfilPelangganActivity.class)));
    }

    private void setFilter(String filter) {
        activeFilter = filter;

        // Reset semua chip ke style default (tidak aktif)
        resetChipStyle(chipSemua);
        resetChipStyle(chipBuka);
        resetChipStyle(chipTutup);

        // Aktifkan chip yang dipilih
        if (filter.equals("Semua")) setActiveChipStyle(chipSemua);
        else if (filter.equals("Buka")) setActiveChipStyle(chipBuka);
        else if (filter.equals("Tutup")) setActiveChipStyle(chipTutup);

        // Jalankan filter
        if (adapter != null) {
            adapter.filter(etSearchKantin.getText().toString(), activeFilter);
        }
    }

    private void setActiveChipStyle(TextView chip) {
        chip.setBackgroundResource(R.drawable.bg_chip_active); // background aktif (oranye/hijau)
        chip.setTextColor(android.graphics.Color.WHITE);
    }

    private void resetChipStyle(TextView chip) {
        chip.setBackgroundResource(R.drawable.bg_chip_inactive); // background abu
        chip.setTextColor(android.graphics.Color.parseColor("#6B7280"));
    }

    private void fetchSemuaKantin() {
        ApiService apiService = ApiClient.getClient().create(ApiService.class);
        apiService.getAllCanteens().enqueue(new Callback<CanteenListResponse>() {
            @Override
            public void onResponse(Call<CanteenListResponse> call, Response<CanteenListResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    List<CanteenListResponse.CanteenData> list = response.body().getData();
                    adapter = new KantinAdapter(ExploreKantinPelangganActivity.this, list);
                    rvExploreKantin.setAdapter(adapter);
                    setFilter("Semua");

                    String queryDariIntent = getIntent().getStringExtra("QUERY");
                    if (queryDariIntent != null && !queryDariIntent.isEmpty()) {
                        etSearchKantin.setText(queryDariIntent);
                        etSearchKantin.setSelection(queryDariIntent.length());
                        adapter.filter(queryDariIntent, activeFilter);
                    }
                }
            }

            @Override
            public void onFailure(Call<CanteenListResponse> call, Throwable t) {
                Toast.makeText(ExploreKantinPelangganActivity.this, "Gagal memuat kantin", Toast.LENGTH_SHORT).show();
            }
        });
    }


}