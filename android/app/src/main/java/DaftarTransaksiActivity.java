import android.os.Bundle;
import android.widget.ImageButton;
import android.widget.ImageView;
import androidx.appcompat.app.AppCompatActivity;

import com.example.kantin.ExportBottomSheetDialog;
import com.example.kantin.R;

public class DaftarTransaksiActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        // Pastikan nama ini sesuai dengan nama file XML tampilanmu
        setContentView(R.layout.bottom_sheet_export);

        // Deklarasi dan Inisialisasi dijadikan satu (menyelesaikan warning local variable)
        ImageView btnDownloadLaporan = findViewById(R.id.btnDownload);
        ImageButton btnBack = findViewById(R.id.btnClose);

        // Aksi Tombol Kembali
        btnBack.setOnClickListener(v -> finish());

        // Aksi Tombol Unduh Laporan
        btnDownloadLaporan.setOnClickListener(v -> {
            ExportBottomSheetDialog bottomSheet = new ExportBottomSheetDialog();
            bottomSheet.show(getSupportFragmentManager(), "ExportBottomSheetDialog");
        });
    }
}