# TODO: Add Settings Link to Navigation Menus

## Task: Apply settings page access to all user pages by adding "Settings" link to main navigation menus.

### Files to Edit:
- [x] resources/views/layouts/authenticated.blade.php - Remove Settings menu item
- [x] resources/views/layouts/admin.blade.php - Remove Settings menu item
- [x] resources/views/layouts/sales.blade.php - Remove Settings menu item
- [x] resources/views/layouts/teknisi.blade.php - Remove Settings menu item
- [x] resources/views/sales.blade.php - Remove Settings menu item
- [x] resources/views/teknisi.blade.php - Remove Settings menu item

### Details:
- Remove the navigation item linking to {{ route('profile.edit') }}
- Remove settings/cog icon
- Remove active class check: {{ request()->routeIs('profile.edit') ? 'active' : '' }}

### Status: COMPLETED
All navigation menus no longer include a "Settings" link. Settings are still accessible via the user dropdown menu.
