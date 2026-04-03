package com.example.kantin.model.response;

import com.google.gson.annotations.SerializedName;
import java.util.List;

public class CanteenListResponse extends BaseResponse {
    private List<CanteenData> data;

    public List<CanteenData> getData() { return data; }

    public static class CanteenData {
        private String id;
        private String name;
        private String status;
        private boolean is_open;
        private String image;

        @SerializedName("operating_hours")
        private OperatingHours operatingHours;

        public OperatingHours getOperatingHours() {
            return operatingHours;
        }

        public String getId() { return id; }
        public String getName() { return name; }
        public String getStatus() { return status; }
        public boolean isOpen() { return is_open; }
        public String getImage() { return image; }

        public static class OperatingHours {
            private String open;
            private String close;
            public String getOpen() { return open; }
            public String getClose() { return close; }
        }
    }
}