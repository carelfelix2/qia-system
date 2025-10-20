# TODO: Fix Search Display and Integrate Pricing in Sales Input Penawaran

## Steps to Complete

- [x] Update EquipmentController search method to include pricing columns (harga_retail, harga_inaproc, harga_sebelum_ppn) in JSON response
- [x] Modify blade file suggestions styling for neat dropdown display
- [x] Change kategori_harga select options to "Harga Retail", "Harga Inaproc", "Harga Sebelum PPN", "Manual"
- [x] Update fillEquipmentForm JS to set harga based on selected kategori_harga
- [x] Add logic for manual input when price is null (editable) or set readonly if available
- [x] Update add_equipment_btn logic to handle pricing selection
- [x] Test search suggestions display (code review: dropdown styling improved)
- [x] Test adding equipment with different pricing options (code review: logic implemented)
- [x] Verify manual input when price is null (code review: editable when null or manual selected)
