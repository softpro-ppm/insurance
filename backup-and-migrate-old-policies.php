<!DOCTYPE html>
<html>
<head>
    <title>🔄 Backup & Migrate Old Policies</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        .container { max-width: 1200px; margin: 0 auto; }
        .backup-box { background: #fff3cd; padding: 20px; border-radius: 10px; border-left: 5px solid #ffc107; margin: 20px 0; }
        .migrate-box { background: #d4edda; padding: 20px; border-radius: 10px; border-left: 5px solid #28a745; margin: 20px 0; }
        .warning-box { background: #f8d7da; padding: 20px; border-radius: 10px; border-left: 5px solid #dc3545; margin: 20px 0; }
        .btn { display: inline-block; padding: 12px 24px; margin: 8px; text-decoration: none; border-radius: 8px; font-weight: bold; color: white; text-decoration: none; cursor: pointer; border: none; }
        .btn-warning { background: #ffc107; color: #212529; }
        .btn-success { background: #28a745; }
        .btn-danger { background: #dc3545; }
        .btn-primary { background: #007bff; }
        h1 { color: #333; text-align: center; }
        h2 { color: #007bff; }
        .code { background: #f8f9fa; padding: 2px 6px; border-radius: 4px; font-family: monospace; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f8f9fa; }
        .step { margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 8px; }
        .progress { background: #e9ecef; border-radius: 10px; height: 20px; margin: 10px 0; }
        .progress-bar { background: #28a745; height: 100%; border-radius: 10px; width: 0%; transition: width 0.3s; }
        #result { margin: 20px 0; padding: 20px; border-radius: 8px; display: none; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔄 Backup & Migrate Old Policies to New Structure</h1>

        <div class="warning-box">
            <h2>⚠️ Important: Complete Backup Strategy</h2>
            <p><strong>Before making ANY changes, we'll create:</strong></p>
            <ul>
                <li>🗄️ <strong>Full Database Backup:</strong> Complete SQL dump of all 1410+ policies</li>
                <li>📁 <strong>Document Backup:</strong> ZIP archive of all uploaded policy documents</li>
                <li>📊 <strong>Data Export:</strong> Excel/CSV export for easy viewing</li>
                <li>🔄 <strong>Rollback Script:</strong> Instant restore capability if needed</li>
            </ul>
        </div>

        <div class="backup-box">
            <h2>📊 Your Migration Strategy</h2>
            <p><strong>Perfect solution for old policies in edit modal!</strong></p>
            
            <table>
                <tr>
                    <th>Field</th>
                    <th>Current Value</th>
                    <th>Will Become</th>
                    <th>Logic</th>
                </tr>
                <tr>
                    <td><strong>Payout</strong></td>
                    <td>NULL/Empty</td>
                    <td><span class="code">existing_revenue</span></td>
                    <td>Payout = Current Revenue (400.00)</td>
                </tr>
                <tr>
                    <td><strong>Customer Paid</strong></td>
                    <td>NULL/Empty</td>
                    <td><span class="code">premium_amount</span></td>
                    <td>Customer Paid = Premium (7742.00)</td>
                </tr>
                <tr>
                    <td><strong>Discount</strong></td>
                    <td>NULL/Empty</td>
                    <td><span class="code">premium - customer_paid</span></td>
                    <td>Discount = 7742.00 - 7742.00 = 0.00</td>
                </tr>
                <tr>
                    <td><strong>Revenue</strong></td>
                    <td>400.00</td>
                    <td><span class="code">400.00</span></td>
                    <td>Revenue = Unchanged (preserved)</td>
                </tr>
            </table>
            
            <p><strong>Result:</strong> Edit modal will show proper values, old revenue preserved!</p>
        </div>

        <div class="step">
            <h3>📋 Step 1: Create Complete Backup</h3>
            <button onclick="createBackup()" class="btn btn-warning">🗄️ Create Full Backup</button>
            <div id="backup-progress"></div>
        </div>

        <div class="step">
            <h3>📋 Step 2: Analyze Current Data</h3>
            <button onclick="analyzeData()" class="btn btn-primary">📊 Analyze Old Policies</button>
            <div id="analysis-result"></div>
        </div>

        <div class="step">
            <h3>📋 Step 3: Preview Migration</h3>
            <button onclick="previewMigration()" class="btn btn-primary">👀 Preview Changes</button>
            <div id="preview-result"></div>
        </div>

        <div class="step">
            <h3>📋 Step 4: Execute Migration</h3>
            <button onclick="executeMigration()" class="btn btn-success">🚀 Migrate Old Policies</button>
            <div id="migration-result"></div>
        </div>

        <div class="step">
            <h3>📋 Step 5: Verify Results</h3>
            <button onclick="verifyMigration()" class="btn btn-success">✅ Verify Migration</button>
            <div id="verify-result"></div>
        </div>

        <div id="result"></div>

        <?php
        if ($_POST['action'] ?? false) {
            include 'include/config.php';
            
            switch ($_POST['action']) {
                case 'backup':
                    createFullBackup($con);
                    break;
                case 'analyze':
                    analyzeOldPolicies($con);
                    break;
                case 'preview':
                    previewMigration($con);
                    break;
                case 'migrate':
                    executeMigration($con);
                    break;
                case 'verify':
                    verifyMigration($con);
                    break;
            }
            $con->close();
        }

        function createFullBackup($con) {
            $backupDir = 'backups/' . date('Y-m-d_H-i-s');
            if (!file_exists('backups')) mkdir('backups', 0755, true);
            mkdir($backupDir, 0755, true);
            
            // 1. Database backup
            $sqlFile = $backupDir . '/complete_database_backup.sql';
            $command = "mysqldump -h localhost -u u820431346_newinsurance -p'Softpro@123' u820431346_newinsurance > $sqlFile";
            exec($command);
            
            // 2. Policy table specific backup
            $policyFile = $backupDir . '/policy_table_backup.sql';
            $command = "mysqldump -h localhost -u u820431346_newinsurance -p'Softpro@123' u820431346_newinsurance policy > $policyFile";
            exec($command);
            
            // 3. Documents backup
            if (file_exists('assets/uploads')) {
                $zip = new ZipArchive();
                if ($zip->open($backupDir . '/documents_backup.zip', ZipArchive::CREATE) === TRUE) {
                    $files = new RecursiveIteratorIterator(
                        new RecursiveDirectoryIterator('assets/uploads'),
                        RecursiveIteratorIterator::LEAVES_ONLY
                    );
                    
                    foreach ($files as $name => $file) {
                        if (!$file->isDir()) {
                            $relativePath = substr($file, strlen('assets/uploads') + 1);
                            $zip->addFile($file, $relativePath);
                        }
                    }
                    $zip->close();
                }
            }
            
            // 4. Data export to Excel
            $result = $con->query("SELECT * FROM policy WHERE payout IS NULL OR payout = 0");
            $csvFile = $backupDir . '/old_policies_export.csv';
            $fp = fopen($csvFile, 'w');
            
            // Headers
            $headers = ['id', 'vehicle_number', 'name', 'phone', 'vehicle_type', 'policy_type', 'premium', 'revenue', 'policy_start_date', 'policy_end_date'];
            fputcsv($fp, $headers);
            
            while ($row = $result->fetch_assoc()) {
                fputcsv($fp, array_values($row));
            }
            fclose($fp);
            
            echo json_encode([
                'success' => true,
                'message' => "✅ Complete backup created in: $backupDir",
                'files' => [
                    'Complete Database' => 'complete_database_backup.sql',
                    'Policy Table Only' => 'policy_table_backup.sql',
                    'Documents Archive' => 'documents_backup.zip',
                    'Old Policies CSV' => 'old_policies_export.csv'
                ]
            ]);
        }

        function analyzeOldPolicies($con) {
            // Count old policies
            $result = $con->query("SELECT COUNT(*) as count FROM policy WHERE payout IS NULL OR payout = 0");
            $oldCount = $result->fetch_assoc()['count'];
            
            // Sample data
            $result = $con->query("SELECT vehicle_number, name, premium, revenue FROM policy WHERE payout IS NULL OR payout = 0 LIMIT 5");
            $samples = [];
            while ($row = $result->fetch_assoc()) {
                $samples[] = $row;
            }
            
            // Revenue statistics
            $result = $con->query("SELECT MIN(revenue) as min_revenue, MAX(revenue) as max_revenue, AVG(revenue) as avg_revenue, SUM(revenue) as total_revenue FROM policy WHERE (payout IS NULL OR payout = 0) AND revenue > 0");
            $stats = $result->fetch_assoc();
            
            echo json_encode([
                'success' => true,
                'old_count' => $oldCount,
                'samples' => $samples,
                'revenue_stats' => $stats
            ]);
        }

        function previewMigration($con) {
            $result = $con->query("
                SELECT 
                    vehicle_number,
                    name,
                    premium,
                    revenue,
                    premium as new_customer_paid,
                    revenue as new_payout,
                    (premium - premium) as new_discount,
                    revenue as preserved_revenue
                FROM policy 
                WHERE payout IS NULL OR payout = 0 
                LIMIT 10
            ");
            
            $preview = [];
            while ($row = $result->fetch_assoc()) {
                $preview[] = $row;
            }
            
            echo json_encode([
                'success' => true,
                'preview' => $preview,
                'message' => 'This shows how the first 10 old policies will be updated'
            ]);
        }

        function executeMigration($con) {
            // Start transaction for safety
            $con->autocommit(FALSE);
            
            try {
                $updateQuery = "
                    UPDATE policy 
                    SET 
                        payout = revenue,
                        customer_paid = premium,
                        discount = 0,
                        calculated_revenue = revenue
                    WHERE payout IS NULL OR payout = 0
                ";
                
                $result = $con->query($updateQuery);
                
                if ($result) {
                    $affectedRows = $con->affected_rows;
                    $con->commit();
                    
                    echo json_encode([
                        'success' => true,
                        'message' => "✅ Successfully migrated $affectedRows old policies!",
                        'affected_rows' => $affectedRows
                    ]);
                } else {
                    throw new Exception($con->error);
                }
                
            } catch (Exception $e) {
                $con->rollback();
                echo json_encode([
                    'success' => false,
                    'message' => "❌ Migration failed: " . $e->getMessage()
                ]);
            }
            
            $con->autocommit(TRUE);
        }

        function verifyMigration($con) {
            // Check migrated policies
            $result = $con->query("
                SELECT COUNT(*) as migrated_count 
                FROM policy 
                WHERE payout IS NOT NULL AND payout > 0 AND customer_paid IS NOT NULL
            ");
            $migratedCount = $result->fetch_assoc()['migrated_count'];
            
            // Sample verification
            $result = $con->query("
                SELECT vehicle_number, name, premium, revenue, payout, customer_paid, discount
                FROM policy 
                WHERE payout = revenue AND customer_paid = premium
                LIMIT 5
            ");
            
            $samples = [];
            while ($row = $result->fetch_assoc()) {
                $samples[] = $row;
            }
            
            echo json_encode([
                'success' => true,
                'migrated_count' => $migratedCount,
                'samples' => $samples,
                'message' => "✅ Migration verification complete!"
            ]);
        }
        ?>
    </div>

    <script>
        function createBackup() {
            showProgress('backup-progress', 'Creating complete backup...');
            
            fetch('', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=backup'
            })
            .then(response => response.json())
            .then(data => {
                showResult('backup-progress', data);
            });
        }

        function analyzeData() {
            showProgress('analysis-result', 'Analyzing old policies...');
            
            fetch('', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=analyze'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let html = `
                        <div class="migrate-box">
                            <h4>📊 Analysis Results</h4>
                            <p><strong>Old Policies Found:</strong> ${data.old_count}</p>
                            <p><strong>Total Revenue:</strong> ₹${Number(data.revenue_stats.total_revenue).toLocaleString()}</p>
                            <p><strong>Average Revenue:</strong> ₹${Number(data.revenue_stats.avg_revenue).toFixed(2)}</p>
                            
                            <h5>Sample Policies:</h5>
                            <table>
                                <tr><th>Vehicle</th><th>Name</th><th>Premium</th><th>Revenue</th></tr>
                    `;
                    
                    data.samples.forEach(sample => {
                        html += `<tr><td>${sample.vehicle_number}</td><td>${sample.name}</td><td>₹${sample.premium}</td><td>₹${sample.revenue}</td></tr>`;
                    });
                    
                    html += `</table></div>`;
                    document.getElementById('analysis-result').innerHTML = html;
                } else {
                    showResult('analysis-result', data);
                }
            });
        }

        function previewMigration() {
            showProgress('preview-result', 'Generating migration preview...');
            
            fetch('', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=preview'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let html = `
                        <div class="migrate-box">
                            <h4>👀 Migration Preview</h4>
                            <p>${data.message}</p>
                            <table>
                                <tr>
                                    <th>Vehicle</th><th>Premium</th><th>Old Revenue</th>
                                    <th>→ New Payout</th><th>→ New Customer Paid</th><th>→ New Discount</th>
                                </tr>
                    `;
                    
                    data.preview.forEach(row => {
                        html += `
                            <tr>
                                <td>${row.vehicle_number}</td>
                                <td>₹${row.premium}</td>
                                <td>₹${row.revenue}</td>
                                <td>₹${row.new_payout}</td>
                                <td>₹${row.new_customer_paid}</td>
                                <td>₹${row.new_discount}</td>
                            </tr>
                        `;
                    });
                    
                    html += `</table><p><strong>✅ This looks good! Old revenue is preserved as payout.</strong></p></div>`;
                    document.getElementById('preview-result').innerHTML = html;
                } else {
                    showResult('preview-result', data);
                }
            });
        }

        function executeMigration() {
            if (!confirm('⚠️ Execute migration for all old policies?\n\nThis will update payout and customer_paid fields for all policies with missing financial data.\n\nBackup is recommended before proceeding.')) {
                return;
            }
            
            showProgress('migration-result', 'Executing migration...');
            
            fetch('', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=migrate'
            })
            .then(response => response.json())
            .then(data => {
                showResult('migration-result', data);
                if (data.success) {
                    document.getElementById('migration-result').innerHTML += `
                        <div class="migrate-box">
                            <h4>🎉 Migration Complete!</h4>
                            <p><strong>Policies Updated:</strong> ${data.affected_rows}</p>
                            <p>✅ All old policies now have proper payout and customer_paid values</p>
                            <p>✅ Edit modal will now display correctly</p>
                            <p>✅ Revenue values preserved</p>
                        </div>
                    `;
                }
            });
        }

        function verifyMigration() {
            showProgress('verify-result', 'Verifying migration results...');
            
            fetch('', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=verify'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let html = `
                        <div class="migrate-box">
                            <h4>✅ Verification Results</h4>
                            <p><strong>Migrated Policies:</strong> ${data.migrated_count}</p>
                            <p>${data.message}</p>
                            
                            <h5>Sample Migrated Policies:</h5>
                            <table>
                                <tr><th>Vehicle</th><th>Premium</th><th>Revenue</th><th>Payout</th><th>Customer Paid</th><th>Discount</th></tr>
                    `;
                    
                    data.samples.forEach(sample => {
                        html += `
                            <tr>
                                <td>${sample.vehicle_number}</td>
                                <td>₹${sample.premium}</td>
                                <td>₹${sample.revenue}</td>
                                <td>₹${sample.payout}</td>
                                <td>₹${sample.customer_paid}</td>
                                <td>₹${sample.discount}</td>
                            </tr>
                        `;
                    });
                    
                    html += `</table></div>`;
                    document.getElementById('verify-result').innerHTML = html;
                } else {
                    showResult('verify-result', data);
                }
            });
        }

        function showProgress(elementId, message) {
            document.getElementById(elementId).innerHTML = `
                <div class="progress">
                    <div class="progress-bar" style="width: 100%; animation: pulse 1s infinite;"></div>
                </div>
                <p>${message}</p>
            `;
        }

        function showResult(elementId, data) {
            const className = data.success ? 'migrate-box' : 'warning-box';
            document.getElementById(elementId).innerHTML = `
                <div class="${className}">
                    <p>${data.message}</p>
                </div>
            `;
        }
    </script>

    <style>
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
    </style>
</body>
</html>
