# Implementation Plan for Quotation and Purchase Order System

## 1. Make Tools (Alat) Visible in Collapse for Inputer SAP
- [x] Update `resources/views/sap/daftar-penawaran.blade.php` to add collapse functionality for quotation items (similar to sales view)

## 2. Add Tracking for Revisions of Quotations
- [x] Add `updated_at` tracking in Quotation model (already exists via timestamps)
- [x] Create migration for quotation_revisions table to log changes
- [x] Create QuotationRevision model
- [x] Update SalesController to log revisions when editing quotations
- [x] Add revision history view in quotation details

## 3. Create Purchase Order System
- [x] Create migration for purchase_orders table
- [x] Create PurchaseOrder model
- [x] Create PurchaseOrderItem model (if needed)
- [x] Create PurchaseOrderController
- [x] Create views for purchase orders (list, create, show)
- [x] Add routes for purchase orders
- [x] Implement functionality to pull quotation into PO
- [x] Create PurchaseOrderNotification
- [x] Send notifications when quotation becomes PO

## 4. Add Notification for Quotation Edits
- [x] Create QuotationUpdatedNotification
- [x] Update SalesController to send notifications to inputer_sap users when quotation is updated
- [x] Ensure notifications are sent only when relevant fields change

## Followup Steps
- [ ] Create views for purchase orders (index, create, show)
- [ ] Add navigation links for purchase orders
- [ ] Test all functionalities
- [ ] Update navigation menus if needed
- [ ] Ensure proper permissions and middleware

## Additional Feature: File Attachment for Quotations
- [x] Add attachment_file column to quotations table
- [x] Update Quotation model fillable array
- [x] Modify SapController updateSapNumber method to handle file uploads
- [x] Update SAP input modal to include file upload field
- [x] Create storage directory for attachments
- [x] Create storage symlink for public access
- [x] Add enctype to form for file uploads
