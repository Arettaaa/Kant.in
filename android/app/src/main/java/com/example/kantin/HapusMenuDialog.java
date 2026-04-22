package com.example.kantin;

import android.app.Dialog;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.Window;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.widget.AppCompatButton;
import androidx.fragment.app.DialogFragment;

public class HapusMenuDialog extends DialogFragment {

    // Interface callback ke Activity
    public interface OnHapusMenuListener {
        void onKonfirmasiHapus();
    }

    private OnHapusMenuListener listener;

    // Factory method — cara yang benar membuat DialogFragment
    public static HapusMenuDialog newInstance() {
        return new HapusMenuDialog();
    }

    public void setOnHapusMenuListener(OnHapusMenuListener listener) {
        this.listener = listener;
    }

    @NonNull
    @Override
    public Dialog onCreateDialog(@Nullable Bundle savedInstanceState) {
        Dialog dialog = super.onCreateDialog(savedInstanceState);
        // Hapus title bar bawaan dialog
        if (dialog.getWindow() != null) {
            dialog.getWindow().requestFeature(Window.FEATURE_NO_TITLE);
            dialog.getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));
        }
        return dialog;
    }

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater,
                             @Nullable ViewGroup container,
                             @Nullable Bundle savedInstanceState) {
        return inflater.inflate(R.layout.dialog_hapus_menu, container, false);
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);

        AppCompatButton btnBatal   = view.findViewById(R.id.btnBatal);
        AppCompatButton btnYaHapus = view.findViewById(R.id.btnYaHapus);

        btnBatal.setOnClickListener(v -> dismiss());

        btnYaHapus.setOnClickListener(v -> {
            dismiss();
            if (listener != null) {
                listener.onKonfirmasiHapus();
            }
        });
    }

    @Override
    public void onStart() {
        super.onStart();
        // Buat dialog lebarnya match_parent dengan margin dari XML
        if (getDialog() != null && getDialog().getWindow() != null) {
            getDialog().getWindow().setLayout(
                    ViewGroup.LayoutParams.MATCH_PARENT,
                    ViewGroup.LayoutParams.WRAP_CONTENT
            );
        }
    }
}