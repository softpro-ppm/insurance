<?php
/**
 * Secure Database Class with Prepared Statements
 * Prevents SQL Injection attacks
 */

class SecureDB {
    private $connection;
    
    public function __construct($host, $username, $password, $database) {
        $this->connection = new mysqli($host, $username, $password, $database);
        
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
        
        // Set charset to prevent character set confusion attacks
        $this->connection->set_charset("utf8");
    }
    
    /**
     * Execute a prepared statement with parameters
     */
    public function query($sql, $params = [], $types = '') {
        $stmt = $this->connection->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->connection->error);
        }
        
        if (!empty($params)) {
            if (empty($types)) {
                // Auto-detect types
                $types = '';
                foreach ($params as $param) {
                    if (is_int($param)) {
                        $types .= 'i';
                    } elseif (is_float($param)) {
                        $types .= 'd';
                    } else {
                        $types .= 's';
                    }
                }
            }
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        
        if ($stmt->error) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        
        return $stmt;
    }
    
    /**
     * Get single row result
     */
    public function getRow($sql, $params = [], $types = '') {
        $stmt = $this->query($sql, $params, $types);
        $result = $stmt->get_result();
        
        if ($result) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    /**
     * Get all rows result
     */
    public function getRows($sql, $params = [], $types = '') {
        $stmt = $this->query($sql, $params, $types);
        $result = $stmt->get_result();
        $rows = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        
        return $rows;
    }
    
    /**
     * Insert data and return insert ID
     */
    public function insert($sql, $params = [], $types = '') {
        $stmt = $this->query($sql, $params, $types);
        return $this->connection->insert_id;
    }
    
    /**
     * Update/Delete data and return affected rows
     */
    public function execute($sql, $params = [], $types = '') {
        $stmt = $this->query($sql, $params, $types);
        return $stmt->affected_rows;
    }
    
    /**
     * Escape string for legacy code (use prepared statements instead)
     */
    public function escape($string) {
        return $this->connection->real_escape_string($string);
    }
    
    /**
     * Get connection for complex operations
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * Close connection
     */
    public function close() {
        $this->connection->close();
    }
}

// Initialize secure database connection
$secureDB = new SecureDB($host, $username, $password, $database);
?>
