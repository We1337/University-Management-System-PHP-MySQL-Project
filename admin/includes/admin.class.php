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

	//search student
	//Search Query
	public function search($query){
		global $conn;
		$result = $conn->query("SELECT * FROM st_info WHERE (st_id LIKE '%".$query."%'
							OR name LIKE '%".$query."%'
								OR contact LIKE '%".$query."%'
									OR email LIKE '%".$query."%') order by st_id");
		return $result;
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




	public function get_admin_session(){
		return @$_SESSION['admin_login'];
	}
	//admin logout 
	public function admin_logout(){
		$_SESSION['admin_login'] = false;
		unset($_SESSION['admin_id']);
		unset($_SESSION['admin_name']);
		unset($_SESSION['admin_login']);
	}
	//delete student
	public function delete_student($st_id){
		global $conn;
		$sql = "delete from st_info where st_id='$st_id' ";
		$result = $conn->query($sql);
		if($result){
			return true;
		}else{
			return false;
		}
	}
	//attendance system
	
	public function attn_student(){
		global $conn;
		$sql = "select * from at_student";
		$result = $conn->query($sql);
		return $result;
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