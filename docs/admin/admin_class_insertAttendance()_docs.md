## Summary
This code snippet is a method called `insertAttendance` within the `DatabaseConnection` class. It inserts attendance records for students into the 'attn' table for a given date.

## Example Usage
```php
$database = new DatabaseConnection($config);
$database->insertAttendance('2021-01-01', [
    'student1' => 'present',
    'student2' => 'absent',
]);
```

## Code Analysis
### Inputs
- `$curDate` (string): The current date for which attendance is being recorded.
- `$attendanceRecords` (array): An associative array with student st_id as keys and attendance values ('present' or 'absent') as values.
___
### Flow
1. The method starts by preparing the SQL query to insert attendance records into the 'attn' table.
2. It establishes a database connection using the `getConnection` method inherited from the parent class.
3. It checks if attendance records already exist for the given date by executing a SELECT query.
4. If records already exist, it returns false.
5. If records don't exist, it prepares the SQL statement for inserting attendance records.
6. It loops through the `$attendanceRecords` array and binds the parameters for the SQL statement.
7. It executes the query for each student's attendance record and checks if the insertion was successful.
8. If any record insertion fails, it returns false.
9. If all records are inserted successfully, it returns true.
10. If any exception occurs during the process, it logs the database error and returns false.
___
### Outputs
- True if the insertion is successful; otherwise, false.
___
