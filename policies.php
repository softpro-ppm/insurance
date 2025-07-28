<?php 
    include 'include/session.php';
    include 'include/config.php'; 
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Policies | Softpro</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="0" />
	<link rel="shortcut icon" href="assets/logo.PNG">
	<!-- Bootstrap 5 DataTables CSS -->
	<link href="assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css?v=bootstrap5-<?=time()?>" rel="stylesheet" type="text/css" />
	<link href="assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css?v=bootstrap5-<?=time()?>" rel="stylesheet" type="text/css" />
	<link href="assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css?v=bootstrap5-<?=time()?>" rel="stylesheet" type="text/css" />
	<!-- Bootstrap 5.1.3 CSS -->
	<link href="assets/css/bootstrap.min.css?v=<?=time()?>" id="bootstrap-style" rel="stylesheet" type="text/css" />
	<link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
	<!-- Modal Fix for Bootstrap 5 Compatibility -->
	<link href="assets/css/modal-fix.css" rel="stylesheet" type="text/css" />
	<!-- Enhanced Modal Fix for Interaction Issues -->
	<link href="assets/css/modal-fix-enhanced.css" rel="stylesheet" type="text/css" />
	<!-- Enhanced Modal Layout System -->
	<link href="assets/css/modal-layout-enhanced.css" rel="stylesheet" type="text/css" />
	<!-- Medium Size Modal Enhancement -->
	<link href="assets/css/modal-medium-size.css" rel="stylesheet" type="text/css" />
	<!-- Modal Button Fix -->
	<link href="assets/css/modal-button-fix.css" rel="stylesheet" type="text/css" />
	<!-- Critical Modal Fix - Emergency Override -->
	<link href="assets/css/modal-fix-critical.css" rel="stylesheet" type="text/css" />
	<!-- View Policy Modal Complete Data Display Fix -->
	<link href="assets/css/view-policy-modal-fix.css" rel="stylesheet" type="text/css" />
	<!-- Global Policy Management Styles -->
	<link href="assets/css/global.css" rel="stylesheet" type="text/css" />
	<!-- Enhanced UI Components -->
	<link href="assets/css/enhanced-ui.css" rel="stylesheet" type="text/css" />
</head>

<body data-sidebar="dark">
	<div id="layout-wrapper">
		<header id="page-topbar">
			<?php require 'include/header.php'; ?>
		</header>
		<div class="vertical-menu">
			<div data-simplebar class="h-100">
				<div id="sidebar-menu">
					<?php require 'include/sidebar.php'; ?>
				</div>
			</div>
		</div>
		<div class="main-content">
			<div class="page-content">
				<div class="container-fluid">
					<!-- start page title -->
					<div class="row">
						<div class="col-12">
							<div class="page-title-box d-sm-flex align-items-center justify-content-between">
								<h4 class="mb-sm-0 font-size-18">Policies</h4>
								<div class="page-title-right">
									<ol class="breadcrumb m-0">
										<li class="breadcrumb-item">
											<a id="btnExport" class="btn btn-outline-primary btn-sm" href="excel.php"><i class="bx bx-download"></i> Export</a>
											<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPolicyModal">
												<i class="bx bx-plus"></i> Add Policy
											</button>
										</li>
									</ol>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-body">
									<div class="table-responsive" >
										<table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
											<thead>
												<tr>
													<th>S.NO.</th>
													<th>VEHICLE NUMBER</th>
													<th>NAME</th>
													<th>PHONE</th>
													<th>VEHICLE&nbsp;TYPE</th>
													<th>POLICY&nbsp;TYPE</th>
													<th>INSURANCE&nbsp;COMPANY</th>
													<th>PREMIUM</th>
													<th>POLICY&nbsp;START&nbsp;DATE</th>
													<th>POLICY&nbsp;END&nbsp;DATE</th>
													<th>ACTIONS</th>
												</tr>
											</thead>
											<tbody>
												<?php  
													if(isset($_GET['latest'])){
													   // Show current month policies, recent first
													    $sql = "select * from policy where month(policy_issue_date) ='".date('m')."' and year(policy_issue_date)='".date('Y')."' ORDER BY id DESC";
													}else{
													    // Show all policies, recent first
													    $sql = "SELECT * FROM policy ORDER BY id DESC ";
													}
							                        
							                        $rs = mysqli_query($con, $sql);
							                        if(mysqli_num_rows($rs) > 0){
							                        $sn=1; // Initialize serial number
							                        while ($r=mysqli_fetch_array($rs)) {

						                            if($r['fc_expiry_date'] == ''){
						                                $fc_expiry_date = '';
						                            }else{
						                                $fc_expiry_date = date('d-m-Y',strtotime($r['fc_expiry_date']));
						                            }

						                            if($r['permit_expiry_date'] == ''){
						                                $permit_expiry_date = '';
						                            }else{
						                                $permit_expiry_date = date('d-m-Y',strtotime($r['permit_expiry_date']));
						                            }
												?>
												<tr>
						                            <td><?=$sn;?></td>
						                            <td><a href="javascript: void(0);" class="text-body fw-bold waves-effect waves-light" onclick="viewpolicy(this)" data-id="<?=$r['id']?>" ><?=$r['vehicle_number'];?></a></td>
													<td><?=$r['name'];?></td>
						                            <td><?=$r['phone'];?></td>
						                            <td><?=$r['vehicle_type'];?></td>
						                            <td><?=$r['policy_type'];?></td>
						                            <td><?=$r['insurance_company'];?></td>
						                            <td><?=$r['premium'];?></td>
						                            <td><?=date('d-m-Y',strtotime($r['policy_start_date']));?></td>
						                            <td><?=date('d-m-Y',strtotime($r['policy_end_date']));?></td>
						                            <td class="action-buttons">
						                                <button type="button" class="btn btn-outline-info btn-sm btn-action" onclick="viewPolicy(<?=$r['id'];?>)" title="View Policy Details" data-bs-toggle="tooltip">
						                                    <i class="bx bx-show"></i>
						                                </button>
						                                <button type="button" class="btn btn-outline-primary btn-sm btn-action" onclick="editPolicy(<?=$r['id'];?>)" title="Edit Policy" data-bs-toggle="tooltip">
						                                    <i class="bx bx-edit"></i>
						                                </button>
						                                <button type="button" class="btn btn-outline-danger btn-sm btn-action" onclick="deletePolicy(<?=$r['id'];?>)" title="Delete Policy" data-bs-toggle="tooltip">
						                                    <i class="bx bx-trash"></i>
						                                </button>
						                            </td>
						                        </tr>
						                        <?php $sn++; } }else{ ?> 
						                        <tr>
            										<td colspan="10" >No Policy found</td>
						                        </tr>
                								<?php } ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal fade transaction-detailModal" tabindex="-1" role="dialog" aria-labelledby="transaction-detailModalLabel" aria-hidden="true" id="renewalpolicyview" >
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                    <div class="modal-content border-0 shadow-lg" id="viewpolicydata" ></div>
                </div>
            </div>
			<footer class="footer">
				<div class="container-fluid">
					<div class="row">
						<div class="col-sm-6">
							<script>
								document.write(new Date().getFullYear())
							</script>© Softpro.</div>
						<div class="col-sm-6">
							<div class="text-sm-end d-none d-sm-block">Design & Develop by Softpro</div>
						</div>
					</div>
				</div>
			</footer>
		</div>
	</div>
	<script src="assets/libs/jquery/jquery.min.js"></script>
	<script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="assets/libs/metismenu/metisMenu.min.js"></script>
	<script src="assets/libs/simplebar/simplebar.min.js"></script>
	<script src="assets/libs/node-waves/waves.min.js"></script>
	<!-- Bootstrap 5 DataTables JavaScript -->
	<script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
	<script src="assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js?v=bootstrap5-<?=time()?>"></script>
	<script src="assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
	<script src="assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js?v=bootstrap5-<?=time()?>"></script>
	<script src="assets/libs/jszip/jszip.min.js"></script>
	<script src="assets/libs/pdfmake/build/pdfmake.min.js"></script>
	<script src="assets/libs/pdfmake/build/vfs_fonts.js"></script>
	<script src="assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
	<script src="assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
	<script src="assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>
	<!-- <script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script> -->
	<script src="assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js?v=bootstrap5-<?=time()?>"></script>
	<script src="assets/js/app.js"></script>
	<script src="assets/js/table2excel.js" type="text/javascript"></script>
	
	<!-- Custom script for enhanced DataTables -->
	<script type="text/javascript">
        $(document).ready(function() {
            // Check if DataTable is already initialized and destroy it
            if ($.fn.DataTable.isDataTable('#datatable')) {
                $('#datatable').DataTable().destroy();
            }
            
            // Initialize DataTable with enhanced features - matching global.js configuration
            var table = $('#datatable').DataTable({
                "order": [], // No initial sorting to maintain our ORDER BY DESC from SQL
                "pageLength": 30, // Default to 30 as requested (10, 30, 50, 100, All)
                "lengthMenu": [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],
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
                        "targets": 0, // First column (S.NO.)
                        "searchable": false,
                        "orderable": false,
                        "className": "text-center"
                    },
                    {
                        "targets": -1, // Last column (Actions)
                        "searchable": false,
                        "orderable": false,
                        "className": "text-center action-buttons"
                    }
                ],
                "drawCallback": function(settings) {
                    // Recalculate GLOBAL serial numbers on every redraw (search, sort, pagination)
                    var api = this.api();
                    var pageInfo = api.page.info();
                    var startIndex = pageInfo.start; // This gives us the global start index
                    
                    api.column(0, {page: 'current'}).nodes().each(function(cell, i) {
                        cell.innerHTML = startIndex + i + 1;
                    });
                    
                    // Re-initialize tooltips for action buttons
                    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                    tooltipTriggerList.map(function(tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl);
                    });
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
        });
    </script>
    <script type="text/javascript">
        function Export() {
            $("#datatable").table2excel({
                filename: "Table.xls"
            });
        }
    </script>
	<script type="text/javascript">
        function viewPolicy(policyId) {
            console.log("View policy function called for ID:", policyId);
            
            if (!policyId) {
                showToaster('Error: Policy ID is missing', 'error');
                return;
            }
            
            // Show loading state
            showLoadingOverlay('#renewalpolicyview .modal-content');
            
            // Open modal first
            const modal = new bootstrap.Modal(document.getElementById('renewalpolicyview'));
            modal.show();
            
            $.ajax({
                url: "include/view-policy.php",
                type: "POST",
                data: { id: policyId },
                beforeSend: function() {
                    console.log("Loading policy view data for ID:", policyId);
                },
                success: function(data) {
                    console.log("Policy view data loaded successfully, length:", data.length);
                    hideLoadingOverlay('#renewalpolicyview .modal-content');
                    $('#viewpolicydata').html(data);
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    console.error("Response:", xhr.responseText);
                    hideLoadingOverlay('#renewalpolicyview .modal-content');
                    showToaster("Error loading policy data: " + error, 'error');
                }
            });
        }

        function editPolicy(policyId) {
            console.log("Edit policy function called for ID:", policyId);
            
            if (!policyId) {
                showToaster('Error: Policy ID is missing', 'error');
                return;
            }
            
            // Use the global.js function
            if (window.PolicyManagement && window.PolicyManagement.editPolicy) {
                window.PolicyManagement.editPolicy(policyId);
            } else {
                // Fallback to direct function call
                loadPolicyForEdit(policyId);
            }
        }

        function deletePolicy(policyId) {
            console.log("Delete policy function called for ID:", policyId);
            
            if (!policyId) {
                showToaster('Error: Policy ID is missing', 'error');
                return;
            }
            
            // Use the global.js function
            if (window.PolicyManagement && window.PolicyManagement.deletePolicy) {
                window.PolicyManagement.deletePolicy(policyId);
            } else {
                // Fallback to enhanced delete confirmation
                showEnhancedDeleteConfirmation(policyId);
            }
        }

        function showEnhancedDeleteConfirmation(policyId) {
            // Enhanced delete confirmation with Bootstrap modal and proper AJAX
            const confirmHtml = `
                <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">
                                    <i class="bx bx-error-circle me-2"></i>Confirm Delete
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="text-center">
                                    <i class="bx bx-trash text-danger mb-3" style="font-size: 4rem;"></i>
                                    <h5 class="mb-3">Are you sure you want to delete this policy?</h5>
                                    <div class="alert alert-warning">
                                        <i class="bx bx-info-circle me-2"></i>
                                        <strong>This action will permanently delete:</strong>
                                        <ul class="mt-2 mb-0 text-start">
                                            <li>Policy record and all data</li>
                                            <li>Uploaded documents (Aadhar, PAN, RC, etc.)</li>
                                            <li>Financial records and history</li>
                                        </ul>
                                    </div>
                                    <p class="text-danger fw-bold">
                                        <i class="bx bx-error me-2"></i>This action cannot be undone!
                                    </p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="bx bx-x me-1"></i>Cancel
                                </button>
                                <button type="button" class="btn btn-danger" onclick="confirmDeletePolicyEnhanced(${policyId})" data-bs-dismiss="modal">
                                    <i class="bx bx-trash me-1"></i>Yes, Delete Policy
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Remove existing modal if any
            $('#confirmDeleteModal').remove();
            
            // Add modal to body and show
            $('body').append(confirmHtml);
            new bootstrap.Modal(document.getElementById('confirmDeleteModal')).show();
        }
        
        function confirmDeletePolicyEnhanced(policyId) {
            console.log("Confirming delete for policy ID:", policyId);
            
            // Show loading toaster
            showToaster('Deleting policy...', 'info');
            
            $.ajax({
                url: "include/delete-policy.php",
                type: "POST",
                data: { id: policyId },
                dataType: 'text', // Expect text response
                success: function(data) {
                    console.log("Delete response:", data);
                    
                    if (data.includes('successfully') || data.includes('deleted')) {
                        showToaster('Policy deleted successfully!', 'success');
                        // Reload the page after a short delay
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        showToaster('Error: ' + data, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Delete error:', { xhr, status, error });
                    showToaster('Network error occurred while deleting policy', 'error');
                }
            });
        }

        // Enhanced toaster function
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

        // Enhanced loading overlay functions
        function showLoadingOverlay(selector) {
            const loadingHtml = `
                <div class="loading-overlay">
                    <div class="loading-spinner">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="mt-2">Loading...</div>
                    </div>
                </div>
            `;
            $(selector).css('position', 'relative').append(loadingHtml);
        }

        function hideLoadingOverlay(selector) {
            $(selector + ' .loading-overlay').remove();
        }
    </script>

    <?php include 'include/add-policy-modal.php'; ?>
    <?php include 'include/edit-policy-modal.php'; ?>
    
    <!-- Modal Fix JavaScript for Bootstrap 5 -->
    <script src="assets/js/modal-fix.js"></script>
    <!-- Enhanced Modal Fix JavaScript -->
    <script src="assets/js/modal-fix-enhanced.js"></script>
    <!-- Enhanced Modal Layout System -->
    <script src="assets/js/modal-layout-enhanced.js"></script>
    <!-- Modal Button Fix JavaScript -->
    <script src="assets/js/modal-button-fix.js"></script>
    <!-- Critical Modal Fix - Emergency Override -->
    <script src="assets/js/modal-fix-critical.js"></script>
    <!-- View Policy Modal Complete Data Display Fix -->
    <script src="assets/js/view-policy-modal-fix.js"></script>
    <!-- Global Policy Management JavaScript -->
    <script src="assets/js/global.js"></script>
</body>
</html>