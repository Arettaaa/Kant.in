package com.example.kantin.model.request;

import com.google.gson.annotations.SerializedName;

public class UpdateStatusOrderRequest {
    /** Nilai yang valid: "processing" | "ready" | "completed" */
    @SerializedName("status")
    private String status;

    // Perbaikan: Hapus 'void' dan pastikan nama sama dengan Class
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