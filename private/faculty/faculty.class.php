<?php

class Faculty extends DatabaseConnection {
    

	/**
	 * Register a new faculty member.
	 *
	 * @param string $name     The name of the faculty member.
	 * @param string $uname    The username for login.
	 * @param string $pass     The password for login.
	 * @param string $email    The email address of the faculty member.
	 * @param string $bday     The birthday of the faculty member.
	 * @param string $gender   The gender of the faculty member.
	 * @param string $edu      The education level of the faculty member.
	 * @param string $contact  The contact information of the faculty member.
	 * @param string $address  The address of the faculty member.
	 *
	 * @return bool True if registration is successful, false otherwise.
	 */
	public function facultyRegistration($name, $uname, $pass, $email, $bday, $gender, $edu, $contact, $address) {
		// Check if the username already exists
		$checkUsernameQuery = "SELECT `id` FROM `faculty` WHERE `username`=:student_username";

		try {
			$checkUsernameStatement = parent::getConnection()->prepare($checkUsernameQuery);
			$checkUsernameStatement->bindParam(":student_username", $uname, PDO::PARAM_STR);
			$checkUsernameStatement->execute();

			$count = $checkUsernameStatement->rowCount();

			if ($count == 0) {
				// Username is not taken, proceed with registration
				$insertQuery = "INSERT INTO faculty(`name`, `username`, `password`, `email`, `birthday`, `gender`, `education`, `contact`, `address`) VALUES  (:student_name, :student_username, :student_password, :student_email, :student_birthday, :student_gender, :student_education, :student_contact, :student_address)";

				$insertStatement = parent::getConnection()->prepare($insertQuery);

				// Bind parameters
				$insertStatement->bindParam(":student_name", $name, PDO::PARAM_STR);
				$insertStatement->bindParam(":student_username", $uname, PDO::PARAM_STR);
				$insertStatement->bindParam(":student_password", $pass, PDO::PARAM_STR);
				$insertStatement->bindParam(":student_email", $email, PDO::PARAM_STR);
				$insertStatement->bindParam(":student_birthday", $bday, PDO::PARAM_STR);
				$insertStatement->bindParam(":student_gender", $gender, PDO::PARAM_STR);
				$insertStatement->bindParam(":student_education", $edu, PDO::PARAM_STR);
				$insertStatement->bindParam(":student_contact", $contact, PDO::PARAM_STR);
				$insertStatement->bindParam(":student_address", $address, PDO::PARAM_STR);

				// Execute the query
				if ($insertStatement->execute()) {
					// Registration successful
					return true;
				}
			}
		} catch (PDOException $e) {
			// Log any database errors
			error_log("Database Error: " . $e->getMessage());
		}

		// Registration failed
		return false;
	}



	/**
	 * Get faculty information by username.
	 *
	 * @param string $username The username of the faculty member.
	 *
	 * @return mixed PDOStatement|bool Returns a PDOStatement on success, false on failure.
	 */
	public function getFacultyByUsername($username) 
	{
		// Query to retrieve faculty information by username
		$sql = "SELECT * FROM `faculty` WHERE `username`=:username";

		try {
			// Prepare and execute the query
			$result = parent::getConnection()->prepare($sql);
			$result->bindParam(":username", $username, PDO::PARAM_STR);

			if ($result->execute()) {
				// Return the PDOStatement on success
				return $result;
			}
		} catch (PDOException $e) {
			// Log any database errors
			error_log("Database Error: " . $e->getMessage());
		}

		// Return false on failure
		return false;
	}



	/**
	 * Get all faculty members from the database.
	 *
	 * @return mixed PDOStatement|bool Returns a PDOStatement on success, false on failure.
	 */
	public function getAllFaculty()
	{
		// Query to retrieve all faculty members ordered by ID in ascending order
		$sql = "SELECT * FROM `faculty` ORDER BY `id` ASC";

		try {
			// Prepare and execute the query
			$result = parent::getConnection()->prepare($sql);

			if ($result->execute()) {
				// Return the PDOStatement on success
				return $result;
			}
		} catch (PDOException $e) {
			// Log any database errors
			error_log("Database Error: " . $e->getMessage());
		}

		// Return false on failure
		return false;
	}



	/**
	 * Login function for faculty members.
	 *
	 * @param string $uname The username of the faculty member.
	 * @param string $pass The password for login.
	 *
	 * @return bool True if login is successful, false otherwise.
	 */
	public function facultyLogin($uname, $pass) 
	{
		// Query to retrieve faculty information by username and password
		$sql = "SELECT `id`, `username`, `name` FROM `faculty` WHERE `username`=:username AND `password`=:passwords";

		try {
			// Prepare and execute the query
			$result = parent::getConnection()->prepare($sql);

			// Bind parameters
			$result->bindParam(":username", $uname, PDO::PARAM_STR);
			$result->bindParam(":passwords", $pass, PDO::PARAM_STR);

			$result->execute();

			// Get the number of rows returned by the query
			$count = $result->rowCount();
			$facultyInfo = $result->fetch(PDO::FETCH_ASSOC);

			if ($count == 1) {
				// Start a session and set session variables on successful login
				session_start();
				$_SESSION['fct_login'] = true;
				$_SESSION['f_id'] = $facultyInfo['id'];
				$_SESSION['f_uname'] = $facultyInfo['username'];
				$_SESSION['f_name'] = $facultyInfo['name'];
				
				// Return true to indicate successful login
				return true;
			} else {
				// Return false if login fails
				return false;
			}
		} catch (PDOException $e) {
			// Log any database errors
			error_log("Database Error: " . $e->getMessage());
		}

		// Return false on failure
		return false;
	}



	/**
	 * Logout function for faculty members.
	 * Unsets faculty-related session variables and marks faculty as logged out.
	 */
	public function facultyLogout() 
	{
		// Check if the faculty member is logged in
		if ($_SESSION['fct_login']) {
			// Mark the faculty member as logged out
			$_SESSION['fct_login'] = false;

			// Unset specific faculty-related session variables
			unset($_SESSION['f_id']);
			unset($_SESSION['f_uname']);
			unset($_SESSION['f_name']);
			unset($_SESSION['fct_login']);
		}
		// If the faculty member is not logged in, do nothing
		// You may choose to provide a message or log an event

		// Optionally, destroy the entire session if needed
		// session_destroy();
	}



	/**
	 * Get the current session status for faculty login.
	 *
	 * @return bool True if the faculty member is logged in, false otherwise.
	 */
	public function isFacultyLoggedIn() 
	{
		// Using error suppression to handle the case where the session variable is not set
		// It's better to check if the session variable is set instead of suppressing errors
		return isset($_SESSION['fct_login']) ? $_SESSION['fct_login'] : false;
	}

}