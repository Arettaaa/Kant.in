package com.example.kantin;

public class Order {
    // Variabel sesuai kebutuhan kartu pesanan di Dashboard Admin
    private String orderId;
    private String customerName;
    private String time;
    private String method; // "Ambil Sendiri" atau "Antar Kurir"
    private String menuSummary; // Contoh: "2x Nasi Goreng, 1x Es Teh"
    private String totalHarga;
    private String status; // "Menunggu", "Dimasak", "Siap", "Selesai"

    // 1. CONSTRUCTOR
    // Digunakan untuk membuat objek Order baru dengan data lengkap
    public Order(String orderId, String customerName, String time, String method, String menuSummary, String totalHarga, String status) {
        this.orderId = orderId;
        this.customerName = customerName;
        this.time = time;
        this.method = method;
        this.menuSummary = menuSummary;
        this.totalHarga = totalHarga;
        this.status = status;
    }

    // 2. GETTER & SETTER
    // Getter untuk mengambil data, Setter untuk mengubah data

    public String getOrderId() {
        return orderId;
    }

    public void setOrderId(String orderId) {
        this.orderId = orderId;
    }

    public String getCustomerName() {
        return customerName;
    }

    public void setCustomerName(String customerName) {
        this.customerName = customerName;
    }

    public String getTime() {
        return time;
    }

    public void setTime(String time) {
        this.time = time;
    }

    public String getMethod() {
        return method;
    }

    public void setMethod(String method) {
        this.method = method;
    }

    public String getMenuSummary() {
        return menuSummary;
    }

    public void setMenuSummary(String menuSummary) {
        this.menuSummary = menuSummary;
    }

    public String getTotalHarga() {
        return totalHarga;
    }

    public void setTotalHarga(String totalHarga) {
        this.totalHarga = totalHarga;
    }

    public String getStatus() {
        return status;
    }

    public void setStatus(String status) {
        this.status = status;
    }
}