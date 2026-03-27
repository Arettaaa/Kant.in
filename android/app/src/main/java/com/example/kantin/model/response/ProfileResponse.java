package com.example.kantin.model.response;

public class ProfileResponse extends BaseResponse {
    private UserData data;

    public UserData getData() { return data; }

    public class UserData {
        private String _id;
        private String name;
        private String email;
        private String phone;
        private String photo_profile;

        public String getName() { return name; }
        public String getEmail() { return email; }
        public String getPhone() { return phone; }
        public String getPhotoProfile() { return photo_profile; }
    }
}