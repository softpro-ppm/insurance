<?php
// setup-local-db.php - Create local database and tables
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$host = "localhost";
$username = "root";
$password = "";

echo "<h2>Setting up local database...</h2>";

try {
    // Connect to MySQL server (without database)
    $con = new mysqli($host, $username, $password);
    
    if ($con->connect_errno) {
        throw new Exception("Failed to connect to MySQL: " . $con->connect_error);
    }
    
    echo "✅ Connected to MySQL server<br>";
    
    // Create database
    $sql = "CREATE DATABASE IF NOT EXISTS insurance_local";
    if ($con->query($sql)) {
        echo "✅ Database 'insurance_local' created or already exists<br>";
    } else {
        throw new Exception("Error creating database: " . $con->error);
    }
    
    // Select the database
    $con->select_db('insurance_local');
    
    // Create policy table with minimal structure
    $sql = "CREATE TABLE IF NOT EXISTS policy (
        id INT AUTO_INCREMENT PRIMARY KEY,
        vehicle_number VARCHAR(50) NOT NULL,
        name VARCHAR(100) NOT NULL,
        phone VARCHAR(20),
        vehicle_type VARCHAR(50),
        policy_type VARCHAR(50),
        insurance_company VARCHAR(100),
        policy_issue_date DATE,
        policy_start_date DATE,
        policy_end_date DATE,
        premium DECIMAL(10,2) DEFAULT 0,
        revenue DECIMAL(10,2) DEFAULT 0,
        payout DECIMAL(10,2) DEFAULT 0,
        customer_paid DECIMAL(10,2) DEFAULT 0,
        discount DECIMAL(10,2) DEFAULT 0,
        calculated_revenue DECIMAL(10,2) DEFAULT 0,
        chassiss VARCHAR(100),
        comments TEXT,
        fc_expiry_date DATE,
        permit_expiry_date DATE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    if ($con->query($sql)) {
        echo "✅ Policy table created or already exists<br>";
    } else {
        throw new Exception("Error creating policy table: " . $con->error);
    }
    
    // Check if table has data, if not insert sample data
    $result = $con->query("SELECT COUNT(*) as count FROM policy");
    $row = $result->fetch_assoc();
    
    if ($row['count'] == 0) {
        echo "<br>Inserting sample data...<br>";
        
        $sample_data = [
            ["AP01AB1234", "John Doe", "9876543210", "Car", "Comprehensive", "ICICI Lombard", "2025-01-01", "2025-01-01", "2026-01-01", 25000, 2500, 22500, 25000, 0, 2500],
            ["AP02CD5678", "Jane Smith", "9876543211", "Bike", "Third Party", "Bajaj Allianz", "2025-02-01", "2025-02-01", "2026-02-01", 3000, 300, 2700, 3000, 0, 300],
            ["AP03EF9012", "Bob Johnson", "9876543212", "Car", "Comprehensive", "HDFC ERGO", "2025-03-01", "2025-03-01", "2026-03-01", 28000, 2800, 25200, 28000, 0, 2800]
        ];
        
        $stmt = $con->prepare("INSERT INTO policy (vehicle_number, name, phone, vehicle_type, policy_type, insurance_company, policy_issue_date, policy_start_date, policy_end_date, premium, revenue, payout, customer_paid, discount, calculated_revenue) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        foreach ($sample_data as $data) {
            $stmt->bind_param("sssssssssdddddd", ...$data);
            $stmt->execute();
        }
        
        echo "✅ Sample data inserted<br>";
    } else {
        echo "✅ Table already has " . $row['count'] . " records<br>";
    }
    
    echo "<br><h3>Setup Complete!</h3>";
    echo '<a href="test-connection.php">Test Connection</a> | ';
    echo '<a href="test-login.php">Login</a> | ';
    echo '<a href="policies.php">View Policies</a>';
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    
    // If root with no password doesn't work, try with password
    if (strpos($e->getMessage(), "Access denied") !== false) {
        echo "<br>Trying with password 'root'...<br>";
        try {
            $con = new mysqli($host, $username, "root");
            if (!$con->connect_errno) {
                echo "✅ Connected with password 'root'. Please update config-local.php<br>";
            }
        } catch (Exception $e2) {
            echo "❌ Also failed with password 'root': " . $e2->getMessage() . "<br>";
            echo "<br><strong>Please check your MySQL configuration:</strong><br>";
            echo "1. Make sure MySQL/XAMPP/WAMP is running<br>";
            echo "2. Check your MySQL username and password<br>";
            echo "3. Update config-local.php with correct credentials<br>";
        }
    }
}
?>
