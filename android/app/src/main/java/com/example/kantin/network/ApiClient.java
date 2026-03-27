package com.example.kantin.network;

import okhttp3.OkHttpClient;
import okhttp3.logging.HttpLoggingInterceptor;
import retrofit2.Retrofit;
import retrofit2.converter.gson.GsonConverterFactory;
import java.util.concurrent.TimeUnit;

/**
 * ApiClient — satu-satunya tempat membuat instance Retrofit.
 *
 * Cara pakai di Activity:
 *   ApiService api = ApiClient.getClient().create(ApiService.class);
 *
 * Kalau butuh token (endpoint yang perlu login):
 *   ApiService api = ApiClient.getAuthClient(token).create(ApiService.class);
 */
public class ApiClient {

    // ============================================================
    //  GANTI URL INI dengan IP server / domain backend kamu
    //  Contoh emulator  : "http://10.0.2.2:8000/api/"
    //  Contoh device HP : "http://192.168.1.100:8000/api/"
    //  Contoh production: "https://api.kantin.com/api/"
    // ============================================================
//    private static final String BASE_URL = "http://10.0.2.2:8000/api/";

    public static final String BASE_URL = "https://kantin-production.up.railway.app/api/";
//    public static final String BASE_URL = "https://nonephemerally-nonrevolving-judie.ngrok-free.dev/api/";
    private static Retrofit retrofit = null;
    private static Retrofit authRetrofit = null;

    // ----------------------------------------------------------------
    // Client tanpa token — untuk endpoint public (login, register, dll)
    // ----------------------------------------------------------------
    public static Retrofit getClient() {
        if (retrofit == null) {
            HttpLoggingInterceptor logging = new HttpLoggingInterceptor();
            logging.setLevel(HttpLoggingInterceptor.Level.BODY);

            OkHttpClient client = new OkHttpClient.Builder()
                    .addInterceptor(logging)
                    .addInterceptor(chain -> {
                        okhttp3.Request original = chain.request();
                        okhttp3.Request request = original.newBuilder()
                                .header("Accept", "application/json")
                                .method(original.method(), original.body())
                                .build();
                        return chain.proceed(request);
                    })
                    .connectTimeout(30, TimeUnit.SECONDS)
                    .build();

            retrofit = new Retrofit.Builder()
                    .baseUrl(BASE_URL)
                    .client(client)
                    .addConverterFactory(GsonConverterFactory.create())
                    .build();
        }
        return retrofit;
    }
    // ----------------------------------------------------------------
    // Client dengan Bearer Token — untuk endpoint yang butuh login
    // token = string token dari SessionManager
    // ----------------------------------------------------------------
    public static Retrofit getAuthClient(String token) {
        HttpLoggingInterceptor logging = new HttpLoggingInterceptor();
        logging.setLevel(HttpLoggingInterceptor.Level.BODY);

        OkHttpClient client = new OkHttpClient.Builder()
                .addInterceptor(logging)
                // Interceptor: otomatis tambahkan header Authorization ke setiap request
                .addInterceptor(chain -> {
                    okhttp3.Request original = chain.request();
                    okhttp3.Request request = original.newBuilder()
                            .header("Authorization", "Bearer " + token)
                            .header("Accept", "application/json")
                            .method(original.method(), original.body())
                            .build();
                    return chain.proceed(request);
                })
                .connectTimeout(30, TimeUnit.SECONDS)
                .readTimeout(30, TimeUnit.SECONDS)
                .writeTimeout(30, TimeUnit.SECONDS)
                .build();

        // Selalu buat instance baru agar token tidak cached
        return new Retrofit.Builder()
                .baseUrl(BASE_URL)
                .client(client)
                .addConverterFactory(GsonConverterFactory.create())
                .build();
    }
}