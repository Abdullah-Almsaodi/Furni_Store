<?php
// Database configuration
define('DB_HOST', 'localhost'); // Database host, typically 'localhost' or an IP address
define('DB_NAME', 'furniture_store'); // The name of your database
define('DB_USER', 'root'); // Your database username
define('DB_PASS', ''); // Your database password
define('DB_CHARSET', 'utf8mb4'); // Character set to use, utf8mb4 supports a wide range of characters including emojis

// Other configuration options can go here

// Debug mode
define('DEBUG_MODE', true); // Set to 'false' in a production environment

// Include this to display all errors in debug mode
if (DEBUG_MODE) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

// Database connection settings
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Set the default fetch mode to associative array
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Turn off emulation mode for prepared statements
];

try {
    // Create a new PDO instance with the database connection
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        $options
    );
} catch (PDOException $e) {
    // Handle connection error
    if (DEBUG_MODE) {
        die("Database connection failed: " . $e->getMessage());
    } else {
        die("Database connection failed. Please try again later.");
    }
}


// config.php
define('BASE_URL', 'http://192.168.1.6/Furni_Store/api/v1/');
