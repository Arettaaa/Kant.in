package com.example.kantin.model;

import com.google.gson.annotations.SerializedName;
import java.util.List;

public class TransactionOrder {
    @SerializedName("_id")
    private String id;
    @SerializedName("order_code")
    private String orderCode;
    @SerializedName("total_amount")
    private double totalAmount;
    @SerializedName("subtotal_amount")
    private double subtotalAmount;
    @SerializedName("status")
    private String status;
    @SerializedName("created_at")
    private String createdAt;
    @SerializedName("customer_snapshot")
    private Customer customer;
    @SerializedName("items")
    private List<OrderItem> items;
    @SerializedName("delivery_details")
    private DeliveryDetails deliveryDetails;
    @SerializedName("payment")
    private Payment payment;

    // Getter lama
    public String getId() { return id; }
    public String getOrderCode() { return orderCode; }
    public double getTotalAmount() { return totalAmount; }
    public double getSubtotalAmount() { return subtotalAmount; }
    public String getStatus() { return status; }
    public String getCreatedAt() { return createdAt; }
    public String getCustomerName() { return customer != null ? customer.name : "Pelanggan"; }
    public int getItemCount() { return items != null ? items.size() : 0; }
    public List<OrderItem> getItems() { return items; }
    public DeliveryDetails getDeliveryDetails() { return deliveryDetails; }
    public Payment getPayment() { return payment; }

    // Inner classes
    public static class Customer {
        @SerializedName("name")
        public String name;
        @SerializedName("phone")
        public String phone;
    }

    public static class OrderItem {
        @SerializedName("name")
        public String name;
        @SerializedName("price")
        public double price;
        @SerializedName("quantity")
        public int quantity;
        @SerializedName("subtotal")
        public double subtotal;
        @SerializedName("notes")
        public String notes;
    }

    public static class DeliveryDetails {
        @SerializedName("method")
        public String method;
        @SerializedName("fee")
        public double fee;
        @SerializedName("location_note")
        public String locationNote;
    }

    public static class Payment {
        @SerializedName("method")
        public String method;
        @SerializedName("status")
        public String status;
    }
}