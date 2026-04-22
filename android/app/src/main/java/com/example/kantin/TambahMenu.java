package com.example.kantin;

import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.Spinner;
import android.widget.Toast;

import androidx.activity.result.ActivityResultLauncher;
import androidx.activity.result.contract.ActivityResultContracts;
import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.cardview.widget.CardView;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

import com.example.kantin.model.response.MenuDetailResponse;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;
import com.example.kantin.utils.SessionManager;

import java.io.File;
import java.io.FileOutputStream;
import java.io.InputStream;

import okhttp3.MediaType;
import okhttp3.MultipartBody;
import okhttp3.RequestBody;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class TambahMenu extends AppCompatActivity {

    // UI Components
    private CardView btnBack, cardPhotoPreview, btnChangeFoto, btnSave;
    private ImageView ivPhotoPreview;
    private LinearLayout containerPhoto, layoutUploadPlaceholder;
    private EditText etName, etPrice, etCookingTime, etDescription;
    private Spinner spinnerCategory;
    private android.widget.CheckBox switchIsAvailable;

    // Data & Network
    private Uri selectedImageUri = null;
    private ApiService apiService;
    private SessionManager sessionManager;

    // Image Picker Launcher
    private final ActivityResultLauncher<Intent> imagePickerLauncher = registerForActivityResult(
            new ActivityResultContracts.StartActivityForResult(),
            result -> {
                if (result.getResultCode() == RESULT_OK && result.getData() != null) {
                    selectedImageUri = result.getData().getData();
                    showImagePreview();
                }
            }
    );

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_tambah_menu);

        // Pengaturan Window Insets (Agar UI rapi sampai ke ujung layar)
        View mainView = findViewById(android.R.id.content);
        if (mainView != null) {
            ViewCompat.setOnApplyWindowInsetsListener(mainView, (v, insets) -> {
                Insets systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars());
                v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom);
                return insets;
            });
        }

        sessionManager = new SessionManager(this);
        apiService = ApiClient.getAuthClient(sessionManager.getToken()).create(ApiService.class);

        initViews();
        setupListeners();
    }

    private void initViews() {
        btnBack = findViewById(R.id.btnBack);

        // Komponen Foto
        containerPhoto = findViewById(R.id.containerPhoto);
        layoutUploadPlaceholder = findViewById(R.id.layoutUploadPlaceholder);
        cardPhotoPreview = findViewById(R.id.cardPhotoPreview);
        ivPhotoPreview = findViewById(R.id.ivPhotoPreview);
        btnChangeFoto = findViewById(R.id.btnChangeFoto);

        // Komponen Input
        etName = findViewById(R.id.etEditMenuName);
        etPrice = findViewById(R.id.etEditPrice);
        spinnerCategory = findViewById(R.id.spinnerCategory);
        etCookingTime = findViewById(R.id.etEditCookingTime);
        etDescription = findViewById(R.id.etEditDescription);
        switchIsAvailable = findViewById(R.id.switchEditIsAvailable);

        btnSave = findViewById(R.id.btnSave);

        switchIsAvailable.setChecked(true); // Default menu tersedia
    }

    private void setupListeners() {
        btnBack.setOnClickListener(v -> finish());

        // Klik area foto kosong atau tombol edit foto
        containerPhoto.setOnClickListener(v -> openGallery());
        btnChangeFoto.setOnClickListener(v -> openGallery());

        // Tombol Simpan
        btnSave.setOnClickListener(v -> validateAndSubmit());
    }

    private void openGallery() {
        Intent intent = new Intent(Intent.ACTION_PICK);
        intent.setType("image/*");
        imagePickerLauncher.launch(intent);
    }

    private void showImagePreview() {
        if (selectedImageUri != null) {
            ivPhotoPreview.setImageURI(selectedImageUri);
            layoutUploadPlaceholder.setVisibility(View.GONE);
            cardPhotoPreview.setVisibility(View.VISIBLE);
            ivPhotoPreview.setVisibility(View.VISIBLE);
            btnChangeFoto.setVisibility(View.VISIBLE);
        }
    }

    private void validateAndSubmit() {
        String name = etName.getText().toString().trim();
        String price = etPrice.getText().toString().trim();
        String cookingTime = etCookingTime.getText().toString().trim();
        String description = etDescription.getText().toString().trim();
        String category = spinnerCategory.getSelectedItem().toString();

        // 1. Validasi Input Teks Kosong
        if (name.isEmpty() || price.isEmpty()) {
            Toast.makeText(this, "Nama dan Harga wajib diisi!", Toast.LENGTH_SHORT).show();
            return;
        }

        // 2. Validasi Dropdown Kategori belum dipilih
        if (category.equals("Pilih Kategori...")) {
            Toast.makeText(this, "Silakan pilih kategori menu terlebih dahulu!", Toast.LENGTH_SHORT).show();
            return;
        }

        uploadMenuToServer(name, price, category, cookingTime, description, switchIsAvailable.isChecked());
    }

    private void uploadMenuToServer(String name, String price, String category, String cookingTime, String description, boolean isAvailable) {
        String canteenId = sessionManager.getCanteenId();

        btnSave.setEnabled(false); // Matikan tombol sementara agar tidak dobel klik
        Toast.makeText(this, "Menyimpan menu...", Toast.LENGTH_SHORT).show();

        // Siapkan Data Teks
        RequestBody nameBody = RequestBody.create(name, MediaType.parse("text/plain"));
        RequestBody priceBody = RequestBody.create(price, MediaType.parse("text/plain"));
        RequestBody categoryBody = RequestBody.create(category, MediaType.parse("text/plain"));
        RequestBody cookingTimeBody = RequestBody.create(cookingTime, MediaType.parse("text/plain"));
        RequestBody descBody = RequestBody.create(description, MediaType.parse("text/plain"));
        RequestBody availableBody = RequestBody.create(isAvailable ? "1" : "0", MediaType.parse("text/plain"));

        // Siapkan Data Gambar (Jika Ada)
        MultipartBody.Part imagePart = null;
        if (selectedImageUri != null) {
            File imageFile = getFileFromUri(selectedImageUri);
            if (imageFile != null) {
                RequestBody requestFile = RequestBody.create(imageFile, MediaType.parse("image/*"));
                imagePart = MultipartBody.Part.createFormData("image", imageFile.getName(), requestFile);
            }
        }

        // Tembak API
        apiService.createMenu(canteenId, nameBody, priceBody, categoryBody, cookingTimeBody, descBody, availableBody, imagePart)
                .enqueue(new Callback<>() {
                    @Override
                    public void onResponse(@NonNull Call<MenuDetailResponse> call, @NonNull Response<MenuDetailResponse> response) {
                        btnSave.setEnabled(true);
                        if (response.isSuccessful()) {
                            Toast.makeText(TambahMenu.this, "Menu berhasil ditambahkan!", Toast.LENGTH_SHORT).show();
                            finish(); // Tutup Activity setelah sukses
                        } else {
                            Toast.makeText(TambahMenu.this, "Gagal menyimpan menu", Toast.LENGTH_SHORT).show();
                        }
                    }

                    @Override
                    public void onFailure(@NonNull Call<MenuDetailResponse> call, @NonNull Throwable t) {
                        btnSave.setEnabled(true);
                        Toast.makeText(TambahMenu.this, "Koneksi Error: " + t.getMessage(), Toast.LENGTH_SHORT).show();
                    }
                });
    }

    // Fungsi Pengaman untuk konversi URI Galeri ke File fisik
    private File getFileFromUri(Uri uri) {
        try {
            InputStream inputStream = getContentResolver().openInputStream(uri);
            if (inputStream == null) return null; // Cegah NullPointerException

            File tempFile = new File(getCacheDir(), "menu_img_" + System.currentTimeMillis() + ".jpg");
            FileOutputStream outputStream = new FileOutputStream(tempFile);

            byte[] buffer = new byte[1024];
            int length;
            while ((length = inputStream.read(buffer)) > 0) {
                outputStream.write(buffer, 0, length);
            }
            outputStream.close();
            inputStream.close();
            return tempFile;
        } catch (Exception e) {
            Log.e("TambahMenu", "Gagal memproses file gambar", e);
            return null;
        }
    }
}