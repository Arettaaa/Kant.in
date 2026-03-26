package com.example.kantin;

import android.app.Activity;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Toast;

import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;

import com.bumptech.glide.Glide;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;
import com.example.kantin.model.response.ProfileResponse;
import com.example.kantin.utils.SessionManager;
import com.github.dhaval2404.imagepicker.ImagePicker;
import com.google.android.material.button.MaterialButton;

import java.io.File;

import okhttp3.MediaType;
import okhttp3.MultipartBody;
import okhttp3.RequestBody;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class UbahProfilPelangganActivity extends AppCompatActivity {

    private ImageView btnBack, imgProfile, btnChangePhoto;
    private EditText etNama, etPhone, etEmail;
    private MaterialButton btnSimpan;

    private SessionManager sessionManager;
    private File photoFile = null;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_ubahprofilpelanggan); // Sesuaikan dengan nama XML kamu

        // 1. Inisialisasi
        sessionManager = new SessionManager(this);
        btnBack = findViewById(R.id.btnBack);
        imgProfile = findViewById(R.id.imgProfile);
        btnChangePhoto = findViewById(R.id.btnChangePhoto);
        etNama = findViewById(R.id.etNama);
        etPhone = findViewById(R.id.etPhone);
        etEmail = findViewById(R.id.etEmail);
        btnSimpan = findViewById(R.id.btnSimpan);

        // Pasang data sementara dari lokal dulu biar form gak kosong melompong
        etNama.setText(sessionManager.getUserName());
        etEmail.setText(sessionManager.getUserEmail());
        etEmail.setEnabled(false);
        etPhone.setText(sessionManager.getUserPhone()); // Sekarang dia bakal ngambil data dari session

        String currentPhotoUrl = sessionManager.getPhotoUrl();
        if (currentPhotoUrl != null && !currentPhotoUrl.isEmpty()) {
            Glide.with(this).load(currentPhotoUrl).circleCrop().into(imgProfile);
        } else {
            imgProfile.setImageResource(R.drawable.user);
        }

        // 2. Tarik Data ASLI dari Database (Biar Nomor HP Muncul!)
        tarikDataDariServer();

        // 3. AKSI TOMBOL
        btnBack.setOnClickListener(v -> onBackPressed());
        btnChangePhoto.setOnClickListener(v -> bukaGaleri());
        imgProfile.setOnClickListener(v -> bukaGaleri());
        btnSimpan.setOnClickListener(v -> simpanData());
    }

    // Fungsi sakti buat narik data langsung dari Laravel
    private void tarikDataDariServer() {
        String token = "Bearer " + sessionManager.getToken();
        ApiService apiService = ApiClient.getClient().create(ApiService.class);

        apiService.getBuyerProfile(token).enqueue(new Callback<ProfileResponse>() {
            @Override
            public void onResponse(Call<ProfileResponse> call, Response<ProfileResponse> response) {
                if (response.isSuccessful() && response.body() != null && response.body().getData() != null) {

                    // Ambil data segar dari server
                    String namaAsli = response.body().getData().getName();
                    String phoneAsli = response.body().getData().getPhone();
                    String fotoAsli = response.body().getData().getPhotoProfile();

                    // Timpa form dengan data dari server
                    if (namaAsli != null) etNama.setText(namaAsli);

                    // Nah, ini dia yang bikin nomor HP-nya muncul!
                    if (phoneAsli != null && !phoneAsli.isEmpty()) {
                        etPhone.setText(phoneAsli);
                    }

                    if (fotoAsli != null && !fotoAsli.isEmpty()) {
                        Glide.with(UbahProfilPelangganActivity.this).load(fotoAsli).circleCrop().into(imgProfile);
                        sessionManager.savePhotoUrl(fotoAsli); // Update sesi lokal sekalian
                    }
                }
            }

            @Override
            public void onFailure(Call<ProfileResponse> call, Throwable t) {
                // Kalau internet lemot, biarin aja pake data lokal, gak usah error
            }
        });
    }

    private void bukaGaleri() {
        ImagePicker.with(this)
                .cropSquare()
                .compress(1024)
                .maxResultSize(1080, 1080)
                .start();
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, @Nullable Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        if (resultCode == Activity.RESULT_OK && data != null) {
            Uri fileUri = data.getData();
            photoFile = new File(fileUri.getPath());
            Glide.with(this).load(photoFile).circleCrop().into(imgProfile);
        } else if (resultCode == ImagePicker.RESULT_ERROR) {
            Toast.makeText(this, ImagePicker.getError(data), Toast.LENGTH_SHORT).show();
        }
    }

    private void simpanData() {
        String inputNama = etNama.getText().toString().trim();
        String inputPhone = etPhone.getText().toString().trim();

        if (inputNama.isEmpty()) {
            etNama.setError("Nama tidak boleh kosong!");
            return;
        }

        btnSimpan.setEnabled(false);
        btnSimpan.setText("Menyimpan...");

        RequestBody nameBody = RequestBody.create(MediaType.parse("text/plain"), inputNama);
        RequestBody phoneBody = RequestBody.create(MediaType.parse("text/plain"), inputPhone);

        MultipartBody.Part photoPart = null;
        if (photoFile != null) {
            RequestBody requestFile = RequestBody.create(MediaType.parse("image/*"), photoFile);
            photoPart = MultipartBody.Part.createFormData("photo_profile", photoFile.getName(), requestFile);
        }

        String token = "Bearer " + sessionManager.getToken();
        ApiService apiService = ApiClient.getClient().create(ApiService.class);

        apiService.updateProfileBuyers(token, nameBody, phoneBody, photoPart).enqueue(new Callback<ProfileResponse>() {
            @Override
            public void onResponse(Call<ProfileResponse> call, Response<ProfileResponse> response) {
                btnSimpan.setEnabled(true);
                btnSimpan.setText("Simpan Perubahan");

                if (response.isSuccessful() && response.body() != null) {
                    Toast.makeText(UbahProfilPelangganActivity.this, "Berhasil diupdate!", Toast.LENGTH_SHORT).show();

                    // AMBIL DATA TERBARU DARI FORM
                    String inputNama = etNama.getText().toString().trim();
                    String inputPhone = etPhone.getText().toString().trim(); // Ambil nomor HP yang baru diketik

                    // SIMPAN 3 DATA SEKALIGUS (Nama, Email, dan Phone Baru)
                    sessionManager.saveUserInfo(inputNama, sessionManager.getUserEmail(), inputPhone);

                    // Update foto jika ada yang baru dari server
                    if (response.body().getData() != null && response.body().getData().getPhotoProfile() != null) {
                        sessionManager.savePhotoUrl(response.body().getData().getPhotoProfile());
                    }

                    finish(); // Balik ke halaman profil
                } else {
                    Toast.makeText(UbahProfilPelangganActivity.this, "Gagal simpan data", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<ProfileResponse> call, Throwable t) {
                btnSimpan.setEnabled(true);
                btnSimpan.setText("Simpan Perubahan");
                Toast.makeText(UbahProfilPelangganActivity.this, "Error koneksi: " + t.getMessage(), Toast.LENGTH_SHORT).show();
            }
        });
    }
}