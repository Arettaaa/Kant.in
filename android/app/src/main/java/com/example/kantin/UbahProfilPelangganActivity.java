package com.example.kantin;

import android.app.Activity;
import android.content.Intent;
import android.graphics.Color;
import android.net.Uri;
import android.os.Bundle;
import android.util.Log;
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

    // Pastikan URL Ngrok ini sesuai dengan yang sedang jalan
    private final String BASE_URL_STORAGE = "https://nonephemerally-nonrevolving-judie.ngrok-free.dev/storage/";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getSupportActionBar() != null) getSupportActionBar().hide();
        setContentView(R.layout.activity_ubahprofilpelanggan);

        sessionManager = new SessionManager(this);
        initViews();

        // 1. Load data dari session
        loadDataAwal();

        // 2. Sync data terbaru dari server
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
    }

    private void loadDataAwal() {
        etNama.setText(sessionManager.getUserName());
        etEmail.setText(sessionManager.getUserEmail());
        etPhone.setText(sessionManager.getUserPhone());
        handleProfileImage(sessionManager.getPhotoUrl());
    }

    private void handleProfileImage(String path) {
        if (path != null && !path.isEmpty()) {
            imgProfile.clearColorFilter();
            imgProfile.setPadding(0, 0, 0, 0);
            String fullUrl = path.startsWith("http") ? path : BASE_URL_STORAGE + path;
            Glide.with(this).load(fullUrl).circleCrop().into(imgProfile);
        } else {
            imgProfile.setImageResource(R.drawable.user);
            int p = (int) (20 * getResources().getDisplayMetrics().density);
            imgProfile.setPadding(p, p, p, p);
            imgProfile.setColorFilter(Color.parseColor("#F97316"));
        }
    }

    private void syncDataFromServer() {
        String token = "Bearer " + sessionManager.getToken();
        ApiClient.getClient().create(ApiService.class).getBuyerProfile(token).enqueue(new Callback<ProfileResponse>() {
            @Override
            public void onResponse(Call<ProfileResponse> call, Response<ProfileResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    ProfileResponse.UserData data = response.body().getData();
                    etNama.setText(data.getName());
                    etPhone.setText(data.getPhone());
                    sessionManager.saveUserInfo(data.getName(), data.getEmail(), data.getPhone());
                    sessionManager.savePhotoUrl(data.getPhotoProfile());
                    handleProfileImage(data.getPhotoProfile());
                }
            }
            @Override
            public void onFailure(Call<ProfileResponse> call, Throwable t) {
                Log.e("SYNC_ERROR", t.getMessage());
            }
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
            imgProfile.clearColorFilter();
            imgProfile.setPadding(0, 0, 0, 0);
            Glide.with(this).load(photoFile).circleCrop().into(imgProfile);
        } else if (resultCode == ImagePicker.RESULT_ERROR) {
            Toast.makeText(this, ImagePicker.getError(data), Toast.LENGTH_SHORT).show();
        }
    }

    private void validasiDanSimpan() {
        String name = etNama.getText().toString().trim();
        if (name.isEmpty()) {
            etNama.setError("Nama tidak boleh kosong");
            return;
        }
        simpanKeServer(name, etPhone.getText().toString().trim());
    }

    private void simpanKeServer(String name, String phone) {
        btnSimpan.setEnabled(false);
        btnSimpan.setText("Menyimpan...");

        RequestBody rbName  = RequestBody.create(MediaType.parse("text/plain"), name);
        RequestBody rbPhone = RequestBody.create(MediaType.parse("text/plain"), phone);

        MultipartBody.Part partPhoto = null;
        if (photoFile != null) {
            RequestBody requestFile = RequestBody.create(MediaType.parse("image/*"), photoFile);
            partPhoto = MultipartBody.Part.createFormData("photo_profile", photoFile.getName(), requestFile);
        }

        String token = "Bearer " + sessionManager.getToken();
        ApiClient.getClient().create(ApiService.class)
                .updateProfileBuyers(token, rbName, rbPhone, partPhoto)
                .enqueue(new Callback<ProfileResponse>() {
                    @Override
                    public void onResponse(Call<ProfileResponse> call, Response<ProfileResponse> response) {
                        btnSimpan.setEnabled(true);
                        btnSimpan.setText("Simpan Perubahan");

                        if (response.isSuccessful() && response.body() != null) {
                            Toast.makeText(UbahProfilPelangganActivity.this, "Update Berhasil!", Toast.LENGTH_SHORT).show();
                            ProfileResponse.UserData data = response.body().getData();
                            sessionManager.saveUserInfo(data.getName(), data.getEmail(), data.getPhone());
                            sessionManager.savePhotoUrl(data.getPhotoProfile());
                            finish();
                        } else {
                            try {
                                String errBody = response.errorBody().string();
                                Log.e("API_ERROR", errBody);
                                Toast.makeText(UbahProfilPelangganActivity.this, "Gagal: " + response.code(), Toast.LENGTH_LONG).show();
                            } catch (Exception e) { e.printStackTrace(); }
                        }
                    }

                    @Override
                    public void onFailure(Call<ProfileResponse> call, Throwable t) {
                        btnSimpan.setEnabled(true);
                        btnSimpan.setText("Simpan Perubahan");
                        Toast.makeText(UbahProfilPelangganActivity.this, "Error: " + t.getMessage(), Toast.LENGTH_SHORT).show();
                    }
                });
    }
}