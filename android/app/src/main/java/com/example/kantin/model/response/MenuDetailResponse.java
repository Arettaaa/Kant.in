package com.example.kantin.model.response;

import com.google.gson.annotations.SerializedName;

/**
 * MenuDetailResponse — untuk POST/PUT menu (add/update menu)
 * Struktur data sama persis dengan MenuListResponse.MenuItem
 */
public class MenuDetailResponse {

    @SerializedName("success")
    private boolean success;

    @SerializedName("message")
    private String message;

    @SerializedName("data")
    private MenuListResponse.MenuItem data;

    public boolean isSuccess()                  { return success; }
    public String getMessage()                  { return message; }
    public MenuListResponse.MenuItem getData()  { return data; }
}