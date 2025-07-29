<!-- Bootstrap 5 Sidebar Navigation -->
<nav id="sidebar" class="sidebar bg-dark text-white">
    <div class="sidebar-header p-3">
        <div class="d-flex align-items-center">
            <img src="assets/logo.PNG" alt="Softpro" height="35" class="me-2">
            <div>
                <h5 class="mb-0 fw-bold">Softpro</h5>
                <small class="text-muted">Insurance System</small>
            </div>
        </div>
        <button type="button" id="sidebarCollapse" class="btn btn-outline-light btn-sm mt-2">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <div class="sidebar-content">
        <!-- Main Navigation -->
        <ul class="nav flex-column">
            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'home.php' || basename($_SERVER['PHP_SELF']) == 'home-modern.php') ? 'active' : ''; ?>" 
                   href="home.php">
                    <i class="fas fa-home me-2"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>

            <!-- Policies -->
            <li class="nav-item">
                <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'policies.php' || basename($_SERVER['PHP_SELF']) == 'policies-modern.php') ? 'active' : ''; ?>" 
                   href="policies.php">
                    <i class="fas fa-file-alt me-2"></i>
                    <span class="nav-text">Policies</span>
                </a>
            </li>

            <!-- Clients -->
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'client-management.php' ? 'active' : ''; ?>" 
                   href="client-management.php">
                    <i class="fas fa-users me-2"></i>
                    <span class="nav-text">Clients</span>
                </a>
            </li>

            <!-- Renewals -->
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-toggle="collapse" href="#renewalsSubmenu" role="button">
                    <i class="fas fa-sync-alt me-2"></i>
                    <span class="nav-text">Renewals</span>
                    <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="renewalsSubmenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link" href="manage-renewal.php?renewal=renewal">
                                <i class="fas fa-clock me-2"></i>
                                <span class="nav-text">Due Renewals</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manage-renewal.php?pending=pending">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <span class="nav-text">Pending Renewals</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Reports -->
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-toggle="collapse" href="#reportsSubmenu" role="button">
                    <i class="fas fa-chart-bar me-2"></i>
                    <span class="nav-text">Reports</span>
                    <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="reportsSubmenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link" href="excel.php">
                                <i class="fas fa-download me-2"></i>
                                <span class="nav-text">Export Data</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" onclick="generateReport('monthly')">
                                <i class="fas fa-calendar-alt me-2"></i>
                                <span class="nav-text">Monthly Report</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" onclick="generateReport('yearly')">
                                <i class="fas fa-calendar me-2"></i>
                                <span class="nav-text">Yearly Report</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Communication -->
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-toggle="collapse" href="#communicationSubmenu" role="button">
                    <i class="fas fa-envelope me-2"></i>
                    <span class="nav-text">Communication</span>
                    <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="communicationSubmenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link" href="whatsapp-automation.php">
                                <i class="fab fa-whatsapp me-2"></i>
                                <span class="nav-text">WhatsApp</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="email-automation.php">
                                <i class="fas fa-mail-bulk me-2"></i>
                                <span class="nav-text">Email</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="sms-automation.php">
                                <i class="fas fa-sms me-2"></i>
                                <span class="nav-text">SMS</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- User Management -->
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-toggle="collapse" href="#userSubmenu" role="button">
                    <i class="fas fa-user-cog me-2"></i>
                    <span class="nav-text">User Management</span>
                    <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="userSubmenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link" href="add-user.php">
                                <i class="fas fa-user-plus me-2"></i>
                                <span class="nav-text">Add User</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="edit-user.php">
                                <i class="fas fa-user-edit me-2"></i>
                                <span class="nav-text">Edit User</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Settings -->
            <li class="nav-item">
                <a class="nav-link" href="settings.php">
                    <i class="fas fa-cog me-2"></i>
                    <span class="nav-text">Settings</span>
                </a>
            </li>
        </ul>

        <!-- Quick Actions -->
        <div class="quick-actions mt-4 p-3">
            <h6 class="text-uppercase fw-bold mb-3">Quick Actions</h6>
            <div class="d-grid gap-2">
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPolicyModal">
                    <i class="fas fa-plus me-1"></i> Add Policy
                </button>
                <button class="btn btn-warning btn-sm" onclick="openRenewalReminder()">
                    <i class="fas fa-bell me-1"></i> Set Reminder
                </button>
                <button class="btn btn-info btn-sm" onclick="generateQuickReport()">
                    <i class="fas fa-chart-line me-1"></i> Quick Report
                </button>
            </div>
        </div>

        <!-- User Info -->
        <div class="user-info mt-auto p-3 border-top">
            <div class="d-flex align-items-center">
                <img src="assets/images/users/default-avatar.png" alt="User" class="rounded-circle me-2" width="40" height="40">
                <div class="flex-grow-1">
                    <div class="fw-bold"><?php echo $_SESSION['username'] ?? 'User'; ?></div>
                    <small class="text-muted">Administrator</small>
                </div>
                <div class="dropdown">
                    <button class="btn btn-outline-light btn-sm" data-bs-toggle="dropdown">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Sidebar Toggle Overlay for Mobile -->
<div id="sidebarOverlay" class="sidebar-overlay"></div>

<style>
/* Sidebar Styles */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    width: 280px;
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
    transition: all 0.3s ease;
    z-index: 1050;
    overflow-y: auto;
    box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
}

.sidebar.collapsed {
    width: 80px;
}

.sidebar.collapsed .nav-text,
.sidebar.collapsed .sidebar-header h5,
.sidebar.collapsed .sidebar-header small,
.sidebar.collapsed .quick-actions,
.sidebar.collapsed .user-info .fw-bold,
.sidebar.collapsed .user-info small {
    display: none;
}

.sidebar.collapsed .nav-link {
    text-align: center;
    padding: 0.75rem 0;
}

.sidebar.collapsed .nav-link i {
    margin: 0;
}

.sidebar-content {
    padding: 1rem 0;
    height: calc(100vh - 140px);
    display: flex;
    flex-direction: column;
}

.sidebar .nav-link {
    color: #cbd5e0;
    padding: 0.75rem 1rem;
    margin: 0.125rem 0.5rem;
    border-radius: 8px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    text-decoration: none;
}

.sidebar .nav-link:hover {
    background-color: rgba(255, 255, 255, 0.1);
    color: #ffffff;
    transform: translateX(2px);
}

.sidebar .nav-link.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #ffffff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.sidebar .nav-link i {
    width: 20px;
    text-align: center;
}

.sidebar .quick-actions {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    margin-top: auto;
}

.sidebar .user-info {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

/* Content area adjustment */
.main-content {
    margin-left: 280px;
    transition: margin-left 0.3s ease;
    min-height: 100vh;
}

.main-content.sidebar-collapsed {
    margin-left: 80px;
}

/* Sidebar overlay for mobile */
.sidebar-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1040;
    display: none;
}

/* Mobile responsive */
@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
        width: 280px;
    }
    
    .sidebar.show {
        transform: translateX(0);
    }
    
    .main-content {
        margin-left: 0;
    }
    
    .sidebar-overlay.show {
        display: block;
    }
}

/* Scrollbar styling */
.sidebar::-webkit-scrollbar {
    width: 4px;
}

.sidebar::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
}

.sidebar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.3);
    border-radius: 2px;
}

.sidebar::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.5);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const sidebarCollapse = document.getElementById('sidebarCollapse');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const mainContent = document.querySelector('.main-content') || document.body;

    // Toggle sidebar collapse
    sidebarCollapse.addEventListener('click', function() {
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('sidebar-collapsed');
        
        // Store preference
        localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
    });

    // Restore sidebar state
    if (localStorage.getItem('sidebarCollapsed') === 'true') {
        sidebar.classList.add('collapsed');
        mainContent.classList.add('sidebar-collapsed');
    }

    // Mobile menu toggle
    if (window.innerWidth <= 768) {
        sidebarCollapse.addEventListener('click', function() {
            sidebar.classList.toggle('show');
            sidebarOverlay.classList.toggle('show');
        });

        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
        });
    }

    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
        }
    });
});

// Quick action functions
function openRenewalReminder() {
    // Open renewal reminder modal
    showToast('Feature coming soon!', 'info');
}

function generateQuickReport() {
    // Generate quick report
    window.open('api/generate_report.php?type=quick', '_blank');
}

function generateReport(type) {
    const url = `api/generate_report.php?type=${type}`;
    window.open(url, '_blank');
}
</script>
