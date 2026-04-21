package com.example.kantin.fragments;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.example.kantin.AdminDashboardActivity;
import com.example.kantin.R;
import com.example.kantin.OrderProsesAdapter;
import com.example.kantin.model.OrderModel;
import com.example.kantin.model.response.AdminOrderListResponse;
import com.example.kantin.network.ApiClient;
import com.example.kantin.network.ApiService;
import com.example.kantin.utils.SessionManager;

import java.util.ArrayList;
import java.util.List;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class OrderProsesFragment extends Fragment {

    private RecyclerView rvOrderProses;
    private OrderProsesAdapter adapter;
    private final List<OrderModel> orderList = new ArrayList<>();

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_order_proses, container, false);

        rvOrderProses = view.findViewById(R.id.rvOrderProses);
        rvOrderProses.setLayoutManager(new LinearLayoutManager(getContext()));
        adapter = new OrderProsesAdapter(getContext(), orderList);
        rvOrderProses.setAdapter(adapter);

        fetchOrders();
        return view;
    }

    @Override
    public void onResume() {
        super.onResume();
        fetchOrders();
    }

    private void fetchOrders() {
        SessionManager session = new SessionManager(requireContext());
        ApiService api = ApiClient.getAuthClient(session.getToken()).create(ApiService.class);

        api.getAdminOrders(session.getCanteenId(), "processing").enqueue(new Callback<AdminOrderListResponse>() {
            @Override
            public void onResponse(@NonNull Call<AdminOrderListResponse> call, @NonNull Response<AdminOrderListResponse> response) {
                if (response.isSuccessful() && response.body() != null && response.body().isSuccess()) {
                    orderList.clear();
                    if (response.body().getData() != null) orderList.addAll(response.body().getData());
                    adapter.notifyDataSetChanged();
                    if (getActivity() instanceof AdminDashboardActivity) {
                        ((AdminDashboardActivity) getActivity()).updateTabCount(1, orderList.size());
                    }
                } else {
                    Toast.makeText(getContext(), "Gagal memuat pesanan diproses", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(@NonNull Call<AdminOrderListResponse> call, @NonNull Throwable t) {
                Toast.makeText(getContext(), "Koneksi error: " + t.getMessage(), Toast.LENGTH_SHORT).show();
            }
        });
    }
}