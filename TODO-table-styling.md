# TODO: Styling Tabel Daftar Alat

## Tujuan
Mengubah tampilan tabel "Daftar Alat" agar lebih rapi, teks tidak turun ke bawah, dan menerapkan efek hover serta scroll horizontal.

## Ketentuan
- table-layout: fixed;
- width: 100%;
- td: white-space: nowrap; overflow-x: auto; display: block; max-width: 200px; cursor: text;
- Hover: background #f8f9fa;
- Responsif dengan scroll horizontal.

## Langkah-langkah
- [ ] Tambahkan CSS class .table-daftar-alat di resources/css/app.css
- [ ] Edit resources/views/sales/daftar-penawaran.blade.php: tambahkan class pada tabel daftar alat
- [ ] Edit resources/views/sap/daftar-penawaran.blade.php: tambahkan class pada tabel daftar alat
- [ ] Test responsivitas dan fungsi scroll/copy teks
