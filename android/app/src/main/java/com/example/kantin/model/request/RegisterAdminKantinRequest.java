package com.example.kantin.model.request;

import com.google.gson.annotations.SerializedName;

public class RegisterAdminKantinRequest {
    @SerializedName("name")
    private String name;

    @SerializedName("email")
    private String email;

    @SerializedName("password")
    private String password;

    @SerializedName("role")
    private String role = "admin_kantin";

    @SerializedName("canteen_name")
    private String canteenName;

    public RegisterAdminKantinRequest(String name, String email, String password, String canteenName) {
        this.name = name;
        this.email = email;
        this.password = password;
        this.canteenName = canteenName;
    }

    public String getName()       { return name; }
    public String getEmail()      { return email; }
    public String getPassword()   { return password; }
    public String getRole()       { return role; }
    public String getCanteenName(){ return canteenName; }
}