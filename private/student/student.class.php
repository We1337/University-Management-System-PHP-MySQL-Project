<?php


class Student extends DatabaseConnection {

	

    /**
     * Registers a new student if the student ID and email are unique.
     *
     * @param int    $student_id        - Student ID.
     * @param string $student_name      - Student name.
     * @param string $student_password  - Student password.
     * @param string $student_email     - Student email.
     * @param string $student_birthday  - Student birthday.
     * @param string $student_departman - Student department/program.
     * @param string $student_contact   - Student contact information.
     * @param string $student_gender    - Student gender.
     * @param string $student_address   - Student address.
     *
     * @return bool - Returns true on successful registration, false otherwise.
     */
    public function registerNewStudent($student_id, $student_name, $student_password, $student_email, $student_birthday, $student_departman, $student_contact, $student_gender, $student_address)
    {
        // Check if the student ID or email already exists
        $check_query = "SELECT `st_id` FROM `st_info` WHERE `st_id`=:student_id OR `email`=:student_email";

        try {
            $check_connection = parent::getConnection()->prepare($check_query);

            // Bind parameters
            $check_connection->bindParam(':student_id', $student_id, PDO::PARAM_INT);
            $check_connection->bindParam(':student_email', $student_email, PDO::PARAM_STR);

            // Execute the query
            if ($check_connection->execute()) {
                // If a record already exists, do not register again
                if ($check_connection->rowCount() > 0) {
                    return false;
                }

                // If no record exists, proceed with registration
                $add_new_student_query = "INSERT INTO `st_info` (`st_id`,`name`,`password`,`email`,`bday`,`program`,`contact`,`gender`,`address`) 
                                          VALUES (:student_id,:student_name,:student_password,:student_email,:student_birthday,:student_departman,:student_contact,:student_gender,:student_address)";

                // Prepare the insert query
                $add_new_student = parent::getConnection()->prepare($add_new_student_query);

                // Bind parameters for the insert query
                $add_new_student->bindParam(':student_id', $student_id, PDO::PARAM_INT);
                $add_new_student->bindParam(':student_name', $student_name, PDO::PARAM_STR);
                $add_new_student->bindParam(':student_password', $student_password, PDO::PARAM_STR);
                $add_new_student->bindParam(':student_email', $student_email, PDO::PARAM_STR);
                $add_new_student->bindParam(':student_birthday', $student_birthday, PDO::PARAM_STR);
                $add_new_student->bindParam(':student_departman', $student_departman, PDO::PARAM_STR);
                $add_new_student->bindParam(':student_contact', $student_contact, PDO::PARAM_STR);
                $add_new_student->bindParam(':student_gender', $student_gender, PDO::PARAM_STR);
                $add_new_student->bindParam(':student_address', $student_address, PDO::PARAM_STR);

                // Execute the insert query
                if ($add_new_student->execute()) {
                    return true; // Registration successful
                }
            }
        } catch (PDOException $e) {
            // Log any database errors
            error_log("Database Error: " . $e->getMessage());
        }

        return false; // Registration failed
    }

	


    /**
     * Attempts to log in a student based on provided credentials.
     *
     * @param int    $student_id       - Student ID.
     * @param string $student_password - Student password.
     *
     * @return bool - Returns true on successful login, false otherwise.
     */
    public function loginStudent($student_id, $student_password)
    {
        // SQL query to check student credentials
        $sql = "SELECT `st_id`, `name` FROM `st_info` WHERE `st_id`=:student_id AND `password`=:student_password";

        try {
            // Prepare the SQL query
            $connection = parent::getConnection()->prepare($sql);

            // Bind parameters
            $connection->bindParam(':student_id', $student_id, PDO::PARAM_INT);
            $connection->bindParam(':student_password', $student_password, PDO::PARAM_STR);

            // Execute the query
            if ($connection->execute()) {

                // Fetch the result
                $userdata = $connection->fetch(PDO::FETCH_ASSOC);

                // Check if a matching record is found
                if ($userdata) {
                    // Start a new session
                    session_start();

                    // Set session variables
                    $_SESSION['st_login'] = true;
                    $_SESSION['sid'] = $userdata['st_id'];
                    $_SESSION['sname'] = $userdata['name'];

                    // Successful login
                    return true;
                }
            }
        } catch (PDOException $e) {
            // Log any database errors
            error_log("Database Error: " . $e->getMessage());
        }

        return false; // Login failed
    }



    /**
     * Retrieves the name of a student based on their ID.
     *
     * @param int $student_id - Student ID.
     *
     * @return string|null - Returns the student name if found, null otherwise.
     */
    public function getStudentName($student_id)
    {
        // SQL query to retrieve the student name
        $query = "SELECT `name` FROM `st_info` WHERE `st_id`=:student_id";

        try {
            // Prepare the SQL query
            $connection = parent::getConnection()->prepare($query);

            // Bind parameters
            $connection->bindParam(':student_id', $student_id, PDO::PARAM_INT);

            // Execute the query
            if ($connection->execute()) {
                // Fetch the result
                $result = $connection->fetch(PDO::FETCH_ASSOC);

                // Return the student name if found
                return isset($result['name']) ? $result['name'] : null;
            }

        } catch (PDOException $e) {
            // Log any database errors
            error_log("Database Error: " . $e->getMessage());
        }

        return null; // Return null if the student name is not found or an error occurs
    }



	/**
     * Retrieves all information of a specific student by Student ID.
     *
     * @param int $student_id - Student ID.
     *
     * @return PDOStatement|null - Returns the PDOStatement if successful, null otherwise.
     */
    public function getStudentById($student_id)
    {
        // SQL query to retrieve all information of a specific student
        $query = "SELECT * FROM `st_info` WHERE `st_id`=:student_id";

        try {
            // Prepare the SQL query
            $connection = parent::getConnection()->prepare($query);

            // Bind parameters
            $connection->bindParam(":student_id", $student_id, PDO::PARAM_INT);

            // Execute the query
            if ($connection->execute()) {
                // Return the PDOStatement for further processing
                return $connection;
            }
        } catch (PDOException $e) {
            // Log any database errors
            error_log("Database Error: " . $e->getMessage());
        }

        return null; // Return null if the query fails or an error occurs
    }




    /**
     * Updates the profile of a student.
     *
     * @param int    $student_id       - Student ID.
     * @param string $student_name     - Updated student name.
     * @param string $student_email    - Updated student email.
     * @param string $student_dept     - Updated student department/program.
     * @param string $student_gender   - Updated student gender.
     * @param string $student_contact  - Updated student contact information.
     * @param string $student_address  - Updated student address.
     * @param string $student_image    - Updated student image (file path or base64 encoded data).
     *
     * @return bool|null - Returns true on successful update, null otherwise.
     */
    public function updateStudentProfile($student_id, $student_name, $student_email, $student_dept, $student_gender, $student_contact, $student_address, $student_image)
    {
        // SQL query to update student profile
        $query = "UPDATE `st_info` SET `name`=:student_name, `email`=:student_email, `program`=:student_departman, 
                  `gender`=:student_gender, `contact`=:student_contact, `address`=:student_address, `img`=:student_image 
                  WHERE st_id=:student_id";

        try {
            // Prepare the SQL query
            $connection = parent::getConnection()->prepare($query);

            // Bind parameters
            $connection->bindParam(':student_id', $student_id, PDO::PARAM_INT);
            $connection->bindParam(':student_name', $student_name, PDO::PARAM_STR);
            $connection->bindParam(':student_email', $student_email, PDO::PARAM_STR);
            $connection->bindParam(':student_departman', $student_dept, PDO::PARAM_STR);
            $connection->bindParam(':student_gender', $student_gender, PDO::PARAM_STR);
            $connection->bindParam(':student_contact', $student_contact, PDO::PARAM_STR);
            $connection->bindParam(':student_address', $student_address, PDO::PARAM_STR);
            $connection->bindParam(':student_image', $student_image, PDO::PARAM_STR); // Assuming the image is a file path or base64 encoded data

            // Execute the query
            if ($connection->execute()) {
                return true; // Successful update
            }

        } catch (PDOException $e) {
            // Log any database errors
            error_log("Database Error: " . $e->getMessage());
        }

        return null; // Return null if the update fails or an error occurs
    }



    /**
     * Update student password in the database.
     *
     * @param int    $studentId      The ID of the student.
     * @param string $newPassword    The new password for the student.
     * @param string $oldPassword    The old password for verification.
     *
     * @return bool                  True if password changed successfully, false otherwise.
     */
    public function changeStudentPassword($studentId, $newPassword, $oldPassword) 
    {
        // Query to check if old password exists for the given student ID
        $checkOldPasswordQuery = "SELECT `st_id` FROM `st_info` WHERE `st_id`=:student_id AND `password`=:student_old_password";

        try {
            // Prepare and execute the query to check old password
            $checkOldPasswordStatement = parent::getConnection()->prepare($checkOldPasswordQuery);
            $checkOldPasswordStatement->bindParam(":student_id", $studentId, PDO::PARAM_INT);
            $checkOldPasswordStatement->bindParam(":student_old_password", $oldPassword, PDO::PARAM_STR);

            if ($checkOldPasswordStatement->execute()) {
                // Get the number of rows returned by the query
                $rowCount = $checkOldPasswordStatement->rowCount();

                if ($rowCount == 0) {
                    // Old password does not exist
                    return false;
                } else {
                    // Update the password for the given student ID
                    $updatePasswordQuery = "UPDATE `st_info` SET `password`=:student_new_password WHERE st_id=:student_id";
                    $updatePasswordStatement = parent::getConnection()->prepare($updatePasswordQuery);
                    $updatePasswordStatement->bindParam(":student_new_password", $newPassword, PDO::PARAM_STR);
                    $updatePasswordStatement->bindParam(":student_id", $studentId, PDO::PARAM_INT);
                    $updatePasswordStatement->execute();

                    // Password changed successfully
                    return true;
                }
            }
        } catch (PDOException $e) {
            // Log any database errors
            error_log("Database Error: " . $e->getMessage());
        }

        // Return false if an error occurred
        return false;
    }



    /**
     * Logout function for student.
     * Unsets student-related session variables and marks student as logged out.
     */
    public function studentLogout() 
    {
        // Check if the student is logged in
        if ($_SESSION['st_login']) {
            // Mark the student as logged out
            $_SESSION['st_login'] = false;

            // Unset specific student-related session variables
            unset($_SESSION['sid']);
            unset($_SESSION['sname']);
            unset($_SESSION['st_login']);

            // If you want to completely destroy the session, uncomment the line below
            // session_destroy();

            // Provide a success message or return true if needed
            return true;
        } else {
            // The student is not logged in, nothing to do
            // Provide a message or return false if needed
            return false;
        }
    }



    /**
     * Get the current session status for student login.
     *
     * @return bool True if the student is logged in, false otherwise.
     */
    public function isStudentLoggedIn() 
    {
        // Using error suppression to handle the case where the session variable is not set
        // It's better to check if the session variable is set instead of suppressing errors
        return isset($_SESSION['st_login']) ? $_SESSION['st_login'] : false;
    }



	
}