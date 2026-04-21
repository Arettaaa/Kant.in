package com.example.kantin.model;

import com.google.gson.annotations.SerializedName;
import java.io.Serializable;
import java.util.List;

public class OrderModel implements Serializable {

    @SerializedName("id")
    private String id;

    @SerializedName("order_code")
    private String orderCode;

    @SerializedName("customer_snapshot")
    private CustomerSnapshot customerSnapshot;

    @SerializedName("items")
    private List<OrderItem> items; // Menggunakan OrderItem yang sudah kita buat sebelumnya

    @SerializedName("delivery_details")
    private DeliveryDetails deliveryDetails;

    @SerializedName("payment")
    private Payment payment;

    @SerializedName("subtotal_amount")
    private int subtotalAmount;

    @SerializedName("total_amount")
    private int totalAmount;

    @SerializedName("status")
    private String status;

    @SerializedName("created_at")
    private String createdAt;

    // --- GETTER ---
    public String getId() { return id; }
    public String getOrderCode() { return orderCode; }
    public CustomerSnapshot getCustomerSnapshot() { return customerSnapshot; }
    public List<OrderItem> getItems() { return items; }
    public DeliveryDetails getDeliveryDetails() { return deliveryDetails; }
    public Payment getPayment() { return payment; }
    public int getSubtotalAmount() { return subtotalAmount; }
    public int getTotalAmount() { return totalAmount; }
    public String getStatus() { return status; }
    public String getCreatedAt() { return createdAt; }

    // ==========================================
    // NESTED CLASSES (Sesuai struktur JSON Laravel)
    // ==========================================

    public static class CustomerSnapshot implements Serializable {
        @SerializedName("name")
        private String name;
        @SerializedName("phone")
        private String phone;

        public String getName() { return name; }
        public String getPhone() { return phone; }
    }

    public static class DeliveryDetails implements Serializable {
        @SerializedName("method")
        private String method; // "delivery" atau "pickup"
        @SerializedName("fee")
        private int fee;
        @SerializedName("location_note")
        private String locationNote;

        public String getMethod() { return method; }
        public int getFee() { return fee; }
        public String getLocationNote() { return locationNote; }
    }

    public static class Payment implements Serializable {
        @SerializedName("method")
        private String method;
        @SerializedName("status")
        private String status;
        @SerializedName("proof")
        private String proof; // Nama file foto struk

        public String getMethod() { return method; }
        public String getStatus() { return status; }
        public String getProof() { return proof; }
    }
}