## Summary
The code snippet is a method called `fetchMarksOfStudent` that retrieves student marks for a specific semester using prepared statements to prevent SQL injection.

## Example Usage
```php
$databaseConnection = new DatabaseConnection($config);
$marks = $databaseConnection->fetchMarksOfStudent($studentID, $semester);
if ($marks) {
    // Process the marks
} else {
    // No marks found
}
```

## Code Analysis
### Inputs
- `$studentID` (integer): The ID of the student.
- `$semester` (integer): The semester for which you want to retrieve marks.
___
### Flow
1. The SQL query is defined to retrieve marks for a specific student and semester.
2. The database connection is established by calling the `getConnection` method of the parent class.
3. The SQL statement is prepared using the query.
4. Parameters are bound to the prepared statement to prevent SQL injection.
5. The statement is executed.
6. The result is fetched as an associative array.
7. If there are marks, the result is returned.
8. If an exception occurs or no marks are found, false is returned.
___
### Outputs
- The query result as an associative array if marks are found.
- False if no marks are found or an exception occurs.
___
