<?php
/**
 * Audit Logging System
 * Tracks all user activities for security and compliance
 */

class AuditLogger {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
        $this->createAuditTable();
    }
    
    /**
     * Create audit log table if not exists
     */
    private function createAuditTable() {
        $sql = "CREATE TABLE IF NOT EXISTS audit_log (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id VARCHAR(50),
            action VARCHAR(100),
            table_name VARCHAR(50),
            record_id INT,
            old_values TEXT,
            new_values TEXT,
            ip_address VARCHAR(45),
            user_agent TEXT,
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        $this->db->getConnection()->query($sql);
    }
    
    /**
     * Log user activity
     */
    public function log($action, $tableName = null, $recordId = null, $oldValues = null, $newValues = null) {
        $userId = isset($_SESSION['username']) ? $_SESSION['username'] : 'anonymous';
        $ipAddress = $this->getClientIP();
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        $sql = "INSERT INTO audit_log (user_id, action, table_name, record_id, old_values, new_values, ip_address, user_agent) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $this->db->query($sql, [
            $userId,
            $action,
            $tableName,
            $recordId,
            $oldValues ? json_encode($oldValues) : null,
            $newValues ? json_encode($newValues) : null,
            $ipAddress,
            $userAgent
        ]);
    }
    
    /**
     * Get client IP address
     */
    private function getClientIP() {
        $ipKeys = ['HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'];
        
        foreach ($ipKeys as $key) {
            if (!empty($_SERVER[$key])) {
                $ips = explode(',', $_SERVER[$key]);
                return trim($ips[0]);
            }
        }
        
        return 'unknown';
    }
    
    /**
     * Get audit logs with filtering
     */
    public function getLogs($userId = null, $action = null, $limit = 100) {
        $sql = "SELECT * FROM audit_log WHERE 1=1";
        $params = [];
        
        if ($userId) {
            $sql .= " AND user_id = ?";
            $params[] = $userId;
        }
        
        if ($action) {
            $sql .= " AND action LIKE ?";
            $params[] = "%$action%";
        }
        
        $sql .= " ORDER BY timestamp DESC LIMIT ?";
        $params[] = $limit;
        
        return $this->db->getRows($sql, $params);
    }
}

// Initialize audit logger
if (isset($secureDB)) {
    $auditLogger = new AuditLogger($secureDB);
}
?>
