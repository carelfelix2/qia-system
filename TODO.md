# TODO: Samakan Daftar PO Sales dengan Daftar PO Inputer SAP

## Tujuan
Menyamakan tampilan dan fungsi daftar PO pada dashboard sales agar sama dengan daftar PO pada dashboard inputer SAP.

## Langkah-langkah

### 1. Update SalesController daftarPo method
- Ubah method untuk mengembalikan `$poFiles` seperti pada SapController
- Gunakan query yang sama dengan SapController untuk konsistensi

### 2. Update resources/views/sales/daftar-po.blade.php
- Ubah struktur tabel agar sama dengan sap/daftar-po.blade.php
- Kolom yang perlu disesuaikan:
  - No
  - Quotation Number (sap_number)
  - Customer Name
  - Uploaded By
  - File (download link)
  - Uploaded At
  - Action (View Quotation)
- Hapus kolom yang tidak ada di SAP: Tanggal, Sales Person, Jenis Penawaran, Items, Keterangan Tambahan
- Update placeholder search agar sesuai dengan SAP

### 3. Test dan Verifikasi
- Pastikan tampilan sama dengan SAP
- Pastikan fungsi search, pagination, dan download berfungsi
- Pastikan tidak ada error

## Status
- [x] Update SalesController
- [x] Update view sales/daftar-po.blade.php
- [x] Update routes to allow sales access to quotation detail
- [x] Fix back button route in quotation detail view
- [x] Restore SAP routes for inputer_sap and inputer_spk roles
- [ ] Test functionality
