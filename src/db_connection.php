<?php
// File: src/db_connection.php

// Load environment variables from .env file
function loadEnv($path) {
    if (!file_exists($path)) {
        throw new Exception('.env file not found at: ' . $path);
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue; // Skip comments
        }
        
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        
        if (!array_key_exists($name, $_ENV)) {
            $_ENV[$name] = $value;
        }
    }
}

// Load .env file from project root
try {
    $envPath = dirname(__DIR__) . '/.env';
    loadEnv($envPath);
} catch (Exception $e) {
    error_log("Environment file error: " . $e->getMessage());
    die("Configuration error. Please check server configuration.");
}

// Database configuration from environment variables
$servername = $_ENV['DB_HOST'] ?? 'localhost';
$username_db = $_ENV['DB_USERNAME'] ?? 'root';
$password_db = $_ENV['DB_PASSWORD'] ?? '';
$dbname = $_ENV['DB_NAME'] ?? 'charm_new';
$charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';

// Create connection with proper error handling
try {
    $conn = new mysqli($servername, $username_db, $password_db, $dbname);
    
    // Set charset
    $conn->set_charset($charset);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
} catch (Exception $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Connection failed. Please try again later.");
}

// Midtrans configuration from environment variables
$midtrans_client_key = $_ENV['MIDTRANS_CLIENT_KEY'] ?? '';
$midtrans_server_key = $_ENV['MIDTRANS_SERVER_KEY'] ?? '';

// Optional: Set error reporting based on environment
if (($_ENV['APP_ENV'] ?? 'production') === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
}
?>