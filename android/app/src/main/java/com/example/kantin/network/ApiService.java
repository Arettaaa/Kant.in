package com.example.kantin.network;

import com.example.kantin.model.request.LoginRequest;
import com.example.kantin.model.request.RegisterAdminKantinRequest;
import com.example.kantin.model.request.UpdateStatusOrderRequest;
import com.example.kantin.model.request.UpdateProfilAdminRequest;
import com.example.kantin.model.request.UpdatePasswordRequest;
import com.example.kantin.model.response.BaseResponse;
import com.example.kantin.model.response.LoginResponse;
import com.example.kantin.model.response.MenuListResponse;
import com.example.kantin.model.response.MenuDetailResponse;
import com.example.kantin.model.response.OrderListResponse;
import com.example.kantin.model.response.OrderDetailResponse;
import com.example.kantin.model.response.ProfileAdminResponse;
import com.example.kantin.model.response.TransactionListResponse;
import com.example.kantin.model.response.DashboardResponse;

import okhttp3.MultipartBody;
import okhttp3.RequestBody;
import retrofit2.Call;
import retrofit2.http.Body;
import retrofit2.http.DELETE;
import retrofit2.http.GET;
import retrofit2.http.Multipart;
import retrofit2.http.POST;
import retrofit2.http.PUT;
import retrofit2.http.Part;
import retrofit2.http.Path;

/**
 * ApiService — daftar semua endpoint yang dipakai Admin Kantin.
 * Sesuai dengan API Documentation Kant.in v1.0
 */
public interface ApiService {

    // ================================================================
    // 1. AUTENTIKASI
    // ================================================================

    /** Login → dapat token */
    @POST("auth/sessions")
    Call<LoginResponse> login(@Body LoginRequest request);

    /** Logout → hapus token di server */
    @DELETE("auth/sessions")
    Call<BaseResponse> logout();

    /** Register Admin Kantin */
    @POST("auth/register")
    Call<BaseResponse> registerAdminKantin(@Body RegisterAdminKantinRequest request);

    // ================================================================
    // 2. MENU — Admin Kantin
    // ================================================================

    /** Lihat semua menu kantin (public, tidak perlu token) */
    @GET("canteens/{canteenId}/menus")
    Call<MenuListResponse> getMenus(@Path("canteenId") String canteenId);

    /**
     * Tambah menu baru (dengan foto — pakai Multipart)
     * @param name     nama menu
     * @param price    harga (angka)
     * @param category kategori (Makanan / Minuman / dll)
     * @param description deskripsi menu
     * @param photo    file foto menu (opsional)
     */
    @Multipart
    @POST("canteens/{canteenId}/menus")
    Call<MenuDetailResponse> addMenu(
            @Path("canteenId") String canteenId,
            @Part("name") RequestBody name,
            @Part("price") RequestBody price,
            @Part("category") RequestBody category,
            @Part("description") RequestBody description,
            @Part MultipartBody.Part photo  // bisa null jika tidak upload foto
    );

    /**
     * Edit menu (dengan foto — pakai Multipart + _method: PUT)
     * Laravel tidak mendukung PUT dengan multipart, jadi pakai POST + _method spoofing
     */
    @Multipart
    @POST("canteens/{canteenId}/menus/{menuId}")
    Call<MenuDetailResponse> updateMenu(
            @Path("canteenId") String canteenId,
            @Path("menuId") String menuId,
            @Part("_method") RequestBody method,      // isi: "PUT"
            @Part("name") RequestBody name,
            @Part("price") RequestBody price,
            @Part("category") RequestBody category,
            @Part("description") RequestBody description,
            @Part MultipartBody.Part photo            // bisa null
    );

    /** Toggle ketersediaan menu (available / unavailable) */
    @PUT("canteens/{canteenId}/menus/{menuId}/availabilities")
    Call<BaseResponse> toggleMenuAvailability(
            @Path("canteenId") String canteenId,
            @Path("menuId") String menuId
    );

    /** Hapus menu */
    @DELETE("canteens/{canteenId}/menus/{menuId}")
    Call<BaseResponse> deleteMenu(
            @Path("canteenId") String canteenId,
            @Path("menuId") String menuId
    );

    // ================================================================
    // 3. PESANAN — Admin Kantin
    // ================================================================

    /** Lihat semua pesanan masuk di kantin */
    @GET("canteens/{canteenId}/orders")
    Call<OrderListResponse> getOrders(@Path("canteenId") String canteenId);

    /** Lihat detail satu pesanan */
    @GET("canteens/{canteenId}/orders/{orderId}")
    Call<OrderDetailResponse> getOrderDetail(
            @Path("canteenId") String canteenId,
            @Path("orderId") String orderId
    );

    /**
     * Update status pesanan
     * status: "processing" | "ready" | "completed"
     */
    @PUT("canteens/{canteenId}/orders/{orderId}/statuses")
    Call<BaseResponse> updateOrderStatus(
            @Path("canteenId") String canteenId,
            @Path("orderId") String orderId,
            @Body UpdateStatusOrderRequest request
    );

    /** Verifikasi bukti pembayaran → terima pesanan */
    @POST("canteens/{canteenId}/orders/{orderId}/payments/verify")
    Call<BaseResponse> verifyPayment(
            @Path("canteenId") String canteenId,
            @Path("orderId") String orderId
    );

    /** Tolak bukti pembayaran */
    @POST("canteens/{canteenId}/orders/{orderId}/payments/reject")
    Call<BaseResponse> rejectPayment(
            @Path("canteenId") String canteenId,
            @Path("orderId") String orderId
    );

    // ================================================================
    // 4. KANTIN — Admin Kantin
    // ================================================================

    /** Toggle buka/tutup kantin */
    @PUT("canteens/{canteenId}/availability")
    Call<BaseResponse> toggleCanteenAvailability(@Path("canteenId") String canteenId);

    // ================================================================
    // 5. PROFIL — Admin Kantin
    // ================================================================

    /** Lihat profil admin kantin */
    @GET("admin/profiles")
    Call<ProfileAdminResponse> getProfile();

    /**
     * Update profil admin kantin (nama, foto, password)
     * Pakai Multipart karena bisa upload foto
     */
    @Multipart
    @POST("admin/profiles")
    Call<ProfileAdminResponse> updateProfile(
            @Part("_method") RequestBody method,     // isi: "PUT"
            @Part("name") RequestBody name,
            @Part("email") RequestBody email,
            @Part MultipartBody.Part photo           // bisa null
    );

    /** Ganti password */
    @PUT("admin/profiles")
    Call<BaseResponse> updatePassword(@Body UpdatePasswordRequest request);

    // ================================================================
    // 6. TRANSAKSI & DASHBOARD — Admin Kantin
    // ================================================================

    /** Laporan transaksi completed per kantin */
    @GET("canteens/{canteenId}/transactions")
    Call<TransactionListResponse> getTransactions(@Path("canteenId") String canteenId);

    /** Dashboard detail per kantin */
    @GET("canteens/{canteenId}/dashboard")
    Call<DashboardResponse> getDashboard(@Path("canteenId") String canteenId);
}