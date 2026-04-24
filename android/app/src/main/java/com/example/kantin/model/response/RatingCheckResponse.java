package com.example.kantin.model.response;

import com.google.gson.annotations.SerializedName;

public class RatingCheckResponse {

    @SerializedName("success")
    private boolean success;

    @SerializedName("data")
    private Data data;

    public boolean isSuccess() { return success; }
    public Data getData()      { return data; }

    public static class Data {

        @SerializedName("has_rated")
        private boolean hasRated;

        // Nilai rating 1-5, 0 kalau belum pernah rating
        @SerializedName("rating")
        private int rating;

        public boolean isHasRated() { return hasRated; }
        public int getRating()      { return rating; }
    }
}