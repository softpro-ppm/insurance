<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'include/session.php';
include 'include/config.php'; 

// CSRF Token Generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Process comment submission with security
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment'])) {
    // CSRF validation
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        die('CSRF token validation failed');
    }
    
    $policy_id = filter_input(INPUT_POST, 'policy_id', FILTER_VALIDATE_INT);
    $comment = trim(filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING));
    
    if ($policy_id && !empty($comment)) {
        $stmt = $con->prepare("INSERT INTO customer_feedback (policy_id, comment, created_at, created_by) VALUES (?, ?, NOW(), ?)");
        $stmt->bind_param("iss", $policy_id, $comment, $_SESSION['user_id'] ?? 'system');
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = 'Comment added successfully!';
        } else {
            $_SESSION['error_message'] = 'Error adding comment. Please try again.';
        }
        $stmt->close();
    } else {
        $_SESSION['error_message'] = 'Invalid input. Please check your data.';
    }
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Follow UP | Softpro Insurance</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Manage policy renewals and customer feedback">
	<link rel="shortcut icon" href="assets/logo.PNG">
	
	<!-- Enhanced CSS -->
	<link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/libs/%40chenfengyuan/datepicker/datepicker.min.css" rel="stylesheet">
	<link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
	<link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
	
	<style>
		.renewal-status {
			font-weight: bold;
		}
		.expired { color: #dc3545; }
		.expiring-soon { color: #fd7e14; }
		.active { color: #198754; }
		
		.comment-box {
			background: #f8f9fa;
			border-left: 4px solid #007bff;
			padding: 15px;
			margin: 10px 0;
			border-radius: 5px;
		}
		
		.comment-meta {
			font-size: 0.875rem;
			color: #6c757d;
		}
		
		.filter-section {
			background: #f8f9fa;
			padding: 20px;
			border-radius: 8px;
			margin-bottom: 20px;
		}
		
		.stats-card {
			text-align: center;
			padding: 20px;
			border-radius: 8px;
			margin-bottom: 20px;
		}
		
		.badge-renewal-type {
			font-size: 0.75rem;
		}
	</style>
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
					
					<!-- Success/Error Messages -->
					<?php if (isset($_SESSION['success_message'])): ?>
						<div class="alert alert-success alert-dismissible fade show" role="alert">
							<i class="bx bx-check-circle me-2"></i><?php echo $_SESSION['success_message']; ?>
							<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
						</div>
						<?php unset($_SESSION['success_message']); ?>
					<?php endif; ?>
					
					<?php if (isset($_SESSION['error_message'])): ?>
						<div class="alert alert-danger alert-dismissible fade show" role="alert">
							<i class="bx bx-error me-2"></i><?php echo $_SESSION['error_message']; ?>
							<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
						</div>
						<?php unset($_SESSION['error_message']); ?>
					<?php endif; ?>

					<!-- Page Title -->
					<div class="row">
						<div class="col-12">
							<div class="page-title-box d-sm-flex align-items-center justify-content-between">
								<h4 class="mb-sm-0 font-size-18">
									<i class="bx bx-calendar-check me-2"></i>Manage Follow UP & Renewals
								</h4>
								<div class="page-title-right">
									<ol class="breadcrumb m-0">
										<li class="breadcrumb-item"><a href="home.php">Dashboard</a></li>
										<li class="breadcrumb-item active">Follow UP</li>
									</ol>
								</div>
							</div>
						</div>
					</div>

					<!-- Statistics Cards -->
					<div class="row mb-4">
						<?php
						// Get statistics with prepared statements
						$stats = [
							'total_policies' => 0,
							'expired_policies' => 0,
							'expiring_soon' => 0,
							'fc_renewals' => 0,
							'permit_renewals' => 0
						];
						
						$current_month = date('m');
						$current_year = date('Y');
						$today = date('Y-m-d');
						
						// Total policies expiring this month
						$stmt = $con->prepare("SELECT COUNT(*) as count FROM policy WHERE MONTH(policy_end_date) = ? AND YEAR(policy_end_date) = ?");
						$stmt->bind_param("ii", $current_month, $current_year);
						$stmt->execute();
						$result = $stmt->get_result();
						$stats['total_policies'] = $result->fetch_assoc()['count'];
						$stmt->close();
						
						// Expired policies
						$stmt = $con->prepare("SELECT COUNT(*) as count FROM policy WHERE policy_end_date < ?");
						$stmt->bind_param("s", $today);
						$stmt->execute();
						$result = $stmt->get_result();
						$stats['expired_policies'] = $result->fetch_assoc()['count'];
						$stmt->close();
						
						// Expiring in next 7 days
						$next_week = date('Y-m-d', strtotime('+7 days'));
						$stmt = $con->prepare("SELECT COUNT(*) as count FROM policy WHERE policy_end_date BETWEEN ? AND ?");
						$stmt->bind_param("ss", $today, $next_week);
						$stmt->execute();
						$result = $stmt->get_result();
						$stats['expiring_soon'] = $result->fetch_assoc()['count'];
						$stmt->close();
						?>
						
						<div class="col-xl-3 col-md-6">
							<div class="card stats-card bg-primary text-white">
								<div class="card-body">
									<h4 class="mb-2"><?php echo number_format($stats['total_policies']); ?></h4>
									<p class="mb-0">Total Renewals This Month</p>
								</div>
							</div>
						</div>
						
						<div class="col-xl-3 col-md-6">
							<div class="card stats-card bg-danger text-white">
								<div class="card-body">
									<h4 class="mb-2"><?php echo number_format($stats['expired_policies']); ?></h4>
									<p class="mb-0">Expired Policies</p>
								</div>
							</div>
						</div>
						
						<div class="col-xl-3 col-md-6">
							<div class="card stats-card bg-warning text-white">
								<div class="card-body">
									<h4 class="mb-2"><?php echo number_format($stats['expiring_soon']); ?></h4>
									<p class="mb-0">Expiring Soon (7 Days)</p>
								</div>
							</div>
						</div>
						
						<div class="col-xl-3 col-md-6">
							<div class="card stats-card bg-success text-white">
								<div class="card-body">
									<h4 class="mb-2">
										<?php echo number_format($stats['total_policies'] - $stats['expired_policies']); ?>
									</h4>
									<p class="mb-0">Active Policies</p>
								</div>
							</div>
						</div>
					</div>

					<!-- Enhanced Filter Section -->
					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-body filter-section">
									<h5 class="card-title mb-3">
										<i class="bx bx-filter me-2"></i>Filter & Search Options
									</h5>
									<form method="post" action="" autocomplete="off" id="filterForm">
										<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
										<div class="row g-3">
											<div class="col-md-3">
												<label class="form-label">From Date</label>
												<input type="text" name="fromdate" class="form-control js-datepicker" 
													   placeholder="Select from date"
													   value="<?php echo htmlspecialchars($_POST['fromdate'] ?? ''); ?>">
											</div>
											<div class="col-md-3">
												<label class="form-label">To Date</label>
												<input type="text" name="todate" class="form-control js-datepicker" 
													   placeholder="Select to date"
													   value="<?php echo htmlspecialchars($_POST['todate'] ?? ''); ?>">
											</div>
											<div class="col-md-3">
												<label class="form-label">Renewal Type</label>
												<select class="form-select" name="type">
													<option value="1" <?php echo (isset($_POST['type']) && $_POST['type'] == '1') ? 'selected' : ''; ?>>
														Policy Renewal
													</option>
													<option value="2" <?php echo (isset($_POST['type']) && $_POST['type'] == '2') ? 'selected' : ''; ?>>
														FC Renewal
													</option>
													<option value="3" <?php echo (isset($_POST['type']) && $_POST['type'] == '3') ? 'selected' : ''; ?>>
														Permit Renewal
													</option>
												</select>
											</div>
											<div class="col-md-3">
												<label class="form-label">&nbsp;</label>
												<div class="d-grid gap-2 d-md-flex">
													<button type="submit" name="submit" class="btn btn-primary">
														<i class="bx bx-search me-1"></i>Search
													</button>
													<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-outline-secondary">
														<i class="bx bx-reset me-1"></i>Reset
													</a>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>

					<!-- Main Data Table -->
					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-header">
									<h4 class="card-title mb-0">
										<i class="bx bx-list-ul me-2"></i>Renewal Follow-up List
									</h4>
								</div>
								<div class="card-body">
									<div class="table-responsive">
										<table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
											<thead class="table-light">
												<tr>
													<th>S.NO.</th>
													<th>Comments</th>
													<th>Vehicle Number</th>
													<th>Customer Name</th>
													<th>Phone</th>
													<th>Vehicle Type</th>
													<th>Policy Type</th>
													<th>Latest Comment</th>
													<th>Policy End Date</th>
													<th>Status</th>
													<th>Actions</th>
												</tr>
											</thead>
											<tbody>
												<?php  
													$sn = 1;
													
													// Secure query building with prepared statements
													$base_conditions = "WHERE 1=1";
													$params = [];
													$types = "";
													
													if (isset($_GET['pending'])) {
														$base_conditions .= " AND MONTH(policy_end_date) = ? AND YEAR(policy_end_date) = ? AND policy_end_date <= ?";
														$params = [date("m"), date("Y"), date("Y-m-d")];
														$types = "sss";
													} elseif (isset($_GET['renewal'])) {
														$base_conditions .= " AND MONTH(policy_end_date) = ? AND YEAR(policy_end_date) = ?";
														$params = [date("m"), date("Y")];
														$types = "ss";
													} elseif (isset($_POST['submit'])) {
														if ($_POST['type'] == '1') {
															$base_conditions .= " AND policy_end_date BETWEEN ? AND ?";
															$params = [date("Y-m-d", strtotime($_POST['fromdate'])), date("Y-m-d", strtotime($_POST['todate']))];
															$types = "ss";
														} elseif ($_POST['type'] == '2') {
															$base_conditions .= " AND fc_expiry_date BETWEEN ? AND ?";
															$params = [date("Y-m-d", strtotime($_POST['fromdate'])), date("Y-m-d", strtotime($_POST['todate']))];
															$types = "ss";
														} elseif ($_POST['type'] == '3') {
															$base_conditions .= " AND permit_expiry_date BETWEEN ? AND ?";
															$params = [date("Y-m-d", strtotime($_POST['fromdate'])), date("Y-m-d", strtotime($_POST['todate']))];
															$types = "ss";
														}
													} else {
														$base_conditions .= " AND MONTH(policy_end_date) = ? AND YEAR(policy_end_date) = ?";
														$params = [date("m"), date("Y")];
														$types = "ss";
													}
													
													$sql = "SELECT * FROM policy $base_conditions ORDER BY policy_end_date ASC, id DESC";
													$stmt = $con->prepare($sql);
													
													if (!empty($params)) {
														$stmt->bind_param($types, ...$params);
													}
													
													$stmt->execute();
													$rs = $stmt->get_result();
													
													if ($rs->num_rows > 0) {
														while ($r = $rs->fetch_assoc()) {
															// Get latest comment securely
															$comment_stmt = $con->prepare("SELECT comment, created_at FROM customer_feedback WHERE policy_id = ? ORDER BY created_at DESC LIMIT 1");
															$comment_stmt->bind_param("i", $r['id']);
															$comment_stmt->execute();
															$comment_result = $comment_stmt->get_result();
															
															if ($comment_result->num_rows > 0) {
																$latest_comment_data = $comment_result->fetch_assoc();
																$latest = htmlspecialchars($latest_comment_data['comment']);
																$comment_date = date('d-m-Y', strtotime($latest_comment_data['created_at']));
															} else {
																$latest = "No comments yet";
																$comment_date = "";
															}
															$comment_stmt->close();
															
															// Determine status
															$policy_end = strtotime($r['policy_end_date']);
															$today_timestamp = strtotime(date('Y-m-d'));
															$days_diff = ($policy_end - $today_timestamp) / (60 * 60 * 24);
															
															if ($days_diff < 0) {
																$status = '<span class="badge bg-danger">Expired</span>';
																$status_class = 'expired';
															} elseif ($days_diff <= 7) {
																$status = '<span class="badge bg-warning">Expiring Soon</span>';
																$status_class = 'expiring-soon';
															} else {
																$status = '<span class="badge bg-success">Active</span>';
																$status_class = 'active';
															}
												?>
												<tr>
													<td><?php echo $sn; ?></td>
													<td class="text-center">
														<button type="button" class="btn btn-primary btn-sm" 
																data-bs-toggle="modal" 
																onclick="viewcomment('<?php echo $r['id']; ?>')" 
																data-bs-target="#commentModal" 
																title="View/Add Comments">
															<i class="bx bx-comment"></i>
														</button>
													</td>
													<td>
														<a href="javascript:void(0);" 
														   class="text-body fw-bold waves-effect waves-light" 
														   onclick="viewpolicy(this)" 
														   data-id="<?php echo $r['id']; ?>"
														   title="View Policy Details">
															<?php echo htmlspecialchars($r['vehicle_number']); ?>
														</a>
													</td>
													<td><?php echo htmlspecialchars($r['name']); ?></td>
													<td>
														<a href="tel:<?php echo $r['phone']; ?>" class="text-decoration-none">
															<?php echo htmlspecialchars($r['phone']); ?>
														</a>
													</td>
													<td>
														<span class="badge badge-renewal-type bg-info">
															<?php echo htmlspecialchars($r['vehicle_type']); ?>
														</span>
													</td>
													<td>
														<span class="badge badge-renewal-type bg-secondary">
															<?php echo htmlspecialchars($r['policy_type']); ?>
														</span>
													</td>
													<td class="comment_last_<?php echo $r['id']; ?>">
														<small><?php echo $latest; ?></small>
														<?php if (!empty($comment_date)): ?>
															<br><em class="text-muted"><?php echo $comment_date; ?></em>
														<?php endif; ?>
													</td>
													<td>
														<span class="renewal-status <?php echo $status_class; ?>">
															<?php echo date('d-m-Y', strtotime($r['policy_end_date'])); ?>
														</span>
													</td>
													<td><?php echo $status; ?></td>
													<td>
														<div class="btn-group" role="group">
															<button type="button" class="btn btn-outline-primary btn-sm" 
																	onclick="loadPolicyForEdit(<?php echo $r['id']; ?>)" 
																	title="Edit Policy">
																<i class="bx bx-edit"></i>
															</button>
															<button type="button" class="btn btn-outline-info btn-sm" 
																	onclick="viewpolicy(this)" 
																	data-id="<?php echo $r['id']; ?>"
																	title="View Details">
																<i class="bx bx-show"></i>
															</button>
														</div>
													</td>
												</tr>
												<?php 
														$sn++; 
													} 
													$stmt->close();
												} else { 
												?> 
													<tr>
														<td colspan="11" class="text-center text-muted">
															<i class="bx bx-info-circle me-2"></i>No policies found for the selected criteria
														</td>
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

			<!-- Enhanced Comment Modal -->
			<div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header bg-primary text-white">
							<h5 class="modal-title" id="commentModalLabel">
								<i class="bx bx-comment-detail me-2"></i>Customer Feedback & Comments
							</h5>
							<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
						</div>
						<div class="modal-body">
							<!-- Add Comment Form -->
							<div class="card mb-4">
								<div class="card-header">
									<h6 class="mb-0"><i class="bx bx-plus me-2"></i>Add New Comment</h6>
								</div>
								<div class="card-body">
									<form id="submit_comment" method="POST">
										<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
										<input type="hidden" name="policy_id" id="policy_id_comment" value="">
										<div class="mb-3">
											<label for="comment_data" class="form-label">Your Comment</label>
											<textarea name="comment" class="form-control" id="comment_data" 
													  rows="4" placeholder="Write your follow-up comment here..." 
													  required maxlength="1000"></textarea>
											<div class="form-text">Maximum 1000 characters</div>
										</div>
										<button type="submit" class="btn btn-primary">
											<i class="bx bx-send me-1"></i>Submit Comment
										</button>
									</form>
									<div class="mt-2">
										<div class="comment_message"></div>
									</div>
								</div>
							</div>
							
							<!-- Display Comments -->
							<div class="card">
								<div class="card-header">
									<h6 class="mb-0"><i class="bx bx-history me-2"></i>Comment History</h6>
								</div>
								<div class="card-body">
									<div class="comment-section">
										<!-- Comments will be loaded here via AJAX -->
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Policy View Modal -->
			<div class="modal fade transaction-detailModal" tabindex="-1" role="dialog" 
				 aria-labelledby="transaction-detailModalLabel" aria-hidden="true" id="renewalpolicyview">
				<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
					<div class="modal-content border-0 shadow-lg" id="viewpolicydata"></div>
				</div>
			</div>

			<footer class="footer">
				<div class="container-fluid">
					<div class="row">
						<div class="col-sm-6">
							<script>document.write(new Date().getFullYear())</script> © Softpro Insurance.
						</div>
						<div class="col-sm-6">
							<div class="text-sm-end d-none d-sm-block">
								Design & Develop by <strong>Softpro</strong>
							</div>
						</div>
					</div>
				</div>
			</footer>
		</div>
	</div>

	<!-- JavaScript Libraries -->
	<script src="assets/libs/jquery/jquery.min.js"></script>
	<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
	<script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="assets/libs/metismenu/metisMenu.min.js"></script>
	<script src="assets/libs/simplebar/simplebar.min.js"></script>
	<script src="assets/libs/node-waves/waves.min.js"></script>
	<script src="assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
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

	<!-- Enhanced JavaScript -->
	<script type="text/javascript">
		// Initialize datepicker
		$('.js-datepicker').datepicker({
			format: 'dd-mm-yyyy',
			autoclose: true,
			todayHighlight: true
		});

		// Policy view function
		function viewpolicy(identifier) {
			var id = $(identifier).data("id");
			$('#renewalpolicyview').modal("show");
			$.post("include/view-policy.php", { id: id }, function(data) {
				$('#viewpolicydata').html(data);
			}).fail(function() {
				$('#viewpolicydata').html('<div class="alert alert-danger">Error loading policy details</div>');
			});
		}

		// View comments function
		function viewcomment(id) {
			$('#policy_id_comment').val(id);
			$('.comment-section').html('<div class="text-center"><div class="spinner-border text-primary" role="status"></div><p>Loading comments...</p></div>');
			$('.comment_message').html('');

			$.ajax({
				url: 'include/feedback-renewal-add.php',
				type: 'post',
				data: { id: id, load: 'load' },
				success: function(data) {
					$('.comment-section').html(data);
				},
				error: function() {
					$('.comment-section').html('<div class="alert alert-danger">Error loading comments</div>');
				}
			});
		}

		// Enhanced comment submission
		$('#submit_comment').on('submit', function(e) {
			e.preventDefault();

			var submitBtn = $(this).find('button[type="submit"]');
			var originalText = submitBtn.html();
			submitBtn.html('<span class="spinner-border spinner-border-sm me-1"></span>Submitting...').prop('disabled', true);

			$('.comment_message').html('');

			var id = $('#policy_id_comment').val();
			var comment_data = $('#comment_data').val().trim();

			if (!comment_data) {
				$('.comment_message').html('<div class="alert alert-warning">Please enter a comment</div>');
				submitBtn.html(originalText).prop('disabled', false);
				return;
			}

			$.ajax({
				url: 'include/feedback-renewal-add.php',
				type: 'post',
				data: { 
					policy_id: id, 
					comment: comment_data,
					csrf_token: $('input[name="csrf_token"]').val()
				},
				success: function(data) {
					$('.comment_message').html(data);
					viewcomment(id); // Reload comments
					$('#submit_comment')[0].reset();

					// Update latest comment in table
					$.ajax({
						url: 'include/feedback-renewal-add.php',
						type: 'post',
						data: { id: id, last: 'last' },
						success: function(data) {
							$('.comment_last_' + id).html(data);
						}
					});
				},
				error: function() {
					$('.comment_message').html('<div class="alert alert-danger">Error submitting comment</div>');
				},
				complete: function() {
					submitBtn.html(originalText).prop('disabled', false);
				}
			});
		});

		// Edit policy function (if modal exists)
		function loadPolicyForEdit(policyId) {
			if (typeof window.openEditModal === 'function') {
				window.openEditModal(policyId);
			} else {
				window.location.href = 'edit.php?id=' + policyId;
			}
		}

		// Enhanced DataTable initialization
		$(document).ready(function() {
			if ($.fn.DataTable.isDataTable('#datatable')) {
				$('#datatable').DataTable().destroy();
			}

			var table = $('#datatable').DataTable({
				"order": [[8, "asc"]], // Sort by policy end date
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
						className: 'btn btn-primary btn-sm me-1',
						text: '<i class="bx bx-copy"></i> Copy'
					},
					{
						extend: 'csv',
						className: 'btn btn-success btn-sm me-1',
						text: '<i class="bx bx-file"></i> CSV'
					},
					{
						extend: 'excel',
						className: 'btn btn-success btn-sm me-1',
						text: '<i class="bx bx-spreadsheet"></i> Excel',
						title: 'Feedback Renewal Report - ' + new Date().toLocaleDateString()
					},
					{
						extend: 'pdf',
						className: 'btn btn-danger btn-sm me-1',
						text: '<i class="bx bx-file-doc"></i> PDF',
						title: 'Feedback Renewal Report',
						orientation: 'landscape',
						pageSize: 'A4'
					},
					{
						extend: 'print',
						className: 'btn btn-info btn-sm me-1',
						text: '<i class="bx bx-printer"></i> Print'
					}
				],
				"columnDefs": [
					{
						"targets": [0, 1, 10], // S.NO, Comments, Actions
						"searchable": false,
						"orderable": false
					}
				],
				"language": {
					"search": "Search policies:",
					"lengthMenu": "Show _MENU_ entries",
					"info": "Showing _START_ to _END_ of _TOTAL_ policies",
					"infoEmpty": "No policies found",
					"infoFiltered": "(filtered from _MAX_ total entries)",
					"zeroRecords": "No matching policies found",
					"emptyTable": "No policies available for follow-up"
				}
			});

			// Auto-refresh every 5 minutes (optional)
			setInterval(function() {
				table.ajax.reload(null, false);
			}, 300000);
		});

		// Form validation enhancement
		$('#filterForm').on('submit', function(e) {
			var fromDate = $('input[name="fromdate"]').val();
			var toDate = $('input[name="todate"]').val();

			if (fromDate && toDate) {
				var from = new Date(fromDate.split('-').reverse().join('-'));
				var to = new Date(toDate.split('-').reverse().join('-'));

				if (from > to) {
					e.preventDefault();
					alert('From date cannot be greater than To date');
					return false;
				}
			}
		});
	</script>

	<!-- Include edit modal if available -->
	<?php if (file_exists('include/edit-policy-modal.php')): ?>
		<?php include 'include/edit-policy-modal.php'; ?>
		<script src="assets/js/edit-policy-modal.js"></script>
	<?php endif; ?>

</body>
</html>
