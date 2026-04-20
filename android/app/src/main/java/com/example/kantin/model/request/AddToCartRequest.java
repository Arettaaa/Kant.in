package com.example.kantin.model.request;

import com.google.gson.annotations.SerializedName;

public class AddToCartRequest {
    @SerializedName("menu_id")
    private String menuId;

    @SerializedName("quantity")
    private int quantity;

    public AddToCartRequest(String menuId, int quantity) {
        this.menuId = menuId;
        this.quantity = quantity;
    }

    public String getMenuId() { return menuId; }
    public int getQuantity() { return quantity; }
}