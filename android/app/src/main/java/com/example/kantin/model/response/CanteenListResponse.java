package com.example.kantin.model.response;
import java.util.List;

public class CanteenListResponse extends BaseResponse {
    private List<CanteenData> data;

    public List<CanteenData> getData() { return data; }

    public class CanteenData {
        private String _id;
        private String name;
        private String status;
        private boolean is_open;

        public String getName() { return name; }
        public boolean isOpen() { return is_open; }
    }
}