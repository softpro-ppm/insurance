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
	<!-- Policies Table Display Fix -->
	<link href="assets/css/policies-table-fix.css" rel="stylesheet" type="text/css" />
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
						                            <td class="text-center"><?=$sn;?></td>
						                            <td class="text-nowrap">
						                                <a href="javascript: void(0);" class="text-body fw-bold waves-effect waves-light" onclick="viewpolicy(this)" data-id="<?=$r['id']?>" >
						                                    <?=htmlspecialchars($r['vehicle_number'], ENT_QUOTES, 'UTF-8');?>
						                                </a>
						                            </td>
													<td class="text-nowrap"><?=htmlspecialchars($r['name'], ENT_QUOTES, 'UTF-8');?></td>
						                            <td class="text-nowrap"><?=htmlspecialchars($r['phone'], ENT_QUOTES, 'UTF-8');?></td>
						                            <td class="text-nowrap"><?=htmlspecialchars($r['vehicle_type'], ENT_QUOTES, 'UTF-8');?></td>
						                            <td class="text-nowrap"><?=htmlspecialchars($r['policy_type'], ENT_QUOTES, 'UTF-8');?></td>
						                            <td class="text-nowrap"><?=htmlspecialchars($r['insurance_company'], ENT_QUOTES, 'UTF-8');?></td>
						                            <td class="text-end">₹<?=number_format($r['premium'], 2);?></td>
						                            <td class="text-nowrap text-center"><?=date('d-m-Y',strtotime($r['policy_start_date']));?></td>
						                            <td class="text-nowrap text-center"><?=date('d-m-Y',strtotime($r['policy_end_date']));?></td>
						                            <td class="action-buttons">
						                                <div class="btn-group" role="group">
						                                    <button type="button" class="btn btn-outline-info btn-sm" onclick="viewPolicy(<?=$r['id'];?>)" title="View Policy Details" data-bs-toggle="tooltip">
						                                        <i class="bx bx-show"></i>
						                                    </button>
						                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="editPolicyFixed(<?=$r['id'];?>)" title="Edit Policy" data-bs-toggle="tooltip">
						                                        <i class="bx bx-edit"></i>
						                                    </button>
						                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="deletePolicy(<?=$r['id'];?>)" title="Delete Policy" data-bs-toggle="tooltip">
						                                        <i class="bx bx-trash"></i>
						                                    </button>
						                                </div>
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
			<!-- <footer class="footer">
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
			</footer> -->
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
            
            // Initialize DataTable with enhanced features and anti-bounce configuration
            var table = $('#datatable').DataTable({
                "order": [], // No initial sorting to maintain our ORDER BY DESC from SQL
                "pageLength": 30, // Default to 30 as requested (10, 30, 50, 100, All)
                "lengthMenu": [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],
                "responsive": false, // Disable responsive to prevent layout issues
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false, // Disable auto width
                "stateSave": true,
                "scrollX": true, // Enable horizontal scrolling for better mobile experience
                "scrollCollapse": true,
                "fixedColumns": false, // Disable fixed columns to prevent bouncing
                "processing": false, // Disable processing indicator to prevent bouncing
                "deferRender": true, // Improve performance and reduce bouncing
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
                        "className": "text-center",
                        "width": "60px"
                    },
                    {
                        "targets": 1, // Vehicle Number
                        "width": "120px",
                        "className": "text-nowrap"
                    },
                    {
                        "targets": 2, // Name
                        "width": "150px",
                        "className": "text-nowrap"
                    },
                    {
                        "targets": 3, // Phone
                        "width": "120px",
                        "className": "text-nowrap"
                    },
                    {
                        "targets": 4, // Vehicle Type
                        "width": "100px",
                        "className": "text-nowrap"
                    },
                    {
                        "targets": 5, // Policy Type
                        "width": "100px",
                        "className": "text-nowrap"
                    },
                    {
                        "targets": 6, // Insurance Company
                        "width": "150px",
                        "className": "text-nowrap"
                    },
                    {
                        "targets": 7, // Premium
                        "width": "100px",
                        "className": "text-end"
                    },
                    {
                        "targets": 8, // Policy Start Date
                        "width": "120px",
                        "className": "text-center text-nowrap"
                    },
                    {
                        "targets": 9, // Policy End Date
                        "width": "120px",
                        "className": "text-center text-nowrap"
                    },
                    {
                        "targets": -1, // Last column (Actions)
                        "searchable": false,
                        "orderable": false,
                        "className": "text-center action-buttons",
                        "width": "130px"
                    }
                ],
                "drawCallback": function(settings) {
                    // Prevent bouncing by disabling animations during redraw
                    $('#datatable').addClass('no-animate');
                    
                    // Recalculate GLOBAL serial numbers on every redraw (search, sort, pagination)
                    var api = this.api();
                    var pageInfo = api.page.info();
                    var startIndex = pageInfo.start; // This gives us the global start index
                    
                    api.column(0, {page: 'current'}).nodes().each(function(cell, i) {
                        cell.innerHTML = startIndex + i + 1;
                    });
                    
                    // Re-initialize tooltips for action buttons (without animation)
                    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                    tooltipTriggerList.map(function(tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl, {
                            animation: false // Disable tooltip animations
                        });
                    });
                    
                    // Re-enable animations after a brief delay
                    setTimeout(function() {
                        $('#datatable').removeClass('no-animate');
                    }, 50);
                },
                "initComplete": function(settings, json) {
                    console.log('DataTable initialized successfully');
                    
                    // Disable animations during initialization
                    $('#datatable').addClass('no-animate');
                    
                    // Ensure proper table layout
                    var table = this.api();
                    table.columns.adjust();
                    
                    // Fix any rendering issues and stabilize the table
                    setTimeout(function() {
                        $('#datatable').removeClass('dataTable').addClass('dataTable');
                        table.columns.adjust();
                        
                        // Re-enable animations after table is stable
                        setTimeout(function() {
                            $('#datatable').removeClass('no-animate');
                        }, 200);
                    }, 100);
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

        // New fixed edit policy function
        function editPolicyFixed(policyId) {
            console.log("Edit policy (fixed) function called for ID:", policyId);
            
            if (!policyId || policyId <= 0) {
                showToaster('Error: Invalid policy ID', 'error');
                return;
            }
            
            // Show loading toaster
            showToaster('Loading policy data...', 'info');
            
            $.ajax({
                url: "include/get-policy-data-clean.php",
                type: "POST",
                data: { policy_id: policyId },
                dataType: 'json',
                cache: false,
                timeout: 10000,
                beforeSend: function() {
                    console.log("Sending request to get policy data for ID:", policyId);
                },
                success: function(response) {
                    console.log("Received response:", response);
                    
                    if (response && response.success === true && response.data) {
                        console.log("Policy data loaded successfully:", response.data);
                        
                        // Show edit modal
                        const modal = new bootstrap.Modal(document.getElementById('editPolicyModal'), {
                            backdrop: 'static',
                            keyboard: false
                        });
                        modal.show();
                        
                        // Populate form with data
                        populateEditFormFixed(response.data);
                        showToaster('Policy data loaded successfully', 'success');
                    } else {
                        const errorMsg = response && response.message ? response.message : 'Failed to load policy data';
                        console.error("Policy data load failed:", errorMsg);
                        showToaster('Error: ' + errorMsg, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error Details:", {
                        status: status,
                        error: error,
                        responseText: xhr.responseText,
                        statusCode: xhr.status,
                        statusText: xhr.statusText
                    });
                    
                    let errorMessage = 'Failed to load policy data';
                    
                    if (xhr.status === 0) {
                        errorMessage = 'Network connection failed. Please check your internet connection.';
                    } else if (xhr.status === 404) {
                        errorMessage = 'Policy data service not found. Please contact support.';
                    } else if (xhr.status === 500) {
                        errorMessage = 'Server error occurred. Please try again later.';
                    } else if (status === 'timeout') {
                        errorMessage = 'Request timed out. Please try again.';
                    } else if (status === 'parsererror') {
                        errorMessage = 'Data parsing error. Please try again.';
                    } else {
                        errorMessage = 'Error: ' + error;
                    }
                    
                    showToaster(errorMessage, 'error');
                }
            });
        }

        // Enhanced form population function
        function populateEditFormFixed(data) {
            console.log('Populating edit form with data:', data);
            
            try {
                // Set hidden policy ID
                $('#edit_policy_id').val(data.id || '');
                
                // Customer & Vehicle Information
                $('#edit_vehicle_number').val(data.vehicle_number || '');
                $('#edit_phone').val(data.phone || '');
                $('#edit_name').val(data.name || '');
                $('#edit_vehicle_type').val(data.vehicle_type || '');
                
                // Insurance Information
                $('#edit_insurance_company').val(data.insurance_company || '');
                $('#edit_policy_type').val(data.policy_type || '');
                
                // Date Fields - handle empty dates properly
                $('#edit_policy_start_date').val(data.policy_start_date || '');
                $('#edit_policy_end_date').val(data.policy_end_date || '');
                $('#edit_policy_issue_date').val(data.policy_issue_date || '');
                $('#edit_fc_expiry_date').val(data.fc_expiry_date || '');
                $('#edit_permit_expiry_date').val(data.permit_expiry_date || '');
                
                // Financial Details - handle numbers properly
                $('#edit_premium').val(data.premium || 0);
                $('#edit_payout').val(data.payout || 0);
                $('#edit_customer_paid').val(data.customer_paid || 0);
                $('#edit_discount').val(data.discount || 0);
                $('#edit_calculated_revenue').val(data.calculated_revenue || 0);
                
                // Additional Information
                $('#edit_chassiss').val(data.chassiss || '');
                $('#edit_comments').val(data.comments || '');
                
                console.log('Edit form populated successfully');
                
            } catch (error) {
                console.error('Error populating edit form:', error);
                showToaster('Error displaying policy data: ' + error.message, 'error');
            }
        }

        function editPolicy(policyId) {
            console.log("Edit policy function called for ID:", policyId);
            
            if (!policyId) {
                showToaster('Error: Policy ID is missing', 'error');
                return;
            }
            
            // Show loading toaster
            showToaster('Loading policy data...', 'info');
            
            $.ajax({
                url: "include/get-policy-data-fixed.php",
                type: "POST",
                data: { policy_id: policyId },
                dataType: 'json',
                beforeSend: function() {
                    console.log("Loading policy data for edit, ID:", policyId);
                },
                success: function(response) {
                    console.log("Policy data response:", response);
                    
                    if (response && response.success && response.data) {
                        // Show edit modal
                        const modal = new bootstrap.Modal(document.getElementById('editPolicyModal'));
                        modal.show();
                        
                        // Populate form with data
                        populateEditForm(response.data);
                        showToaster('Policy data loaded successfully', 'success');
                    } else {
                        const errorMsg = response && response.message ? response.message : 'Failed to load policy data';
                        showToaster('Error: ' + errorMsg, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", {
                        status: status,
                        error: error,
                        responseText: xhr.responseText,
                        statusCode: xhr.status
                    });
                    
                    let errorMessage = 'Error loading policy data';
                    if (xhr.status === 0) {
                        errorMessage += ' - Network connection failed';
                    } else if (xhr.status === 404) {
                        errorMessage += ' - File not found';
                    } else if (xhr.status === 500) {
                        errorMessage += ' - Server error';
                    } else {
                        errorMessage += ' - ' + error;
                    }
                    
                    showToaster(errorMessage, 'error');
                }
            });
        }

        function populateEditForm(data) {
            console.log('Populating edit form with:', data);
            
            try {
                // Set hidden policy ID
                $('#edit_policy_id').val(data.id);
                
                // Customer & Vehicle Information
                $('#edit_vehicle_number').val(data.vehicle_number || '');
                $('#edit_phone').val(data.phone || '');
                $('#edit_name').val(data.name || '');
                $('#edit_vehicle_type').val(data.vehicle_type || '');
                
                // Insurance Information
                $('#edit_insurance_company').val(data.insurance_company || '');
                $('#edit_policy_type').val(data.policy_type || '');
                
                // Date Fields
                $('#edit_policy_start_date').val(data.policy_start_date || '');
                $('#edit_policy_end_date').val(data.policy_end_date || '');
                $('#edit_policy_issue_date').val(data.policy_issue_date || '');
                $('#edit_fc_expiry_date').val(data.fc_expiry_date || '');
                $('#edit_permit_expiry_date').val(data.permit_expiry_date || '');
                
                // Financial Details
                $('#edit_premium').val(data.premium || '');
                $('#edit_payout').val(data.payout || '');
                $('#edit_customer_paid').val(data.customer_paid || '');
                $('#edit_discount').val(data.discount || '');
                $('#edit_calculated_revenue').val(data.calculated_revenue || '');
                
                // Additional Information
                $('#edit_chassiss').val(data.chassiss || '');
                $('#edit_comments').val(data.comments || '');
                
                console.log('Edit form populated successfully');
                
            } catch (error) {
                console.error('Error populating edit form:', error);
                showToaster('Error displaying policy data', 'error');
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