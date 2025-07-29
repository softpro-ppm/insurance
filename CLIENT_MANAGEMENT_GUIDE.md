# Client Management System - Implementation Guide

## Overview
This comprehensive client management system provides Bootstrap 5 standard modals, DataTables integration, file management, and full CRUD operations.

## Features Implemented

### ✅ Bootstrap 5 Standard Modal Structure
- **Add/Edit Modal**: Uses proper Bootstrap 5 modal markup with `modal`, `modal-dialog`, `modal-content`, `modal-header`, `modal-body`, `modal-footer`
- **Delete Confirmation Modal**: Standard confirmation dialog with warning styling
- **View Documents Modal**: Document preview with proper image handling

### ✅ DataTables Integration
- **Pagination**: 10, 30, 50, 100, All entries per page
- **Search**: Global search across all columns
- **Serial Numbers**: Global serial numbers that persist across pages (1, 2, 3... not restarting on each page)
- **Responsive Design**: Mobile-friendly table layout
- **Sorting**: Descending order by creation date (latest first)

### ✅ File Upload System
- **Aadhar Card Upload**: Image preview with validation
- **PAN Card Upload**: Image preview with validation
- **File Validation**: JPG, PNG, PDF (Max 5MB)
- **Security**: Proper file handling and storage

### ✅ Complete CRUD Operations
- **Create**: Add new clients with documents
- **Read**: View client data and documents
- **Update**: Edit existing clients and replace documents
- **Delete**: Remove clients and all associated files from server

## Files Created

### 1. Main Application
- `client-management.php` - Main client management interface
- `database_client_management.sql` - Database schema and sample data

### 2. API Endpoints
- `api/clients_data.php` - DataTables server-side processing
- `api/client_operations.php` - CRUD operations handler

### 3. File Structure
```
insurance/
├── client-management.php (Main interface)
├── api/
│   ├── clients_data.php (DataTables data source)
│   └── client_operations.php (CRUD operations)
├── uploads/
│   └── documents/
│       └── .htaccess (Security settings)
└── database_client_management.sql (Database schema)
```

## Database Schema

```sql
CREATE TABLE clients (
  id INT PRIMARY KEY AUTO_INCREMENT,
  client_name VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  phone VARCHAR(20) NOT NULL,
  address TEXT,
  dob DATE,
  policy_number VARCHAR(100) UNIQUE NOT NULL,
  policy_type VARCHAR(100) NOT NULL,
  premium_amount DECIMAL(10,2) NOT NULL,
  status ENUM('Active','Pending','Expired','Cancelled') DEFAULT 'Active',
  policy_start_date DATE,
  policy_end_date DATE,
  aadhar_card VARCHAR(255),
  pan_card VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## Key Features

### 1. Bootstrap 5 Modal Examples

**Add Client Modal:**
```html
<div class="modal fade" id="addClientModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Client</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <!-- Form content -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Save Client</button>
      </div>
    </div>
  </div>
</div>
```

### 2. DataTables Configuration
```javascript
$('#clientsTable').DataTable({
    "serverSide": true,
    "ajax": "api/clients_data.php",
    "pageLength": 10,
    "lengthMenu": [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],
    "order": [[9, "desc"]], // Latest first
    "columns": [
        { 
            "render": function(data, type, row, meta) {
                return meta.settings._iDisplayStart + meta.row + 1; // Global serial numbers
            }
        }
        // ... other columns
    ]
});
```

### 3. File Upload with Preview
```javascript
function setupFilePreview(inputId, previewId) {
    $(document).on('change', '#' + inputId, function(e) {
        const file = e.target.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#' + previewId + ' img').attr('src', e.target.result);
                $('#' + previewId).show();
            };
            reader.readAsDataURL(file);
        }
    });
}
```

### 4. Complete Delete with File Removal
```php
// Delete client and associated files
$deleteQuery = "DELETE FROM clients WHERE id = ?";
if ($stmt->execute()) {
    // Delete associated files from server
    if ($filesData['aadhar_card']) {
        unlink($uploadDir . $filesData['aadhar_card']);
    }
    if ($filesData['pan_card']) {
        unlink($uploadDir . $filesData['pan_card']);
    }
}
```

## Usage Instructions

### 1. Database Setup
1. Run the SQL script: `database_client_management.sql`
2. Ensure the uploads directory has write permissions

### 2. Access the System
1. Navigate to `client-management.php`
2. Use the "Add New Client" button to create clients
3. Upload documents and see live previews
4. Use table search, pagination, and sorting features

### 3. File Management
- Files are stored in `uploads/documents/` with unique names
- Supported formats: JPG, PNG, PDF (Max 5MB)
- Old files are automatically deleted when updated

### 4. Security Features
- File type validation
- SQL injection protection with prepared statements
- XSS protection with htmlspecialchars()
- Directory security with .htaccess

## Responsive Design
- Mobile-friendly modals
- Responsive table with Bootstrap 5
- Touch-friendly buttons and controls
- Proper viewport handling

## Browser Compatibility
- Bootstrap 5.3.3 (Latest stable)
- Modern browsers (Chrome, Firefox, Safari, Edge)
- Mobile browsers (iOS Safari, Android Chrome)

This implementation follows all Bootstrap 5 standards and provides a complete, production-ready client management system with advanced features and security measures.
