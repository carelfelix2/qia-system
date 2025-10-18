# TODO: Change Login System to Use Username Instead of Email

## Steps to Complete

- [x] Create a new migration to add 'username' column (string, unique, required) to the users table
- [x] Update app/Models/User.php to include 'username' in the fillable array
- [x] Update config/auth.php to change the username field from 'email' to 'username'
- [x] Update resources/views/auth/login.blade.php to replace the email input with a username input
- [x] Update resources/views/auth/register.blade.php to add a username input field after the name field
- [x] Update app/Http/Requests/Auth/LoginRequest.php to validate 'username' instead of 'email', authenticate using 'username', update throttle key and error messages accordingly
- [x] Update app/Http/Controllers/Auth/RegisteredUserController.php to validate 'username' (required, string, max 255, unique) and store it in the user creation
- [x] Run the new migration to add the username column to the database
- [x] Test the login functionality with username
- [x] Test the registration functionality with username
