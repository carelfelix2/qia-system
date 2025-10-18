# TODO: Implement User Approval System

## Tasks
- [x] Modify `app/Http/Controllers/Auth/RegisteredUserController.php`: Set status to 'pending' and remove role assignment on registration.
- [x] Update `routes/web.php`: Enhance dashboard logic to check if user->status == 'approved' AND has a role; otherwise, show pending-approval.
- [x] Test user registration: New users should have status 'pending' and no role.
- [x] Test admin approval: Should set status to 'approved' and assign requested role.
- [x] Verify login and dashboard access for pending vs. approved users.
