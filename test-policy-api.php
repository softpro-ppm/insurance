<?php
// Simple test file to check if policy API is working
?>
<!DOCTYPE html>
<html>
<head>
    <title>Policy API Test</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Policy API Test</h1>
    <button onclick="testGetPolicy()">Test Get Policy (ID: 1)</button>
    <button onclick="testInvalidPolicy()">Test Invalid Policy ID</button>
    <button onclick="testNoAction()">Test No Action</button>
    
    <div id="results" style="margin-top: 20px; padding: 10px; border: 1px solid #ccc; background: #f9f9f9;">
        <h3>Results:</h3>
        <pre id="output"></pre>
    </div>

    <script>
        function logResult(test, result) {
            const output = document.getElementById('output');
            output.innerHTML += `\n=== ${test} ===\n${JSON.stringify(result, null, 2)}\n`;
        }

        function testGetPolicy() {
            $.ajax({
                url: 'include/policy-operations.php',
                method: 'POST',
                data: {
                    action: 'get_policy_data',
                    policy_id: 1
                },
                dataType: 'json',
                success: function(response) {
                    logResult('Get Policy ID 1', response);
                },
                error: function(xhr, status, error) {
                    logResult('Get Policy ID 1 - ERROR', {
                        status: status,
                        error: error,
                        responseText: xhr.responseText,
                        statusCode: xhr.status
                    });
                }
            });
        }

        function testInvalidPolicy() {
            $.ajax({
                url: 'include/policy-operations.php',
                method: 'POST',
                data: {
                    action: 'get_policy_data',
                    policy_id: 99999
                },
                dataType: 'json',
                success: function(response) {
                    logResult('Get Invalid Policy ID', response);
                },
                error: function(xhr, status, error) {
                    logResult('Get Invalid Policy ID - ERROR', {
                        status: status,
                        error: error,
                        responseText: xhr.responseText,
                        statusCode: xhr.status
                    });
                }
            });
        }

        function testNoAction() {
            $.ajax({
                url: 'include/policy-operations.php',
                method: 'POST',
                data: {},
                dataType: 'json',
                success: function(response) {
                    logResult('No Action Test', response);
                },
                error: function(xhr, status, error) {
                    logResult('No Action Test - ERROR', {
                        status: status,
                        error: error,
                        responseText: xhr.responseText,
                        statusCode: xhr.status
                    });
                }
            });
        }
    </script>
</body>
</html>
