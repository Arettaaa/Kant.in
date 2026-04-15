package com.example.kantin;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.cardview.widget.CardView;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.bumptech.glide.Glide;
import com.example.kantin.model.response.CanteenListResponse;
import com.example.kantin.model.response.MenuListResponse;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;
import com.example.kantin.utils.SessionManager;

import java.util.ArrayList;
import java.util.List;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

import android.text.Editable;
import android.text.TextWatcher;
import android.widget.EditText;
import android.view.inputmethod.EditorInfo;

public class BerandaPelangganActivity extends AppCompatActivity {

    private RecyclerView rvKantin, rvMenuPopuler;
    private KantinAdapter kantinAdapter;
    private MenuPopulerAdapter menuPopulerAdapter;
    private SessionManager sessionManager;
    private ImageView ivFotoProfil;
    private TextView tvHaloUser;

    // Pastikan BASE_URL sesuai dengan link Ngrok aktif
    private final String BASE_URL_STORAGE = "https://nonephemerally-nonrevolving-judie.ngrok-free.dev/storage/";

    private List<MenuListResponse.MenuItem> cachedMenus = new ArrayList<>();
    private List<CanteenListResponse.CanteenData> cachedKantins = new ArrayList<>();
    private boolean menuLoaded = false, kantinLoaded = false;

    private CardView chipSemua, chipMakanan, chipMinuman, chipCemilan;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_berandapelanggan);

        sessionManager = new SessionManager(this);

        // --- 1. INISIALISASI VIEW ---
        tvHaloUser = findViewById(R.id.tv_halo_user);
        ivFotoProfil = findViewById(R.id.ivFotoProfil);
        rvKantin = findViewById(R.id.rv_kantin);
        rvMenuPopuler = findViewById(R.id.rv_menu_populer);

        chipSemua    = findViewById(R.id.chip_semua);
        chipMakanan  = findViewById(R.id.chip_makanan);
        chipMinuman  = findViewById(R.id.chip_minuman);
        chipCemilan  = findViewById(R.id.chip_cemilan);

        ImageView btnHistoryTop = findViewById(R.id.btn_history_top);
        FrameLayout btnKeranjang = findViewById(R.id.btn_keranjang);
        CardView btnProfilTop = findViewById(R.id.btn_profil_top);
        TextView tvLihatSemuaMenu = findViewById(R.id.tv_lihat_semua_menu);
        TextView tvLihatSemuaKantin = findViewById(R.id.tv_lihat_semua_kantin);

        LinearLayout navBeranda = findViewById(R.id.nav_beranda);
        LinearLayout navPesanan = findViewById(R.id.nav_pesanan);
        LinearLayout navProfil = findViewById(R.id.nav_profil);

        // --- 2. SETUP RECYCLERVIEW ---
        rvKantin.setLayoutManager(new LinearLayoutManager(this));
        rvMenuPopuler.setLayoutManager(new LinearLayoutManager(this, LinearLayoutManager.HORIZONTAL, false));

        // --- 3. LOAD DATA ---
        updateProfileUI();
        fetchKantinBeranda();
        fetchMenuPopuler();
        preloadSearchData();

        EditText etSearchBeranda = findViewById(R.id.etSearchBeranda);
        etSearchBeranda.setOnEditorActionListener((v, actionId, event) -> {
            if (actionId == EditorInfo.IME_ACTION_SEARCH) {
                String query = etSearchBeranda.getText().toString().trim();
                if (!query.isEmpty()) {
                    handleSearch(query);
                    etSearchBeranda.setText("");
                }
                return true;
            }
            return false;
        });

        ImageView btnIconSearch = findViewById(R.id.btnIconSearch);
        btnIconSearch.setOnClickListener(v -> {
            String query = etSearchBeranda.getText().toString().trim();
            if (!query.isEmpty()) {
                handleSearch(query);
                etSearchBeranda.setText("");
            } else {
                Toast.makeText(this, "Ketik pencarian dulu ya!", Toast.LENGTH_SHORT).show();
            }
        });

        // --- 4. LOGIKA KLIK CHIP KATEGORI ---
        chipSemua.setOnClickListener(v -> {
            updateKategoriUI(chipSemua);
            bukaExploreMenuDenganKategori("Semua");
        });
        chipMakanan.setOnClickListener(v -> {
            updateKategoriUI(chipMakanan);
            bukaExploreMenuDenganKategori("makanan");
        });
        chipMinuman.setOnClickListener(v -> {
            updateKategoriUI(chipMinuman);
            bukaExploreMenuDenganKategori("minuman");
        });
        chipCemilan.setOnClickListener(v -> {
            updateKategoriUI(chipCemilan);
            bukaExploreMenuDenganKategori("camilan");
        });

        // --- 5. LOGIKA NAVIGASI LAINNYA ---
        btnHistoryTop.setOnClickListener(v -> startActivity(new Intent(this, HistoryActivity.class)));
        btnKeranjang.setOnClickListener(v -> startActivity(new Intent(this, KeranjangPelangganActivity.class)));
        btnProfilTop.setOnClickListener(v -> startActivity(new Intent(this, ProfilPelangganActivity.class)));
        tvLihatSemuaMenu.setOnClickListener(v -> startActivity(new Intent(this, ExploreMenuPelangganActivity.class)));
        tvLihatSemuaKantin.setOnClickListener(v -> startActivity(new Intent(this, ExploreKantinPelangganActivity.class)));

        navPesanan.setOnClickListener(v -> startActivity(new Intent(this, HistoryActivity.class)));
        navProfil.setOnClickListener(v -> startActivity(new Intent(this, ProfilPelangganActivity.class)));
    }

    // --- FUNGSI UNTUK MENGUBAH WARNA CHIP AKTIF & INAKTIF ---
    private void updateKategoriUI(CardView activeChip) {
        CardView[] allChips = {chipSemua, chipMakanan, chipMinuman, chipCemilan};

        for (CardView chip : allChips) {
            // Karena hierarki XML: CardView -> LinearLayout -> [ImageView, TextView]
            LinearLayout layout = (LinearLayout) chip.getChildAt(0);
            ImageView icon = (ImageView) layout.getChildAt(0);
            TextView text = (TextView) layout.getChildAt(1);

            if (chip == activeChip) {
                // Style Aktif: Background Oranye, Teks & Ikon Putih
                chip.setCardBackgroundColor(android.graphics.Color.parseColor("#F97316"));
                icon.setColorFilter(android.graphics.Color.parseColor("#FFFFFF"));
                text.setTextColor(android.graphics.Color.parseColor("#FFFFFF"));
            } else {
                // Style Tidak Aktif: Background Putih, Ikon Oranye, Teks Abu-abu
                chip.setCardBackgroundColor(android.graphics.Color.parseColor("#FFFFFF"));
                icon.setColorFilter(android.graphics.Color.parseColor("#F97316"));
                text.setTextColor(android.graphics.Color.parseColor("#4B5563"));
            }
        }
    }

    private void updateProfileUI() {
        String fullName = sessionManager.getUserName();
        if (fullName != null && !fullName.isEmpty()) {
            tvHaloUser.setText("Halo, " + fullName.split(" ")[0] + "! 👋");
        } else {
            tvHaloUser.setText("Halo, Sobat Kant.in! 👋");
        }

        String path = sessionManager.getPhotoUrl();
        if (path != null && !path.isEmpty()) {
            ivFotoProfil.setPadding(0, 0, 0, 0);
            String fullUrl = path.startsWith("http") ? path : BASE_URL_STORAGE + path;
            Glide.with(this).load(fullUrl).circleCrop().placeholder(R.drawable.userorg).into(ivFotoProfil);
        } else {
            ivFotoProfil.setImageResource(R.drawable.userorg);
            int p = (int) (7 * getResources().getDisplayMetrics().density);
            ivFotoProfil.setPadding(p, p, p, p);
        }
    }

    private void fetchKantinBeranda() {
        ApiService apiService = ApiClient.getClient().create(ApiService.class);
        apiService.getAllCanteens().enqueue(new Callback<CanteenListResponse>() {
            @Override
            public void onResponse(Call<CanteenListResponse> call, Response<CanteenListResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    List<CanteenListResponse.CanteenData> allKantin = response.body().getData();
                    if (allKantin != null && !allKantin.isEmpty()) {
                        List<CanteenListResponse.CanteenData> displayList = allKantin.size() > 5 ? allKantin.subList(0, 5) : allKantin;
                        kantinAdapter = new KantinAdapter(BerandaPelangganActivity.this, displayList);
                        rvKantin.setAdapter(kantinAdapter);
                    }
                }
            }
            @Override public void onFailure(Call<CanteenListResponse> call, Throwable t) {
                Log.e("API_ERROR", "Fetch Kantin: " + t.getMessage());
            }
        });
    }

    private void fetchMenuPopuler() {
        ApiService apiService = ApiClient.getClient().create(ApiService.class);
        apiService.getAllMenus().enqueue(new Callback<MenuListResponse>() {
            @Override
            public void onResponse(Call<MenuListResponse> call, Response<MenuListResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    List<MenuListResponse.MenuItem> allMenu = response.body().getData();
                    if (allMenu != null && !allMenu.isEmpty()) {
                        List<MenuListResponse.MenuItem> displayList = allMenu.size() > 3 ? allMenu.subList(0, 3) : allMenu;
                        menuPopulerAdapter = new MenuPopulerAdapter(BerandaPelangganActivity.this, displayList);
                        rvMenuPopuler.setAdapter(menuPopulerAdapter);
                    }
                }
            }
            @Override public void onFailure(Call<MenuListResponse> call, Throwable t) {
                Log.e("API_ERROR", "Fetch Menu Populer: " + t.getMessage());
            }
        });
    }

    private void preloadSearchData() {
        ApiService api = ApiClient.getClient().create(ApiService.class);

        api.getAllMenus().enqueue(new Callback<MenuListResponse>() {
            @Override
            public void onResponse(Call<MenuListResponse> call, Response<MenuListResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    cachedMenus = response.body().getData();
                }
                menuLoaded = true;
            }
            @Override
            public void onFailure(Call<MenuListResponse> call, Throwable t) {
                menuLoaded = true;
            }
        });

        api.getAllCanteens().enqueue(new Callback<CanteenListResponse>() {
            @Override
            public void onResponse(Call<CanteenListResponse> call, Response<CanteenListResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    cachedKantins = response.body().getData();
                }
                kantinLoaded = true;
            }
            @Override
            public void onFailure(Call<CanteenListResponse> call, Throwable t) {
                kantinLoaded = true;
            }
        });
    }

    private void handleSearch(String query) {
        boolean adaMenu = false, adaKantin = false;

        if (cachedMenus != null) {
            for (MenuListResponse.MenuItem item : cachedMenus) {
                if (item.getName() != null && item.getName().toLowerCase().contains(query.toLowerCase())) {
                    adaMenu = true;
                    break;
                }
            }
        }

        if (cachedKantins != null) {
            for (CanteenListResponse.CanteenData kantin : cachedKantins) {
                if (kantin.getName() != null && kantin.getName().toLowerCase().contains(query.toLowerCase())) {
                    adaKantin = true;
                    break;
                }
            }
        }

        if (adaMenu && adaKantin) {
            Intent intent = new Intent(this, SearchActivity.class);
            intent.putExtra("QUERY", query);
            startActivity(intent);
        } else if (adaMenu) {
            Intent intent = new Intent(this, ExploreMenuPelangganActivity.class);
            intent.putExtra("QUERY", query);
            intent.putExtra("KATEGORI", "Semua");
            startActivity(intent);
        } else if (adaKantin) {
            Intent intent = new Intent(this, ExploreKantinPelangganActivity.class);
            intent.putExtra("QUERY", query);
            startActivity(intent);
        } else {
            Intent intent = new Intent(this, SearchActivity.class);
            intent.putExtra("QUERY", query);
            startActivity(intent);
        }
    }

    private void bukaExploreMenuDenganKategori(String kategori) {
        Intent intent = new Intent(this, ExploreMenuPelangganActivity.class);
        intent.putExtra("KATEGORI", kategori);
        startActivity(intent);
    }

    @Override
    protected void onResume() {
        super.onResume();
        updateProfileUI();
        // Reset kategori ke "Semua" setiap kali user kembali ke Beranda
        if (chipSemua != null) {
            updateKategoriUI(chipSemua);
        }
    }
}