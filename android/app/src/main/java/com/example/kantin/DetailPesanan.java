package com.example.kantin;

import android.app.Dialog;
import android.content.Intent;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.view.Window;
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

import android.Manifest;
import android.content.ContentValues;
import android.content.pm.PackageManager;
import android.graphics.Bitmap;
import android.graphics.drawable.Drawable;
import android.net.Uri;
import android.os.Build;
import android.os.Environment;
import android.provider.MediaStore;
import android.view.ViewGroup;

import androidx.core.app.ActivityCompat;
import androidx.core.content.ContextCompat;

import com.bumptech.glide.request.target.CustomTarget;
import com.bumptech.glide.request.transition.Transition;

import java.io.OutputStream;
import com.github.chrisbanes.photoview.PhotoView;

import java.text.NumberFormat;
import java.util.Locale;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class DetailPesanan extends AppCompatActivity {

    private ImageView btnBack, imgAvatar;
    private ImageView btnViewBukti; // ← TAMBAHAN
    private ShapeableImageView imgBuktiTransfer;
    private TextView tvOrderCode, tvCustomerName, tvCustomerPhone, valWaktu, valMetode, valAlamat, tvTotalItemHeader;
    private TextView valSubtotal, valOngkir, valTotal;
    private CheckBox cbVerifikasi;
    private MaterialButton btnTolak, btnVerifikasi;
    private RecyclerView rvMenuPesanan;

    private String canteenId = "";
    private OrderModel currentOrder;
    private String currentImageUrl = null; // ← TAMBAHAN: simpan URL gambar untuk dialog

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
        btnViewBukti = findViewById(R.id.btnViewBukti); // ← TAMBAHAN

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
        SessionManager session = new SessionManager(this);
        canteenId = session.getCanteenId();

        Intent intent = getIntent();
        if (intent != null) {
            currentOrder = (OrderModel) intent.getSerializableExtra("ORDER_DATA");
        }

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

        tvOrderCode.setText(currentOrder.getOrderCode() != null ? currentOrder.getOrderCode() : "#ORD-XXX");

        if (currentOrder.getCustomerSnapshot() != null) {
            tvCustomerName.setText(currentOrder.getCustomerSnapshot().getName());
            tvCustomerPhone.setText(currentOrder.getCustomerSnapshot().getPhone());
        }

        String rawDate = currentOrder.getCreatedAt();
        valWaktu.setText(rawDate != null ? rawDate.substring(11, 16) : "Baru saja");

        if (currentOrder.getDeliveryDetails() != null) {
            String method = currentOrder.getDeliveryDetails().getMethod();
            valMetode.setText(method != null && method.equals("delivery") ? "Antar Kurir" : "Ambil Sendiri");

            String alamat = currentOrder.getDeliveryDetails().getLocationNote();
            valAlamat.setText(alamat != null && !alamat.isEmpty() ? alamat : "-");
        }

        if (currentOrder.getItems() != null) {
            tvTotalItemHeader.setText(currentOrder.getItems().size() + " ITEM");
            MenuPesananAdapter adapter = new MenuPesananAdapter(currentOrder.getItems());
            rvMenuPesanan.setAdapter(adapter);
        }

        valSubtotal.setText(formatRupiah.format(currentOrder.getSubtotalAmount()));
        valOngkir.setText(formatRupiah.format(currentOrder.getDeliveryDetails() != null ? currentOrder.getDeliveryDetails().getFee() : 0));
        valTotal.setText(formatRupiah.format(currentOrder.getTotalAmount()));

        // Bukti Pembayaran
        if (currentOrder.getPayment() != null && currentOrder.getPayment().getProof() != null) {
            currentImageUrl = ApiClient.BASE_URL.replace("api/", "") + "storage/" + currentOrder.getPayment().getProof(); // ← SIMPAN URL

            Glide.with(this)
                    .load(currentImageUrl)
                    .into(imgBuktiTransfer);

            btnViewBukti.setVisibility(ImageView.VISIBLE); // ← Tampilkan tombol mata jika ada gambar
        } else {
            btnViewBukti.setVisibility(ImageView.GONE); // ← Sembunyikan jika tidak ada gambar
        }
    }

    // ↓ TAMBAHAN: Method untuk membuka dialog preview gambar
    private void showImagePreviewDialog(String imageUrl) {
        Dialog dialog = new Dialog(this);
        dialog.requestWindowFeature(Window.FEATURE_NO_TITLE);
        dialog.setContentView(R.layout.dialog_image_preview);

        if (dialog.getWindow() != null) {
            dialog.getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));
            dialog.getWindow().setLayout(
                    ViewGroup.LayoutParams.MATCH_PARENT,
                    ViewGroup.LayoutParams.WRAP_CONTENT
            );
        }

        // ← Ganti ImageView jadi PhotoView
        PhotoView imgPreview = dialog.findViewById(R.id.imgPreview);
        ImageView btnClose = dialog.findViewById(R.id.btnCloseDialog);
        MaterialButton btnDownload = dialog.findViewById(R.id.btnDownloadBukti);

        Glide.with(this)
                .load(imageUrl)
                .placeholder(R.drawable.bg_circle_outline)
                .into(imgPreview);

        btnClose.setOnClickListener(v -> dialog.dismiss());

        btnDownload.setOnClickListener(v -> {
            if (Build.VERSION.SDK_INT < Build.VERSION_CODES.Q) {
                if (ContextCompat.checkSelfPermission(this, Manifest.permission.WRITE_EXTERNAL_STORAGE)
                        != PackageManager.PERMISSION_GRANTED) {
                    ActivityCompat.requestPermissions(this,
                            new String[]{Manifest.permission.WRITE_EXTERNAL_STORAGE}, 101);
                    Toast.makeText(this, "Izin diperlukan. Silakan coba lagi.", Toast.LENGTH_SHORT).show();
                    return;
                }
            }
            downloadImage(imageUrl);
            dialog.dismiss();
        });

        dialog.show();
    }

    private void downloadImage(String imageUrl) {
        Toast.makeText(this, "Menyimpan gambar...", Toast.LENGTH_SHORT).show();

        Glide.with(this)
                .asBitmap()
                .load(imageUrl)
                .into(new CustomTarget<Bitmap>() {
                    @Override
                    public void onResourceReady(Bitmap bitmap, Transition<? super Bitmap> transition) {
                        try {
                            String fileName = "bukti_" + currentOrder.getOrderCode() + ".jpg";
                            OutputStream outputStream;

                            if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.Q) {
                                // Android 10+ — pakai MediaStore, tidak perlu permission
                                ContentValues values = new ContentValues();
                                values.put(MediaStore.Images.Media.DISPLAY_NAME, fileName);
                                values.put(MediaStore.Images.Media.MIME_TYPE, "image/jpeg");
                                values.put(MediaStore.Images.Media.RELATIVE_PATH,
                                        Environment.DIRECTORY_PICTURES + "/BuktiPembayaran");

                                Uri uri = getContentResolver().insert(
                                        MediaStore.Images.Media.EXTERNAL_CONTENT_URI, values);
                                outputStream = getContentResolver().openOutputStream(uri);
                            } else {
                                // Android < 10 — simpan ke folder Pictures
                                java.io.File dir = new java.io.File(
                                        Environment.getExternalStoragePublicDirectory(
                                                Environment.DIRECTORY_PICTURES), "BuktiPembayaran");
                                if (!dir.exists()) dir.mkdirs();
                                java.io.File file = new java.io.File(dir, fileName);
                                outputStream = new java.io.FileOutputStream(file);
                            }

                            bitmap.compress(Bitmap.CompressFormat.JPEG, 95, outputStream);
                            outputStream.close();

                            Toast.makeText(DetailPesanan.this,
                                    "Tersimpan di Galeri › BuktiPembayaran", Toast.LENGTH_LONG).show();

                        } catch (Exception e) {
                            Toast.makeText(DetailPesanan.this,
                                    "Gagal menyimpan: " + e.getMessage(), Toast.LENGTH_SHORT).show();
                        }
                    }

                    @Override
                    public void onLoadCleared(Drawable placeholder) {}
                });
    }

    private void setupListeners() {
        btnBack.setOnClickListener(v -> getOnBackPressedDispatcher().onBackPressed());

        cbVerifikasi.setOnCheckedChangeListener((buttonView, isChecked) -> {
            btnVerifikasi.setEnabled(isChecked);
        });

        // ↓ TAMBAHAN: Listener tombol mata untuk membuka preview gambar
        btnViewBukti.setOnClickListener(v -> {
            if (currentImageUrl != null && !currentImageUrl.isEmpty()) {
                showImagePreviewDialog(currentImageUrl);
            } else {
                Toast.makeText(this, "Gambar bukti pembayaran tidak tersedia", Toast.LENGTH_SHORT).show();
            }
        });

        // Tombol Tolak
        btnTolak.setOnClickListener(v -> {
            btnTolak.setEnabled(false);

            SessionManager session = new SessionManager(DetailPesanan.this);
            ApiService apiService = ApiClient.getAuthClient(session.getToken()).create(ApiService.class);
            Call<BaseResponse> call = apiService.rejectPayment(canteenId, currentOrder.getId());

            call.enqueue(new Callback<BaseResponse>() {
                @Override
                public void onResponse(Call<BaseResponse> call, Response<BaseResponse> response) {
                    btnTolak.setEnabled(true);

                    if (response.isSuccessful() && response.body() != null && response.body().isSuccess()) {
                        Toast.makeText(DetailPesanan.this, "Pesanan berhasil ditolak", Toast.LENGTH_SHORT).show();

                        Intent intent = new Intent(DetailPesanan.this, CancelOrderActivity.class);
                        intent.putExtra("ORDER_DATA", currentOrder);
                        startActivity(intent);
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

        // Tombol Verifikasi
        btnVerifikasi.setOnClickListener(v -> {
            if (cbVerifikasi.isChecked()) {
                btnVerifikasi.setEnabled(false);

                SessionManager session = new SessionManager(DetailPesanan.this);
                ApiService apiService = ApiClient.getAuthClient(session.getToken()).create(ApiService.class);
                Call<BaseResponse> call = apiService.verifyPayment(canteenId, currentOrder.getId());

                call.enqueue(new Callback<BaseResponse>() {
                    @Override
                    public void onResponse(Call<BaseResponse> call, Response<BaseResponse> response) {
                        btnVerifikasi.setEnabled(true);

                        if (response.isSuccessful() && response.body() != null && response.body().isSuccess()) {
                            Toast.makeText(DetailPesanan.this, "Pembayaran berhasil diverifikasi!", Toast.LENGTH_SHORT).show();

                            Intent intent = new Intent(DetailPesanan.this, UpdateStatusPesananActivity.class);
                            intent.putExtra("ORDER_ID", currentOrder.getId());
                            intent.putExtra("CANTEEN_ID", canteenId);
                            intent.putExtra("ORDER_DATA", currentOrder);
                            startActivity(intent);
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
                Toast.makeText(DetailPesanan.this, "Silakan centang verifikasi pembayaran terlebih dahulu.", Toast.LENGTH_SHORT).show();
            }
        });
    }
}