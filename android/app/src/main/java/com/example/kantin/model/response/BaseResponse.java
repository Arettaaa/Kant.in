package com.example.kantin.model.response;

import com.google.gson.annotations.SerializedName;

/**
 * BaseResponse — dipakai untuk endpoint yang hanya return status + message.
 * Contoh: logout, toggle availability, verify/reject payment, dll.
 */
public class BaseResponse {
    @SerializedName("success")
    private boolean success;

    @SerializedName("message")
    private String message;

    public boolean isSuccess() { return success; }
    public String getMessage() { return message; }
}