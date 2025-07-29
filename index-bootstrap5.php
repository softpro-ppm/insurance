<?php
// Check if user is already logged in
session_start();
if (isset($_SESSION['username'])) {
    header('Location: home-bootstrap5.php');
    exit;
}

// Handle login submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'connection.php';
    
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($username && $password) {
        // In a real application, you should hash passwords
        $query = "SELECT * FROM users WHERE username = ? AND password = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "ss", $username, $password);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($user = mysqli_fetch_assoc($result)) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['login_time'] = time();
            
            header('Location: home-bootstrap5.php');
            exit;
        } else {
            $error_message = "Invalid username or password";
        }
    } else {
        $error_message = "Please enter both username and password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Softpro Insurance Management System</title>
    
    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 1000px;
            width: 100%;
        }
        
        .login-left {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }
        
        .login-left::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="80" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="60" r="1" fill="rgba(255,255,255,0.1)"/></svg>');
            background-size: 50px 50px;
        }
        
        .login-right {
            padding: 3rem;
        }
        
        .form-control {
            border-radius: 12px;
            border: 2px solid #e9ecef;
            padding: 0.875rem 1.25rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 0.875rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .feature-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
        }
        
        .alert {
            border: none;
            border-radius: 12px;
            padding: 1rem 1.5rem;
        }
        
        .logo {
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }
        
        @media (max-width: 768px) {
            .login-left {
                padding: 2rem;
                text-align: center;
            }
            
            .login-right {
                padding: 2rem;
            }
        }
        
        .version-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .stat-item {
            text-align: center;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            line-height: 1;
        }
        
        .stat-label {
            font-size: 0.875rem;
            opacity: 0.9;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="version-badge">
        <i class="fas fa-code-branch me-1"></i>v2.0 Bootstrap 5
    </div>
    
    <div class="login-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="login-card">
                        <div class="row g-0">
                            <!-- Left Side - Branding -->
                            <div class="col-lg-6">
                                <div class="login-left d-flex flex-column justify-content-center h-100">
                                    <div class="logo">
                                        <img src="assets/logo.PNG" alt="Softpro" width="40" height="40">
                                    </div>
                                    
                                    <h2 class="fw-bold mb-3">Welcome to Softpro Insurance</h2>
                                    <p class="lead mb-4">Complete Bootstrap 5 Insurance Management System</p>
                                    
                                    <!-- Features List -->
                                    <div class="features-list">
                                        <div class="feature-item">
                                            <div class="feature-icon">
                                                <i class="fas fa-shield-alt"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Policy Management</h6>
                                                <small>Complete CRUD operations with DataTables</small>
                                            </div>
                                        </div>
                                        
                                        <div class="feature-item">
                                            <div class="feature-icon">
                                                <i class="fas fa-bell"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Smart Renewals</h6>
                                                <small>Automated renewal reminders & notifications</small>
                                            </div>
                                        </div>
                                        
                                        <div class="feature-item">
                                            <div class="feature-icon">
                                                <i class="fas fa-chart-line"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Analytics Dashboard</h6>
                                                <small>Real-time charts and revenue tracking</small>
                                            </div>
                                        </div>
                                        
                                        <div class="feature-item">
                                            <div class="feature-icon">
                                                <i class="fas fa-mobile-alt"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">Mobile Ready</h6>
                                                <small>Fully responsive Bootstrap 5 design</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Stats Grid -->
                                    <div class="stats-grid">
                                        <div class="stat-item">
                                            <div class="stat-number">100%</div>
                                            <div class="stat-label">Bootstrap 5</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-number">25+</div>
                                            <div class="stat-label">Features</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-number">24/7</div>
                                            <div class="stat-label">Support</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-number">∞</div>
                                            <div class="stat-label">Policies</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Right Side - Login Form -->
                            <div class="col-lg-6">
                                <div class="login-right">
                                    <div class="text-center mb-4">
                                        <h3 class="fw-bold text-dark">Sign In</h3>
                                        <p class="text-muted">Enter your credentials to access the dashboard</p>
                                    </div>
                                    
                                    <?php if (isset($error_message)): ?>
                                        <div class="alert alert-danger" role="alert">
                                            <i class="fas fa-exclamation-circle me-2"></i>
                                            <?php echo htmlspecialchars($error_message); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <form method="POST" action="" id="loginForm">
                                        <div class="mb-3">
                                            <label for="username" class="form-label fw-semibold">Username</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="fas fa-user text-muted"></i>
                                                </span>
                                                <input type="text" class="form-control border-start-0" id="username" name="username" 
                                                       placeholder="Enter your username" required value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <label for="password" class="form-label fw-semibold">Password</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="fas fa-lock text-muted"></i>
                                                </span>
                                                <input type="password" class="form-control border-start-0" id="password" name="password" 
                                                       placeholder="Enter your password" required>
                                                <button class="btn btn-outline-secondary border-start-0" type="button" id="togglePassword">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="rememberMe" name="remember_me">
                                                <label class="form-check-label text-muted" for="rememberMe">
                                                    Remember me
                                                </label>
                                            </div>
                                            <a href="#" class="text-decoration-none">Forgot password?</a>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary w-100 py-3">
                                            <i class="fas fa-sign-in-alt me-2"></i>
                                            Sign In to Dashboard
                                        </button>
                                    </form>
                                    
                                    <div class="text-center mt-4">
                                        <p class="text-muted">
                                            Demo credentials: <strong>admin / password</strong>
                                        </p>
                                    </div>
                                    
                                    <!-- Quick Links -->
                                    <div class="row mt-4">
                                        <div class="col-6">
                                            <div class="card border-0 bg-light h-100">
                                                <div class="card-body text-center py-3">
                                                    <i class="fas fa-question-circle text-primary fa-2x mb-2"></i>
                                                    <h6 class="card-title mb-0">Need Help?</h6>
                                                    <small class="text-muted">Contact Support</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="card border-0 bg-light h-100">
                                                <div class="card-body text-center py-3">
                                                    <i class="fas fa-book text-success fa-2x mb-2"></i>
                                                    <h6 class="card-title mb-0">Documentation</h6>
                                                    <small class="text-muted">User Guide</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap 5.3.3 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
        
        // Form validation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();
            
            if (!username || !password) {
                e.preventDefault();
                alert('Please enter both username and password');
                return false;
            }
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Signing In...';
            submitBtn.disabled = true;
            
            // Re-enable button after 5 seconds (in case of errors)
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 5000);
        });
        
        // Auto-focus username field
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('username').focus();
        });
        
        // Demo credentials auto-fill
        function fillDemoCredentials() {
            document.getElementById('username').value = 'admin';
            document.getElementById('password').value = 'password';
        }
        
        // Add click event to demo credentials text
        document.querySelector('p:contains("Demo credentials")').addEventListener('click', fillDemoCredentials);
    </script>
</body>
</html>
