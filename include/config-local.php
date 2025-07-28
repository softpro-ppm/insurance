<?php
// config-local.php - Local development configuration
date_default_timezone_set("Asia/Calcutta");

// Database configuration - Try local first, fallback to remote
$configs = [
    // Local development configuration
    [
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'insurance_local'
    ],
    // Alternative local with password
    [
        'host' => 'localhost',
        'username' => 'root',
        'password' => 'root',
        'database' => 'insurance_local'
    ],
    // Remote production configuration
    [
        'host' => 'localhost',
        'username' => 'u820431346_newinsurance',
        'password' => 'Softpro@123',
        'database' => 'u820431346_newinsurance'
    ]
];

$con = null;
$connection_error = '';

// Try each configuration until one works
foreach ($configs as $config) {
    try {
        $con = new mysqli($config['host'], $config['username'], $config['password'], $config['database']);
        
        if (!$con->connect_errno) {
            // Connection successful
            $con->set_charset("utf8");
            break;
        } else {
            $connection_error .= "Failed " . $config['database'] . ": " . $con->connect_error . "\n";
        }
    } catch (Exception $e) {
        $connection_error .= "Exception " . $config['database'] . ": " . $e->getMessage() . "\n";
    }
}

// If no connection worked, show error
if (!$con || $con->connect_errno) {
    echo "Database Connection Error:<br>";
    echo nl2br($connection_error);
    echo "<br><br>Please ensure MySQL is running and create a local database called 'insurance_local' or use the existing remote configuration.";
    exit();
}

// Include security classes if they exist
if (file_exists('secure-db.php')) {
    require_once 'secure-db.php';
}
if (file_exists('input-validator.php')) {
    require_once 'input-validator.php';
}
if (file_exists('audit-logger.php')) {
    require_once 'audit-logger.php';
}
?>
