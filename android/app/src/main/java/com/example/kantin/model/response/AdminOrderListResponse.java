package com.example.kantin.model.response;

import com.example.kantin.ApiOrder;
import com.google.gson.annotations.SerializedName;
import java.util.List;

public class AdminOrderListResponse extends BaseResponse {

    @SerializedName("data")
    private List<ApiOrder> data;

    public List<ApiOrder> getData() {
        return data;
    }
}