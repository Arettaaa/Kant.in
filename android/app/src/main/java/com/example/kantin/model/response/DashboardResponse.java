package com.example.kantin.model.response;

import com.google.gson.annotations.SerializedName;

/**
 * DashboardResponse — untuk GET /canteens/{id}/dashboard
 * Struktur disesuaikan nanti setelah endpoint tersedia.
 */
public class DashboardResponse {

    @SerializedName("success")
    private boolean success;

    @SerializedName("message")
    private String message;

    @SerializedName("data")
    private DashboardData data;

    public boolean isSuccess()        { return success; }
    public String getMessage()        { return message; }
    public DashboardData getData()    { return data; }

    public static class DashboardData {
        @SerializedName("total_revenue_today")
        private double totalRevenueToday;

        @SerializedName("total_orders_today")
        private int totalOrdersToday;

        @SerializedName("total_orders_pending")
        private int totalOrdersPending;

        @SerializedName("total_orders_completed")
        private int totalOrdersCompleted;

        public double getTotalRevenueToday()  { return totalRevenueToday; }
        public int getTotalOrdersToday()      { return totalOrdersToday; }
        public int getTotalOrdersPending()    { return totalOrdersPending; }
        public int getTotalOrdersCompleted()  { return totalOrdersCompleted; }
    }
}