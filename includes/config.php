<?php

/**
* Class DatabaseConnection
*
* This class provides a secure and documented way to establish a database connection using PDO.
*/
class DatabaseConnection {
	private $hostname = "localhost";
	private $username = "root";
	private $password = "DedSec44678@";
	private $database = "uni";

	/**
     * Connect to the database using PDO.
     *
     * @return PDO|null Returns a PDO instance if the connection is successful, or null if an error occurs.
     */
	protected function connect() {
        try {
            // Create a PDO instance with the specified connection details
            $pdo = new PDO("mysql:host=" . $this->hostname . ";dbname=" . $this->database, $this->username, $this->password);

            // Set PDO to throw exceptions on errors for better error handling
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;
        } catch (PDOException $e) {
            // If there's an error, handle it gracefully and provide an error message
            // You can log the error instead of using die() in a production environment
            die("Connection failed: " . $e->getMessage());
            return null;
        }
    }
}

?>
