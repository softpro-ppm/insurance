/**
 * ============================================
 * USER MANAGEMENT JAVASCRIPT
 * Bootstrap 5 + AJAX + Modal System + Toaster Alerts
 * ============================================
 */

// Global variables for user management
let usersTable;
let selectedUserId = null;

// Document ready initialization
$(document).ready(function() {
    console.log('User Management JS initialized');
    
    // Initialize DataTables if table exists
    if ($('#datatable-buttons').length) {
        initializeUsersTable();
    }
    
    // Initialize modal handlers
    initializeUserModalHandlers();
    
    // Initialize form validation
    initializeUserFormValidation();
});

/**
 * ==========================================
 * DATATABLES INITIALIZATION
 * ==========================================
 */

function initializeUsersTable() {
    console.log('Initializing users DataTable...');
    
    // Destroy existing DataTable if it exists
    if ($.fn.DataTable.isDataTable('#datatable-buttons')) {
        $('#datatable-buttons').DataTable().destroy();
    }
    
    usersTable = $('#datatable-buttons').DataTable({
        "order": [],
        "pageLength": 25,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        "responsive": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "stateSave": true,
        "dom": 'Bfrtip',
        "buttons": [
            {
                extend: 'copy',
                className: 'btn btn-primary btn-sm',
                text: '<i class="fas fa-copy"></i> Copy'
            },
            {
                extend: 'csv',
                className: 'btn btn-success btn-sm',
                text: '<i class="fas fa-file-csv"></i> CSV'
            },
            {
                extend: 'excel',
                className: 'btn btn-success btn-sm',
                text: '<i class="fas fa-file-excel"></i> Excel',
                title: 'Users Management - ' + new Date().toLocaleDateString()
            },
            {
                extend: 'pdf',
                className: 'btn btn-danger btn-sm',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                title: 'Users Management - ' + new Date().toLocaleDateString(),
                orientation: 'landscape',
                pageSize: 'A4'
            },
            {
                extend: 'print',
                className: 'btn btn-info btn-sm',
                text: '<i class="fas fa-print"></i> Print',
                title: 'Users Management'
            },
            {
                extend: 'colvis',
                className: 'btn btn-secondary btn-sm',
                text: '<i class="fas fa-columns"></i> Columns'
            }
        ],
        "columnDefs": [
            {
                "targets": 0, // First column (S.NO.)
                "searchable": false,
                "orderable": false
            },
            {
                "targets": -1, // Last column (Actions)
                "searchable": false,
                "orderable": false
            }
        ],
        "language": {
            "search": "Search users:",
            "lengthMenu": "Show _MENU_ entries",
            "info": "Showing _START_ to _END_ of _TOTAL_ users",
            "infoEmpty": "No users found",
            "infoFiltered": "(filtered from _MAX_ total users)",
            "zeroRecords": "No matching users found",
            "emptyTable": "No users available"
        }
    });
}

/**
 * ==========================================
 * MODAL SYSTEM FUNCTIONS
 * ==========================================
 */

function initializeUserModalHandlers() {
    console.log('Initializing user modal handlers...');
    
    // Modal event handlers
    $('#addUserModal').on('shown.bs.modal', function() {
        console.log('Add User modal opened');
        resetAddUserForm();
        $('#add_user_name').focus();
    });
    
    $('#editUserModal').on('shown.bs.modal', function() {
        console.log('Edit User modal opened');
    });
    
    $('#deleteUserModal').on('shown.bs.modal', function() {
        console.log('Delete User modal opened');
    });
    
    $('#changeStatusModal').on('shown.bs.modal', function() {
        console.log('Change Status modal opened');
    });
    
    // Modal cleanup on hide
    $('.modal').on('hidden.bs.modal', function() {
        console.log('User modal closed - cleaning up');
        clearModalErrors();
        selectedUserId = null;
    });
}

// Add User Modal
function openAddUserModal() {
    console.log('Opening Add User modal...');
    
    const modal = new bootstrap.Modal(document.getElementById('addUserModal'), {
        backdrop: 'static',
        keyboard: false
    });
    
    resetAddUserForm();
    modal.show();
}

// Edit User Modal with data loading
function editUser(userId) {
    console.log('Opening Edit User modal for ID:', userId);
    
    if (!userId) {
        console.error('User ID is required');
        showToaster('Error: User ID is missing', 'error');
        return;
    }
    
    selectedUserId = userId;
    
    // Show loading state
    showLoadingOverlay('#editUserModal .modal-content');
    
    const modal = new bootstrap.Modal(document.getElementById('editUserModal'), {
        backdrop: 'static',
        keyboard: false
    });
    
    modal.show();
    
    // Load user data
    loadUserForEdit(userId);
}

// Delete User Confirmation Modal
function deleteUser(userId) {
    console.log('Opening Delete User confirmation for ID:', userId);
    
    if (!userId) {
        console.error('User ID is required');
        showToaster('Error: User ID is missing', 'error');
        return;
    }
    
    selectedUserId = userId;
    showDeleteUserConfirmation(userId);
}

// Change Status Modal
function changeUserStatus(userId, currentStatus) {
    console.log('Opening Change Status modal for ID:', userId, 'Current Status:', currentStatus);
    
    if (!userId) {
        console.error('User ID is required');
        showToaster('Error: User ID is missing', 'error');
        return;
    }
    
    selectedUserId = userId;
    showChangeStatusConfirmation(userId, currentStatus);
}

/**
 * ==========================================
 * DATA LOADING FUNCTIONS
 * ==========================================
 */

function loadUserForEdit(userId) {
    console.log('Loading user data for edit, ID:', userId);
    
    $.ajax({
        url: 'include/get-user-data.php',
        type: 'POST',
        data: { user_id: userId },
        dataType: 'json',
        beforeSend: function() {
            console.log('Sending AJAX request for user ID:', userId);
        },
        success: function(response) {
            console.log('AJAX Success - User data response:', response);
            hideLoadingOverlay('#editUserModal .modal-content');
            
            if (response && response.success) {
                console.log('User data loaded successfully:', response.data);
                populateEditUserForm(response.data);
            } else {
                console.error('Failed to load user data:', response);
                const errorMsg = response && response.message ? response.message : 'Unknown error';
                showToaster('Failed to load user data: ' + errorMsg, 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', { status, error, responseText: xhr.responseText });
            hideLoadingOverlay('#editUserModal .modal-content');
            showToaster('Error loading user data: ' + error, 'error');
        }
    });
}

function populateEditUserForm(data) {
    console.log('Populating edit user form with data:', data);
    
    try {
        // Set hidden user ID
        $('#edit_user_id').val(data.id);
        
        // User Information
        $('#edit_user_name').val(data.name);
        $('#edit_user_username').val(data.username);
        $('#edit_user_phone').val(data.phone);
        $('#edit_user_email').val(data.email);
        $('#edit_user_type').val(data.type);
        $('#edit_user_status').val(data.delete_flag);
        
        console.log('Edit user form populated successfully');
        
    } catch (error) {
        console.error('Error populating edit user form:', error);
        showToaster('Error displaying user data', 'error');
    }
}

function showDeleteUserConfirmation(userId) {
    console.log('Showing delete user confirmation for ID:', userId);
    
    // Store user ID for deletion
    $('#confirmDeleteUserBtn').data('user-id', userId);
    
    // Load user info for confirmation display
    $.ajax({
        url: 'include/get-user-data.php',
        type: 'POST',
        data: { user_id: userId },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const data = response.data;
                const userType = data.type == '1' ? 'Admin' : 'Employee';
                const userStatus = data.delete_flag == '1' ? 'Active' : 'Inactive';
                
                // Populate user info in delete modal
                $('#deleteUserInfo').html(`
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Name:</strong> ${data.name}<br>
                            <strong>Username:</strong> ${data.username}<br>
                            <strong>Phone:</strong> ${data.phone}
                        </div>
                        <div class="col-md-6">
                            <strong>Email:</strong> ${data.email || 'Not provided'}<br>
                            <strong>Type:</strong> ${userType}<br>
                            <strong>Status:</strong> ${userStatus}
                        </div>
                    </div>
                `);
                
                // Show the modal
                const modal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
                modal.show();
                
            } else {
                console.error('Failed to load user for delete confirmation:', response.message);
                showToaster('Error loading user information', 'error');
            }
        },
        error: function() {
            console.error('AJAX error loading user for delete');
            showToaster('Error loading user information', 'error');
        }
    });
}

function showChangeStatusConfirmation(userId, currentStatus) {
    console.log('Showing change status confirmation for ID:', userId);
    
    // Store user ID and status for change
    $('#confirmChangeStatusBtn').data('user-id', userId);
    $('#confirmChangeStatusBtn').data('current-status', currentStatus);
    
    // Load user info for confirmation display
    $.ajax({
        url: 'include/get-user-data.php',
        type: 'POST',
        data: { user_id: userId },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const data = response.data;
                const currentStatusText = currentStatus == '1' ? 'Active' : 'Inactive';
                const newStatusText = currentStatus == '1' ? 'Inactive' : 'Active';
                
                // Populate user info in change status modal
                $('#changeStatusInfo').html(`
                    <div class="row">
                        <div class="col-md-12">
                            <strong>User:</strong> ${data.name} (${data.username})<br>
                            <strong>Current Status:</strong> <span class="badge bg-${currentStatus == '1' ? 'success' : 'danger'}">${currentStatusText}</span><br>
                            <strong>New Status:</strong> <span class="badge bg-${currentStatus == '1' ? 'danger' : 'success'}">${newStatusText}</span>
                        </div>
                    </div>
                `);
                
                // Show the modal
                const modal = new bootstrap.Modal(document.getElementById('changeStatusModal'));
                modal.show();
                
            } else {
                console.error('Failed to load user for status change:', response.message);
                showToaster('Error loading user information', 'error');
            }
        },
        error: function() {
            console.error('AJAX error loading user for status change');
            showToaster('Error loading user information', 'error');
        }
    });
}

/**
 * ==========================================
 * FORM VALIDATION & SUBMISSION
 * ==========================================
 */

function initializeUserFormValidation() {
    console.log('Initializing user form validation...');
    
    // Add User Form
    $('#addUserForm').on('submit', function(e) {
        e.preventDefault();
        console.log('Add user form submitted');
        
        if (validateAddUserForm()) {
            submitAddUserForm();
        }
    });
    
    // Edit User Form
    $('#editUserForm').on('submit', function(e) {
        e.preventDefault();
        console.log('Edit user form submitted');
        
        if (validateEditUserForm()) {
            submitEditUserForm();
        }
    });
    
    // Delete Confirmation
    $('#confirmDeleteUserBtn').on('click', function() {
        const userId = $(this).data('user-id');
        console.log('Delete confirmed for user ID:', userId);
        
        if (userId) {
            confirmDeleteUser(userId);
        }
    });
    
    // Change Status Confirmation
    $('#confirmChangeStatusBtn').on('click', function() {
        const userId = $(this).data('user-id');
        const currentStatus = $(this).data('current-status');
        console.log('Status change confirmed for user ID:', userId);
        
        if (userId) {
            confirmChangeUserStatus(userId, currentStatus);
        }
    });
}

function validateAddUserForm() {
    console.log('Validating add user form...');
    
    let isValid = true;
    const requiredFields = [
        'add_user_name',
        'add_user_username',
        'add_user_phone',
        'add_user_password',
        'add_user_type'
    ];
    
    // Clear previous errors
    clearFormErrors('#addUserForm');
    
    // Validate required fields
    requiredFields.forEach(fieldId => {
        const $field = $(`#${fieldId}`);
        if (!$field.val().trim()) {
            showFieldError(fieldId, 'This field is required');
            isValid = false;
        }
    });
    
    // Validate phone number
    const phone = $('#add_user_phone').val().trim();
    if (phone && !/^[6-9]\d{9}$/.test(phone)) {
        showFieldError('add_user_phone', 'Please enter a valid 10-digit mobile number');
        isValid = false;
    }
    
    // Validate email if provided
    const email = $('#add_user_email').val().trim();
    if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showFieldError('add_user_email', 'Please enter a valid email address');
        isValid = false;
    }
    
    // Validate password strength
    const password = $('#add_user_password').val().trim();
    if (password && password.length < 6) {
        showFieldError('add_user_password', 'Password must be at least 6 characters long');
        isValid = false;
    }
    
    console.log('Add user form validation result:', isValid);
    return isValid;
}

function validateEditUserForm() {
    console.log('Validating edit user form...');
    
    let isValid = true;
    const requiredFields = [
        'edit_user_name',
        'edit_user_username',
        'edit_user_phone',
        'edit_user_type'
    ];
    
    // Clear previous errors
    clearFormErrors('#editUserForm');
    
    // Validate required fields
    requiredFields.forEach(fieldId => {
        const $field = $(`#${fieldId}`);
        if (!$field.val().trim()) {
            showFieldError(fieldId, 'This field is required');
            isValid = false;
        }
    });
    
    // Validate phone number
    const phone = $('#edit_user_phone').val().trim();
    if (phone && !/^[6-9]\d{9}$/.test(phone)) {
        showFieldError('edit_user_phone', 'Please enter a valid 10-digit mobile number');
        isValid = false;
    }
    
    // Validate email if provided
    const email = $('#edit_user_email').val().trim();
    if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showFieldError('edit_user_email', 'Please enter a valid email address');
        isValid = false;
    }
    
    // Validate password if provided
    const password = $('#edit_user_password').val().trim();
    if (password && password.length < 6) {
        showFieldError('edit_user_password', 'Password must be at least 6 characters long');
        isValid = false;
    }
    
    console.log('Edit user form validation result:', isValid);
    return isValid;
}

function submitAddUserForm() {
    console.log('Submitting add user form...');
    
    // Show loading state
    showLoadingOverlay('#addUserModal .modal-content');
    
    // Prepare form data
    const formData = new FormData($('#addUserForm')[0]);
    
    // Submit form
    $.ajax({
        url: 'include/add-user-handler.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            console.log('Add user form submitted successfully:', response);
            hideLoadingOverlay('#addUserModal .modal-content');
            
            if (response.success) {
                showToaster('User added successfully!', 'success');
                
                // Close modal
                bootstrap.Modal.getInstance(document.getElementById('addUserModal')).hide();
                
                // Refresh page
                setTimeout(() => location.reload(), 1500);
            } else {
                showToaster('Error: ' + response.message, 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('Add user form submission error:', { xhr, status, error });
            hideLoadingOverlay('#addUserModal .modal-content');
            showToaster('Error submitting form. Please try again.', 'error');
        }
    });
}

function submitEditUserForm() {
    console.log('Submitting edit user form...');
    
    // Show loading state
    showLoadingOverlay('#editUserModal .modal-content');
    
    // Prepare form data
    const formData = new FormData($('#editUserForm')[0]);
    
    // Submit form
    $.ajax({
        url: 'include/edit-user-handler.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            console.log('Edit user form submitted successfully:', response);
            hideLoadingOverlay('#editUserModal .modal-content');
            
            if (response.success) {
                showToaster('User updated successfully!', 'success');
                
                // Close modal
                bootstrap.Modal.getInstance(document.getElementById('editUserModal')).hide();
                
                // Refresh page
                setTimeout(() => location.reload(), 1500);
            } else {
                showToaster('Error: ' + response.message, 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('Edit user form submission error:', { xhr, status, error });
            hideLoadingOverlay('#editUserModal .modal-content');
            showToaster('Error updating user. Please try again.', 'error');
        }
    });
}

function confirmDeleteUser(userId) {
    console.log('Confirming delete for user ID:', userId);
    
    // Show loading state
    showLoadingOverlay('#deleteUserModal .modal-content');
    
    // Submit delete request
    $.ajax({
        url: 'include/delete-user-handler.php',
        type: 'POST',
        data: { id: userId },
        dataType: 'json',
        success: function(response) {
            console.log('Delete request completed:', response);
            hideLoadingOverlay('#deleteUserModal .modal-content');
            
            if (response.success) {
                showToaster('User deleted successfully!', 'success');
                
                // Close modal
                bootstrap.Modal.getInstance(document.getElementById('deleteUserModal')).hide();
                
                // Refresh page
                setTimeout(() => location.reload(), 1500);
            } else {
                showToaster('Error: ' + response.message, 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('Delete request error:', { xhr, status, error });
            hideLoadingOverlay('#deleteUserModal .modal-content');
            showToaster('Error deleting user. Please try again.', 'error');
        }
    });
}

function confirmChangeUserStatus(userId, currentStatus) {
    console.log('Confirming status change for user ID:', userId);
    
    // Show loading state
    showLoadingOverlay('#changeStatusModal .modal-content');
    
    // Submit status change request
    $.ajax({
        url: 'include/update-user-status-handler.php',
        type: 'POST',
        data: { id: userId, status: currentStatus },
        dataType: 'json',
        success: function(response) {
            console.log('Status change request completed:', response);
            hideLoadingOverlay('#changeStatusModal .modal-content');
            
            if (response.success) {
                const newStatus = currentStatus == '1' ? 'deactivated' : 'activated';
                showToaster(`User ${newStatus} successfully!`, 'success');
                
                // Close modal
                bootstrap.Modal.getInstance(document.getElementById('changeStatusModal')).hide();
                
                // Refresh page
                setTimeout(() => location.reload(), 1500);
            } else {
                showToaster('Error: ' + response.message, 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('Status change request error:', { xhr, status, error });
            hideLoadingOverlay('#changeStatusModal .modal-content');
            showToaster('Error changing user status. Please try again.', 'error');
        }
    });
}

/**
 * ==========================================
 * UTILITY FUNCTIONS
 * ==========================================
 */

function resetAddUserForm() {
    console.log('Resetting add user form...');
    
    // Reset form fields
    $('#addUserForm')[0].reset();
    
    // Clear form errors
    clearFormErrors('#addUserForm');
}

function clearFormErrors(formSelector) {
    $(formSelector + ' .is-invalid').removeClass('is-invalid');
    $(formSelector + ' .invalid-feedback').remove();
}

function clearModalErrors() {
    $('.modal .is-invalid').removeClass('is-invalid');
    $('.modal .invalid-feedback').remove();
}

function showFieldError(fieldId, message) {
    const $field = $(`#${fieldId}`);
    $field.addClass('is-invalid');
    
    const errorHtml = `<div class="invalid-feedback">${message}</div>`;
    $field.after(errorHtml);
}

function showLoadingOverlay(selector) {
    const loadingHtml = `
        <div class="loading-overlay">
            <div class="loading-spinner">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    `;
    $(selector).css('position', 'relative').append(loadingHtml);
}

function hideLoadingOverlay(selector) {
    $(selector + ' .loading-overlay').remove();
}

function showToaster(message, type = 'info') {
    console.log('Showing toaster:', { message, type });
    
    // Remove existing toasts
    $('.toast').remove();
    
    // Map type to Bootstrap classes
    const typeClasses = {
        'success': 'bg-success text-white',
        'error': 'bg-danger text-white',
        'warning': 'bg-warning text-dark',
        'info': 'bg-info text-white'
    };
    
    const typeIcons = {
        'success': 'bx-check-circle',
        'error': 'bx-error',
        'warning': 'bx-error-circle',
        'info': 'bx-info-circle'
    };
    
    const bgClass = typeClasses[type] || typeClasses.info;
    const icon = typeIcons[type] || typeIcons.info;
    
    // Create toast element
    const toastHtml = `
        <div class="toast align-items-center ${bgClass} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bx ${icon} me-2"></i>${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
    
    // Find or create toast container
    let $container = $('.toast-container');
    if (!$container.length) {
        $container = $('<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1070;"></div>');
        $('body').append($container);
    }
    
    // Add toast to container
    const $toast = $(toastHtml);
    $container.append($toast);
    
    // Initialize and show toast
    const toast = new bootstrap.Toast($toast[0], {
        autohide: true,
        delay: type === 'error' ? 8000 : 5000
    });
    
    toast.show();
    
    // Remove toast element after it's hidden
    $toast[0].addEventListener('hidden.bs.toast', function() {
        $(this).remove();
    });
}

// Global utility functions for external access
window.UserManagement = {
    openAddModal: openAddUserModal,
    editUser: editUser,
    deleteUser: deleteUser,
    changeStatus: changeUserStatus,
    refreshTable: function() {
        if (usersTable) {
            usersTable.ajax.reload();
        } else {
            location.reload();
        }
    }
};

console.log('User Management JavaScript loaded successfully');
