<!DOCTYPE html>
<html>
<head>
    <title>Test Page</title>
</head>
<body>
    <h1>Test Page</h1>
    <p>This is a test page to check if PHP is working correctly.</p>
    
    <?php
    // Basic PHP test
    echo "<p>PHP is working correctly!</p>";
    echo "<p>Current date and time: " . date('Y-m-d H:i:s') . "</p>";
    
    // Try to include account.php
    try {
        echo "<h2>Testing Database Connections</h2>";
        echo "<p>Attempting to connect to insurance database...</p>";
        require_once 'include/config.php';
        echo "<p>✅ Insurance database connection successful</p>";
        
        echo "<p>Attempting to connect to account database...</p>";
        require_once 'include/account.php';
        echo "<p>✅ Account database connection successful</p>";
    } catch (Exception $e) {
        echo "<p>❌ Error: " . $e->getMessage() . "</p>";
    }
    ?>
    
    <h2>Links for Testing</h2>
    <ul>
        <li><a href="policies.php">Go to Policies Page</a></li>
        <li><a href="home.php">Go to Home Page</a></li>
    </ul>
</body>
</html>
