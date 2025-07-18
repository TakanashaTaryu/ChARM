<?php
// File: src/db_connection.php

$servername = "localhost";
$username_db = "admin";
$password_db = "admin";
$dbname = "charm_new"; // Changed from charm_new to match your SQL file

// Create connection with proper error handling
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Set charset to UTF-8
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    die("Connection failed. Please try again later.");
}

// Konfigurasi Midtrans
$midtrans_client_key = "SB-Mid-client-PTOwdx_ZDMJ31gS1";
$midtrans_server_key = "SB-Mid-server-p6MgCKt5insW_SC-ZwWTCM9_";
?>