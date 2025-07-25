<ul class="metismenu list-unstyled" id="side-menu">
    <!-- Dashboard -->
    <li class="menu-title" key="t-menu">Main</li>
    <li>
        <a href="home.php" class="waves-effect"> 
            <i class="bx bx-home-circle"></i>
            <span key="t-dashboards">Dashboard</span>
            <span class="badge rounded-pill bg-success ms-auto">Live</span>
        </a>
    </li>
    
    <!-- Policy Management -->
    <li class="menu-title" key="t-apps">Policy Management</li>
    <li>
        <a href="policies.php" class="waves-effect"> 
            <i class="bx bx-file-blank"></i>
            <span key="t-policies">All Policies</span>
        </a>
    </li>
    <li>
        <a href="add.php" class="waves-effect"> 
            <i class="bx bx-plus-circle"></i>
            <span key="t-add-policy">Add New Policy</span>
        </a>
    </li>
    
    <!-- Renewal Management -->
    <li class="menu-title" key="t-pages">Renewal & Follow-up</li>
    <li>
        <a href="manage-renewal.php" class="waves-effect"> 
            <i class="bx bx-refresh"></i>
            <span key="t-renewals">Manage Renewals</span>
        </a>
    </li>
    <li>
        <a href="feedback-renewal.php" class="waves-effect"> 
            <i class="bx bx-message-dots"></i>
            <span key="t-followup">Follow-up Management</span>
        </a>
    </li>
    
    <!-- Reports & Analytics -->
    <li class="menu-title" key="t-components">Reports & Analytics</li>
    <li>
        <a href="javascript: void(0);" class="has-arrow waves-effect">
            <i class="bx bx-bar-chart-alt-2"></i>
            <span key="t-reports">Reports</span>
        </a>
        <ul class="sub-menu" aria-expanded="false">
            <li><a href="home.php?year=2025" key="t-policy-reports">Policy Reports</a></li>
            <li><a href="manage-renewal.php?renewal=renewal" key="t-renewal-reports">Renewal Reports</a></li>
            <li><a href="excel.php" key="t-export">Export Data</a></li>
        </ul>
    </li>
    
    <!-- System Management -->
    <li class="menu-title" key="t-layouts">System</li>
    <li>
        <a href="javascript: void(0);" class="has-arrow waves-effect">
            <i class="bx bx-cog"></i>
            <span key="t-settings">Settings</span>
        </a>
        <ul class="sub-menu" aria-expanded="false">
            <li><a href="users.php" key="t-users"><i class="bx bx-user"></i> User Management</a></li>
            <li><a href="change-password.php" key="t-password"><i class="bx bx-lock"></i> Change Password</a></li>
            <li><a href="profile.php" key="t-profile"><i class="bx bx-user-circle"></i> Profile Settings</a></li>
        </ul>
    </li>
    
    <!-- Quick Actions -->
    <li class="menu-title" key="t-components">Quick Actions</li>
    <li>
        <a href="manage-renewal.php?pending=pending" class="waves-effect">
            <i class="bx bx-error-circle text-warning"></i>
            <span key="t-pending">Pending Renewals</span>
        </a>
    </li>
    <li>
        <a href="policies.php?latest=latest" class="waves-effect">
            <i class="bx bx-time-five text-info"></i>
            <span key="t-recent">Recent Policies</span>
        </a>
    </li>
    
    <!-- Help & Support -->
    <li class="menu-title" key="t-extra">Support</li>
    <li>
        <a href="javascript: void(0);" class="has-arrow waves-effect">
            <i class="bx bx-help-circle"></i>
            <span key="t-help">Help & Support</span>
        </a>
        <ul class="sub-menu" aria-expanded="false">
            <li><a href="#" key="t-documentation"><i class="bx bx-book"></i> Documentation</a></li>
            <li><a href="#" key="t-contact"><i class="bx bx-phone"></i> Contact Support</a></li>
            <li><a href="#" key="t-about"><i class="bx bx-info-circle"></i> About System</a></li>
        </ul>
    </li>
    
    <!-- Logout -->
    <li class="menu-title" key="t-account">Account</li>
    <li>
        <a href="include/logout.php" class="waves-effect text-danger"> 
            <i class="mdi mdi-logout"></i>
            <span key="t-logout">Logout</span>
        </a>
    </li>
</ul>