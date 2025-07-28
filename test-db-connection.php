<?php
// Live server database test - testing different connection approaches
session_start();

// Set headers for JSON response
header('Content-Type: application/json; charset=utf-8');

try {
    // Test different database configurations
    $configs = [
        // Original config
        [
            'host' => 'localhost',
            'username' => 'u820431346_newinsurance',
            'password' => 'Softpro@123',
            'database' => 'u820431346_newinsurance'
        ],
        // Alternative configurations
        [
            'host' => '127.0.0.1',
            'username' => 'u820431346_newinsurance',
            'password' => 'Softpro@123',
            'database' => 'u820431346_newinsurance'
        ],
        // Check if it's a socket connection issue
        [
            'host' => 'localhost',
            'username' => 'u820431346_newinsurance',
            'password' => 'Softpro@123',
            'database' => 'u820431346_newinsurance',
            'port' => 3306
        ]
    ];

    $working_config = null;
    $connection_errors = [];

    foreach ($configs as $index => $config) {
        try {
            $con = new mysqli(
                $config['host'],
                $config['username'],
                $config['password'],
                $config['database'],
                $config['port'] ?? 3306
            );

            if (!$con->connect_errno) {
                $working_config = $config;
                $working_config['index'] = $index;
                break;
            } else {
                $connection_errors[] = "Config $index: " . $con->connect_error;
            }
        } catch (Exception $e) {
            $connection_errors[] = "Config $index Exception: " . $e->getMessage();
        }
    }

    if ($working_config) {
        echo json_encode([
            'success' => true,
            'message' => 'Connection successful',
            'working_config' => $working_config,
            'server_info' => $con->server_info ?? 'N/A'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'All connection attempts failed',
            'errors' => $connection_errors,
            'php_version' => PHP_VERSION,
            'mysqli_version' => mysqli_get_client_version()
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Test script error: ' . $e->getMessage()
    ]);
}
?>
