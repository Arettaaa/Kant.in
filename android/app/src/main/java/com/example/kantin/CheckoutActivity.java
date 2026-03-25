package com.example.kantin;

import android.annotation.SuppressLint;
import android.content.Intent;
import android.database.Cursor;
import android.net.Uri;
import android.os.Bundle;
import android.provider.OpenableColumns;
import android.view.View;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import androidx.activity.result.ActivityResultLauncher;
import androidx.activity.result.contract.ActivityResultContracts;
import androidx.appcompat.app.AppCompatActivity;

public class CheckoutActivity extends AppCompatActivity {

    private ImageView btnBack, btnRemoveFile;
    private LinearLayout layoutUploadEmpty, layoutUploadSuccess;
    private TextView btnKonfirmasi, tvFileName;
    private Uri imageUri;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_checkout);

        // Inisialisasi ID dari XML baru
        btnBack = findViewById(R.id.btnBack);
        layoutUploadEmpty = findViewById(R.id.layoutUploadEmpty);
        layoutUploadSuccess = findViewById(R.id.layoutUploadSuccess);
        tvFileName = findViewById(R.id.tvFileName);
        btnRemoveFile = findViewById(R.id.btnRemoveFile);
        btnKonfirmasi = findViewById(R.id.btnKonfirmasi);

        // 1. Aksi Pilih Gambar (Buka Galeri)
        ActivityResultLauncher<Intent> pickImageLauncher = registerForActivityResult(
                new ActivityResultContracts.StartActivityForResult(),
                result -> {
                    if (result.getResultCode() == RESULT_OK && result.getData() != null) {
                        imageUri = result.getData().getData();

                        // Sembunyikan kotak putus-putus, tampilkan kotak hijau
                        layoutUploadEmpty.setVisibility(View.GONE);
                        layoutUploadSuccess.setVisibility(View.VISIBLE);

                        // Ambil nama file dan tampilkan di TextView
                        String fileName = getFileName(imageUri);
                        tvFileName.setText(fileName);
                    }
                }
        );

        // Klik area putus-putus untuk upload
        layoutUploadEmpty.setOnClickListener(v -> {
            Intent intent = new Intent(Intent.ACTION_PICK);
            intent.setType("image/*");
            pickImageLauncher.launch(intent);
        });

        // 2. Klik tombol silang (X) untuk menghapus file yang dipilih
        btnRemoveFile.setOnClickListener(v -> {
            imageUri = null; // Kosongkan data gambar
            // Sembunyikan kotak hijau, tampilkan lagi kotak putus-putus
            layoutUploadSuccess.setVisibility(View.GONE);
            layoutUploadEmpty.setVisibility(View.VISIBLE);
        });

        // 3. Tombol Konfirmasi
        btnKonfirmasi.setOnClickListener(v -> {
            if (imageUri == null) {
                Toast.makeText(this, "Silakan unggah bukti bayar dulu ya!", Toast.LENGTH_SHORT).show();
            } else {
                Intent intent = new Intent(CheckoutActivity.this, CancelPaymentActivity.class);
                startActivity(intent);

                // Kita finish() activity ini agar user tidak bisa balik
                finish();
            }
        });

        // 4. Tombol Back
        btnBack.setOnClickListener(v -> finish());
    }

    // Method bantuan untuk mendapatkan nama file asli dari URI galeri
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
}