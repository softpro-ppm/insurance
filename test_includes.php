<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing file includes...\n<br>";

// Test session.php
if(file_exists('include/session.php')) {
    echo "✓ session.php exists\n<br>";
    try {
        include 'include/session.php';
        echo "✓ session.php included successfully\n<br>";
    } catch (Exception $e) {
        echo "✗ session.php error: " . $e->getMessage() . "\n<br>";
    }
} else {
    echo "✗ session.php missing\n<br>";
}

// Test config.php
if(file_exists('include/config.php')) {
    echo "✓ config.php exists\n<br>";
    try {
        include 'include/config.php';
        echo "✓ config.php included successfully\n<br>";
        
        // Test database connection
        if(isset($con)) {
            if($con->ping()) {
                echo "✓ Database connection working\n<br>";
            } else {
                echo "✗ Database connection failed\n<br>";
            }
        } else {
            echo "✗ Database connection object not found\n<br>";
        }
    } catch (Exception $e) {
        echo "✗ config.php error: " . $e->getMessage() . "\n<br>";
    }
} else {
    echo "✗ config.php missing\n<br>";
}

// Test modal files
$modals = ['include/add-policy-modal.php', 'include/edit-policy-modal.php'];
foreach($modals as $modal) {
    if(file_exists($modal)) {
        echo "✓ $modal exists\n<br>";
    } else {
        echo "✗ $modal missing\n<br>";
    }
}

// Test CSS and JS files
$assets = [
    'assets/css/modern-theme.css',
    'assets/js/global-search.js',
    'assets/js/policies-optimized.js'
];

foreach($assets as $asset) {
    if(file_exists($asset)) {
        echo "✓ $asset exists\n<br>";
    } else {
        echo "✗ $asset missing\n<br>";
    }
}

echo "\nTest completed.";
?>
