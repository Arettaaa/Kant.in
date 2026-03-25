package com.example.kantin.model.response;

import com.google.gson.annotations.SerializedName;
import java.util.List;

/**
 * TransactionListResponse — untuk GET /canteens/{id}/transactions
 * Hanya menampilkan order dengan status "completed"
 */
public class TransactionListResponse {

    @SerializedName("success")
    private boolean success;

    @SerializedName("message")
    private String message;

    @SerializedName("data")
    private TransactionData data;

    public boolean isSuccess()         { return success; }
    public String getMessage()         { return message; }
    public TransactionData getData()   { return data; }

    public static class TransactionData {
        @SerializedName("total_revenue")
        private double totalRevenue;

        @SerializedName("total_orders")
        private int totalOrders;

        @SerializedName("transactions")
        private List<OrderListResponse.OrderItem> transactions;

        public double getTotalRevenue()                          { return totalRevenue; }
        public int getTotalOrders()                              { return totalOrders; }
        public List<OrderListResponse.OrderItem> getTransactions() { return transactions; }
    }
}