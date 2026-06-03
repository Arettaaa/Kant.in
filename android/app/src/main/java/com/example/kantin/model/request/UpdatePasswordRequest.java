package com.example.kantin.model.request;

import com.google.gson.annotations.SerializedName;

public class UpdatePasswordRequest {

    @SerializedName("password")
    private String password;

    @SerializedName("password_confirmation")
    private String passwordConfirmation;

    public UpdatePasswordRequest(String password, String passwordConfirmation) {
        this.password = password;
        this.passwordConfirmation = passwordConfirmation;
    }

    public String getPassword()             { return password; }
    public String getPasswordConfirmation() { return passwordConfirmation; }
}