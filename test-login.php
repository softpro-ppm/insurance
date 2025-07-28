<?php
// test-login.php - Quick login for testing
session_start();

if (!isset($_SESSION['username'])) {
    $_SESSION['username'] = 'TestUser';
    $_SESSION['user_id'] = 1;
    echo "Session created for testing.<br>";
} else {
    echo "Already logged in as: " . $_SESSION['username'] . "<br>";
}

echo '<a href="policies.php">Go to Policies Page</a>';
?>
