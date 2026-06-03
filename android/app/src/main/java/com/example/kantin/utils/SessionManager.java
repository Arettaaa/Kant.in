package com.example.kantin.utils;

import android.content.Context;
import android.content.SharedPreferences;

/**
 * SessionManager — menyimpan dan mengambil data sesi login.
 *
 * Cara pakai:
 *   SessionManager session = new SessionManager(this);
 *   session.saveSession(token, userId, canteenId, "admin_kantin");
 *   String token = session.getToken();
 *   session.clearSession(); // saat logout
 */
public class SessionManager {

    private static final String PREF_NAME = "KantinSession";

    private static final String KEY_TOKEN        = "token";
    private static final String KEY_USER_ID      = "user_id";
    private static final String KEY_CANTEEN_ID   = "canteen_id";
    private static final String KEY_USER_ROLE    = "user_role";
    private static final String KEY_USER_NAME    = "user_name";
    private static final String KEY_USER_EMAIL   = "user_email";
    private static final String KEY_USER_PHONE   = "user_phone"; // Kunci HP
    private static final String KEY_PHOTO_URL    = "photo_url";  // Kunci Foto
    private static final String KEY_IS_LOGGED_IN = "is_logged_in";

    public static final String ROLE_ADMIN_KANTIN = "admin_kantin";
    public static final String ROLE_PEMBELI      = "buyer";
    public static final String ROLE_ADMIN_GLOBAL = "admin_global";

    private static final String KEY_CANTEEN_NAME = "canteen_name";

    private final SharedPreferences prefs;
    private final SharedPreferences.Editor editor;

    public SessionManager(Context context) {
        prefs  = context.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE);
        editor = prefs.edit();
    }

    public void saveCanteenName(String canteenName) {
        editor.putString(KEY_CANTEEN_NAME, canteenName);
        editor.apply();
    }
    public void saveSession(String token, String userId, String canteenId, String role) {
        editor.putBoolean(KEY_IS_LOGGED_IN, true);
        editor.putString(KEY_TOKEN, token);
        editor.putString(KEY_USER_ID, userId);
        editor.putString(KEY_CANTEEN_ID, canteenId);
        editor.putString(KEY_USER_ROLE, role);
        editor.apply();
    }

    public void saveUserInfo(String name, String email, String phone) {
        editor.putString(KEY_USER_NAME, name);
        editor.putString(KEY_USER_EMAIL, email);
        editor.putString(KEY_USER_PHONE, phone); // Ini kuncinya!
        editor.apply();
    }

    public void savePhotoUrl(String photoUrl) {
        editor.putString(KEY_PHOTO_URL, photoUrl);
        editor.apply();
    }

    /** Update canteen ID (khusus admin kantin) */
    public void saveCanteenId(String canteenId) {
        editor.putString(KEY_CANTEEN_ID, canteenId);
        editor.apply();
    }

    public boolean isLoggedIn()   { return prefs.getBoolean(KEY_IS_LOGGED_IN, false); }
    public String getToken()      { return prefs.getString(KEY_TOKEN, ""); }
    public String getUserId()     { return prefs.getString(KEY_USER_ID, ""); }
    public String getCanteenId()  { return prefs.getString(KEY_CANTEEN_ID, ""); }
    public String getUserRole()   { return prefs.getString(KEY_USER_ROLE, ""); }
    public String getUserName()   { return prefs.getString(KEY_USER_NAME, ""); }
    public String getUserEmail()  { return prefs.getString(KEY_USER_EMAIL, ""); }
    public String getUserPhone()  { return prefs.getString(KEY_USER_PHONE, ""); }
    public String getPhotoUrl()   { return prefs.getString(KEY_PHOTO_URL, ""); }

    public boolean isAdminKantin() {
        return ROLE_ADMIN_KANTIN.equals(getUserRole());
    }

    public String getCanteenName() {
        return prefs.getString(KEY_CANTEEN_NAME, null);
    }
    public void clearSession() {
        editor.clear();
        editor.apply();
    }


}