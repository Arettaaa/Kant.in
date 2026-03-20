package com.example.kantin;

import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Toast;
import androidx.activity.result.ActivityResultLauncher;
import androidx.activity.result.contract.ActivityResultContracts;
import androidx.appcompat.app.AppCompatActivity;
import com.google.android.material.button.MaterialButton; // Import MaterialButton

public class UbahProfilPelangganActivity extends AppCompatActivity {

    private ImageView btnBack, btnChangePhoto, imgProfile;
    private EditText etNama, etPhone, etEmail;
    private MaterialButton btnSimpan; // Sudah diperbaiki dari LinearLayout ke MaterialButton
    private Uri imageUri;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_ubahprofilpelanggan);

        // 1. Inisialisasi View
        btnBack = findViewById(R.id.btnBack);
        btnChangePhoto = findViewById(R.id.btnChangePhoto);
        imgProfile = findViewById(R.id.imgProfile);
        etNama = findViewById(R.id.etNama);
        etPhone = findViewById(R.id.etPhone);
        etEmail = findViewById(R.id.etEmail);
        btnSimpan = findViewById(R.id.btnSimpan);

        // 2. Fungsi Ambil Gambar dari Galeri
        ActivityResultLauncher<Intent> pickImageLauncher = registerForActivityResult(
                new ActivityResultContracts.StartActivityForResult(),
                result -> {
                    if (result.getResultCode() == RESULT_OK && result.getData() != null) {
                        imageUri = result.getData().getData();
                        imgProfile.setImageURI(imageUri);
                        // Opsional: Hilangkan background transparan jika gambar sudah ada
                        imgProfile.setBackgroundResource(0);
                    }
                }
        );

        btnChangePhoto.setOnClickListener(v -> {
            Intent intent = new Intent(Intent.ACTION_PICK);
            intent.setType("image/*");
            pickImageLauncher.launch(intent);
        });

        // 3. Tombol Simpan Perubahan
        btnSimpan.setOnClickListener(v -> {
            String nama = etNama.getText().toString().trim();
            String phone = etPhone.getText().toString().trim();
            String email = etEmail.getText().toString().trim();

            if (nama.isEmpty() || phone.isEmpty() || email.isEmpty()) {
                Toast.makeText(this, "Harap isi semua data!", Toast.LENGTH_SHORT).show();
            } else {
                Toast.makeText(this, "Profil " + nama + " Berhasil Diperbarui!", Toast.LENGTH_LONG).show();
                finish(); // Kembali ke halaman sebelumnya
            }
        });

        // 4. Tombol Kembali
        btnBack.setOnClickListener(v -> finish());
    }
}