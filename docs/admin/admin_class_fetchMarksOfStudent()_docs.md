## Summary
This code snippet is a method called `fetchMarksOfStudent` that retrieves the marks of a student for a specific semester from a database table called `result`. It uses prepared statements to prevent SQL injection.

## Example Usage
```php
$admin = new Admin();
$studentID = 123;
$semester = 1;
$result = $admin->fetchMarksOfStudent($studentID, $semester);
print_r($result);
```
Expected Output:
```
Array
(
    [0] => Array
        (
            [id] => 1
            [st_id] => 123
            [semester] => 1
            [marks] => 85
        )
    [1] => Array
        (
            [id] => 2
            [st_id] => 123
            [semester] => 1
            [marks] => 90
        )
)
```

## Code Analysis
### Inputs
- `$studentID` (integer): The ID of the student.
- `$semester` (integer): The semester for which you want to retrieve marks.
___
### Flow
1. Create a SQL query to select all rows from the `result` table where the `st_id` column matches the given `$studentID` and the `semester` column matches the given `$semester`.
2. Create a database connection using the `connect` method inherited from the `DatabaseConnection` class.
3. Prepare the SQL query using the `prepare` method of the database connection.
4. Bind the values of `$studentID` and `$semester` to the named parameters `:studentID` and `:semester` in the prepared statement.
5. Execute the prepared statement using the `execute` method.
6. Fetch all the rows from the result set using the `fetchAll` method and store them in the `$result` variable.
7. Check if there are any rows in the result set. If there are, return the `$result` array.
8. If an exception occurs during the execution of the prepared statement, log the error message to the error log.
___
### Outputs
- The query result if marks are found, represented as an array of associative arrays, where each associative array represents a row from the `result` table.
- False if no marks are found.
___
