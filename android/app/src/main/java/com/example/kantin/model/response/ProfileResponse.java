package com.example.kantin.model.response;

import com.google.gson.annotations.SerializedName; // Import ini penting!

public class ProfileResponse extends BaseResponse {
    @SerializedName("data")
    private UserData data;

    public UserData getData() { return data; }

    public class UserData {
        @SerializedName("_id")
        private String _id;

        @SerializedName("name")
        private String name;

        @SerializedName("email")
        private String email;

        @SerializedName("phone")
        private String phone;

        @SerializedName("photo_profile") // Memastikan mapping ke JSON 'photo_profile'
        private String photo_profile;

        @SerializedName("role")
        private String role;

        public String getName() { return name; }
        public String getEmail() { return email; }
        public String getPhone() { return phone; }
        public String getPhotoProfile() { return photo_profile; }
        public String getRole() { return role; }
    }
}