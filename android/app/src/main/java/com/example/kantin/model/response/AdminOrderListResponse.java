package com.example.kantin.model.response;

import com.example.kantin.model.OrderModel; // Import OrderModel yang baru
import com.google.gson.annotations.SerializedName;
import java.util.List;

public class AdminOrderListResponse extends BaseResponse {

    @SerializedName("data")
    private List<OrderModel> data; // Ubah tipe list ke OrderModel

    public List<OrderModel> getData() {
        return data;
    }
}