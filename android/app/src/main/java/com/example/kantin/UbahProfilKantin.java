package com.example.kantin;

import android.app.Activity;
import android.app.TimePickerDialog;
import android.content.Intent;
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
import java.io.IOException;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.List;
import java.util.Locale;

import okhttp3.MediaType;
import okhttp3.MultipartBody;
import okhttp3.RequestBody;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class UbahProfilKantin extends AppCompatActivity {

    private static final String TAG = "UbahProfilKantin";

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

    private static final int TYPE_LOGO  = 1;
    private static final int TYPE_QRIS  = 2;
    private static final int TYPE_ADMIN = 3;
    private int currentImageType = 0;

    // ==========================================================
    // LIFECYCLE
    // ==========================================================

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
        apiService     = ApiClient.getAuthClient(sessionManager.getToken()).create(ApiService.class);

        initViews();
        setupListeners();
        loadCanteenSettings();
    }

    // ==========================================================
    // INIT VIEWS
    // ==========================================================

    private void initViews() {
        ivCanteenLogo        = findViewById(R.id.ivCanteenLogo);
        btnEditCanteenLogo   = findViewById(R.id.btnEditCanteenLogo);
        ivQrisImage          = findViewById(R.id.ivQrisImage);
        btnUploadQris        = findViewById(R.id.btnUploadQris);
        etCanteenName        = findViewById(R.id.etCanteenName);
        etCanteenLocation    = findViewById(R.id.etCanteenLocation);
        etCanteenDescription = findViewById(R.id.etCanteenDescription);
        etDeliveryFee        = findViewById(R.id.etDeliveryFee);
        etOpenTime           = findViewById(R.id.etOpenTime);
        etCloseTime          = findViewById(R.id.etCloseTime);

        ivAdminPhoto         = findViewById(R.id.ivAdminPhoto);
        btnEditAdminPhoto    = findViewById(R.id.btnEditAdminPhoto);
        etAdminName          = findViewById(R.id.etAdminName);
        etAdminEmail         = findViewById(R.id.etAdminEmail);
        etAdminPhone         = findViewById(R.id.etAdminPhone);

        btnSubmitAll         = findViewById(R.id.btnSubmitAll);
        btnBack              = findViewById(R.id.btnBack);

        // Field yang tidak boleh diedit
        etCanteenName.setEnabled(false);
        etCanteenLocation.setEnabled(false);
        etAdminEmail.setEnabled(false);
    }

    // ==========================================================
    // BAGIAN GET DATA (READ)
    // ==========================================================

    private void loadCanteenSettings() {
        String canteenId = sessionManager.getCanteenId();
        Log.d(TAG, "loadCanteenSettings() → canteenId: [" + canteenId + "]");

        if (canteenId == null || canteenId.isEmpty()) {
            Log.e(TAG, "loadCanteenSettings() → canteenId NULL atau kosong, batalkan load");
            return;
        }

        apiService.getCanteenSettings(canteenId).enqueue(new Callback<CanteenDetailResponse>() {
            @Override
            public void onResponse(@NonNull Call<CanteenDetailResponse> call,
                                   @NonNull Response<CanteenDetailResponse> response) {
                Log.d(TAG, "getCanteenSettings → HTTP " + response.code());

                if (response.isSuccessful() && response.body() != null) {
                    CanteenDetailResponse.CanteenDetail data = response.body().getData();
                    if (data != null) {
                        Log.d(TAG, "getCanteenSettings → SUKSES"
                                + " | name="  + data.getName()
                                + " | desc="  + data.getDescription()
                                + " | fee="   + data.getDeliveryFeeFlat()
                                + " | open="  + (data.getOperatingHours() != null ? data.getOperatingHours().getOpen()  : "null")
                                + " | close=" + (data.getOperatingHours() != null ? data.getOperatingHours().getClose() : "null")
                                + " | image=" + data.getImage()
                                + " | qris="  + data.getQrisUrl());

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
                    } else {
                        Log.w(TAG, "getCanteenSettings → data null di dalam body");
                    }
                } else {
                    try {
                        String errBody = response.errorBody() != null ? response.errorBody().string() : "null";
                        Log.e(TAG, "getCanteenSettings → GAGAL HTTP " + response.code() + " | body: " + errBody);
                    } catch (IOException e) {
                        Log.e(TAG, "getCanteenSettings → GAGAL HTTP " + response.code() + " | error baca errorBody: " + e.getMessage());
                    }
                }

                // Selalu lanjut load profil admin, apapun hasilnya
                loadAdminProfile();
            }

            @Override
            public void onFailure(@NonNull Call<CanteenDetailResponse> call, @NonNull Throwable t) {
                Log.e(TAG, "getCanteenSettings → onFailure: " + t.getMessage(), t);
                loadAdminProfile();
            }
        });
    }

    private void loadAdminProfile() {
        Log.d(TAG, "loadAdminProfile() → mulai request GET /admin/profiles");

        apiService.getProfile().enqueue(new Callback<ProfileAdminResponse>() {
            @Override
            public void onResponse(@NonNull Call<ProfileAdminResponse> call,
                                   @NonNull Response<ProfileAdminResponse> response) {
                Log.d(TAG, "getProfile → HTTP " + response.code());

                if (response.isSuccessful() && response.body() != null && response.body().isSuccess()) {
                    ProfileAdminResponse.AdminProfile admin = response.body().getData();
                    if (admin != null) {
                        Log.d(TAG, "getProfile → SUKSES"
                                + " | name="  + admin.getName()
                                + " | phone=" + admin.getPhone()
                                + " | photo=" + admin.getPhotoProfile());

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
                    } else {
                        Log.w(TAG, "getProfile → data admin null");
                    }
                } else {
                    try {
                        String errBody = response.errorBody() != null ? response.errorBody().string() : "null";
                        Log.e(TAG, "getProfile → GAGAL HTTP " + response.code() + " | body: " + errBody);
                    } catch (IOException e) {
                        Log.e(TAG, "getProfile → GAGAL, error baca errorBody: " + e.getMessage());
                    }
                }
            }

            @Override
            public void onFailure(@NonNull Call<ProfileAdminResponse> call, @NonNull Throwable t) {
                Log.e(TAG, "getProfile → onFailure: " + t.getMessage(), t);
            }
        });
    }

    // ==========================================================
    // BAGIAN EVENT LISTENER & IMAGE PICKER
    // ==========================================================

    private void setupListeners() {
        btnBack.setOnClickListener(v -> getOnBackPressedDispatcher().onBackPressed());

        etOpenTime.setOnClickListener(v  -> showTimePicker(etOpenTime));
        etCloseTime.setOnClickListener(v -> showTimePicker(etCloseTime));

        btnEditCanteenLogo.setOnClickListener(v -> bukaGaleri(TYPE_LOGO));
        ivCanteenLogo.setOnClickListener(v      -> bukaGaleri(TYPE_LOGO));
        btnUploadQris.setOnClickListener(v      -> bukaGaleri(TYPE_QRIS));
        btnEditAdminPhoto.setOnClickListener(v  -> bukaGaleri(TYPE_ADMIN));
        ivAdminPhoto.setOnClickListener(v       -> bukaGaleri(TYPE_ADMIN));

        btnSubmitAll.setOnClickListener(v -> validasiDanSimpan());
    }

    private void showTimePicker(EditText target) {
        Calendar c = Calendar.getInstance();
        new TimePickerDialog(this,
                (view, hourOfDay, minute) ->
                        target.setText(String.format(Locale.getDefault(), "%02d:%02d", hourOfDay, minute)),
                c.get(Calendar.HOUR_OF_DAY), c.get(Calendar.MINUTE), true
        ).show();
    }

    private void bukaGaleri(int type) {
        currentImageType = type;
        Log.d(TAG, "bukaGaleri() → type=" + type
                + " (" + (type == TYPE_LOGO ? "LOGO" : type == TYPE_QRIS ? "QRIS" : "ADMIN") + ")");

        ImagePicker.Builder picker = ImagePicker.with(this)
                .compress(1024)
                .maxResultSize(1080, 1080)
                .galleryOnly();

        if (type == TYPE_LOGO || type == TYPE_ADMIN) {
            picker.cropSquare();
        } else if (type == TYPE_QRIS) {
            picker.crop();
        }

        picker.start();
    }

    // ==========================================================
    // FIX UTAMA: onActivityResult
    //
    // MASALAH LAMA: new File(uri.getPath()) tidak valid di Android 10+
    // karena getData() mengembalikan content:// URI seperti:
    //   content://com.android.providers.media.documents/document/image:1234
    // Dipaksa jadi File → path "/document/image:1234" tidak ada di filesystem
    // → exists=false, size=0 → foto tidak pernah terupload meski 200 OK.
    //
    // FIX: ImagePicker menyimpan path file asli ke cache dan menaruh
    // path tersebut di Intent extras dengan key "extra_image_path".
    // Ini kompatibel dengan semua versi ImagePicker (tidak bergantung
    // pada Companion object yang berubah antar versi library).
    // ==========================================================

    @Override
    protected void onActivityResult(int requestCode, int resultCode, @Nullable Intent data) {
        super.onActivityResult(requestCode, resultCode, data);

        if (resultCode == Activity.RESULT_OK && data != null) {

            // Ambil path file dari extras — ImagePicker menulis path cache
            // ke key ini sehingga file bisa langsung dibaca sebagai File.
            String filePath = data.getStringExtra("extra_image_path");

            // Fallback jika extras tidak ada (ImagePicker versi lama)
            if (filePath == null && data.getData() != null) {
                filePath = data.getData().getPath();
                Log.w(TAG, "onActivityResult → extras kosong, fallback ke URI path: " + filePath);
            }

            if (filePath != null) {
                File file = new File(filePath);

                // Validasi wajib:
                // - exists() = true  → file benar-benar ada di storage device
                // - length() > 0     → file tidak kosong atau corrupt
                // Jika salah satu false, file tidak dikirim ke server.
                // Cek ini yang membedakan file valid vs file "fiktif" dari URI lama.
                boolean isValid = file.exists() && file.length() > 0;

                Log.d(TAG, "onActivityResult → type=" + currentImageType
                        + " | path="   + filePath
                        + " | exists=" + file.exists()   // harus: true
                        + " | size="   + file.length()   // harus: > 0
                        + " | valid="  + isValid);

                if (!isValid) {
                    Log.e(TAG, "onActivityResult → FILE TIDAK VALID!"
                            + " exists=" + file.exists()
                            + ", size="  + file.length()
                            + " → foto tidak akan diupload");
                    Toast.makeText(this, "Gagal membaca foto, coba pilih ulang", Toast.LENGTH_SHORT).show();
                    return;
                }

                // File valid, simpan ke variabel sesuai tipe
                if (currentImageType == TYPE_LOGO) {
                    fileLogo = file;
                    Glide.with(this).load(fileLogo).into(ivCanteenLogo);
                    Log.d(TAG, "onActivityResult → fileLogo diset | path=" + fileLogo.getAbsolutePath());

                } else if (currentImageType == TYPE_QRIS) {
                    fileQris = file;
                    ivQrisImage.clearColorFilter();
                    Glide.with(this).load(fileQris).into(ivQrisImage);
                    Log.d(TAG, "onActivityResult → fileQris diset | path=" + fileQris.getAbsolutePath());

                } else if (currentImageType == TYPE_ADMIN) {
                    fileAdmin = file;
                    Glide.with(this).load(fileAdmin).circleCrop().into(ivAdminPhoto);
                    Log.d(TAG, "onActivityResult → fileAdmin diset | path=" + fileAdmin.getAbsolutePath());
                }

            } else {
                Log.w(TAG, "onActivityResult → filePath NULL (user batal atau ImagePicker error)");
            }

        } else {
            Log.w(TAG, "onActivityResult → dibatalkan | resultCode=" + resultCode);
        }
    }

    // ==========================================================
    // BAGIAN SIMPAN DATA
    // ==========================================================

    @SuppressWarnings("SetTextI18n")
    private void validasiDanSimpan() {
        String aName  = etAdminName.getText().toString().trim();
        String aPhone = etAdminPhone.getText().toString().trim();

        Log.d(TAG, "validasiDanSimpan() → aName=" + aName + ", aPhone=" + aPhone);

        if (aName.isEmpty()) {
            etAdminName.setError("Nama wajib diisi");
            etAdminName.requestFocus();
            return;
        }

        btnSubmitAll.setEnabled(false);
        btnSubmitAll.setText("Menyimpan...");

        simpanKantinKeServer(aName, aPhone);
    }

    @SuppressWarnings("SetTextI18n")
    private void simpanKantinKeServer(String aName, String aPhone) {
        String canteenId = sessionManager.getCanteenId();
        String descVal   = etCanteenDescription.getText().toString();
        String feeVal    = etDeliveryFee.getText().toString();
        String openVal   = etOpenTime.getText().toString();
        String closeVal  = etCloseTime.getText().toString();

        Log.d(TAG, "simpanKantinKeServer() → canteenId=[" + canteenId + "]"
                + " | desc="     + descVal
                + " | fee="      + feeVal
                + " | open="     + openVal
                + " | close="    + closeVal
                + " | fileLogo=" + (fileLogo  != null ? fileLogo.getAbsolutePath()  + " (size=" + fileLogo.length()  + ")" : "null")
                + " | fileQris=" + (fileQris  != null ? fileQris.getAbsolutePath()  + " (size=" + fileQris.length()  + ")" : "null"));

        // _method=PUT wajib dikirim karena endpoint Laravel didefinisikan sebagai PUT,
        // tapi request dikirim sebagai POST agar PHP bisa membaca file multipart.
        // PHP tidak bisa baca $request->file() dari method PUT — ini limitasi PHP core.
        RequestBody method      = RequestBody.create(MediaType.parse("text/plain"), "PUT");
        RequestBody desc        = RequestBody.create(MediaType.parse("text/plain"), descVal);
        RequestBody fee         = RequestBody.create(MediaType.parse("text/plain"), feeVal);
        RequestBody open        = RequestBody.create(MediaType.parse("text/plain"), openVal);
        RequestBody close       = RequestBody.create(MediaType.parse("text/plain"), closeVal);
        RequestBody phoneKantin = RequestBody.create(MediaType.parse("text/plain"), "");

        List<MultipartBody.Part> files = new ArrayList<>();

        if (fileLogo != null) {
            if (fileLogo.exists() && fileLogo.length() > 0) {
                files.add(prepareFilePart("image", fileLogo));
                Log.d(TAG, "simpanKantinKeServer() → image ditambahkan ke multipart (size=" + fileLogo.length() + ")");
            } else {
                Log.e(TAG, "simpanKantinKeServer() → fileLogo TIDAK VALID, skip upload"
                        + " | exists=" + fileLogo.exists() + ", size=" + fileLogo.length());
            }
        }

        if (fileQris != null) {
            if (fileQris.exists() && fileQris.length() > 0) {
                files.add(prepareFilePart("qris_image", fileQris));
                Log.d(TAG, "simpanKantinKeServer() → qris_image ditambahkan ke multipart (size=" + fileQris.length() + ")");
            } else {
                Log.e(TAG, "simpanKantinKeServer() → fileQris TIDAK VALID, skip upload"
                        + " | exists=" + fileQris.exists() + ", size=" + fileQris.length());
            }
        }

        Log.d(TAG, "simpanKantinKeServer() → total files dikirim: " + files.size());

        apiService.updateCanteenSettings(canteenId, method, desc, phoneKantin, fee, open, close, files)
                .enqueue(new Callback<CanteenDetailResponse>() {
                    @Override
                    public void onResponse(@NonNull Call<CanteenDetailResponse> call,
                                           @NonNull Response<CanteenDetailResponse> response) {
                        Log.d(TAG, "updateCanteenSettings → HTTP " + response.code());

                        if (response.isSuccessful() && response.body() != null) {
                            CanteenDetailResponse.CanteenDetail updated = response.body().getData();
                            if (updated != null) {
                                Log.d(TAG, "updateCanteenSettings → SUKSES"
                                        + " | desc="  + updated.getDescription()
                                        + " | image=" + updated.getImage()
                                        + " | qris="  + updated.getQrisUrl());
                            }
                            // Lanjut simpan profil admin
                            simpanProfilKeServer(aName, aPhone);
                        } else {
                            try {
                                String errBody = response.errorBody() != null ? response.errorBody().string() : "null";
                                Log.e(TAG, "updateCanteenSettings → GAGAL HTTP " + response.code() + " | body: " + errBody);
                            } catch (IOException e) {
                                Log.e(TAG, "updateCanteenSettings → GAGAL, error baca errorBody: " + e.getMessage());
                            }
                            btnSubmitAll.setEnabled(true);
                            btnSubmitAll.setText("Simpan Perubahan");
                            Toast.makeText(UbahProfilKantin.this, "Gagal menyimpan data kantin", Toast.LENGTH_SHORT).show();
                        }
                    }

                    @Override
                    public void onFailure(@NonNull Call<CanteenDetailResponse> call, @NonNull Throwable t) {
                        Log.e(TAG, "updateCanteenSettings → onFailure: " + t.getMessage(), t);
                        btnSubmitAll.setEnabled(true);
                        btnSubmitAll.setText("Simpan Perubahan");
                        Toast.makeText(UbahProfilKantin.this, "Error koneksi kantin", Toast.LENGTH_SHORT).show();
                    }
                });
    }

    @SuppressWarnings("SetTextI18n")
    private void simpanProfilKeServer(String name, String phone) {
        Log.d(TAG, "simpanProfilKeServer() → name=" + name + ", phone=" + phone
                + " | fileAdmin=" + (fileAdmin != null
                ? fileAdmin.getAbsolutePath() + " (size=" + fileAdmin.length() + ")"
                : "null (tidak ada foto baru)"));

        RequestBody rbName  = RequestBody.create(MediaType.parse("text/plain"), name);
        RequestBody rbPhone = RequestBody.create(MediaType.parse("text/plain"), phone);

        // FIX: kirim sebagai List agar bisa kosong saat tidak ada foto baru.
        // Retrofit tidak bisa kirim null sebagai @Part — harus List kosong.
        List<MultipartBody.Part> photoParts = new ArrayList<>();
        if (fileAdmin != null) {
            if (fileAdmin.exists() && fileAdmin.length() > 0) {
                photoParts.add(prepareFilePart("photo_profile", fileAdmin));
                Log.d(TAG, "simpanProfilKeServer() → photo_profile ditambahkan ke multipart (size=" + fileAdmin.length() + ")");
            } else {
                Log.e(TAG, "simpanProfilKeServer() → fileAdmin TIDAK VALID, skip upload"
                        + " | exists=" + fileAdmin.exists() + ", size=" + fileAdmin.length());
            }
        } else {
            Log.d(TAG, "simpanProfilKeServer() → tidak ada foto baru, photo_profile tidak dikirim");
        }

        apiService.updateProfile(rbName, rbPhone, photoParts)
                .enqueue(new Callback<ProfileAdminResponse>() {
                    @Override
                    public void onResponse(@NonNull Call<ProfileAdminResponse> call,
                                           @NonNull Response<ProfileAdminResponse> response) {
                        Log.d(TAG, "updateProfile → HTTP " + response.code());

                        btnSubmitAll.setEnabled(true);
                        btnSubmitAll.setText("Simpan Perubahan");

                        if (response.isSuccessful() && response.body() != null && response.body().isSuccess()) {
                            ProfileAdminResponse.AdminProfile data = response.body().getData();
                            if (data != null) {
                                Log.d(TAG, "updateProfile → SUKSES"
                                        + " | name="  + data.getName()
                                        + " | phone=" + data.getPhone()
                                        + " | photo=" + data.getPhotoProfile());
                                sessionManager.saveUserInfo(data.getName(), data.getEmail(), data.getPhone());
                                sessionManager.savePhotoUrl(data.getPhotoProfile());
                            }
                            Toast.makeText(UbahProfilKantin.this, "Data Kantin & Profil diperbarui!", Toast.LENGTH_SHORT).show();
                            finish();
                        } else {
                            try {
                                String errBody = response.errorBody() != null ? response.errorBody().string() : "null";
                                Log.e(TAG, "updateProfile → GAGAL HTTP " + response.code() + " | body: " + errBody);
                            } catch (IOException e) {
                                Log.e(TAG, "updateProfile → GAGAL, error baca errorBody: " + e.getMessage());
                            }
                            Toast.makeText(UbahProfilKantin.this,
                                    "Settings Kantin berhasil, tapi Profil gagal diperbarui",
                                    Toast.LENGTH_LONG).show();
                        }
                    }

                    @Override
                    public void onFailure(@NonNull Call<ProfileAdminResponse> call, @NonNull Throwable t) {
                        Log.e(TAG, "updateProfile → onFailure: " + t.getMessage(), t);
                        btnSubmitAll.setEnabled(true);
                        btnSubmitAll.setText("Simpan Perubahan");
                        Toast.makeText(UbahProfilKantin.this, "Kesalahan jaringan saat update profil", Toast.LENGTH_SHORT).show();
                    }
                });
    }

    // ==========================================================
    // HELPER
    // ==========================================================

    private MultipartBody.Part prepareFilePart(String partName, File file) {
        RequestBody requestFile = RequestBody.create(MediaType.parse("image/*"), file);
        return MultipartBody.Part.createFormData(partName, file.getName(), requestFile);
    }
}