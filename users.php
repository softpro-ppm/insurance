<?php  
    require 'include/session.php';
    require 'include/config.php';
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>USERS | Softpro</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="assets/logo.PNG">
	<link href="assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="assets/libs/%40chenfengyuan/datepicker/datepicker.min.css">
	<link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
	<link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
	<!-- Modal Fix for Bootstrap 5 Compatibility -->
	<link href="assets/css/modal-fix.css" rel="stylesheet" type="text/css" />
	<!-- Enhanced Modal Fix for Interaction Issues -->
	<link href="assets/css/modal-fix-enhanced.css" rel="stylesheet" type="text/css" />
	<!-- Enhanced Modal Layout System -->
	<link href="assets/css/modal-layout-enhanced.css" rel="stylesheet" type="text/css" />
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
								<h4 class="mb-sm-0 font-size-18">USERS LIST</h4>
								<div class="page-title-right">
									<ol class="breadcrumb m-0">
										<li class="breadcrumb-item">
											<a class="btn btn-outline-primary btn-sm text-black" href="add-user.php"> <i class="fa fa-plus" ></i> Add</a>
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
										<table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
											<thead>
												<tr>
													<th>S.NO.</th>
													<th>NAME</th>
													<th>USERNAME</th>
													<th>PHONE</th>
													<th>EMAIL</th>
													<th>PASSWORD</th>
													<th>TYPE</th>
													<th>STATUS</th>
													<th>ACTIONS</th>
												</tr>
											</thead>
											<tbody>
												<?php  
                                                $sn = 1;
                                                $sql = mysqli_query($con, "SELECT * FROM user order by id desc");
                                                while ($r=mysqli_fetch_array($sql)) {
                                                    if($r['type'] = '1'){
                                                        $type = 'Admin';
                                                    }else{
                                                        $type = 'Employee';
                                                    }
                                                    if($r['delete_flag'] == '1'){
                                                        $status = 'Active';
                                                    }else{
                                                        $status = 'Inactive';
                                                    }
                                            ?>
                                            <tr>
                                                <td><?=$sn;?></td>
                                                <td><?=$r['name'];?></td>
                                                <td><?=$r['username'];?></td>
                                                <td><?=$r['password'];?></td>
                                                <td><?=$r['phone'];?></td>
                                                <td><?=$r['email'];?></td>
                                                <td><?=$type;?></td>
                                                <td>
                                                    <?php if($status == 'Active'){ ?>
                                                    <a href="javascript:void(0);" data-status="<?=$r['delete_flag'];?>" data-id="<?=$r['id'];?>" class="changestatus btn btn-sm btn-success" ><?=$status;?></a>
                                                    <?php }else{ ?>
                                                    <a href="javascript:void(0);" data-status="<?=$r['delete_flag'];?>" data-id="<?=$r['id'];?>" class="changestatus btn btn-sm btn-danger" ><?=$status;?></a>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <a href="edit-user.php?id=<?=$r['id'];?>" class="btn btn-primary btn-sm" >Edit</a>
                                                    <a href="javascript:void(0);" data-id="<?=$r['id'];?>" class="btn btn-danger btn-sm deleteuser" >Delete</a>
                                                </td>
                                            </tr>
                                            <?php $sn ++; } ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
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
							<div class="text-sm-end d-none d-sm-block">Design & Develop by Asksoft</div>
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
    <script src="assets/libs/select2/js/select2.min.js"></script>
    <script src="assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="assets/libs/spectrum-colorpicker2/spectrum.min.js"></script>
    <script src="assets/libs/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
    <script src="assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
    <script src="assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
    <script src="assets/libs/%40chenfengyuan/datepicker/datepicker.min.js"></script>
    <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script src="assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
    <script src="assets/libs/jszip/jszip.min.js"></script>
    <script src="assets/libs/pdfmake/build/pdfmake.min.js"></script>
    <script src="assets/libs/pdfmake/build/vfs_fonts.js"></script>
    <script src="assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>
    <script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
    <script src="assets/js/pages/form-advanced.init.js"></script>
    <script src="assets/js/app.js"></script>
    <script type="text/javascript">
        $(document).on( "click",".changestatus",function() {
            var conf = confirm( " Are you sure ? ");
            if(conf == true){
                var id = $(this).attr("data-id");
                var status = $(this).attr("data-status");
                $.post("include/update-user-status.php",{ id:id , status:status }, function(data) {
                    location.reload();
                });
            }else{
                return false;
            }
        });
    </script>
    <script type="text/javascript">
        $(document).on( "click",".deleteuser",function() {
            var conf = confirm( " Are you sure you want to delete this ? ");
            if(conf == true){
                var id = $(this).attr("data-id");
                $.post("include/delete-user.php",{ id:id }, function(data) {
                    alert(data);
                    location.reload();
                });
            }else{
                return false;
            }
        });
    </script>
    
    <!-- DataTable Initialization -->
    <script type="text/javascript">
        $(document).ready(function() {
            // Check if DataTable is already initialized and destroy it
            if ($.fn.DataTable.isDataTable('#datatable-buttons')) {
                $('#datatable-buttons').DataTable().destroy();
            }
            
            // Initialize DataTable with advanced features
            $('#datatable-buttons').DataTable({
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
        });
    </script>
    
    <!-- Modal Fix JavaScript for Bootstrap 5 -->
    <script src="assets/js/modal-fix.js"></script>
    <!-- Enhanced Modal Fix JavaScript -->
    <script src="assets/js/modal-fix-enhanced.js"></script>
    <!-- Enhanced Modal Layout System -->
    <script src="assets/js/modal-layout-enhanced.js"></script>
</body>
</html>