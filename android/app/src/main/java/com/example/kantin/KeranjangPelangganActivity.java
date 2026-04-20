package com.example.kantin;

import android.content.res.ColorStateList;
import android.graphics.Color;
import android.os.Bundle;
import android.view.View;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RadioButton;
import android.widget.TextView;
import android.widget.Toast;
import android.content.Intent;

import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.AppCompatCheckBox;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

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

public class KeranjangPelangganActivity extends AppCompatActivity
        implements CartAdapter.OnCartChangedListener {

    private ImageView btnBack, btnDelete;
    private TextView tvTotalBayar, tvTotalBottom, tvSubtotalBelanja, tvOngkir, btnCheckout;
    private LinearLayout layoutAmbilSendiri, layoutAntarKurir;
    private RadioButton radioAmbilSendiri, radioAntarKurir;
    private AppCompatCheckBox cbSelectAll;
    private RecyclerView rvCart;
    private View layoutKosong;
    private TextView tvMenuKosong;

    private int biayaOngkir = 0;
    private String token;
    private List<CartResponse.CartItem> allItems = new ArrayList<>();
    private CartAdapter cartAdapter;
    private boolean isUpdatingSelectAll = false; // flag hindari loop

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_keranjangpelanggan);

        token = new SessionManager(this).getToken();
        initViews();
        setupListeners();
        fetchCart();
    }

    private void initViews() {
        btnBack            = findViewById(R.id.btnBack);
        btnDelete          = findViewById(R.id.btnDelete);
        btnCheckout        = findViewById(R.id.btnCheckout);
        tvTotalBayar       = findViewById(R.id.tvTotalBayar);
        tvTotalBottom      = findViewById(R.id.tvTotalBottom);
        tvSubtotalBelanja  = findViewById(R.id.tvSubtotalBelanja);
        tvOngkir           = findViewById(R.id.tvOngkir);
        layoutAmbilSendiri = findViewById(R.id.layoutAmbilSendiri);
        layoutAntarKurir   = findViewById(R.id.layoutAntarKurir);
        radioAmbilSendiri  = findViewById(R.id.radioAmbilSendiri);
        radioAntarKurir    = findViewById(R.id.radioAntarKurir);
        cbSelectAll        = findViewById(R.id.cbSelectAll);
        rvCart             = findViewById(R.id.rvCart);
        layoutKosong       = findViewById(R.id.layoutKosong);
        tvMenuKosong       = findViewById(R.id.tvMenuKosong);

        rvCart.setLayoutManager(new LinearLayoutManager(this));
    }

    private void setupListeners() {
        btnBack.setOnClickListener(v -> onBackPressed());
        btnDelete.setOnClickListener(v -> tampilkanDialogHapus());

        layoutAmbilSendiri.setOnClickListener(v -> selectAmbilSendiri());
        radioAmbilSendiri.setOnClickListener(v -> selectAmbilSendiri());
        layoutAntarKurir.setOnClickListener(v -> selectAntarKurir());
        radioAntarKurir.setOnClickListener(v -> selectAntarKurir());

        // Select All checkbox
        cbSelectAll.setOnCheckedChangeListener((buttonView, isChecked) -> {
            if (isUpdatingSelectAll) return;
            if (cartAdapter != null) {
                cartAdapter.setSelectAll(isChecked);
                updateTotal();
                updateCheckoutButton();
            }
        });

        // Checkout button
        btnCheckout.setOnClickListener(v -> {
            if (cartAdapter == null || !cartAdapter.hasSelectedItem()) {
                Toast.makeText(this, "Pilih minimal 1 item dulu", Toast.LENGTH_SHORT).show();
                return;
            }
            // TODO: lanjut ke halaman checkout/konfirmasi
            Toast.makeText(this, "Lanjut checkout!", Toast.LENGTH_SHORT).show();
        });
    }

    // ── CartAdapter.OnCartChangedListener ─────────────────────

    @Override
    public void onCartChanged() {
        fetchCart();
    }

    @Override
    public void onSelectionChanged() {
        updateTotal();
        updateCheckoutButton();
        syncSelectAllCheckbox();
    }

    // ── Fetch data ────────────────────────────────────────────

    private void fetchCart() {
        ApiClient.getAuthClient(token).create(ApiService.class)
                .getCart()
                .enqueue(new Callback<CartResponse>() {
                    @Override
                    public void onResponse(Call<CartResponse> call, Response<CartResponse> response) {
                        if (response.isSuccessful() && response.body() != null
                                && response.body().getData() != null) {

                            CartResponse.CartData cart = response.body().getData();
                            allItems.clear();
                            if (cart.getCanteens() != null) {
                                for (CartResponse.CanteenCart canteen : cart.getCanteens()) {
                                    allItems.addAll(canteen.getItems());
                                }
                            }

                            if (allItems.isEmpty()) {
                                showKosong();
                            } else {
                                cartAdapter = new CartAdapter(
                                        KeranjangPelangganActivity.this,
                                        allItems,
                                        KeranjangPelangganActivity.this
                                );
                                rvCart.setAdapter(cartAdapter);
                                showCart();
                                updateTotal();
                                updateCheckoutButton();
                                syncSelectAllCheckbox();
                            }
                        } else {
                            showKosong();
                        }
                    }

                    @Override
                    public void onFailure(Call<CartResponse> call, Throwable t) {
                        Toast.makeText(KeranjangPelangganActivity.this,
                                "Gagal memuat keranjang", Toast.LENGTH_SHORT).show();
                    }
                });
    }

    // ── Hitung & update total ─────────────────────────────────

    private void updateTotal() {
        if (cartAdapter == null) return;

        double subtotal = cartAdapter.getSelectedSubtotal();
        double total = subtotal + biayaOngkir;

        tvSubtotalBelanja.setText(formatRupiah(subtotal));
        tvOngkir.setText(biayaOngkir == 0 ? "Gratis" : formatRupiah(biayaOngkir));
        tvTotalBayar.setText(formatRupiah(total));
        tvTotalBottom.setText(formatRupiah(total));
    }

    private void updateCheckoutButton() {
        if (cartAdapter != null && cartAdapter.hasSelectedItem()) {
            btnCheckout.setAlpha(1.0f);
            btnCheckout.setEnabled(true);
        } else {
            btnCheckout.setAlpha(0.5f);
            btnCheckout.setEnabled(false);
        }
    }

    /** Sync cbSelectAll tanpa trigger listener-nya */
    private void syncSelectAllCheckbox() {
        if (cartAdapter == null) return;
        isUpdatingSelectAll = true;
        cbSelectAll.setChecked(cartAdapter.isAllSelected());
        isUpdatingSelectAll = false;
    }

    // ── Metode pesanan ────────────────────────────────────────

    private void selectAmbilSendiri() {
        biayaOngkir = 0;
        radioAmbilSendiri.setChecked(true);
        radioAntarKurir.setChecked(false);
        radioAmbilSendiri.setButtonTintList(ColorStateList.valueOf(Color.parseColor("#F97316")));
        radioAntarKurir.setButtonTintList(ColorStateList.valueOf(Color.parseColor("#D1D5DB")));
        layoutAmbilSendiri.setBackgroundResource(R.drawable.bg_border_orange);
        layoutAntarKurir.setBackgroundResource(R.drawable.bg_border_gray);
        updateTotal();
    }

    private void selectAntarKurir() {
        biayaOngkir = 5000;
        radioAntarKurir.setChecked(true);
        radioAmbilSendiri.setChecked(false);
        radioAntarKurir.setButtonTintList(ColorStateList.valueOf(Color.parseColor("#F97316")));
        radioAmbilSendiri.setButtonTintList(ColorStateList.valueOf(Color.parseColor("#D1D5DB")));
        layoutAntarKurir.setBackgroundResource(R.drawable.bg_border_orange);
        layoutAmbilSendiri.setBackgroundResource(R.drawable.bg_border_gray);
        updateTotal();
    }

    // ── Show/hide state ───────────────────────────────────────

    private void showKosong() {
        Intent intent = new Intent(this, EmptyCartActivity.class);
        startActivity(intent);
        finish(); // tutup KeranjangPelangganActivity supaya tidak numpuk
    }

    private void showCart() {
        rvCart.setVisibility(View.VISIBLE);
        layoutKosong.setVisibility(View.GONE);
    }

    // ── Dialog hapus semua ────────────────────────────────────

    private void tampilkanDialogHapus() {
        // Kumpulkan item yang sedang dicentang
        List<CartResponse.CartItem> selectedItems = new ArrayList<>();
        if (cartAdapter != null) {
            for (int i = 0; i < allItems.size(); i++) {
                if (cartAdapter.isItemSelected(i)) {
                    selectedItems.add(allItems.get(i));
                }
            }
        }

        // Kalau tidak ada yang dipilih
        if (selectedItems.isEmpty()) {
            Toast.makeText(this, "Pilih item yang ingin dihapus dulu", Toast.LENGTH_SHORT).show();
            return;
        }

        android.app.Dialog dialog = new android.app.Dialog(this);
        dialog.setContentView(R.layout.dialog_hapus);
        dialog.getWindow().setBackgroundDrawable(
                new android.graphics.drawable.ColorDrawable(Color.TRANSPARENT));
        dialog.getWindow().setLayout(
                android.view.ViewGroup.LayoutParams.MATCH_PARENT,
                android.view.ViewGroup.LayoutParams.WRAP_CONTENT);

        TextView btnDialogBatal = dialog.findViewById(R.id.btnDialogBatal);
        TextView btnDialogHapus = dialog.findViewById(R.id.btnDialogHapus);

        btnDialogBatal.setOnClickListener(v -> dialog.dismiss());
        btnDialogHapus.setOnClickListener(v -> {
            for (CartResponse.CartItem item : selectedItems) {
                ApiClient.getAuthClient(token).create(ApiService.class)
                        .removeCartItem(item.getMenuId())
                        .enqueue(new Callback<CartResponse>() {
                            @Override public void onResponse(Call<CartResponse> call, Response<CartResponse> response) {}
                            @Override public void onFailure(Call<CartResponse> call, Throwable t) {}
                        });
            }
            dialog.dismiss();
            Toast.makeText(this, selectedItems.size() + " item dihapus", Toast.LENGTH_SHORT).show();
            fetchCart(); // refresh
        });

        dialog.show();
    }
    // ── Helper ────────────────────────────────────────────────

    private String formatRupiah(double harga) {
        NumberFormat fmt = NumberFormat.getCurrencyInstance(new Locale("id", "ID"));
        return fmt.format(harga).replace(",00", "");
    }
}