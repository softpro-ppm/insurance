<?php
// Check account database structure
include 'config.php';

try {
    // Connect to account database
    $acc = new mysqli($hostname, $username_account, $password_account, $database_account);
    
    if ($acc->connect_error) {
        die("Account database connection failed: " . $acc->connect_error);
    }
    
    echo "<h3>Account Database Tables:</h3>";
    $result = $acc->query("SHOW TABLES");
    while ($row = $result->fetch_array()) {
        echo "<p><strong>Table:</strong> " . $row[0] . "</p>";
    }
    
    echo "<h3>Income Table Structure:</h3>";
    $result = $acc->query("DESCRIBE income");
    if ($result) {
        echo "<table border='1'>";
        echo "<tr><th>Column</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . $row['Default'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Income table does not exist. Available tables:</p>";
        $result = $acc->query("SHOW TABLES");
        while ($row = $result->fetch_array()) {
            echo "<p>- " . $row[0] . "</p>";
        }
    }
    
    $acc->close();
    
} catch (Exception $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>
