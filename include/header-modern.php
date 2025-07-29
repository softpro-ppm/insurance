<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Softpro Insurance Management System'; ?></title>
    
    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- DataTables with Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
    
    <!-- ApexCharts for Dashboard -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --info-color: #0dcaf0;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --shadow-sm: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            --shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            --shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            line-height: 1.6;
        }

        /* Global Card Styling */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: var(--shadow);
            transform: translateY(-2px);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #0056b3 100%);
            color: white;
            border-radius: 12px 12px 0 0 !important;
            padding: 1rem 1.5rem;
            border: none;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Button Enhancements */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.5rem 1.25rem;
            transition: all 0.3s ease;
            border: none;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, #0056b3 100%);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success-color) 0%, #146c43 100%);
        }

        .btn-warning {
            background: linear-gradient(135deg, var(--warning-color) 0%, #e5a821 100%);
            color: var(--dark-color);
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger-color) 0%, #b02a37 100%);
        }

        .btn-info {
            background: linear-gradient(135deg, var(--info-color) 0%, #3dd5f3 100%);
            color: var(--dark-color);
        }

        /* Form Styling */
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #dee2e6;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        /* DataTables Styling */
        .table {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .table thead th {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: none;
            font-weight: 600;
            color: var(--dark-color);
            padding: 1rem;
        }

        .table tbody td {
            padding: 0.875rem 1rem;
            border-color: #f1f3f4;
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 6px;
            margin: 0 2px;
            padding: 0.5rem 0.75rem;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
            color: white !important;
        }

        /* Alert Styling */
        .alert {
            border: none;
            border-radius: 10px;
            padding: 1rem 1.25rem;
            border-left: 4px solid;
        }

        .alert-success {
            background: linear-gradient(135deg, #d1eddb 0%, #c3e6cb 100%);
            border-left-color: var(--success-color);
        }

        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            border-left-color: var(--danger-color);
        }

        .alert-warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border-left-color: var(--warning-color);
        }

        .alert-info {
            background: linear-gradient(135deg, #d1ecf1 0%, #b8daff 100%);
            border-left-color: var(--info-color);
        }

        /* Badge Styling */
        .badge {
            border-radius: 20px;
            padding: 0.5rem 0.75rem;
            font-weight: 500;
            font-size: 0.75rem;
        }

        /* Loading Animation */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .spinner-border-custom {
            width: 3rem;
            height: 3rem;
            border-width: 0.3em;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .card-body {
                padding: 1rem;
            }
            
            .btn {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
            }
            
            .table {
                font-size: 0.875rem;
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Page Transitions */
        .page-content {
            animation: fadeInUp 0.5s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Print Styles */
        @media print {
            .no-print {
                display: none !important;
            }
            
            .card {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }
    </style>
    
    <!-- Page-specific CSS -->
    <?php if (isset($customCSS)) echo $customCSS; ?>
</head>
<body>
    
    <!-- Include Navbar or Sidebar based on preference -->
    <?php
    $useNavbar = $useNavbar ?? true;
    $useSidebar = $useSidebar ?? false;
    
    if ($useNavbar) {
        include_once 'include/navbar-modern.php';
    }
    
    if ($useSidebar) {
        include_once 'include/sidebar-modern.php';
    }
    ?>
    
    <!-- Main Content Wrapper -->
    <div class="<?php echo $useSidebar ? 'main-content' : 'container-fluid'; ?>" style="<?php echo $useNavbar ? 'padding-top: 80px;' : ''; ?>">
        
        <!-- Page Content Start -->
        <div class="page-content">
            
            <!-- Include Toaster System -->
            <?php include_once 'include/toaster-system.php'; ?>
            
            <!-- Include Modal System -->
            <?php include_once 'include/modal-system.php'; ?>
            
            <!-- Page-specific Breadcrumb -->
            <?php if (isset($breadcrumb) && $breadcrumb): ?>
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb bg-white rounded shadow-sm p-3">
                    <li class="breadcrumb-item"><a href="home.php"><i class="fas fa-home"></i> Home</a></li>
                    <?php foreach ($breadcrumb as $item): ?>
                        <?php if (isset($item['url'])): ?>
                            <li class="breadcrumb-item"><a href="<?php echo $item['url']; ?>"><?php echo $item['name']; ?></a></li>
                        <?php else: ?>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo $item['name']; ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ol>
            </nav>
            <?php endif; ?>
            
            <!-- Page Title -->
            <?php if (isset($pageHeader) && $pageHeader): ?>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1 text-dark fw-bold">
                        <?php echo $pageHeader['icon'] ?? ''; ?> <?php echo $pageHeader['title']; ?>
                    </h1>
                    <?php if (isset($pageHeader['description'])): ?>
                        <p class="text-muted mb-0"><?php echo $pageHeader['description']; ?></p>
                    <?php endif; ?>
                </div>
                <?php if (isset($pageHeader['actions'])): ?>
                    <div class="d-flex gap-2">
                        <?php echo $pageHeader['actions']; ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <!-- Alert Messages -->
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['warning_message'])): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?php echo $_SESSION['warning_message']; unset($_SESSION['warning_message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['info_message'])): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    <?php echo $_SESSION['info_message']; unset($_SESSION['info_message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

<!-- Content starts here -->
