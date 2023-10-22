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
	public function get_all_student()
	{
		// Define the SQL query to retrieve all student information
		$query_value = "SELECT * FROM st_info ORDER BY st_id ASC";
		
		try
		{
			// Prepare the SQL query and execute it
			$query = parent::connect()->prepare($query_value);
			$query->execute();
			
			// Return the PDOStatement object
			return $query;
		}
		catch(PDOException $e)
		{
			// Log any database errors that occur
			error_log("Database Error: " . $e->getMessage());
		}
		
		// Return null if an error occurs
		return null;
	}


	/**
	 * Search for students based on a query.
	 *
	 * This function performs a search for students in the "st_info" table based on the provided query.
	 * It searches for matches in the student ID, name, contact, and email fields and returns the results.
	 *
	 * @param string $query The search query.
	 * @return PDOStatement|false Returns a PDOStatement object with the search results on success or false on failure.
	 */
	public function search($user_input)
	{
		// Define the SQL query for searching students
		$query_value = "SELECT * FROM st_info WHERE (st_id LIKE :query OR name LIKE :query OR contact LIKE :query OR email LIKE :query) ORDER BY st_id";
		
		try
		{
			// Prepare the SQL query
			$pdo = parent::connect();
			$query = $pdo->prepare($query_value);
			
			// Bind the search query as a parameter (using prepared statements to prevent SQL injection)
			$query->bindValue(':query', '%' . $user_input . '%');
			
			// Execute the query
			$query->execute();
			
			// Return the PDOStatement object with search results
			return $query;
		}
		catch(PDOException $e)
		{
			// Log any database errors that occur
			error_log("Database Error: " . $e->getMessage());
		}
		
		// Return null if an error occurs
		return null;
	}
	

	/**
	 * Admin Login Function
	 *
	 * This function is responsible for authenticating an admin user by checking their
	 * credentials in the database.
	 *
	 * @param string $username The admin's username.
	 * @param string $password The admin's password.
	 *
	 * @return bool Returns true if the login is successful, false otherwise.
	 */
	public function admin_login($username, $password)
	{
		// Define the SQL query with placeholders for username and password
		$query_value = "SELECT `id`, `username` FROM `admin` WHERE `username` = :username AND `password` = :password";

		try 
		{
			// Prepare the SQL statement
			$stmt = parent::connect()->prepare($query_value);

			// Bind the parameters to the placeholders
			$stmt->bindParam(':username', $username, PDO::PARAM_STR);
			$stmt->bindParam(':password', $password, PDO::PARAM_STR);

			// Execute the query
			$stmt->execute();

			// Get the count of rows that match the query
			$count = $stmt->rowCount();

			if ($count == 1)
			{
				// Fetch the admin information
				$admin_info = $stmt->fetch(PDO::FETCH_ASSOC);

				// Set session variables for successful login
				$_SESSION['admin_login'] = true;
				$_SESSION['admin_id'] = $admin_info['id'];
				$_SESSION['admin_name'] = $admin_info['username'];

				return true; // Successful login
			}
		} 
		catch(PDOException $e) 
		{
			// Handle database errors gracefully, log the error, and return false
			error_log("Database Error: " . $e->getMessage());
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
	public function delete_student($student_id)
	{
		// Define the SQL query to delete a student by their Student ID
		$query_value = "DELETE FROM st_info WHERE st_id = :student_id";

		try
		{
			// Get a PDO instance for the database connection
			$pdo = parent::connect();

			// Prepare the SQL query
			$stmt = $pdo->prepare($query_value);

			// Bind the Student ID as a parameter to prevent SQL injection
			$stmt->bindParam(":student_id", $student_id, PDO::PARAM_STR);

			// Execute the query and check the result
			$result = $stmt->execute();

			if ($result) {
				return true; // Deletion was successful
			}
		} 
		catch (PDOException $e)
		{
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
	public function fetchStudentAttendanceRecords()
	{
		$query = "SELECT * FROM at_student";
		
		try {
			// Get a database connection using the parent's 'connect' method.
			$pdo = parent::connect();

			// Prepare the SQL query.
			$stmt = $pdo->prepare($query);

			// Execute the query.
			$result = $stmt->execute();

			if ($result) {
				return $stmt; // Return the PDOStatement with the result set.
			}
		} catch (PDOException $e) {
			error_log("Database Error: " . $e->getMessage());
		}
		
		return null;
	}



	public function add_attn_student($name,$stid){
		global $conn;
		$sql = "insert into at_student(name,st_id) values('$name','$stid')";
		$result = $conn->query($sql);
		
		$sql2 = "insert into attn(st_id) values('$stid')";
		$result = $conn->query($sql2);
		return $result;
	}
	

	public function insertattn($cur_date,$atten = array()){
		global $conn;
		$sql = "select distinct at_date from attn";
		$result = $conn->query($sql);
		while($row = $result->fetch_assoc()){
			$db_date = $row['at_date'];
			if($cur_date == $db_date){
				return false;
			}
		}
		foreach($atten as $key =>$attn_value ){
			if($attn_value == "present"){
				$sql = "insert into attn(st_id,atten,at_date) values('$key','present','$cur_date')";
				$att_res = $conn->query($sql);
			}elseif($attn_value == "absent"){
				$sql = "insert into attn(st_id,atten,at_date) values('$key','absent','$cur_date')";
				$att_res = $conn->query($sql);
			}
		}
		if($att_res){
			return true;
		}else{
			return false;
		}
		
	}


	public function delete_atn_student($at_id){
		global $conn;
		$res = $conn->query("delete from at_student where id = '$at_id' ");
		return $res;
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