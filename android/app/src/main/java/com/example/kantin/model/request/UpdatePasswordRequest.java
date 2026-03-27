package com.example.kantin.model.request;

import com.google.gson.annotations.SerializedName;

/**
 * UpdatePasswordRequest — disesuaikan ProfileController@update
 *
 * Field yang diterima server untuk ganti password:
 * - password (sometimes, min:8, confirmed)
 * - password_confirmation (wajib ada karena pakai "confirmed" rule)
 *
 * CATATAN: Server tidak minta "current_password" — langsung ganti
 * Update profil dan ganti password pakai endpoint yang SAMA:
 * PUT /api/admin/profiles (atau /api/buyers/profiles untuk pembeli)
 */
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