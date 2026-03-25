package com.example.kantin.model.response;

import com.google.gson.annotations.SerializedName;

public class LoginResponse {
    @SerializedName("success")
    private boolean success;

    @SerializedName("message")
    private String message;

    @SerializedName("data")
    private LoginData data;

    public boolean isSuccess() { return success; }
    public String getMessage() { return message; }
    public LoginData getData() { return data; }

    public static class LoginData {
        @SerializedName("token")
        private String token;

        @SerializedName("user")
        private UserData user;

        public String getToken()   { return token; }
        public UserData getUser()  { return user; }
    }

    public static class UserData {
        @SerializedName("id")
        private String id;

        @SerializedName("name")
        private String name;

        @SerializedName("email")
        private String email;

        @SerializedName("role")
        private String role;

        @SerializedName("canteen_id")
        private String canteenId;

        public String getId()        { return id; }
        public String getName()      { return name; }
        public String getEmail()     { return email; }
        public String getRole()      { return role; }
        public String getCanteenId() { return canteenId; }
    }
}