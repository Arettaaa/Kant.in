package com.example.kantin.model.response;

import com.google.gson.annotations.SerializedName;
import java.util.List;

/**
 * TransactionListResponse — disesuaikan dengan TransactionController.php
 *
 * Response GET /canteens/{id}/transactions:
 * {
 *   "success": true,
 *   "data": {
 *     "total_revenue": 15000,
 *     "total_orders": 1,
 *     "orders": [ ...array of OrderItem... ]   ← key-nya "orders", BUKAN "transactions"
 *   }
 * }
 *
 * Hanya order dengan status "completed" yang masuk ke sini.
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

        /**
         * Key di response adalah "orders", BUKAN "transactions"
         * Struktur sama dengan OrderListResponse.OrderItem
         */
        @SerializedName("orders")
        private List<OrderListResponse.OrderItem> orders;

        public double getTotalRevenue()                        { return totalRevenue; }
        public int getTotalOrders()                            { return totalOrders; }
        public List<OrderListResponse.OrderItem> getOrders()   { return orders; }
    }
}