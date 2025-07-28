<?php  
	include 'session.php';
	include 'config.php';

	// Validate input
	if (!isset($_POST['id']) || empty($_POST['id'])) {
		echo '<div class="modal-header bg-danger text-white">
				<h5 class="modal-title">Error</h5>
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="alert alert-danger">Invalid policy ID provided.</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			</div>';
		exit;
	}

	$policy_id = mysqli_real_escape_string($con, $_POST['id']);
	$sql = mysqli_query($con, "select * from policy where id='".$policy_id."'");
	
	if (!$sql || mysqli_num_rows($sql) == 0) {
		echo '<div class="modal-header bg-warning text-dark">
				<h5 class="modal-title">Policy Not Found</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="alert alert-warning">The requested policy could not be found in the database.</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			</div>';
		exit;
	}
	
	$r = mysqli_fetch_array($sql);

	if($r['fc_expiry_date'] !=''){
		$fc_expiry_date = date('d-m-Y', strtotime($r['fc_expiry_date']));
	}else{
		$fc_expiry_date = '';
	}

	if($r['permit_expiry_date'] !=''){
		$permit_expiry_date = date('d-m-Y', strtotime($r['permit_expiry_date']));
	}else{
		$permit_expiry_date = '';
	}

<<<<<<< HEAD
	$data = 
		'<div class="modal-header bg-primary text-white border-0">
=======
	$data = '<div class="modal-header bg-primary text-white">
>>>>>>> 1f7b50d32c5c8f031a319939d390a458ad4b1e45
            <h5 class="modal-title d-flex align-items-center" id="transaction-detailModalLabel">
                <i class="bx bx-file-blank mr-2"></i>
                <span>Policy Details - <strong>'.$r['vehicle_number'].'</strong></span>
            </h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body p-4" style="background: #ffffff; color: #1f2937;">
            
            <!-- Data Completeness Summary -->
            <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="bx bx-data me-2"></i>Data Completeness Overview</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <div class="badge bg-primary p-2">
                                <i class="bx bx-id-card"></i><br>
                                <small>Policy ID: #'.$r['id'].'</small>
                            </div>
                        </div>';
                        
        // Financial completeness badge
        $financial_complete = (!empty($r['payout']) && !empty($r['customer_paid']));
        $data .= '<div class="col-md-3 text-center">
                            <div class="badge '.($financial_complete ? 'bg-success' : 'bg-warning').' p-2">
                                <i class="bx bx-money"></i><br>
                                <small>'.($financial_complete ? 'Complete Financial' : 'Basic Financial').'</small>
                            </div>
                        </div>';
                        
        // Comments badge
        $data .= '<div class="col-md-3 text-center">
                            <div class="badge '.(!empty($r['comments']) ? 'bg-success' : 'bg-secondary').' p-2">
                                <i class="bx bx-comment"></i><br>
                                <small>'.(!empty($r['comments']) ? 'Has Comments' : 'No Comments').'</small>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="badge bg-info p-2">
                                <i class="bx bx-time"></i><br>
                                <small>'.date('M Y', strtotime($r['created_date'])).'</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Customer & Vehicle Info Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="bx bx-user mr-2"></i>Customer & Vehicle Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="info-label">Vehicle Number:</label>
                                <div class="info-value badge bg-primary">'.$r['vehicle_number'].'</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="info-label">Customer Name:</label>
                                <div class="info-value">'.$r['name'].'</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="info-label">Phone:</label>
                                <div class="info-value">'.$r['phone'].'</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="info-label">Vehicle Type:</label>
                                <div class="info-value">'.$r['vehicle_type'].'</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Policy Information Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="bx bx-file-blank mr-2"></i>Policy Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="info-label">Policy Type:</label>
                                <div class="info-value">'.$r['policy_type'].'</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="info-label">Policy Number:</label>
                                <div class="info-value">'.$r['policy_number'].'</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="info-label">Company:</label>
                                <div class="info-value">'.$r['company'].'</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="info-label">Sub Agent:</label>
                                <div class="info-value">'.$r['sub_agent'].'</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="info-label">Issue Date:</label>
                                <div class="info-value">'.date('d-m-Y', strtotime($r['policy_issue_date'])).'</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="info-label">End Date:</label>
                                <div class="info-value text-danger font-weight-bold">'.date('d-m-Y', strtotime($r['policy_end_date'])).'</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Information Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="bx bx-money mr-2"></i>Financial Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="info-item">
                                <label class="info-label">Premium:</label>
                                <div class="info-value text-success font-weight-bold">₹ '.number_format($r['premium'], 2).'</div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="info-item">
                                <label class="info-label">Revenue:</label>
                                <div class="info-value text-primary font-weight-bold">₹ '.number_format($r['revenue'], 2).'</div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="info-item">
                                <label class="info-label">Payment Mode:</label>
                                <div class="info-value">'.$r['payment_mode'].'</div>
                            </div>
                        </div>
<<<<<<< HEAD
                    </div>
                </div>
            </div>

            <!-- Additional Information Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="bx bx-info-circle mr-2"></i>Additional Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
=======
                        <div class="col-md-4 mb-3">
>>>>>>> 1f7b50d32c5c8f031a319939d390a458ad4b1e45
                            <div class="info-item">
                                <label class="info-label">FC Expiry Date:</label>
                                <div class="info-value">'.($fc_expiry_date ? $fc_expiry_date : '<span class="text-muted">Not set</span>').'</div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="info-item">
                                <label class="info-label">Permit Expiry Date:</label>
                                <div class="info-value">'.($permit_expiry_date ? $permit_expiry_date : '<span class="text-muted">Not set</span>').'</div>
                            </div>
                        </div>';
                        
        // Show chassis number if available
        if (!empty($r['chassiss'])) {
            $data .= '<div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="info-label">Chassis Number:</label>
                                <div class="info-value">'.htmlspecialchars($r['chassiss']).'</div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="info-item">
<<<<<<< HEAD
                                <label class="info-label">Remarks:</label>
                                <div class="info-value">'.$r['remarks'].'</div>
=======
                                <label class="info-label">Revenue:</label>
                                <div class="info-value text-success">₹'.number_format($r['revenue'], 2).'</div>
                            </div>
                        </div>';
                        
        // Show new financial fields if available
        if (isset($r['payout']) && $r['payout'] !== null) {
            $data .= '<div class="col-md-4 mb-3">
                            <div class="info-item">
                                <label class="info-label">Payout:</label>
                                <div class="info-value">₹'.number_format($r['payout'], 2).'</div>
                            </div>
                        </div>';
        }
        if (isset($r['customer_paid']) && $r['customer_paid'] !== null) {
            $data .= '<div class="col-md-4 mb-3">
                            <div class="info-item">
                                <label class="info-label">Customer Paid:</label>
                                <div class="info-value">₹'.number_format($r['customer_paid'], 2).'</div>
                            </div>
                        </div>';
        }
        if (isset($r['discount']) && $r['discount'] !== null) {
            $data .= '<div class="col-md-4 mb-3">
                            <div class="info-item">
                                <label class="info-label">Discount:</label>
                                <div class="info-value">₹'.number_format($r['discount'], 2).'</div>
                            </div>
                        </div>';
        }
        
        // Show calculated revenue if available
        if (isset($r['calculated_revenue']) && $r['calculated_revenue'] !== null) {
            $data .= '<div class="col-md-4 mb-3">
                            <div class="info-item">
                                <label class="info-label">Calculated Revenue:</label>
                                <div class="info-value text-info">₹'.number_format($r['calculated_revenue'], 2).'</div>
                            </div>
                        </div>';
        }
        
        $data .= '</div>
                </div>
            </div>

            <!-- Documents Section -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="bx bx-download me-2"></i>Documents & Downloads</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="info-label">Policy Documents:</label>
                                <div class="info-value">';
                        	// Check files table - ordered by ID to show all documents chronologically
                        	$files_sql = mysqli_query($con, "SELECT * FROM files WHERE policy_id='".$r['id']."' ORDER BY id ASC");
                        	$doc_count = 0;
                            while ($file_row = mysqli_fetch_array($files_sql)) {
                                $doc_count++;
                                $file_path = "assets/uploads/".$file_row['files'];
                                // Check if file actually exists
                                if (file_exists("../".$file_path)) {
                        $data .='<a href="include/file-download.php?file='.$file_row['files'].'" target="_blank" class="btn btn-primary btn-sm me-2 mb-2" title="'.$file_row['files'].'">
                                    <i class="bx bx-download me-1"></i>Policy Doc '.$doc_count.'
                                </a>';
                                } else {
                        $data .='<span class="btn btn-outline-secondary btn-sm me-2 mb-2" disabled title="File missing: '.$file_row['files'].'">
                                    <i class="bx bx-x me-1"></i>Doc '.$doc_count.' (Missing)
                                </span>';
                                }
                        	}
                        	if ($doc_count == 0) {
                        	    $data .= '<span class="text-muted"><i class="bx bx-info-circle me-1"></i>No policy documents uploaded</span>';
                        	} else {
                        	    $data .= '<br><small class="text-muted">Total: '.$doc_count.' document(s)</small>';
                        	}
                        $data .='</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="info-label">RC Documents:</label>
                                <div class="info-value">';
                        	$rc_sql = mysqli_query($con, "SELECT * FROM rc WHERE policy_id='".$r['id']."' ORDER BY id ASC");
                        	$rc_count = 0;
                            while ($rc_row = mysqli_fetch_array($rc_sql)) {
                                $rc_count++;
                                $rc_file_path = "assets/uploads/".$rc_row['files'];
                                // Check if file actually exists
                                if (file_exists("../".$rc_file_path)) {
                        $data .='<a href="include/file-download.php?file='.$rc_row['files'].'" target="_blank" class="btn btn-success btn-sm me-2 mb-2" title="'.$rc_row['files'].'">
                                    <i class="bx bx-download me-1"></i>RC Doc '.$rc_count.'
                                </a>';
                                } else {
                        $data .='<span class="btn btn-outline-secondary btn-sm me-2 mb-2" disabled title="File missing: '.$rc_row['files'].'">
                                    <i class="bx bx-x me-1"></i>RC '.$rc_count.' (Missing)
                                </span>';
                                }
                        	}
                        	if ($rc_count == 0) {
                        	    $data .= '<span class="text-muted"><i class="bx bx-info-circle me-1"></i>No RC documents uploaded</span>';
                        	} else {
                        	    $data .= '<br><small class="text-muted">Total: '.$rc_count.' document(s)</small>';
                        	}
                        $data .='</div>
>>>>>>> 1f7b50d32c5c8f031a319939d390a458ad4b1e45
                            </div>
                        </div>
                    </div>
                </div>
<<<<<<< HEAD
            </div>
=======
            </div>';
            
        // Show policy comments if available (inline comments field)
        if (!empty($r['comments'])) {
            $data .= '<div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="bx bx-comment-detail me-2"></i>Policy Comments</h6>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info mb-0">'.htmlspecialchars($r['comments']).'</div>
                        </div>
                    </div>';
        }
            
        // Comments section if exists (from separate comments table)
        $comments_sql = mysqli_query($con, "select * from comments where policy_id='".$r['id']."'");
        if (mysqli_num_rows($comments_sql) > 0) {
            $data .= '<div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-secondary text-white">
                            <h6 class="mb-0"><i class="bx bx-comment me-2"></i>Additional Comments</h6>
                        </div>
                        <div class="card-body">';
            while ($comment_row = mysqli_fetch_array($comments_sql)) {
                $data .= '<div class="alert alert-light border">'.htmlspecialchars($comment_row['comments']).'</div>';
            }
            $data .= '</div>
                    </div>';
        }
        
        // Additional info section removed - chassis number moved to policy information section
        
        // Show creation date and last update if available
        $data .= '<div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="bx bx-time me-2"></i>Record Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">';
        
        if (!empty($r['created_date'])) {
            $data .= '<div class="col-md-6 mb-3">
                        <div class="info-item">
                            <label class="info-label">Created Date:</label>
                            <div class="info-value">'.date('d-m-Y H:i', strtotime($r['created_date'])).'</div>
                        </div>
                    </div>';
        }
        
        $data .= '<div class="col-md-6 mb-3">
                    <div class="info-item">
                        <label class="info-label">Policy ID:</label>
                        <div class="info-value badge bg-secondary">#'.$r['id'].'</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                <i class="bx bx-x me-1"></i>Close
            </button>
            <button type="button" class="btn btn-primary" onclick="openEditFromView('.$r['id'].')">
                <i class="bx bx-edit me-1"></i>Edit Policy
            </button>
>>>>>>> 1f7b50d32c5c8f031a319939d390a458ad4b1e45
        </div>

        <style>
        .info-item {
            margin-bottom: 1rem;
        }
        .info-label {
            font-weight: 600;
            color: #6b7280;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
            display: block;
        }
        .info-value {
            font-weight: 500;
            color: #1f2937;
            background: #f9fafb;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
<<<<<<< HEAD
            border: 1px solid #e5e7eb;
=======
            border: 1px solid #e9ecef;
>>>>>>> 1f7b50d32c5c8f031a319939d390a458ad4b1e45
        }
        .card {
            transition: transform 0.2s ease;
        }
        .card:hover {
            transform: translateY(-2px);
        }
        .modal-body {
            max-height: 80vh;
            overflow-y: auto;
        }
        </style>';

	echo $data;
?>
