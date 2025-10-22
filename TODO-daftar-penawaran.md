# TODO: Tambahkan Checkbox dan Tombol Delete untuk Daftar Penawaran Sales

## Tujuan
Menambahkan fungsi untuk menghapus penawaran secara single atau multiple dengan checkbox pada table daftar penawaran untuk user sales.

## Langkah-langkah yang perlu dilakukan:

### 1. Modifikasi View (resources/views/sales/daftar-penawaran.blade.php)
- [x] Tambahkan kolom checkbox di header table
- [x] Tambahkan checkbox di setiap row table
- [x] Tambahkan tombol "Delete Selected" di sebelah kanan search box
- [x] Buat modal konfirmasi delete
- [x] Tambahkan JavaScript untuk handle select all checkbox dan submit form

### 2. Modifikasi Controller (app/Http/Controllers/SalesController.php)
- [x] Tambahkan method `destroy` untuk delete single quotation
- [x] Tambahkan method `destroyMultiple` untuk delete multiple quotations
- [x] Pastikan hanya quotation milik user yang bisa dihapus

### 3. Modifikasi Routes (routes/web.php)
- [x] Tambahkan route DELETE /sales/quotation/{quotation} untuk delete single
- [x] Tambahkan route POST /sales/quotations/delete-multiple untuk delete multiple

### 4. Testing
- [ ] Test delete single quotation
- [ ] Test delete multiple quotations
- [ ] Test select all checkbox functionality
- [ ] Test konfirmasi modal delete

## Catatan Teknis
- Gunakan CSRF protection untuk form delete
- Pastikan hanya quotation dengan status 'proses' yang bisa dihapus (belum selesai)
- Tambahkan validasi di controller untuk memastikan quotation milik user
- Gunakan Bootstrap modal untuk konfirmasi delete
- Implementasi JavaScript untuk handle checkbox selection
