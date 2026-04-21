package com.example.kantin.model.response;

import com.example.kantin.model.TransactionOrder;
import com.google.gson.annotations.SerializedName;
import java.util.List;

public class TransactionListResponse {

    @SerializedName("success")
    private boolean success;

    @SerializedName("data")
    private Data data;

    public boolean isSuccess() {
        return success;
    }

    public Data getData() {
        return data;
    }

    // Inner class untuk membungkus object "data"
    public static class Data {
        @SerializedName("total_revenue")
        private double totalRevenue;

        @SerializedName("total_orders")
        private int totalOrders;

        @SerializedName("orders")
        private List<TransactionOrder> orders;

        public double getTotalRevenue() {
            return totalRevenue;
        }

        public int getTotalOrders() {
            return totalOrders;
        }

        public List<TransactionOrder> getOrders() {
            return orders;
        }
    }
}