<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
					<div class="row">
						<div class="col-12">
							<div class="page-title-box d-sm-flex align-items-center justify-content-between">
								<h4 class="mb-sm-0 font-size-18">Manage Renewal</h4>
								<div class="page-title-right">
									<ol class="breadcrumb m-0">
										<li class="breadcrumb-item">
											<form method="post" action="">
												<div class="row">
													<div class="col-md-3">
														<input type="text" name="fromdate" class="form-control" placeholder="From date" value="<?php echo isset($_POST['fromdate']) ? $_POST['fromdate'] : ''; ?>">
													</div>
													<div class="col-md-3">
														<input type="text" name="todate" class="form-control" placeholder="To date" value="<?php echo isset($_POST['todate']) ? $_POST['todate'] : ''; ?>">
													</div>
													<div class="col-md-3">
														<select class="form-control" name="type">
															<option value="1" <?php echo (isset($_POST['type']) && $_POST['type'] == '1') ? 'selected' : ''; ?>>Policies Renewal</option>
															<option value="2" <?php echo (isset($_POST['type']) && $_POST['type'] == '2') ? 'selected' : ''; ?>>FC Renewal</option>
															<option value="3" <?php echo (isset($_POST['type']) && $_POST['type'] == '3') ? 'selected' : ''; ?>>Permit Renewal</option>
														</select>
													</div>
													<div class="col-md-3">
														<button name="submit" class="btn btn-primary">Search</button>
														<a class="btn btn-secondary" href="manage-renewal.php">Reset</a>
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
									<div class="table-responsive">
										<table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
											<thead>
												<tr>
													<th>S.NO.</th>
													<th>VEHICLE NUMBER</th>
													<th>NAME</th>
													<th>PHONE</th>
													<th>VEHICLE TYPE</th>
													<th>POLICY TYPE</th>
													<th>POLICY END DATE</th>
													<th>PREMIUM</th>
													<th>POLICY START DATE</th>
													<th>ACTIONS</th>
												</tr>
											</thead>
											<tbody>
												<?php  
													try {
													    $sql = "";
													    
													    if(isset($_GET['pending'])){
													        $sql = "SELECT * FROM policy WHERE MONTH(policy_end_date) = '".date("m")."' AND YEAR(policy_end_date) = '".date("Y")."' AND policy_end_date <= '".date("Y-m-d")."' ORDER BY policy_end_date DESC";
													    } elseif(isset($_GET['renewal'])){
													        $sql = "SELECT * FROM policy WHERE MONTH(policy_end_date) = '".date("m")."' AND YEAR(policy_end_date) = '".date("Y")."' ORDER BY policy_end_date DESC";
													    } elseif(isset($_POST['submit']) && !empty($_POST['fromdate']) && !empty($_POST['todate'])){
													        $fromdate = mysqli_real_escape_string($con, $_POST['fromdate']);
													        $todate = mysqli_real_escape_string($con, $_POST['todate']);
													        
													        if($_POST['type'] == '1'){
													            $sql = "SELECT * FROM policy WHERE policy_end_date >= '".date("Y-m-d", strtotime($fromdate))."' AND policy_end_date <= '".date("Y-m-d", strtotime($todate))."' ORDER BY policy_end_date DESC";
													        } elseif($_POST['type'] == '2'){
													            $sql = "SELECT * FROM policy WHERE fc_expiry_date IS NOT NULL AND fc_expiry_date >= '".date("Y-m-d", strtotime($fromdate))."' AND fc_expiry_date <= '".date("Y-m-d", strtotime($todate))."' ORDER BY fc_expiry_date DESC";
													        } elseif($_POST['type'] == '3'){
													            $sql = "SELECT * FROM policy WHERE permit_expiry_date IS NOT NULL AND permit_expiry_date >= '".date("Y-m-d", strtotime($fromdate))."' AND permit_expiry_date <= '".date("Y-m-d", strtotime($todate))."' ORDER BY permit_expiry_date DESC";
													        }
													    } else {
													        $sql = "SELECT * FROM policy WHERE MONTH(policy_end_date) = '".date("m")."' AND YEAR(policy_end_date) = '".date("Y")."' ORDER BY policy_end_date DESC";
													    }
													    
													    $rs = mysqli_query($con, $sql);
													    
													    if(!$rs) {
													        echo "<tr><td colspan='10' class='text-center text-danger'>Query Error: " . mysqli_error($con) . "</td></tr>";
													    } elseif(mysqli_num_rows($rs) > 0) {
													        $sn = 1;
													        while ($r = mysqli_fetch_array($rs)) {
													            $premium = is_numeric($r['premium']) ? number_format($r['premium'], 2) : '0.00';
													            echo '<tr>
													                <td>'.$sn.'</td>
													                <td>'.htmlspecialchars($r['vehicle_number']).'</td>
													                <td>'.htmlspecialchars($r['name']).'</td>
													                <td>'.htmlspecialchars($r['phone']).'</td>
													                <td>'.htmlspecialchars($r['vehicle_type']).'</td>
													                <td>'.htmlspecialchars($r['policy_type']).'</td>
													                <td><strong class="text-danger">'.date('d-m-Y',strtotime($r['policy_end_date'])).'</strong></td>
													                <td>₹'.$premium.'</td>
													                <td>'.date('d-m-Y',strtotime($r['policy_start_date'])).'</td>
													                <td>
													                    <a href="edit.php?id='.$r['id'].'" class="btn btn-primary btn-sm">Edit</a>
													                </td>
													                </tr>';
													            $sn++;
													        }
													    } else {
													        echo "<tr><td colspan='10' class='text-center'>No renewals found</td></tr>";
													    }
													} catch (Exception $e) {
													    echo "<tr><td colspan='10' class='text-center text-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
													}
												?>
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

	<script src="assets/libs/jquery/jquery.min.js"></script>
	<script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="assets/libs/metismenu/metisMenu.min.js"></script>
	<script src="assets/libs/simplebar/simplebar.min.js"></script>
	<script src="assets/libs/node-waves/waves.min.js"></script>
	<script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
	<script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
	<script src="assets/js/app.js"></script>

	<script type="text/javascript">
        $(document).ready(function() {
            $('#datatable').DataTable({
                "pageLength": 25,
                "responsive": true,
                "order": []
            });
        });
    </script>
</body>
</html>
