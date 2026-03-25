package com.example.kantin.model.request;

import com.google.gson.annotations.SerializedName;

public class UpdatePasswordRequest {
    @SerializedName("current_password")
    private String currentPassword;

    @SerializedName("password")
    private String newPassword;

    @SerializedName("password_confirmation")
    private String passwordConfirmation;

    public UpdatePasswordRequest(String currentPassword, String newPassword, String passwordConfirmation) {
        this.currentPassword = currentPassword;
        this.newPassword = newPassword;
        this.passwordConfirmation = passwordConfirmation;
    }
}