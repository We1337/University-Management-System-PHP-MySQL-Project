## Summary
The code snippet is a part of the 'Admin' class which extends the 'DatabaseConnection' class. It contains methods for retrieving student information, searching for students, logging in and out admin users, deleting students, fetching attendance information, adding and deleting attendance records, updating attendance records, fetching student marks, updating student results, and viewing CGPA.

## Example Usage
```php
$admin = new Admin($config);
$students = $admin->fetchAllStudents();
$searchResults = $admin->searchStudents('John');
$loggedIn = $admin->adminLogin('admin', 'password');
$admin->adminLogout();
$deleted = $admin->deleteStudent(1);
$attendance = $admin->fetchAttendanceForStudents();
$added = $admin->addAttendanceForStudent('John Doe', 1);
$inserted = $admin->insertAttendance('2021-01-01', [1 => 'present', 2 => 'absent']);
$deletedAttendance = $admin->deleteAttendanceStudent(1);
$dates = $admin->fetchDistinctAttendanceDates();
$attendanceRecords = $admin->fetchAttendanceForAllStudents('2021-01-01');
$updated = $admin->updateAttendance('2021-01-01', [1 => 'absent', 2 => 'present']);
$marks = $admin->fetchMarksOfStudent(1, 1);
$updatedResult = $admin->updateResult(1, ['Math' => 90, 'Science' => 85], 1);
$cgpa = $admin->viewCgpa(1);
```

## Code Analysis
### Inputs
- $config: An array containing the database configuration parameters.
- $searchParam: A string representing the search parameter.
- $username: A string representing the admin username.
- $password: A string representing the admin password.
- $studentID: An integer representing the student ID.
- $studentName: A string representing the student name.
- $curDate: A string representing the current date.
- $attendanceRecords: An associative array with student IDs as keys and attendance values ('present' or 'absent') as values.
- $date: A string representing the date for which attendance records are requested.
- $atten: An associative array with student IDs as keys and updated attendance values ('present' or 'absent') as values.
- $semester: An integer representing the semester.
- $subjects: An associative array of subjects and their corresponding marks.
___
### Flow
1. The 'fetchAllStudents' method retrieves all student records from the 'st_info' table and returns the result as an array of associative arrays.
2. The 'searchStudents' method performs a search for students in the 'st_info' table based on the provided search parameter and returns an array of matching student records.
3. The 'adminLogin' method checks the provided username and password against the 'admin' table and logs in the admin user if the credentials match.
4. The 'adminLogout' method clears admin-related session variables to log out the admin user.
5. The 'deleteStudent' method deletes a student from the 'st_info' table based on their student ID.
6. The 'fetchAttendanceForStudents' method retrieves attendance information for students from the 'at_student' table.
7. The 'addAttendanceForStudent' method adds a student's attendance record to the 'at_student' and 'attn' tables.
8. The 'insertAttendance' method inserts attendance records for students into the 'attn' table for a given date.
9. The 'deleteAttendanceStudent' method deletes an attendance record from the 'at_student' table based on its attendance ID.
10. The 'fetchDistinctAttendanceDates' method retrieves distinct attendance dates from the 'attn' table.
11. The 'fetchAttendanceForAllStudents' method retrieves attendance records for all students from the 'at_student' and 'attn' tables for a given date.
12. The 'updateAttendance' method updates attendance records for students in the 'attn' table for a given date and attendance values.
13. The 'fetchMarksOfStudent' method retrieves marks for a specific student and semester from the 'result' table.
14. The 'updateResult' method updates student results for specific subjects and semester in the 'result' table.
15. The 'viewCgpa' method retrieves CGPA for a specific student from the 'result' table.
___
### Outputs
- The 'fetchAllStudents' method returns a PDOStatement representing the result set or false on failure, or null on error.
- The 'searchStudents' method returns an array of matching student records or false on failure.
- The 'adminLogin' method returns true if the login is successful; otherwise, false.
- The 'adminLogout' method does not return any value.
- The 'deleteStudent' method returns true if the deletion is successful; otherwise, false.
- The 'fetchAttendanceForStudents' method returns an array of attendance records for students or false on failure.
- The 'addAttendanceForStudent' method returns true if the insertion is successful; otherwise, false.
- The 'insertAttendance' method returns true if the insertion is successful; otherwise, false.
- The 'deleteAttendanceStudent' method returns true if the deletion is successful; otherwise, false.
- The 'fetchDistinctAttendanceDates' method returns an array of distinct attendance dates or false on failure.
- The 'fetchAttendanceForAllStudents' method returns an array of attendance records for all students or false on failure.
- The 'updateAttendance' method returns true if the update is successful; otherwise, false.
- The 'fetchMarksOfStudent' method returns the query result if marks are found; otherwise, false.
- The 'updateResult' method returns true if the update was successful; otherwise, false.
- The 'viewCgpa' method returns the query result if data is found; otherwise, false.
___
