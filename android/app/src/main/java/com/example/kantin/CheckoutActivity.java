package com.example.kantin;

import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;
import androidx.activity.result.ActivityResultLauncher;
import androidx.activity.result.contract.ActivityResultContracts;
import androidx.appcompat.app.AppCompatActivity;

public class CheckoutActivity extends AppCompatActivity {

    private ImageView btnBack, imgPreview;
    private LinearLayout btnUpload;
    private TextView btnKonfirmasi, tvUploadStatus;
    private Uri imageUri;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_checkout);

        // Inisialisasi
        btnBack = findViewById(R.id.btnBack);
        btnUpload = findViewById(R.id.btnUpload);
        imgPreview = findViewById(R.id.imgPreview);
        tvUploadStatus = findViewById(R.id.tvUploadStatus);
        btnKonfirmasi = findViewById(R.id.btnKonfirmasi);

        // 1. Aksi Pilih Gambar (Buka Galeri)
        ActivityResultLauncher<Intent> pickImageLauncher = registerForActivityResult(
                new ActivityResultContracts.StartActivityForResult(),
                result -> {
                    if (result.getResultCode() == RESULT_OK && result.getData() != null) {
                        imageUri = result.getData().getData();
                        // Ganti icon upload jadi gambar yang dipilih
                        imgPreview.setImageURI(imageUri);
                        imgPreview.setPadding(0, 0, 0, 0); // Hapus padding agar gambar penuh
                        imgPreview.setScaleType(ImageView.ScaleType.CENTER_CROP);
                        tvUploadStatus.setText("Gambar Berhasil Dipilih!");
                    }
                }
        );

        btnUpload.setOnClickListener(v -> {
            Intent intent = new Intent(Intent.ACTION_PICK);
            intent.setType("image/*");
            pickImageLauncher.launch(intent);
        });

        // 2. Tombol Konfirmasi
        btnKonfirmasi.setOnClickListener(v -> {
            if (imageUri == null) {
                Toast.makeText(this, "Silakan unggah bukti bayar dulu ya!", Toast.LENGTH_SHORT).show();
            } else {
                // Berpindah ke halaman SuccessPaymentActivity
                Intent intent = new Intent(CheckoutActivity.this, SuccessPaymentActivity.class);

                // Opsional: Jika ingin mengirim data harga ke halaman sukses
                // intent.putExtra("TOTAL_PAYMENT", "Rp 48.000");

                startActivity(intent);

                // Kita finish() activity ini agar user tidak bisa balik
                // ke halaman pembayaran lagi dengan tombol back HP
                finish();
            }
        });

        // 3. Tombol Back
        btnBack.setOnClickListener(v -> finish());
    }
}