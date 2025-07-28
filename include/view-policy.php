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
		'<div class="modal-header bg-primary text-white border-0">
            <h5 class="modal-title d-flex align-items-center" id="transaction-detailModalLabel">
                <i class="bx bx-file-blank mr-2"></i>
                <span>Policy Details - <strong>'.$r['vehicle_number'].'</strong></span>
            </h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body p-4" style="background: #ffffff; color: #1f2937;">
            
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
                                <div class="info-value text-danger fw-bold">'.date('d-m-Y', strtotime($r['policy_end_date'])).'</div>
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
                                <div class="info-value text-success fw-bold">₹ '.number_format($r['premium'], 2).'</div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="info-item">
                                <label class="info-label">Revenue:</label>
                                <div class="info-value text-primary fw-bold">₹ '.number_format($r['revenue'], 2).'</div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="info-item">
                                <label class="info-label">Payment Mode:</label>
                                <div class="info-value">'.$r['payment_mode'].'</div>
                            </div>
                        </div>
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
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="info-item">
                                <label class="info-label">Remarks:</label>
                                <div class="info-value">'.$r['remarks'].'</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
            border: 1px solid #e5e7eb;
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
