package com.example.kantin;

import android.annotation.SuppressLint;
import android.app.Dialog;
import android.content.Intent;
import android.database.Cursor;
import android.net.Uri;
import android.os.Bundle;
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
import androidx.appcompat.app.AppCompatActivity;
import androidx.cardview.widget.CardView;

import com.example.kantin.model.response.CartResponse;
import com.example.kantin.model.response.OrderDetailResponse;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;
import com.example.kantin.utils.SessionManager;

import java.io.File;
import java.io.FileOutputStream;
import java.io.InputStream;
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

    private ImageView btnBack, btnRemoveFile;
    private LinearLayout layoutUploadEmpty, layoutUploadSuccess;
    private TextView btnKonfirmasi, tvFileName;
    private TextView tvItemCount, tvSubtotal, tvOngkir, tvTotal;
    private CardView btnDownloadQris;
    private Uri imageUri;

    private LinearLayout layoutAlamat;
    private EditText etAlamat;
    private String deliveryMethod;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_checkout);

        // Inisialisasi ID
        btnBack = findViewById(R.id.btnBack);
        layoutUploadEmpty = findViewById(R.id.layoutUploadEmpty);
        layoutUploadSuccess = findViewById(R.id.layoutUploadSuccess);
        tvFileName = findViewById(R.id.tvFileName);
        btnRemoveFile = findViewById(R.id.btnRemoveFile);
        btnKonfirmasi = findViewById(R.id.btnKonfirmasi);

        // Inisialisasi ID Harga & QRIS
        tvItemCount = findViewById(R.id.tvItemCount);
        tvSubtotal = findViewById(R.id.tvSubtotal);
        tvOngkir = findViewById(R.id.tvOngkir);
        tvTotal = findViewById(R.id.tvTotal);
        btnDownloadQris = findViewById(R.id.btnDownloadQris);

        layoutAlamat = findViewById(R.id.layoutAlamat);
        etAlamat = findViewById(R.id.etAlamat);

        // Cek metode pengiriman dari Intent
        deliveryMethod = getIntent().getStringExtra("DELIVERY_METHOD");

        // Tampilkan kolom alamat HANYA jika pilihannya "delivery" (Antar Kurir)
        if ("delivery".equals(deliveryMethod)) {
            layoutAlamat.setVisibility(View.VISIBLE);
        }

        // --- TAMPILKAN HARGA DINAMIS DARI KERANJANG ---
        tampilkanDetailHarga();

        // --- AKSI POPUP ICON MATA (QRIS) ---
        btnDownloadQris.setOnClickListener(v -> showQrisPopup());

        // --- AKSI UPLOAD BUKTI BAYAR ---
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

        // --- TOMBOL KONFIRMASI (TEMBAK API) ---
        btnKonfirmasi.setOnClickListener(v -> {
            // Tambahkan log ini untuk debug:
            String canteenId = getIntent().getStringExtra("CANTEEN_ID");
            ArrayList<String> menuIds = getIntent().getStringArrayListExtra("MENU_IDS");
            Log.d("CHECKOUT_DEBUG", "canteen_id: " + canteenId);
            Log.d("CHECKOUT_DEBUG", "menu_ids: " + menuIds);
            Log.d("CHECKOUT_DEBUG", "delivery_method: " + deliveryMethod);

            if (imageUri == null) {
                Toast.makeText(this, "Silakan unggah bukti bayar dulu ya!", Toast.LENGTH_SHORT).show();
            } else {
                File fileBuktiBayar = getFileFromUri(imageUri);
                if (fileBuktiBayar != null) {
                    btnKonfirmasi.setText("Memproses...");
                    btnKonfirmasi.setEnabled(false);
                    cekKantinBukaDanCheckout(fileBuktiBayar);
                } else {
                    Toast.makeText(this, "Gagal memproses gambar", Toast.LENGTH_SHORT).show();
                }
            }
        });

        btnBack.setOnClickListener(v -> finish());
    }

    // --- FUNGSI TAMPILKAN HARGA ---
    private void tampilkanDetailHarga() {
        Intent intent = getIntent();
        int itemCount = intent.getIntExtra("ITEM_COUNT", 0);
        double subtotal = intent.getDoubleExtra("SUBTOTAL", 0);
        double ongkir = intent.getDoubleExtra("ONGKIR", 0);
        double totalBayar = intent.getDoubleExtra("TOTAL_BAYAR", 0);

        tvItemCount.setText(itemCount + " Item");
        tvSubtotal.setText(formatRupiah(subtotal));
        tvOngkir.setText(ongkir == 0 ? "Gratis" : formatRupiah(ongkir));
        tvTotal.setText(formatRupiah(totalBayar));
    }

    // --- FUNGSI POPUP QRIS ---
    private void showQrisPopup() {
        Dialog dialog = new Dialog(this);
        dialog.requestWindowFeature(Window.FEATURE_NO_TITLE);

        // Buat ImageView untuk nampilin QRIS secara fullscreen/popup
        ImageView imageView = new ImageView(this);
        // Ganti R.drawable.makanan ini dengan gambar dummy QRIS kamu nanti kalau sudah ada
        imageView.setImageResource(R.drawable.makanan);
        imageView.setAdjustViewBounds(true);
        imageView.setPadding(20, 20, 20, 20);

        dialog.setContentView(imageView);
        dialog.show();
    }

    private String formatRupiah(double harga) {
        NumberFormat fmt = NumberFormat.getCurrencyInstance(new Locale("id", "ID"));
        return fmt.format(harga).replace(",00", "");
    }

    // --- FUNGSI BANTUAN MENGUBAH URI KE FILE ---
    private File getFileFromUri(Uri uri) {
        try {
            InputStream inputStream = getContentResolver().openInputStream(uri);
            File tempFile = File.createTempFile("payment_", ".jpg", getCacheDir());
            FileOutputStream outputStream = new FileOutputStream(tempFile);
            byte[] buffer = new byte[1024];
            int length;
            while ((length = inputStream.read(buffer)) > 0) {
                outputStream.write(buffer, 0, length);
            }
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
        if (uri.getScheme().equals("content")) {
            try (Cursor cursor = getContentResolver().query(uri, null, null, null, null)) {
                if (cursor != null && cursor.moveToFirst()) {
                    result = cursor.getString(cursor.getColumnIndex(OpenableColumns.DISPLAY_NAME));
                }
            }
        }
        if (result == null) {
            result = uri.getPath();
            int cut = result.lastIndexOf('/');
            if (cut != -1) {
                result = result.substring(cut + 1);
            }
        }
        return result != null ? result : "bukti_transfer.jpg";
    }

    // --- FUNGSI TEMBAK API CHECKOUT ---
    private void prosesCheckout(File fileBuktiBayar) {
        String canteenId = getIntent().getStringExtra("CANTEEN_ID");
        ArrayList<String> menuIds = getIntent().getStringArrayListExtra("MENU_IDS");
        ArrayList<Integer> quantities = getIntent().getIntegerArrayListExtra("QUANTITIES");
        ArrayList<String> notes = getIntent().getStringArrayListExtra("NOTES");

// DEBUG
        Log.d("CHECKOUT_DEBUG", "quantities: " + quantities);
        Log.d("CHECKOUT_DEBUG", "notes: " + notes);

// SAFETY BIAR NGGAK CRASH
        if (quantities == null) quantities = new ArrayList<>();
        if (notes == null) notes = new ArrayList<>();

        if (canteenId == null || deliveryMethod == null || menuIds == null || menuIds.isEmpty()) {
            Toast.makeText(this, "Data keranjang tidak lengkap!", Toast.LENGTH_SHORT).show();
            resetTombolKonfirmasi();
            return;
        }

        // Validasi alamat kalau delivery
        String locationNoteText = "";
        if ("delivery".equals(deliveryMethod)) {
            locationNoteText = etAlamat.getText().toString().trim();
            if (locationNoteText.isEmpty()) {
                Toast.makeText(this, "Alamat pengiriman wajib diisi!", Toast.LENGTH_SHORT).show();
                resetTombolKonfirmasi();
                return;
            }
        }

        // Build multipart body
        RequestBody canteenIdPart       = RequestBody.create(okhttp3.MultipartBody.FORM, canteenId);
        RequestBody deliveryMethodPart  = RequestBody.create(okhttp3.MultipartBody.FORM, deliveryMethod);
        RequestBody locationNotePart    = RequestBody.create(okhttp3.MultipartBody.FORM, locationNoteText);
        RequestBody orderNotesPart      = RequestBody.create(okhttp3.MultipartBody.FORM, "");

        // Build menu_ids, quantities, notes sebagai list RequestBody
        List<RequestBody> menuIdParts   = new ArrayList<>();
        List<RequestBody> qtyParts      = new ArrayList<>();
        List<RequestBody> notesParts    = new ArrayList<>();

        for (int i = 0; i < menuIds.size(); i++) {
            menuIdParts.add(RequestBody.create(okhttp3.MultipartBody.FORM, menuIds.get(i)));
            qtyParts.add(RequestBody.create(okhttp3.MultipartBody.FORM, String.valueOf(quantities.get(i))));
            notesParts.add(RequestBody.create(okhttp3.MultipartBody.FORM,
                    notes != null && i < notes.size() ? notes.get(i) : ""));
        }

        RequestBody requestFile = RequestBody.create(okhttp3.MediaType.parse("image/*"), fileBuktiBayar);
        MultipartBody.Part paymentProofPart = MultipartBody.Part.createFormData(
                "payment_proof", fileBuktiBayar.getName(), requestFile);

        Log.d("CHECKOUT_DEBUG", "canteen_id: " + canteenId);
        Log.d("CHECKOUT_DEBUG", "delivery_method: " + deliveryMethod);
        Log.d("CHECKOUT_DEBUG", "location_note: " + locationNoteText);
        Log.d("CHECKOUT_DEBUG", "menu_ids: " + menuIds);
        Log.d("CHECKOUT_DEBUG", "notes: " + notes);

        String token = new SessionManager(this).getToken();
        ApiClient.getAuthClient(token).create(ApiService.class)
                .checkout(canteenIdPart, deliveryMethodPart, locationNotePart, orderNotesPart,
                        menuIdParts, paymentProofPart)
                .enqueue(new Callback<OrderDetailResponse>() {
                    @Override
                    public void onResponse(Call<OrderDetailResponse> call, Response<OrderDetailResponse> response) {
                        resetTombolKonfirmasi();
                        if (response.isSuccessful() && response.body() != null) {
                            Toast.makeText(CheckoutActivity.this, "Pesanan Berhasil Dibuat!", Toast.LENGTH_SHORT).show();

                            String orderCode = response.body().getData().getOrderCode();
                            String orderId = response.body().getData().getId();
                            if (orderId == null) orderId = response.body().getData().getIdAlias();

                            Intent intent = new Intent(CheckoutActivity.this, CancelPaymentActivity.class);
                            intent.putExtra("ORDER_CODE", orderCode);
                            intent.putExtra("ORDER_ID", orderId);
                            intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
                            startActivity(intent);
                            finish();
                        } else {
                            try {
                                String errorBody = response.errorBody().string();
                                org.json.JSONObject json = new org.json.JSONObject(errorBody);
                                Toast.makeText(CheckoutActivity.this,
                                        json.optString("message", "Gagal Checkout!"), Toast.LENGTH_SHORT).show();
                            } catch (Exception e) {
                                Toast.makeText(CheckoutActivity.this, "Gagal Checkout!", Toast.LENGTH_SHORT).show();
                            }
                        }
                    }

                    @Override
                    public void onFailure(Call<OrderDetailResponse> call, Throwable t) {
                        resetTombolKonfirmasi();
                        Toast.makeText(CheckoutActivity.this, "Error jaringan", Toast.LENGTH_SHORT).show();
                    }
                });
    }
    private void cekKantinBukaDanCheckout(File fileBuktiBayar) {
        String canteenId = getIntent().getStringExtra("CANTEEN_ID");
        ApiClient.getClient().create(ApiService.class).getAllCanteens()
                .enqueue(new Callback<com.example.kantin.model.response.CanteenListResponse>() {
                    @Override
                    public void onResponse(Call<com.example.kantin.model.response.CanteenListResponse> call,
                                           Response<com.example.kantin.model.response.CanteenListResponse> response) {
                        if (response.isSuccessful() && response.body() != null) {
                            com.example.kantin.model.response.CanteenListResponse.CanteenData targetKantin = null;
                            for (com.example.kantin.model.response.CanteenListResponse.CanteenData kantin
                                    : response.body().getData()) {
                                if (kantin.getId().equals(canteenId)) {
                                    targetKantin = kantin;
                                    break;
                                }
                            }
                            if (targetKantin == null) {
                                Toast.makeText(CheckoutActivity.this, "Kantin tidak ditemukan!", Toast.LENGTH_SHORT).show();
                                resetTombolKonfirmasi();
                                return;
                            }
                            if (!isKantinBuka(targetKantin)) {
                                Toast.makeText(CheckoutActivity.this, "Kantin sedang tutup, tidak bisa memesan!", Toast.LENGTH_SHORT).show();
                                resetTombolKonfirmasi();
                                return;
                            }
                            prosesCheckout(fileBuktiBayar);
                        } else {
                            Toast.makeText(CheckoutActivity.this, "Gagal cek status kantin!", Toast.LENGTH_SHORT).show();
                            resetTombolKonfirmasi();
                        }
                    }
                    @Override
                    public void onFailure(Call<com.example.kantin.model.response.CanteenListResponse> call, Throwable t) {
                        Toast.makeText(CheckoutActivity.this, "Error jaringan!", Toast.LENGTH_SHORT).show();
                        resetTombolKonfirmasi();
                    }
                });
    }

    private boolean isKantinBuka(com.example.kantin.model.response.CanteenListResponse.CanteenData kantin) {
        if (!kantin.isOpen()) return false;
        if (kantin.getOperatingHours() == null) return true;
        try {
            String openStr = kantin.getOperatingHours().getOpen();
            String closeStr = kantin.getOperatingHours().getClose();
            java.util.Calendar now = java.util.Calendar.getInstance();
            int nowTotal = now.get(java.util.Calendar.HOUR_OF_DAY) * 60 + now.get(java.util.Calendar.MINUTE);
            int openTotal = Integer.parseInt(openStr.split(":")[0]) * 60 + Integer.parseInt(openStr.split(":")[1]);
            int closeTotal = Integer.parseInt(closeStr.split(":")[0]) * 60 + Integer.parseInt(closeStr.split(":")[1]);
            return nowTotal >= openTotal && nowTotal < closeTotal;
        } catch (Exception e) {
            return kantin.isOpen();
        }
    }

    private void resetTombolKonfirmasi() {
        btnKonfirmasi.setText("Konfirmasi Pembayaran");
        btnKonfirmasi.setEnabled(true);
    }
}