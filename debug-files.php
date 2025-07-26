<?php
// Debug script to check files in database
include 'include/config.php';

echo "<h3>Files Debug Information</h3>";

// Check a few policies and their files
$policies_sql = mysqli_query($con, "SELECT id, vehicle_number FROM policy ORDER BY id DESC LIMIT 5");

while ($policy = mysqli_fetch_array($policies_sql)) {
    echo "<h4>Policy ID: {$policy['id']} - Vehicle: {$policy['vehicle_number']}</h4>";
    
    // Check policy files
    echo "<strong>Policy Files:</strong><br>";
    $files_sql = mysqli_query($con, "SELECT * FROM files WHERE policy_id='{$policy['id']}' ORDER BY id ASC");
    $file_count = 0;
    while ($file = mysqli_fetch_array($files_sql)) {
        $file_count++;
        echo "File {$file_count}: ID={$file['id']}, Filename={$file['files']}<br>";
    }
    if ($file_count == 0) {
        echo "No policy files found<br>";
    }
    
    // Check RC files
    echo "<strong>RC Files:</strong><br>";
    $rc_sql = mysqli_query($con, "SELECT * FROM rc WHERE policy_id='{$policy['id']}' ORDER BY id ASC");
    $rc_count = 0;
    while ($rc = mysqli_fetch_array($rc_sql)) {
        $rc_count++;
        echo "RC File {$rc_count}: ID={$rc['id']}, Filename={$rc['files']}<br>";
    }
    if ($rc_count == 0) {
        echo "No RC files found<br>";
    }
    
    echo "<hr>";
}

// Check table structures
echo "<h4>Files Table Structure:</h4>";
$files_structure = mysqli_query($con, "DESCRIBE files");
while ($field = mysqli_fetch_array($files_structure)) {
    echo "{$field['Field']} - {$field['Type']}<br>";
}

echo "<h4>RC Table Structure:</h4>";
$rc_structure = mysqli_query($con, "DESCRIBE rc");
while ($field = mysqli_fetch_array($rc_structure)) {
    echo "{$field['Field']} - {$field['Type']}<br>";
}
?>
