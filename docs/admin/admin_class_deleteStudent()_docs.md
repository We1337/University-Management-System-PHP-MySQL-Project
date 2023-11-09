## Summary
This code snippet is a method called `deleteStudent` inside the `Admin` class. It deletes a student from the `st_info` table based on their `st_id`.

## Example Usage
```php
$admin = new Admin();
$result = $admin->deleteStudent($studentID);
```

## Code Analysis
### Inputs
- `$studentID` (integer): The `st_id` of the student to be deleted.
___
### Flow
1. The method prepares an SQL query to delete a student from the `st_info` table using a prepared statement.
2. It establishes a database connection by calling the `connect` method from the parent class.
3. The SQL statement is prepared and the `st_id` parameter is bound to the `$studentID` variable.
4. The query is executed and the result is returned.
___
### Outputs
- `true` if the deletion is successful.
- `false` if there is an error or the deletion fails.
___
