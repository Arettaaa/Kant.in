package com.example.kantin;

import android.Manifest;
import android.annotation.SuppressLint;
import android.app.Dialog;
import android.content.ContentValues;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.database.Cursor;
import android.graphics.Bitmap;
import android.graphics.drawable.Drawable;
import android.net.Uri;
import android.os.Build;
import android.os.Bundle;
import android.os.Environment;
import android.provider.MediaStore;
import android.provider.OpenableColumns;
import android.util.Log;
import android.view.View;
import android.view.Window;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import androidx.activity.result.ActivityResultLauncher;
import androidx.activity.result.contract.ActivityResultContracts;
import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.cardview.widget.CardView;
import androidx.core.app.ActivityCompat;
import androidx.core.content.ContextCompat;

import com.bumptech.glide.Glide;
import com.bumptech.glide.request.target.CustomTarget;
import com.bumptech.glide.request.transition.Transition;
import com.example.kantin.model.response.CanteenDetailResponse;
import com.example.kantin.model.response.CartResponse;
import com.example.kantin.model.response.OrderDetailResponse;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;
import com.example.kantin.utils.SessionManager;

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.text.NumberFormat;
import java.util.ArrayList;
import java.util.List;
import java.util.Locale;

import okhttp3.MultipartBody;
import okhttp3.RequestBody;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class CheckoutActivity extends AppCompatActivity {

    private static final int REQUEST_WRITE_STORAGE = 101;

    // ── Views ──────────────────────────────────────────────────
    private ImageView btnBack, btnRemoveFile;
    private LinearLayout layoutUploadEmpty, layoutUploadSuccess;
    private TextView btnKonfirmasi, tvFileName;
    private TextView tvItemCount, tvSubtotal, tvOngkir, tvTotal;
    private CardView btnDownloadQris;
    private ImageView ivQrisThumbnail;       // thumbnail QRIS di kartu
    private Uri imageUri;

    private LinearLayout layoutAlamat;
    private EditText etAlamat;
    private String deliveryMethod;

    // ── State QRIS ─────────────────────────────────────────────
    private String qrisUrl = null;           // URL dari API kantin
    private Bitmap qrisBitmap = null;        // Bitmap cache untuk download

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_checkout);

        initViews();
        setupDeliveryMethod();
        tampilkanDetailHarga();
        fetchQrisFromApi();
        setupListeners();
    }

    // ── Init ───────────────────────────────────────────────────

    private void initViews() {
        btnBack             = findViewById(R.id.btnBack);
        layoutUploadEmpty   = findViewById(R.id.layoutUploadEmpty);
        layoutUploadSuccess = findViewById(R.id.layoutUploadSuccess);
        tvFileName          = findViewById(R.id.tvFileName);
        btnRemoveFile       = findViewById(R.id.btnRemoveFile);
        btnKonfirmasi       = findViewById(R.id.btnKonfirmasi);
        tvItemCount         = findViewById(R.id.tvItemCount);
        tvSubtotal          = findViewById(R.id.tvSubtotal);
        tvOngkir            = findViewById(R.id.tvOngkir);
        tvTotal             = findViewById(R.id.tvTotal);
        btnDownloadQris     = findViewById(R.id.btnDownloadQris);
        layoutAlamat        = findViewById(R.id.layoutAlamat);
        etAlamat            = findViewById(R.id.etAlamat);

        // Thumbnail QRIS di dalam layout kartu QRIS
        // Pastikan ImageView ini ada di activity_checkout.xml dengan id ivQrisThumbnail
        ivQrisThumbnail     = findViewById(R.id.ivQrisThumbnail);
    }

    private void setupDeliveryMethod() {
        deliveryMethod = getIntent().getStringExtra("DELIVERY_METHOD");
        if ("delivery".equals(deliveryMethod)) {
            layoutAlamat.setVisibility(View.VISIBLE);
        }
    }

    // ── Fetch QRIS URL dari API ────────────────────────────────

    private void fetchQrisFromApi() {
        String canteenId = getIntent().getStringExtra("CANTEEN_ID");
        if (canteenId == null) return;

        // Coba ambil qris_url yang sudah dikirim dari Keranjang via Intent (opsional shortcut)
        String qrisFromIntent = getIntent().getStringExtra("QRIS_URL");
        if (qrisFromIntent != null && !qrisFromIntent.isEmpty()) {
            qrisUrl = qrisFromIntent;
            loadQrisThumbnail();
            return;
        }

        // Fallback: fetch ulang dari API pakai canteen_id
        String token = new SessionManager(this).getToken();
        ApiClient.getAuthClient(token).create(ApiService.class)
                .getCanteenDetail(canteenId)
                .enqueue(new Callback<CanteenDetailResponse>() {
                    @Override
                    public void onResponse(Call<CanteenDetailResponse> call,
                                           Response<CanteenDetailResponse> response) {
                        if (response.isSuccessful() && response.body() != null
                                && response.body().getData() != null) {
                            qrisUrl = response.body().getData().getQrisUrl();
                            loadQrisThumbnail();
                        }
                    }

                    @Override
                    public void onFailure(Call<CanteenDetailResponse> call, Throwable t) {
                        Log.e("CHECKOUT_QRIS", "Gagal fetch QRIS: " + t.getMessage());
                    }
                });
    }

    /**
     * Load thumbnail QRIS ke ImageView di kartu, sekaligus cache Bitmap-nya
     * agar bisa dipakai untuk download tanpa fetch ulang.
     */
    private void loadQrisThumbnail() {
        if (qrisUrl == null || ivQrisThumbnail == null) return;

        Glide.with(this)
                .asBitmap()
                .load(qrisUrl)
                .into(new CustomTarget<Bitmap>() {
                    @Override
                    public void onResourceReady(@NonNull Bitmap resource,
                                                Transition<? super Bitmap> transition) {
                        qrisBitmap = resource;
                        ivQrisThumbnail.setImageBitmap(resource);
                    }

                    @Override
                    public void onLoadCleared(Drawable placeholder) {
                        // no-op
                    }
                });
    }

    // ── Setup listeners ────────────────────────────────────────

    private void setupListeners() {
        btnBack.setOnClickListener(v -> finish());

        // Tombol mata → preview fullscreen dialog
        btnDownloadQris.setOnClickListener(v -> showQrisDialog());

        // Upload bukti bayar
        ActivityResultLauncher<Intent> pickImageLauncher = registerForActivityResult(
                new ActivityResultContracts.StartActivityForResult(),
                result -> {
                    if (result.getResultCode() == RESULT_OK && result.getData() != null) {
                        imageUri = result.getData().getData();
                        layoutUploadEmpty.setVisibility(View.GONE);
                        layoutUploadSuccess.setVisibility(View.VISIBLE);
                        tvFileName.setText(getFileName(imageUri));
                    }
                }
        );

        layoutUploadEmpty.setOnClickListener(v -> {
            Intent intent = new Intent(Intent.ACTION_GET_CONTENT);
            intent.setType("image/*");
            pickImageLauncher.launch(intent);
        });

        btnRemoveFile.setOnClickListener(v -> {
            imageUri = null;
            layoutUploadSuccess.setVisibility(View.GONE);
            layoutUploadEmpty.setVisibility(View.VISIBLE);
        });

        // Tombol konfirmasi
        btnKonfirmasi.setOnClickListener(v -> {
            if (imageUri == null) {
                Toast.makeText(this, "Silakan unggah bukti bayar dulu ya!", Toast.LENGTH_SHORT).show();
                return;
            }
            File fileBuktiBayar = getFileFromUri(imageUri);
            if (fileBuktiBayar != null) {
                btnKonfirmasi.setText("Memproses...");
                btnKonfirmasi.setEnabled(false);
                cekKantinBukaDanCheckout(fileBuktiBayar);
            } else {
                Toast.makeText(this, "Gagal memproses gambar", Toast.LENGTH_SHORT).show();
            }
        });
    }

    // ── Dialog QRIS fullscreen ─────────────────────────────────

    private void showQrisDialog() {
        Dialog dialog = new Dialog(this, android.R.style.Theme_Black_NoTitleBar_Fullscreen);
        dialog.requestWindowFeature(Window.FEATURE_NO_TITLE);
        dialog.setContentView(R.layout.dialog_qris_preview);

        // Pastikan dialog_qris_preview.xml ada — lihat komentar di bawah
        ImageView ivQrisFull      = dialog.findViewById(R.id.ivQrisFull);
        TextView  tvNamaKantin    = dialog.findViewById(R.id.tvNamaKantinQris);
        TextView  btnDownload     = dialog.findViewById(R.id.btnDownloadQrisFull);
        ImageView btnTutup        = dialog.findViewById(R.id.btnTutupQris);

        // Load gambar QRIS full di dialog
        if (qrisUrl != null) {
            Glide.with(this).load(qrisUrl).into(ivQrisFull);
        }

        // Nama kantin (opsional, bisa dikirim via Intent)
        String canteenName = getIntent().getStringExtra("CANTEEN_NAME");
        if (canteenName != null && tvNamaKantin != null) {
            tvNamaKantin.setText(canteenName);
        }

        // Tombol download di dalam dialog
        if (btnDownload != null) {
            btnDownload.setOnClickListener(v -> {
                downloadQrisToGallery();
                dialog.dismiss();
            });
        }

        // Tombol tutup dialog
        if (btnTutup != null) {
            btnTutup.setOnClickListener(v -> dialog.dismiss());
        }

        dialog.show();
    }

    // ── Download QRIS ke Gallery ───────────────────────────────

    private void downloadQrisToGallery() {
        // Android 10+ tidak butuh permission WRITE_EXTERNAL_STORAGE
        if (Build.VERSION.SDK_INT < Build.VERSION_CODES.Q) {
            if (ContextCompat.checkSelfPermission(this, Manifest.permission.WRITE_EXTERNAL_STORAGE)
                    != PackageManager.PERMISSION_GRANTED) {
                ActivityCompat.requestPermissions(this,
                        new String[]{Manifest.permission.WRITE_EXTERNAL_STORAGE},
                        REQUEST_WRITE_STORAGE);
                return;
            }
        }
        simpanQrisKeBitmap();
    }

    private void simpanQrisKeBitmap() {
        if (qrisBitmap != null) {
            // Bitmap sudah ada di cache, langsung simpan
            simpanBitmapKeGallery(qrisBitmap);
        } else if (qrisUrl != null) {
            // Belum ada bitmap, download dulu
            Glide.with(this)
                    .asBitmap()
                    .load(qrisUrl)
                    .into(new CustomTarget<Bitmap>() {
                        @Override
                        public void onResourceReady(@NonNull Bitmap resource,
                                                    Transition<? super Bitmap> transition) {
                            qrisBitmap = resource;
                            simpanBitmapKeGallery(resource);
                        }

                        @Override
                        public void onLoadCleared(Drawable placeholder) {}
                    });
        } else {
            Toast.makeText(this, "QRIS belum tersedia", Toast.LENGTH_SHORT).show();
        }
    }

    private void simpanBitmapKeGallery(Bitmap bitmap) {
        String namaFile = "QRIS_Kantin_" + System.currentTimeMillis() + ".png";

        try {
            OutputStream outputStream;

            if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.Q) {
                // Android 10+ pakai MediaStore
                ContentValues values = new ContentValues();
                values.put(MediaStore.Images.Media.DISPLAY_NAME, namaFile);
                values.put(MediaStore.Images.Media.MIME_TYPE, "image/png");
                values.put(MediaStore.Images.Media.RELATIVE_PATH,
                        Environment.DIRECTORY_PICTURES + "/Kantin");

                Uri uri = getContentResolver().insert(
                        MediaStore.Images.Media.EXTERNAL_CONTENT_URI, values);
                if (uri == null) {
                    Toast.makeText(this, "Gagal menyimpan QRIS", Toast.LENGTH_SHORT).show();
                    return;
                }
                outputStream = getContentResolver().openOutputStream(uri);
            } else {
                // Android 9 ke bawah
                File dir = new File(Environment.getExternalStoragePublicDirectory(
                        Environment.DIRECTORY_PICTURES), "Kantin");
                if (!dir.exists()) dir.mkdirs();
                File file = new File(dir, namaFile);
                outputStream = new FileOutputStream(file);
            }

            if (outputStream != null) {
                bitmap.compress(Bitmap.CompressFormat.PNG, 100, outputStream);
                outputStream.flush();
                outputStream.close();
                Toast.makeText(this, "QRIS berhasil disimpan ke Galeri!", Toast.LENGTH_SHORT).show();
            }

        } catch (IOException e) {
            Log.e("QRIS_DOWNLOAD", "Gagal simpan: " + e.getMessage());
            Toast.makeText(this, "Gagal menyimpan QRIS", Toast.LENGTH_SHORT).show();
        }
    }

    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions,
                                           @NonNull int[] grantResults) {
        super.onRequestPermissionsResult(requestCode, permissions, grantResults);
        if (requestCode == REQUEST_WRITE_STORAGE
                && grantResults.length > 0
                && grantResults[0] == PackageManager.PERMISSION_GRANTED) {
            simpanQrisKeBitmap();
        } else {
            Toast.makeText(this, "Izin diperlukan untuk menyimpan gambar", Toast.LENGTH_SHORT).show();
        }
    }

    // ── Tampilkan harga ────────────────────────────────────────

    private void tampilkanDetailHarga() {
        Intent intent = getIntent();
        int itemCount     = intent.getIntExtra("ITEM_COUNT", 0);
        double subtotal   = intent.getDoubleExtra("SUBTOTAL", 0);
        double ongkir     = intent.getDoubleExtra("ONGKIR", 0);
        double totalBayar = intent.getDoubleExtra("TOTAL_BAYAR", 0);

        tvItemCount.setText(itemCount + " Item");
        tvSubtotal.setText(formatRupiah(subtotal));
        tvOngkir.setText(ongkir == 0 ? "Gratis" : formatRupiah(ongkir));
        tvTotal.setText(formatRupiah(totalBayar));
    }

    // ── Proses checkout ────────────────────────────────────────

    private void prosesCheckout(File fileBuktiBayar) {
        String canteenId        = getIntent().getStringExtra("CANTEEN_ID");
        ArrayList<String>  menuIds    = getIntent().getStringArrayListExtra("MENU_IDS");
        ArrayList<Integer> quantities = getIntent().getIntegerArrayListExtra("QUANTITIES");
        ArrayList<String>  notes      = getIntent().getStringArrayListExtra("NOTES");

        if (quantities == null) quantities = new ArrayList<>();
        if (notes == null) notes = new ArrayList<>();

        if (canteenId == null || deliveryMethod == null || menuIds == null || menuIds.isEmpty()) {
            Toast.makeText(this, "Data keranjang tidak lengkap!", Toast.LENGTH_SHORT).show();
            resetTombolKonfirmasi();
            return;
        }

        String locationNoteText = "";
        if ("delivery".equals(deliveryMethod)) {
            locationNoteText = etAlamat.getText().toString().trim();
            if (locationNoteText.isEmpty()) {
                Toast.makeText(this, "Alamat pengiriman wajib diisi!", Toast.LENGTH_SHORT).show();
                resetTombolKonfirmasi();
                return;
            }
        }

        RequestBody canteenIdPart      = RequestBody.create(okhttp3.MultipartBody.FORM, canteenId);
        RequestBody deliveryMethodPart = RequestBody.create(okhttp3.MultipartBody.FORM, deliveryMethod);
        RequestBody locationNotePart   = RequestBody.create(okhttp3.MultipartBody.FORM, locationNoteText);
        RequestBody orderNotesPart     = RequestBody.create(okhttp3.MultipartBody.FORM, "");

        List<RequestBody> menuIdParts = new ArrayList<>();
        List<RequestBody> qtyParts    = new ArrayList<>();
        List<RequestBody> notesParts  = new ArrayList<>();

        final ArrayList<Integer> finalQty   = quantities;
        final ArrayList<String>  finalNotes = notes;

        for (int i = 0; i < menuIds.size(); i++) {
            menuIdParts.add(RequestBody.create(okhttp3.MultipartBody.FORM, menuIds.get(i)));
            qtyParts.add(RequestBody.create(okhttp3.MultipartBody.FORM,
                    String.valueOf(finalQty.get(i))));
            notesParts.add(RequestBody.create(okhttp3.MultipartBody.FORM,
                    i < finalNotes.size() ? finalNotes.get(i) : ""));
        }

        RequestBody requestFile = RequestBody.create(
                okhttp3.MediaType.parse("image/*"), fileBuktiBayar);
        MultipartBody.Part paymentProofPart = MultipartBody.Part.createFormData(
                "payment_proof", fileBuktiBayar.getName(), requestFile);

        String token = new SessionManager(this).getToken();
        ApiClient.getAuthClient(token).create(ApiService.class)
                .checkout(canteenIdPart, deliveryMethodPart, locationNotePart, orderNotesPart,
                        menuIdParts, paymentProofPart)
                .enqueue(new Callback<OrderDetailResponse>() {
                    @Override
                    public void onResponse(Call<OrderDetailResponse> call,
                                           Response<OrderDetailResponse> response) {
                        resetTombolKonfirmasi();
                        if (response.isSuccessful() && response.body() != null) {
                            Toast.makeText(CheckoutActivity.this,
                                    "Pesanan Berhasil Dibuat!", Toast.LENGTH_SHORT).show();

                            String orderCode = response.body().getData().getOrderCode();
                            String orderId   = response.body().getData().getId();
                            if (orderId == null) orderId = response.body().getData().getIdAlias();

                            Intent intent = new Intent(CheckoutActivity.this,
                                    CancelPaymentActivity.class);
                            intent.putExtra("ORDER_CODE", orderCode);
                            intent.putExtra("ORDER_ID", orderId);
                            intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK
                                    | Intent.FLAG_ACTIVITY_CLEAR_TASK);
                            startActivity(intent);
                            finish();
                        } else {
                            try {
                                String errorBody = response.errorBody().string();
                                org.json.JSONObject json = new org.json.JSONObject(errorBody);
                                Toast.makeText(CheckoutActivity.this,
                                        json.optString("message", "Gagal Checkout!"),
                                        Toast.LENGTH_SHORT).show();
                            } catch (Exception e) {
                                Toast.makeText(CheckoutActivity.this,
                                        "Gagal Checkout!", Toast.LENGTH_SHORT).show();
                            }
                        }
                    }

                    @Override
                    public void onFailure(Call<OrderDetailResponse> call, Throwable t) {
                        resetTombolKonfirmasi();
                        Toast.makeText(CheckoutActivity.this,
                                "Error jaringan", Toast.LENGTH_SHORT).show();
                    }
                });
    }

    private void cekKantinBukaDanCheckout(File fileBuktiBayar) {
        String canteenId = getIntent().getStringExtra("CANTEEN_ID");
        ApiClient.getClient().create(ApiService.class).getAllCanteens()
                .enqueue(new Callback<com.example.kantin.model.response.CanteenListResponse>() {
                    @Override
                    public void onResponse(
                            Call<com.example.kantin.model.response.CanteenListResponse> call,
                            Response<com.example.kantin.model.response.CanteenListResponse> response) {

                        if (response.isSuccessful() && response.body() != null) {
                            com.example.kantin.model.response.CanteenListResponse.CanteenData target = null;
                            for (com.example.kantin.model.response.CanteenListResponse.CanteenData k
                                    : response.body().getData()) {
                                if (k.getId().equals(canteenId)) { target = k; break; }
                            }
                            if (target == null) {
                                Toast.makeText(CheckoutActivity.this,
                                        "Kantin tidak ditemukan!", Toast.LENGTH_SHORT).show();
                                resetTombolKonfirmasi();
                                return;
                            }
                            if (!isKantinBuka(target)) {
                                Toast.makeText(CheckoutActivity.this,
                                        "Kantin sedang tutup, tidak bisa memesan!",
                                        Toast.LENGTH_SHORT).show();
                                resetTombolKonfirmasi();
                                return;
                            }
                            prosesCheckout(fileBuktiBayar);
                        } else {
                            Toast.makeText(CheckoutActivity.this,
                                    "Gagal cek status kantin!", Toast.LENGTH_SHORT).show();
                            resetTombolKonfirmasi();
                        }
                    }

                    @Override
                    public void onFailure(
                            Call<com.example.kantin.model.response.CanteenListResponse> call,
                            Throwable t) {
                        Toast.makeText(CheckoutActivity.this,
                                "Error jaringan!", Toast.LENGTH_SHORT).show();
                        resetTombolKonfirmasi();
                    }
                });
    }

    private boolean isKantinBuka(
            com.example.kantin.model.response.CanteenListResponse.CanteenData kantin) {
        if (!kantin.isOpen()) return false;
        if (kantin.getOperatingHours() == null) return true;
        try {
            String openStr  = kantin.getOperatingHours().getOpen();
            String closeStr = kantin.getOperatingHours().getClose();
            java.util.Calendar now = java.util.Calendar.getInstance();
            int nowTotal   = now.get(java.util.Calendar.HOUR_OF_DAY) * 60
                    + now.get(java.util.Calendar.MINUTE);
            int openTotal  = Integer.parseInt(openStr.split(":")[0]) * 60
                    + Integer.parseInt(openStr.split(":")[1]);
            int closeTotal = Integer.parseInt(closeStr.split(":")[0]) * 60
                    + Integer.parseInt(closeStr.split(":")[1]);
            return nowTotal >= openTotal && nowTotal < closeTotal;
        } catch (Exception e) {
            return kantin.isOpen();
        }
    }

    // ── Helpers ────────────────────────────────────────────────

    private void resetTombolKonfirmasi() {
        btnKonfirmasi.setText("Konfirmasi Pembayaran");
        btnKonfirmasi.setEnabled(true);
    }

    private String formatRupiah(double harga) {
        NumberFormat fmt = NumberFormat.getCurrencyInstance(new Locale("id", "ID"));
        return fmt.format(harga).replace(",00", "");
    }

    private File getFileFromUri(Uri uri) {
        try {
            InputStream inputStream = getContentResolver().openInputStream(uri);
            File tempFile = File.createTempFile("payment_", ".jpg", getCacheDir());
            FileOutputStream outputStream = new FileOutputStream(tempFile);
            byte[] buffer = new byte[1024];
            int length;
            while ((length = inputStream.read(buffer)) > 0) outputStream.write(buffer, 0, length);
            outputStream.close();
            inputStream.close();
            return tempFile;
        } catch (Exception e) {
            Log.e("FILE_ERROR", "Gagal convert URI ke File: " + e.getMessage());
            return null;
        }
    }

    @SuppressLint("Range")
    private String getFileName(Uri uri) {
        String result = null;
        if ("content".equals(uri.getScheme())) {
            try (Cursor cursor = getContentResolver().query(uri, null, null, null, null)) {
                if (cursor != null && cursor.moveToFirst()) {
                    result = cursor.getString(cursor.getColumnIndex(OpenableColumns.DISPLAY_NAME));
                }
            }
        }
        if (result == null) {
            result = uri.getPath();
            int cut = result.lastIndexOf('/');
            if (cut != -1) result = result.substring(cut + 1);
        }
        return result != null ? result : "bukti_transfer.jpg";
    }
}