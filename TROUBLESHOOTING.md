# Policy Edit Modal Troubleshooting Guide

## Quick Diagnosis Steps

### Step 1: Open the Diagnosis Page
1. Navigate to: `your-domain.com/insurance/debug-api.html`
2. Click "Test API Connectivity"
3. Check the results

### Step 2: Check Browser Console
1. Open your browser's Developer Tools (F12)
2. Go to the Console tab
3. Try to edit a policy from `home.php`
4. Look for any error messages

### Step 3: Check Server Logs
1. Check your PHP error logs
2. Look for any errors related to `policy-operations.php`

## Common Issues and Solutions

### Issue 1: 404 Not Found
**Symptoms:** API returns 404 error
**Solution:** 
- Check if `include/policy-operations.php` exists
- Verify file permissions (should be readable by web server)
- Make sure the path is correct relative to your web root

### Issue 2: 500 Server Error
**Symptoms:** API returns 500 error
**Solutions:**
- Check PHP error logs for specific error
- Verify database connection in `include/config.php`
- Ensure all required PHP extensions are installed
- Check file permissions

### Issue 3: Empty or Non-JSON Response
**Symptoms:** Server returns HTML instead of JSON
**Solutions:**
- Check if session is properly started
- Verify `include/session.php` exists and works
- Make sure there are no PHP errors before JSON output
- Check if user is logged in

### Issue 4: Network/CORS Errors
**Symptoms:** Network error in browser console
**Solutions:**
- Check if files are on the same domain
- Verify web server is running
- Check firewall settings
- Test with the diagnostic page

## Enhanced Debug Information

The updated files now include:

1. **Multiple URL Testing**: The JavaScript tries different path patterns
2. **Detailed Console Logging**: Shows exactly what's happening
3. **Connection Test Endpoint**: `test_connection` action for basic connectivity
4. **Better Error Messages**: More specific error descriptions

## File Locations

- **Main Edit Script**: `assets/js/edit-policy-modal.js`
- **API Endpoint**: `include/policy-operations.php`
- **Diagnostic Page**: `debug-api.html`
- **Test Page**: `test-policy-api.php`

## Testing Workflow

1. First, test with `debug-api.html` to verify basic connectivity
2. Check browser console on `home.php` when clicking edit
3. Look at the detailed logs to see which URL pattern works
4. If all URLs fail, check server-side issues

## What the Enhanced Code Does

The new `loadPolicyData` function:
- Tests multiple URL patterns automatically
- Provides detailed console logging
- Shows which URLs work and which don't
- Gives specific error messages for different failure types
- Includes proper error handling for network issues

This should help identify exactly where the connection is failing!
