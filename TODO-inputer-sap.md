# TODO: Implement "Inputer SAP" User Role

## Steps to Complete

### 1. Add 'inputer_sap' role to RoleSeeder.php
- Update database/seeders/RoleSeeder.php to include the new role
- [x] COMPLETED

### 2. Create migration for status and sap_number fields
- Create database/migrations/[timestamp]_add_status_and_sap_number_to_quotations_table.php
- Add 'status' enum field (proses, selesai) with default 'proses'
- Add 'sap_number' string field (nullable)
- [x] COMPLETED

### 3. Update Quotation model
- Add 'status' and 'sap_number' to fillable array in app/Models/Quotation.php
- [x] COMPLETED

### 4. Create SapController
- Create app/Http/Controllers/SapController.php with methods:
  - index() - dashboard view
  - quotations() - view all quotations with search/sort functionality
  - updateSapNumber() - update SAP number and status
- [x] COMPLETED

### 5. Add routes for inputer sap
- Update routes/web.php to include inputer sap routes with role middleware
- Update dashboard redirect logic to handle inputer_sap role
- [x] COMPLETED

### 6. Create SAP layout
- Create resources/views/layouts/sap.blade.php
- [x] COMPLETED

### 7. Create SAP views
- Create resources/views/sap/dashboard.blade.php
- Create resources/views/sap/daftar-penawaran.blade.php with:
  - Search bar (by name/SAP number)
  - Date sorting
  - SAP number input field
  - Status update functionality
- [x] COMPLETED

### 8. Modify SalesController for notifications
- Update app/Http/Controllers/SalesController.php store() method to send notifications to inputer sap users
- [x] COMPLETED

### 9. Update sales daftar-penawaran.blade.php
- Add status column to display current quotation status
- Add SAP number column
- [x] COMPLETED

### 10. Create notification for new quotations
- Create NewQuotationNotification for database and email notifications
- [x] COMPLETED

## Followup Steps (After Implementation)
- Run php artisan migrate
- Run php artisan db:seed --class=RoleSeeder
- Test notification sending
- Test SAP input functionality
- Test search and sort features
- [x] MIGRATION COMPLETED
- [x] SEEDING COMPLETED

## Testing Checklist
- [x] Add inputer_sap option to registration form
- [x] Fix validation in RegisteredUserController to accept inputer_sap
- [ ] Create a user with inputer_sap role via admin panel
- [ ] Login as sales user and create a quotation
- [ ] Verify notification is sent to inputer_sap user
- [ ] Login as inputer_sap user and check dashboard
- [ ] Test search functionality in daftar-penawaran
- [ ] Test date sorting
- [ ] Test SAP number input and status update
- [ ] Verify sales can see updated status and SAP number
