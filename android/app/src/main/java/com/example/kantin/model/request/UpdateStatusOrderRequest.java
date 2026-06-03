package com.example.kantin.model.request;

import com.google.gson.annotations.SerializedName;

public class UpdateStatusOrderRequest {
    @SerializedName("status")
    private String status;

    public UpdateStatusOrderRequest(String status) {
        this.status = status;
    }

    public String getStatus() {
        return status;
    }

    public void setStatus(String status) {
        this.status = status;
    }
}