<?php
class databaseConnection{
	private $hostname = "localhost";
	private $username = "root";
	private $password = "DedSec44678@";
	private $database = "uni";

	public function __construct(){

		global $conn;
		$conn = new mysqli($this->hostname, $this->username, $this->password, $this->database);

		//check error 
		if(!$conn)
		{
			die("Database cannot established connection properly: " . $conn);
		}

	}
}

?>
