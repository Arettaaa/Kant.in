package com.example.kantin;

import com.google.gson.annotations.SerializedName;
import java.util.List;

public class ApiOrder { // <-- Nama class diubah jadi ApiOrder

    @SerializedName("_id")
    private String id;

    @SerializedName("order_code")
    private String orderCode;

    @SerializedName("total_amount")
    private int totalAmount;

    @SerializedName("created_at")
    private String createdAt;

    @SerializedName("status")
    private String status;

    @SerializedName("customer_snapshot")
    private CustomerSnapshot customerSnapshot;

    @SerializedName("delivery_details")
    private DeliveryDetails deliveryDetails;

    @SerializedName("items")
    private List<OrderItem> items;

    // --- GETTERS ---
    public String getId() { return id; }
    public String getOrderCode() { return orderCode; }
    public int getTotalAmount() { return totalAmount; }
    public String getCreatedAt() { return createdAt; }
    public String getStatus() { return status; }
    public CustomerSnapshot getCustomerSnapshot() { return customerSnapshot; }
    public DeliveryDetails getDeliveryDetails() { return deliveryDetails; }
    public List<OrderItem> getItems() { return items; }

    // --- SETTER ---
    public void setStatus(String status) { this.status = status; }

    // =========================================================
    // INNER CLASSES
    // =========================================================

    public static class CustomerSnapshot {
        @SerializedName("name")
        private String name;
        public String getName() { return name; }
    }

    public static class DeliveryDetails {
        @SerializedName("method")
        private String method;
        public String getMethod() { return method; }
    }

    public static class OrderItem {
        @SerializedName("name")
        private String name;

        @SerializedName("quantity")
        private int quantity;

        public String getName() { return name; }
        public int getQuantity() { return quantity; }
    }
}