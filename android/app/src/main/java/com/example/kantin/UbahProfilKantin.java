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
import androidx.appcompat.widget.AppCompatButton;
import androidx.cardview.widget.CardView;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

import com.bumptech.glide.Glide;
import com.example.kantin.model.response.ProfileAdminResponse;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;
import com.example.kantin.utils.SessionManager;
import com.github.dhaval2404.imagepicker.ImagePicker;

import java.io.File;

import okhttp3.MediaType;
import okhttp3.MultipartBody;
import okhttp3.RequestBody;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class UbahProfilKantin extends AppCompatActivity {

    private ImageView ivAdminPhoto, btnEditAdminPhoto;
    private EditText etAdminName, etAdminEmail, etAdminPhone;
    private AppCompatButton btnSubmitAll;
    private CardView btnBack;

    private SessionManager sessionManager;
    private ApiService apiService;
    private File photoFile = null; // null = tidak ganti foto

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_ubah_profil_kantin);

        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.main), (v, insets) -> {
            Insets systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars());
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom);
            return insets;
        });

        sessionManager = new SessionManager(this);
        apiService = ApiClient.getAuthClient(sessionManager.getToken()).create(ApiService.class);

        initViews();
        loadDataAwal();
        syncDataFromServer();
        setupListeners();
    }

    private void initViews() {
        ivAdminPhoto      = findViewById(R.id.ivAdminPhoto);
        btnEditAdminPhoto = findViewById(R.id.btnEditAdminPhoto);
        etAdminName       = findViewById(R.id.etAdminName);
        etAdminEmail      = findViewById(R.id.etAdminEmail);
        etAdminPhone      = findViewById(R.id.etAdminPhone);
        btnSubmitAll      = findViewById(R.id.btnSubmitAll);
        btnBack           = findViewById(R.id.btnBack);

        etAdminEmail.setEnabled(false); // Email tidak bisa diubah
    }

    private void loadDataAwal() {
        etAdminName.setText(sessionManager.getUserName());
        etAdminEmail.setText(sessionManager.getUserEmail());
        etAdminPhone.setText(sessionManager.getUserPhone());

        String photoUrl = sessionManager.getPhotoUrl();
        if (photoUrl != null && !photoUrl.isEmpty()) {
            Glide.with(this).load(photoUrl).circleCrop()
                    .placeholder(R.drawable.avatar).into(ivAdminPhoto);
        }
    }

    private void syncDataFromServer() {
        apiService.getProfile().enqueue(new Callback<ProfileAdminResponse>() {
            @Override
            public void onResponse(Call<ProfileAdminResponse> call, Response<ProfileAdminResponse> response) {
                if (response.isSuccessful() && response.body() != null && response.body().isSuccess()) {
                    ProfileAdminResponse.AdminProfile data = response.body().getData();
                    if (data != null) {
                        sessionManager.saveUserInfo(data.getName(), data.getEmail(), data.getPhone());
                        sessionManager.savePhotoUrl(data.getPhotoProfile());

                        etAdminName.setText(data.getName());
                        etAdminEmail.setText(data.getEmail());
                        etAdminPhone.setText(data.getPhone());

                        // Load foto hanya jika user belum pilih foto baru
                        if (photoFile == null && data.getPhotoProfile() != null) {
                            Glide.with(UbahProfilKantin.this)
                                    .load(data.getPhotoProfile())
                                    .circleCrop()
                                    .placeholder(R.drawable.avatar)
                                    .into(ivAdminPhoto);
                        }
                    }
                }
            }

            @Override
            public void onFailure(Call<ProfileAdminResponse> call, Throwable t) {
                Log.e("SYNC_ERROR", t.getMessage());
            }
        });
    }

    private void setupListeners() {
        btnBack.setOnClickListener(v -> onBackPressed());
        ivAdminPhoto.setOnClickListener(v -> bukaGaleri());
        btnEditAdminPhoto.setOnClickListener(v -> bukaGaleri());
        btnSubmitAll.setOnClickListener(v -> validasiDanSimpan());
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
            Glide.with(this).load(photoFile).circleCrop().into(ivAdminPhoto);
        }
    }

    private void validasiDanSimpan() {
        String name  = etAdminName.getText().toString().trim();
        String phone = etAdminPhone.getText().toString().trim();

        if (name.isEmpty()) {
            etAdminName.setError("Nama tidak boleh kosong");
            etAdminName.requestFocus();
            return;
        }

        simpanKeServer(name, phone);
    }

    private void simpanKeServer(String name, String phone) {
        btnSubmitAll.setEnabled(false);
        btnSubmitAll.setText("Menyimpan...");

        // ✅ Admin kantin wajib kirim _method: PUT
        RequestBody methodPut = RequestBody.create(MediaType.parse("text/plain"), "PUT");
        RequestBody rbName    = RequestBody.create(MediaType.parse("text/plain"), name);
        RequestBody rbPhone   = RequestBody.create(MediaType.parse("text/plain"), phone);

        // Foto null = tidak ganti foto
        MultipartBody.Part partPhoto = null;
        if (photoFile != null) {
            RequestBody rbPhoto = RequestBody.create(MediaType.parse("image/*"), photoFile);
            partPhoto = MultipartBody.Part.createFormData("photo_profile", photoFile.getName(), rbPhoto);
        }

        apiService.updateProfile(methodPut, rbName, rbPhone, partPhoto)
                .enqueue(new Callback<ProfileAdminResponse>() {
                    @Override
                    public void onResponse(Call<ProfileAdminResponse> call, Response<ProfileAdminResponse> response) {
                        btnSubmitAll.setEnabled(true);
                        btnSubmitAll.setText("Simpan Perubahan");

                        if (response.isSuccessful() && response.body() != null && response.body().isSuccess()) {
                            ProfileAdminResponse.AdminProfile data = response.body().getData();
                            if (data != null) {
                                sessionManager.saveUserInfo(data.getName(), data.getEmail(), data.getPhone());
                                sessionManager.savePhotoUrl(data.getPhotoProfile());
                            }
                            Toast.makeText(UbahProfilKantin.this,
                                    "Profil berhasil diperbarui!", Toast.LENGTH_SHORT).show();
                            finish();
                        } else {
                            Log.e("API_ERROR", "Status: " + response.code());
                            Toast.makeText(UbahProfilKantin.this,
                                    "Gagal menyimpan: " + response.code(), Toast.LENGTH_SHORT).show();
                        }
                    }

                    @Override
                    public void onFailure(Call<ProfileAdminResponse> call, Throwable t) {
                        btnSubmitAll.setEnabled(true);
                        btnSubmitAll.setText("Simpan Perubahan");
                        Log.e("API_FAILURE", t.getMessage());
                        Toast.makeText(UbahProfilKantin.this,
                                "Kesalahan jaringan", Toast.LENGTH_SHORT).show();
                    }
                });
    }
}