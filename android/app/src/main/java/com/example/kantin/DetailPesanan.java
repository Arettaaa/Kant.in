package com.example.kantin; // Sesuaikan jika kamu memindahkannya ke dalam folder ui/admin

import android.content.Intent;
import android.os.Bundle;
import android.widget.CheckBox;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.bumptech.glide.Glide;
import com.example.kantin.R;
import com.example.kantin.MenuPesananAdapter;
import com.example.kantin.model.OrderModel;
import com.example.kantin.model.response.BaseResponse;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;
import com.example.kantin.utils.SessionManager;
import com.google.android.material.button.MaterialButton;
import com.google.android.material.imageview.ShapeableImageView;

import java.text.NumberFormat;
import java.util.Locale;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class DetailPesanan extends AppCompatActivity {

    private ImageView btnBack, imgAvatar;
    private ShapeableImageView imgBuktiTransfer;
    private TextView tvOrderCode, tvCustomerName, tvCustomerPhone, valWaktu, valMetode, valAlamat, tvTotalItemHeader;
    private TextView valSubtotal, valOngkir, valTotal;
    private CheckBox cbVerifikasi;
    private MaterialButton btnTolak, btnVerifikasi;
    private RecyclerView rvMenuPesanan;

    private String canteenId = "";
    private OrderModel currentOrder; // Menyimpan data pesanan saat ini

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_detail_pesanan);

        initViews();
        getIntentData();
        setupListeners();
    }

    private void initViews() {
        btnBack = findViewById(R.id.btnBack);
        imgAvatar = findViewById(R.id.imgAvatar);
        imgBuktiTransfer = findViewById(R.id.imgBuktiTransfer);

        tvOrderCode = findViewById(R.id.tvOrderCode);
        tvCustomerName = findViewById(R.id.tvCustomerName);
        tvCustomerPhone = findViewById(R.id.tvCustomerPhone);
        valWaktu = findViewById(R.id.valWaktu);
        valMetode = findViewById(R.id.valMetode);
        valAlamat = findViewById(R.id.valAlamat);
        tvTotalItemHeader = findViewById(R.id.tvTotalItemHeader);

        valSubtotal = findViewById(R.id.valSubtotal);
        valOngkir = findViewById(R.id.valOngkir);
        valTotal = findViewById(R.id.valTotal);

        cbVerifikasi = findViewById(R.id.cbVerifikasi);
        btnTolak = findViewById(R.id.btnTolak);
        btnVerifikasi = findViewById(R.id.btnVerifikasi);

        rvMenuPesanan = findViewById(R.id.rvMenuPesanan);
        rvMenuPesanan.setLayoutManager(new LinearLayoutManager(this));
        rvMenuPesanan.setNestedScrollingEnabled(false);

        btnVerifikasi.setEnabled(false);
    }

    private void getIntentData() {
        // ambil dari session (langsung)
        SessionManager session = new SessionManager(this);
        canteenId = session.getCanteenId();

        // ambil order dari intent (ini masih perlu)
        Intent intent = getIntent();
        if (intent != null) {
            currentOrder = (OrderModel) intent.getSerializableExtra("ORDER_DATA");
        }

        // debug biar yakin
        android.util.Log.d("DEBUG", "canteenId = " + canteenId);
        android.util.Log.d("DEBUG", "orderId = " + (currentOrder != null ? currentOrder.getId() : "null"));

        if (currentOrder != null) {
            populateDataToUI();
        } else {
            Toast.makeText(this, "Data pesanan tidak ditemukan", Toast.LENGTH_SHORT).show();
            finish();
        }
    }

    private void populateDataToUI() {
        android.util.Log.d("ORDER_DEBUG", new com.google.gson.Gson().toJson(currentOrder));

        NumberFormat formatRupiah = NumberFormat.getCurrencyInstance(new Locale("in", "ID"));

        // Header & Customer Info
        tvOrderCode.setText(currentOrder.getOrderCode() != null ? currentOrder.getOrderCode() : "#ORD-XXX");

        if (currentOrder.getCustomerSnapshot() != null) {
            tvCustomerName.setText(currentOrder.getCustomerSnapshot().getName());
            tvCustomerPhone.setText(currentOrder.getCustomerSnapshot().getPhone());
        }

        // Waktu
        String rawDate = currentOrder.getCreatedAt();
        valWaktu.setText(rawDate != null ? rawDate.substring(11, 16) : "Baru saja"); // Ambil jam:menit kasar

        // Delivery Info
        if (currentOrder.getDeliveryDetails() != null) {
            String method = currentOrder.getDeliveryDetails().getMethod();
            valMetode.setText(method != null && method.equals("delivery") ? "Antar Kurir" : "Ambil Sendiri");

            String alamat = currentOrder.getDeliveryDetails().getLocationNote();
            valAlamat.setText(alamat != null && !alamat.isEmpty() ? alamat : "-");
        }

        // Daftar Menu (RecyclerView)
        if (currentOrder.getItems() != null) {
            tvTotalItemHeader.setText(currentOrder.getItems().size() + " ITEM");
            MenuPesananAdapter adapter = new MenuPesananAdapter(currentOrder.getItems());
            rvMenuPesanan.setAdapter(adapter);
        }

        // Ringkasan Pembayaran
        valSubtotal.setText(formatRupiah.format(currentOrder.getSubtotalAmount()));
        valOngkir.setText(formatRupiah.format(currentOrder.getDeliveryDetails() != null ? currentOrder.getDeliveryDetails().getFee() : 0));
        valTotal.setText(formatRupiah.format(currentOrder.getTotalAmount()));

        // Bukti Pembayaran
        if (currentOrder.getPayment() != null && currentOrder.getPayment().getProof() != null) {
            // URL Base server + direktori storage Laravel
            String imageUrl = ApiClient.BASE_URL.replace("api/", "") + "storage/" + currentOrder.getPayment().getProof();

            Glide.with(this)
                    .load(imageUrl)
                    .into(imgBuktiTransfer);
        }
    }

    private void setupListeners() {
        // 1. Perbaikan Tombol Back (Tidak lagi memakai fungsi deprecated)
        btnBack.setOnClickListener(v -> getOnBackPressedDispatcher().onBackPressed());

        // Buka kunci tombol terima jika checkbox dicentang
        cbVerifikasi.setOnCheckedChangeListener((buttonView, isChecked) -> {
            btnVerifikasi.setEnabled(isChecked);
        });

        // 2. ACTION: TOMBOL TOLAK (Hit API Laravel, lalu ke halaman Cancel)
        btnTolak.setOnClickListener(v -> {

            // Nonaktifkan tombol sementara biar gak diklik dobel
            btnTolak.setEnabled(false);

            SessionManager session = new SessionManager(DetailPesanan.this);
            ApiService apiService = ApiClient.getAuthClient(session.getToken()).create(ApiService.class);
            Call<BaseResponse> call = apiService.rejectPayment(canteenId, currentOrder.getId());

            call.enqueue(new Callback<BaseResponse>() {
                @Override
                public void onResponse(Call<BaseResponse> call, Response<BaseResponse> response) {
                    btnTolak.setEnabled(true); // Aktifkan lagi tombolnya

                    if (response.isSuccessful() && response.body() != null && response.body().isSuccess()) {
                        Toast.makeText(DetailPesanan.this, "Pesanan berhasil ditolak", Toast.LENGTH_SHORT).show();

                        // Pindah ke halaman CancelOrderActivity jika sukses
                        Intent intent = new Intent(DetailPesanan.this, CancelOrderActivity.class);
                        // Kirim data pesanan agar bisa ditampilkan di halaman pembatalan
                        intent.putExtra("ORDER_DATA", currentOrder);
                        startActivity(intent);

                        // Menutup halaman DetailPesanan ini agar saat user back dari CancelOrderActivity
                        // mereka kembali ke halaman List Pesanan, bukan ke Detail yang sudah ditolak.
                        finish();
                    } else {
                        Toast.makeText(DetailPesanan.this, "Gagal menolak pesanan", Toast.LENGTH_SHORT).show();
                    }
                }

                @Override
                public void onFailure(Call<BaseResponse> call, Throwable t) {
                    btnTolak.setEnabled(true);
                    Toast.makeText(DetailPesanan.this, "Koneksi bermasalah: " + t.getMessage(), Toast.LENGTH_SHORT).show();
                }
            });
        });

        // 3. ACTION: TOMBOL VERIFIKASI & TERIMA
        btnVerifikasi.setOnClickListener(v -> {
            if (cbVerifikasi.isChecked()) {

                // Nonaktifkan tombol sementara agar tidak diklik dua kali (double-submit)
                btnVerifikasi.setEnabled(false);

                SessionManager session = new SessionManager(DetailPesanan.this);
                ApiService apiService = ApiClient.getAuthClient(session.getToken()).create(ApiService.class);
                // Panggil API Verify Payment
                Call<BaseResponse> call = apiService.verifyPayment(canteenId, currentOrder.getId());

                call.enqueue(new Callback<BaseResponse>() {
                    @Override
                    public void onResponse(Call<BaseResponse> call, Response<BaseResponse> response) {
                        btnVerifikasi.setEnabled(true); // Aktifkan tombol lagi

                        if (response.isSuccessful() && response.body() != null && response.body().isSuccess()) {
                            Toast.makeText(DetailPesanan.this, "Pembayaran berhasil diverifikasi!", Toast.LENGTH_SHORT).show();

                            // Pindah ke halaman Update Status Pesanan
                            Intent intent = new Intent(DetailPesanan.this, UpdateStatusPesananActivity.class);
                            intent.putExtra("ORDER_ID", currentOrder.getId());
                            intent.putExtra("CANTEEN_ID", canteenId);

                            // PENTING: Bawa data pesanannya agar bisa ditampilkan di halaman update status
                            intent.putExtra("ORDER_DATA", currentOrder);

                            startActivity(intent);

                            // Tutup halaman detail ini agar saat admin menekan back,
                            // tidak kembali ke halaman pesanan yang sudah diterima
                            finish();
                        } else {
                            Toast.makeText(DetailPesanan.this, "Gagal memverifikasi pembayaran.", Toast.LENGTH_SHORT).show();
                        }
                    }

                    @Override
                    public void onFailure(Call<BaseResponse> call, Throwable t) {
                        btnVerifikasi.setEnabled(true);
                        Toast.makeText(DetailPesanan.this, "Koneksi bermasalah: " + t.getMessage(), Toast.LENGTH_SHORT).show();
                    }
                });

            } else {
                // (Opsional) Pesan jika admin klik terima tapi belum centang checkbox
                Toast.makeText(DetailPesanan.this, "Silakan centang verifikasi pembayaran terlebih dahulu.", Toast.LENGTH_SHORT).show();
            }
        });
    }
}