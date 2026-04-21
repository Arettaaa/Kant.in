package com.example.kantin.model;

import com.google.gson.annotations.SerializedName;
import java.io.Serializable;

public class OrderItem implements Serializable {

    @SerializedName("menu_id")
    private String menuId;

    @SerializedName("name")
    private String name;

    @SerializedName("price")
    private int price;

    @SerializedName("quantity")
    private int quantity;

    @SerializedName("notes")
    private String notes;

    @SerializedName("estimated_cooking_time")
    private int estimatedCookingTime;

    @SerializedName("subtotal")
    private int subtotal;

    // --- GETTER ---
    public String getMenuId() {
        return menuId;
    }

    public String getName() {
        return name;
    }

    public int getPrice() {
        return price;
    }

    public int getQuantity() {
        return quantity;
    }

    public String getNotes() {
        return notes;
    }

    public int getEstimatedCookingTime() {
        return estimatedCookingTime;
    }

    public int getSubtotal() {
        return subtotal;
    }
}