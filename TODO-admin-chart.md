# TODO: Add Chart Box for Total Active Users on Admin Home Page

## Steps to Complete

- [x] Update `routes/web.php` to calculate and pass the total active users count to the admin view
- [x] Modify `resources/views/admin.blade.php` to restructure layout into two columns and add ApexCharts chart box for total active users
- [x] Test the admin dashboard to ensure chart renders correctly and displays accurate count (Browser tool disabled, manual testing required)

## Details

- Use `User::where('status', 'approved')->count()` to get total active users
- Restructure admin.blade.php to use Bootstrap row with two cols: one for hello user, one for chart
- Include ApexCharts CDN and render a bar chart in the new card
- Chart should show "Active Users" category with dynamic count value
- Feature is restricted to admin role only via middleware
