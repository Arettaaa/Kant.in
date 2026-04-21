package com.example.kantin.model.response;

import com.google.gson.annotations.SerializedName;
import java.util.List;

/**
 * OrderListResponse — disesuaikan dengan struktur DB MongoDB asli.
 *
 * Status order  : pending | processing | completed | cancelled
 * Status payment: unpaid | paid | pending_verification | rejected
 * Delivery method: pickup | delivery
 */
public class OrderListResponse {

    @SerializedName("success")
    private boolean success;

    @SerializedName("message")
    private String message;

    @SerializedName("data")
    private List<OrderItem> data;

    public boolean isSuccess()        { return success; }
    public String getMessage()        { return message; }
    public List<OrderItem> getData()  { return data; }

    // ======================================================================
    // Model satu pesanan
    // ======================================================================
    public static class OrderItem {

        @SerializedName("_id")
        private String id;

        @SerializedName("order_code")
        private String orderCode;

        @SerializedName("customer_snapshot")
        private CustomerSnapshot customerSnapshot;

        @SerializedName("canteen_id")
        private String canteenId;

        @SerializedName("items")
        private List<OrderItemDetail> items;

        @SerializedName("subtotal_amount")
        private double subtotalAmount;

        @SerializedName("delivery_details")
        private DeliveryDetails deliveryDetails;

        @SerializedName("total_amount")
        private double totalAmount;

        @SerializedName("payment")
        private PaymentInfo payment;

        /** Status order: pending | processing | completed | cancelled */
        @SerializedName("status")
        private String status;

        @SerializedName("created_at")
        private String createdAt;

        @SerializedName("updated_at")
        private String updatedAt;

        @SerializedName("canteen_name")
        private String canteenName;

        public String getCanteenName() { return canteenName; }

        @SerializedName("id")
        private String idAlias; // untuk response yang pakai "id" bukan "_id"

        public String getIdAlias() { return idAlias; }


        public String getId()                         { return id; }
        public String getOrderCode()                  { return orderCode; }
        public CustomerSnapshot getCustomerSnapshot() { return customerSnapshot; }
        public String getCanteenId()                  { return canteenId; }
        public List<OrderItemDetail> getItems()       { return items; }
        public double getSubtotalAmount()             { return subtotalAmount; }
        public DeliveryDetails getDeliveryDetails()   { return deliveryDetails; }
        public double getTotalAmount()                { return totalAmount; }
        public PaymentInfo getPayment()               { return payment; }
        public String getStatus()                     { return status; }
        public String getCreatedAt()                  { return createdAt; }
        public String getUpdatedAt()                  { return updatedAt; }

        // ---- Helper agar Activity tidak perlu null-check manual ----
        public String getBuyerName() {
            return customerSnapshot != null ? customerSnapshot.getName() : "-";
        }
        public String getBuyerPhone() {
            return customerSnapshot != null ? customerSnapshot.getPhone() : "-";
        }
        public String getDeliveryMethod() {
            return deliveryDetails != null ? deliveryDetails.getMethod() : "pickup";
        }
        public double getDeliveryFee() {
            return deliveryDetails != null ? deliveryDetails.getFee() : 0;
        }
        public String getPaymentStatus() {
            return payment != null ? payment.getStatus() : "unpaid";
        }
        public String getPaymentProofUrl() {
            return payment != null ? payment.getProof() : null;
        }
        public boolean isPickup() {
            return "pickup".equals(getDeliveryMethod());
        }
        public boolean needsPaymentVerification() {
            return payment != null && payment.isPendingVerification();
        }
    }

    // ======================================================================
    // Nested: customer_snapshot
    // ======================================================================
    public static class CustomerSnapshot {
        @SerializedName("user_id")
        private String userId;



        @SerializedName("name")
        private String name;

        @SerializedName("phone")
        private String phone;

        public String getUserId() { return userId; }
        public String getName()   { return name != null ? name : "-"; }
        public String getPhone()  { return phone != null ? phone : "-"; }
    }

    // ======================================================================
    // Nested: items[]
    // ======================================================================
    public static class OrderItemDetail {
        @SerializedName("menu_id")
        private String menuId;

        @SerializedName("name")
        private String name;

        // Hati-hati: DB menyimpan price sebagai String ("15000"), bukan number
        @SerializedName("price")
        private String price;

        @SerializedName("quantity")
        private int quantity;

        @SerializedName("notes")
        private String notes;

        @SerializedName("estimated_cooking_time")
        private String estimatedCookingTime;

        @SerializedName("subtotal")
        private double subtotal;

        public String getMenuId()                { return menuId; }
        public String getName()                  { return name; }
        public String getPrice()                 { return price; }
        public int getQuantity()                 { return quantity; }
        public String getNotes()                 { return notes; }
        public String getEstimatedCookingTime()  { return estimatedCookingTime; }
        public double getSubtotal()              { return subtotal; }

        /** Konversi price String → double untuk kalkulasi */
        public double getPriceAsDouble() {
            try { return Double.parseDouble(price); }
            catch (Exception e) { return 0; }
        }
    }

    // ======================================================================
    // Nested: delivery_details
    // ======================================================================
    public static class DeliveryDetails {
        /** "pickup" atau "delivery" */
        @SerializedName("method")
        private String method;

        @SerializedName("fee")
        private double fee;

        @SerializedName("location_note")
        private String locationNote;

        public String getMethod()       { return method; }
        public double getFee()          { return fee; }
        public String getLocationNote() { return locationNote; }
    }

    // ======================================================================
    // Nested: payment
    // ======================================================================
    public static class PaymentInfo {
        /** "cash" atau "qris" */
        @SerializedName("method")
        private String method;

        /** "unpaid" | "paid" | "pending_verification" | "rejected" */
        @SerializedName("status")
        private String status;

        /** URL foto bukti bayar — bisa null */
        @SerializedName("proof")
        private String proof;

        @SerializedName("paid_at")
        private String paidAt;

        public String getMethod() { return method; }
        public String getStatus() { return status; }
        public String getProof()  { return proof; }
        public String getPaidAt() { return paidAt; }

        public boolean isPendingVerification() { return "pending_verification".equals(status); }
        public boolean isPaid()    { return "paid".equals(status); }
        public boolean isUnpaid()  { return "unpaid".equals(status); }
        public boolean isRejected(){ return "rejected".equals(status); }
    }
}