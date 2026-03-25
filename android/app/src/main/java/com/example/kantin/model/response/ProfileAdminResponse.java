package com.example.kantin.model.response;

import com.google.gson.annotations.SerializedName;

/**
 * ProfileAdminResponse — disesuaikan dengan DB asli.
 *
 * Field user DB: _id, name, email, phone, role, canteen_id,
 *                status (active/pending/rejected), photo_profile, created_at
 *
 * Field canteen DB: _id, name, description, location, phone, image,
 *                   delivery_fee_flat, operating_hours{open,close},
 *                   is_active, is_open, status, created_at
 */
public class ProfileAdminResponse {

    @SerializedName("success")
    private boolean success;

    @SerializedName("message")
    private String message;

    @SerializedName("data")
    private AdminProfile data;

    public boolean isSuccess()      { return success; }
    public String getMessage()      { return message; }
    public AdminProfile getData()   { return data; }

    // ======================================================================
    // Data admin kantin (gabungan user + canteen dari API)
    // ======================================================================
    public static class AdminProfile {

        // --- Data User ---
        @SerializedName("_id")
        private String id;

        @SerializedName("name")
        private String name;

        @SerializedName("email")
        private String email;

        @SerializedName("phone")
        private String phone;

        /** "active" | "pending" | "rejected" */
        @SerializedName("status")
        private String status;

        /** Nama field di DB: "photo_profile", BUKAN "photo_url" */
        @SerializedName("photo_profile")
        private String photoProfile;

        @SerializedName("role")
        private String role;

        @SerializedName("canteen_id")
        private String canteenId;

        @SerializedName("created_at")
        private String createdAt;

        // --- Data Kantin (dikirim bersama oleh API) ---
        @SerializedName("canteen")
        private CanteenInfo canteen;

        public String getId()           { return id; }
        public String getName()         { return name != null ? name : ""; }
        public String getEmail()        { return email != null ? email : ""; }
        public String getPhone()        { return phone != null ? phone : ""; }
        public String getStatus()       { return status; }
        public String getPhotoProfile() { return photoProfile; }
        public String getRole()         { return role; }
        public String getCanteenId()    { return canteenId; }
        public String getCreatedAt()    { return createdAt; }
        public CanteenInfo getCanteen() { return canteen; }

        // Helper
        public boolean isActive()  { return "active".equals(status); }
        public boolean isPending() { return "pending".equals(status); }
    }

    // ======================================================================
    // Data kantin yang melekat pada profil admin
    // ======================================================================
    public static class CanteenInfo {

        @SerializedName("_id")
        private String id;

        @SerializedName("name")
        private String name;

        @SerializedName("description")
        private String description;

        @SerializedName("location")
        private String location;

        @SerializedName("phone")
        private String phone;

        /** URL gambar kantin — field DB bernama "image" */
        @SerializedName("image")
        private String image;

        @SerializedName("delivery_fee_flat")
        private String deliveryFeeFlat;

        @SerializedName("operating_hours")
        private OperatingHours operatingHours;

        /** true = kantin aktif/terdaftar */
        @SerializedName("is_active")
        private boolean isActive;

        /** true = kantin sedang buka (toggle oleh admin kantin) */
        @SerializedName("is_open")
        private boolean isOpen;

        /** "active" | "pending" | "rejected" */
        @SerializedName("status")
        private String status;

        public String getId()                   { return id; }
        public String getName()                 { return name != null ? name : ""; }
        public String getDescription()          { return description; }
        public String getLocation()             { return location; }
        public String getPhone()                { return phone; }
        public String getImage()                { return image; }
        public String getDeliveryFeeFlat()      { return deliveryFeeFlat; }
        public OperatingHours getOperatingHours() { return operatingHours; }
        public boolean isActive()               { return isActive; }
        public boolean isOpen()                 { return isOpen; }
        public String getStatus()               { return status; }

        public double getDeliveryFeeAsDouble() {
            try { return Double.parseDouble(deliveryFeeFlat); }
            catch (Exception e) { return 0; }
        }
    }

    // ======================================================================
    // Nested: operating_hours
    // ======================================================================
    public static class OperatingHours {
        @SerializedName("open")
        private String open;

        @SerializedName("close")
        private String close;

        public String getOpen()  { return open != null ? open : ""; }
        public String getClose() { return close != null ? close : ""; }
    }
}