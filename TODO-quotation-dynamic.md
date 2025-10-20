# TODO: Implement Dynamic Equipment Addition in Input Penawaran Form

## Completed Tasks
- [x] Create migration for quotation_items table with fields: quotation_id, nama_alat, part_number, kategori_harga, harga, ppn, diskon
- [x] Create QuotationItem model with belongsTo Quotation relationship
- [x] Update Quotation model to add hasMany quotationItems relationship and remove single equipment fields from fillable
- [x] Update SalesController store method to validate arrays for items and create multiple QuotationItem records
- [x] Modify input-penawaran.blade.php to replace single nama_alat input with dynamic add functionality: input field, add button, and table for added items
- [x] Add JavaScript to handle adding/removing table rows and form data management
- [x] Run migrations to apply database changes

## Completed Tasks
- [x] Create migration for quotation_items table with fields: quotation_id, nama_alat, part_number, kategori_harga, harga, ppn, diskon
- [x] Create QuotationItem model with belongsTo Quotation relationship
- [x] Update Quotation model to add hasMany quotationItems relationship and remove single equipment fields from fillable
- [x] Update SalesController store method to validate arrays for items and create multiple QuotationItem records
- [x] Modify input-penawaran.blade.php to replace single nama_alat input with dynamic add functionality: input field, add button, and table for added items
- [x] Add JavaScript to handle adding/removing table rows and form data management
- [x] Run migrations to apply database changes
- [x] Fix diskon field to be text input instead of number (as per user feedback)
- [x] Add diskon input field to the equipment addition form
- [x] Add harga input field to the equipment addition form
- [x] Update table column order to match input sequence
- [x] Make diskon and harga fields required in validation

## Pending Tasks
- [ ] Test the form submission with multiple equipment items
