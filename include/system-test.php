<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔧 Session & Database Test</h1>";

// Test 1: PHP Basic Info
echo "<h3>1. PHP Version:</h3>";
echo "<p>PHP Version: " . phpversion() . "</p>";

// Test 2: Session Test
echo "<h3>2. Session Test:</h3>";
try {
    session_start();
    echo "<p style='color: green;'>✅ Session started successfully</p>";
    
    // Check if session variables exist
    if (isset($_SESSION['username'])) {
        echo "<p style='color: green;'>✅ User logged in: " . $_SESSION['username'] . "</p>";
    } else {
        echo "<p style='color: red;'>❌ No username in session</p>";
        echo "<p>Available session variables:</p>";
        echo "<pre>";
        print_r($_SESSION);
        echo "</pre>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Session error: " . $e->getMessage() . "</p>";
}

// Test 3: Database Connection Test
echo "<h3>3. Database Connection Test:</h3>";
try {
    // Use the same credentials as config.php
    $host = "localhost";
    $username = "u820431346_newinsurance";
    $password = "Metx@123";
    $database = "u820431346_newinsurance";
    
    $con = new mysqli($host, $username, $password, $database);
    
    if ($con->connect_error) {
        echo "<p style='color: red;'>❌ Database connection failed: " . $con->connect_error . "</p>";
    } else {
        echo "<p style='color: green;'>✅ Database connection successful</p>";
        
        // Test a simple query
        $result = $con->query("SELECT COUNT(*) as count FROM policy");
        if ($result) {
            $row = $result->fetch_assoc();
            echo "<p style='color: green;'>✅ Database query successful - Total policies: " . $row['count'] . "</p>";
        } else {
            echo "<p style='color: red;'>❌ Database query failed: " . $con->error . "</p>";
        }
    }
    $con->close();
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database error: " . $e->getMessage() . "</p>";
}

// Test 4: Account Database Connection Test
echo "<h3>4. Account Database Connection Test:</h3>";
try {
    $host = "localhost";
    $username = "u820431346_new_account";
    $password = "otRkXMf]5;Ny";
    $database = "u820431346_new_account";
    
    $acc = new mysqli($host, $username, $password, $database);
    
    if ($acc->connect_error) {
        echo "<p style='color: red;'>❌ Account database connection failed: " . $acc->connect_error . "</p>";
    } else {
        echo "<p style='color: green;'>✅ Account database connection successful</p>";
        
        // Test a simple query
        $result = $acc->query("SELECT COUNT(*) as count FROM income");
        if ($result) {
            $row = $result->fetch_assoc();
            echo "<p style='color: green;'>✅ Account database query successful - Total income records: " . $row['count'] . "</p>";
        } else {
            echo "<p style='color: red;'>❌ Account database query failed: " . $acc->error . "</p>";
        }
    }
    $acc->close();
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Account database error: " . $e->getMessage() . "</p>";
}

// Test 5: POST data simulation
echo "<h3>5. POST Data Test:</h3>";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<p style='color: green;'>✅ POST request received</p>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
} else {
    echo "<p style='color: orange;'>⚠️ No POST data (this is normal for direct access)</p>";
}

echo "<br><a href='../policies.php' style='padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>← Back to Policies</a>";
?>
