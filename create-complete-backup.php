<?php
/**
 * 🗄️ Complete Backup Script for Insurance System
 * Creates full backup before any changes
 */

// Set time limit and memory for large operations
set_time_limit(300);
ini_set('memory_limit', '512M');

// Create backup directory with timestamp
$backupDir = 'backups/backup_' . date('Y-m-d_H-i-s');
if (!file_exists('backups')) {
    mkdir('backups', 0755, true);
}
mkdir($backupDir, 0755, true);

echo "<!DOCTYPE html><html><head><title>Complete Backup</title>";
echo "<style>body{font-family:Arial;margin:20px;line-height:1.6;} .success{background:#d4edda;padding:15px;border-radius:8px;color:#155724;margin:10px 0;} .error{background:#f8d7da;padding:15px;border-radius:8px;color:#721c24;margin:10px 0;} .info{background:#e7f3ff;padding:15px;border-radius:8px;color:#004085;margin:10px 0;} .progress{background:#f8f9fa;padding:10px;border-radius:5px;margin:5px 0;}</style>";
echo "</head><body>";

echo "<h1>🗄️ Complete System Backup</h1>";
echo "<p><strong>Backup Directory:</strong> $backupDir</p>";

$backupResults = [];

// 1. Database Backup
echo "<div class='info'><h3>📊 1. Creating Database Backup...</h3></div>";

try {
    include 'include/config.php';
    
    // Get all tables
    $tables = [];
    $result = $con->query("SHOW TABLES");
    while ($row = $result->fetch_array()) {
        $tables[] = $row[0];
    }
    
    $sqlDump = "-- Complete Database Backup\n";
    $sqlDump .= "-- Created: " . date('Y-m-d H:i:s') . "\n";
    $sqlDump .= "-- Database: u820431346_newinsurance\n\n";
    
    foreach ($tables as $table) {
        echo "<div class='progress'>Backing up table: $table</div>";
        
        // Create table structure
        $result = $con->query("SHOW CREATE TABLE `$table`");
        $row = $result->fetch_array();
        $sqlDump .= "\n-- Table structure for `$table`\n";
        $sqlDump .= "DROP TABLE IF EXISTS `$table`;\n";
        $sqlDump .= $row[1] . ";\n\n";
        
        // Get table data
        $result = $con->query("SELECT * FROM `$table`");
        if ($result->num_rows > 0) {
            $sqlDump .= "-- Data for table `$table`\n";
            $sqlDump .= "INSERT INTO `$table` VALUES\n";
            
            $first = true;
            while ($row = $result->fetch_array(MYSQLI_NUM)) {
                if (!$first) $sqlDump .= ",\n";
                $sqlDump .= "(";
                for ($i = 0; $i < count($row); $i++) {
                    if ($i > 0) $sqlDump .= ", ";
                    $sqlDump .= is_null($row[$i]) ? "NULL" : "'" . $con->real_escape_string($row[$i]) . "'";
                }
                $sqlDump .= ")";
                $first = false;
            }
            $sqlDump .= ";\n\n";
        }
    }
    
    file_put_contents("$backupDir/complete_database_backup.sql", $sqlDump);
    $backupResults['database'] = "✅ Database backup created";
    echo "<div class='success'>✅ Database backup completed</div>";
    
} catch (Exception $e) {
    $backupResults['database'] = "❌ Database backup failed: " . $e->getMessage();
    echo "<div class='error'>❌ Database backup failed: " . $e->getMessage() . "</div>";
}

// 2. Policy Table Specific Backup
echo "<div class='info'><h3>📋 2. Creating Policy Table Backup...</h3></div>";

try {
    $result = $con->query("SELECT * FROM policy");
    $csvFile = fopen("$backupDir/policy_table_backup.csv", 'w');
    
    // Get column names
    $columns = [];
    $result2 = $con->query("DESCRIBE policy");
    while ($row = $result2->fetch_assoc()) {
        $columns[] = $row['Field'];
    }
    fputcsv($csvFile, $columns);
    
    // Export data
    $count = 0;
    while ($row = $result->fetch_assoc()) {
        fputcsv($csvFile, array_values($row));
        $count++;
    }
    fclose($csvFile);
    
    $backupResults['policy_csv'] = "✅ Policy table CSV exported ($count records)";
    echo "<div class='success'>✅ Policy table exported: $count records</div>";
    
} catch (Exception $e) {
    $backupResults['policy_csv'] = "❌ Policy CSV backup failed: " . $e->getMessage();
    echo "<div class='error'>❌ Policy CSV backup failed: " . $e->getMessage() . "</div>";
}

// 3. Documents Backup
echo "<div class='info'><h3>📁 3. Creating Documents Backup...</h3></div>";

try {
    $documentsBackedUp = 0;
    $uploadDirs = ['assets/uploads', 'assets/profile', 'uploads'];
    
    foreach ($uploadDirs as $uploadDir) {
        if (file_exists($uploadDir)) {
            echo "<div class='progress'>Backing up directory: $uploadDir</div>";
            
            $zip = new ZipArchive();
            $zipFile = "$backupDir/" . str_replace('/', '_', $uploadDir) . "_backup.zip";
            
            if ($zip->open($zipFile, ZipArchive::CREATE) === TRUE) {
                $files = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($uploadDir),
                    RecursiveIteratorIterator::LEAVES_ONLY
                );
                
                foreach ($files as $name => $file) {
                    if (!$file->isDir()) {
                        $relativePath = substr($file, strlen($uploadDir) + 1);
                        $zip->addFile($file, $relativePath);
                        $documentsBackedUp++;
                    }
                }
                $zip->close();
            }
        }
    }
    
    $backupResults['documents'] = "✅ Documents backup created ($documentsBackedUp files)";
    echo "<div class='success'>✅ Documents backup completed: $documentsBackedUp files</div>";
    
} catch (Exception $e) {
    $backupResults['documents'] = "❌ Documents backup failed: " . $e->getMessage();
    echo "<div class='error'>❌ Documents backup failed: " . $e->getMessage() . "</div>";
}

// 4. Configuration Backup
echo "<div class='info'><h3>⚙️ 4. Creating Configuration Backup...</h3></div>";

try {
    $configFiles = ['include/config.php', 'include/session.php', '.htaccess'];
    $configBackup = "$backupDir/config_backup/";
    mkdir($configBackup, 0755, true);
    
    $configCount = 0;
    foreach ($configFiles as $configFile) {
        if (file_exists($configFile)) {
            copy($configFile, $configBackup . basename($configFile));
            $configCount++;
        }
    }
    
    $backupResults['config'] = "✅ Configuration files backed up ($configCount files)";
    echo "<div class='success'>✅ Configuration backup completed: $configCount files</div>";
    
} catch (Exception $e) {
    $backupResults['config'] = "❌ Configuration backup failed: " . $e->getMessage();
    echo "<div class='error'>❌ Configuration backup failed: " . $e->getMessage() . "</div>";
}

// 5. Create Restore Script
echo "<div class='info'><h3>🔄 5. Creating Restore Script...</h3></div>";

$restoreScript = "#!/bin/bash
# Restore script for backup created on " . date('Y-m-d H:i:s') . "

echo 'Restoring database...'
mysql -h localhost -u u820431346_newinsurance -p'Softpro@123' u820431346_newinsurance < complete_database_backup.sql

echo 'Restoring documents...'
cd ..
";

foreach ($uploadDirs as $uploadDir) {
    if (file_exists($uploadDir)) {
        $zipFile = str_replace('/', '_', $uploadDir) . "_backup.zip";
        $restoreScript .= "unzip -o $backupDir/$zipFile -d $uploadDir/\n";
    }
}

$restoreScript .= "
echo 'Restore completed!'
";

file_put_contents("$backupDir/restore.sh", $restoreScript);
chmod("$backupDir/restore.sh", 0755);

// Create backup summary
$summary = "INSURANCE SYSTEM BACKUP SUMMARY\n";
$summary .= "================================\n";
$summary .= "Created: " . date('Y-m-d H:i:s') . "\n";
$summary .= "Backup Directory: $backupDir\n\n";

foreach ($backupResults as $type => $result) {
    $summary .= "$type: $result\n";
}

$summary .= "\nFILES CREATED:\n";
$summary .= "- complete_database_backup.sql (Full database)\n";
$summary .= "- policy_table_backup.csv (Policy table only)\n";
$summary .= "- *_backup.zip (Document archives)\n";
$summary .= "- config_backup/ (Configuration files)\n";
$summary .= "- restore.sh (Restore script)\n";
$summary .= "- backup_summary.txt (This file)\n";

file_put_contents("$backupDir/backup_summary.txt", $summary);

echo "<div class='success'>";
echo "<h3>🎉 Backup Completed Successfully!</h3>";
echo "<h4>📁 Backup Location: <code>$backupDir</code></h4>";
echo "<h4>📋 Summary:</h4>";
echo "<ul>";
foreach ($backupResults as $type => $result) {
    echo "<li>$result</li>";
}
echo "</ul>";
echo "</div>";

echo "<div class='info'>";
echo "<h3>📌 Next Steps:</h3>";
echo "<ol>";
echo "<li><strong>Verify backup:</strong> Check the backup directory for all files</li>";
echo "<li><strong>Download backup:</strong> Download the entire backup folder to your local computer</li>";
echo "<li><strong>Test restore:</strong> You can use restore.sh to restore if needed</li>";
echo "<li><strong>Proceed with migration:</strong> Now it's safe to run the migration</li>";
echo "</ol>";
echo "</div>";

echo "<p style='margin-top:30px;'>";
echo "<a href='quick-migrate-old-policies.php' style='background:#28a745; color:white; padding:15px 30px; text-decoration:none; border-radius:8px; font-size:16px; margin-right:10px;'>✅ Proceed to Migration</a>";
echo "<a href='policies.php' style='background:#6c757d; color:white; padding:15px 30px; text-decoration:none; border-radius:8px; font-size:16px;'>← Back to Policies</a>";
echo "</p>";

if (isset($con)) {
    $con->close();
}

echo "</body></html>";
?>
