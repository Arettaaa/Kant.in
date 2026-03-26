package com.example.kantin.model.request;

public class RegisterPelangganRequest {
    private String name;
    private String email;
    private String phone;
    private String password;
    private String role;

    public RegisterPelangganRequest(String name, String email, String phone, String password, String role) {
        this.name = name;
        this.email = email;
        this.phone = phone;
        this.password = password;
        this.role = role;
    }
}