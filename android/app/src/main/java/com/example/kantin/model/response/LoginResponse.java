package com.example.kantin.model.response;

import com.google.gson.annotations.SerializedName;

/**
 * LoginResponse — disesuaikan dengan AuthController.php
 *
 * Response login dari server:
 * {
 *   "message": "Login berhasil",
 *   "token": "1|xxxxx",
 *   "user": { _id, name, email, phone, role, canteen_id, status, photo_profile, ... }
 * }
 *
 * CATATAN PENTING:
 * - Tidak ada wrapper "success" dan "data" — langsung flat
 * - Token ada di root level, bukan di dalam "data"
 * - Field user langsung berisi data user (bukan nested dalam data)
 * - role pembeli: "pembeli" (bukan "buyer")
 * - role admin kantin: "admin_kantin"
 */
public class LoginResponse {

    /** "Login berhasil" atau pesan error */
    @SerializedName("message")
    private String message;

    /** Bearer token — format: "id|plaintext" */
    @SerializedName("token")
    private String token;

    /** Data user yang login */
    @SerializedName("user")
    private UserData user;

    public String getMessage() { return message; }
    public String getToken()   { return token; }
    public UserData getUser()  { return user; }

    /** Cek apakah login berhasil (token tidak null) */
    public boolean isSuccess() { return token != null && !token.isEmpty(); }

    // ======================================================================
    // Data user yang dikembalikan saat login
    // ======================================================================
    public static class UserData {

        @SerializedName("_id")
        private String id;

        @SerializedName("name")
        private String name;

        @SerializedName("email")
        private String email;

        @SerializedName("phone")
        private String phone;

        /**
         * Role: "admin_kantin" | "admin_global" | "pembeli"
         * BUKAN "buyer" — sesuai DB dan controller
         */
        @SerializedName("role")
        private String role;

        /** ID kantin — hanya ada untuk admin_kantin, null untuk pembeli */
        @SerializedName("canteen_id")
        private String canteenId;

        /** "active" | "pending" | "rejected" */
        @SerializedName("status")
        private String status;

        /** Foto profil — field DB bernama "photo_profile" */
        @SerializedName("photo_profile")
        private String photoProfile;

        @SerializedName("created_at")
        private String createdAt;

        @SerializedName("updated_at")
        private String updatedAt;

        public String getId()           { return id; }
        public String getName()         { return name != null ? name : ""; }
        public String getEmail()        { return email != null ? email : ""; }
        public String getPhone()        { return phone != null ? phone : ""; }
        public String getRole()         { return role; }
        public String getCanteenId()    { return canteenId; }
        public String getStatus()       { return status; }
        public String getPhotoProfile() { return photoProfile; }
        public String getCreatedAt()    { return createdAt; }
        public String getUpdatedAt()    { return updatedAt; }

        public boolean isAdminKantin()  { return "admin_kantin".equals(role); }
        public boolean isAdminGlobal()  { return "admin_global".equals(role); }
        public boolean isPembeli()      { return "pembeli".equals(role); }
        public boolean isActive()       { return "active".equals(status); }
    }
}