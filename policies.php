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
						                                <button type="button" class="btn btn-outline-primary btn-sm btn-action" onclick="loadPolicyForEdit(<?=$r['id'];?>)" title="Edit Policy" data-bs-toggle="tooltip">
						                                    <i class="bx bx-edit"></i>
						                                </button>
						                                <button type="button" class="btn btn-outline-danger btn-sm btn-action" onclick="deletepolicy(this)" data-id="<?=$r['id']?>" title="Delete Policy" data-bs-toggle="tooltip">
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
        function deletepolicy(identifier) {
            var conf = confirm( " Are you sure you want to delete this ? ");
            if(conf == true){
                var id= $(identifier).data("id");
                $.post("include/delete-policy.php",{ id:id }, function(data) {
                    alert(data);
                    location.reload();
                });
            }else{
                return false;
            }
        }
    </script>
    <script type="text/javascript">
        function viewpolicy(identifier) {
            console.log("viewpolicy function called");
            var id= $(identifier).data("id");
            console.log("Policy ID:", id);
            
            $('#renewalpolicyview').modal("show");
            console.log("Modal show called");
            
            $.post("include/view-policy.php",{ id:id }, function(data) {
                console.log("AJAX response received, length:", data.length);
                $('#viewpolicydata').html(data);
            }).fail(function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
                console.error("Response:", xhr.responseText);
                alert("Error loading policy data: " + error);
            });
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