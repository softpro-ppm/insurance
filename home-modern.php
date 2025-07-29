<?php 
    include 'include/session.php';
    include 'include/config.php'; 
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Dashboard | Softpro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/logo.PNG">
    
    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- DataTables Bootstrap 5 CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@latest"></script>
    
    <!-- Additional CSS -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    
    <style>
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        
        .dashboard-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.25);
        }
        
        .modern-card-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.25);
            transition: all 0.3s ease;
            color: white;
        }
        
        .modern-card-success {
            background: linear-gradient(135deg, #5ee7df 0%, #66a6ff 100%);
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(94, 231, 223, 0.25);
            transition: all 0.3s ease;
            color: white;
        }
        
        .modern-card-warning {
            background: linear-gradient(135deg, #ffc107 0%, #ff8a00 100%);
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(255, 193, 7, 0.25);
            transition: all 0.3s ease;
            color: white;
        }
        
        .modern-card-danger {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(255, 107, 107, 0.25);
            transition: all 0.3s ease;
            color: white;
        }
        
        .modern-card-info:hover,
        .modern-card-success:hover,
        .modern-card-warning:hover,
        .modern-card-danger:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        
        .modern-icon-bg {
            background: rgba(255, 255, 255, 0.2) !important;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modern-icon-bg i {
            color: white !important;
            font-size: 24px;
        }
        
        .modern-card-info a,
        .modern-card-success a,
        .modern-card-warning a,
        .modern-card-danger a {
            color: white !important;
            text-decoration: none;
        }
        
        .modern-card-info a:hover,
        .modern-card-success a:hover,
        .modern-card-warning a:hover,
        .modern-card-danger a:hover {
            color: rgba(255, 255, 255, 0.9) !important;
        }
        
        .modern-chart-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            background: white;
        }
        
        .modern-chart-card:hover {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }
        
        .modern-chart-card .card-header {
            border-bottom: 1px solid #f0f0f0;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px 15px 0 0;
            padding: 20px;
        }
        
        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0;
        }
        
        .stats-label {
            font-size: 1rem;
            font-weight: 500;
            opacity: 0.9;
            margin-bottom: 5px;
        }
        
        .data-table-container {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }
        
        .btn-action {
            margin: 2px;
            padding: 5px 10px;
            font-size: 12px;
        }
        
        .table-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .status-badge {
            font-size: 11px;
            padding: 4px 8px;
        }
        
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
            
            .stats-number {
                font-size: 2rem;
            }
            
            .dashboard-header {
                padding: 20px;
            }
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
                    
                    <!-- Dashboard Header -->
                    <div class="dashboard-header">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h3 class="mb-2">Insurance Management Dashboard   ttt</h3>
                                <p class="mb-0 opacity-75">Welcome back! Here's what's happening with your insurance policies today.</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <button type="button" class="btn btn-light btn-lg" data-bs-toggle="modal" data-bs-target="#addPolicyModal">
                                    <i class="fas fa-plus me-2"></i>Add New Policy
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Statistics Cards -->
                    <div class="row g-4 mb-4">
                        <?php  
                            $sql1 = mysqli_query($con, "select * from policy where month(policy_issue_date) ='".date('m')."' and year(policy_issue_date)='".date('Y')."'  ");
                            $totalpolicy = mysqli_num_rows($sql1);
                        ?>
                        <div class="col-md-3">
                            <div class="card modern-card-info h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <a href="policies.php?latest=latest" class="text-decoration-none">     
                                                <p class="stats-label mb-2">Total Policies</p>
                                                <h4 class="stats-number"><?=$totalpolicy?></h4>
                                                <small class="opacity-75">This month</small>
                                            </a>    
                                        </div>
                                        <div class="modern-icon-bg">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php  
                            $totalpremium = 0;
                            $sql3 = mysqli_query($con, "select * from policy where month(policy_issue_date) ='".date('m')."' and year(policy_issue_date)='".date('Y')."' ");
                            while($totalpremiumr = mysqli_fetch_array($sql3)){
                                $totalpremium +=$totalpremiumr['premium'];
                            }
                        ?>
                        <div class="col-md-3">
                            <div class="card modern-card-success h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <p class="stats-label mb-2">Total Premium</p>
                                            <h4 class="stats-number">₹<?=number_format($totalpremium)?></h4>
                                            <small class="opacity-75">This month</small>
                                        </div>
                                        <div class="modern-icon-bg">
                                            <i class="fas fa-rupee-sign"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php  
                            $renewalsql = mysqli_query($con, "select * from policy where month(policy_end_date) = '".date("m")."' and year(policy_end_date) = '".date("Y")."'");
                            $renewaltotal = mysqli_num_rows($renewalsql);
                        ?>
                        <div class="col-md-3">
                            <div class="card modern-card-warning h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <a href="manage-renewal.php?renewal=renewal" class="text-decoration-none">
                                                <p class="stats-label mb-2">Total Renewals</p>
                                                <h4 class="stats-number"><?=$renewaltotal?></h4>
                                                <small class="opacity-75">This month</small>
                                            </a>
                                        </div>
                                        <div class="modern-icon-bg">
                                            <i class="fas fa-sync-alt"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php  
                            $pendingrenewalsql = mysqli_query($con, "select * from policy where month(policy_end_date) = '".date("m")."' and year(policy_end_date) = '".date("Y")."' and policy_end_date <= '".date("Y-m-d")."'");
                            $pendingrenewaltotal = mysqli_num_rows($pendingrenewalsql);
                        ?>
                        <div class="col-md-3">
                            <div class="card modern-card-danger h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <a href="manage-renewal.php?pending=pending" class="text-decoration-none">
                                                <p class="stats-label mb-2">Pending Renewals</p>
                                                <h4 class="stats-number"><?=$pendingrenewaltotal?></h4>
                                                <small class="opacity-75">Overdue</small>
                                            </a>
                                        </div>
                                        <div class="modern-icon-bg">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Analytics Chart -->
                    <div class="row g-4 mb-4">
                        <div class="col-12">
                            <div class="card modern-chart-card">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h4 class="card-title mb-0">
                                            <i class="fas fa-chart-line me-2"></i>Monthly Analytics
                                        </h4>
                                        <div>
                                            <select class="form-select" id="year" style="min-width: 150px;">
                                                <option value="">Select Year</option>
                                                <?php for($y = 2019; $y <= 2025; $y++): ?>
                                                <option value="<?=$y?>" <?=($_GET['year'] == $y ? 'selected' : '')?>>
                                                    FY <?=$y?>-<?=($y+1)?>
                                                </option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="modern-analytics-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Distribution Charts -->
                    <div class="row g-4 mb-4">
                        <div class="col-xl-6">
                            <div class="card modern-chart-card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-chart-pie me-2"></i>Policy Types Distribution
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div id="policy_type_chart"></div>  
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-6">
                            <div class="card modern-chart-card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-car me-2"></i>Vehicle Types Distribution
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div id="vehicle_chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Renewal Table -->
                    <div class="row">
                        <div class="col-12">
                            <div class="data-table-container">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h4 class="mb-0">
                                        <i class="fas fa-clock me-2"></i>Renewal Management
                                    </h4>
                                    <div>
                                        <button class="btn btn-outline-primary btn-sm me-2">
                                            <i class="fas fa-download me-2"></i>Export
                                        </button>
                                        <button class="btn btn-primary btn-sm">
                                            <i class="fas fa-refresh me-2"></i>Refresh
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="table-responsive">
                                    <table id="renewalTable" class="table table-striped table-hover" style="width:100%">
                                        <thead class="table-header">
                                            <tr>
                                                <th>Sr. No.</th>
                                                <th>Vehicle Number</th>
                                                <th>Client Name</th>
                                                <th>Phone</th>
                                                <th>Vehicle Type</th>
                                                <th>Policy End Date</th>
                                                <th>Days Left</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php  
                                                $sn = 1;
                                                $renewalsql = mysqli_query($con, "select * from policy where month(policy_end_date)='".date('m')."' and year(policy_end_date)='".date('Y')."' ORDER BY policy_end_date ASC");
                                                if(mysqli_num_rows($renewalsql) > 0 ){
                                                    while($renewalr = mysqli_fetch_array($renewalsql)){
                                                        $endDate = new DateTime($renewalr['policy_end_date']);
                                                        $today = new DateTime();
                                                        $diffDays = $today->diff($endDate)->days;
                                                        $isPastDue = $today > $endDate;
                                                        
                                                        if ($isPastDue) {
                                                            $status = 'Expired';
                                                            $statusClass = 'bg-danger';
                                                            $daysText = $diffDays . ' days overdue';
                                                        } elseif ($diffDays <= 7) {
                                                            $status = 'Critical';
                                                            $statusClass = 'bg-warning';
                                                            $daysText = $diffDays . ' days left';
                                                        } else {
                                                            $status = 'Upcoming';
                                                            $statusClass = 'bg-info';
                                                            $daysText = $diffDays . ' days left';
                                                        }
                                            ?>
                                            <tr>
                                                <td><?=$sn?></td>
                                                <td>
                                                    <a href="javascript:void(0);" class="text-primary fw-bold" onclick="viewpolicy(this)" data-id="<?=$renewalr['id']?>">
                                                        <?=$renewalr['vehicle_number']?>
                                                    </a>
                                                </td>
                                                <td><?=$renewalr['name']?></td>
                                                <td><?=$renewalr['phone']?></td>
                                                <td><?=$renewalr['vehicle_type']?></td>
                                                <td><?=date('d-m-Y', strtotime($renewalr['policy_end_date']))?></td>
                                                <td><?=$daysText?></td>
                                                <td>
                                                    <span class="badge status-badge <?=$statusClass?>"><?=$status?></span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-outline-primary btn-action" onclick="viewpolicy(this)" data-id="<?=$renewalr['id']?>" title="View Policy">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-warning btn-action" onclick="loadPolicyForEdit(<?=$renewalr['id']?>)" title="Edit Policy">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-success btn-action" onclick="renewPolicy(<?=$renewalr['id']?>)" title="Renew Policy">
                                                            <i class="fas fa-sync-alt"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php $sn++; } } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div> <!-- container-fluid -->
            </div> <!-- page-content -->
            
            <!-- Footer -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <script>document.write(new Date().getFullYear())</script> © Softpro.
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end d-none d-sm-block">
                                Design & Develop by Softpro
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div> <!-- main-content -->
    </div> <!-- layout-wrapper -->

    <!-- Policy View Modal -->
    <div class="modal fade" id="renewalpolicyview" tabindex="-1" aria-labelledby="renewalpolicyviewLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div id="viewpolicydata">
                    <!-- Policy data will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <script src="assets/js/app.js"></script>

    <?php  
        $totaldata = array();
        $totalpremium = array();
        $totalrevenue = array();
        $monthname = array();
        
        if(!empty($_GET['year'])){
            $fyYear = $_GET['year'];
            $fyStartYear = $fyYear;
            $fyEndYear = $fyYear + 1;
            
            $fyMonths = [
                ['year' => $fyStartYear, 'month' => '04', 'name' => 'Apr ' . $fyStartYear],
                ['year' => $fyStartYear, 'month' => '05', 'name' => 'May ' . $fyStartYear],
                ['year' => $fyStartYear, 'month' => '06', 'name' => 'Jun ' . $fyStartYear],
                ['year' => $fyStartYear, 'month' => '07', 'name' => 'Jul ' . $fyStartYear],
                ['year' => $fyStartYear, 'month' => '08', 'name' => 'Aug ' . $fyStartYear],
                ['year' => $fyStartYear, 'month' => '09', 'name' => 'Sep ' . $fyStartYear],
                ['year' => $fyStartYear, 'month' => '10', 'name' => 'Oct ' . $fyStartYear],
                ['year' => $fyStartYear, 'month' => '11', 'name' => 'Nov ' . $fyStartYear],
                ['year' => $fyStartYear, 'month' => '12', 'name' => 'Dec ' . $fyStartYear],
                ['year' => $fyEndYear, 'month' => '01', 'name' => 'Jan ' . $fyEndYear],
                ['year' => $fyEndYear, 'month' => '02', 'name' => 'Feb ' . $fyEndYear],
                ['year' => $fyEndYear, 'month' => '03', 'name' => 'Mar ' . $fyEndYear]
            ];
            
            foreach($fyMonths as $monthData) {
                $monthsql = mysqli_query($con, "SELECT COUNT(*) as total, SUM(premium) as totalpremium, SUM(revenue) as totalrevenue FROM policy WHERE YEAR(policy_issue_date) = '".$monthData['year']."' AND MONTH(policy_issue_date) = '".$monthData['month']."'");
                $monthr = mysqli_fetch_array($monthsql);
                
                $totaldata[] = $monthr['total'] ? $monthr['total'] : 0;
                $totalpremium[] = $monthr['totalpremium'] ? $monthr['totalpremium'] : 0;
                $totalrevenue[] = $monthr['totalrevenue'] ? $monthr['totalrevenue'] : 0;
                $monthname[] = $monthData['name'];
            }
        } else {
            for($i = 11; $i >= 0; $i--) {
                $targetDate = date('Y-m', strtotime("-$i months"));
                $year = date('Y', strtotime("-$i months"));
                $month = date('m', strtotime("-$i months"));
                
                $monthsql = mysqli_query($con, "SELECT COUNT(*) as total, SUM(premium) as totalpremium, SUM(revenue) as totalrevenue FROM policy WHERE YEAR(policy_issue_date) = '$year' AND MONTH(policy_issue_date) = '$month'");
                $monthr = mysqli_fetch_array($monthsql);
                
                $totaldata[] = $monthr['total'] ? $monthr['total'] : 0;
                $totalpremium[] = $monthr['totalpremium'] ? $monthr['totalpremium'] : 0;
                $totalrevenue[] = $monthr['totalrevenue'] ? $monthr['totalrevenue'] : 0;
                $monthname[] = date('M Y', strtotime("-$i months"));
            }
        }

        $annualdata = array();
        $annualname = array();
        if(!empty($_GET['year'])){
            $annualsql = mysqli_query($con, "SELECT COUNT(*) as total, vehicle_type FROM `policy` where year(policy_issue_date)='".$_GET['year']."' GROUP BY vehicle_type");
        }else{
            $annualsql = mysqli_query($con, "SELECT COUNT(*) as total, vehicle_type FROM `policy` where year(policy_issue_date)='".date('Y')."' GROUP BY vehicle_type");
        }
        while ($annualr = mysqli_fetch_array($annualsql)) {
            $annualdata[] = $annualr['total'];
            $annualname[] = $annualr['vehicle_type'];
        }

        $policytypedata = array();
        $policytypename = array();
        if(!empty($_GET['year'])){
            $policytypesql = mysqli_query($con, "SELECT COUNT(*) as total, policy_type FROM `policy` where year(policy_issue_date)='".$_GET['year']."' GROUP BY policy_type");
        }else{
            $policytypesql = mysqli_query($con, "SELECT COUNT(*) as total, policy_type FROM `policy` where year(policy_issue_date)='".date('Y')."' GROUP BY policy_type");
        }
        while ($policytyper = mysqli_fetch_array($policytypesql)) {
            $policytypedata[] = $policytyper['total'];
            $policytypename[] = $policytyper['policy_type'];
        }
    ?>

    <script>
        $(document).ready(function() {
            // Initialize DataTable for renewal table
            let renewalTable = $('#renewalTable').DataTable({
                "order": [[5, "asc"]], // Sort by Policy End Date ascending
                "pageLength": 10,
                "lengthMenu": [[10, 30, 50, 100, -1], [10, 30, 50, 100, "All"]],
                "responsive": true,
                "columnDefs": [{
                    "targets": -1,
                    "orderable": false
                }],
                "language": {
                    "search": "Search renewals:",
                    "lengthMenu": "Show _MENU_ renewals per page",
                    "info": "Showing _START_ to _END_ of _TOTAL_ renewals",
                    "paginate": {
                        "first": "First",
                        "last": "Last",
                        "next": "Next",
                        "previous": "Previous"
                    }
                }
            });

            // Year filter change
            $('#year').on('change', function() {
                if ($(this).val()) {
                    window.location.href = 'home.php?year=' + $(this).val();
                }
            });
        });

        // Modern Analytics Chart
        var analyticsOptions = {
            series: [{
                name: 'Premium',
                data: [<?=implode(',', $totalpremium)?>],
                type: 'column'
            }, {
                name: 'Revenue',
                data: [<?=implode(',', $totalrevenue)?>],
                type: 'line'
            }],
            chart: {
                height: 350,
                type: 'line',
                toolbar: { show: true },
                animations: { enabled: true, easing: 'easeinout', speed: 800 }
            },
            colors: ['#667eea', '#764ba2'],
            plotOptions: {
                bar: { borderRadius: 8, columnWidth: '60%' }
            },
            stroke: { width: [0, 4], curve: 'smooth' },
            dataLabels: { enabled: false },
            labels: <?=json_encode($monthname)?>,,
            xaxis: {
                categories: <?=json_encode($monthname)?>,
                labels: { style: { colors: '#8e8da4' } }
            },
            yaxis: [{
                title: { text: 'Premium Amount (₹)', style: { color: '#667eea' } },
                labels: {
                    style: { colors: '#667eea' },
                    formatter: function (val) { return "₹" + val; }
                }
            }, {
                opposite: true,
                title: { text: 'Revenue Amount (₹)', style: { color: '#764ba2' } },
                labels: {
                    style: { colors: '#764ba2' },
                    formatter: function (val) { return "₹" + val; }
                }
            }],
            legend: { position: 'top', horizontalAlign: 'right', floating: true, offsetY: -25, offsetX: -5 },
            grid: { borderColor: '#f1f1f1', strokeDashArray: 3 },
            tooltip: {
                shared: true,
                intersect: false,
                y: [{
                    formatter: function (y) {
                        return typeof y !== "undefined" ? "₹" + y.toFixed(0) : y;
                    }
                }, {
                    formatter: function (y) {
                        return typeof y !== "undefined" ? "₹" + y.toFixed(0) : y;
                    }
                }]
            }
        };
        
        new ApexCharts(document.querySelector("#modern-analytics-chart"), analyticsOptions).render();

        // Vehicle Types Chart
        var vehicleOptions = {
            chart: { height: 350, type: "bar", toolbar: { show: false } },
            plotOptions: {
                bar: { horizontal: true, borderRadius: 8, barHeight: '70%' }
            },
            dataLabels: { enabled: true, style: { fontSize: '12px', fontWeight: 'bold' } },
            series: [{ data: <?=json_encode($annualdata)?> }],
            colors: ["#667eea", "#764ba2", "#5ee7df", "#66a6ff", "#ffc107", "#ff8a00", "#ff6b6b", "#ee5a52"],
            grid: { borderColor: "#f1f1f1" },
            xaxis: { categories: <?=json_encode($annualname)?> },
            tooltip: {
                y: { formatter: function (val) { return val + " vehicles"; } }
            }
        };
        new ApexCharts(document.querySelector("#vehicle_chart"), vehicleOptions).render();

        // Policy Types Chart
        var policyTypeOptions = {
            chart: { height: 370, type: "donut" },
            plotOptions: {
                pie: {
                    donut: { size: '70%' },
                    startAngle: -90,
                    endAngle: 90
                }
            },
            dataLabels: {
                enabled: true,
                style: { fontWeight: 'bold' },
                dropShadow: { enabled: false }
            },
            series: <?=json_encode($policytypedata)?>,
            labels: <?=json_encode($policytypename)?>,
            colors: ["#667eea", "#764ba2", "#5ee7df", "#66a6ff", "#ffc107", "#ff8a00", "#ff6b6b", "#ee5a52"],
            legend: {
                position: 'bottom',
                horizontalAlign: 'center',
                fontSize: '14px',
                markers: { width: 12, height: 12, radius: 12 }
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: { height: 300 },
                    legend: { position: 'bottom' }
                }
            }],
            tooltip: {
                y: { formatter: function (val) { return val + " policies"; } }
            }
        };
        new ApexCharts(document.querySelector("#policy_type_chart"), policyTypeOptions).render();

        // View Policy Function
        function viewpolicy(identifier) {
            var id = $(identifier).data("id");
            
            $('#viewpolicydata').html(`
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title">
                        <i class="fas fa-file-alt me-2"></i>Loading Policy Details...
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3">Please wait while we fetch the policy details...</p>
                </div>
            `);
            
            $('#renewalpolicyview').modal("show");
            
            $.ajax({
                url: "include/view-policy.php",
                type: "POST",
                data: { id: id },
                timeout: 10000,
                success: function(data) {
                    if (data && data.trim().length > 0) {
                        $('#viewpolicydata').html(data);
                    } else {
                        $('#viewpolicydata').html(`
                            <div class="modal-header bg-danger text-white border-0">
                                <h5 class="modal-title">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Error
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    No data received from server. Please try again.
                                </div>
                            </div>
                        `);
                    }
                },
                error: function(xhr, status, error) {
                    $('#viewpolicydata').html(`
                        <div class="modal-header bg-danger text-white border-0">
                            <h5 class="modal-title">
                                <i class="fas fa-exclamation-triangle me-2"></i>Connection Error
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <strong>Error loading policy details:</strong><br>
                                ${error === 'timeout' ? 'Request timed out. Please try again.' : 'Connection error. Please check your internet connection and try again.'}
                            </div>
                        </div>
                    `);
                }
            });
        }

        // Load Policy for Edit Function
        function loadPolicyForEdit(policyId) {
            // This will be implemented when the edit modal is included
            console.log('Edit policy:', policyId);
        }

        // Renew Policy Function
        function renewPolicy(policyId) {
            // This will be implemented for renewal functionality
            console.log('Renew policy:', policyId);
        }
    </script>

    <?php include 'include/add-policy-modal.php'; ?>
    <?php include 'include/edit-policy-modal.php'; ?>

</body>
</html>
