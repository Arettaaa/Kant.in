package com.example.kantin.network;// Import untuk Request (Data yang dikirim ke Laravel)
import com.example.kantin.model.request.ForgotPasswordRequest;
import com.example.kantin.model.request.LoginRequest;
import com.example.kantin.model.request.RegisterPelangganRequest;
import com.example.kantin.model.request.RegisterAdminKantinRequest;
import com.example.kantin.model.request.AddToCartRequest;
import com.example.kantin.model.request.ResetPasswordRequest;
import com.example.kantin.model.request.UpdateCartRequest;
import com.example.kantin.model.request.CheckoutRequest;
import com.example.kantin.model.request.UpdateStatusOrderRequest;

// Import untuk Response (Data yang diterima dari Laravel)
import com.example.kantin.model.response.AdminOrderListResponse;
import com.example.kantin.model.response.BaseResponse;
import com.example.kantin.model.response.CanteenDetailResponse;
import com.example.kantin.model.response.LoginResponse;
import com.example.kantin.model.response.MenuListResponse;
import com.example.kantin.model.response.MenuDetailResponse;
import com.example.kantin.model.response.CartResponse;
import com.example.kantin.model.response.CanteenListResponse;
import com.example.kantin.model.response.OrderListResponse;
import com.example.kantin.model.response.OrderDetailResponse;
import com.example.kantin.model.response.ProfileResponse;
import com.example.kantin.model.response.ProfileAdminResponse;
import com.example.kantin.model.response.TransactionListResponse;
import com.example.kantin.model.response.DashboardResponse;
import com.example.kantin.model.response.RegisterResponse;

// Import library Retrofit & OkHttp
import java.util.List;

import okhttp3.MultipartBody;
import okhttp3.RequestBody;
import retrofit2.Call;
import retrofit2.http.Body;
import retrofit2.http.DELETE;
import retrofit2.http.GET;
import retrofit2.http.Header;
import retrofit2.http.Headers;
import retrofit2.http.Multipart;
import retrofit2.http.POST;
import retrofit2.http.PUT;
import retrofit2.http.Part;
import retrofit2.http.Path;
import retrofit2.http.Query;

/**
 * ApiService — semua endpoint yang dipakai Admin Kantin.
 * Disesuaikan dengan controller Laravel yang sebenarnya.
 */
public interface ApiService {

    // ================================================================
    // 1. AUTENTIKASI — AuthController
    // ================================================================

    /**
     * Login → dapat token
     * POST /api/auth/sessions
     * Response: { message, token, user } — FLAT, tidak ada wrapper success/data
     */
    @POST("auth/sessions")
    Call<LoginResponse> login(@Body LoginRequest request);

    /**
     * Logout → hapus token di server
     * DELETE /api/auth/sessions
     * Response: { message }
     */
    @DELETE("auth/sessions")
    Call<BaseResponse> logout();

    @POST("auth/forgot-password")
    Call<BaseResponse> forgotPassword(@Body ForgotPasswordRequest request);

    @POST("auth/reset-password")
    Call<BaseResponse> resetPassword(@Body ResetPasswordRequest request);

    /**
     * Register Admin Kantin
     * POST /api/auth/register
     * Response: { message, user } — TANPA token (harus tunggu approve)
     */
    @POST("auth/register")
    Call<RegisterResponse> registerPelanggan(@Body RegisterPelangganRequest request);

    @POST("auth/register")
    Call<BaseResponse> registerAdminKantin(@Body RegisterAdminKantinRequest request);


    //    @POST("auth/register")
    //    Call<BaseResponse> registerAdminKantin(@Body RegisterAdminKantinRequest request);

    // 1. Daftar Kantin
    @GET("canteens")
    Call<CanteenListResponse> getAllCanteens();

    // 2. Detail Kantin
    @GET("canteens/{id}")
    Call<CanteenDetailResponse> getCanteenDetail(@Path("id") String canteenId);

    // 3. Menu per Kantin
    @GET("canteens/{id}/menus")
    Call<MenuListResponse> getCanteenMenus(@Path("id") String canteenId);

    // 4. Semua Menu (Explore)
    @GET("menus")
    Call<MenuListResponse> getAllMenus();

    // Detail menu by ID
    @GET("menus/{menuId}")
    Call<MenuDetailResponse> getMenuDetail(@Path("menuId") String menuId);

    // ================================================================
    // 2. MENU — MenuController
    // ================================================================

    /**
     * Lihat semua menu kantin
     * GET /api/canteens/{canteenId}/menus
     * Support query: ?search=keyword&category=makanan
     */
    @GET("canteens/{canteenId}/menus")
    Call<MenuListResponse> getMenus(
            @Path("canteenId") String canteenId,
            @Query("search") String search,      // nullable
            @Query("category") String category   // nullable
    );

    /** Lihat status ketersediaan semua menu */
    @GET("canteens/{canteenId}/menus/availabilities")
    Call<MenuListResponse> getMenuAvailabilities(@Path("canteenId") String canteenId);

    /**
     * Tambah menu baru
     * POST /api/canteens/{canteenId}/menus
     * Field: name*, description, price* (integer), category*, image (file), estimated_cooking_time
     */
    @Multipart
    @POST("canteens/{canteenId}/menus")
    Call<MenuDetailResponse> addMenu(
            @Path("canteenId") String canteenId,
            @Part("name") RequestBody name,
            @Part("description") RequestBody description,
            @Part("price") RequestBody price,
            @Part("category") RequestBody category,
            @Part("estimated_cooking_time") RequestBody estimatedCookingTime,
            @Part MultipartBody.Part image  // nullable — kirim null jika tidak ada foto
    );

    /**
     * Edit menu
     * PUT /api/canteens/{canteenId}/menus/{menuId}
     * Laravel tidak support PUT multipart → pakai POST + _method: PUT
     */

    // UPDATE MENU (Laravel butuh @POST + _method=PUT jika ada file Multipart)
    @Multipart
    @POST("canteens/{canteenId}/menus/{menuId}")
    Call<MenuDetailResponse> updateMenu(
            @Path("canteenId") String canteenId,
            @Path("menuId") String menuId,
            @Part("_method") RequestBody method, // WAJIB UNTUK LARAVEL
            @Part("name") RequestBody name,
            @Part("price") RequestBody price,
            @Part("category") RequestBody category,
            @Part("estimated_cooking_time") RequestBody cookingTime,
            @Part("description") RequestBody description,
            @Part("is_available") RequestBody isAvailable,
            @Part MultipartBody.Part image // Opsional jika foto diganti
    );

    /**
     * Toggle ketersediaan menu (available/unavailable)
     * PUT /api/canteens/{canteenId}/menus/{menuId}/availabilities
     * Body: { is_available: 0|1|true|false }
     */
    @Multipart
    @POST("canteens/{canteenId}/menus/{menuId}/availabilities")
    Call<MenuDetailResponse> toggleMenuAvailability(
            @Path("canteenId") String canteenId,
            @Path("menuId") String menuId,
            @Part("_method") RequestBody method,        // "PUT"
            @Part("is_available") RequestBody isAvailable // "1" atau "0"
    );

    /**
     * Hapus menu
     * DELETE /api/canteens/{canteenId}/menus/{menuId}
     */
    @DELETE("canteens/{canteenId}/menus/{menuId}")
    Call<BaseResponse> deleteMenu(
            @Path("canteenId") String canteenId,
            @Path("menuId") String menuId
    );

    // ================================================================
    // 3. PESANAN — OrderController
    // ================================================================

    /**
     * Lihat semua pesanan masuk di kantin
     * GET /api/canteens/{canteenId}/orders
     * Optional filter: ?status=pending|processing|ready|completed|cancelled
     *
     * Status order: pending | processing | ready | completed | cancelled
     */
    @GET("canteens/{canteenId}/orders")
    Call<OrderListResponse> getOrders(
            @Path("canteenId") String canteenId,
            @Query("status") String status  // nullable — null = semua status
    );

    /**
     * Update status pesanan
     * PUT /api/canteens/{canteenId}/orders/{orderId}/statuses
     * Body: { status: "processing"|"ready"|"completed"|"cancelled" }
     */
    @PUT("canteens/{canteenId}/orders/{orderId}/statuses")
    Call<BaseResponse> updateOrderStatus(
            @Path("canteenId") String canteenId,
            @Path("orderId") String orderId,
            @Body UpdateStatusOrderRequest request
    );

    /**
     * Verifikasi bukti pembayaran → status payment jadi "paid", order jadi "processing"
     * POST /api/canteens/{canteenId}/orders/{orderId}/payments/verify
     * Hanya bisa jika payment.status = "pending_verification"
     */
    @POST("canteens/{canteenId}/orders/{orderId}/payments/verify")
    Call<BaseResponse> verifyPayment(
            @Path("canteenId") String canteenId,
            @Path("orderId") String orderId
    );

    /**
     * Tolak bukti pembayaran → status payment jadi "rejected", order jadi "cancelled"
     * POST /api/canteens/{canteenId}/orders/{orderId}/payments/reject
     */
    @POST("canteens/{canteenId}/orders/{orderId}/payments/reject")
    Call<BaseResponse> rejectPayment(
            @Path("canteenId") String canteenId,
            @Path("orderId") String orderId
    );

    // ================================================================
    // 4. KANTIN — CanteenController
    // ================================================================

    /**
     * Toggle buka/tutup kantin
     * PUT /api/canteens/{canteenId}/availability
     * Body: { is_open: 0|1|true|false }
     * Hanya bisa untuk kantinnya sendiri
     */
    @Multipart
    @POST("canteens/{canteenId}/availability")
    Call<BaseResponse> toggleCanteenOpen(
            @Path("canteenId") String canteenId,
            @Part("_method") RequestBody method,   // "PUT"
            @Part("is_open") RequestBody isOpen    // "1" atau "0"
    );

    // ================================================================
    // 5. PROFIL — ProfileController
    // ================================================================

    /**
     * Lihat profil admin kantin
     * GET /api/admin/profiles
     * Response: { success, data: { _id, name, email, phone, role,
     *             canteen_id, status, photo_profile, ... } }
     */
    @GET("admin/profiles")
    Call<ProfileAdminResponse> getProfile();

    /**
     * Update profil admin kantin (nama, phone, foto)
     * PUT /api/admin/profiles → pakai POST + _method: PUT karena ada file
     * Field: name (sometimes), phone (sometimes), photo_profile (file, nullable)
     */
    @Multipart
    @POST("admin/profiles")
    Call<ProfileAdminResponse> updateProfile(
            @Part("name") RequestBody name,
            @Part("phone") RequestBody phone,
            @Part List<MultipartBody.Part> photoProfile  // kosong jika tidak ada foto baru
    );

    /**
     * Ganti password — pakai endpoint profil yang sama
     * PUT /api/admin/profiles
     * Field: password (min:8), password_confirmation
     * Pakai Multipart juga karena endpoint sama
     */
    @Multipart
    @POST("admin/profiles")
    Call<BaseResponse> updatePassword(
            @Part("_method") String method,              // "PUT"
            @Part("password") RequestBody password,
            @Part("password_confirmation") RequestBody passwordConfirmation
    );

    // ================================================================
    // 6. TRANSAKSI — TransactionController
    // ================================================================

    /**
     * Laporan transaksi completed per kantin
     * GET /api/canteens/{canteenId}/transactions
     * Response: { success, data: { total_revenue, total_orders, orders: [...] } }
     */
    @GET("canteens/{canteenId}/transactions")
    Call<TransactionListResponse> getTransactions(@Path("canteenId") String canteenId);

    /**
     * Dashboard per kantin — hanya admin global
     * GET /api/canteens/{canteenId}/dashboard
     */
    @GET("canteens/{canteenId}/dashboard")
    Call<DashboardResponse> getDashboard(@Path("canteenId") String canteenId);

    // ================================================================
    // FITUR PEMBELI (Prefix: buyers)
    // ================================================================

//    @POST("auth/register")
//    Call<BaseResponse> register(@Body RegisterPelangganRequest request);

    /** 1. KERANJANG (Cart) **/
//    @GET("buyers/carts")
//    Call<CartResponse> getMyCart();
//
//    @POST("buyers/carts/items")
//    Call<BaseResponse> addToCart(@Body AddToCartRequest request);
//
//    @PUT("buyers/carts/items/{itemId}")
//    Call<BaseResponse> updateCartItem(@Path("itemId") String itemId, @Body UpdateCartRequest request);
//
//    @DELETE("buyers/carts/items/{itemId}")
//    Call<BaseResponse> removeFromCart(@Path("itemId") String itemId);

    // GET cart
    @GET("buyers/carts")
    Call<CartResponse> getCart();

    // POST add item
    @POST("buyers/carts/items")
    Call<CartResponse> addToCart(@Body AddToCartRequest request);

    // PUT update quantity
    @PUT("buyers/carts/items/{itemId}")
    Call<CartResponse> updateCartItem(
            @Path("itemId") String itemId,
            @Body UpdateCartRequest request   // { "quantity": int }
    );

    // DELETE remove item
    @DELETE("buyers/carts/items/{itemId}")
    Call<CartResponse> removeCartItem(@Path("itemId") String itemId);

    /** 2. PESANAN (Order & Checkout) **/

    @Multipart
    @POST("buyers/checkouts")
    Call<OrderDetailResponse> checkout(
            @Part("canteen_id") RequestBody canteenId,
            @Part("delivery_method") RequestBody deliveryMethod,
            @Part("location_note") RequestBody locationNote, // bisa null
            @Part("order_notes") RequestBody orderNotes, // bisa null
            @Part("menu_ids[]") List<RequestBody> menuIds,
            @Part MultipartBody.Part paymentProof
    );

    @GET("buyers/orders/histories")
    Call<OrderListResponse> getOrderHistory();

    @GET("buyers/orders/{orderId}")
    Call<OrderDetailResponse> getOrderDetail(@Path("orderId") String orderId);

    @POST("buyers/orders/{orderId}/cancellations")
    Call<BaseResponse> cancelOrder(@Path("orderId") String orderId);

    @POST("buyers/orders/{orderId}/completions")
    Call<BaseResponse> completeOrder(@Path("orderId") String orderId);

    /** 3. PROFIL (Pelanggan) **/
    @GET("buyers/profiles")
    Call<ProfileResponse> getBuyerProfile(@Header("Authorization") String token);

    @Headers("Accept: application/json")
    @Multipart
    @POST("buyers/profiles")
    Call<ProfileResponse> updateProfileBuyers(
            @Header("Authorization") String token,
            @Part("name") RequestBody name,
            @Part("phone") RequestBody phone,
            @Part MultipartBody.Part photo_profile
    );

    @Headers("Accept: application/json")
    @Multipart
    @POST("buyers/profiles")
    Call<BaseResponse> updatePasswordBuyers(
            @Header("Authorization") String token,
            @Part("old_password") RequestBody oldPassword,
            @Part("password") RequestBody password,
            @Part("password_confirmation") RequestBody confirmation
    );

    @GET("canteens/{canteenId}/orders")
    Call<AdminOrderListResponse> getAdminOrders(
            @Path("canteenId") String canteenId,
            @Query("status") String status
    );

    @Multipart
    @POST("canteens/{canteenId}/menus")
    Call<MenuDetailResponse> createMenu(
            @Path("canteenId") String canteenId,
            @Part("name") RequestBody name,
            @Part("price") RequestBody price,
            @Part("category") RequestBody category,
            @Part("estimated_cooking_time") RequestBody cookingTime,
            @Part("description") RequestBody description,
            @Part("is_available") RequestBody isAvailable,
            @Part MultipartBody.Part image
    );



    @GET("canteens/{id}/settings")
    Call<CanteenDetailResponse> getCanteenSettings(@Path("id") String canteenId);

    @Multipart
    @POST("canteens/{id}/settings")
    Call<CanteenDetailResponse> updateCanteenSettings(
            @Path("id") String canteenId,
            @Part("_method") RequestBody method,          // WAJIB: isi "PUT"
            @Part("description") RequestBody description,
            @Part("phone") RequestBody phone,
            @Part("delivery_fee_flat") RequestBody deliveryFee,
            @Part("operating_hours[open]") RequestBody openTime,
            @Part("operating_hours[close]") RequestBody closeTime,
            @Part List<MultipartBody.Part> files);




}