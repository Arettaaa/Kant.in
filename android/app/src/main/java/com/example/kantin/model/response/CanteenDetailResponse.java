package com.example.kantin.model.response;

import com.google.gson.annotations.SerializedName;

public class CanteenDetailResponse {
    @SerializedName("success")
    private boolean success;
    @SerializedName("data")
    private CanteenDetail data;

    public boolean isSuccess() { return success; }
    public CanteenDetail getData() { return data; }

    public static class CanteenDetail {
        private String id;
        private String name;
        private String description;
        private String location;
        private String image;
        @SerializedName("is_open")
        private boolean isOpen;

        // Getter
        public String getId() { return id; }
        public String getName() { return name; }
        public String getDescription() { return description; }
        public String getLocation() { return location; }
        public String getImage() { return image; }
        public boolean isOpen() { return isOpen; }
    }
}