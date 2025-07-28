<?php 
    include 'include/session.php';
    include 'include/config.php'; 
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Policies | Softpro Insurance</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <link rel="shortcut icon" href="assets/logo.PNG">
    
    <!-- Bootstrap 5.1.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- BoxIcons -->
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- DataTables Bootstrap 5 CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
    
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
                    <!-- Page Title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0 font-size-18">Policy Management</h4>
                                <div class="page-title-right">
                                    <button type="button" class="btn btn-success waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#addPolicyModal">
                                        <i class="bx bx-plus me-2"></i>Add New Policy
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Policy Table -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">All Policies</h4>
                                    <p class="card-title-desc">Manage client policies with document verification</p>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="policyDataTable" class="table table-striped table-bordered dt-responsive nowrap w-100">
                                            <thead>
                                                <tr>
                                                    <th>Sr. No.</th>
                                                    <th>Vehicle Number</th>
                                                    <th>Client Name</th>
                                                    <th>Phone</th>
                                                    <th>Vehicle Type</th>
                                                    <th>Policy Type</th>
                                                    <th>Insurance Company</th>
                                                    <th>Premium</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Get policies in descending order (latest first)
                                                $sql = "SELECT * FROM policy ORDER BY id DESC";
                                                $rs = mysqli_query($con, $sql);
                                                
                                                if(mysqli_num_rows($rs) > 0) {
                                                    while ($r = mysqli_fetch_array($rs)) {
                                                        $fc_expiry_date = !empty($r['fc_expiry_date']) ? date('d-m-Y', strtotime($r['fc_expiry_date'])) : '';
                                                        $permit_expiry_date = !empty($r['permit_expiry_date']) ? date('d-m-Y', strtotime($r['permit_expiry_date'])) : '';
                                                ?>
                                                <tr data-policy-id="<?=$r['id']?>">
                                                    <td></td> <!-- Sr. No. will be handled by DataTables -->
                                                    <td>
                                                        <a href="javascript:void(0);" class="text-body fw-bold" onclick="viewPolicy(<?=$r['id']?>)">
                                                            <?=$r['vehicle_number']?>
                                                        </a>
                                                    </td>
                                                    <td><?=$r['name']?></td>
                                                    <td><?=$r['phone']?></td>
                                                    <td><?=$r['vehicle_type']?></td>
                                                    <td>
                                                        <span class="badge bg-<?= $r['policy_type'] == 'Comprehensive' ? 'success' : 'warning' ?>">
                                                            <?=$r['policy_type']?>
                                                        </span>
                                                    </td>
                                                    <td><?=$r['insurance_company']?></td>
                                                    <td>₹<?=number_format($r['premium'], 2)?></td>
                                                    <td><?=date('d-m-Y', strtotime($r['policy_start_date']))?></td>
                                                    <td><?=date('d-m-Y', strtotime($r['policy_end_date']))?></td>
                                                    <td class="action-buttons">
                                                        <div class="btn-group" role="group">
                                                            <button type="button" class="btn btn-outline-primary btn-sm" 
                                                                    onclick="loadPolicyForEdit(<?=$r['id']?>)" 
                                                                    title="Edit Policy" data-bs-toggle="tooltip">
                                                                <i class="bx bx-edit"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-outline-danger btn-sm" 
                                                                    onclick="showDeleteConfirmation(<?=$r['id']?>, '<?=addslashes($r['name'])?>', '<?=addslashes($r['vehicle_number'])?>')" 
                                                                    title="Delete Policy" data-bs-toggle="tooltip">
                                                                <i class="bx bx-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                                    }
                                                } else {
                                                ?>
                                                <tr>
                                                    <td colspan="11" class="text-center">No policies found</td>
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

            <footer class="footer">
                <?php require 'include/footer.php'; ?>
            </footer>
        </div>
    </div>

    <!-- Include Modals -->
    <?php include 'include/modals/add-policy-modal.php'; ?>
    <?php include 'include/modals/edit-policy-modal.php'; ?>
    <?php include 'include/modals/delete-policy-modal.php'; ?>

    <!-- JavaScript Libraries -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 5.1.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    
    <!-- Export libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

    <!-- Global Policy Management JavaScript -->
    <script src="assets/js/global.js"></script>

    <script>
        // Initialize page when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>
</html>
