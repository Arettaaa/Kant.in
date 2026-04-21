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
    @SerializedName("status")
    private String status;
    @SerializedName("created_at")
    private String createdAt;
    @SerializedName("customer_snapshot")
    private Customer customer;
    @SerializedName("items")
    private List<OrderItem> items;

    public String getOrderCode() { return orderCode; }
    public double getTotalAmount() { return totalAmount; }
    public String getStatus() { return status; }
    public String getCustomerName() { return customer != null ? customer.name : "Pelanggan"; }
    public int getItemCount() { return items != null ? items.size() : 0; }

    public static class Customer {
        @SerializedName("name")
        public String name;
    }
    public static class OrderItem {
        @SerializedName("name")
        public String name;
    }

    public String getCreatedAt() {
        return createdAt;
    }
}