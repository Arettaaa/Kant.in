package com.example.kantin;

import android.app.Activity;
import android.app.TimePickerDialog;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.util.Log;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.AppCompatButton;
import androidx.cardview.widget.CardView;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

import com.bumptech.glide.Glide;
import com.example.kantin.model.response.BaseResponse;
import com.example.kantin.model.response.CanteenDetailResponse;
import com.example.kantin.model.response.ProfileAdminResponse;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;
import com.example.kantin.utils.SessionManager;
import com.github.dhaval2404.imagepicker.ImagePicker;

import java.io.File;
import java.util.Calendar;
import java.util.Locale;

import okhttp3.MediaType;
import okhttp3.MultipartBody;
import okhttp3.RequestBody;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class UbahProfilKantin extends AppCompatActivity {

    private ImageView ivCanteenLogo, btnEditCanteenLogo, ivQrisImage;
    private EditText etCanteenName, etCanteenLocation, etCanteenDescription, etDeliveryFee, etOpenTime, etCloseTime;
    private LinearLayout btnUploadQris;

    private ImageView ivAdminPhoto, btnEditAdminPhoto;
    private EditText etAdminName, etAdminEmail, etAdminPhone;

    private AppCompatButton btnSubmitAll;
    private CardView btnBack;

    private SessionManager sessionManager;
    private ApiService apiService;

    private File fileLogo = null;
    private File fileQris = null;
    private File fileAdmin = null;

    private static final int TYPE_LOGO = 1;
    private static final int TYPE_QRIS = 2;
    private static final int TYPE_ADMIN = 3;
    private int currentImageType = 0;

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
        ivCanteenLogo = findViewById(R.id.ivCanteenLogo);
        btnEditCanteenLogo = findViewById(R.id.btnEditCanteenLogo);
        ivQrisImage = findViewById(R.id.ivQrisImage);
        btnUploadQris = findViewById(R.id.btnUploadQris);
        etCanteenName = findViewById(R.id.etCanteenName);
        etCanteenLocation = findViewById(R.id.etCanteenLocation);
        etCanteenDescription = findViewById(R.id.etCanteenDescription);
        etDeliveryFee = findViewById(R.id.etDeliveryFee);
        etOpenTime = findViewById(R.id.etOpenTime);
        etCloseTime = findViewById(R.id.etCloseTime);

        ivAdminPhoto = findViewById(R.id.ivAdminPhoto);
        btnEditAdminPhoto = findViewById(R.id.btnEditAdminPhoto);
        etAdminName = findViewById(R.id.etAdminName);
        etAdminEmail = findViewById(R.id.etAdminEmail);
        etAdminPhone = findViewById(R.id.etAdminPhone);

        btnSubmitAll = findViewById(R.id.btnSubmitAll);
        btnBack = findViewById(R.id.btnBack);

        etAdminEmail.setEnabled(false);
    }

    private void loadDataAwal() {
        etAdminName.setText(sessionManager.getUserName());
        etAdminEmail.setText(sessionManager.getUserEmail());
        etAdminPhone.setText(sessionManager.getUserPhone());

        String photoUrl = sessionManager.getPhotoUrl();
        if (photoUrl != null && !photoUrl.isEmpty()) {
            Glide.with(this).load(photoUrl).circleCrop().placeholder(R.drawable.avatar).into(ivAdminPhoto);
        }
    }

    private void syncDataFromServer() {
        String canteenId = sessionManager.getCanteenId();
        if (canteenId != null && !canteenId.isEmpty()) {
            apiService.getCanteenDetail(canteenId).enqueue(new Callback<CanteenDetailResponse>() {
                @Override
                public void onResponse(@NonNull Call<CanteenDetailResponse> call, @NonNull Response<CanteenDetailResponse> response) {
                    if (response.isSuccessful() && response.body() != null) {
                        // Menggunakan CanteenDetail sesuai struktur modelmu
                        CanteenDetailResponse.CanteenDetail data = response.body().getData();
                        if (data != null) {
                            etCanteenName.setText(data.getName());
                            etCanteenLocation.setText(data.getLocation());
                            etCanteenDescription.setText(data.getDescription());
                            etDeliveryFee.setText(String.valueOf((int) data.getDeliveryFeeFlat()));

                            if (data.getOperatingHours() != null) {
                                etOpenTime.setText(data.getOperatingHours().getOpen());
                                etCloseTime.setText(data.getOperatingHours().getClose());
                            }

                            // Menggunakan getImage() dan getQrisUrl()
                            if (fileLogo == null && data.getImage() != null) {
                                Glide.with(UbahProfilKantin.this).load(data.getImage()).into(ivCanteenLogo);
                            }

                            if (fileQris == null && data.getQrisUrl() != null) {
                                ivQrisImage.clearColorFilter();
                                Glide.with(UbahProfilKantin.this).load(data.getQrisUrl()).into(ivQrisImage);
                            }
                        }
                    }
                }

                @Override
                public void onFailure(@NonNull Call<CanteenDetailResponse> call, @NonNull Throwable t) {
                    Log.e("SYNC_CANTEEN", t.getMessage() != null ? t.getMessage() : "Unknown error");
                }
            });
        }
    }

    private void setupListeners() {
        btnBack.setOnClickListener(v -> getOnBackPressedDispatcher().onBackPressed());

        etOpenTime.setOnClickListener(v -> showTimePicker(etOpenTime));
        etCloseTime.setOnClickListener(v -> showTimePicker(etCloseTime));

        btnEditCanteenLogo.setOnClickListener(v -> bukaGaleri(TYPE_LOGO));
        ivCanteenLogo.setOnClickListener(v -> bukaGaleri(TYPE_LOGO));
        btnUploadQris.setOnClickListener(v -> bukaGaleri(TYPE_QRIS));
        btnEditAdminPhoto.setOnClickListener(v -> bukaGaleri(TYPE_ADMIN));
        ivAdminPhoto.setOnClickListener(v -> bukaGaleri(TYPE_ADMIN));

        btnSubmitAll.setOnClickListener(v -> validasiDanSimpan());
    }

    private void showTimePicker(EditText target) {
        Calendar c = Calendar.getInstance();
        new TimePickerDialog(this, (view, hourOfDay, minute) -> {
            target.setText(String.format(Locale.getDefault(), "%02d:%02d", hourOfDay, minute));
        }, c.get(Calendar.HOUR_OF_DAY), c.get(Calendar.MINUTE), true).show();
    }

    private void bukaGaleri(int type) {
        currentImageType = type;
        ImagePicker.Builder picker = ImagePicker.with(this).compress(1024).maxResultSize(1080, 1080);
        if (type == TYPE_LOGO || type == TYPE_ADMIN) picker.cropSquare();
        else picker.crop();
        picker.start();
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, @Nullable Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        if (resultCode == Activity.RESULT_OK && data != null) {
            Uri fileUri = data.getData();
            if (fileUri != null && fileUri.getPath() != null) {
                File file = new File(fileUri.getPath());

                if (currentImageType == TYPE_LOGO) {
                    fileLogo = file;
                    Glide.with(this).load(fileLogo).into(ivCanteenLogo);
                } else if (currentImageType == TYPE_QRIS) {
                    fileQris = file;
                    ivQrisImage.clearColorFilter();
                    Glide.with(this).load(fileQris).into(ivQrisImage);
                } else if (currentImageType == TYPE_ADMIN) {
                    fileAdmin = file;
                    Glide.with(this).load(fileAdmin).circleCrop().into(ivAdminPhoto);
                }
            }
        }
    }

    @SuppressWarnings("SetTextI18n")
    private void validasiDanSimpan() {
        String cName = etCanteenName.getText().toString().trim();
        String cLoc = etCanteenLocation.getText().toString().trim();
        String aName = etAdminName.getText().toString().trim();
        String aPhone = etAdminPhone.getText().toString().trim();

        if (cName.isEmpty()) { etCanteenName.setError("Wajib diisi"); etCanteenName.requestFocus(); return; }
        if (cLoc.isEmpty()) { etCanteenLocation.setError("Wajib diisi"); etCanteenLocation.requestFocus(); return; }
        if (aName.isEmpty()) { etAdminName.setError("Wajib diisi"); etAdminName.requestFocus(); return; }

        btnSubmitAll.setEnabled(false);
        btnSubmitAll.setText("Menyimpan...");

        simpanKantinKeServer(cName, cLoc, aName, aPhone);
    }

    @SuppressWarnings({"deprecation", "SetTextI18n"})
    private void simpanKantinKeServer(String cName, String cLoc, String aName, String aPhone) {
        RequestBody name = RequestBody.create(MediaType.parse("text/plain"), cName);
        RequestBody loc = RequestBody.create(MediaType.parse("text/plain"), cLoc);
        RequestBody desc = RequestBody.create(MediaType.parse("text/plain"), etCanteenDescription.getText().toString());
        RequestBody fee = RequestBody.create(MediaType.parse("text/plain"), etDeliveryFee.getText().toString());
        RequestBody open = RequestBody.create(MediaType.parse("text/plain"), etOpenTime.getText().toString());
        RequestBody close = RequestBody.create(MediaType.parse("text/plain"), etCloseTime.getText().toString());

        MultipartBody.Part partLogo = prepareFilePart("image", fileLogo);
        MultipartBody.Part partQris = prepareFilePart("qris_image", fileQris);

        apiService.updateCanteen(sessionManager.getCanteenId(), name, loc, desc, fee, open, close, partLogo, partQris)
                .enqueue(new Callback<BaseResponse>() {
                    @Override
                    public void onResponse(@NonNull Call<BaseResponse> call, @NonNull Response<BaseResponse> response) {
                        if (response.isSuccessful()) {
                            simpanProfilKeServer(aName, aPhone);
                        } else {
                            btnSubmitAll.setEnabled(true);
                            btnSubmitAll.setText("Simpan Perubahan");
                            Toast.makeText(UbahProfilKantin.this, "Gagal simpan data kantin", Toast.LENGTH_SHORT).show();
                        }
                    }

                    @Override
                    public void onFailure(@NonNull Call<BaseResponse> call, @NonNull Throwable t) {
                        btnSubmitAll.setEnabled(true);
                        btnSubmitAll.setText("Simpan Perubahan");
                        Toast.makeText(UbahProfilKantin.this, "Error koneksi kantin", Toast.LENGTH_SHORT).show();
                    }
                });
    }

    @SuppressWarnings({"deprecation", "SetTextI18n"})
    private void simpanProfilKeServer(String name, String phone) {
        RequestBody methodPut = RequestBody.create(MediaType.parse("text/plain"), "PUT");
        RequestBody rbName    = RequestBody.create(MediaType.parse("text/plain"), name);
        RequestBody rbPhone   = RequestBody.create(MediaType.parse("text/plain"), phone);

        MultipartBody.Part partPhoto = prepareFilePart("photo_profile", fileAdmin);

        apiService.updateProfile(methodPut, rbName, rbPhone, partPhoto)
                .enqueue(new Callback<ProfileAdminResponse>() {
                    @Override
                    public void onResponse(@NonNull Call<ProfileAdminResponse> call, @NonNull Response<ProfileAdminResponse> response) {
                        btnSubmitAll.setEnabled(true);
                        btnSubmitAll.setText("Simpan Perubahan");

                        if (response.isSuccessful() && response.body() != null && response.body().isSuccess()) {
                            ProfileAdminResponse.AdminProfile data = response.body().getData();
                            if (data != null) {
                                sessionManager.saveUserInfo(data.getName(), data.getEmail(), data.getPhone());
                                sessionManager.savePhotoUrl(data.getPhotoProfile());
                            }
                            Toast.makeText(UbahProfilKantin.this, "Kantin & Profil diperbarui!", Toast.LENGTH_SHORT).show();
                            finish();
                        } else {
                            Toast.makeText(UbahProfilKantin.this, "Gagal menyimpan profil admin", Toast.LENGTH_SHORT).show();
                        }
                    }

                    @Override
                    public void onFailure(@NonNull Call<ProfileAdminResponse> call, @NonNull Throwable t) {
                        btnSubmitAll.setEnabled(true);
                        btnSubmitAll.setText("Simpan Perubahan");
                        Toast.makeText(UbahProfilKantin.this, "Kesalahan jaringan pada profil", Toast.LENGTH_SHORT).show();
                    }
                });
    }

    @SuppressWarnings("deprecation")
    private MultipartBody.Part prepareFilePart(String partName, File file) {
        if (file == null) return null;
        RequestBody requestFile = RequestBody.create(MediaType.parse("image/*"), file);
        return MultipartBody.Part.createFormData(partName, file.getName(), requestFile);
    }
}