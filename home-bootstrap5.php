<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

// Page configuration
$pageTitle = 'Dashboard - Softpro Insurance Management System';
$useNavbar = true;
$useSidebar = false;

$pageHeader = [
    'title' => 'Dashboard',
    'icon' => '<i class="fas fa-tachometer-alt"></i>',
    'description' => 'Welcome back! Here\'s an overview of your insurance business',
    'actions' => '<button class="btn btn-primary" onclick="showAddPolicyModal()">
                    <i class="fas fa-plus me-1"></i>Quick Add Policy
                  </button>
                  <div class="dropdown d-inline-block ms-2">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-download me-1"></i>Reports
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="generateReport(\'monthly\')">Monthly Report</a></li>
                        <li><a class="dropdown-item" href="#" onclick="generateReport(\'yearly\')">Yearly Report</a></li>
                        <li><a class="dropdown-item" href="#" onclick="generateReport(\'renewals\')">Renewals Report</a></li>
                    </ul>
                  </div>'
];

$breadcrumb = [
    ['name' => 'Dashboard']
];

include 'include/header-modern.php';

// Get dashboard statistics
$totalPolicies = mysqli_num_rows(mysqli_query($con, "SELECT * FROM policy"));
$activePolicies = mysqli_num_rows(mysqli_query($con, "SELECT * FROM policy WHERE policy_end_date >= CURDATE()"));
$expiredPolicies = mysqli_num_rows(mysqli_query($con, "SELECT * FROM policy WHERE policy_end_date < CURDATE()"));
$renewalsDue = mysqli_num_rows(mysqli_query($con, "SELECT * FROM policy WHERE policy_end_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) AND policy_end_date >= CURDATE()"));
$urgentRenewals = mysqli_num_rows(mysqli_query($con, "SELECT * FROM policy WHERE policy_end_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY) AND policy_end_date >= CURDATE()"));

// Revenue statistics
$thisMonthRevenue = mysqli_query($con, "SELECT SUM(premium_amount) as total, SUM(commission_amount) as commission, COUNT(*) as count FROM policy WHERE DATE_FORMAT(policy_start_date, '%Y-%m') = '" . date('Y-m') . "'");
$thisMonthData = mysqli_fetch_array($thisMonthRevenue);

$lastMonthRevenue = mysqli_query($con, "SELECT SUM(premium_amount) as total, SUM(commission_amount) as commission, COUNT(*) as count FROM policy WHERE DATE_FORMAT(policy_start_date, '%Y-%m') = '" . date('Y-m', strtotime('-1 month')) . "'");
$lastMonthData = mysqli_fetch_array($lastMonthRevenue);

// Calculate growth
$revenueGrowth = 0;
$policyGrowth = 0;
if ($lastMonthData['total'] > 0) {
    $revenueGrowth = (($thisMonthData['total'] - $lastMonthData['total']) / $lastMonthData['total']) * 100;
}
if ($lastMonthData['count'] > 0) {
    $policyGrowth = (($thisMonthData['count'] - $lastMonthData['count']) / $lastMonthData['count']) * 100;
}
?>

<!-- Dashboard Content -->
<div class="row">
    <!-- Welcome Card -->
    <div class="col-12 mb-4">
        <div class="card border-0 shadow-sm bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body text-white">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="card-title mb-2">
                            Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>! 👋
                        </h4>
                        <p class="card-text mb-0">
                            You have <strong><?php echo $renewalsDue; ?> policies</strong> due for renewal in the next 30 days.
                            <?php if ($urgentRenewals > 0): ?>
                                <span class="badge bg-warning text-dark ms-2">
                                    <?php echo $urgentRenewals; ?> urgent renewals
                                </span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="d-flex flex-column align-items-md-end">
                            <h6 class="mb-1">Today's Date</h6>
                            <h5 class="mb-0"><?php echo date('F j, Y'); ?></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-lg rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center">
                            <i class="fas fa-file-alt fa-2x text-primary"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Total Policies</h6>
                        <h3 class="mb-0"><?php echo number_format($totalPolicies); ?></h3>
                        <small class="text-success">
                            <i class="fas fa-arrow-up me-1"></i>
                            +<?php echo number_format($policyGrowth, 1); ?>% this month
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-lg rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Active Policies</h6>
                        <h3 class="mb-0"><?php echo number_format($activePolicies); ?></h3>
                        <small class="text-muted">
                            <?php echo round(($activePolicies / max($totalPolicies, 1)) * 100, 1); ?>% of total
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-lg rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center">
                            <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Renewals Due</h6>
                        <h3 class="mb-0 text-warning"><?php echo number_format($renewalsDue); ?></h3>
                        <small class="text-muted">Next 30 days</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-lg rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center">
                            <i class="fas fa-rupee-sign fa-2x text-info"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">This Month Revenue</h6>
                        <h3 class="mb-0 text-info">₹<?php echo number_format($thisMonthData['total'] ?? 0); ?></h3>
                        <small class="text-success">
                            <i class="fas fa-arrow-up me-1"></i>
                            +<?php echo number_format($revenueGrowth, 1); ?>% growth
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Revenue Chart -->
    <div class="col-xl-8 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2 text-primary"></i>Revenue Trend
                    </h5>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-calendar me-1"></i>Last 12 Months
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="updateChart('6months')">Last 6 Months</a></li>
                            <li><a class="dropdown-item" href="#" onclick="updateChart('12months')">Last 12 Months</a></li>
                            <li><a class="dropdown-item" href="#" onclick="updateChart('year')">This Year</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="revenueChart"></div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="col-xl-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt me-2 text-warning"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-primary" onclick="showAddPolicyModal()">
                        <i class="fas fa-plus me-2"></i>Add New Policy
                    </button>
                    <button class="btn btn-warning" onclick="window.location.href='manage-renewal.php'">
                        <i class="fas fa-sync-alt me-2"></i>Manage Renewals
                    </button>
                    <button class="btn btn-info" onclick="window.location.href='policies-bootstrap5.php'">
                        <i class="fas fa-list me-2"></i>View All Policies
                    </button>
                    <button class="btn btn-success" onclick="generateReport('quick')">
                        <i class="fas fa-download me-2"></i>Generate Report
                    </button>
                </div>
                
                <hr class="my-3">
                
                <h6 class="fw-bold mb-3">Recent Activity</h6>
                <div class="activity-list">
                    <?php
                    $recentActivity = mysqli_query($con, "SELECT * FROM policy ORDER BY id DESC LIMIT 5");
                    while ($activity = mysqli_fetch_array($recentActivity)):
                        $timeDiff = time() - strtotime($activity['policy_start_date']);
                        $daysDiff = floor($timeDiff / (60 * 60 * 24));
                    ?>
                    <div class="d-flex align-items-center mb-2">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-light d-flex align-items-center justify-content-center">
                                <i class="fas fa-file-alt text-muted"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <p class="mb-0 small">
                                <strong><?php echo htmlspecialchars($activity['client_name']); ?></strong>
                                <br>
                                <span class="text-muted"><?php echo htmlspecialchars($activity['vehicle_number']); ?></span>
                            </p>
                            <small class="text-muted">
                                <?php echo $daysDiff == 0 ? 'Today' : $daysDiff . ' days ago'; ?>
                            </small>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Policies and Upcoming Renewals -->
<div class="row">
    <div class="col-xl-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock me-2 text-primary"></i>Recent Policies
                    </h5>
                    <a href="policies-bootstrap5.php" class="btn btn-outline-primary btn-sm">View All</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Vehicle</th>
                                <th>Premium</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $recentPolicies = mysqli_query($con, "SELECT * FROM policy ORDER BY id DESC LIMIT 8");
                            while ($policy = mysqli_fetch_array($recentPolicies)):
                            ?>
                            <tr>
                                <td>
                                    <div class="fw-bold"><?php echo htmlspecialchars($policy['client_name']); ?></div>
                                    <small class="text-muted"><?php echo htmlspecialchars($policy['mobile_number']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($policy['vehicle_number']); ?></td>
                                <td>₹<?php echo number_format($policy['premium_amount']); ?></td>
                                <td>
                                    <small><?php echo date('M j', strtotime($policy['policy_start_date'])); ?></small>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bell me-2 text-warning"></i>Upcoming Renewals
                    </h5>
                    <a href="manage-renewal.php" class="btn btn-outline-warning btn-sm">Manage All</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Vehicle</th>
                                <th>Expires</th>
                                <th>Days Left</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $upcomingRenewals = mysqli_query($con, "SELECT * FROM policy WHERE policy_end_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) AND policy_end_date >= CURDATE() ORDER BY policy_end_date ASC LIMIT 8");
                            while ($renewal = mysqli_fetch_array($upcomingRenewals)):
                                $daysLeft = (strtotime($renewal['policy_end_date']) - strtotime(date('Y-m-d'))) / (60 * 60 * 24);
                                $urgentClass = $daysLeft <= 7 ? 'text-danger' : ($daysLeft <= 15 ? 'text-warning' : 'text-info');
                            ?>
                            <tr>
                                <td>
                                    <div class="fw-bold"><?php echo htmlspecialchars($renewal['client_name']); ?></div>
                                    <small class="text-muted"><?php echo htmlspecialchars($renewal['mobile_number']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($renewal['vehicle_number']); ?></td>
                                <td>
                                    <small><?php echo date('M j', strtotime($renewal['policy_end_date'])); ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-light <?php echo $urgentClass; ?>">
                                        <?php echo ceil($daysLeft); ?> days
                                    </span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS for Dashboard -->
<style>
.avatar-lg {
    width: 4rem;
    height: 4rem;
}

.avatar-sm {
    width: 2rem;
    height: 2rem;
}

.activity-list {
    max-height: 200px;
    overflow-y: auto;
}

.card:hover {
    transform: translateY(-2px);
    transition: transform 0.3s ease;
}

.bg-gradient {
    position: relative;
    overflow: hidden;
}

.bg-gradient::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="80" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="60" r="1" fill="rgba(255,255,255,0.1)"/></svg>');
    background-size: 50px 50px;
}

.table th {
    font-weight: 600;
    font-size: 0.875rem;
    border-bottom: 2px solid #dee2e6;
}
</style>

<!-- Chart and Dashboard JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Revenue Chart
    initializeRevenueChart();
    
    // Show welcome message
    showSuccessToast('Dashboard loaded successfully');
    
    // Auto-refresh dashboard data every 5 minutes
    setInterval(function() {
        location.reload();
    }, 300000);
});

function initializeRevenueChart() {
    const options = {
        series: [{
            name: 'Revenue',
            data: [
                <?php
                $chartData = [];
                for ($i = 11; $i >= 0; $i--) {
                    $month = date('Y-m', strtotime("-$i months"));
                    $monthlyRevenue = mysqli_query($con, "SELECT SUM(premium_amount) as total FROM policy WHERE DATE_FORMAT(policy_start_date, '%Y-%m') = '$month'");
                    $monthlyData = mysqli_fetch_array($monthlyRevenue);
                    $chartData[] = intval($monthlyData['total'] ?? 0);
                }
                echo implode(',', $chartData);
                ?>
            ]
        }],
        chart: {
            type: 'area',
            height: 350,
            toolbar: {
                show: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        xaxis: {
            categories: [
                <?php
                $months = [];
                for ($i = 11; $i >= 0; $i--) {
                    $months[] = "'" . date('M Y', strtotime("-$i months")) . "'";
                }
                echo implode(',', $months);
                ?>
            ]
        },
        yaxis: {
            labels: {
                formatter: function (val) {
                    return "₹" + val.toLocaleString();
                }
            }
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return "₹" + val.toLocaleString();
                }
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.9,
                stops: [0, 90, 100]
            }
        },
        colors: ['#667eea']
    };

    const chart = new ApexCharts(document.querySelector("#revenueChart"), options);
    chart.render();
    
    window.revenueChart = chart;
}

function updateChart(period) {
    // This would typically make an AJAX call to get new data
    showInfoToast(`Chart updated for ${period}`);
}
</script>

<?php
$customJS = "
<script>
// Dashboard-specific functionality
function showDashboardStats() {
    showInfoToast('Dashboard statistics updated');
}

// Auto-refresh notifications
setInterval(function() {
    fetch('api/get_notifications.php')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.urgent > 0) {
                showWarningToast(`${data.urgent} urgent renewals need attention!`);
            }
        })
        .catch(error => console.error('Error fetching notifications:', error));
}, 600000); // Check every 10 minutes
</script>
";

include 'include/footer-modern.php';
?>
