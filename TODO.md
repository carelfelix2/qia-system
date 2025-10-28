# TODO: Implement PO File Upload for Completed Quotations

## Information Gathered
- Quotation model has status field ('proses', 'selesai'), and SalesController prevents editing when status == 'selesai'
- Routes are in web.php with role middleware
- Views exist for sales daftar-penawaran
- RoleMiddleware handles multiple roles with | separator
- SapController handles SAP updates and file attachments

## Plan
- [ ] Create migration for po_files table (id, quotation_id, uploaded_by, file_path, created_at, updated_at)
- [ ] Create POFile model with relationships
- [ ] Add relationship to Quotation model (hasMany POFiles)
- [ ] Modify SalesController edit/update methods to allow file upload for 'selesai' quotations (but not other edits)
- [ ] Add route for PO file upload: POST /quotations/{id}/upload-po (sales role)
- [ ] Create uploadPo method in SalesController with validation (PDF, JPG, PNG, max 5MB)
- [ ] Create "Daftar PO" blade view for inputer_spk|inputer_sap roles
- [ ] Add route for "Daftar PO" page: GET /sap/daftar-po
- [ ] Add controller method for daftar-po in SapController
- [ ] Update sales daftar-penawaran.blade.php to add upload button for 'selesai' quotations
- [ ] Run php artisan migrate
- [ ] Run php artisan storage:link

## Followup Steps
- [ ] Test file upload functionality
- [ ] Test "Daftar PO" page access and display
- [ ] Verify file validation (types and size)
- [ ] Ensure files are stored in /storage/app/public/po_files
- [ ] Confirm download links work
