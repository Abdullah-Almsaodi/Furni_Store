<?php
// admin/classes/Database.php
class Database
{
    private $host = 'localhost';
    private $dbName = 'furniture_store';
    private $username = 'root';
    private $password = '';
    private $conn;
    public function connect()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->dbName, $this->username, $this->password);
            // Set PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Set additional security attributes
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // Disable prepared statement emulation
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Set default fetch mode to associative array
            $this->conn->setAttribute(PDO::ATTR_PERSISTENT, false); // Disable persistent connections

        } catch (PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
            die();
        }

        return $this->conn;
    }
}
