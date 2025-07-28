<?php
// Enhanced feedback renewal add with security improvements
include 'session.php';
include 'config.php';

// Set JSON header for AJAX responses
header('Content-Type: application/json');

// CSRF validation function
function validateCSRF() {
    if (!isset($_SESSION['csrf_token']) || !isset($_POST['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
}

// Error response function
function sendError($message) {
    echo json_encode(['success' => false, 'message' => $message]);
    exit;
}

// Success response function
function sendSuccess($message, $data = null) {
    $response = ['success' => true, 'message' => $message];
    if ($data) {
        $response['data'] = $data;
    }
    echo json_encode($response);
    exit;
}

try {
    // Handle comment submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment'])) {
        // Validate CSRF token
        if (!validateCSRF()) {
            sendError('Security validation failed. Please refresh the page and try again.');
        }
        
        // Validate and sanitize inputs
        $policy_id = filter_input(INPUT_POST, 'policy_id', FILTER_VALIDATE_INT);
        $comment = trim(filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES));
        
        if (!$policy_id || $policy_id <= 0) {
            sendError('Invalid policy ID.');
        }
        
        if (empty($comment) || strlen($comment) > 1000) {
            sendError('Comment must be between 1 and 1000 characters.');
        }
        
        // Check if policy exists
        $policy_check = $con->prepare("SELECT id FROM policy WHERE id = ?");
        $policy_check->bind_param("i", $policy_id);
        $policy_check->execute();
        
        if ($policy_check->get_result()->num_rows === 0) {
            $policy_check->close();
            sendError('Policy not found.');
        }
        $policy_check->close();
        
        // Insert comment
        $stmt = $con->prepare("INSERT INTO customer_feedback (policy_id, comment, created_at, created_by) VALUES (?, ?, NOW(), ?)");
        $created_by = $_SESSION['user_id'] ?? $_SESSION['username'] ?? 'system';
        $stmt->bind_param("iss", $policy_id, $comment, $created_by);
        
        if ($stmt->execute()) {
            $stmt->close();
            echo "<span class='text-success'><i class='bx bx-check-circle'></i> Comment added successfully!</span>";
        } else {
            $stmt->close();
            echo "<span class='text-danger'><i class='bx bx-x-circle'></i> Error adding comment. Please try again.</span>";
        }
        exit;
    }
    
    // Handle loading comments
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['load'])) {
        $policy_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        
        if (!$policy_id || $policy_id <= 0) {
            echo "<div class='alert alert-danger'>Invalid policy ID</div>";
            exit;
        }
        
        // Get comments with pagination
        $limit = 50; // Limit to last 50 comments
        $stmt = $con->prepare("
            SELECT cf.*, p.vehicle_number, p.name as customer_name 
            FROM customer_feedback cf 
            LEFT JOIN policy p ON cf.policy_id = p.id 
            WHERE cf.policy_id = ? 
            ORDER BY cf.created_at DESC 
            LIMIT ?
        ");
        $stmt->bind_param("ii", $policy_id, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            echo "<div class='comment-list'>";
            $comment_count = 0;
            
            while ($comment = $result->fetch_assoc()) {
                $comment_count++;
                $comment_text = htmlspecialchars($comment['comment']);
                $created_at = date("d-m-Y H:i A", strtotime($comment['created_at']));
                $created_by = htmlspecialchars($comment['created_by'] ?? 'Unknown');
                
                echo "<div class='comment-box mb-3'>";
                echo "<div class='d-flex justify-content-between align-items-start'>";
                echo "<div class='comment-content flex-grow-1'>";
                echo "<p class='mb-2'><strong>Comment #{$comment_count}:</strong> {$comment_text}</p>";
                echo "<div class='comment-meta'>";
                echo "<small class='text-muted'>";
                echo "<i class='bx bx-time mr-1'></i>Posted on: {$created_at}";
                if (!empty($created_by) && $created_by !== 'Unknown') {
                    echo " | <i class='bx bx-user mr-1'></i>By: {$created_by}";
                }
                echo "</small>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
                
                if ($comment_count < $result->num_rows) {
                    echo "<hr class='my-2'>";
                }
            }
            echo "</div>";
            
            if ($result->num_rows >= $limit) {
                echo "<div class='alert alert-info mt-3'>";
                echo "<i class='bx bx-info-circle mr-2'></i>Showing latest {$limit} comments. ";
                echo "There may be more comments in the system.";
                echo "</div>";
            }
        } else {
            echo "<div class='text-center py-4'>";
            echo "<i class='bx bx-comment-x display-4 text-muted'></i>";
            echo "<p class='text-muted mt-2'>No comments yet. Be the first to add a follow-up comment!</p>";
            echo "</div>";
        }
        
        $stmt->close();
        exit;
    }
    
    // Handle getting latest comment for table update
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['last'])) {
        $policy_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        
        if (!$policy_id || $policy_id <= 0) {
            echo "Invalid ID";
            exit;
        }
        
        $stmt = $con->prepare("
            SELECT comment, created_at 
            FROM customer_feedback 
            WHERE policy_id = ? 
            ORDER BY created_at DESC 
            LIMIT 1
        ");
        $stmt->bind_param("i", $policy_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $latest = $result->fetch_assoc();
            $comment = htmlspecialchars($latest['comment']);
            $date = date('d-m-Y', strtotime($latest['created_at']));
            
            // Truncate long comments for table display
            $short_comment = strlen($comment) > 50 ? substr($comment, 0, 50) . '...' : $comment;
            
            echo "<small>{$short_comment}</small>";
            echo "<br><em class='text-muted'>{$date}</em>";
        } else {
            echo "<small class='text-muted'>No comments yet</small>";
        }
        
        $stmt->close();
        exit;
    }
    
    // Handle comment statistics (optional endpoint)
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['stats'])) {
        $policy_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        
        if (!$policy_id || $policy_id <= 0) {
            sendError('Invalid policy ID');
        }
        
        $stmt = $con->prepare("
            SELECT 
                COUNT(*) as total_comments,
                MIN(created_at) as first_comment,
                MAX(created_at) as last_comment
            FROM customer_feedback 
            WHERE policy_id = ?
        ");
        $stmt->bind_param("i", $policy_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stats = $result->fetch_assoc();
        $stmt->close();
        
        sendSuccess('Statistics retrieved', $stats);
    }
    
} catch (Exception $e) {
    error_log("Feedback renewal error: " . $e->getMessage());
    
    if (isset($_POST['load']) || isset($_POST['last'])) {
        echo "<div class='alert alert-danger'>An error occurred. Please try again.</div>";
    } else {
        echo "<span class='text-danger'><i class='bx bx-x-circle'></i> An error occurred. Please try again.</span>";
    }
    exit;
}

// If no valid action is found
http_response_code(400);
echo "<span class='text-warning'>Invalid request</span>";
?>
