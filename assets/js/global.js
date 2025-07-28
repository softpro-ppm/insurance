/* ============================================
   GLOBAL POLICY MANAGEMENT JAVASCRIPT
   Bootstrap 5 + DataTables + File Upload System
   ============================================ */

// Global variables
let globalDataTable = null;
let currentDeleteId = null;
let currentDeleteData = null;
let initializationTimeout = null;
let isInitializing = false;

// DOM Content Loaded
document.addEventListener('DOMContentLoaded', function() {
    // Prevent page bouncing during initialization
    document.body.style.overflow = 'hidden';
    
    // Initialize after a brief delay to ensure DOM is fully ready
    setTimeout(() => {
        initializePolicyManagement();
        // Re-enable scrolling after initialization
        document.body.style.overflow = '';
    }, 100);
});

/* ====================
   DATATABLES INITIALIZATION
   ==================== */

function initializePolicyManagement() {
    // Prevent multiple simultaneous initializations
    if (isInitializing) return;
    isInitializing = true;
    
    try {
        initializeDataTable();
        initializeModals();
        initializeFileUploads();
        console.log('Policy Management System Initialized');
    } catch (error) {
        console.error('Error initializing policy management:', error);
    } finally {
        isInitializing = false;
    }
}

function initializeDataTable() {
    // Check if DataTable already exists and destroy it
    if ($.fn.DataTable.isDataTable('#datatable')) {
        $('#datatable').DataTable().destroy();
    }
    
    // Initialize DataTable with enhanced configuration
    globalDataTable = $('#datatable').DataTable({
        "processing": true,
        "deferRender": true,
        "order": [], // No initial sorting to maintain ORDER BY DESC from SQL
        "pageLength": 30, // Default to 30 as requested
        "lengthMenu": [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],
        "responsive": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "stateSave": true,
        "scrollCollapse": true,
        "dom": 'Bfrtip',
        "buttons": [
            {
                extend: 'copy',
                className: 'btn btn-outline-secondary btn-sm me-1',
                text: '<i class="bx bx-copy"></i> Copy'
            },
            {
                extend: 'csv',
                className: 'btn btn-outline-success btn-sm me-1',
                text: '<i class="bx bx-download"></i> CSV',
                title: 'Insurance Policies - ' + new Date().toLocaleDateString()
            },
            {
                extend: 'excel',
                className: 'btn btn-outline-success btn-sm me-1',
                text: '<i class="bx bx-file-blank"></i> Excel',
                title: 'Insurance Policies - ' + new Date().toLocaleDateString()
            },
            {
                extend: 'pdf',
                className: 'btn btn-outline-danger btn-sm me-1',
                text: '<i class="bx bx-file-blank"></i> PDF',
                title: 'Insurance Policies - ' + new Date().toLocaleDateString(),
                orientation: 'landscape',
                pageSize: 'A3'
            },
            {
                extend: 'print',
                className: 'btn btn-outline-primary btn-sm me-1',
                text: '<i class="bx bx-printer"></i> Print',
                title: 'Insurance Policies'
            }
        ],
        "columnDefs": [
            {
                "targets": 0, // Serial number column
                "searchable": false,
                "orderable": false,
                "className": "text-center"
            },
            {
                "targets": -1, // Actions column
                "searchable": false,
                "orderable": false,
                "className": "text-center action-buttons"
            }
        ],
        "drawCallback": function(settings) {
            // Recalculate global serial numbers on every redraw
            var api = this.api();
            var pageInfo = api.page.info();
            var startIndex = pageInfo.start;
            
            api.column(0, {page: 'current'}).nodes().each(function(cell, i) {
                cell.innerHTML = startIndex + i + 1;
            });
            
            // Re-initialize tooltips for new buttons
            initializeTooltips();
        },
        "initComplete": function(settings, json) {
            // Add initialized class to prevent FOUC
            $(this).closest('.dataTables_wrapper').addClass('initialized');
        },
        "language": {
            "search": "Search policies:",
            "lengthMenu": "Show _MENU_ entries",
            "info": "Showing _START_ to _END_ of _TOTAL_ policies",
            "infoEmpty": "No policies found",
            "infoFiltered": "(filtered from _MAX_ total policies)",
            "zeroRecords": "No matching policies found",
            "emptyTable": "No policies available",
            "paginate": {
                "first": "First",
                "last": "Last",
                "next": "Next",
                "previous": "Previous"
            }
        }
    });
    
    console.log('DataTable initialized with global serial numbering');
}

/* ====================
   MODAL MANAGEMENT
   ==================== */

function initializeModals() {
    // Create delete confirmation modal if it doesn't exist
    createDeleteModal();
    
    // Initialize modal event listeners
    setupModalEventListeners();
}

function createDeleteModal() {
    // Check if delete modal already exists
    if (document.getElementById('deletePolicyModal')) {
        return;
    }
    
    const deleteModalHtml = `
        <div class="modal fade delete-modal" id="deletePolicyModal" tabindex="-1" aria-labelledby="deletePolicyModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deletePolicyModalLabel">
                            <i class="bx bx-trash me-2"></i>Delete Policy Confirmation
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="delete-icon">
                            <i class="bx bx-error-circle"></i>
                        </div>
                        <h5 class="mb-3">Are you sure you want to delete this policy?</h5>
                        <div class="policy-info" id="deletePolicyInfo">
                            <!-- Policy details will be populated here -->
                        </div>
                        <p class="warning-text">
                            <i class="bx bx-info-circle me-2"></i>
                            This action will permanently delete:
                        </p>
                        <ul class="text-start warning-text">
                            <li>Policy record and all associated data</li>
                            <li>Uploaded document files (Aadhar, PAN, RC, etc.)</li>
                            <li>Related financial records</li>
                        </ul>
                        <p class="text-danger fw-bold mt-3">This action cannot be undone!</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bx bx-x me-2"></i>Cancel
                        </button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                            <i class="bx bx-trash me-2"></i>Delete Policy
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Append modal to body
    document.body.insertAdjacentHTML('beforeend', deleteModalHtml);
    console.log('Delete confirmation modal created');
}

function setupModalEventListeners() {
    // Delete confirmation handler
    document.addEventListener('click', function(e) {
        if (e.target.id === 'confirmDeleteBtn') {
            executeDelete();
        }
    });
    
    // Reset modal on close
    document.addEventListener('hidden.bs.modal', function(e) {
        if (e.target.id === 'deletePolicyModal') {
            resetDeleteModal();
        }
    });
}

/* ====================
   DELETE FUNCTIONALITY
   ==================== */

function showDeleteModal(policyId, policyData) {
    currentDeleteId = policyId;
    currentDeleteData = policyData;
    
    // Populate policy information
    const policyInfoDiv = document.getElementById('deletePolicyInfo');
    policyInfoDiv.innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <strong>Vehicle Number:</strong><br>
                <span class="text-dark">${policyData.vehicleNumber || 'N/A'}</span>
            </div>
            <div class="col-md-6">
                <strong>Customer Name:</strong><br>
                <span class="text-dark">${policyData.customerName || 'N/A'}</span>
            </div>
            <div class="col-md-6 mt-2">
                <strong>Policy Type:</strong><br>
                <span class="text-dark">${policyData.policyType || 'N/A'}</span>
            </div>
            <div class="col-md-6 mt-2">
                <strong>Premium:</strong><br>
                <span class="text-dark">₹${policyData.premium || '0'}</span>
            </div>
        </div>
    `;
    
    // Show modal
    const deleteModal = new bootstrap.Modal(document.getElementById('deletePolicyModal'));
    deleteModal.show();
}

function executeDelete() {
    if (!currentDeleteId) {
        alert('Error: No policy selected for deletion');
        return;
    }
    
    // Show loading state
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    const originalText = confirmBtn.innerHTML;
    confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Deleting...';
    confirmBtn.disabled = true;
    
    // Execute AJAX delete request
    $.post("include/delete-policy.php", { id: currentDeleteId })
        .done(function(response) {
            console.log('Delete response:', response);
            
            // Hide modal
            bootstrap.Modal.getInstance(document.getElementById('deletePolicyModal')).hide();
            
            // Show success message
            showNotification('Policy deleted successfully', 'success');
            
            // Reload the page to refresh data
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        })
        .fail(function(xhr, status, error) {
            console.error('Delete error:', error);
            showNotification('Error deleting policy. Please try again.', 'error');
            
            // Reset button
            confirmBtn.innerHTML = originalText;
            confirmBtn.disabled = false;
        });
}

function resetDeleteModal() {
    currentDeleteId = null;
    currentDeleteData = null;
    
    // Reset button state
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    if (confirmBtn) {
        confirmBtn.innerHTML = '<i class="bx bx-trash me-2"></i>Delete Policy';
        confirmBtn.disabled = false;
    }
}

/* ====================
   GLOBAL DELETE FUNCTION (for onclick handlers)
   ==================== */

window.deletepolicy = function(element) {
    const policyId = element.getAttribute('data-id');
    
    // Extract policy data from the table row
    const row = element.closest('tr');
    const cells = row.querySelectorAll('td');
    
    const policyData = {
        vehicleNumber: cells[1]?.textContent?.trim() || 'N/A',
        customerName: cells[2]?.textContent?.trim() || 'N/A',
        policyType: cells[5]?.textContent?.trim() || 'N/A',
        premium: cells[7]?.textContent?.trim() || '0'
    };
    
    showDeleteModal(policyId, policyData);
};

/* ====================
   FILE UPLOAD FUNCTIONALITY
   ==================== */

function initializeFileUploads() {
    // Initialize file inputs with preview functionality
    initializeFileInput('aadhar_card', 'Aadhar Card');
    initializeFileInput('pan_card', 'PAN Card');
    
    console.log('File upload system initialized');
}

function initializeFileInput(inputName, label) {
    // Handle both add and edit modals
    const selectors = [`#${inputName}`, `#edit_${inputName}`];
    
    selectors.forEach(selector => {
        const fileInput = document.querySelector(selector);
        if (fileInput) {
            setupFileInputEvents(fileInput, label);
        }
    });
}

function setupFileInputEvents(fileInput, label) {
    const wrapper = fileInput.closest('.file-input-wrapper') || createFileInputWrapper(fileInput, label);
    
    // File change event
    fileInput.addEventListener('change', function(e) {
        handleFileSelection(e.target);
    });
    
    // Drag and drop events
    const dropZone = wrapper.querySelector('.file-input-label');
    if (dropZone) {
        dropZone.addEventListener('dragover', handleDragOver);
        dropZone.addEventListener('dragleave', handleDragLeave);
        dropZone.addEventListener('drop', function(e) {
            handleFileDrop(e, fileInput);
        });
    }
}

function createFileInputWrapper(fileInput, label) {
    const wrapper = document.createElement('div');
    wrapper.className = 'file-input-wrapper';
    
    const labelElement = document.createElement('label');
    labelElement.className = 'file-input-label';
    labelElement.innerHTML = `
        <div class="file-input-icon">
            <i class="bx bx-cloud-upload"></i>
        </div>
        <div>
            <strong>Click to upload ${label}</strong><br>
            <small class="text-muted">Or drag and drop files here</small><br>
            <small class="text-muted">Supported: JPEG, PNG (Max 2MB)</small>
        </div>
    `;
    
    // Insert wrapper before the input
    fileInput.parentNode.insertBefore(wrapper, fileInput);
    wrapper.appendChild(fileInput);
    wrapper.appendChild(labelElement);
    
    return wrapper;
}

function handleFileSelection(fileInput) {
    const files = fileInput.files;
    if (files.length === 0) return;
    
    const file = files[0];
    
    // Validate file
    if (!validateFile(file, fileInput)) {
        fileInput.value = ''; // Clear invalid file
        return;
    }
    
    // Show preview
    showFilePreview(file, fileInput);
}

function validateFile(file, fileInput) {
    const maxSize = 2 * 1024 * 1024; // 2MB
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
    
    // Check file type
    if (!allowedTypes.includes(file.type)) {
        showNotification('Please select a JPEG or PNG image file', 'error');
        return false;
    }
    
    // Check file size
    if (file.size > maxSize) {
        showNotification('File size must be less than 2MB', 'error');
        return false;
    }
    
    return true;
}

function showFilePreview(file, fileInput) {
    const wrapper = fileInput.closest('.file-input-wrapper');
    let previewContainer = wrapper.querySelector('.file-preview-container');
    
    if (!previewContainer) {
        previewContainer = document.createElement('div');
        previewContainer.className = 'file-preview-container';
        wrapper.appendChild(previewContainer);
    }
    
    // Create preview element
    const preview = document.createElement('div');
    preview.className = 'file-preview';
    
    // Create image preview
    const reader = new FileReader();
    reader.onload = function(e) {
        preview.innerHTML = `
            <img src="${e.target.result}" alt="Preview">
            <div class="file-info">
                <div class="file-name">${file.name}</div>
                <div class="file-size">${formatFileSize(file.size)}</div>
            </div>
            <button type="button" class="remove-file" onclick="removeFilePreview(this, '${fileInput.id}')">
                <i class="bx bx-x"></i>
            </button>
        `;
    };
    reader.readAsDataURL(file);
    
    // Clear existing previews and add new one
    previewContainer.innerHTML = '';
    previewContainer.appendChild(preview);
    
    // Update label
    const label = wrapper.querySelector('.file-input-label');
    label.innerHTML = `
        <div class="file-input-icon">
            <i class="bx bx-check-circle text-success"></i>
        </div>
        <div>
            <strong class="text-success">File Selected</strong><br>
            <small class="text-muted">Click to change file</small>
        </div>
    `;
}

function removeFilePreview(button, inputId) {
    const fileInput = document.getElementById(inputId);
    fileInput.value = '';
    
    const wrapper = fileInput.closest('.file-input-wrapper');
    const previewContainer = wrapper.querySelector('.file-preview-container');
    if (previewContainer) {
        previewContainer.remove();
    }
    
    // Reset label
    const label = wrapper.querySelector('.file-input-label');
    const labelText = inputId.includes('aadhar') ? 'Aadhar Card' : 'PAN Card';
    label.innerHTML = `
        <div class="file-input-icon">
            <i class="bx bx-cloud-upload"></i>
        </div>
        <div>
            <strong>Click to upload ${labelText}</strong><br>
            <small class="text-muted">Or drag and drop files here</small><br>
            <small class="text-muted">Supported: JPEG, PNG (Max 2MB)</small>
        </div>
    `;
}

function handleDragOver(e) {
    e.preventDefault();
    e.currentTarget.classList.add('dragover');
}

function handleDragLeave(e) {
    e.preventDefault();
    e.currentTarget.classList.remove('dragover');
}

function handleFileDrop(e, fileInput) {
    e.preventDefault();
    e.currentTarget.classList.remove('dragover');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        fileInput.files = files;
        handleFileSelection(fileInput);
    }
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

/* ====================
   UTILITY FUNCTIONS
   ==================== */

function initializeTooltips() {
    // Initialize Bootstrap tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = `
        top: 20px;
        right: 20px;
        z-index: 1060;
        min-width: 300px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    `;
    
    const icon = type === 'success' ? 'bx-check-circle' : 
                 type === 'error' ? 'bx-error-circle' : 'bx-info-circle';
    
    notification.innerHTML = `
        <i class="bx ${icon} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

function refreshDataTable() {
    if (globalDataTable) {
        globalDataTable.ajax.reload(null, false); // false to keep current page
    }
}

// Export functions for global access
window.deletepolicy = window.deletepolicy;
window.removeFilePreview = removeFilePreview;
window.refreshDataTable = refreshDataTable;
