package com.example.kantin.model.response;

import com.google.gson.annotations.SerializedName;
import java.util.List;

/**
 * MenuListResponse — disesuaikan dengan DB asli.
 *
 * Field DB:
 *   _id, name, description, price (String), category,
 *   stock, estimated_cooking_time, canteen_id,
 *   image (bukan photo_url!), is_available,
 *   created_at, updated_at
 */
public class MenuListResponse {

    @SerializedName("success")
    private boolean success;

    @SerializedName("message")
    private String message;

    @SerializedName("data")
    private List<MenuItem> data;

    public boolean isSuccess()       { return success; }
    public String getMessage()       { return message; }
    public List<MenuItem> getData()  { return data; }

    // ======================================================================
    // Model satu item menu
    // ======================================================================
    public static class MenuItem {

        @SerializedName("_id")
        private String id;

        @SerializedName("canteen_id")
        private String canteenId;

        @SerializedName("name")
        private String name;

        // Hati-hati: price di DB bisa String ("15000") atau number (18000)
        // Gson akan handle otomatis jika tipe String
        @SerializedName("price")
        private String price;

        @SerializedName("category")
        private String category;

        @SerializedName("description")
        private String description;

        @SerializedName("stock")
        private Integer stock;

        @SerializedName("estimated_cooking_time")
        private String estimatedCookingTime;

        // Nama field di DB adalah "image", BUKAN "photo_url"
        @SerializedName("image")
        private String image;

        @SerializedName("is_available")
        private boolean isAvailable;

        @SerializedName("created_at")
        private String createdAt;

        @SerializedName("updated_at")
        private String updatedAt;

        public String getId()                   { return id; }
        public String getCanteenId()            { return canteenId; }
        public String getName()                 { return name; }
        public String getPrice()                { return price; }
        public String getCategory()             { return category; }
        public String getDescription()          { return description; }
        public Integer getStock()               { return stock; }
        public String getEstimatedCookingTime() { return estimatedCookingTime; }
        /** URL gambar menu — field asli DB bernama "image" */
        public String getImage()                { return image; }
        public boolean isAvailable()            { return isAvailable; }
        public String getCreatedAt()            { return createdAt; }
        public String getUpdatedAt()            { return updatedAt; }

        /** Harga sebagai double untuk kalkulasi & tampilan */
        public double getPriceAsDouble() {
            try { return Double.parseDouble(price); }
            catch (Exception e) { return 0; }
        }
    }
}