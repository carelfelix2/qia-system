# TODO: Add Quantity Field for Equipment in Sales Quotation Form

## Overview
Add a "quantity" field to the QuotationItem model and table, allowing sales users to specify how many units of each equipment are needed in quotations.

## Pending Tasks
- [x] Create migration to add quantity column to quotation_items table (integer, default 1, nullable)
- [x] Update QuotationItem model to include quantity in fillable and add integer cast
- [x] Modify edit modal in daftar-penawaran.blade.php to add quantity input in equipment table
- [x] Update JavaScript in daftar-penawaran.blade.php to handle quantity in equipment table rows and form submission
- [x] Update SalesController store and update methods to validate and store quantity for items
- [x] Update detail modal in daftar-penawaran.blade.php to display quantity in equipment list
- [x] Add quantity field to input-penawaran.blade.php form
- [x] Update JavaScript in input-penawaran.blade.php to handle quantity input and validation
- [ ] Test the changes to ensure quantity is properly saved and displayed
