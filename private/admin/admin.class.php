<?php
class Admin extends DatabaseConnection {

    /**
     * Retrieve information for all students.
     *
     * This method fetches all student records from the 'st_info' table
     * and returns the result as an array of associative arrays.
     *
     * @return array|false An array of student records or false on failure.
     */
    public function fetchAllStudents() {
        // SQL query to select all student information and order by 'st_id' in ascending order
        $query = "SELECT * FROM `st_info` ORDER BY `st_id` ASC";

        try {
            // Establish a database connection
            $connection = parent::connect();

            // Prepare the SQL statement
            $statement = $connection->prepare($query);

            // If the statement was prepared successfully
            if ($statement) {
                // Execute the query
                $statement->execute();

                // Fetch all results as associative arrays
                return $statement->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            // Handle the database error, log it, or display an error message
            error_log("Database Error: " . $e->getMessage());
        } 
		
        return false;
    }



	/**
	 * Search for students in the database based on specific search parameters.
	 *
	 * This method performs a search for students in the 'st_info' table based on the provided search parameter.
	 *
	 * @param string $searchParam The search parameter to match against 'st_id', 'name', 'contact', and 'email' columns.
	 *
	 * @return array|false An array of matching student records or false on failure.
	 */
	public function searchStudents($searchParam) {
		// SQL query to search for students based on the provided search parameter
		$query = "SELECT * FROM `st_info` WHERE (`st_id` LIKE :parameter OR `name` LIKE :parameter OR `contact` LIKE :parameter OR `email` LIKE :parameter) ORDER BY `st_id` ASC";

		try {
			// Establish a database connection
			$connection = parent::connect();

			// Prepare the SQL statement
			$statement = $connection->prepare($query);

			if ($statement) {
				// Bind the search parameter and execute the query
				$statement->bindParam(':parameter', $searchParam, PDO::PARAM_STR);
				$statement->execute();

				// Fetch and return matching results as associative arrays
				return $statement->fetchAll(PDO::FETCH_ASSOC);
			}
		} catch (PDOException $e) {
			// Handle the database error, log it, or display an error message
			error_log("Database Error: " . $e->getMessage());
		}

		return false;
	}



	/**
	 * Attempt to log in an admin user.
	 *
	 * This method checks the provided username and password against the 'admin' table and
	 * logs in the admin user if the credentials match.
	 *
	 * @param string $username The admin username.
	 * @param string $password The admin password.
	 *
	 * @return bool True if the login is successful; otherwise, false.
	 */
	public function adminLogin($username, $password) {
		$query = "SELECT `id`, `username` FROM `admin` WHERE `username` = :username AND `password` = :password";

		try {
			// Establish a database connection
			$connection = parent::connect();

			// Prepare the SQL statement
			$statement = $connection->prepare($query);

			if ($statement) {
				// Bind parameters and execute the query
				$statement->bindParam(":username", $username, PDO::PARAM_STR);
				$statement->bindParam(":password", $password, PDO::PARAM_STR);

				$result = $statement->execute();

				// Fetch the result as an associative array
				$adminInfo = $statement->fetch(PDO::FETCH_ASSOC);

				// Get the row count
				$count = $statement->rowCount();

				if ($count == 1) {
					// Start a session and store admin login information
					session_start();
					$_SESSION['admin_login'] = true;
					$_SESSION['admin_id'] = $adminInfo['id'];
					$_SESSION['admin_name'] = $adminInfo['username'];
					return true;
				}
			}
		} catch (PDOException $e) {
			// Handle the database error, log it, or display an error message
			error_log("Database Error: " . $e->getMessage());
		}
		return false;
	}

	

	
	/**
	 * Get the admin session status.
	 *
	 * This method checks if the 'admin_login' session variable is set and returns its value.
	 *
	 * @return bool True if the admin is logged in; otherwise, false.
	 */
	public function fetchAdminSession() {
		if (isset($_SESSION['admin_login'])) {
			return $_SESSION['admin_login'];
		}
		return false;
	}


	/**
	 * Log out an admin user.
	 *
	 * This method clears admin-related session variables to log out the admin user.
	 */
	public function adminLogout() {
		// Clear admin-related session variables
		$_SESSION['admin_login'] = false;
		unset($_SESSION['admin_id']);
		unset($_SESSION['admin_name']);
	}



	/**
	 * Delete a student by their st_id.
	 *
	 * This method deletes a student from the 'st_info' table based on their st_id.
	 *
	 * @param int $st_id The st_id of the student to be deleted.
	 *
	 * @return bool True if the deletion is successful; otherwise, false.
	 */
	public function deleteStudent($studentID) {
		// SQL query to delete a student by st_id using a prepared statement
		$query = "DELETE FROM `st_info` WHERE `st_id` = :studentID";
		try {
			// Establish a database connection
			$connection = parent::connect();		

			// Prepare the SQL statement
			$statement = $connection->prepare($query);

			// Bind the st_id parameter and execute the query
			$statement->bindParam(":studentID", $studentID, PDO::PARAM_INT);
			$result = $statement->execute();

			return $result;
		} catch (PDOException $e) {
			// Handle the database error, log it, or display an error message
			error_log("Database Error: " . $e->getMessage());
		}
		return false;
	}



	/**
	 * Get attendance information for students.
	 *
	 * This method retrieves attendance information for students from the 'at_student' table.
	 *
	 * @return array|false An array of attendance records for students or false on failure.
	 */
	public function fetchAttendanceForStudents() {
		$query = "SELECT * FROM `at_student`";

		try {
			// Establish a database connection
			$connection = parent::connect();

			// Prepare the SQL statement
			$statement = $connection->prepare($query);

			if ($statement->execute()) {
				// Fetch and return results as an array of associative arrays
				return $statement->fetchAll(PDO::FETCH_ASSOC);
			}
		} catch (PDOException $e) {
			// Handle the database error, log it, or display an error message
			error_log("Database Error: " . $e->getMessage());
		}
		return false;
	}




    /**
     * Add a student's attendance record.
     *
     * This method adds a student's attendance record to the 'at_student' and 'attn' tables.
     *
     * @param string $studentName The name of the student.
     * @param int $studentID The st_id of the student.
     *
     * @return bool True if the insertion is successful; otherwise, false.
     */
    public function addAttendanceForStudent($studentName, $studentID) {
        // SQL query to insert into 'at_student' table
        $query1 = "INSERT INTO `at_student` (`name`, `st_id`) VALUES (:studentName, :studentID)";

        // SQL query to insert into 'attn' table
        $query2 = "INSERT INTO `attn` (`st_id`) VALUES (:studentID)";

        try {
            // Establish a database connection
            $connection = parent::connect();

            // Prepare the first SQL statement
            $statement1 = $connection->prepare($query1);
            $statement1->bindParam(":studentName", $studentName, PDO::PARAM_STR);
            $statement1->bindParam(":studentID", $studentID, PDO::PARAM_INT);

            // Prepare the second SQL statement
            $statement2 = $connection->prepare($query2);
            $statement2->bindParam(":studentID", $studentID, PDO::PARAM_INT);

            // Execute both statements in a transaction
            $connection->beginTransaction();

            $result1 = $statement1->execute();
            $result2 = $statement2->execute();

            // Commit the transaction if both statements are successful, or rollback on failure
            if ($result1 && $result2) {
                $connection->commit();
                return true;
            } else {
                $connection->rollBack();
            }
        } catch (PDOException $e) {
            // Handle the database error, log it, or display an error message
            error_log("Database Error: " . $e->getMessage());
        }

        return false;
    }



    /**
     * Insert attendance records for students.
     *
     * This method inserts attendance records for students into the 'attn' table for a given date.
     *
     * @param string $cur_date The current date for which attendance is being recorded.
     * @param array $atten An associative array with student st_id as keys and attendance values ('present' or 'absent') as values.
     *
     * @return bool True if the insertion is successful; otherwise, false.
     */
    public function insertAttendance($cur_date, $atten = []) {
        // Insert attendance records into the 'attn' table
        $query = "INSERT INTO `attn` (`st_id`, `atten`, `at_date`) VALUES (:studentID, :attendance, :atDate)";
        try {
            // Establish a database connection
            $connection = parent::connect();

            // Check if attendance records already exist for the given date
            $query = "SELECT DISTINCT `at_date` FROM `attn`";
            $statement = $connection->prepare($query);
            $statement->execute();

            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                $db_date = $row['at_date'];
                if ($cur_date == $db_date) {
                    return false;
                }
            }

            $statement = $connection->prepare($query);

            foreach ($atten as $key => $attn_value) {
                $statement->bindParam(":studentID", $key, PDO::PARAM_INT);
                $statement->bindParam(":attendance", $attn_value, PDO::PARAM_STR);
                $statement->bindParam(":atDate", $cur_date, PDO::PARAM_STR);
                $result = $statement->execute();

                if (!$result) {
                    return false;
                }
            }

            return true;
        } catch (PDOException $e) {
            // Handle the database error, log it, or display an error message
            error_log("Database Error: " . $e->getMessage());
        }

        return false;
    }

	



	/**
	 * Delete an attendance record by its at_id.
	 *
	 * This method deletes an attendance record from the 'at_student' table based on its at_id.
	 *
	 * @param int $at_id The at_id of the attendance record to be deleted.
	 *
	 * @return bool True if the deletion is successful; otherwise, false.
	 */
	public function deleteAttendanceStudent($at_id) {
		try {
			// Establish a database connection
			$connection = parent::connect();

			// SQL query to delete an attendance record by at_id using a prepared statement
			$query = "DELETE FROM `at_student` WHERE `id` = :at_id";

			// Prepare the SQL statement
			$statement = $connection->prepare($query);
			$statement->bindParam(":at_id", $at_id, PDO::PARAM_INT);

			// Execute the query
			$result = $statement->execute();

			return $result;
		} catch (PDOException $e) {
			// Handle the database error, log it, or display an error message
			error_log("Database Error: " . $e->getMessage());
		}

		return false;
	}




	/**
	 * Get distinct attendance dates.
	 *
	 * This method retrieves distinct attendance dates from the 'attn' table.
	 *
	 * @return array|false An array of distinct attendance dates or false on failure.
	 */
	public function fetchDistinctAttendanceDates() {
		try {
			// Establish a database connection
			$connection = parent::connect();

			// SQL query to select distinct attendance dates
			$query = "SELECT DISTINCT at_date FROM attn";

			$statement = $connection->query($query);
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);

			return $result;
		} catch (PDOException $e) {
			// Handle the database error, log it, or display an error message
			error_log("Database Error: " . $e->getMessage());
		}

		return false;
	}

	


	/**
	 * Get attendance records for all students on a specific date.
	 *
	 * This method retrieves attendance records for all students from the 'at_student' and 'attn' tables for a given date.
	 *
	 * @param string $date The date for which attendance records are requested.
	 *
	 * @return array|false An array of attendance records for all students or false on failure.
	 */
	public function fetchAttendanceForAllStudents($date) {
		try {
			// Establish a database connection
			$connection = parent::connect();

			// SQL query to select attendance records for all students on a specific date
			$query = "SELECT `at_student.name`, `attn.*` FROM `at_student` INNER JOIN `attn` ON `at_student.st_id` = `attn.st_id` WHERE `at_date` = :dates";

			// Prepare the SQL statement
			$statement = $connection->prepare($query);
			$statement->bindParam(":dates", $date, PDO::PARAM_STR);

			if ($statement->execute()) {
				// Fetch and return results as an array of associative arrays
				return $statement->fetchAll(PDO::FETCH_ASSOC);
			}
		} catch (PDOException $e) {
			// Handle the database error, log it, or display an error message
			error_log("Database Error: " . $e->getMessage());
		}

		return false;
	}


	

	/**
	 * Update attendance records for students on a specific date.
	 *
	 * This method updates attendance records for students in the 'attn' table for a given date and attendance values.
	 *
	 * @param string $date The date for which attendance records are being updated.
	 * @param array $atten An associative array with student st_id as keys and updated attendance values ('present' or 'absent') as values.
	 *
	 * @return bool True if the update is successful; otherwise, false.
	 */
	public function updateAttendance($date, $atten = []) {
		try {
			// Establish a database connection
			$connection = parent::connect();

			// Update attendance records for each student
			foreach ($atten as $key => $attn_value) {
				// SQL query to update attendance
				$query = "UPDATE `attn` SET `atten` = :attendance WHERE `st_id` = :studentID AND `at_date` = :dates";

				// Prepare the SQL statement
				$statement = $connection->prepare($query);
				$statement->bindParam(":attendance", $attn_value, PDO::PARAM_STR);
				$statement->bindParam(":studentID", $key, PDO::PARAM_INT);
				$statement->bindParam(":dates", $date, PDO::PARAM_STR);

				$result = $statement->execute();

				if (!$result) {
					return false;
				}
			}

			return true;
		} catch (PDOException $e) {
			// Handle the database error, log it, or display an error message
			error_log("Database Error: " . $e->getMessage());
		}

		return false;
	}

	



	/**
	 * Retrieve student marks for a specific semester using prepared statements to prevent SQL injection.
	 *
	 * @param int $studentID The ID of the student.
	 * @param int $semester The semester for which you want to retrieve marks.
	 * @return mixed The query result if marks are found, or false if no marks are found.
	 */
	public function fetchMarksOfStudent($studentID, $semester) {
		$query = "SELECT * FROM `result` WHERE `st_id` = :studentID AND `semester` = :semester";
		
		try {
			$connection = parent::connect();
			$statement = $connection->prepare($query);

			// Bind parameters
			$statement->bindParam(':studentID', $studentID, PDO::PARAM_INT);
			$statement->bindParam(':semester', $semester, PDO::PARAM_INT);

			// Execute the statement
			$statement->execute();

			// Get the result
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);

			// Check if there are marks
			if (count($result) > 0) {
				return $result;
			}
		} catch (PDOException $e) {
			error_log("Database Error: " . $e->getMessage());
		}

		return false;
	}




	
	/**
	 * Update student results for specific subjects and semester.
	 *
	 * @param int $studentId The ID of the student.
	 * @param array $subjects An associative array of subjects and their corresponding marks.
	 * @param int $semester The semester for which to update results.
	 * @return bool True if the update was successful, false otherwise.
	 */
	public function updateResult($studentId, $subjects, $semester) {
		$query = "UPDATE `result` SET `marks` = :mark WHERE `st_id` = :studentId AND `semester` = :semester AND `sub` = :subjects";
		
		try {
			$connection = parent::connect()->prepare($query);

			foreach ($subjects as $subject => $mark) {
				$connection->bindParam(':studentId', $studentId, PDO::PARAM_INT);
				$connection->bindParam(':semester', $semester, PDO::PARAM_INT);
				$connection->bindParam(':subjects', $subject, PDO::PARAM_STR);
				$connection->bindParam(':mark', $mark, PDO::PARAM_INT);

				$result = $connection->execute();
				
				if (!$result) {
					return false; // Return false if any update fails
				}
			}
			return true; // Return true if all updates are successful
		} catch (PDOException $e) {
			error_log("Database Error: " . $e->getMessage());
			return false; // Return false on any exception
		}
	}




	/**
	 * View CGPA for a specific student with error handling using a try-catch block.
	 *
	 * @param int $studentId The ID of the student.
	 * @return mixed The query result if data is found, or false if an error occurs or no data is found.
	 */
	public function viewCgpa($studentId) {
		// Prepare the SQL query with a parameterized statement
		$query = "SELECT * FROM `result` WHERE `st_id` = :studentId";

		try {
			
			$statement = parent::connect()->prepare($query);

			// Bind the student ID parameter
			$statement->bindParam(':studentId', $studentId, PDO::PARAM_INT);

			// Execute the statement
			$statement->execute();

			// Get the result
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);

			// Return the result if data is found, or false if no data is found
			if (count($result) > 0) {
				return $result;
			}

		} catch (PDOException $e) {
			error_log("Database Error: " . $e->getMessage());	
		}
		return false; // Return false on any exception
	}


	
	
	/* Total average marks
	public function sgpa(){
		global $conn;
		$sql = "SELECT avg(marks) as sgpa from result where st_id=12103072 and semester='1st'";
		$result = $conn->query($sql);
		return $result;
	}
	*/
	
	
	
	
	
//end class 	
}