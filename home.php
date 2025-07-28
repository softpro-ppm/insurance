<?php 
    include 'include/session.php';
    include 'include/config.php'; 
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Dashboard | Softpro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/logo.PNG">
    <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <!-- Modern theme -->
    <link href="assets/css/modern-theme.css" rel="stylesheet" type="text/css" />
    <!-- Autofill override styles -->
    <link href="assets/css/autofill-override.css" rel="stylesheet" type="text/css" />
    <!-- Custom color scheme for professional appearance -->
    <link href="assets/css/custom-colors.css" rel="stylesheet" type="text/css" />
    <!-- Modal fixes -->
    <link href="assets/css/modal-fixes.css" rel="stylesheet" type="text/css" />
    
    <!-- Modern Dashboard Card Styles -->
    <style>
        .modern-card-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.25);
            transition: all 0.3s ease;
        }
        
        .modern-card-success {
            background: linear-gradient(135deg, #5ee7df 0%, #66a6ff 100%);
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(94, 231, 223, 0.25);
            transition: all 0.3s ease;
        }
        
        .modern-card-warning {
            background: linear-gradient(135deg, #ffc107 0%, #ff8a00 100%);
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(255, 193, 7, 0.25);
            transition: all 0.3s ease;
        }
        
        .modern-card-danger {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(255, 107, 107, 0.25);
            transition: all 0.3s ease;
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
        }
        
        .modern-icon-bg .avatar-title {
            background: transparent !important;
            color: white !important;
        }
        
        .modern-card-info a,
        .modern-card-success a,
        .modern-card-warning a,
        .modern-card-danger a {
            color: white !important;
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
        }
        
        .modern-chart-card:hover {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        }
        
        .modern-chart-card .card-header {
            border-bottom: 1px solid #f0f0f0;
            background: #fafafa;
            border-radius: 15px 15px 0 0;
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
                    <div class="row text-right" >
                        <div class="col-xl-12 " >
                            <button type="button" style="float: right;margin-bottom: 15px;" class="btn btn-primary" data-toggle="modal" data-target="#addPolicyModal" onclick="testAddPolicyModal()">
                                <i class="bx bx-plus-circle"></i> Add New Policy
                            </button>
                        </div>
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="row">
                                <?php  
                                    $sql1 = mysqli_query($con, "select * from policy where month(policy_issue_date) ='".date('m')."' and year(policy_issue_date)='".date('Y')."'  ");
                                    $totalpolicy = mysqli_num_rows($sql1);
                                ?>
                                <div class="col-md-3">
                                    <div class="card mini-stats-wid modern-card-info">
                                        <div class="card-body">
                                            <div class="d-flex">
                                                <div class="flex-grow-1">
                                                    <a href="policies.php?latest=latest" class="text-decoration-none">     
                                                        <p class="text-white font-weight-normal mb-2">Policies</p>
                                                        <h4 class="mb-0 text-white font-weight-bold"><?=$totalpolicy?></h4>
                                                    </a>    
                                                </div>
                                                <div class="flex-shrink-0 align-self-center">
                                                    <div class="mini-stat-icon avatar-sm rounded-circle modern-icon-bg"> 
                                                        <span class="avatar-title">
                                                            <i class="bx bx-copy-alt font-size-24"></i>
                                                        </span>
                                                    </div>
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
                                    <div class="card mini-stats-wid modern-card-success">
                                        <div class="card-body">
                                            <div class="d-flex">
                                                <div class="flex-grow-1">
                                                    <p class="text-white font-weight-normal mb-2">Premium</p>
                                                    <h4 class="mb-0 text-white font-weight-bold">&#8377;<?=$totalpremium;?></h4>
                                                </div>
                                                <div class="flex-shrink-0 align-self-center ">
                                                    <div class="avatar-sm rounded-circle modern-icon-bg"> 
                                                        <span class="avatar-title rounded-circle">
                                                            <i class="bx bx-archive-in font-size-24"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php  
                                    // Total Renewal = Policies expiring this month (July 2025)
                                    $renewalsql = mysqli_query($con, "select * from policy where month(policy_end_date) = '".date("m")."' and year(policy_end_date) = '".date("Y")."'");
                                    $renewaltotal = mysqli_num_rows($renewalsql);
                                ?>
                                <div class="col-md-3">
                                    <div class="card mini-stats-wid modern-card-warning">
                                        <div class="card-body">
                                            <div class="d-flex">
                                                <div class="flex-grow-1">
                                                    <a href="manage-renewal.php?renewal=renewal" class="text-decoration-none">
                                                        <p class="text-white font-weight-normal mb-2">Total Renewal</p>
                                                        <h4 class="mb-0 text-white font-weight-bold"><?=$renewaltotal;?></h4>
                                                    </a>
                                                </div>
                                                <div class="flex-shrink-0 align-self-center">
                                                    <div class="avatar-sm rounded-circle modern-icon-bg"> 
                                                        <span class="avatar-title rounded-circle">
                                                            <i class="bx bx-purchase-tag-alt font-size-24"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php  
                                    // Pending Renewal = Policies that have already expired in July 2025 (up to today's date)
                                    // These are policies with end_date <= today's date in July 2025
                                    $pendingrenewalsql = mysqli_query($con, "select * from policy where month(policy_end_date) = '".date("m")."' and year(policy_end_date) = '".date("Y")."' and policy_end_date <= '".date("Y-m-d")."'");
                                    $pendingrenewaltotal = mysqli_num_rows($pendingrenewalsql);
                                ?>
                                <div class="col-md-3">
                                    <div class="card mini-stats-wid modern-card-danger">
                                        <div class="card-body">
                                            <div class="d-flex">
                                                <div class="flex-grow-1">
                                                    <a href="manage-renewal.php?pending=pending" class="text-decoration-none">
                                                        <p class="text-white font-weight-normal mb-2">Pending Renewal</p>
                                                        <h4 class="mb-0 text-white font-weight-bold"><?=$pendingrenewaltotal?></h4>
                                                    </a>
                                                </div>
                                                <div class="flex-shrink-0 align-self-center">
                                                    <div class="avatar-sm rounded-circle modern-icon-bg"> 
                                                        <span class="avatar-title rounded-circle">
                                                            <i class="bx bx-purchase-tag-alt font-size-24"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end row -->
                            
                            <!--Bar chart start-->
                            
                            <div class="card modern-chart-card">
                              <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="card-title mb-0">Monthly Analytics</h4>
                                    <div class="ml-auto">
                                        <select class="form-control" id="year" style="min-width: 120px;">
                                            <option>Select Year</option>
                                            <?php if($_GET['year'] == '2025'){ ?>
                                            <option selected value="2025" >FY 2025-26</option>
                                            <?php }else{ ?>
                                            <option value="2025" >FY 2025-26</option>
                                            <?php } ?>
                                            <?php if($_GET['year'] == '2024'){ ?>
                                            <option selected value="2024" >FY 2024-25</option>
                                            <?php }else{ ?>
                                            <option value="2024" >FY 2024-25</option>
                                            <?php } ?>
                                            <?php if($_GET['year'] == '2023'){ ?>
                                            <option selected value="2023" >FY 2023-24</option>
                                            <?php }else{ ?>
                                            <option value="2023" >FY 2023-24</option>
                                            <?php } ?>
                                            <?php if($_GET['year'] == '2022'){ ?>
                                            <option selected value="2022" >FY 2022-23</option>
                                            <?php }else{ ?>
                                            <option value="2022" >FY 2022-23</option>
                                            <?php } ?>
                                            <?php if($_GET['year'] == '2021'){ ?>
                                            <option selected value="2021" >FY 2021-22</option>
                                            <?php }else{ ?>
                                            <option value="2021" >FY 2021-22</option>
                                            <?php } ?>
                                            <?php if($_GET['year'] == '2020'){ ?>
                                            <option selected value="2020" >FY 2020-21</option>
                                            <?php }else{ ?>
                                            <option value="2020" >FY 2020-21</option>
                                            <?php } ?>
                                            <?php if($_GET['year'] == '2019'){ ?>
                                            <option selected value="2019" >FY 2019-20</option>
                                            <?php }else{ ?>
                                            <option value="2019" >FY 2019-20</option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                              </div>
                              <div class="card-body">
                                <div id="modern-analytics-chart" class="apex-charts" dir="ltr"></div>
                              </div>
                            </div>  
                            
                             <!--Bar chart start-->
            
                            <?php /*
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-sm-flex flex-wrap">
                                        <h4 class="card-title mb-4">Premium</h4>
                                        <div class="ml-auto">
                                            <ul class="nav nav-pills">
                                                <li class="nav-item"> 
                                                    <select class="form-control" id="year" >
                                                        <option>Select</option>
                                                        <?php if($_GET['year'] == '2019'){ ?>
                                                        <option selected value="2019" >2019</option>
                                                        <?php }else{ ?>
                                                        <option value="2019" >2019</option>
                                                        <?php } ?>
                                                        <?php if($_GET['year'] == '2020'){ ?>
                                                        <option selected value="2020" >2020</option>
                                                        <?php }else{ ?>
                                                        <option value="2020" >2020</option>
                                                        <?php } ?>
                                                        <?php if($_GET['year'] == '2021'){ ?>
                                                        <option selected value="2021" >2021</option>
                                                        <?php }else{ ?>
                                                        <option value="2021" >2021</option>
                                                        <?php } ?>
                                                        <?php if($_GET['year'] == '2022'){ ?>
                                                        <option selected value="2022" >2022</option>
                                                        <?php }else{ ?>
                                                        <option value="2022" >2022</option>
                                                        <?php } ?>
                                                        <?php if($_GET['year'] == '2023'){ ?>
                                                        <option selected value="2023" >2023</option>
                                                        <?php }else{ ?>
                                                        <option value="2023" >2023</option>
                                                        <?php } ?>
                                                        <?php if($_GET['year'] == '2024'){ ?>
                                                        <option selected value="2024" >2024</option>
                                                        <?php }else{ ?>
                                                        <option value="2024" >2024</option>
                                                        <?php } ?>
                                                        <?php if($_GET['year'] == '2025'){ ?>
                                                        <option selected value="2025" >2025</option>
                                                        <?php }else{ ?>
                                                        <option value="2025" >2025</option>
                                                        <?php } ?>
                                                        
                                                    </select>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div id="premium-chart" class="apex-charts" dir="ltr"></div>
                                </div>
                            </div>  */ ?>
                            <!--<div class="card">-->
                            <!--    <div class="card-body">-->
                            <!--        <div class="d-sm-flex flex-wrap">-->
                            <!--            <h4 class="card-title mb-4">Policies</h4>-->
                            <!--        </div>-->
                            <!--        <div id="policies-chart" class="apex-charts" dir="ltr"></div>-->
                            <!--    </div>-->
                            <!--</div>-->
                            <!--<div class="card">-->
                            <!--    <div class="card-body">-->
                            <!--        <div class="d-sm-flex flex-wrap">-->
                            <!--            <h4 class="card-title mb-4">Revenue</h4>-->
                            <!--        </div>-->
                            <!--        <div id="revenue-chart" class="apex-charts" dir="ltr"></div>-->
                            <!--    </div>-->
                            <!--</div>-->
                        </div>
                    </div> 
                    <div class="row" >
                        <div class="col-xl-6">
                            <div class="card modern-chart-card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4">Policy Types Distribution</h4>
                                    
                                    <div id="policy_type_chart" class="apex-charts" dir="ltr"></div>  
                                </div>
                            </div><!--end card-->
                            
                        </div>
                        <div class="col-xl-6">
                            <div class="card modern-chart-card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4">Vehicle Types Distribution</h4>
                                    
                                    <div id="vehicle_chart" class="apex-charts" dir="ltr"></div>
                                </div>
                            </div><!--end card-->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4">Renewal</h4>
                                    <div class="table-responsive">
                                        <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="text-center">S.No.</th>
                                                    <th class="text-center">VEHICLE&nbsp;NUMBER</th>
                                                    <th class="text-center">NAME</th>
                                                    <th class="text-center">PHONE</th>
                                                    <th class="text-center">VEHICLE&nbsp;TYPE</th>
                                                    <th class="text-center">POLICY&nbsp;END DATE</th>
                                                    <th class="text-center">ACTIONS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php  
                                                    $sn=1;
                                                    // Sort by policy end date from start to end of current month
                                                    $renewalsql = mysqli_query($con, "select * from policy where month(policy_end_date)='".date('m')."' and year(policy_end_date)='".date('Y')."' ORDER BY DAY(policy_end_date) ASC");
                                                    if(mysqli_num_rows($renewalsql) > 0 ){
                                                    while($renewalr=mysqli_fetch_array($renewalsql)){
                                                ?>
                                                <tr>
                                                    <td class="text-center" ><?=$sn;?></td>
                                                    <td class="text-center" ><a href="javascript: void(0);" class="text-body font-weight-bold waves-effect waves-light" onclick="viewpolicy(this)" data-id="<?=$renewalr['id']?>" ><?=$renewalr['vehicle_number'];?></a></td>
                                                    <td class="text-center" ><?=$renewalr['name'];?></td>
                                                    <td class="text-center" ><?=$renewalr['phone'];?></td>
                                                    <td class="text-center" ><?=$renewalr['vehicle_type'];?></td>
                                                    <td class="text-center" ><?=date('d-m-Y', strtotime($renewalr['policy_end_date']));?></td>
                                                    <td class="text-center" >
                                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="loadPolicyForEdit(<?=$renewalr['id'];?>)" title="Edit Policy">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </button>
                                                        <!-- <a href="javascript:void(0);" class="btn btn-outline-danger btn-sm edit" ><i class="fas fa-trash-alt" ></i></a> -->
                                                    </td>
                                                </tr>
                                                <?php $sn++; } }else{ ?>

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
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content" id="viewpolicydata" ></div>
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
                            <div class="text-sm-right d-none d-sm-block">Design & Develop by Softpro</div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <?php  
        $totaldata = array();
        $totalpremium = array();
        $totalrevenue = array();
        $monthname = array();
        
        if(!empty($_GET['year'])){
            // Financial Year logic: April to March
            $fyYear = $_GET['year'];
            $fyStartYear = $fyYear;
            $fyEndYear = $fyYear + 1;
            
            // Generate 12 months of Financial Year data (April to March)
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
            // Default: Generate previous 12 months data (current month is the last one)
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

    ?>
    <?php  
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

    ?>
    <?php  
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
        
     //   print_r($policytypedata);
        
        
       

    ?>
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/metismenu/metisMenu.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/apexcharts/apexcharts.min.js"></script>
    <script src="assets/js/pages/dashboard.init.js"></script>
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
    <!-- Edit modal functionality -->
    <script src="assets/js/edit-policy-modal-fixed.js"></script>
    <!-- Modern functionality scripts -->
    <script src="assets/js/global-search.js"></script>
    
    <!-- Add Policy Modal Debug Script -->
    <script>
        $(document).ready(function() {
            console.log('=== ADD POLICY MODAL DEBUG ===');
            console.log('jQuery loaded:', typeof $ !== 'undefined');
            console.log('Bootstrap modal plugin:', typeof $.fn.modal !== 'undefined');
            
            // Add modal focus fix event handlers
            $('#addPolicyModal').on('shown.bs.modal', function () {
                console.log('Modal shown event triggered - fixing input focus');
                
                // Remove any tabindex restrictions and fix CSS
                $(this).find('input, select, textarea').each(function() {
                    const element = $(this);
                    element.removeAttr('tabindex');
                    element.removeAttr('readonly');
                    element.removeAttr('disabled');
                    element.css({
                        'pointer-events': 'auto !important',
                        'user-select': 'text !important',
                        'background-color': '#ffffff !important',
                        'position': 'relative',
                        'z-index': '1060'
                    });
                });
                
                // Focus on first input
                setTimeout(function() {
                    const firstInput = $('#modal_vehicle_number');
                    if (firstInput.length) {
                        firstInput.focus();
                        console.log('Focus set to vehicle number input');
                    }
                }, 300);
            });
            
            // Ensure inputs are clickable
            $('#addPolicyModal').on('click', 'input, select, textarea', function(e) {
                console.log('Input clicked:', this.name || this.id);
                $(this).focus();
                e.stopPropagation();
            });
            
            // Check if modal exists after DOM is ready
            setTimeout(function() {
                const modal = document.getElementById('addPolicyModal');
                console.log('Modal exists after DOM ready:', modal ? 'Yes' : 'No');
                
                if (modal) {
                    console.log('Modal HTML structure present');
                    
                    // Manually bind click event as fallback
                    $('[data-target="#addPolicyModal"]').off('click.manualModal').on('click.manualModal', function(e) {
                        e.preventDefault();
                        console.log('Manual click handler triggered');
                        
                        try {
                            $('#addPolicyModal').modal('show');
                        } catch (error) {
                            console.error('Modal show failed:', error);
                            // Manual fallback
                            testAddPolicyModal();
                        }
                    });
                    
                    console.log('Manual click handler attached');
                } else {
                    console.error('Modal not found, include files may not be loading');
                }
            }, 500);
        });
    </script>
    
    
    <!--Modern Analytics Chart Script-->
    <script>
        // Modern Analytics Chart with ApexCharts
        var analyticsOptions = {
            series: [{
                name: 'Premium',
                data: [<?=$totalpremium[0]?>, <?=$totalpremium[1]?>, <?=$totalpremium[2]?>, <?=$totalpremium[3]?>, <?=$totalpremium[4]?>, <?=$totalpremium[5]?>, <?=$totalpremium[6]?>, <?=$totalpremium[7]?>, <?=$totalpremium[8]?>, <?=$totalpremium[9]?>, <?=$totalpremium[10]?>, <?=$totalpremium[11]?>],
                type: 'column'
            }, {
                name: 'Revenue',
                data: [<?=$totalrevenue[0]?>, <?=$totalrevenue[1]?>, <?=$totalrevenue[2]?>, <?=$totalrevenue[3]?>, <?=$totalrevenue[4]?>, <?=$totalrevenue[5]?>, <?=$totalrevenue[6]?>, <?=$totalrevenue[7]?>, <?=$totalrevenue[8]?>, <?=$totalrevenue[9]?>, <?=$totalrevenue[10]?>, <?=$totalrevenue[11]?>],
                type: 'line'
            }],
            chart: {
                height: 350,
                type: 'line',
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        selection: false,
                        zoom: true,
                        zoomin: true,
                        zoomout: true,
                        pan: false,
                        reset: true
                    }
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            colors: ['#667eea', '#764ba2'],
            plotOptions: {
                bar: {
                    borderRadius: 8,
                    columnWidth: '60%'
                }
            },
            stroke: {
                width: [0, 4],
                curve: 'smooth'
            },
            dataLabels: {
                enabled: false
            },
            labels: <?=json_encode($monthname,true);?>,
            xaxis: {
                categories: <?=json_encode($monthname,true);?>,
                labels: {
                    style: {
                        colors: '#8e8da4'
                    }
                }
            },
            yaxis: [{
                title: {
                    text: 'Premium Amount (₹)',
                    style: {
                        color: '#667eea'
                    }
                },
                labels: {
                    style: {
                        colors: '#667eea'
                    },
                    formatter: function (val) {
                        return "₹" + val;
                    }
                }
            }, {
                opposite: true,
                title: {
                    text: 'Revenue Amount (₹)',
                    style: {
                        color: '#764ba2'
                    }
                },
                labels: {
                    style: {
                        colors: '#764ba2'
                    },
                    formatter: function (val) {
                        return "₹" + val;
                    }
                }
            }],
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                floating: true,
                offsetY: -25,
                offsetX: -5
            },
            grid: {
                borderColor: '#f1f1f1',
                strokeDashArray: 3
            },
            tooltip: {
                shared: true,
                intersect: false,
                y: [{
                    formatter: function (y) {
                        if (typeof y !== "undefined") {
                            return "₹" + y.toFixed(0);
                        }
                        return y;
                    }
                }, {
                    formatter: function (y) {
                        if (typeof y !== "undefined") {
                            return "₹" + y.toFixed(0);
                        }
                        return y;
                    }
                }]
            }
        };
        
        var analyticsChart = new ApexCharts(document.querySelector("#modern-analytics-chart"), analyticsOptions);
        analyticsChart.render();
    </script>
    
    <!--Modern Analytics Chart End-->
    
    
    <script type="text/javascript">
        function viewpolicy(identifier) {
            console.log('=== VIEW POLICY FUNCTION CALLED ===');
            var id = $(identifier).data("id");
            console.log('Policy ID:', id);
            
            // Check if modal exists
            if ($('#renewalpolicyview').length === 0) {
                console.error('Modal #renewalpolicyview not found!');
                return;
            }
            
            // Show loading in modal content first
            $('#viewpolicydata').html(`
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title">
                        <i class="bx bx-file-blank mr-2"></i>Loading Policy Details...
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center p-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-3">Please wait while we fetch the policy details...</p>
                </div>
            `);
            
            // Show modal
            console.log('Showing modal...');
            $('#renewalpolicyview').modal("show");
            
            // Make AJAX call with better error handling
            console.log('Making AJAX call to view-policy.php...');
            $.ajax({
                url: "include/view-policy.php",
                type: "POST",
                data: { id: id },
                timeout: 10000, // 10 second timeout
                success: function(data) {
                    console.log('AJAX response received:', data.substring(0, 100));
                    if (data && data.trim().length > 0) {
                        $('#viewpolicydata').html(data);
                    } else {
                        $('#viewpolicydata').html(`
                            <div class="modal-header bg-danger text-white border-0">
                                <h5 class="modal-title">
                                    <i class="bx bx-error mr-2"></i>Error
                                </h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-danger">
                                    <i class="bx bx-error-circle mr-2"></i>
                                    No data received from server. Please try again.
                                </div>
                            </div>
                        `);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX call failed:', error);
                    console.error('Status:', status);
                    console.error('Response:', xhr.responseText);
                    $('#viewpolicydata').html(`
                        <div class="modal-header bg-danger text-white border-0">
                            <h5 class="modal-title">
                                <i class="bx bx-error mr-2"></i>Connection Error
                            </h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger">
                                <i class="bx bx-error-circle mr-2"></i>
                                <strong>Error loading policy details:</strong><br>
                                ${error === 'timeout' ? 'Request timed out. Please try again.' : 'Connection error. Please check your internet connection and try again.'}
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-primary" onclick="viewpolicy($(this).closest('.modal').data('trigger'))">
                                    <i class="bx bx-refresh mr-1"></i>Try Again
                                </button>
                            </div>
                        </div>
                    `);
                }
            });
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#year').on("change", function () {
                window.location.href='home.php?year='+$(this).val();
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            var options = {
                chart: {
                    height: 300,
                    type: "bar",
                    stacked: !0,
                    toolbar: {
                        show: !1
                    },
                    zoom: {
                        enabled: !0
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: !1,
                        columnWidth: "15%",
                        endingShape: "rounded"
                    }
                },
                dataLabels: {
                    enabled: !1
                },
                series: [{
                    name: "Premium",
                    data: <?=json_encode($totalpremium,JSON_NUMERIC_CHECK);?>
                }],
                xaxis: {
                    categories: <?=json_encode($monthname,true);?>
                },
                colors: ["#556ee6"],
                legend: {
                    position: "bottom"
                },
                fill: {
                    opacity: 1
                }
            },
            chart = new ApexCharts(document.querySelector("#premium-chart"), options);
            chart.render();
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            var options = {
                chart: {
                    height: 300,
                    type: "bar",
                    stacked: !0,
                    toolbar: {
                        show: !1
                    },
                    zoom: {
                        enabled: !0
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: !1,
                        columnWidth: "15%",
                        endingShape: "rounded"
                    }
                },
                dataLabels: {
                    enabled: !1
                },
                series: [{
                    name: "Policies",
                    data: <?=json_encode($totaldata,JSON_NUMERIC_CHECK);?>
                }],
                xaxis: {
                    categories: <?=json_encode($monthname,true);?>
                },
                colors: ["#f1b44c"],
                legend: {
                    position: "bottom"
                },
                fill: {
                    opacity: 1
                }
            },
            chart = new ApexCharts(document.querySelector("#policies-chart"), options);
            chart.render();
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            var options = {
                chart: {
                    height: 300,
                    type: "bar",
                    stacked: !0,
                    toolbar: {
                        show: !1
                    },
                    zoom: {
                        enabled: !0
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: !1,
                        columnWidth: "15%",
                        endingShape: "rounded"
                    }
                },
                dataLabels: {
                    enabled: !1
                },
                series: [{
                    name: "Revenue",
                    data: <?=json_encode($totalrevenue,JSON_NUMERIC_CHECK);?>
                }],
                xaxis: {
                    categories: <?=json_encode($monthname,true);?>
                },
                colors: ["#34c38f"],
                legend: {
                    position: "bottom"
                },
                fill: {
                    opacity: 1
                }
            },
            chart = new ApexCharts(document.querySelector("#revenue-chart"), options);
            chart.render();
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
            options = {
                chart: {
                    height: 350,
                    type: "bar",
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                        borderRadius: 8,
                        barHeight: '70%'
                    }
                },
                dataLabels: {
                    enabled: true,
                    style: {
                        fontSize: '12px',
                        fontWeight: 'bold'
                    }
                },
                series: [{
                    data: <?=json_encode($annualdata,JSON_NUMERIC_CHECK);?>
                }],
                colors: ["#667eea", "#764ba2", "#5ee7df", "#66a6ff", "#ffc107", "#ff8a00", "#ff6b6b", "#ee5a52"],
                grid: {
                    borderColor: "#f1f1f1"
                },
                xaxis: {
                    categories: <?=json_encode($annualname,true);?>
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val + " vehicles"
                        }
                    }
                }
            };
            (chart = new ApexCharts(document.querySelector("#vehicle_chart"), options)).render();
        });
        $(document).ready(function() {
            options = {
                chart: {
                    height: 370,
                    type: "donut"
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total Policies',
                                    fontSize: '16px',
                                    fontWeight: 600,
                                    color: '#373d3f',
                                    formatter: function (w) {
                                        return w.globals.seriesTotals.reduce((a, b) => {
                                            return a + b
                                        }, 0)
                                    }
                                }
                            }
                        }
                    }
                },
                dataLabels: {
                    enabled: true,
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold'
                    },
                    dropShadow: {
                        enabled: false
                    }
                },
                series: <?=json_encode($policytypedata,JSON_NUMERIC_CHECK);?>,
                labels: <?=json_encode($policytypename,true);?>,
                colors: ["#667eea", "#764ba2", "#5ee7df", "#66a6ff", "#ffc107", "#ff8a00", "#ff6b6b", "#ee5a52"],
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center',
                    fontSize: '14px',
                    markers: {
                        width: 12,
                        height: 12,
                        radius: 6
                    }
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }],
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val + " policies"
                        }
                    }
                }
            };
            (chart = new ApexCharts(document.querySelector("#policy_type_chart"), options)).render();
        });
    </script>
    
    <!-- Custom script for renewal table serial numbering -->
    <script type="text/javascript">
        $(document).ready(function() {
            // Check if DataTable is already initialized and destroy it
            if ($.fn.DataTable.isDataTable('#datatable')) {
                $('#datatable').DataTable().destroy();
            }
            
            // Initialize DataTable for renewal table with advanced features
            var renewalTable = $('#datatable').DataTable({
                "order": [[5, "asc"]], // Sort by Policy End Date (column index 5) ascending
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
                        title: 'Renewal Policies - ' + new Date().toLocaleDateString()
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-danger btn-sm',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        title: 'Renewal Policies - ' + new Date().toLocaleDateString(),
                        orientation: 'landscape',
                        pageSize: 'A4'
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-info btn-sm',
                        text: '<i class="fas fa-print"></i> Print',
                        title: 'Renewal Policies'
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
                    "info": "Showing _START_ to _END_ of _TOTAL_ renewal policies",
                    "infoEmpty": "No renewal policies found",
                    "infoFiltered": "(filtered from _MAX_ total policies)",
                    "zeroRecords": "No matching renewal policies found",
                    "emptyTable": "No renewal policies available"
                },
                "initComplete": function() {
                    // Add custom search functionality
                    this.api().columns().every(function() {
                        var column = this;
                        if (column.index() !== 0 && column.index() !== 6) { // Skip S.No and Actions columns
                            var select = $('<select class="form-control form-control-sm"><option value="">All</option></select>')
                                .appendTo($(column.header()))
                                .on('change', function() {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val()
                                    );
                                    column
                                        .search(val ? '^' + val + '$' : '', true, false)
                                        .draw();
                                });

                            column.data().unique().sort().each(function(d, j) {
                                if (d) {
                                    select.append('<option value="' + d + '">' + d + '</option>');
                                }
                            });
                        }
                    });
                }
            });
        });
    </script>

    <?php include 'include/add-policy-modal.php'; ?>
    <?php include 'include/edit-policy-modal.php'; ?>

    <script>
        // Enhanced function to open edit modal with better error handling
        function loadPolicyForEdit(policyId) {
            console.log('=== HOME.PHP EDIT FUNCTION CALLED ===');
            console.log('loadPolicyForEdit called with policyId:', policyId);
            console.log('Available functions check:', {
                openEditModal: typeof window.openEditModal,
                testEditModal: typeof window.testEditModal,
                testAPI: typeof window.testAPI,
                testConnectivity: typeof window.testConnectivity
            });
            
            // Check if the global openEditModal function is available
            if (typeof window.openEditModal === 'function') {
                console.log('Using global openEditModal function');
                window.openEditModal(policyId);
            } else {
                console.error('Edit modal function not available!');
                console.log('Available window functions:', Object.keys(window).filter(k => k.includes('edit') || k.includes('modal') || k.includes('test')));
                
                // Try to load the script again
                console.log('Attempting to reload edit modal script...');
                const script = document.createElement('script');
                script.src = 'assets/js/edit-policy-modal.js';
                script.onload = function() {
                    console.log('Script reloaded, trying again...');
                    if (typeof window.openEditModal === 'function') {
                        window.openEditModal(policyId);
                    } else {
                        console.error('Still no openEditModal function after reload');
                        // Fallback: redirect to edit page
                        window.location.href = 'edit.php?id=' + policyId;
                    }
                };
                script.onerror = function() {
                    console.error('Failed to reload script, redirecting to edit page');
                    window.location.href = 'edit.php?id=' + policyId;
                };
                document.head.appendChild(script);
            }
        }
        
        // Add debug functions for home.php
        window.debugHomeEdit = function(policyId = 1) {
            console.log('=== HOME.PHP DEBUG ===');
            console.log('Testing edit functionality from home.php');
            loadPolicyForEdit(policyId);
        };
        
        // Test connectivity from home.php
        window.homeTestConnectivity = function() {
            console.log('=== HOME.PHP CONNECTIVITY TEST ===');
            if (typeof window.testConnectivity === 'function') {
                window.testConnectivity();
            } else {
                console.log('testConnectivity not available, testing manually...');
                fetch('include/test-endpoint.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'test=1'
                })
                .then(response => response.text())
                .then(text => {
                    console.log('Home connectivity test result:', text);
                })
                .catch(error => {
                    console.error('Home connectivity test error:', error);
                });
            }
        };
        
        // Test view policy modal
        window.testViewPolicy = function(policyId = 1) {
            console.log('=== TESTING VIEW POLICY MODAL ===');
            console.log('Policy ID:', policyId);
            
            // Test direct AJAX call
            $.post("include/view-policy.php", { id: policyId }, function(data) {
                console.log('AJAX Success - Data length:', data.length);
                console.log('First 200 chars:', data.substring(0, 200));
                
                // Show modal
                $('#viewpolicydata').html(data);
                $('#renewalpolicyview').modal('show');
            }).fail(function(xhr, status, error) {
                console.error('AJAX Failed:', error);
                console.error('Status:', status);
                console.error('Response Text:', xhr.responseText);
            });
        };
        
        // Test dashboard functionality
        window.testDashboard = function() {
            console.log('=== TESTING DASHBOARD ===');
            console.log('Checking dashboard elements...');
            
            // Check if cards are visible
            const cards = document.querySelectorAll('.modern-card-info, .modern-card-success, .modern-card-warning, .modern-card-danger');
            console.log('Found dashboard cards:', cards.length);
            
            cards.forEach((card, index) => {
                const rect = card.getBoundingClientRect();
                const computedStyle = window.getComputedStyle(card);
                console.log(`Card ${index + 1}:`, {
                    visible: rect.width > 0 && rect.height > 0,
                    display: computedStyle.display,
                    visibility: computedStyle.visibility,
                    opacity: computedStyle.opacity,
                    background: computedStyle.background
                });
            });
            
            // Test modal functionality
            console.log('Testing modal functionality...');
            const modal = document.getElementById('editPolicyModal');
            if (modal) {
                console.log('Edit modal found');
                // Bootstrap 4 uses jQuery-based modal
                console.log('Bootstrap modal instance via jQuery:', $('#editPolicyModal').data('bs.modal'));
            } else {
                console.log('Edit modal not found');
            }
        };
        
        // Test Bootstrap and jQuery
        window.testLibraries = function() {
            console.log('=== TESTING LIBRARIES ===');
            console.log('jQuery version:', typeof $ !== 'undefined' ? $.fn.jquery : 'Not loaded');
            console.log('Bootstrap 4 loaded:', typeof $.fn.modal !== 'undefined' ? 'Yes' : 'No');
            
            if (typeof $ !== 'undefined') {
                console.log('jQuery modal plugin:', typeof $.fn.modal !== 'undefined' ? 'Available' : 'Not available');
                console.log('Bootstrap version check:', $().tooltip ? 'Bootstrap loaded' : 'Bootstrap not detected');
            }
        };
        
        console.log('=== HOME.PHP DEBUG FUNCTIONS LOADED ===');
        console.log('Available functions:');
        console.log('- window.debugHomeEdit(policyId)');
        console.log('- window.homeTestConnectivity()');
        console.log('- window.testViewPolicy(policyId)');
        console.log('- window.testDashboard()');
        console.log('- window.testLibraries()');
        console.log('- window.testAddPolicyModal()');
        
        // Add Policy Modal Test Function
        function testAddPolicyModal() {
            console.log('=== TESTING ADD POLICY MODAL ===');
            
            // Check if modal exists
            const modal = document.getElementById('addPolicyModal');
            console.log('Modal element found:', modal ? 'Yes' : 'No');
            
            if (modal) {
                console.log('Modal classes:', modal.className);
                console.log('Modal style display:', modal.style.display);
                
                // Check if jQuery is available
                if (typeof $ !== 'undefined') {
                    console.log('jQuery available, testing modal function...');
                    console.log('jQuery version:', $.fn.jquery);
                    console.log('Modal plugin available:', typeof $.fn.modal);
                    
                    // Try to show modal using jQuery
                    try {
                        $('#addPolicyModal').modal('show');
                        console.log('Modal show command executed successfully');
                        
                        // Fix input focus issues immediately
                        setTimeout(() => {
                            console.log('Fixing input focus issues...');
                            
                            // Remove any interfering attributes and fix styles
                            $('#addPolicyModal').find('input, select, textarea').each(function() {
                                const element = $(this);
                                element.removeAttr('readonly');
                                element.removeAttr('disabled');
                                element.removeAttr('tabindex');
                                element.css({
                                    'pointer-events': 'auto',
                                    'user-select': 'text',
                                    'background-color': '#ffffff',
                                    'opacity': '1',
                                    'position': 'relative',
                                    'z-index': '10'
                                });
                                console.log('Fixed element:', this.name || this.id);
                            });
                            
                            // Set focus to first input
                            const firstInput = $('#modal_vehicle_number');
                            if (firstInput.length) {
                                firstInput.focus();
                                console.log('Focus set to vehicle number input');
                            }
                        }, 300);
                        
                        // Check if modal is visible after 1 second
                        setTimeout(() => {
                            const isVisible = $('#addPolicyModal').hasClass('show');
                            console.log('Modal visible after show:', isVisible);
                            if (!isVisible) {
                                console.log('Modal not showing, checking for errors...');
                                console.log('Bootstrap CSS loaded:', $().modal ? 'Yes' : 'No');
                                
                                // Try manual display
                                console.log('Trying manual modal display...');
                                modal.style.display = 'block';
                                modal.classList.add('show');
                                document.body.classList.add('modal-open');
                                
                                // Add backdrop
                                const backdrop = document.createElement('div');
                                backdrop.className = 'modal-backdrop fade show';
                                backdrop.id = 'manual-backdrop';
                                document.body.appendChild(backdrop);
                                
                                // Fix inputs for manual display too
                                setTimeout(() => {
                                    $('#addPolicyModal').find('input, select, textarea').each(function() {
                                        $(this).css({
                                            'pointer-events': 'auto',
                                            'user-select': 'text',
                                            'background-color': '#ffffff',
                                            'z-index': '1060'
                                        });
                                    });
                                    $('#modal_vehicle_number').focus();
                                }, 100);
                            }
                        }, 1000);
                        
                    } catch (error) {
                        console.error('Error showing modal:', error);
                        // Manual fallback
                        console.log('Using manual fallback...');
                        modal.style.display = 'block';
                        modal.classList.add('show');
                        document.body.classList.add('modal-open');
                    }
                } else {
                    console.error('jQuery not available!');
                }
            } else {
                console.error('Modal #addPolicyModal not found in DOM!');
                
                // List all modals
                const allModals = document.querySelectorAll('.modal');
                console.log('Found modals:', Array.from(allModals).map(m => m.id));
                
                // Check if include files are loading
                console.log('Checking for include content...');
                const scripts = document.querySelectorAll('script');
                console.log('Number of script tags:', scripts.length);
            }
            
            // Also test the button
            const button = document.querySelector('[data-target="#addPolicyModal"]');
            console.log('Button found:', button ? 'Yes' : 'No');
            if (button) {
                console.log('Button data-toggle:', button.getAttribute('data-toggle'));
                console.log('Button data-target:', button.getAttribute('data-target'));
            }
        }
        
        window.testAddPolicyModal = testAddPolicyModal;
        
        // Manual close function
        window.closeAddPolicyModal = function() {
            const modal = document.getElementById('addPolicyModal');
            if (modal) {
                modal.style.display = 'none';
                modal.classList.remove('show');
                document.body.classList.remove('modal-open');
                
                // Remove manual backdrop
                const backdrop = document.getElementById('manual-backdrop');
                if (backdrop) {
                    backdrop.remove();
                }
            }
        };
    </script>
</body>

</html>