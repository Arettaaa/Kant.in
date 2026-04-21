package com.example.kantin.model;

import com.google.gson.annotations.SerializedName;

public class MenuModel {
    @SerializedName("_id")
    private String id;

    @SerializedName("name")
    private String name;

    @SerializedName("price")
    private double price;

    @SerializedName("image")
    private String image;

    @SerializedName("is_available")
    private boolean isAvailable;

    @SerializedName("category")
    private String category;

    // Tambahkan Getter
    public String getId() { return id; }
    public String getName() { return name; }
    public double getPrice() { return price; }
    public String getImage() { return image; }
    public boolean isAvailable() { return isAvailable; }
    public String getCategory() { return category; }

    // Setter untuk update lokal
    public void setAvailable(boolean available) { isAvailable = available; }
}