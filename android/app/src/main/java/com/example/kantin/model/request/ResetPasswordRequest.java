package com.example.kantin.model.request;

import com.google.gson.annotations.SerializedName;

public class ResetPasswordRequest {

    @SerializedName("email")
    private String email;

    @SerializedName("password")
    private String password;

    @SerializedName("password_confirmation")
    private String passwordConfirmation;

    public ResetPasswordRequest(String email, String password, String passwordConfirmation) {
        this.email = email;
        this.password = password;
        this.passwordConfirmation = passwordConfirmation;
    }
}