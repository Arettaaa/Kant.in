package com.example.kantin;

import android.app.Activity;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.util.Log;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Toast;

import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;

import com.bumptech.glide.Glide;
import com.example.kantin.model.response.ProfileResponse;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;
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

    // Pastikan URL Ngrok ini sesuai dengan yang sedang jalan
    private final String BASE_URL_STORAGE = "https://nonephemerally-nonrevolving-judie.ngrok-free.dev/storage/";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_ubahprofilpelanggan);

        sessionManager = new SessionManager(this);
        initViews();
        loadDataAwal();
        syncDataFromServer();

        btnBack.setOnClickListener(v -> onBackPressed());
        btnChangePhoto.setOnClickListener(v -> bukaGaleri());
        imgProfile.setOnClickListener(v -> bukaGaleri());
        btnSimpan.setOnClickListener(v -> validasiDanSimpan());
    }

    private void initViews() {
        btnBack = findViewById(R.id.btnBack);
        imgProfile = findViewById(R.id.imgProfile);
        btnChangePhoto = findViewById(R.id.btnChangePhoto);
        etNama = findViewById(R.id.etNama);
        etPhone = findViewById(R.id.etPhone);
        etEmail = findViewById(R.id.etEmail);
        btnSimpan = findViewById(R.id.btnSimpan);
        etEmail.setEnabled(false); // Biasanya email tidak bisa diubah
    }

    private void loadDataAwal() {
        etNama.setText(sessionManager.getUserName());
        etEmail.setText(sessionManager.getUserEmail());
        etPhone.setText(sessionManager.getUserPhone());
        handleProfileImage(sessionManager.getPhotoUrl());
    }

    private void handleProfileImage(String path) {
        if (path != null && !path.isEmpty()) {
            imgProfile.setPadding(0, 0, 0, 0);
            String fullUrl = path.startsWith("http") ? path : BASE_URL_STORAGE + path;
            Glide.with(this).load(fullUrl).circleCrop().placeholder(R.drawable.userorg).into(imgProfile);
        } else {
            imgProfile.setImageResource(R.drawable.userorg);
            int p = (int) (22 * getResources().getDisplayMetrics().density);
            imgProfile.setPadding(p, p, p, p);
        }
    }

    private void syncDataFromServer() {
        String token = "Bearer " + sessionManager.getToken();
        ApiClient.getClient().create(ApiService.class).getBuyerProfile(token).enqueue(new Callback<ProfileResponse>() {
            @Override
            public void onResponse(Call<ProfileResponse> call, Response<ProfileResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    ProfileResponse.UserData data = response.body().getData();
                    sessionManager.saveUserInfo(data.getName(), data.getEmail(), data.getPhone());
                    sessionManager.savePhotoUrl(data.getPhotoProfile());

                    if (etNama.getText().toString().equals(sessionManager.getUserName())) etNama.setText(data.getName());
                    if (etPhone.getText().toString().equals(sessionManager.getUserPhone())) etPhone.setText(data.getPhone());
                    if (photoFile == null) handleProfileImage(data.getPhotoProfile());
                }
            }
            @Override
            public void onFailure(Call<ProfileResponse> call, Throwable t) { Log.e("SYNC_ERROR", t.getMessage()); }
        });
    }

    private void bukaGaleri() {
        ImagePicker.with(this).cropSquare().compress(1024).maxResultSize(1080, 1080).start();
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, @Nullable Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        if (resultCode == Activity.RESULT_OK && data != null) {
            Uri fileUri = data.getData();
            photoFile = new File(fileUri.getPath());
            Glide.with(this).load(photoFile).circleCrop().into(imgProfile);
            imgProfile.setPadding(0, 0, 0, 0);
        }
    }

    private void validasiDanSimpan() {
        String name = etNama.getText().toString().trim();
        String phone = etPhone.getText().toString().trim();
        if (name.isEmpty()) {
            etNama.setError("Nama tidak boleh kosong");
            return;
        }
        simpanKeServer(name, phone);
    }

    private void simpanKeServer(String name, String phone) {
        btnSimpan.setEnabled(false);
        btnSimpan.setText("Menyimpan...");

        // 1. Buat Body untuk teks
        RequestBody rbName = RequestBody.create(MediaType.parse("text/plain"), name);
        RequestBody rbPhone = RequestBody.create(MediaType.parse("text/plain"), phone);

        // 2. Buat Body untuk file (Hanya jika ada foto baru)
        MultipartBody.Part partPhoto = null;
        if (photoFile != null) {
            RequestBody requestFile = RequestBody.create(MediaType.parse("image/*"), photoFile);
            partPhoto = MultipartBody.Part.createFormData("photo_profile", photoFile.getName(), requestFile);
        }

        String token = "Bearer " + sessionManager.getToken();

        // 3. Panggil API (Sekarang hanya 4 parameter karena _method dihapus)
        ApiClient.getClient().create(ApiService.class)
                .updateProfileBuyers(token, rbName, rbPhone, partPhoto)
                .enqueue(new Callback<ProfileResponse>() {
                    @Override
                    public void onResponse(Call<ProfileResponse> call, Response<ProfileResponse> response) {
                        btnSimpan.setEnabled(true);
                        btnSimpan.setText("Simpan Perubahan");

                        if (response.isSuccessful()) {
                            Toast.makeText(UbahProfilPelangganActivity.this, "Profil berhasil diperbarui!", Toast.LENGTH_SHORT).show();
                            // Update session lokal agar saat balik ke halaman sebelumnya data sudah baru
                            if (response.body() != null) {
                                ProfileResponse.UserData d = response.body().getData();
                                sessionManager.saveUserInfo(d.getName(), d.getEmail(), d.getPhone());
                                sessionManager.savePhotoUrl(d.getPhotoProfile());
                            }
                            finish();
                        } else {
                            Log.e("API_ERROR", "Status: " + response.code());
                            Toast.makeText(UbahProfilPelangganActivity.this, "Gagal: " + response.code(), Toast.LENGTH_SHORT).show();
                        }
                    }

                    @Override
                    public void onFailure(Call<ProfileResponse> call, Throwable t) {
                        btnSimpan.setEnabled(true);
                        btnSimpan.setText("Simpan Perubahan");
                        Log.e("API_FAILURE", t.getMessage());
                        Toast.makeText(UbahProfilPelangganActivity.this, "Kesalahan Jaringan", Toast.LENGTH_SHORT).show();
                    }
                });
    }
}