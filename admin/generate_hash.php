<?php
// Hanya dapat diakses dari localhost
if ($_SERVER['REMOTE_ADDR'] !== '127.0.0.1' && $_SERVER['REMOTE_ADDR'] !== '::1') {
    die("Akses ditolak");
}

// Generate hash untuk password admin123
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "<p>Hash baru untuk password 'admin123': " . $hash . "</p>";
echo "<p>Query SQL untuk update:</p>";
echo "<code>UPDATE admin SET password = '" . $hash . "' WHERE username = 'admin';</code>";
?>