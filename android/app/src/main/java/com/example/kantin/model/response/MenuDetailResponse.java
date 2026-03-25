package com.example.kantin.model.response;

import com.google.gson.annotations.SerializedName;

public class MenuDetailResponse {
    @SerializedName("success")
    private boolean success;

    @SerializedName("message")
    private String message;

    @SerializedName("data")
    private MenuListResponse.MenuItem data;

    public boolean isSuccess()                   { return success; }
    public String getMessage()                   { return message; }
    public MenuListResponse.MenuItem getData()   { return data; }
}