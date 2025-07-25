<?php
/**
 * Input Validation and Sanitization Class
 * Prevents XSS, SQL Injection, and other security vulnerabilities
 */

class InputValidator {
    
    /**
     * Sanitize string input
     */
    public static function sanitizeString($input) {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        return $input;
    }
    
    /**
     * Validate and sanitize email
     */
    public static function validateEmail($email) {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $email;
        }
        return false;
    }
    
    /**
     * Validate and sanitize phone number
     */
    public static function validatePhone($phone) {
        $phone = preg_replace('/[^0-9+\-\s]/', '', $phone);
        $phone = trim($phone);
        
        if (preg_match('/^[+]?[0-9\-\s]{10,15}$/', $phone)) {
            return $phone;
        }
        return false;
    }
    
    /**
     * Validate and sanitize numeric input
     */
    public static function validateNumber($number, $min = null, $max = null) {
        if (!is_numeric($number)) {
            return false;
        }
        
        $number = (float)$number;
        
        if ($min !== null && $number < $min) {
            return false;
        }
        
        if ($max !== null && $number > $max) {
            return false;
        }
        
        return $number;
    }
    
    /**
     * Validate date format
     */
    public static function validateDate($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
    
    /**
     * Generate CSRF token
     */
    public static function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Validate CSRF token
     */
    public static function validateCSRFToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Sanitize file upload
     */
    public static function validateFileUpload($file, $allowedTypes = ['jpg', 'jpeg', 'png', 'pdf'], $maxSize = 5242880) {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }
        
        if ($file['size'] > $maxSize) {
            return false;
        }
        
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($fileExtension, $allowedTypes)) {
            return false;
        }
        
        // Additional security: Check MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        $allowedMimes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg', 
            'png' => 'image/png',
            'pdf' => 'application/pdf'
        ];
        
        if (!isset($allowedMimes[$fileExtension]) || $allowedMimes[$fileExtension] !== $mimeType) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Clean array input
     */
    public static function cleanArray($array) {
        $cleaned = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $cleaned[$key] = self::cleanArray($value);
            } else {
                $cleaned[$key] = self::sanitizeString($value);
            }
        }
        return $cleaned;
    }
    
    /**
     * Validate vehicle number format
     */
    public static function validateVehicleNumber($vehicleNumber) {
        $vehicleNumber = strtoupper(trim($vehicleNumber));
        // Indian vehicle number format: XX-XX-XX-XXXX or XX-XX-XXXX
        if (preg_match('/^[A-Z]{2}[0-9]{1,2}[A-Z]{1,2}[0-9]{4}$/', str_replace(['-', ' '], '', $vehicleNumber))) {
            return $vehicleNumber;
        }
        return false;
    }
}
?>
