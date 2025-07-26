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
	<link rel="shortcut icon" href="assets/logo.PNG">
	<link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
	<link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
	<!-- Modern theme -->
	<link href="assets/css/modern-theme.css" rel="stylesheet" type="text/css" />
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
											<a class="btn btn-primary btn-sm" href="add.php"><i class="bx bx-plus"></i> ADD POLICY</a>
										</li>
									</ol>
								</div>
							</div>
						</div>
					</div>
					<!-- end page title -->

					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-body">
									<div class="table-responsive">
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
													$sn=1;
													
													if(isset($_GET['latest'])){
													   // Show current month policies, recent first
													    $sql = "select * from policy where month(policy_start_date) ='".date('m')."' and year(policy_start_date)='".date('Y')."' ORDER BY id DESC";
													}else{
													    // Show all policies, recent first
													    $sql = "SELECT * FROM policy ORDER BY id DESC ";
													}
							                        
							                        $rs = mysqli_query($con, $sql);
							                        if(mysqli_num_rows($rs) > 0){
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
						                            <td>
						                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="loadPolicyForEdit(<?=$r['id'];?>)" title="Edit Policy">
						                                    <i class="fas fa-pencil-alt"></i>
						                                </button>
						                                <a href="javascript:void(0);" onclick="deletepolicy(this)" data-id="<?=$r['id']?>" class="btn btn-outline-danger btn-sm" title="Delete Policy">
						                                    <i class="fas fa-trash-alt"></i>
						                                </a>
						                            </td>
						                        </tr>
						                        <?php $sn++; } }else{ ?> 
						                        <tr>
            										<td colspan="11" >No Policy found</td>
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
			</div>>
			
			<!-- Policy view modal -->
			<div class="modal fade transaction-detailModal" tabindex="-1" role="dialog" aria-labelledby="transaction-detailModalLabel" aria-hidden="true" id="renewalpolicyview">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
                    <div class="modal-content border-0 shadow-lg" id="viewpolicydata"></div>
                </div>
            </div>

			<footer class="footer">
				<div class="container-fluid">
					<div class="row">
						<div class="col-sm-6">
							<script>document.write(new Date().getFullYear())</script>© Softpro.
						</div>
						<div class="col-sm-6">
							<div class="text-sm-end d-none d-sm-block">Design & Develop by Softpro</div>
						</div>
					</div>
				</div>
			</footer>
		</div>
	</div>

	<!-- JavaScript libraries -->
	<script src="assets/libs/jquery/jquery.min.js"></script>
	<script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="assets/js/edit-policy-modal.js"></script>
	<script src="assets/libs/metismenu/metisMenu.min.js"></script>
	<script src="assets/libs/simplebar/simplebar.min.js"></script>
	<script src="assets/libs/node-waves/waves.min.js"></script>
	<script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
	<script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
	<script src="assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
	<script src="assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
	<script src="assets/libs/jszip/jszip.min.js"></script>
	<script src="assets/libs/pdfmake/build/pdfmake.min.js"></script>
	<script src="assets/libs/pdfmake/build/vfs_fonts.js"></script>
	<script src="assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
	<script src="assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
	<script src="assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>
	<script src="assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
	<script src="assets/js/app.js"></script>
	<script src="assets/js/table2excel.js" type="text/javascript"></script>

	<!-- Initialize standard DataTable -->
	<script type="text/javascript">
        $(document).ready(function() {
            // Initialize DataTable with standard features
            var table = $('#datatable').DataTable({
                "order": [], // No initial sorting to maintain our ORDER BY DESC from SQL
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
                        title: 'Insurance Policies - ' + new Date().toLocaleDateString()
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-danger btn-sm',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        title: 'Insurance Policies - ' + new Date().toLocaleDateString(),
                        orientation: 'landscape',
                        pageSize: 'A3'
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-info btn-sm',
                        text: '<i class="fas fa-print"></i> Print',
                        title: 'Insurance Policies'
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
                "drawCallback": function(settings) {
                    // Recalculate serial numbers on every redraw (search, sort, pagination)
                    var api = this.api();
                    var startIndex = api.page.info().start;
                    api.column(0, {page: 'current'}).nodes().each(function(cell, i) {
                        cell.innerHTML = startIndex + i + 1;
                    });
                },
                "language": {
                    "search": "Search policies:",
                    "lengthMenu": "Show _MENU_ entries",
                    "info": "Showing _START_ to _END_ of _TOTAL_ policies",
                    "infoEmpty": "No policies found",
                    "infoFiltered": "(filtered from _MAX_ total policies)",
                    "zeroRecords": "No matching policies found",
                    "emptyTable": "No policies available"
                }
            });
        });
    </script>

    <script type="text/javascript">
        function Export() {
            $("#datatable").table2excel({
                filename: "Insurance_Policies_" + new Date().toISOString().slice(0,10) + ".xls"
            });
        }
        
        function deletepolicy(identifier) {
            var conf = confirm(" Are you sure you want to delete this policy? ");
            if(conf == true){
                var id = $(identifier).data("id");
                $.post("include/delete-policy.php", { id: id }, function(data) {
                    alert(data);
                    location.reload();
                });
            }
        }
        
        // Updated function to use new modal system
        function loadPolicyForEdit(policyId) {
            console.log('loadPolicyForEdit called with ID:', policyId);
            console.log('window.openEditModal type:', typeof window.openEditModal);
            console.log('openEditModal type:', typeof openEditModal);
            
            // Check multiple ways the function might be available
            if (typeof window.openEditModal === 'function') {
                console.log('Using window.openEditModal');
                window.openEditModal(policyId);
            } else if (typeof openEditModal === 'function') {
                console.log('Using global openEditModal');
                openEditModal(policyId);
            } else {
                // Add a longer delay and try again
                console.log('Function not available, trying with longer delay...');
                setTimeout(function() {
                    if (typeof window.openEditModal === 'function') {
                        console.log('Using window.openEditModal after delay');
                        window.openEditModal(policyId);
                    } else if (typeof openEditModal === 'function') {
                        console.log('Using global openEditModal after delay');
                        openEditModal(policyId);
                    } else {
                        // Fallback to old system
                        console.warn('New edit modal system not available after delay, using fallback');
                        window.location.href = 'edit.php?id=' + policyId;
                    }
                }, 500);
            }
        }
        
        function viewpolicy(identifier) {
            var id = $(identifier).data("id");
            $('#renewalpolicyview').modal("show");
            $.post("include/view-policy.php", { id: id }, function(data) {
                $('#viewpolicydata').html(data);
            });
        }
    </script>

    <?php include 'include/add-policy-modal.php'; ?>
    <?php include 'include/edit-policy-modal.php'; ?>
    
    <!-- Load edit modal functionality at the end to ensure all dependencies are loaded -->
    <script src="assets/js/edit-policy-modal.js"></script>
</body>
</html>