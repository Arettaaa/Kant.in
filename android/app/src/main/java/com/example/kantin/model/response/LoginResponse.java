package com.example.kantin.model.response;

import com.google.gson.annotations.SerializedName;

public class LoginResponse {

    @SerializedName("message")
    private String message;

    @SerializedName("token")
    private String token;

    @SerializedName("user")
    private UserData user;

    public String getMessage() { return message; }
    public String getToken()   { return token; }
    public UserData getUser()  { return user; }

    public boolean isSuccess() { return token != null && !token.isEmpty(); }

    public static class UserData {

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

        @SerializedName("status")
        private String status;

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