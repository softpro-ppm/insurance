<div class="navbar-header">
    <div class="d-flex">
        <!-- LOGO -->
        <div class="navbar-brand-box">
            <a href="home.php" class="logo logo-dark"> 
                <span class="logo-sm">
                    <h3 style="color:#fff; margin-top: 15px; font-weight: bold; font-family: 'Arial', sans-serif;" height="22">
                        <i class="bx bx-shield-check me-2"></i>SOFTPRO
                    </h3>
                </span>
                <span class="logo-lg">
                    <h3 style="color:#fff; margin-top: 15px; font-weight: bold; font-family: 'Arial', sans-serif;" height="17">
                        <i class="bx bx-shield-check me-2"></i>SOFTPRO INSURANCE
                    </h3>
                </span>
            </a>
            <a href="home.php" class="logo logo-light"> 
                <span class="logo-sm">
                    <h3 style="color:#fff; margin-top: 20px; font-weight: bold; font-family: 'Arial', sans-serif;" height="22">
                        <i class="bx bx-shield-check me-2"></i>SOFTPRO
                    </h3>
                </span>
                <span class="logo-lg">
                    <h3 style="color:#fff; margin-top: 20px; font-weight: bold; font-family: 'Arial', sans-serif;" height="19">
                        <i class="bx bx-shield-check me-2"></i>SOFTPRO INSURANCE
                    </h3>
                </span>
            </a>
        </div>
        <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn"> 
            <i class="fa fa-fw fa-bars"></i>
        </button>
        
        <!-- Search Box -->
        <div class="app-search d-none d-lg-block ms-3">
            <div class="position-relative">
                <input type="text" class="form-control" placeholder="Search policies, renewals..." id="global-search">
                <span class="bx bx-search-alt"></span>
            </div>
        </div>
    </div>
    
    <div class="d-flex">
        <!-- Notifications -->
        <div class="dropdown d-none d-sm-inline-block">
            <button type="button" class="btn header-item noti-icon waves-effect" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="bx bx-bell bx-tada"></i>
                <?php
                    // Count pending renewals
                    $pendingCount = mysqli_query($con, "SELECT COUNT(*) as count FROM policy WHERE month(policy_end_date) = '".date("m")."' AND year(policy_end_date) = '".date("Y")."' AND policy_end_date <= '".date("Y-m-d")."'");
                    $pending = mysqli_fetch_array($pendingCount);
                    if($pending['count'] > 0) {
                        echo '<span class="badge bg-danger rounded-pill">'.$pending['count'].'</span>';
                    }
                ?>
            </button>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0">
                <div class="p-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="m-0">Notifications</h6>
                        </div>
                        <div class="col-auto">
                            <a href="#!" class="small">View All</a>
                        </div>
                    </div>
                </div>
                <div class="slimscroll notification-item-list">
                    <?php if($pending['count'] > 0) { ?>
                    <a href="manage-renewal.php?pending=pending" class="text-reset notification-item">
                        <div class="d-flex">
                            <div class="avatar-xs me-3">
                                <span class="avatar-title bg-warning rounded-circle font-size-16">
                                    <i class="bx bx-error-circle"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Pending Renewals</h6>
                                <div class="font-size-12 text-muted">
                                    <p class="mb-1"><?=$pending['count']?> policies require immediate attention</p>
                                    <p class="mb-0"><i class="mdi mdi-clock-outline"></i> Just now</p>
                                </div>
                            </div>
                        </div>
                    </a>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Full Screen -->
        <div class="dropdown d-none d-lg-inline-block ms-1">
            <button type="button" class="btn header-item noti-icon waves-effect" data-bs-toggle="fullscreen"> 
                <i class="bx bx-fullscreen"></i>
            </button>
        </div>

        <!-- Quick Add Policy -->
        <div class="dropdown d-none d-sm-inline-block ms-1">
            <a href="add.php" class="btn header-item noti-icon waves-effect" title="Add New Policy">
                <i class="bx bx-plus-circle text-success"></i>
            </a>
        </div>

        <!-- User Profile -->
        <div class="dropdown d-inline-block">
            <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php  
                    $profilesql = mysqli_query($con, "select photo, name from user where username='".$_SESSION['username']."'");
                    $profiler = mysqli_fetch_array($profilesql);
                    if($profiler['photo'] == ''){
                        $photo = 'default.png';
                    }else{
                        $photo = $profiler['photo'];
                    }
                    $displayName = !empty($profiler['name']) ? $profiler['name'] : $_SESSION['username'];
                ?>
                <img class="rounded-circle header-profile-user" src="assets/profile/<?=$photo;?>" alt="Header Avatar"> 
                <span class="d-none d-xl-inline-block ms-1" key="t-henry"><?=$displayName;?></span>
                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <div class="dropdown-header noti-title">
                    <h6 class="text-overflow m-0">Welcome <?=$displayName;?>!</h6>
                </div>
                <a class="dropdown-item" href="profile.php">
                    <i class="bx bx-user font-size-16 align-middle me-1"></i> 
                    <span key="t-profile">Profile</span>
                </a>
                <a class="dropdown-item" href="change-password.php">
                    <i class="bx bx-lock font-size-16 align-middle me-1"></i> 
                    <span key="t-settings">Change Password</span>
                </a>
                <a class="dropdown-item" href="home.php">
                    <i class="bx bx-home font-size-16 align-middle me-1"></i> 
                    <span key="t-dashboard">Dashboard</span>
                </a>
                <div class="dropdown-divider"></div> 
                <a class="dropdown-item text-danger" href="include/logout.php">
                    <i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> 
                    <span key="t-logout">Logout</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Global Search JavaScript -->
<script>
$(document).ready(function() {
    $('#global-search').on('keyup', function() {
        var searchTerm = $(this).val().toLowerCase();
        if (searchTerm.length > 2) {
            // Implement global search functionality here
            console.log('Searching for: ' + searchTerm);
        }
    });
});
</script>