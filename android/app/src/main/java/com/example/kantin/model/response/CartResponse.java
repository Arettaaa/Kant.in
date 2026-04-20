package com.example.kantin.model.response;

import com.google.gson.annotations.SerializedName;
import java.util.List;

public class CartResponse {

    @SerializedName("success")
    private boolean success;

    @SerializedName("message")
    private String message;

    @SerializedName("data")
    private CartData data;

    public boolean isSuccess() { return success; }
    public String getMessage() { return message; }
    public CartData getData() { return data; }

    // ── CartData ──────────────────────────────────────────────
    public static class CartData {
        @SerializedName("_id")
        private String id;

        @SerializedName("user_id")
        private String userId;

        @SerializedName("canteens")
        private List<CanteenCart> canteens;

        public String getId() { return id; }
        public String getUserId() { return userId; }
        public List<CanteenCart> getCanteens() { return canteens; }
    }

    // ── CanteenCart ───────────────────────────────────────────
    public static class CanteenCart {
        @SerializedName("canteen_id")
        private String canteenId;

        @SerializedName("canteen_name")
        private String canteenName;

        @SerializedName("items")
        private List<CartItem> items;

        @SerializedName("subtotal")
        private double subtotal;

        public String getCanteenId() { return canteenId; }
        public String getCanteenName() { return canteenName; }
        public List<CartItem> getItems() { return items; }
        public double getSubtotal() { return subtotal; }
    }

    // ── CartItem ──────────────────────────────────────────────
    public static class CartItem {
        @SerializedName("menu_id")
        private String menuId;

        @SerializedName("name")
        private String name;

        @SerializedName("price")
        private double price;

        @SerializedName("quantity")
        private int quantity;

        @SerializedName("subtotal")
        private double subtotal;

        @SerializedName("image")
        private String image;

        // ← TAMBAH INI (tidak dari JSON, diisi manual saat parsing)
        private String canteenId;
        private String canteenName;

        public String getMenuId()    { return menuId; }
        public String getName()      { return name; }
        public double getPrice()     { return price; }
        public int getQuantity()     { return quantity; }
        public double getSubtotal()  { return subtotal; }
        public String getImage()     { return image; }

        // ← TAMBAH INI
        public void setCanteenId(String canteenId)     { this.canteenId = canteenId; }
        public void setCanteenName(String canteenName) { this.canteenName = canteenName; }
        public String getCanteenId()                   { return canteenId; }
        public String getCanteenName()                 { return canteenName; }
    }
}