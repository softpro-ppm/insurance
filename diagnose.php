<?php
// diagnose.php - Simple diagnostic script
session_start();

echo "<h1>Insurance System Diagnostics</h1>";

echo "<h2>1. PHP Configuration</h2>";
echo "PHP Version: " . PHP_VERSION . "<br>";
echo "Session Status: " . session_status() . "<br>";
echo "Session ID: " . session_id() . "<br>";

echo "<h2>2. Session Data</h2>";
echo "Username: " . ($_SESSION['username'] ?? 'Not set') . "<br>";
echo "Last Activity: " . ($_SESSION['last_activity'] ?? 'Not set') . "<br>";

echo "<h2>3. Database Connection Test</h2>";
try {
    $host = "localhost";
    $username = "u820431346_newinsurance";
    $password = "Softpro@123";
    $database = "u820431346_newinsurance";

    $con = new mysqli($host, $username, $password, $database);
    
    if ($con->connect_errno) {
        echo "❌ Database connection failed: " . $con->connect_error . "<br>";
    } else {
        echo "✅ Database connection successful<br>";
        echo "Server info: " . $con->server_info . "<br>";
        
        // Test table access
        $result = $con->query("SELECT COUNT(*) as count FROM policy");
        if ($result) {
            $row = $result->fetch_assoc();
            echo "✅ Policy table accessible, total records: " . $row['count'] . "<br>";
        } else {
            echo "❌ Policy table query failed: " . $con->error . "<br>";
        }
    }
} catch (Exception $e) {
    echo "❌ Database exception: " . $e->getMessage() . "<br>";
}

echo "<h2>4. File System Check</h2>";
$files_to_check = [
    'include/get-policy-data-ultra-clean.php',
    'include/view-policy.php',
    'include/config.php',
    'include/session.php'
];

foreach ($files_to_check as $file) {
    if (file_exists($file)) {
        echo "✅ $file exists<br>";
    } else {
        echo "❌ $file missing<br>";
    }
}

echo "<h2>5. Test Policy Data API</h2>";
if (isset($_SESSION['username'])) {
    echo '<button onclick="testPolicyAPI()">Test Policy API (ID: 1243)</button>';
    echo '<div id="api-result" style="margin-top: 10px; padding: 10px; border: 1px solid #ccc;"></div>';
    
    echo '<script>
    function testPolicyAPI() {
        document.getElementById("api-result").innerHTML = "Testing...";
        
        fetch("include/get-policy-data-ultra-clean.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: "policy_id=1243"
        })
        .then(response => response.text())
        .then(text => {
            try {
                const data = JSON.parse(text);
                document.getElementById("api-result").innerHTML = 
                    "<h4>Success!</h4><pre>" + JSON.stringify(data, null, 2) + "</pre>";
            } catch (e) {
                document.getElementById("api-result").innerHTML = 
                    "<h4>Parse Error!</h4><p>Response is not valid JSON:</p><pre>" + text + "</pre>";
            }
        })
        .catch(error => {
            document.getElementById("api-result").innerHTML = 
                "<h4>Network Error!</h4><pre>" + error.toString() + "</pre>";
        });
    }
    </script>';
} else {
    echo "❌ Not logged in - cannot test API<br>";
}

echo "<h2>6. System Status</h2>";
echo "Current time: " . date('Y-m-d H:i:s') . "<br>";
echo "Script execution time: " . round((microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) * 1000) . "ms<br>";
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h1, h2 { color: #333; }
pre { background: #f5f5f5; padding: 10px; border-radius: 4px; overflow-x: auto; }
button { background: #007cba; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
button:hover { background: #005a87; }
</style>
