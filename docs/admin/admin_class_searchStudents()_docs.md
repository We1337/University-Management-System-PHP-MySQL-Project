## Summary
The code snippet is a method called `searchStudents` in the `Admin` class. It searches for students in the database based on a provided search parameter.

## Example Usage
```php
$admin = new Admin();
$searchParam = "John";
$results = $admin->searchStudents($searchParam);
print_r($results);
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
            [name] => John Smith
            [contact] => 9876543210
            [email] => johnsmith@example.com
        )
)
```

## Code Analysis
### Inputs
- `$searchParam` (string): The search parameter to match against the `st_id`, `name`, `contact`, and `email` columns in the `st_info` table.
___
### Flow
1. The method prepares an SQL query to search for students based on the provided search parameter.
2. It establishes a database connection by calling the `connect` method from the parent class.
3. The SQL statement is prepared using the connection.
4. The search parameter is bound to the `:parameter` placeholder in the query.
5. The query is executed.
6. If the statement is successfully prepared, the query is executed and the matching results are fetched as associative arrays.
7. The method returns the array of matching student records.
___
### Outputs
- An array of matching student records (`st_id`, `name`, `contact`, `email`) or false on failure.
___
