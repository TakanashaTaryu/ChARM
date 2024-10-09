<?php
session_start();

// Hapus semua session
$_SESSION = array();
session_destroy();

// Redirect ke halaman login atau halaman utama setelah logout
header("Location: index.php");
exit();
?>
