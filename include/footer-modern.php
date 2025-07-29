        </div> <!-- End Page Content -->
    </div> <!-- End Main Content Wrapper -->

    <!-- Footer -->
    <footer class="footer bg-dark text-light py-4 mt-5 <?php echo $useSidebar ? 'sidebar-footer' : ''; ?>">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">
                        <i class="fas fa-copyright me-1"></i>
                        <?php echo date('Y'); ?> Softpro Insurance Management System. All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">
                        <small class="text-muted">
                            Version 2.0 | 
                            <a href="#" class="text-light text-decoration-none">Support</a> | 
                            <a href="#" class="text-light text-decoration-none">Documentation</a>
                        </small>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3.3 JavaScript Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery (required for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- DataTables with Bootstrap 5 -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
    
    <!-- Global JavaScript Functions -->
    <script>
        // Global Variables
        let currentDataTable = null;
        
        // Document Ready Functions
        $(document).ready(function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Initialize popovers
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
            
            // Auto-hide alerts after 5 seconds
            $('.alert:not(.alert-persistent)').delay(5000).fadeOut(300);
            
            // Global form validation
            $('.needs-validation').on('submit', function(e) {
                if (!this.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                    showErrorToast('Please fill in all required fields correctly');
                }
                this.classList.add('was-validated');
            });
            
            // Global AJAX setup
            $.ajaxSetup({
                beforeSend: function() {
                    // Show loading indicator for AJAX requests
                    if (typeof ModalSystem !== 'undefined') {
                        ModalSystem.showLoading();
                    }
                },
                complete: function() {
                    // Hide loading indicator
                    if (typeof ModalSystem !== 'undefined') {
                        ModalSystem.hideLoading();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    if (typeof showErrorToast !== 'undefined') {
                        showErrorToast('An error occurred while processing your request');
                    }
                }
            });
        });
        
        // Global DataTable Configuration
        function initializeDataTable(tableId, options = {}) {
            const defaultOptions = {
                responsive: true,
                processing: true,
                serverSide: true,
                pageLength: 25,
                lengthMenu: [[10, 20, 25, 50, 100], [10, 20, 25, 50, 100]],
                language: {
                    processing: '<i class="fas fa-spinner fa-spin fa-2x"></i><br>Loading...',
                    lengthMenu: "Show _MENU_ entries per page",
                    zeroRecords: "No matching records found",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "No entries available",
                    infoFiltered: "(filtered from _MAX_ total entries)",
                    search: "Search:",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                },
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                     '<"row"<"col-sm-12"B>>' +
                     '<"row"<"col-sm-12"tr>>' +
                     '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        className: 'btn btn-success btn-sm',
                        text: '<i class="fas fa-file-excel me-1"></i>Excel'
                    },
                    {
                        extend: 'pdfHtml5',
                        className: 'btn btn-danger btn-sm',
                        text: '<i class="fas fa-file-pdf me-1"></i>PDF'
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-info btn-sm',
                        text: '<i class="fas fa-print me-1"></i>Print'
                    },
                    {
                        extend: 'colvis',
                        className: 'btn btn-secondary btn-sm',
                        text: '<i class="fas fa-columns me-1"></i>Columns'
                    }
                ],
                order: [[0, 'desc']],
                columnDefs: [
                    {
                        targets: '_all',
                        className: 'text-center'
                    }
                ]
            };
            
            const finalOptions = $.extend(true, defaultOptions, options);
            
            if (currentDataTable) {
                currentDataTable.destroy();
            }
            
            currentDataTable = $(tableId).DataTable(finalOptions);
            return currentDataTable;
        }
        
        // Global Utility Functions
        function formatCurrency(amount) {
            return new Intl.NumberFormat('en-IN', {
                style: 'currency',
                currency: 'INR'
            }).format(amount);
        }
        
        function formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('en-IN', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }
        
        function formatDateTime(dateString) {
            return new Date(dateString).toLocaleDateString('en-IN', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
        
        function validateEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
        
        function validatePhone(phone) {
            const phoneRegex = /^[0-9]{10}$/;
            return phoneRegex.test(phone.replace(/\D/g, ''));
        }
        
        function validateVehicleNumber(vehicleNumber) {
            const vehicleRegex = /^[A-Z]{2}[0-9]{2}[A-Z]{2}[0-9]{4}$/;
            return vehicleRegex.test(vehicleNumber.replace(/\s/g, ''));
        }
        
        // File Upload Validation
        function validateFileUpload(input, maxSize = 5, allowedTypes = ['pdf', 'jpg', 'jpeg', 'png']) {
            const file = input.files[0];
            if (!file) return true;
            
            const fileName = file.name.toLowerCase();
            const fileSize = file.size / (1024 * 1024); // Convert to MB
            const fileExtension = fileName.split('.').pop();
            
            if (!allowedTypes.includes(fileExtension)) {
                showErrorToast(`Invalid file type. Allowed types: ${allowedTypes.join(', ')}`);
                input.value = '';
                return false;
            }
            
            if (fileSize > maxSize) {
                showErrorToast(`File size must be less than ${maxSize}MB`);
                input.value = '';
                return false;
            }
            
            return true;
        }
        
        // Auto-refresh functions
        function startAutoRefresh(interval = 300000) { // 5 minutes
            setInterval(function() {
                if (currentDataTable) {
                    currentDataTable.ajax.reload(null, false);
                }
                
                // Refresh notifications
                if (typeof refreshNotifications === 'function') {
                    refreshNotifications();
                }
            }, interval);
        }
        
        // Print function
        function printElement(elementId) {
            const printContent = document.getElementById(elementId);
            const windowPrint = window.open('', '_blank');
            
            windowPrint.document.write(`
                <html>
                    <head>
                        <title>Print</title>
                        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
                        <style>
                            body { font-family: Arial, sans-serif; }
                            .no-print { display: none; }
                            @media print {
                                .no-print { display: none !important; }
                            }
                        </style>
                    </head>
                    <body>
                        ${printContent.innerHTML}
                    </body>
                </html>
            `);
            
            windowPrint.document.close();
            windowPrint.focus();
            windowPrint.print();
            windowPrint.close();
        }
        
        // Confirmation Dialog
        function confirmAction(message, callback) {
            if (confirm(message)) {
                callback();
            }
        }
        
        // Copy to Clipboard
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                showSuccessToast('Copied to clipboard');
            }).catch(function() {
                showErrorToast('Failed to copy to clipboard');
            });
        }
        
        // Auto-save form data
        function enableAutoSave(formId, interval = 30000) { // 30 seconds
            setInterval(function() {
                const form = document.getElementById(formId);
                if (form) {
                    const formData = new FormData(form);
                    const data = {};
                    for (let [key, value] of formData.entries()) {
                        data[key] = value;
                    }
                    localStorage.setItem(`autosave_${formId}`, JSON.stringify(data));
                }
            }, interval);
        }
        
        function restoreAutoSavedData(formId) {
            const savedData = localStorage.getItem(`autosave_${formId}`);
            if (savedData) {
                const data = JSON.parse(savedData);
                const form = document.getElementById(formId);
                if (form) {
                    Object.keys(data).forEach(key => {
                        const input = form.querySelector(`[name="${key}"]`);
                        if (input) {
                            input.value = data[key];
                        }
                    });
                    showInfoToast('Auto-saved data restored');
                }
            }
        }
        
        function clearAutoSavedData(formId) {
            localStorage.removeItem(`autosave_${formId}`);
        }
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl+S to save (prevent default and trigger form submit)
            if (e.ctrlKey && e.key === 's') {
                e.preventDefault();
                const activeForm = document.querySelector('form:focus-within, form .form-control:focus');
                if (activeForm) {
                    activeForm.closest('form').requestSubmit();
                }
            }
            
            // Escape key to close modals
            if (e.key === 'Escape') {
                const openModal = document.querySelector('.modal.show');
                if (openModal) {
                    bootstrap.Modal.getInstance(openModal).hide();
                }
            }
        });
    </script>
    
    <!-- Page-specific JavaScript -->
    <?php if (isset($customJS)) echo $customJS; ?>
    
    <!-- Google Analytics (if needed) -->
    <?php if (isset($googleAnalyticsId) && $googleAnalyticsId): ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $googleAnalyticsId; ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?php echo $googleAnalyticsId; ?>');
    </script>
    <?php endif; ?>

    <style>
        /* Footer styling */
        .footer {
            border-top: 1px solid #dee2e6;
            margin-top: auto;
        }
        
        .sidebar-footer {
            margin-left: 280px;
            transition: margin-left 0.3s ease;
        }
        
        @media (max-width: 768px) {
            .sidebar-footer {
                margin-left: 0;
            }
        }
        
        .main-content.sidebar-collapsed ~ .sidebar-footer {
            margin-left: 80px;
        }
    </style>

</body>
</html>
