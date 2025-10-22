# Notification for Sales when Quotation Updated by SAP

## Task
Create notification for Sales users when their quotation gets updated by Inputer SAP.

## Current State
- `QuotationUpdatedNotification` exists but is used for notifying SAP when Sales updates.
- `SapController::updateSapNumber` updates quotation but doesn't notify anyone.

## Plan
1. Modify `QuotationUpdatedNotification` to be generic (accept updater role).
2. In `SapController::updateSapNumber`, detect changes and notify the Sales user (quotation creator).
3. Update notification messages to reflect who updated.

## Files to Edit
- `app/Notifications/QuotationUpdatedNotification.php`
- `app/Http/Controllers/SapController.php`
- `app/Http/Controllers/SalesController.php` (update existing call)

## Changes Made
- [x] Modified `QuotationUpdatedNotification` to accept `$updaterRole` parameter (default 'sales')
- [x] Updated email and database notification messages to show who updated (Sales or Inputer SAP)
- [x] Updated action URL to point to appropriate route based on updater role
- [x] Added change detection in `SapController::updateSapNumber`
- [x] Added notification to Sales user when SAP updates quotation
- [x] Updated `SalesController` to pass 'sales' as updater role

## Followup
- Test the notification flow.
- Ensure email and database notifications work.
