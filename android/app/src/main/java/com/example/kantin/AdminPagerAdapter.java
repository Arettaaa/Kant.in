package com.example.kantin;

import androidx.annotation.NonNull;
import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentActivity;
import androidx.viewpager2.adapter.FragmentStateAdapter;

import com.example.kantin.fragments.OrderMasukFragment;
import com.example.kantin.fragments.OrderProsesFragment;

public class AdminPagerAdapter extends FragmentStateAdapter {

    public AdminPagerAdapter(@NonNull FragmentActivity fa) {
        super(fa);
    }

    @NonNull
    @Override
    public Fragment createFragment(int position) {
        if (position == 1) return new OrderProsesFragment();
        return new OrderMasukFragment();
    }

    @Override
    public int getItemCount() {
        return 2;
    }
}