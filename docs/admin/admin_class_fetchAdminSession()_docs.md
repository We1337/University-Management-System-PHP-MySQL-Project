## Summary
The code snippet is a method called `fetchAdminSession()` which checks if the 'admin_login' session variable is set and returns its value.

## Example Usage
```php
$admin = new Admin();
$loggedIn = $admin->fetchAdminSession();
```

## Code Analysis
### Inputs
No inputs are required for this code snippet.
___
### Flow
1. The method checks if the 'admin_login' session variable is set using the `isset()` function.
2. If the session variable is set, it returns its value (`true`).
3. If the session variable is not set, it returns `false`.
___
### Outputs
The method returns a boolean value (`true` or `false`) indicating whether the admin is logged in or not.
___
