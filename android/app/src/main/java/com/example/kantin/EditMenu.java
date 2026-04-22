package com.example.kantin;

import android.app.AlertDialog;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.EditText;
import android.widget.ImageView;
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

import com.bumptech.glide.Glide;
import com.example.kantin.model.response.BaseResponse;
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

public class EditMenu extends AppCompatActivity {

    // UI Components
    private CardView btnBack, btnChangeFoto, btnSave;
    private ImageView ivMenuPreview, btnDelete;
    private EditText etName, etPrice, etCookingTime, etDescription;
    private Spinner spinnerCategory;
    private android.widget.CheckBox switchIsAvailable;

    // Data & Network
    private String menuId;
    private Uri selectedImageUri = null;
    private ApiService apiService;
    private SessionManager sessionManager;

    // Image Picker Launcher
    private final ActivityResultLauncher<Intent> imagePickerLauncher = registerForActivityResult(
            new ActivityResultContracts.StartActivityForResult(),
            result -> {
                if (result.getResultCode() == RESULT_OK && result.getData() != null) {
                    selectedImageUri = result.getData().getData();
                    ivMenuPreview.setImageURI(selectedImageUri);
                }
            }
    );

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_edit_menu);

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
        getIntentDataAndPopulate();
        setupListeners();
    }

    private void initViews() {
        btnBack = findViewById(R.id.btnBack);
        btnDelete = findViewById(R.id.btnDelete);

        ivMenuPreview = findViewById(R.id.ivMenuPreview);
        btnChangeFoto = findViewById(R.id.btnChangeFoto);
        View containerPhoto = findViewById(R.id.containerPhoto);

        etName = findViewById(R.id.etEditMenuName);
        etPrice = findViewById(R.id.etEditPrice);
        spinnerCategory = findViewById(R.id.spinnerCategory);
        etCookingTime = findViewById(R.id.etEditCookingTime);
        etDescription = findViewById(R.id.etEditDescription);
        switchIsAvailable = findViewById(R.id.switchEditIsAvailable);

        btnSave = findViewById(R.id.btnSave);

        containerPhoto.setOnClickListener(v -> openGallery());
        btnChangeFoto.setOnClickListener(v -> openGallery());
    }

    private void getIntentDataAndPopulate() {
        Intent intent = getIntent();
        if (intent != null && intent.hasExtra("menu_id")) {
            menuId = intent.getStringExtra("menu_id");

            etName.setText(intent.getStringExtra("menu_name"));
            etDescription.setText(intent.getStringExtra("menu_description"));

            double priceDouble = intent.getDoubleExtra("menu_price", 0);
            etPrice.setText(String.valueOf((int) priceDouble));

            etCookingTime.setText(intent.getStringExtra("menu_cooking_time"));

            String oldCategory = intent.getStringExtra("menu_category");
            setSpinnerSelection(spinnerCategory, oldCategory);

            boolean isAvailable = intent.getBooleanExtra("menu_is_available", true);
            switchIsAvailable.setChecked(isAvailable);

            String imageUrl = intent.getStringExtra("menu_image");
            if (imageUrl != null && !imageUrl.isEmpty()) {
                Glide.with(this)
                        .load(imageUrl)
                        .placeholder(R.drawable.makanan)
                        .centerCrop()
                        .into(ivMenuPreview);
            }
        } else {
            Toast.makeText(this, "Data menu tidak ditemukan", Toast.LENGTH_SHORT).show();
            finish();
        }
    }

    private void setSpinnerSelection(Spinner spinner, String value) {
        if (spinner.getAdapter() != null && value != null) {
            for (int i = 0; i < spinner.getAdapter().getCount(); i++) {
                Object item = spinner.getAdapter().getItem(i);
                if (item != null && item.toString().equalsIgnoreCase(value)) {
                    spinner.setSelection(i);
                    break;
                }
            }
        }
    }

    private void setupListeners() {
        btnBack.setOnClickListener(v -> finish());
        btnSave.setOnClickListener(v -> validateAndSubmit());
        btnDelete.setOnClickListener(v -> showDeleteConfirmationDialog());
    }

    private void openGallery() {
        Intent intent = new Intent(Intent.ACTION_PICK);
        intent.setType("image/*");
        imagePickerLauncher.launch(intent);
    }

    private void validateAndSubmit() {
        String name = etName.getText().toString().trim();
        String price = etPrice.getText().toString().trim();
        String cookingTime = etCookingTime.getText().toString().trim();
        String description = etDescription.getText().toString().trim();
        String category = spinnerCategory.getSelectedItem().toString();

        if (name.isEmpty() || price.isEmpty()) {
            Toast.makeText(this, "Nama dan Harga tidak boleh kosong!", Toast.LENGTH_SHORT).show();
            return;
        }

        if (category.equals("Pilih Kategori...")) {
            Toast.makeText(this, "Kategori tidak valid!", Toast.LENGTH_SHORT).show();
            return;
        }

        updateMenuToServer(name, price, category, cookingTime, description, switchIsAvailable.isChecked());
    }

    private void updateMenuToServer(String name, String price, String category,
                                    String cookingTime, String description, boolean isAvailable) {
        String canteenId = sessionManager.getCanteenId();

        btnSave.setEnabled(false);
        Toast.makeText(this, "Memperbarui menu...", Toast.LENGTH_SHORT).show();

        // Urutan sesuai ApiService baris 423:
        // method, name, price, category, cookingTime, description, isAvailable, image
        RequestBody methodBody      = RequestBody.create("PUT",                    MediaType.parse("text/plain"));
        RequestBody nameBody        = RequestBody.create(name,                     MediaType.parse("text/plain"));
        RequestBody priceBody       = RequestBody.create(price,                    MediaType.parse("text/plain"));
        RequestBody categoryBody    = RequestBody.create(category,                 MediaType.parse("text/plain"));
        RequestBody cookingTimeBody = RequestBody.create(cookingTime,              MediaType.parse("text/plain"));
        RequestBody descBody        = RequestBody.create(description,              MediaType.parse("text/plain"));
        RequestBody availableBody   = RequestBody.create(isAvailable ? "1" : "0", MediaType.parse("text/plain"));

        MultipartBody.Part imagePart = null;
        if (selectedImageUri != null) {
            File imageFile = getFileFromUri(selectedImageUri);
            if (imageFile != null) {
                RequestBody requestFile = RequestBody.create(imageFile, MediaType.parse("image/*"));
                imagePart = MultipartBody.Part.createFormData("image", imageFile.getName(), requestFile);
            }
        }

        apiService.updateMenu(
                canteenId, menuId,
                methodBody, nameBody, priceBody, categoryBody,
                cookingTimeBody, descBody, availableBody,
                imagePart
        ).enqueue(new Callback<MenuDetailResponse>() {
            @Override
            public void onResponse(@NonNull Call<MenuDetailResponse> call,
                                   @NonNull Response<MenuDetailResponse> response) {
                btnSave.setEnabled(true);
                if (response.isSuccessful()) {
                    Toast.makeText(EditMenu.this, "Menu berhasil diperbarui!", Toast.LENGTH_SHORT).show();
                    setResult(RESULT_OK);
                    finish();
                } else {
                    Log.e("EditMenu", "Update gagal, kode: " + response.code());
                    Toast.makeText(EditMenu.this, "Gagal memperbarui menu (kode: "
                            + response.code() + ")", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(@NonNull Call<MenuDetailResponse> call, @NonNull Throwable t) {
                btnSave.setEnabled(true);
                Log.e("EditMenu", "Update error: " + t.getMessage(), t);
                Toast.makeText(EditMenu.this, "Koneksi Error: " + t.getMessage(), Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void showDeleteConfirmationDialog() {
        new AlertDialog.Builder(this)
                .setTitle("Hapus Menu")
                .setMessage("Apakah Anda yakin ingin menghapus menu ini secara permanen?")
                .setPositiveButton("Hapus", (dialog, which) -> deleteMenu())
                .setNegativeButton("Batal", null)
                .show();
    }

    private void deleteMenu() {
        String canteenId = sessionManager.getCanteenId();
        Toast.makeText(this, "Menghapus menu...", Toast.LENGTH_SHORT).show();

        // deleteMenu return Call<BaseResponse> — sesuai ApiService
        apiService.deleteMenu(canteenId, menuId).enqueue(new Callback<BaseResponse>() {
            @Override
            public void onResponse(@NonNull Call<BaseResponse> call,
                                   @NonNull Response<BaseResponse> response) {
                if (response.isSuccessful()) {
                    Toast.makeText(EditMenu.this, "Menu berhasil dihapus", Toast.LENGTH_SHORT).show();
                    setResult(RESULT_OK);
                    finish();
                } else {
                    Log.e("EditMenu", "Delete gagal, kode: " + response.code());
                    Toast.makeText(EditMenu.this, "Gagal menghapus menu (kode: "
                            + response.code() + ")", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(@NonNull Call<BaseResponse> call, @NonNull Throwable t) {
                Log.e("EditMenu", "Delete error: " + t.getMessage(), t);
                Toast.makeText(EditMenu.this, "Koneksi Error: " + t.getMessage(), Toast.LENGTH_SHORT).show();
            }
        });
    }

    private File getFileFromUri(Uri uri) {
        try {
            InputStream inputStream = getContentResolver().openInputStream(uri);
            if (inputStream == null) return null;

            File tempFile = new File(getCacheDir(), "edit_menu_img_" + System.currentTimeMillis() + ".jpg");
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
            Log.e("EditMenu", "Gagal memproses file gambar", e);
            return null;
        }
    }
}