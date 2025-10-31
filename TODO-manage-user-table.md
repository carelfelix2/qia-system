# TODO: Manage User Table

## Completed Tasks
- [x] Ubah header tabel di kedua file menjadi: Tanggal, No SAP, Nama Sales, Nama Customer, Status, Detail Penawaran, Input SQ
- [x] Hapus bagian expandable items (collapse) di bawah tabel.
- [x] Tambahkan modal popup untuk "Detail Penawaran" yang menampilkan semua informasi penawaran lengkap (daftar alat, pembayaran, stok, keterangan tambahan, lampiran, file PO jika ada).
- [x] Untuk kolom "Input SQ": Di SAP view, tetap button "Input SQ"; di sales view, tetap button Edit/Upload PO sesuai status.
- [x] Pastikan modal dapat diakses via button di kolom "Detail Penawaran".

## Followup Steps
- [x] Jalankan server lokal (php artisan serve --host=127.0.0.1 --port=8000)
- [x] Konfirmasi bahwa modal popup untuk SAP sudah mencakup tabel alat, keterangan tambahan, dan lampiran file.
- [ ] Test tampilan di browser untuk memastikan modal popup berfungsi dengan baik (server sudah running di http://127.0.0.1:8000).
