package com.example.kantin.fragments;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;

import com.example.kantin.R;

public class OrderProsesFragment extends Fragment {

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        // Baris ini menghubungkan file Java dengan layout fragment_order_proses.xml
        return inflater.inflate(R.layout.fragment_order_proses, container, false);
    }
}