package com.example.kantin.model.response;

import com.google.gson.annotations.SerializedName;

// Detail satu order
public class OrderDetailResponse {
    @SerializedName("success")
    private boolean success;

    @SerializedName("message")
    private String message;

    @SerializedName("data")
    private OrderListResponse.OrderItem data;

    public boolean isSuccess()                      { return success; }
    public String getMessage()                      { return message; }
    public OrderListResponse.OrderItem getData()    { return data; }
}