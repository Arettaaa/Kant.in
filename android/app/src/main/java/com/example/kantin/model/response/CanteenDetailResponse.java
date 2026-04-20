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
        @SerializedName("_id")
        private String id;

        @SerializedName("name")
        private String name;

        @SerializedName("description")
        private String description;

        @SerializedName("location")
        private String location;

        @SerializedName("image")
        private String image;

        @SerializedName("is_open")
        private boolean isOpen;

        @SerializedName("delivery_fee_flat")
        private double deliveryFeeFlat;

        @SerializedName("operating_hours")
        private OperatingHours operatingHours;

        public String getId()              { return id; }
        public String getName()            { return name; }
        public String getDescription()     { return description; }
        public String getLocation()        { return location; }
        public String getImage()           { return image; }
        public boolean isOpen()            { return isOpen; }
        public double getDeliveryFeeFlat() { return deliveryFeeFlat; }
        public OperatingHours getOperatingHours() { return operatingHours; }

        public static class OperatingHours {
            @SerializedName("open")
            private String open;

            @SerializedName("close")
            private String close;

            public String getOpen()  { return open; }
            public String getClose() { return close; }
        }
    }
}