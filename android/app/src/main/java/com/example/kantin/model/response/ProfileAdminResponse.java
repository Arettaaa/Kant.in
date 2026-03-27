package com.example.kantin.model.response;

import com.google.gson.annotations.SerializedName;

/**
 * ProfileAdminResponse — disesuaikan dengan ProfileController.php
 *
 * Response GET /admin/profiles:
 * {
 *   "success": true,
 *   "data": {
 *     "_id", "name", "email", "phone", "role",
 *     "canteen_id", "status", "photo_profile",
 *     "created_at", "updated_at"
 *   }
 * }
 *
 * CATATAN: ProfileController hanya return data user saja (toArray()),
 * tidak ada nested canteen. Canteen info diambil terpisah jika dibutuhkan.
 *
 * Field update yang diterima server (ProfileController@update):
 * - name (sometimes)
 * - phone (sometimes)
 * - photo_profile (file image)
 * - password (sometimes, min:8, confirmed → butuh password_confirmation)
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
    // Data admin kantin — flat, sesuai User model Laravel
    // ======================================================================
    public static class AdminProfile {

        @SerializedName("_id")
        private String id;

        @SerializedName("name")
        private String name;

        @SerializedName("email")
        private String email;

        @SerializedName("phone")
        private String phone;

        @SerializedName("role")
        private String role;

        @SerializedName("canteen_id")
        private String canteenId;

        /** "active" | "pending" | "rejected" */
        @SerializedName("status")
        private String status;

        /**
         * URL foto profil — sudah di-format oleh server dengan asset('storage/...')
         * Field DB: "photo_profile"
         */
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

        public boolean isActive()       { return "active".equals(status); }
        public boolean isPending()      { return "pending".equals(status); }
        public boolean isRejected()     { return "rejected".equals(status); }
    }
}