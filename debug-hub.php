<!DOCTYPE html>
<html>
<head>
    <title>🔧 Comprehensive Debugging Hub</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        .container { max-width: 1200px; margin: 0 auto; }
        .test-section { background: #f8f9fa; padding: 20px; border-radius: 10px; margin: 20px 0; }
        .btn { display: inline-block; padding: 12px 24px; margin: 8px; text-decoration: none; border-radius: 8px; font-weight: bold; color: white; }
        .btn-primary { background: #007bff; }
        .btn-success { background: #28a745; }
        .btn-warning { background: #ffc107; color: #212529; }
        .btn-danger { background: #dc3545; }
        .btn-info { background: #17a2b8; }
        h1 { color: #333; text-align: center; }
        h2 { color: #007bff; }
        .issue-box { background: #f8d7da; padding: 15px; border-radius: 8px; margin: 10px 0; border-left: 4px solid #dc3545; }
        .solution-box { background: #d4edda; padding: 15px; border-radius: 8px; margin: 10px 0; border-left: 4px solid #28a745; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 HTTP 500 Error - Comprehensive Debugging Hub</h1>

        <div class="issue-box">
            <h2>❌ Current Issues</h2>
            <ul>
                <li><strong>HTTP 500 Error:</strong> Both main and debug files failing</li>
                <li><strong>Form not submitting:</strong> Error occurs during form processing</li>
                <li><strong>Revenue calculation:</strong> Still showing wrong values</li>
                <li><strong>Unknown root cause:</strong> Could be session, database, or PHP syntax</li>
            </ul>
        </div>

        <div class="test-section">
            <h2>🧪 Step-by-Step Testing</h2>
            
            <h3>Step 1: System Health Check</h3>
            <p>Test basic system components (session, database connections, PHP):</p>
            <a href="include/system-test.php" class="btn btn-info">Run System Test</a>
            
            <h3>Step 2: Minimal Form Test</h3>
            <p>Test form submission without session or database (isolate HTTP 500 cause):</p>
            <a href="policies.php" class="btn btn-warning">Go to Policies → Submit Form</a>
            <p><small>Form will submit to minimal-test.php (no database operations)</small></p>
            
            <h3>Step 3: Fixed Debug Test</h3>
            <p>Test form with enhanced error handling and debugging:</p>
            <a href="policies.php" class="btn btn-primary">Test Fixed Debug Version</a>
            <p><small>Form will submit to add-policies-debug-fixed.php</small></p>
            
            <h3>Step 4: Account Integration Test</h3>
            <p>Test account database connectivity separately:</p>
            <a href="test-account.php" class="btn btn-success">Test Account Database</a>
        </div>

        <div class="solution-box">
            <h2>🎯 Debugging Strategy</h2>
            <ol>
                <li><strong>Run System Test:</strong> Check if basic PHP/database is working</li>
                <li><strong>Try Minimal Test:</strong> See if form submission works without complex logic</li>
                <li><strong>Check Browser Console:</strong> Look for JavaScript errors (F12)</li>
                <li><strong>Check Server Logs:</strong> Look for PHP error logs on server</li>
                <li><strong>Test Step by Step:</strong> Isolate which component is causing the error</li>
            </ol>
        </div>

        <div class="test-section">
            <h2>🔍 Quick Diagnostic Questions</h2>
            <ul>
                <li><strong>System Test Results:</strong> Does the system test pass all checks?</li>
                <li><strong>Session Status:</strong> Is the user properly logged in?</li>
                <li><strong>Database Connection:</strong> Can we connect to both databases?</li>
                <li><strong>Form Data:</strong> Is the form sending data correctly?</li>
                <li><strong>JavaScript Console:</strong> Are there any JavaScript errors?</li>
            </ul>
        </div>

        <div style="background: #fff3cd; padding: 20px; border-radius: 10px; text-align: center; margin: 30px 0;">
            <h2 style="color: #856404; margin: 0;">⚡ Emergency Bypass</h2>
            <p style="margin: 10px 0 0 0;">If all tests fail, we may need to check server-level PHP configuration or error logs.</p>
        </div>

        <div class="test-section">
            <h2>📋 Current Form Actions</h2>
            <ul>
                <li><strong>Current Form Action:</strong> include/add-policies-debug-fixed.php</li>
                <li><strong>Original Form Action:</strong> include/add-policies.php</li>
                <li><strong>Minimal Test Action:</strong> include/minimal-test.php</li>
            </ul>
            
            <h3>Switch Form Actions:</h3>
            <p><small>These will temporarily change what happens when you submit the form:</small></p>
            <a href="javascript:void(0)" onclick="alert('Form is currently set to: add-policies-debug-fixed.php')" class="btn btn-info">Current: Debug Fixed</a>
        </div>
    </div>

    <script>
        // Add some basic JavaScript debugging
        console.log('Debugging hub loaded successfully');
        
        // Check if there are any immediate JavaScript errors
        window.addEventListener('error', function(e) {
            console.error('JavaScript Error:', e.error);
        });
    </script>
</body>
</html>
