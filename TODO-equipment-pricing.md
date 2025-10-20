# Equipment Pricing Update Plan

## Information Gathered
- Existing equipment table has: nama_alat, tipe_alat, merk, part_number, kategori_harga, harga, ppn, diskon, keterangan
- Current system uses single 'harga' field with 'kategori_harga' enum to distinguish pricing types
- Admin equipment page exists at resources/views/admin/equipment/index.blade.php with upload functionality
- Sales can search equipment via /api/equipment/search API and pull data into quotations
- Need to add separate columns: harga_retail, harga_inaproc, harga_sebelum_ppn

## Plan
1. Create migration to add harga_retail, harga_inaproc, harga_sebelum_ppn columns to equipment table
2. Update Equipment model to include new fillable fields and casts
3. Update EquipmentImport class to handle new price columns
4. Update EquipmentController search method to include new price fields
5. Update admin equipment index view to display new price columns in table
6. Update sales input-penawaran page to handle new pricing structure when pulling equipment data

## Dependent Files to Edit
- database/migrations/ (new migration file)
- app/Models/Equipment.php
- app/Imports/EquipmentImport.php
- app/Http/Controllers/EquipmentController.php
- resources/views/admin/equipment/index.blade.php
- resources/views/sales/input-penawaran.blade.php

## Followup Steps
- Run migration to update database
- Test Excel import with new columns
- Test equipment search and pull functionality in sales quotation creation
- Verify admin table displays all price columns correctly
