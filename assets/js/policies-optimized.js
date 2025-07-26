// Optimized Policies Page JavaScript
// Enhanced with server-side processing, performance improvements, and modern UI

let policiesTable;
let currentFilters = {};

$(document).ready(function() {
    initializePoliciesTable();
    setupEventHandlers();
    applyModernTheme();
});

function initializePoliciesTable() {
    // Show loading state
    showTableLoading();
    
    // Destroy existing table if it exists
    if ($.fn.DataTable.isDataTable('#datatable')) {
        $('#datatable').DataTable().destroy();
    }
    
    // Initialize DataTable with server-side processing
    policiesTable = $('#datatable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "include/policies-ajax-optimized.php",
            "type": "POST",
            "data": function(d) {
                // Add custom filters
                d.startdate = currentFilters.startdate || '';
                d.enddate = currentFilters.enddate || '';
                d.searchval = currentFilters.searchval || '';
                return d;
            },
            "dataSrc": function(response) {
                hideTableLoading();
                
                if (response.error) {
                    showToast('Error', response.error, 'error');
                    return [];
                }
                
                // Update record count display
                updateRecordCount(response.recordsTotal, response.recordsFiltered);
                
                return response.data;
            },
            "error": function(xhr, error, thrown) {
                hideTableLoading();
                console.error('DataTable AJAX error:', error, thrown);
                showToast('Error', 'Failed to load policies data', 'error');
            }
        },
        "columns": [
            { "title": "S.No.", "width": "5%", "orderable": true },
            { "title": "Name", "width": "12%", "orderable": true },
            { "title": "Phone", "width": "10%", "orderable": true },
            { "title": "Vehicle Number", "width": "10%", "orderable": true },
            { "title": "Vehicle Type", "width": "8%", "orderable": true },
            { "title": "Insurance Company", "width": "12%", "orderable": true },
            { "title": "Policy Type", "width": "8%", "orderable": true },
            { "title": "Issue Date", "width": "8%", "orderable": true },
            { "title": "Start Date", "width": "8%", "orderable": true },
            { "title": "End Date", "width": "8%", "orderable": true },
            { "title": "FC Expiry", "width": "8%", "orderable": false },
            { "title": "Permit Expiry", "width": "8%", "orderable": false },
            { "title": "Premium", "width": "8%", "orderable": true },
            { "title": "Revenue", "width": "8%", "orderable": true },
            { "title": "Documents", "width": "5%", "orderable": false },
            { "title": "Actions", "width": "8%", "orderable": false }
        ],
        "order": [[0, "desc"]], // Order by ID descending (newest first)
        "pageLength": 25,
        "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
        "responsive": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "stateSave": true,
        "stateDuration": 300, // 5 minutes
        "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
               '<"row"<"col-sm-12"tr>>' +
               '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        "language": {
            "processing": '<div class="d-flex justify-content-center align-items-center">' +
                         '<div class="loading-spinner me-2"></div>' +
                         '<span>Loading policies...</span></div>',
            "emptyTable": "No policies found",
            "info": "Showing _START_ to _END_ of _TOTAL_ policies",
            "infoEmpty": "No policies to show",
            "infoFiltered": "(filtered from _MAX_ total policies)",
            "lengthMenu": "Show _MENU_ policies per page",
            "search": "Search policies:",
            "paginate": {
                "first": "First",
                "last": "Last",
                "next": "Next",
                "previous": "Previous"
            }
        },
        "drawCallback": function(settings) {
            // Apply animations to new rows
            $('#datatable tbody tr').addClass('fade-in');
            
            // Initialize tooltips
            $('[title]').tooltip();
            
            // Update page info
            updatePageInfo();
        },
        "initComplete": function(settings, json) {
            // Table initialization complete
            hideTableLoading();
            applyTableStyling();
            
            // Show success message
            if (json && json.recordsTotal) {
                showToast('Success', `Loaded ${json.recordsTotal} policies successfully`, 'success');
            }
        }
    });
}

function setupEventHandlers() {
    // Date range filter
    $('#dateRangeFilter').on('click', function() {
        showDateRangeModal();
    });
    
    // Quick filters
    $('.quick-filter').on('click', function() {
        const filterType = $(this).data('filter');
        applyQuickFilter(filterType);
    });
    
    // Export buttons
    $('#exportExcel').on('click', function() {
        exportToExcel();
    });
    
    $('#exportPDF').on('click', function() {
        exportToPDF();
    });
    
    // Refresh button
    $('#refreshTable').on('click', function() {
        refreshPolicyTable();
    });
    
    // Global search enhancement
    let searchTimeout;
    $('#datatable_filter input').off('keyup.DT search.DT').on('keyup', function() {
        clearTimeout(searchTimeout);
        const searchTerm = this.value;
        
        searchTimeout = setTimeout(() => {
            if (searchTerm.length >= 2 || searchTerm.length === 0) {
                policiesTable.search(searchTerm).draw();
            }
        }, 500);
    });
}

function applyModernTheme() {
    // Apply modern styling to table
    $('#datatable').addClass('table-modern');
    
    // Style pagination
    $('.dataTables_paginate .paginate_button').addClass('page-link');
    $('.dataTables_paginate .paginate_button.current').addClass('active');
    
    // Style length menu
    $('.dataTables_length select').addClass('form-select form-select-sm');
    
    // Style search input
    $('.dataTables_filter input').addClass('form-control form-control-sm');
}

function showTableLoading() {
    const tableContainer = $('#datatable_wrapper');
    if (tableContainer.find('.table-loading-overlay').length === 0) {
        const loadingOverlay = `
            <div class="table-loading-overlay position-absolute w-100 h-100 d-flex justify-content-center align-items-center" 
                 style="background: rgba(255,255,255,0.9); z-index: 1000; top: 0; left: 0;">
                <div class="text-center">
                    <div class="loading-spinner mb-3"></div>
                    <h5 class="text-muted">Loading Policies</h5>
                    <p class="text-muted">Please wait while we fetch your data...</p>
                </div>
            </div>
        `;
        tableContainer.css('position', 'relative').append(loadingOverlay);
    }
}

function hideTableLoading() {
    $('.table-loading-overlay').fadeOut(300, function() {
        $(this).remove();
    });
}

function applyTableStyling() {
    // Add modern styling classes
    $('#datatable thead th').addClass('bg-light text-dark border-bottom-0');
    $('#datatable tbody td').addClass('border-top-0');
    
    // Style action buttons
    $('.btn-group .btn').hover(
        function() { $(this).addClass('shadow-sm'); },
        function() { $(this).removeClass('shadow-sm'); }
    );
}

function applyQuickFilter(filterType) {
    const today = new Date();
    let startDate, endDate;
    
    switch(filterType) {
        case 'today':
            startDate = endDate = formatDate(today);
            break;
        case 'week':
            startDate = formatDate(new Date(today.setDate(today.getDate() - 7)));
            endDate = formatDate(new Date());
            break;
        case 'month':
            startDate = formatDate(new Date(today.getFullYear(), today.getMonth(), 1));
            endDate = formatDate(new Date(today.getFullYear(), today.getMonth() + 1, 0));
            break;
        case 'expiring':
            // Policies expiring in next 30 days
            startDate = formatDate(new Date());
            endDate = formatDate(new Date(today.setDate(today.getDate() + 30)));
            filterByExpiryDate(startDate, endDate);
            return;
        case 'all':
            currentFilters = {};
            break;
    }
    
    if (filterType !== 'all') {
        currentFilters.startdate = startDate;
        currentFilters.enddate = endDate;
    }
    
    refreshPolicyTable();
    updateFilterDisplay();
}

function updateRecordCount(total, filtered) {
    const countElement = $('#recordCount');
    if (countElement.length) {
        countElement.text(`Total: ${total} policies`);
    }
}

function updatePageInfo() {
    const info = policiesTable.page.info();
    const pageInfo = `Page ${info.page + 1} of ${info.pages}`;
    $('.page-info').text(pageInfo);
}

function updateFilterDisplay() {
    const filterDisplay = $('#activeFilters');
    if (!filterDisplay.length) return;
    
    let filterText = '';
    if (currentFilters.startdate || currentFilters.enddate) {
        filterText += `Date: ${currentFilters.startdate || 'Any'} to ${currentFilters.enddate || 'Any'}`;
    }
    if (currentFilters.searchval) {
        filterText += filterText ? `, Name: ${currentFilters.searchval}` : `Name: ${currentFilters.searchval}`;
    }
    
    filterDisplay.text(filterText || 'No filters applied');
}

function refreshPolicyTable() {
    showTableLoading();
    policiesTable.ajax.reload(function() {
        hideTableLoading();
        showToast('Success', 'Policies refreshed successfully', 'success');
    }, false);
}

function exportToExcel() {
    showToast('Info', 'Preparing Excel export...', 'info');
    
    // Get current table data
    const data = policiesTable.data().toArray();
    
    // Create workbook
    const wb = XLSX.utils.book_new();
    const ws = XLSX.utils.aoa_to_sheet([
        ['S.No', 'Name', 'Phone', 'Vehicle Number', 'Vehicle Type', 'Insurance Company', 
         'Policy Type', 'Issue Date', 'Start Date', 'End Date', 'Premium', 'Revenue'],
        ...data.map(row => [
            row[0], row[1], row[2], row[3], row[4], row[5], 
            row[6], row[7], row[8], row[9], row[12], row[13]
        ])
    ]);
    
    XLSX.utils.book_append_sheet(wb, ws, 'Policies');
    XLSX.writeFile(wb, `Policies_${formatDate(new Date())}.xlsx`);
    
    showToast('Success', 'Excel file downloaded successfully', 'success');
}

function exportToPDF() {
    showToast('Info', 'Preparing PDF export...', 'info');
    
    // Use DataTables built-in PDF export
    policiesTable.button('.buttons-pdf').trigger();
}

// Policy action functions
function viewPolicy(policyId) {
    // Implementation for viewing policy details
    showToast('Info', 'Loading policy details...', 'info');
    // Add your view policy logic here
}

function editPolicy(policyId) {
    openEditModal(policyId);
}

function deletePolicy(policyId) {
    if (confirm('Are you sure you want to delete this policy? This action cannot be undone.')) {
        showToast('Info', 'Deleting policy...', 'info');
        
        const formData = new FormData();
        formData.append('action', 'delete_policy');
        formData.append('policy_id', policyId);
        
        fetch('include/policy-operations.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Success', data.message, 'success');
                refreshPolicyTable();
            } else {
                showToast('Error', data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Delete error:', error);
            showToast('Error', 'Failed to delete policy', 'error');
        });
    }
}

function viewDocuments(policyId) {
    // Implementation for viewing policy documents
    showToast('Info', 'Loading documents...', 'info');
    // Add your document viewing logic here
}

// Utility functions
function formatDate(date) {
    const d = new Date(date);
    const year = d.getFullYear();
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

function showToast(title, message, type = 'info') {
    // Use the toast function from edit-policy-modal.js
    if (typeof window.showToast === 'function') {
        window.showToast(title, message, type);
    } else {
        // Fallback to alert
        alert(`${title}: ${message}`);
    }
}

// Make functions globally available
window.viewPolicy = viewPolicy;
window.editPolicy = editPolicy;
window.deletePolicy = deletePolicy;
window.viewDocuments = viewDocuments;
window.refreshPolicyTable = refreshPolicyTable;
