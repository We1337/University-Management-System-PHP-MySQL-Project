## Summary
The code snippet is a method called `adminLogout()` which is used to log out an admin user. It clears the admin-related session variables and sets the `admin_login` session variable to false.

## Example Usage
```php
$admin = new Admin();
$admin->adminLogout();
```

## Code Analysis
### Inputs
No inputs are required for this code snippet.
___
### Flow
1. The method sets the `admin_login` session variable to false, indicating that the admin user has logged out.
2. It then unsets the `admin_id` and `admin_name` session variables to remove the admin user's information from the session.
___
### Outputs
No outputs are returned from this code snippet.
___
