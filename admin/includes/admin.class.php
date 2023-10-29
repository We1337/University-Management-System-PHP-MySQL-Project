<?php

class Admin extends DatabaseConnection {
	
	/**
	 * Retrieve a list of all student information from the database.
	 *
	 * This function prepares and executes a SQL query to fetch all student records
	 * from the "st_info" table ordered by the student ID in ascending order.
	 *
	 * @return PDOStatement|false Returns a PDOStatement object on success or false on failure.
	 */
	public function fetchAllStudentInformation() {
		// Define the SQL query to retrieve all student information
		$sqlQuery = "SELECT * FROM `st_info` ORDER BY `st_id` ASC";

		try {
			// Prepare the SQL query
			$query = parent::connect()->prepare($sqlQuery);

			if ($query) {
				// Execute the query
				$result = $query->execute();				

				if ($result) {
					// Return the PDOStatement object
					return $query;
				} 
			} 
		} catch(PDOException $e) {
			// Log any database errors that occur
			error_log("Database Error: " . $e->getMessage());
			// You may also consider re-throwing the exception here for further error handling.
		}

		return false; // Database error
	}



	/**
	 * Search for students based on a query.
	 *
	 * This function performs a search for students in the "st_info" table based on the provided query.
	 * It searches for matches in the student ID, name, contact, and email fields and returns the results.
	 *
	 * @param string $searchQuery The search query.
	 * @return PDOStatement|false Returns a PDOStatement object with the search results on success or false on failure.
	 */
	public function searchStudentsByQuery($searchQuery) {
		// Define the SQL query for searching students
		$sqlQuery = "SELECT * FROM `st_info` WHERE (`st_id` LIKE :query OR `name` LIKE :query OR `contact` LIKE :query OR `email` LIKE :query) ORDER BY `st_id`";
		
		try {
			// Prepare the SQL query
			$query = parent::connect()->prepare($sqlQuery);
			
			if($query) {
				// Bind the search query as a parameter (using prepared statements to prevent SQL injection)
				$query->bindValue(':query', '%' . $searchQuery . '%');
				
				// Execute the query
				$check = $query->execute();
				
				if ($check) {
					// Return the PDOStatement object with search results
					return $query;
				} 
			}
		} catch(PDOException $e) {
			// Log any database errors that occur
			error_log("Database Error: " . $e->getMessage());
			// You may also consider re-throwing the exception here for further error handling.
		}

		return false; // Database error
	}


	/**
	 * Admin Login Function
	 *
	 * This function is responsible for authenticating an admin user by checking their
	 * credentials in the database.
	 *
	 * @param string $adminUsername The admin's username.
	 * @param string $adminPassword The admin's password.
	 *
	 * @return bool Returns true if the login is successful, false otherwise.
	 */
	public function authenticateAdmin($adminUsername, $adminPassword) {
		// Define the SQL query with placeholders for username and password
		$sqlQuery = "SELECT `id`, `username` FROM `admin` WHERE `username` = :username AND `password` = :pass_word";

		try {
			// Prepare the SQL statement
			$query = parent::connect()->prepare($sqlQuery);

			if ($query) {
				// Bind the parameters to the placeholders
				$query->bindParam(':username', $adminUsername, PDO::PARAM_STR);
				$query->bindParam(':pass_word', $adminPassword, PDO::PARAM_STR);

				// Execute the query
				$query->execute();

				// Get the count of rows that match the query
				$rowCount = $query->rowCount();

				if ($rowCount == 1) {
					// Fetch the admin information
					$adminInformation = $query->fetch(PDO::FETCH_ASSOC);

					if ($adminInformation) {
						// Set session variables for successful login
						$_SESSION['admin_login'] = true;
						$_SESSION['admin_id'] = $adminInformation['id'];
						$_SESSION['admin_name'] = $adminInfomation['username'];
						
						return true; // Successful login
					}
				}
			}
		} catch(PDOException $e) {
			// Handle database errors gracefully, log the error
			error_log("Database Error: " . $e->getMessage());
			// You may also consider re-throwing the exception here for further error handling.
		}

		return false; // Login failed
	}



	/**
	 * Get the admin session status.
	 *
	 * This function checks whether the admin is currently logged in by
	 * inspecting the 'admin_login' key in the session data.
	 *
	 * @return bool True if the admin is logged in, false otherwise.
	 */
	public function isAdminLoggedIn() {
		// Use the isset() function to check if the 'admin_login' key is set in the session.
		return isset($_SESSION['admin_login']);
	}



	/**
	 * Log the admin out and destroy their session.
	 *
	 * This function logs the admin out by setting the 'admin_login' session variable to false
	 * and unsetting other admin-related session variables like 'admin_id' and 'admin_name'.
	 */
	public function adminLogout() {
		// Set the 'admin_login' session variable to false to log the admin out.
		$_SESSION['admin_login'] = false;
		
		// Unset other admin-related session variables.
		unset($_SESSION['admin_id']);
		unset($_SESSION['admin_name']);
		unset($_SESSION['admin_login']);

		// Return home page.
		header("Location: ../index.php");
		exit();
	}



	/**
	 * Delete a student by their Student ID.
	 *
	 * @param string $student_id The unique Student ID to identify the student to be deleted.
	 * @return bool Returns true on successful deletion, or false on failure.
	 */
	public function removeStudentById($studentId) {
		// Define the SQL query to delete a student by their Student ID
		$sqlQuery = "DELETE FROM `st_info` WHERE `st_id` = :student_id";

		try {
			// Get a PDO instance for the database connection
			$query = parent::connect()->prepare($sqlQuery);

			if ($query) {
				// Bind the Student ID as a parameter to prevent SQL injection
				$query->bindParam(":student_id", $student_id, PDO::PARAM_STR);

				// Execute the query and check the result
				$result = $query->execute();

				if ($result) {
					return true; // Deletion was successful
				}	
			}
		} catch (PDOException $e) {
			// Log any database errors that occur
			error_log("Database Error: " . $e->getMessage());
		}

		return false; // Deletion failed
	}



	/**
	 * Retrieve attendance records for students from the database.
	 *
	 * This function queries the database to fetch attendance records for students from the 'at_student' table.
	 *
	 * @return PDOStatement|false|null A PDOStatement with the result set, false on query failure, or null on exception.
	 */
	public function fetchStudentAttendanceRecords() {
		$sqlQuery = "SELECT * FROM `at_student`";
		
		try {
			// Get a database connection using the parent's 'connect' method.
			$query = parent::connect()->prepare($sqlQuery);

			if ($query) {
				// Execute the query.
				$result = $query->execute();

				if ($result) {
					// Return the PDOStatement with the result set.
					return $query;
				}
			}
		} catch (PDOException $e) {
			// Log any database errors that occur.
			error_log("Database Error: " . $e->getMessage());
			// You may also consider re-throwing the exception here for further error handling.
		}
		
		// Return false on query failure or null on exception.
		return false;
	}



	/**
	 * Insert student attendance records into the database.
	 *
	 * This function inserts records into the 'at_student' and 'attn' tables to track student attendance.
	 *
	 * @param string $student_name The name of the student.
	 * @param int $student_id The student's ID.
	 * @return bool Returns true on successful insertion, false on failure.
	 */
	public function insertStudentAttendance($student_name, $student_id) {

		$sqlQuery_at_student = "INSERT INTO `at_student` (`name`, `st_id`) VALUES (:student_name, :student_id)";
		$sqlQuery_at_attendance = "INSERT INTO `attn` (`st_id`, `atten`, `at_date`) VALUES (:student_id, :atten, :at_date)";
		$default_value_attendance = 'absent';
		$at_date = date("Y-m-d");

		try {
			$query_at_student = parent::connect()->prepare($sqlQuery_at_student);
			$query_at_attendance = parent::connect()->prepare($sqlQuery_at_attendance);

			if ($query_at_student && $query_at_attendance) {
				// Bind parameters to the prepared statements.
				$query_at_student->bindParam(':student_name', $student_name, PDO::PARAM_STR);
				$query_at_student->bindParam(':student_id', $student_id, PDO::PARAM_INT);

				$query_at_attendance->bindParam(':student_id', $student_id, PDO::PARAM_INT);
				$query_at_attendance->bindParam(':atten', $default_value_attendance, PDO::PARAM_STR);
				$query_at_attendance->bindParam(':at_date', $at_date, PDO::PARAM_STR);

				// Execute both queries.
				$result_student = $query_at_student->execute();
				$result_attendance = $query_at_attendance->execute();

				if ($result_student && $result_attendance) {
					return true; // Successful insertion.
				}
			}
		} catch(PDOException $e) {
			// Log any database errors that occur.
			error_log("Database Error: " . $e->getMessage());
		}
		
		return false; // Insertion failed.
	}

	


	/**
	 * Insert student attendance records for a specific date into the database.
	 *
	 * This function inserts attendance records into the 'attn' table for a given date, while ensuring there are no duplicates.
	 *
	 * @param string $current_date The date for which attendance records should be inserted.
	 * @param array $attendance An array containing student attendance data (student ID as keys and 'present' or 'absent' as values).
	 * @return bool Returns true on successful insertion, false if the date already exists or an error occurs.
	 */
	public function insertStudentAttendanceForDate($current_date, $attendance = array()) {
		// Check if the date already exists in the database to prevent duplicates.
		$sqlCheckDate = "SELECT COUNT(*) as `count` FROM `attn` WHERE `at_date` = :current_date";

		try {
			$stmtCheckDate = parent::connect()->prepare($sqlCheckDate);

			if ($stmtCheckDate) {
				$stmtCheckDate->bindParam(':current_date', $current_date, PDO::PARAM_STR);
				$stmtCheckDate->execute();
				$dateCount = $stmtCheckDate->fetch(PDO::FETCH_ASSOC)['count'];

				if ($dateCount > 0) {
					return false; // Date already exists, no insertion.
				}

				// Insert attendance records based on the provided data.
				foreach ($attendance as $key => $attn_value) {
					$sqlInsertAttendance = "INSERT INTO `attn` (`st_id`, `atten`, `at_date`) VALUES (:student_id, :attendance, :current_date)";

					$stmtInsertAttendance = parent::connect()->prepare($sqlInsertAttendance);
					if ($stmtInsertAttendance) {
						$stmtInsertAttendance->bindParam(':student_id', $key, PDO::PARAM_INT);
						$stmtInsertAttendance->bindParam(':attendance', $attn_value, PDO::PARAM_STR);
						$stmtInsertAttendance->bindParam(':current_date', $current_date, PDO::PARAM_STR);

						if (!$stmtInsertAttendance->execute()) {
							return false; // Insertion failed.
						}
					}
				}
				return true; // Successful insertion.
			}
		} catch(PDOException $e) {
			// Log any database errors that occur
			error_log("Database Error: " . $e->getMessage());
			// You may also consider re-throwing the exception here for further error handling.
		}
		return false; // Insertion failed.
	}



	/**
	 * Delete a student record from the 'at_student' table.
	 *
	 * This function deletes a student record identified by their unique student ID from the 'at_student' table.
	 *
	 * @param int $studentId The unique identifier of the student to be deleted.
	 * @return bool Returns true if the student record is successfully deleted, false if an error occurs.
	 */
	public function deleteAttendentStudent($studentId) {
		// Prepare the SQL query to delete a student record by ID.
		$sqlQuery = "DELETE FROM `at_student` WHERE `id` = :student_id";

		try {
			// Get a database connection and prepare the SQL query.
			$query = parent::connect()->prepare($sqlQuery);

			// Bind the student ID parameter to the query.
			$query->bindParam(':student_id', $studentId, PDO::PARAM_INT);

			// Execute the query to delete the student record.
			$result = $query->execute();

			// Check if the query was executed successfully.
			if ($result) {
				// Return true to indicate a successful deletion.
				return true;
			}

		} catch (PDOException $e) {
			// Log any database errors to the error log.
			error_log("Database Error: " . $e->getMessage());
			// You may also consider re-throwing the exception here for further error handling.
		}

		// Return false in case of an error or unsuccessful deletion.
		return false;
	}



	/**
	 * Retrieve a list of unique attendance dates from the 'attn' table.
	 *
	 * This function fetches distinct attendance dates from the 'attn' table to provide a list of unique dates.
	 *
	 * @return array|false An array of unique attendance dates or false if an error occurs.
	 */
	public function getAttendanceDates() {
		// SQL query to select distinct attendance dates.
		$sqlQuery = "SELECT DISTINCT `at_date` FROM `attn`";

		try {
			// Get a database connection and prepare the SQL query.
			$query = parent::connect()->prepare($sqlQuery);

			// Execute the query to retrieve distinct dates.			
			$result = $query->execute();

			if ($result) {
				return $query;
			}
		} catch (PDOException $e) {
			// Log any database errors to the error log.
			error_log("Database Error: " . $e->getMessage());
			// You may also consider re-throwing the exception here for further error handling.
		}

		// Return false in case of an error or no results.
		return false;
	}



	/**
	 * Retrieve attendance records for all students on a specific date.
	 *
	 * This function retrieves attendance records for all students from the 'at_student' and 'attn' tables
	 * for a given date by performing an inner join on the student IDs.
	 *
	 * @param string $date The specific date for which attendance records should be retrieved.
	 *
	 * @return array|false An array of attendance records for all students on the given date or false if an error occurs.
	 */
	public function getAttendanceForAllStudents($date) {
		// SQL query to select attendance records for all students on the provided date.
		$sqlQuery = "SELECT `at_student`.`name`, `attn`.* FROM `at_student` INNER JOIN `attn` ON `at_student`.`st_id` = `attn`.`st_id` WHERE `at_date` = :dates";

		try {
			// Get a database connection and prepare the SQL query.
			$query = parent::connect()->prepare($sqlQuery);

			// Bind the date parameter to the query.
			$query->bindParam(':dates', $date, PDO::PARAM_STR);

			// Execute the query to retrieve attendance records.
			$result = $query->execute();			

			if ($result) {
				return $query;
			}
		} catch (PDOException $e) {
			// Log any database errors to the error log.
			error_log("Database Error: " . $e->getMessage());
			// You may also consider re-throwing the exception here for further error handling.
		}

		// Return false in case of an error or no results.
		return false;
	}
		



	/**
	 * Update student attendance records for a specific date.
	 *
	 * This function updates attendance records for students in the 'attn' table on a given date.
	 *
	 * @param string $date The specific date for which attendance records should be updated.
	 * @param array $attendance An array containing student attendance data (student ID as keys and 'present' or 'absent' as values).
	 *
	 * @return bool Returns true on successful update, false if an error occurs.
	 */
	public function updateAttendanceForDate($date, $attendance) {
		try {
			// Iterate through the provided attendance data.
			foreach ($attendance as $key => $attnValue) {
				// Prepare the SQL query to update attendance records.
				$sqlQuery = "UPDATE `attn` SET `atten` = :present_absent WHERE `st_id` = :student_id AND `at_date` = :date";
				
				// Get a database connection and prepare the SQL query.
				$query = parent::connect()->prepare($sqlQuery);

				// Bind parameters for the query.
				$query->bindParam(':present_absent', ($attnValue === 'present' ? 'present' : 'absent'), PDO::PARAM_STR);
				$query->bindParam(':student_id', $key, PDO::PARAM_INT);
				$query->bindParam(':date', $date, PDO::PARAM_STR);

				// Execute the query to update attendance records.
				$result = $query->execute();

				if (!$result) {
					// If the update fails, return false immediately.
					return false;
				}
			}

			// Return true if all updates were successful.
			return true;
		} catch (PDOException $e) {
			// Log any database errors to the error log.
			error_log("Database Error: " . $e->getMessage());
			// You may also consider re-throwing the exception here for further error handling.
		}

		// Return false in case of an error.
		return false;
	}



	
	/**
	 * Add or update student marks for a specific subject and semester in the grading system.
	 *
	 * This function checks if a record for the given student, subject, and semester already exists in the 'result' table.
	 * If a record exists, it returns false (indicating that an update is not allowed). If no record exists, it inserts
	 * a new record with the provided marks.
	 *
	 * @param string $stid The student's unique identifier.
	 * @param string $subject The subject for which marks are being added.
	 * @param string $semester The semester in which the marks are being added.
	 * @param int $marks The marks to be added for the subject.
	 *
	 * @return bool Returns true on successful insertion or false if a record already exists or an error occurs.
	 */
	public function addOrUpdateMarks($student_id, $subject, $semester, $marks) {
    	// SQL query to check if a record already exists for the given student, subject, and semester.
    	$querySql = "SELECT * FROM `result` WHERE `st_id` = :student_id AND `sub` = :subjects AND `semester` = :semester";

		try {
			// Get a database connection and prepare the SQL query.
			$query = parent::connect()->prepare($querySql);

			// Bind parameters for the query.
			$query->bindParam(':student_id', $student_id, PDO::PARAM_INT);
			$query->bindParam(':subjects', $subject, PDO::PARAM_STR);
			$query->bindParam(':semester', $semester, PDO::PARAM_STR);

			// Execute the query to check if a record exists.
			$query->execute();

			// Get the count of matching records.
			$count = $query->rowCount();

			if ($count > 0) {
				// A record already exists; return false to indicate that an update is not allowed.
				return false;
			} else {
				// Insert a new record with the provided marks.
				$sql = "INSERT INTO `result` (st_id, marks, sub, semester) VALUES (:student_id, :marks, :subjects, :semester)";
				$insertQuery = parent::connect()->prepare($sql);

				// Bind parameters for the insert query.
				$insertQuery->bindParam(':student_id', $student_id, PDO::PARAM_INT);
				$insertQuery->bindParam(':marks', $marks, PDO::PARAM_INT);
				$insertQuery->bindParam(':subjects', $subject, PDO::PARAM_STR);
				$insertQuery->bindParam(':semester', $semester, PDO::PARAM_STR);

				// Execute the insert query.
				$result = $insertQuery->execute();

				if ($result) {
					return $result;
				}
			}
		} catch (PDOException $e) {
			// Log any database errors to the error log.
			error_log("Database Error: " . $e->getMessage());
			// You may also consider re-throwing the exception here for further error handling.
		}

		// Return false in case of an error.
		return false;
	}



	/**
	 * Retrieve student marks for a specific semester in the grading system.
	 *
	 * This function retrieves the marks for a specific student and semester from the 'result' table.
	 *
	 * @param string $stid The student's unique identifier.
	 * @param string $semester The semester for which marks are being retrieved.
	 *
	 * @return PDOStatement|false Returns a PDOStatement object with the marks or false if no records are found or an error occurs.
	 */
	public function getStudentMarks($stid, $semester) {
		// SQL query to select marks for the given student and semester.
		$querySql = "SELECT * FROM `result` WHERE `st_id` = :student_id AND `semester` = :semester";

		try {
			// Get a database connection and prepare the SQL query.
			$query = parent::connect()->prepare($querySql);

			// Bind parameters for the query.
			$query->bindParam(':student_id', $stid, PDO::PARAM_STR);
			$query->bindParam(':semester', $semester, PDO::PARAM_STR);

			// Execute the query to retrieve the marks.
			$query->execute();

			// Get the count of matching records.
			$result = $query->rowCount();

			if ($result > 0) {
				// Records are found; return the PDOStatement object with marks.
				return $query;
			}
		} catch (PDOException $e) {
			// Log any database errors to the error log.
			error_log("Database Error: " . $e->getMessage());
			// You may also consider re-throwing the exception here for further error handling.
		}

		// Return false in case of an error or no records found.
		return false;
	}



	/**
	 * Update student results for multiple subjects in a specific semester in the grading system.
	 *
	 * This function allows you to update student results for various subjects in a given semester.
	 *
	 * @param string $stid The student's unique identifier.
	 * @param array $subject An associative array containing subjects as keys and marks as values.
	 * @param string $semester The semester for which results are being updated.
	 *
	 * @return bool Returns true on successful update, false if an error occurs.
	 */
	public function updateStudentResults($stid, $subject, $semester) {

		try {
			// Iterate through the provided subjects and marks.
			foreach ($subject as $key => $mark) {
				// SQL query to update a specific subject's result for the student in the given semester.
				$sql = "UPDATE `result` SET `marks` = :mark WHERE `st_id` = :student_id AND `semester` = :semester AND `sub` = :subjects";

				// Get a database connection and prepare the SQL query.
				$query = parent::connect()->prepare($sql);

				// Bind parameters for the update query.
				$query->bindParam(':mark', $mark, PDO::PARAM_INT);
				$query->bindParam(':student_id', $stid, PDO::PARAM_STR);
				$query->bindParam(':semester', $semester, PDO::PARAM_STR);
				$query->bindParam(':subjects', $key, PDO::PARAM_STR);

				// Execute the query to update the result for the subject.
				$result = $query->execute();

				if (!$result) {
					// If the update fails, return false immediately.
					return false;
				}
			}

			// Return true if all updates were successful.
			return true;
		} catch (PDOException $e) {
			// Log any database errors to the error log.
			error_log("Database Error: " . $e->getMessage());
			// You may also consider re-throwing the exception here for further error handling.
		}

		// Return false in case of an error.
		return false;
	}



	/**
	 * View CGPA (Cumulative Grade Point Average) for a specific student.
	 *
	 * This function retrieves the results for a specific student from the 'result' table, which can be used to calculate the CGPA.
	 *
	 * @param string $stid The student's unique identifier.
	 *
	 * @return PDOStatement|false Returns a PDOStatement object with the student's results or false if no records are found or an error occurs.
	 */
	public function viewCGPA($stid) {
		// SQL query to select results for the given student.
		$querySql = "SELECT * FROM `result` WHERE `st_id` = :student_id";
		
		try {
			// Get a database connection and prepare the SQL query.
			$query = parent::connect()->prepare($querySql);

			// Bind the student ID parameter to the query.
			$query->bindParam(':student_id', $stid, PDO::PARAM_INT);
			
			// Execute the query to retrieve the student's results.
			$query->execute();
			
			// Check if the query was executed successfully.
			$result = $query->rowCount();
			
			if ($result) {
				// Records are found; return the PDOStatement object with the student's results.
				return $query;
			}
		} catch (PDOException $e) {
			// Log any database errors to the error log.
			error_log("Database Error: " . $e->getMessage());
			// You may also consider re-throwing the exception here for further error handling.
		}
		
		// Return false in case of an error or no records found.
		return false;
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
};

?>