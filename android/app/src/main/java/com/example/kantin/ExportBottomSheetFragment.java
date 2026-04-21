package com.example.kantin;

import android.app.DatePickerDialog;
import android.graphics.Color;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;
import com.google.android.material.bottomsheet.BottomSheetDialogFragment;
import com.google.android.material.button.MaterialButton;
import com.google.android.material.card.MaterialCardView;
import java.util.Calendar;

public class ExportBottomSheetFragment extends BottomSheetDialogFragment {

    private String format = "PDF";

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View v = inflater.inflate(R.layout.bottom_sheet_export, container, false);

        MaterialCardView cardPdf = v.findViewById(R.id.cardPdf);
        MaterialCardView cardCsv = v.findViewById(R.id.cardCsv);
        EditText etMulai = v.findViewById(R.id.etTanggalMulai);
        EditText etSelesai = v.findViewById(R.id.etTanggalSelesai);
        MaterialButton btnDownload = v.findViewById(R.id.btnDownload);

        // Logika Ganti Warna & Format
        cardPdf.setOnClickListener(view -> {
            format = "PDF";
            updateUI(v);
        });
        cardCsv.setOnClickListener(view -> {
            format = "CSV";
            updateUI(v);
        });

        // Logika Kalender
        etMulai.setOnClickListener(view -> showCalendar(etMulai));
        etSelesai.setOnClickListener(view -> showCalendar(etSelesai));

        btnDownload.setOnClickListener(view -> {
            // Jalankan fungsi download di sini
            dismiss();
        });

        v.findViewById(R.id.btnClose).setOnClickListener(view -> dismiss());

        return v;
    }

    private void updateUI(View v) {
        MaterialCardView cardPdf = v.findViewById(R.id.cardPdf);
        MaterialCardView cardCsv = v.findViewById(R.id.cardCsv);
        // ... (Logika warna oranye/abu-abu yang kita bahas sebelumnya dipindah ke sini)
    }

    private void showCalendar(EditText et) {
        Calendar c = Calendar.getInstance();
        new DatePickerDialog(getContext(), (view, y, m, d) -> et.setText(y + "-" + (m + 1) + "-" + d),
                c.get(Calendar.YEAR), c.get(Calendar.MONTH), c.get(Calendar.DAY_OF_MONTH)).show();
    }
}