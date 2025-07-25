<?php 
    include 'include/session.php';
    include 'include/config.php'; 
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Manage Renewal | Softpro</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="assets/logo.PNG">
	<link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="assets/libs/%40chenfengyuan/datepicker/datepicker.min.css">
	<link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
	<link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
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
								<h4 class="mb-sm-0 font-size-18">Manage Renewal</h4>
								<div class="page-title-right">
									<ol class="breadcrumb m-0">
										<li class="breadcrumb-item">
											<form action="manage-renewal.php" autocomplete="off" method="post" >
												<div class="row" >
													<div class="col-md-3" >
														<?php if(!empty($_POST['fromdate'])){ ?>
														<input type="text" name="fromdate" class="form-control js-datepicker" value="<?=$_POST['fromdate'];?>" placeholder="From date" >
														<?php }else{ ?>
														<input type="text" name="fromdate" class="form-control js-datepicker" placeholder="From date"  name="">
														<?php } ?>
													</div>
													<div class="col-md-3" >
														<?php if(!empty($_POST['todate'])){ ?>
														<input class="form-control js-datepicker" type="text" value="<?=$_POST['todate'];?>" placeholder="To date" name="todate">
														<?php }else{ ?>
														<input class="form-control js-datepicker" type="text" placeholder="To date" name="todate">
														<?php } ?>
													</div>
													<div class="col-md-3" >
														<select class="form-control" name="type" >
															<?php if(isset($_POST['type']) && $_POST['type'] =='1' ){ ?>
															<option selected value="1" >Policies Renewal</option>
															<?php }else{ ?>
															<option value="1" >Policies Renewal</option>
															<?php } ?>
															<?php if(isset($_POST['type']) && $_POST['type'] =='2' ){ ?>
															<option selected value="2" >Fc Renewal</option>
															<?php }else{ ?>
															<option value="2" >Fc Renewal</option>
															<?php } ?>
															<?php if(isset($_POST['type']) && $_POST['type'] =='3' ){ ?>
															<option selected value="3" >Permit Renewal</option>
															<?php }else{ ?>
															<option value="3" >Permit Renewal</option>
															<?php } ?>
														</select>
													</div>
													<div class="col-md-3" >
														<button name="submit" class="btn btn-outline-primary" >Search</button>
														<a class="btn btn-outline-danger " href="manage-renewal.php">Reset</a>
													</div>
												</div>
											</form>
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
													<th>VEHICLE&nbsp;NUMBER</th>
													<th>NAME</th>
													<th>PHONE</th>
													<th>VEHICLE&nbsp;TYPE</th>
													<th>POLICY&nbsp;TYPE</th>
													<th>POLICY&nbsp;END&nbsp;DATE</th>
													<th>PREMIUM</th>
													<th>POLICY&nbsp;START&nbsp;DATE</th>
													<th>
														<span style="visibility: hidden;" >A</span>ACTIONS<span style="visibility: hidden;" >A</span>
													</th>
												</tr>
											</thead>
											<tbody>
												<?php  
													$sn=1;
													
													if(isset($_GET['pending'])){
													     // Pending Renewal = Policies that have already expired in July 2025 (up to today's date)
													     $sql = "select * from policy where month(policy_end_date) = '".date("m")."' and year(policy_end_date) = '".date("Y")."' and policy_end_date <= '".date("Y-m-d")."' ORDER BY policy_end_date DESC";
													}elseif(isset($_GET['renewal'])){
													     // Total Renewal = All policies expiring this month (July 2025)
													     $sql = "select * from policy where month(policy_end_date) = '".date("m")."' and year(policy_end_date) = '".date("Y")."' ORDER BY policy_end_date DESC";
													    
													}elseif(isset($_POST['submit'])){
														if($_POST['type'] == '1'){
															$sql = "SELECT * FROM policy where policy_end_date >='".date("Y-m-d", strtotime($_POST['fromdate']))."' and policy_end_date <='".date("Y-m-d", strtotime($_POST['todate']))."' ORDER BY id DESC ";
														}elseif($_POST['type'] == '2'){
															$sql = "SELECT * FROM policy where fc_expiry_date >='".date("Y-m-d", strtotime($_POST['fromdate']))."' and fc_expiry_date <='".date("Y-m-d", strtotime($_POST['todate']))."' ORDER BY id DESC ";
														}elseif($_POST['type'] == '3'){
															$sql = "SELECT * FROM policy where permit_expiry_date >='".date("Y-m-d", strtotime($_POST['fromdate']))."' and permit_expiry_date <='".date("Y-m-d", strtotime($_POST['todate']))."' ORDER BY id DESC ";
														}
													}else{
							                        	$sql = "SELECT * FROM policy where month(policy_end_date) ='".date("m")."' and year(policy_end_date)='".date("Y")."' ORDER BY policy_end_date DESC ";
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
													<td><a href="javascript: void(0);" class="text-body fw-bold waves-effect waves-light" onclick="viewpolicy(this)" data-id="<?=$r['id'];?>"><?=$r['vehicle_number'];?></a></td>
													<td><?=$r['name'];?></td>
													<td><?=$r['phone'];?></td>
													<td><?=$r['vehicle_type'];?></td>
													<td><?=$r['policy_type'];?></td>
													<td><strong class="text-danger"><?=date('d-m-Y',strtotime($r['policy_end_date']));?></strong></td>
													<td><?=$r['premium'];?></td>
													<td><?=date('d-m-Y',strtotime($r['policy_start_date']));?></td>
													<td>
														<button type="button" class="btn btn-outline-primary btn-sm" onclick="openEditModal(<?=$r['id'];?>)">
															<i class="fas fa-pencil-alt"></i>
														</button>
														<a href="javascript:void(0);" onclick="deletepolicy(this)" data-id="<?=$r['id']?>" class="btn btn-outline-danger btn-sm edit" ><i class="fas fa-trash-alt" ></i></a>
													</td>
												</tr>
												<?php $sn++; } }else{ ?> 
					                        <tr>
         										<td colspan="9" >No Policy found</td>
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
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
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
    <script src="assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="assets/libs/spectrum-colorpicker2/spectrum.min.js"></script>
    <script src="assets/libs/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
    <script src="assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
    <script src="assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
    <script src="assets/libs/%40chenfengyuan/datepicker/datepicker.min.js"></script>
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
	<script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
	<script src="assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
    <script src="assets/js/app.js"></script>
	<script type="text/javascript">
        $('.js-datepicker').datepicker({
           format: 'dd-mm-yyyy',
           autoclose: true, 
        })
        $('.js-datepicker').on("change", function() {
            $(this).datepicker("hide");
        })
    </script>
    <script type="text/javascript">
        function viewpolicy(identifier) {
            var id= $(identifier).data("id");
            $('#renewalpolicyview').modal("show");
            $.post("include/view-policy.php",{ id:id }, function(data) {
                $('#viewpolicydata').html(data);
            });
        }
    </script>
    
    <!-- Custom script for serial numbering -->
    <script type="text/javascript">
        $(document).ready(function() {
            // Check if DataTable is already initialized and destroy it
            if ($.fn.DataTable.isDataTable('#datatable')) {
                $('#datatable').DataTable().destroy();
            }
            
            // Initialize DataTable with advanced features
            var table = $('#datatable').DataTable({
                "order": [], // No initial sorting to maintain our ORDER BY from SQL
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
                        title: 'Renewal Management - ' + new Date().toLocaleDateString()
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-danger btn-sm',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        title: 'Renewal Management - ' + new Date().toLocaleDateString(),
                        orientation: 'landscape',
                        pageSize: 'A4'
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-info btn-sm',
                        text: '<i class="fas fa-print"></i> Print',
                        title: 'Renewal Management'
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
                    "search": "Search renewals:",
                    "lengthMenu": "Show _MENU_ entries",
                    "info": "Showing _START_ to _END_ of _TOTAL_ renewals",
                    "infoEmpty": "No renewals found",
                    "infoFiltered": "(filtered from _MAX_ total renewals)",
                    "zeroRecords": "No matching renewals found",
                    "emptyTable": "No renewals available"
                }
            });
        });
    </script>
    
    <script type="text/javascript">
        // Function to open edit modal
        function openEditModal(policyId) {
            // Show the modal
            $('#editPolicyModal').modal('show');
            
            // Show loading state
            const form = document.getElementById('editPolicyForm');
            form.style.opacity = '0.5';
            
            // Fetch policy data
            fetch(`include/get-policy-data.php?id=${policyId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success === false) {
                        alert('Error: ' + data.message);
                        $('#editPolicyModal').modal('hide');
                        return;
                    }
                    
                    // Extract policy object from response
                    const policy = data.policy;
                    
                    // Populate form fields
                    document.getElementById('edit_policy_id').value = policy.id;
                    document.getElementById('edit_vehicle_number').value = policy.vehicle_number || '';
                    document.getElementById('edit_phone').value = policy.phone || '';
                    document.getElementById('edit_name').value = policy.name || '';
                    document.getElementById('edit_vehicle_type').value = policy.vehicle_type || '';
                    document.getElementById('edit_insurance_company').value = policy.insurance_company || '';
                    document.getElementById('edit_policy_type').value = policy.policy_type || '';
                    document.getElementById('edit_policy_start_date').value = policy.policy_start_date || '';
                    document.getElementById('edit_policy_end_date').value = policy.policy_end_date || '';
                    document.getElementById('edit_premium').value = policy.premium || '';
                    document.getElementById('edit_payout').value = policy.payout || '';
                    document.getElementById('edit_customer_paid').value = policy.customer_paid || '';
                    document.getElementById('edit_comments').value = policy.comments || '';
                    
                    // Calculate and update financial fields
                    calculateEditFinancials();
                    
                    // Remove loading state
                    form.style.opacity = '1';
                })
                .catch(error => {
                    console.error('Error fetching policy data:', error);
                    alert('Error loading policy data. Please try again.');
                    $('#editPolicyModal').modal('hide');
                });
        }
    </script>

    <?php include 'include/add-policy-modal.php'; ?>
    <?php include 'include/edit-policy-modal.php'; ?>
</body>
</html>