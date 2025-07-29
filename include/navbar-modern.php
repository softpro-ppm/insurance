<!-- Bootstrap 5 Top Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" style="box-shadow: 0 2px 4px rgba(0,0,0,.1);">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center" href="home.php">
            <img src="assets/logo.PNG" alt="Softpro" height="30" class="me-2">
            <span class="fw-bold">Softpro Insurance</span>
        </a>

        <!-- Mobile menu button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation Links -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'home.php' || basename($_SERVER['PHP_SELF']) == 'home-modern.php' ? 'active' : ''; ?>" 
                       href="home.php">
                        <i class="fas fa-home me-1"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'policies.php' || basename($_SERVER['PHP_SELF']) == 'policies-modern.php' ? 'active' : ''; ?>" 
                       href="policies.php">
                        <i class="fas fa-file-alt me-1"></i>Policies
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'client-management.php' ? 'active' : ''; ?>" 
                       href="client-management.php">
                        <i class="fas fa-users me-1"></i>Clients
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="renewalDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-sync-alt me-1"></i>Renewals
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="manage-renewal.php?renewal=renewal">
                            <i class="fas fa-clock me-2"></i>Due Renewals
                        </a></li>
                        <li><a class="dropdown-item" href="manage-renewal.php?pending=pending">
                            <i class="fas fa-exclamation-triangle me-2"></i>Pending Renewals
                        </a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="reportsDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-chart-bar me-1"></i>Reports
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="excel.php">
                            <i class="fas fa-download me-2"></i>Export Data
                        </a></li>
                        <li><a class="dropdown-item" href="#" onclick="generateReport('monthly')">
                            <i class="fas fa-calendar-alt me-2"></i>Monthly Report
                        </a></li>
                        <li><a class="dropdown-item" href="#" onclick="generateReport('yearly')">
                            <i class="fas fa-calendar me-2"></i>Yearly Report
                        </a></li>
                    </ul>
                </li>
            </ul>

            <!-- Right side navigation -->
            <ul class="navbar-nav">
                <!-- Quick Add Policy Button -->
                <li class="nav-item">
                    <button class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#addPolicyModal">
                        <i class="fas fa-plus me-1"></i>Add Policy
                    </button>
                </li>
                
                <!-- Notifications -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notificationCount">
                            <?php
                            // Get renewal notifications count
                            $notificationSql = mysqli_query($con, "SELECT COUNT(*) as count FROM policy WHERE policy_end_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) AND policy_end_date >= CURDATE()");
                            $notificationResult = mysqli_fetch_array($notificationSql);
                            echo $notificationResult['count'];
                            ?>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" style="width: 300px;">
                        <li><h6 class="dropdown-header">Renewal Notifications</h6></li>
                        <?php
                        $renewalNotifications = mysqli_query($con, "SELECT * FROM policy WHERE policy_end_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) AND policy_end_date >= CURDATE() ORDER BY policy_end_date ASC LIMIT 5");
                        if(mysqli_num_rows($renewalNotifications) > 0) {
                            while($notification = mysqli_fetch_array($renewalNotifications)) {
                                $daysLeft = (strtotime($notification['policy_end_date']) - strtotime(date('Y-m-d'))) / (60 * 60 * 24);
                                echo '<li><a class="dropdown-item" href="policies.php?search='.$notification['vehicle_number'].'">';
                                echo '<div class="d-flex align-items-center">';
                                echo '<i class="fas fa-exclamation-triangle text-warning me-2"></i>';
                                echo '<div>';
                                echo '<div class="fw-bold">'.$notification['vehicle_number'].'</div>';
                                echo '<small class="text-muted">Expires in '.ceil($daysLeft).' days</small>';
                                echo '</div></div></a></li>';
                            }
                        } else {
                            echo '<li><span class="dropdown-item-text">No pending renewals</span></li>';
                        }
                        ?>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-center" href="manage-renewal.php">View All Renewals</a></li>
                    </ul>
                </li>

                <!-- User Profile -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <img src="assets/images/users/default-avatar.png" alt="User" class="rounded-circle me-2" width="30" height="30">
                        <span><?php echo $_SESSION['username'] ?? 'User'; ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="profile.php">
                            <i class="fas fa-user me-2"></i>Profile
                        </a></li>
                        <li><a class="dropdown-item" href="settings.php">
                            <i class="fas fa-cog me-2"></i>Settings
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Add padding to body content due to fixed navbar -->
<style>
body {
    padding-top: 70px;
}

.navbar-nav .nav-link.active {
    background-color: rgba(255, 255, 255, 0.1);
    border-radius: 4px;
}

.navbar-nav .nav-link:hover {
    background-color: rgba(255, 255, 255, 0.05);
    border-radius: 4px;
}

.dropdown-menu {
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

.navbar-brand:hover {
    transform: scale(1.05);
    transition: transform 0.2s ease;
}

@media (max-width: 768px) {
    .navbar-nav .nav-link {
        padding: 0.75rem 1rem;
    }
}
</style>

<script>
// Auto-refresh notification count every 5 minutes
setInterval(function() {
    fetch('api/get_notifications.php')
        .then(response => response.json())
        .then(data => {
            document.getElementById('notificationCount').textContent = data.count;
        })
        .catch(error => console.error('Error fetching notifications:', error));
}, 300000); // 5 minutes

// Generate reports function
function generateReport(type) {
    const url = `api/generate_report.php?type=${type}`;
    window.open(url, '_blank');
}
</script>
