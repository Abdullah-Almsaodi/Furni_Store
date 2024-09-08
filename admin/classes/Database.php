<?php

class Database
{
    private static $instance = null; // Singleton instance
    private $conn;
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $charset;

    // Private constructor to prevent multiple instances
    private function __construct()
    {
        $this->host     = DB_HOST;
        $this->db_name  = DB_NAME;
        $this->username = DB_USER;
        $this->password = DB_PASS;
        $this->charset  = DB_CHARSET;

        // Create the connection
        $this->connect();
    }

    // Method to establish a connection
    private function connect()
    {
        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=" . $this->charset;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Enable exceptions
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch associative arrays
                PDO::ATTR_EMULATE_PREPARES   => false,                  // Disable emulation
            ];

            // Establish the connection
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            // Handle errors securely
            if (DEBUG_MODE) {
                die("Database connection failed: " . $e->getMessage()); // Show error in debug mode
            } else {
                die("Database connection failed. Please try again later."); // General error message in production
            }
        }
    }

    // Singleton: Get the single instance of the database connection
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Return the PDO connection
    public function getConnection()
    {
        return $this->conn;
    }

    // Prevent cloning the instance
    private function __clone() {}

    // Prevent unserialization of the instance
    private function __wakeup() {}
}
