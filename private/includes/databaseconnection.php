<?php

/**
 * Class DatabaseConnection
 *
 * Represents a database connection.
 */
class DatabaseConnection {
    private $config;
    private $connection;

    /**
     * DatabaseConnection constructor.
     *
     * @param array $config An array containing the database configuration parameters.
     * @throws PDOException If an exception occurs during the connection process.
     */
    public function __construct($config) {
        $this->config = $config;
        $this->connection = $this->connect();
    }

    /**
     * Connects to a MySQL database using PDO.
     *
     * @return PDO The PDO object representing the established database connection.
     * @throws PDOException If an exception occurs during the connection process.
     */
    private function connect() {
        $hostname = $this->config['hostname'];
        $database = $this->config['database'];
        $username = $this->config['username'];
        $password = $this->config['password'];

        try {
            $dsn = "mysql:host=$hostname;dbname=$database;charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $connection = new PDO($dsn, $username, $password, $options);
            return $connection;
        } catch (PDOException $e) {
            throw $e; // Re-throw the exception to be handled by the calling code
        }
    }

    /**
     * Gets the PDO object representing the database connection.
     *
     * @return PDO The PDO object representing the established database connection.
     */
    public function getConnection() {
        return $this->connection;
    }

    /**
     * Closes the database connection.
     */
    public function closeConnection() {
        $this->connection = null;
    }
}

?>