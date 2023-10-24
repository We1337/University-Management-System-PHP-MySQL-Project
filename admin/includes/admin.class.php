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
				$result = $query->execute();
				
				if ($result) {
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
		$sqlQuery_at_student = "INSERT INTO `at_student` (student_name, student_id) VALUES (:student_name, :student_id)";
		$sqlQuery_at_attendance = "INSERT INTO `attn` (st_id) VALUES (:student_id)";

		try {
			$query_at_student = parent::connect()->prepare($sqlQuery_at_student);
			$query_at_attendance = parent::connect()->prepare($sqlQuery_at_attendance);

			if ($query_at_student && $query_at_attendance) {
				// Bind parameters to the prepared statements.
				$query_at_student->bindParam(':student_name', $student_name, PDO::PARAM_STR);
				$query_at_student->bindParam(':student_id', $student_id, PDO::PARAM_INT);

				$query_at_attendance->bindParam(':student_id', $student_id, PDO::PARAM_INT);

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
	public function deleteAtnStudent($studentId) {
		// Prepare the SQL query to delete a student record by ID.
		$sqlQuery = "DELETE FROM at_student WHERE id = :student_id";

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



	public function get_attn_date(){
		global $conn;
		$res = $conn->query("select distinct at_date from attn ");
		return $res;
		
	}


	public function attn_all_student($date){
		global $conn;
		$res = $conn->query("select at_student.name, attn.*
			from at_student
			inner join attn
			on at_student.st_id = attn.st_id
			where at_date = '$date' ");
		return $res;
	}


	public function update_attn($date,$atten){
		global $conn;
		foreach($atten as $key =>$attn_value ){
			if($attn_value == "present"){
				$sql = "update attn set atten='present' where st_id='$key' and at_date='$date' ";
				$att_res = $conn->query($sql);
			}elseif($attn_value == "absent"){
				$sql = "update attn set atten='absent' where st_id='$key' and at_date='$date' ";
				$att_res = $conn->query($sql);
			}
		}
		if($att_res){
			return true;
		}else{
			return false;
		}
	}


	//grading system
	public function add_marks($stid,$subject,$semester,$marks){
		global $conn;
		$qry = "select * from result where st_id='$stid' and sub='$subject' and semester='$semester' ";
		$query = $conn->query($qry);
		$count = $query->num_rows;
		if($count>0){
			return false;
		}else{
		$sql = "insert into result(st_id,marks,sub,semester) values('$stid','$marks','$subject','$semester')";
		$result = $conn->query($sql);
		return $result;
		}
	}


	//show marks
	public function show_marks($stid,$semester){
		global $conn;
		$result = $conn->query("select * from result where st_id='$stid' and semester='$semester' ");
		$count = $result->num_rows;
		if($count>0){
			return $result;
		}else{
			return false;
		}
		
	}


	//update student result
	public function update_result($stid,$subject = array(),$semester){
		global $conn;
		foreach($subject as $key =>$mark ){
			$sql = "update result set marks='$mark' where st_id='$stid' and semester='$semester' and sub='$key' ";
				$result = $conn->query($sql);	
		}
		if($result){
			return true;
		}else{
			return false;
		}
	}


	public function view_cgpa($stid){
		global $conn;
		$sql = "select * from result where st_id='$stid'";
		$result = $conn->query($sql);
		return $result;
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