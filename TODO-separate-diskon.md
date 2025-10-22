# Separate Discount Column Task

## Current State
- Discount is per equipment item in the form and table.
- Validation includes 'items.*.diskon'.
- quotation_items table has diskon column.
- quotations table does not have global diskon.

## Required Changes
1. Add diskon column to quotations table.
2. Remove diskon from quotation_items table (drop column).
3. Update form: remove discount from equipment table, add separate global discount field.
4. Update SalesController: remove 'items.*.diskon' validation, add 'diskon' for quotation.
5. Update Quotation model to include diskon fillable.
6. Update QuotationItem model to remove diskon fillable.
7. Test the changes.

## Steps
- [x] Create migration to add diskon to quotations table.
- [x] Create migration to drop diskon from quotation_items table.
- [x] Update SalesController validation and logic.
- [x] Update models if needed.
- [x] Update the form view.
- [x] Test.
- [x] Verify models and migrations are correct.
