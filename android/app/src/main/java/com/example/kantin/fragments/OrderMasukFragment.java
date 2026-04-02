package com.example.kantin.fragments;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.example.kantin.R;
import com.example.kantin.OrderAdapter;
import com.example.kantin.Order;

import java.util.ArrayList;
import java.util.List;

public class OrderMasukFragment extends Fragment {

    private RecyclerView rvOrderMasuk;
    private OrderAdapter adapter;
    private List<Order> orderList;

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        // Menghubungkan layout fragment_order_masuk.xml
        View view = inflater.inflate(R.layout.fragment_order_masuk, container, false);

        // 1. Inisialisasi RecyclerView
        rvOrderMasuk = view.findViewById(R.id.rvOrderMasuk);
        rvOrderMasuk.setLayoutManager(new LinearLayoutManager(getContext()));

        // 2. Siapkan Data Dummy (Nanti diganti dengan panggil API Laravel)
        prepareData();

        // 3. Pasang Adapter (isProsesTab = false karena ini tab Pesanan Masuk)
        adapter = new OrderAdapter(getContext(), orderList, false);
        rvOrderMasuk.setAdapter(adapter);

        return view;
    }

    private void prepareData() {
        orderList = new ArrayList<>();
        // Contoh data sesuai UI yang kamu inginkan
        orderList.add(new Order(
                "#ORD-089",
                "Alex Johnson",
                "2 mnt yang lalu",
                "Ambil Sendiri",
                "2x Nasi Goreng Spesial\n1x Brown Sugar Boba",
                "Rp 68.000",
                "Menunggu"
        ));

        orderList.add(new Order(
                "#ORD-090",
                "Sarah Smith",
                "5 mnt yang lalu",
                "Antar Kurir",
                "1x Mie Goreng Ayam\n1x Es Teh Manis",
                "Rp 30.000",
                "Menunggu"
        ));
    }
}