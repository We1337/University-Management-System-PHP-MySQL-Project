## Summary
The code snippet is a part of the 'Student' class which extends the 'DatabaseConnection' class. It contains methods for registering a new student, logging in a student, retrieving student information, updating student profile, changing student password, and logging out a student.

## Example Usage
```php
$student = new Student($config);
$student->registerNewStudent($student_id, $student_name, $student_password, $student_email, $student_birthday, $student_departman, $student_contact, $student_gender, $student_address);
$student->loginStudent($student_id, $student_password);
$student->getStudentName($student_id);
$student->getStudentById($student_id);
$student->updateStudentProfile($student_id, $student_name, $student_email, $student_dept, $student_gender, $student_contact, $student_address, $student_image);
$student->changeStudentPassword($studentId, $newPassword, $oldPassword);
$student->studentLogout();
$student->isStudentLoggedIn();
```

## Code Analysis
### Inputs
- $student_id (int): Student ID
- $student_name (string): Student name
- $student_password (string): Student password
- $student_email (string): Student email
- $student_birthday (string): Student birthday
- $student_departman (string): Student department/program
- $student_contact (string): Student contact information
- $student_gender (string): Student gender
- $student_address (string): Student address
- $student_image (string): Student image (file path or base64 encoded data)
___
### Flow
1. The 'registerNewStudent' method checks if the student ID or email already exists in the database.
2. If a record already exists, the method returns false. Otherwise, it proceeds with registration by inserting the student information into the 'st_info' table.
3. The 'loginStudent' method checks if the provided student ID and password match a record in the 'st_info' table.
4. If a matching record is found, the method starts a new session and sets session variables for the student.
5. The 'getStudentName' method retrieves the name of a student based on their ID from the 'st_info' table.
6. The 'getStudentById' method retrieves all information of a specific student based on their ID from the 'st_info' table.
7. The 'updateStudentProfile' method updates the profile of a student in the 'st_info' table.
8. The 'changeStudentPassword' method checks if the old password exists for the given student ID, and if so, updates the password in the 'st_info' table.
9. The 'studentLogout' method marks the student as logged out by unsetting session variables related to the student.
10. The 'isStudentLoggedIn' method checks if the student is currently logged in by checking the value of the 'st_login' session variable.
___
### Outputs
- registerNewStudent: Returns true on successful registration, false otherwise.
- loginStudent: Returns true on successful login, false otherwise.
- getStudentName: Returns the student name if found, null otherwise.
- getStudentById: Returns the PDOStatement if successful, null otherwise.
- updateStudentProfile: Returns true on successful update, null otherwise.
- changeStudentPassword: Returns true if password changed successfully, false otherwise.
- studentLogout: Returns true if the student is logged out, false otherwise.
- isStudentLoggedIn: Returns true if the student is logged in, false otherwise.
___
