<?php
// File: src/config.php

$servername = "localhost";
$username_db = "admin";
$password_db = "admin";
$dbname = "charm_db";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Konfigurasi Midtrans
$midtrans_client_key = "SB-Mid-client-PTOwdx_ZDMJ31gS1";
$midtrans_server_key = "SB-Mid-server-p6MgCKt5insW_SC-ZwWTCM9_";