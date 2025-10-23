# TODO: Log Perubahan Penawaran untuk Inputer SAP

## Tujuan
Membuat halaman log perubahan/edit yang dilakukan oleh user sales pada penawaran di daftar penawaran SAP, dengan notifikasi otomatis ke inputer SAP setiap ada perubahan.

## Status
- [x] Tambahkan method `revisionLog` di SapController untuk menampilkan log perubahan
- [x] Buat view baru `resources/views/sap/log-perubahan.blade.php` untuk menampilkan log
- [x] Tambahkan route baru di `routes/web.php` untuk halaman log
- [x] Pastikan notifikasi sudah dikirim (sudah ada di SalesController update)
- [x] Tambahkan menu navigasi di layout SAP
- [ ] Test halaman log dan notifikasi

## Detail Implementasi
- Method `revisionLog` akan mengambil semua QuotationRevision yang terkait dengan penawaran
- View akan menampilkan tabel dengan kolom: Tanggal, User, Penawaran, Perubahan, Detail
- Route: `/sap/log-perubahan` dengan middleware `role:inputer_sap`
- Notifikasi sudah ada: QuotationUpdatedNotification dikirim ke inputer_sap saat sales update penawaran
