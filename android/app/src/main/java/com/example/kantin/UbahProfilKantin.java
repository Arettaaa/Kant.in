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
        setupListeners();

        // Memulai rentetan Load Data: Kantin dulu, baru Profil Admin
        loadCanteenSettings();
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

        // KUNCI Input yang tidak boleh diedit oleh Admin Kantin
        etCanteenName.setEnabled(false);
        etCanteenLocation.setEnabled(false);
        etAdminEmail.setEnabled(false);
    }

    // ==========================================================
    // BAGIAN GET DATA (READ)
    // ==========================================================

    private void loadCanteenSettings() {
        String canteenId = sessionManager.getCanteenId();
        if (canteenId == null || canteenId.isEmpty()) return;

        apiService.getCanteenSettings(canteenId).enqueue(new Callback<CanteenDetailResponse>() {
            @Override
            public void onResponse(@NonNull Call<CanteenDetailResponse> call, @NonNull Response<CanteenDetailResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
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

                        if (data.getImage() != null) {
                            Glide.with(UbahProfilKantin.this).load(data.getImage()).into(ivCanteenLogo);
                        }
                        if (data.getQrisUrl() != null) {
                            ivQrisImage.clearColorFilter();
                            Glide.with(UbahProfilKantin.this).load(data.getQrisUrl()).into(ivQrisImage);
                        }
                    }
                }

                // 2. Setelah selesai data Kantin, ambil data Profil Admin
                loadAdminProfile();
            }

            @Override
            public void onFailure(@NonNull Call<CanteenDetailResponse> call, @NonNull Throwable t) {
                Log.e("API_ERROR", "Gagal load kantin: " + t.getMessage());
                // Tetap coba load profil jika kantin gagal
                loadAdminProfile();
            }
        });
    }

    private void loadAdminProfile() {
        // Mengambil profil menggunakan endpoint yang sudah temanmu kerjakan
        apiService.getProfile().enqueue(new Callback<ProfileAdminResponse>() {
            @Override
            public void onResponse(@NonNull Call<ProfileAdminResponse> call, @NonNull Response<ProfileAdminResponse> response) {
                if (response.isSuccessful() && response.body() != null && response.body().isSuccess()) {
                    ProfileAdminResponse.AdminProfile admin = response.body().getData();
                    if (admin != null) {
                        etAdminName.setText(admin.getName());
                        etAdminPhone.setText(admin.getPhone());
                        etAdminEmail.setText(admin.getEmail());

                        if (admin.getPhotoProfile() != null && !admin.getPhotoProfile().isEmpty()) {
                            Glide.with(UbahProfilKantin.this)
                                    .load(admin.getPhotoProfile())
                                    .circleCrop()
                                    .placeholder(R.drawable.avatar)
                                    .into(ivAdminPhoto);
                        }
                    }
                }
            }

            @Override
            public void onFailure(@NonNull Call<ProfileAdminResponse> call, @NonNull Throwable t) {
                Log.e("API_ERROR", "Gagal load profil: " + t.getMessage());
            }
        });
    }


    // ==========================================================
    // BAGIAN EVENT LISTENER & IMAGE PICKER
    // ==========================================================

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

        ImagePicker.Builder picker = ImagePicker.with(this)
                .compress(1024)            // Kompres ukuran agar tidak terlalu besar di server
                .maxResultSize(1080, 1080) // Maksimal resolusi HD
                .galleryOnly();            // (Opsional) Jika ingin langsung buka galeri tanpa opsi kamera, atau hapus baris ini jika ingin ada opsi jepret kamera

        if (type == TYPE_LOGO || type == TYPE_ADMIN) {
            // Untuk Logo dan Profil, paksa pengguna memotong dalam bentuk kotak (1:1)
            picker.cropSquare();
        } else if (type == TYPE_QRIS) {
            // Untuk QRIS, gunakan crop() biasa.
            // Ini akan membuka halaman editor dimana pengguna bisa:
            // - Menggeser foto (Pan)
            // - Mencubit layar untuk memperbesar/memperkecil foto (Zoom)
            // - Menarik sudut-sudut kotak pemotong secara bebas (Free Form)
            picker.crop();
        }

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


    // ==========================================================
    // BAGIAN SIMPAN DATA (POST/PUT BERUNTUN)
    // ==========================================================

    @SuppressWarnings("SetTextI18n")
    private void validasiDanSimpan() {
        // Karena Canteen Name & Location dikunci, kita hanya memvalidasi Profil saja.
        String aName = etAdminName.getText().toString().trim();
        String aPhone = etAdminPhone.getText().toString().trim();

        if (aName.isEmpty()) { etAdminName.setError("Nama wajib diisi"); etAdminName.requestFocus(); return; }

        btnSubmitAll.setEnabled(false);
        btnSubmitAll.setText("Menyimpan...");

        // Panggil fungsi simpan kantin terlebih dahulu
        simpanKantinKeServer(aName, aPhone);
    }

    @SuppressWarnings({"deprecation", "SetTextI18n"})
    private void simpanKantinKeServer(String aName, String aPhone) {
        // 1. Settings Kantin butuh _method = PUT
        RequestBody methodPut = RequestBody.create(MediaType.parse("text/plain"), "PUT");
        RequestBody desc = RequestBody.create(MediaType.parse("text/plain"), etCanteenDescription.getText().toString());
        RequestBody fee = RequestBody.create(MediaType.parse("text/plain"), etDeliveryFee.getText().toString());
        RequestBody open = RequestBody.create(MediaType.parse("text/plain"), etOpenTime.getText().toString());
        RequestBody close = RequestBody.create(MediaType.parse("text/plain"), etCloseTime.getText().toString());

        // Canteen Controller menerima parameter 'phone' tapi di XML kita pakai phone untuk Admin.
        // Kita kirim string kosong atau null agar tidak error di API Kantin.
        RequestBody phoneKantin = RequestBody.create(MediaType.parse("text/plain"), "");

        MultipartBody.Part partLogo = prepareFilePart("image", fileLogo);
        MultipartBody.Part partQris = prepareFilePart("qris_image", fileQris);

        apiService.updateCanteenSettings(sessionManager.getCanteenId(), methodPut, desc, phoneKantin, fee, open, close, partLogo, partQris)
                .enqueue(new Callback<CanteenDetailResponse>() {
                    @Override
                    public void onResponse(@NonNull Call<CanteenDetailResponse> call, @NonNull Response<CanteenDetailResponse> response) {
                        if (response.isSuccessful()) {
                            // 2. Jika sukses, lanjut simpan profil admin
                            simpanProfilKeServer(aName, aPhone);
                        } else {
                            btnSubmitAll.setEnabled(true);
                            btnSubmitAll.setText("Simpan Perubahan");
                            Toast.makeText(UbahProfilKantin.this, "Gagal menyimpan data kantin", Toast.LENGTH_SHORT).show();
                        }
                    }

                    @Override
                    public void onFailure(@NonNull Call<CanteenDetailResponse> call, @NonNull Throwable t) {
                        btnSubmitAll.setEnabled(true);
                        btnSubmitAll.setText("Simpan Perubahan");
                        Toast.makeText(UbahProfilKantin.this, "Error koneksi kantin", Toast.LENGTH_SHORT).show();
                    }
                });
    }

    @SuppressWarnings({"deprecation", "SetTextI18n"})
    private void simpanProfilKeServer(String name, String phone) {
        // Profile Controller menggunakan Route::post, sehingga TIDAK PERLU _method: PUT
        RequestBody rbName    = RequestBody.create(MediaType.parse("text/plain"), name);
        RequestBody rbPhone   = RequestBody.create(MediaType.parse("text/plain"), phone);

        MultipartBody.Part partPhoto = prepareFilePart("photo_profile", fileAdmin);

        // Memanggil endpoint milik temanmu
        apiService.updateProfile(rbName, rbPhone, partPhoto)
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
                            Toast.makeText(UbahProfilKantin.this, "Data Kantin & Profil diperbarui!", Toast.LENGTH_SHORT).show();
                            finish();
                        } else {
                            Toast.makeText(UbahProfilKantin.this, "Settings Kantin berhasil, tapi Profil gagal diperbarui", Toast.LENGTH_LONG).show();
                        }
                    }

                    @Override
                    public void onFailure(@NonNull Call<ProfileAdminResponse> call, @NonNull Throwable t) {
                        btnSubmitAll.setEnabled(true);
                        btnSubmitAll.setText("Simpan Perubahan");
                        Toast.makeText(UbahProfilKantin.this, "Kesalahan jaringan saat update profil", Toast.LENGTH_SHORT).show();
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