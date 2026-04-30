package com.example.kantin.model.request;

import com.google.gson.annotations.SerializedName;

/**
 * RegisterAdminKantinRequest — disesuaikan dengan AuthController@register
 *
 * Field yang diterima server:
 * - name (required)
 * - email (required, unique)
 * - password (required, min:6)
 * - phone (nullable)
 * - role (nullable, in:admin_kantin,pembeli)  ← "pembeli" BUKAN "buyer"
 * - canteen_name (required jika role=admin_kantin)
 *
 * Setelah register admin_kantin:
 * - Server buat Canteen baru dengan status "pending"
 * - User dibuat dengan status "pending"
 * - Tidak dapat token langsung — harus tunggu approve admin global
 * - Response: { message, user } — TANPA token
 */
public class RegisterAdminKantinRequest {

    @SerializedName("name")
    private String name;

    @SerializedName("email")
    private String email;

    @SerializedName("password")
    private String password;

    @SerializedName("phone")
    private String phone;

    @SerializedName("role")
    private String role = "admin_kantin";

    @SerializedName("canteen_name")
    private String canteenName;

    @SerializedName("canteen_location")      // ← tambah
    private String canteenLocation;

    @SerializedName("canteen_description")   // ← tambah
    private String canteenDescription;

    @SerializedName("canteen_phone")         // ← tambah
    private String canteenPhone;

    public RegisterAdminKantinRequest(String name, String email, String password,
                                      String phone, String canteenName,
                                      String canteenLocation, String canteenDescription,
                                      String canteenPhone) {
        this.name               = name;
        this.email              = email;
        this.password           = password;
        this.phone              = phone;
        this.canteenName        = canteenName;
        this.canteenLocation    = canteenLocation;
        this.canteenDescription = canteenDescription;
        this.canteenPhone       = canteenPhone;
    }

    public String getName()               { return name; }
    public String getEmail()              { return email; }
    public String getPassword()           { return password; }
    public String getPhone()              { return phone; }
    public String getRole()               { return role; }
    public String getCanteenName()        { return canteenName; }
    public String getCanteenLocation()    { return canteenLocation; }
    public String getCanteenDescription() { return canteenDescription; }
    public String getCanteenPhone()       { return canteenPhone; }
}