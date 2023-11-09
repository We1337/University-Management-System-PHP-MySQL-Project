## Summary
This code snippet is a method called `fetchAllStudents()` inside the `Admin` class. It retrieves all student records from the `st_info` table in the database and returns the result as an array of associative arrays.

## Example Usage
```php
$admin = new Admin();
$students = $admin->fetchAllStudents();
print_r($students);
```
Expected Output:
```
Array
(
    [0] => Array
        (
            [st_id] => 1
            [name] => John Doe
            [contact] => 1234567890
            [email] => john@example.com
        )
    [1] => Array
        (
            [st_id] => 2
            [name] => Jane Smith
            [contact] => 9876543210
            [email] => jane@example.com
        )
    ...
)
```

## Code Analysis
### Inputs
No inputs required for this code snippet.
___
### Flow
1. The SQL query is defined to select all student information from the `st_info` table and order it by the `st_id` column in ascending order.
2. A database connection is established by calling the `connect()` method from the parent class `DatabaseConnection`.
3. The SQL query is prepared using the connection.
4. If the preparation is successful, the query is executed.
5. The results are fetched as associative arrays using the `fetchAll()` method of the PDO statement.
6. The array of student records is returned.
___
### Outputs
The method returns an array of associative arrays, where each associative array represents a student record from the `st_info` table.
___
