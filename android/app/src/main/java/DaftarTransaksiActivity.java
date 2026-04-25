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

        setContentView(R.layout.bottom_sheet_export);

        ImageView btnDownloadLaporan = findViewById(R.id.btnDownload);
        ImageButton btnBack = findViewById(R.id.btnClose);

        btnBack.setOnClickListener(v -> finish());

        btnDownloadLaporan.setOnClickListener(v -> {
            ExportBottomSheetDialog bottomSheet = new ExportBottomSheetDialog();
            bottomSheet.show(getSupportFragmentManager(), "ExportBottomSheetDialog");
        });
    }
}