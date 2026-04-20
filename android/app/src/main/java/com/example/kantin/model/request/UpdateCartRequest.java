package com.example.kantin.model.request;

import com.google.gson.annotations.SerializedName;

public class UpdateCartRequest {
    @SerializedName("quantity")
    private int quantity;

    public UpdateCartRequest(int quantity) {
        this.quantity = quantity;
    }
}