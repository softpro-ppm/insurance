<?php  
	include 'session.php';
	include 'config.php';

	$sql = mysqli_query($con, "select * from policy where id='".$_POST['id']."'");
	$r=mysqli_fetch_array($sql);

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

	$data = 
		'<div class="modal-header bg-gradient-primary text-white border-0">
            <h5 class="modal-title d-flex align-items-center" id="transaction-detailModalLabel">
                <i class="bx bx-file-blank me-2"></i>
                <span>Policy Details - <strong>'.$r['vehicle_number'].'</strong></span>
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
            
            <!-- Customer & Vehicle Info Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="bx bx-user me-2"></i>Customer & Vehicle Information</h6>
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
                                <label class="info-label">Phone Number:</label>
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

            <!-- Policy Details Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="bx bx-shield me-2"></i>Policy Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="info-label">Insurance Company:</label>
                                <div class="info-value">'.$r['insurance_company'].'</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="info-label">Policy Type:</label>
                                <div class="info-value">'.$r['policy_type'].'</div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="info-item">
                                <label class="info-label">Policy Start Date:</label>
                                <div class="info-value">'.date('d-m-Y', strtotime($r['policy_start_date'])).'</div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="info-item">
                                <label class="info-label">Policy End Date:</label>
                                <div class="info-value">'.date('d-m-Y', strtotime($r['policy_end_date'])).'</div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="info-item">
                                <label class="info-label">Policy Issue Date:</label>
                                <div class="info-value">'.date('d-m-Y',strtotime($r['policy_issue_date'])).'</div>
                            </div>
                        </div>';
                        
        if ($fc_expiry_date || $permit_expiry_date) {
            $data .= '<div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="info-label">FC Expiry Date:</label>
                                <div class="info-value">'.$fc_expiry_date.'</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <label class="info-label">Permit Expiry Date:</label>
                                <div class="info-value">'.$permit_expiry_date.'</div>
                            </div>
                        </div>';
        }
        
        $data .= '</div>
                </div>
            </div>

            <!-- Financial Details Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="bx bx-money me-2"></i>Financial Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="info-item">
                                <label class="info-label">Premium Amount:</label>
                                <div class="info-value text-primary">₹'.number_format($r['premium'], 2).'</div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="info-item">
                                <label class="info-label">Revenue:</label>
                                <div class="info-value text-success">₹'.number_format($r['revenue'], 2).'</div>
                            </div>
                        </div>';
                        
        // Show new financial fields if available
        if (isset($r['payout']) && $r['payout'] > 0) {
            $data .= '<div class="col-md-4 mb-3">
                            <div class="info-item">
                                <label class="info-label">Payout:</label>
                                <div class="info-value">₹'.number_format($r['payout'], 2).'</div>
                            </div>
                        </div>';
        }
        if (isset($r['customer_paid']) && $r['customer_paid'] > 0) {
            $data .= '<div class="col-md-4 mb-3">
                            <div class="info-item">
                                <label class="info-label">Customer Paid:</label>
                                <div class="info-value">₹'.number_format($r['customer_paid'], 2).'</div>
                            </div>
                        </div>';
        }
        if (isset($r['discount']) && $r['discount'] > 0) {
            $data .= '<div class="col-md-4 mb-3">
                            <div class="info-item">
                                <label class="info-label">Discount:</label>
                                <div class="info-value">₹'.number_format($r['discount'], 2).'</div>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
            
        // Comments section if exists
        $comments_sql = mysqli_query($con, "select * from comments where policy_id='".$r['id']."'");
        if (mysqli_num_rows($comments_sql) > 0) {
            $data .= '<div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-secondary text-white">
                            <h6 class="mb-0"><i class="bx bx-comment me-2"></i>Comments</h6>
                        </div>
                        <div class="card-body">';
            while ($comment_row = mysqli_fetch_array($comments_sql)) {
                $data .= '<div class="alert alert-light border">'.$comment_row['comments'].'</div>';
            }
            $data .= '</div>
                    </div>';
        }
        
        // Additional info if chassis number exists
        if (!empty($r['chassiss'])) {
            $data .= '<div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-dark text-white">
                            <h6 class="mb-0"><i class="bx bx-cog me-2"></i>Additional Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="info-item">
                                <label class="info-label">Chassis Number:</label>
                                <div class="info-value">'.$r['chassiss'].'</div>
                            </div>
                        </div>
                    </div>';
        }
        
        $data .= '</div>
        <div class="modal-footer border-0" style="background: linear-gradient(135deg, #f6f9fc 0%, #e9ecef 100%);">
            <button type="button" class="btn btn-primary btn-lg" onclick="openEditFromView('.$r['id'].')">
                <i class="bx bx-edit me-2"></i>Edit Policy
            </button>
            <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">
                <i class="bx bx-x me-2"></i>Close
            </button>
        </div>

        <script>
        // Function to open edit modal from view modal
        function openEditFromView(policyId) {
            // Close the view modal first
            $("#renewalpolicyview").modal("hide");
            
            // Wait for the view modal to close completely, then open edit modal
            setTimeout(function() {
                loadPolicyForEdit(policyId);
            }, 300);
        }
        </script>

        <style>
        .info-item {
            margin-bottom: 1rem;
        }
        .info-label {
            font-weight: 600;
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
            display: block;
        }
        .info-value {
            font-weight: 500;
            color: #333;
            background: #f8f9fa;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            border: 1px solid #e9ecef;
        }
        .bg-gradient-primary {
            background: linear-gradient(135deg, #6f42c1 0%, #007bff 100%) !important;
        }
        .card {
            transition: transform 0.2s ease;
        }
        .card:hover {
            transform: translateY(-2px);
        }
        </style>';

		echo $data;
?>