package com.example.kantin.model.response;

import com.google.gson.annotations.SerializedName;
import java.util.List;

public class MenuListResponse extends BaseResponse {
    private List<MenuItem> data;
    public List<MenuItem> getData() { return data; }

    public static class MenuItem {
        @SerializedName("_id")
        private String id;

        @SerializedName("name")
        private String name;

        @SerializedName("description")
        private String description;

        @SerializedName("price")
        private Object price;

        @SerializedName("category")
        private String category;

        @SerializedName("image")
        private String image;

        @SerializedName("canteen_id")
        private String canteenId;

        @SerializedName("is_available")
        private boolean isAvailable;

        @SerializedName("estimated_cooking_time")
        private String estimatedCookingTime;

        // ── Rating fields ──────────────────────────────────────
        @SerializedName("average_rating")
        private double averageRating;

        @SerializedName("total_reviews")
        private int totalReviews;

        // Getters
        public String getId()                   { return id; }
        public String getName()                 { return name; }
        public String getDescription()          { return description; }
        public String getCategory()             { return category; }
        public String getImage()                { return image; }
        public String getCanteenId()            { return canteenId; }
        public boolean isAvailable()            { return isAvailable; }
        public String getEstimatedCookingTime() { return estimatedCookingTime; }
        public double getAverageRating()        { return averageRating; }
        public int getTotalReviews()            { return totalReviews; }

        public double getPriceAsDouble() {
            if (price == null) return 0;
            try { return Double.parseDouble(price.toString()); }
            catch (NumberFormatException e) { return 0; }
        }
    }
}