package com.example.kantin;

import android.content.Intent;
import android.content.res.ColorStateList;
import android.graphics.Color;
import android.os.Bundle;
import android.view.View;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RadioButton;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.AppCompatCheckBox;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.example.kantin.model.response.CanteenDetailResponse;
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

    // ── Views ─────────────────────────────────────────────────
    private ImageView btnBack, btnDelete;
    private TextView tvTotalBayar, tvTotalBottom, tvSubtotalBelanja, tvOngkir;
    private TextView tvOngkirKurir, btnCheckout;
    private LinearLayout layoutAmbilSendiri, layoutAntarKurir;
    private LinearLayout layoutWarningMultiKantin;
    private RadioButton radioAmbilSendiri, radioAntarKurir;
    private AppCompatCheckBox cbSelectAll;
    private RecyclerView rvCart;
    private View layoutKosong;
    private TextView tvMenuKosong;

    // ── State ─────────────────────────────────────────────────
    private double deliveryFeeFlat = 0;
    private int biayaOngkir = 0;
    private String token;
    private List<CartResponse.CartItem> allItems = new ArrayList<>();
    private List<CartResponse.CanteenCart> allCanteens = new ArrayList<>();
    private CartAdapter cartAdapter;
    private boolean isUpdatingSelectAll = false;

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

    // ── Init ──────────────────────────────────────────────────

    private void initViews() {
        btnBack                  = findViewById(R.id.btnBack);
        btnDelete                = findViewById(R.id.btnDelete);
        btnCheckout              = findViewById(R.id.btnCheckout);
        tvTotalBayar             = findViewById(R.id.tvTotalBayar);
        tvTotalBottom            = findViewById(R.id.tvTotalBottom);
        tvSubtotalBelanja        = findViewById(R.id.tvSubtotalBelanja);
        tvOngkir                 = findViewById(R.id.tvOngkir);
        tvOngkirKurir            = findViewById(R.id.tvOngkirKurir);
        layoutAmbilSendiri       = findViewById(R.id.layoutAmbilSendiri);
        layoutAntarKurir         = findViewById(R.id.layoutAntarKurir);
        layoutWarningMultiKantin = findViewById(R.id.layoutWarningMultiKantin);
        radioAmbilSendiri        = findViewById(R.id.radioAmbilSendiri);
        radioAntarKurir          = findViewById(R.id.radioAntarKurir);
        cbSelectAll              = findViewById(R.id.cbSelectAll);
        rvCart                   = findViewById(R.id.rvCart);
        layoutKosong             = findViewById(R.id.layoutKosong);
        tvMenuKosong             = findViewById(R.id.tvMenuKosong);

        rvCart.setLayoutManager(new LinearLayoutManager(this));
    }

    private void setupListeners() {
        btnBack.setOnClickListener(v -> onBackPressed());
        btnDelete.setOnClickListener(v -> tampilkanDialogHapus());

        layoutAmbilSendiri.setOnClickListener(v -> selectAmbilSendiri());
        radioAmbilSendiri.setOnClickListener(v -> selectAmbilSendiri());
        layoutAntarKurir.setOnClickListener(v -> selectAntarKurir());
        radioAntarKurir.setOnClickListener(v -> selectAntarKurir());

        cbSelectAll.setOnCheckedChangeListener((buttonView, isChecked) -> {
            if (isUpdatingSelectAll) return;
            if (cartAdapter != null) {
                cartAdapter.setSelectAll(isChecked);
                updateTotal();
                updateCheckoutButton();
                updateWarningMultiKantin();
            }
        });

        btnCheckout.setOnClickListener(v -> {
            if (cartAdapter == null || !cartAdapter.hasSelectedItem()) {
                Toast.makeText(this, "Pilih minimal 1 item dulu", Toast.LENGTH_SHORT).show();
                return;
            }

            String canteenId = getSelectedCanteenId();
            if (canteenId == null) {
                Toast.makeText(this, "Checkout hanya bisa dari 1 kantin", Toast.LENGTH_SHORT).show();
                return;
            }

            // 1. Kumpulkan ID menu yang dicentang
            ArrayList<String> selectedMenuIds = new ArrayList<>();
            for (int i = 0; i < allItems.size(); i++) {
                if (cartAdapter.isItemSelected(i)) {
                    selectedMenuIds.add(allItems.get(i).getMenuId());
                }
            }

            // 2. Tentukan metode pengiriman
            String deliveryMethod = radioAntarKurir.isChecked() ? "delivery" : "pickup";

            // ... (kode sebelumnya sama) ...

            // 3. Pindah ke Halaman Checkout dan bawa datanya
            Intent intent = new Intent(KeranjangPelangganActivity.this, CheckoutActivity.class);
            intent.putExtra("CANTEEN_ID", canteenId);
            intent.putStringArrayListExtra("MENU_IDS", selectedMenuIds);
            intent.putExtra("DELIVERY_METHOD", deliveryMethod);

            // --- TAMBAHAN BARU: Bawa detail harga & item ---
            intent.putExtra("ITEM_COUNT", selectedMenuIds.size());
            intent.putExtra("SUBTOTAL", cartAdapter.getSelectedSubtotal());
            intent.putExtra("ONGKIR", (double) biayaOngkir);
            intent.putExtra("TOTAL_BAYAR", cartAdapter.getSelectedSubtotal() + biayaOngkir);

            startActivity(intent);
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
        updateWarningMultiKantin();

        // Kalau mode Antar Kurir, refresh ongkir sesuai centangan baru
        if (radioAntarKurir.isChecked()) {
            String selectedCanteenId = getSelectedCanteenId();
            if (selectedCanteenId != null) {
                fetchDeliveryFee(selectedCanteenId);
            } else {
                deliveryFeeFlat = 0;
                biayaOngkir = 0;
                updateTotal();
            }
        }
    }

    // ── Fetch cart ────────────────────────────────────────────

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
                            allCanteens.clear();

                            if (cart.getCanteens() != null) {
                                allCanteens.addAll(cart.getCanteens());
                                for (CartResponse.CanteenCart canteen : cart.getCanteens()) {
                                    for (CartResponse.CartItem item : canteen.getItems()) {
                                        // Inject canteen info ke tiap item
                                        item.setCanteenId(canteen.getCanteenId());
                                        item.setCanteenName(canteen.getCanteenName());
                                        allItems.add(item);
                                    }
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
                                updateWarningMultiKantin();
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

    // ── Fetch delivery fee dari API kantin ────────────────────

    private void fetchDeliveryFee(String canteenId) {
        ApiClient.getAuthClient(token).create(ApiService.class)
                .getCanteenDetail(canteenId)
                .enqueue(new Callback<CanteenDetailResponse>() {
                    @Override
                    public void onResponse(Call<CanteenDetailResponse> call,
                                           Response<CanteenDetailResponse> response) {
                        if (response.isSuccessful() && response.body() != null
                                && response.body().getData() != null) {
                            deliveryFeeFlat = response.body().getData().getDeliveryFeeFlat();
                        } else {
                            deliveryFeeFlat = 0;
                        }
                        biayaOngkir = (int) deliveryFeeFlat;
                        tvOngkirKurir.setText("+" + formatRupiah(deliveryFeeFlat));
                        updateTotal();
                    }

                    @Override
                    public void onFailure(Call<CanteenDetailResponse> call, Throwable t) {
                        Toast.makeText(KeranjangPelangganActivity.this,
                                "Gagal ambil ongkos kirim", Toast.LENGTH_SHORT).show();
                        deliveryFeeFlat = 0;
                        biayaOngkir = 0;
                        updateTotal();
                    }
                });
    }

    // ── Helper: canteen_id dari item yang dicentang ───────────

    /**
     * Return canteen_id jika semua item yang dicentang dari 1 kantin.
     * Return null jika tidak ada item dipilih atau >1 kantin.
     */
    private String getSelectedCanteenId() {
        if (cartAdapter == null) return null;
        String foundId = null;
        for (int i = 0; i < allItems.size(); i++) {
            if (cartAdapter.isItemSelected(i)) {
                String cid = allItems.get(i).getCanteenId();
                if (foundId == null) {
                    foundId = cid;
                } else if (!foundId.equals(cid)) {
                    return null; // lebih dari 1 kantin
                }
            }
        }
        return foundId;
    }

    // ── Update UI ─────────────────────────────────────────────

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
        boolean canCheckout = cartAdapter != null
                && cartAdapter.hasSelectedItem()
                && getSelectedCanteenId() != null; // hanya 1 kantin

        btnCheckout.setAlpha(canCheckout ? 1.0f : 0.5f);
        btnCheckout.setEnabled(canCheckout);
    }

    private void updateWarningMultiKantin() {
        if (cartAdapter == null || layoutWarningMultiKantin == null) return;

        boolean hasSelected = cartAdapter.hasSelectedItem();
        boolean isMultiKantin = hasSelected && getSelectedCanteenId() == null;

        layoutWarningMultiKantin.setVisibility(isMultiKantin ? View.VISIBLE : View.GONE);
    }

    private void syncSelectAllCheckbox() {
        if (cartAdapter == null) return;
        isUpdatingSelectAll = true;
        cbSelectAll.setChecked(cartAdapter.isAllSelected());
        isUpdatingSelectAll = false;
    }

    // ── Metode pesanan ────────────────────────────────────────

    private void selectAmbilSendiri() {
        biayaOngkir = 0;
        deliveryFeeFlat = 0;
        radioAmbilSendiri.setChecked(true);
        radioAntarKurir.setChecked(false);
        radioAmbilSendiri.setButtonTintList(ColorStateList.valueOf(Color.parseColor("#F97316")));
        radioAntarKurir.setButtonTintList(ColorStateList.valueOf(Color.parseColor("#D1D5DB")));
        layoutAmbilSendiri.setBackgroundResource(R.drawable.bg_border_orange);
        layoutAntarKurir.setBackgroundResource(R.drawable.bg_border_gray);
        updateTotal();
    }

    private void selectAntarKurir() {
        radioAntarKurir.setChecked(true);
        radioAmbilSendiri.setChecked(false);
        radioAntarKurir.setButtonTintList(ColorStateList.valueOf(Color.parseColor("#F97316")));
        radioAmbilSendiri.setButtonTintList(ColorStateList.valueOf(Color.parseColor("#D1D5DB")));
        layoutAntarKurir.setBackgroundResource(R.drawable.bg_border_orange);
        layoutAmbilSendiri.setBackgroundResource(R.drawable.bg_border_gray);

        String selectedCanteenId = getSelectedCanteenId();
        if (selectedCanteenId != null) {
            fetchDeliveryFee(selectedCanteenId);
        } else {
            deliveryFeeFlat = 0;
            biayaOngkir = 0;
            updateTotal();
        }
    }

    // ── Show / hide ───────────────────────────────────────────

    private void showKosong() {
        Intent intent = new Intent(this, EmptyCartActivity.class);
        startActivity(intent);
        finish();
    }

    private void showCart() {
        rvCart.setVisibility(View.VISIBLE);
        layoutKosong.setVisibility(View.GONE);
    }

    // ── Dialog hapus ──────────────────────────────────────────

    private void tampilkanDialogHapus() {
        List<CartResponse.CartItem> selectedItems = new ArrayList<>();
        if (cartAdapter != null) {
            for (int i = 0; i < allItems.size(); i++) {
                if (cartAdapter.isItemSelected(i)) {
                    selectedItems.add(allItems.get(i));
                }
            }
        }

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
            fetchCart();
        });

        dialog.show();
    }

    // ── Helper ────────────────────────────────────────────────

    private String formatRupiah(double harga) {
        NumberFormat fmt = NumberFormat.getCurrencyInstance(new Locale("id", "ID"));
        return fmt.format(harga).replace(",00", "");
    }
}