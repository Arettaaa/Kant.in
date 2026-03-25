package com.example.kantin.model.request;

import com.google.gson.annotations.SerializedName;

public class UpdateProfilAdminRequest {
    @SerializedName("name")
    private String name;

    @SerializedName("email")
    private String email;

    public UpdateProfileAdminRequest(String name, String email) {
        this.name = name;
        this.email = email;
    }
}