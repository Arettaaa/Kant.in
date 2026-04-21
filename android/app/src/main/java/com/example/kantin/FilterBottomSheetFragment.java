package com.example.kantin;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import com.google.android.material.bottomsheet.BottomSheetDialogFragment;
import com.google.android.material.chip.Chip;
import com.google.android.material.chip.ChipGroup;

public class FilterBottomSheetFragment extends BottomSheetDialogFragment {

    public interface FilterListener {
        void onFilterApplied(String status);
    }

    private FilterListener listener;
    private String selectedStatus;

    public FilterBottomSheetFragment(String currentStatus, FilterListener listener) {
        this.selectedStatus = currentStatus;
        this.listener = listener;
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View v = inflater.inflate(R.layout.bottom_sheet_filter_transaksi, container, false);

        ChipGroup cgStatus = v.findViewById(R.id.cg_status_filter);

        // Set chip yang aktif sesuai filter terakhir
        if (selectedStatus.equals("completed")) ((Chip)v.findViewById(R.id.chip_selesai)).setChecked(true);
        else if (selectedStatus.equals("cancelled")) ((Chip)v.findViewById(R.id.chip_dibatalkan)).setChecked(true);
        else ((Chip)v.findViewById(R.id.chip_semua)).setChecked(true);

        v.findViewById(R.id.btn_apply_filter).setOnClickListener(view -> {
            int id = cgStatus.getCheckedChipId();
            if (id == R.id.chip_selesai) selectedStatus = "completed";
            else if (id == R.id.chip_dibatalkan) selectedStatus = "cancelled";
            else selectedStatus = "semua";

            listener.onFilterApplied(selectedStatus);
            dismiss();
        });

        v.findViewById(R.id.btn_close_filter).setOnClickListener(view -> dismiss());
        v.findViewById(R.id.btn_reset_filter).setOnClickListener(view -> ((Chip)v.findViewById(R.id.chip_semua)).setChecked(true));

        return v;
    }
}