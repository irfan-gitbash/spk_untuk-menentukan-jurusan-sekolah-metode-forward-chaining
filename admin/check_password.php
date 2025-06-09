<?php
require_once '../includes/functions.php';

// Hanya dapat diakses dari localhost
if ($_SERVER['REMOTE_ADDR'] !== '127.0.0.1' && $_SERVER['REMOTE_ADDR'] !== '::1') {
    die("Akses ditolak");
}

// Password yang ingin diverifikasi
$password = 'admin123';
$stored_hash = '$2y$10$6jUJQRzZJIa1Xq9QJQBnMOmLJ.3o1JJ9x1VJbCHkKXNpUa7HPwUOq';

// Verifikasi password
if (password_verify($password, $stored_hash)) {
    echo "<p style='color:green'>Password valid! Hash cocok.</p>";
} else {
    echo "<p style='color:red'>Password tidak valid! Hash tidak cocok.</p>";
}

// Cek informasi hash
echo "<p>Info hash: " . password_get_info($stored_hash)['algo'] . "</p>";

// Buat hash baru untuk password yang sama
$new_hash = password_hash($password, PASSWORD_DEFAULT);
echo "<p>Hash baru untuk 'admin123': " . $new_hash . "</p>";

// Cek user di database
$query = "SELECT * FROM admin WHERE username = 'admin'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $admin = mysqli_fetch_assoc($result);
    echo "<p>Username: " . $admin['username'] . "</p>";
    echo "<p>Hash di database: " . $admin['password'] . "</p>";
    
    if (password_verify($password, $admin['password'])) {
        echo "<p style='color:green'>Password 'admin123' cocok dengan hash di database!</p>";
    } else {
        echo "<p style='color:red'>Password 'admin123' TIDAK cocok dengan hash di database!</p>";
    }
} else {
    echo "<p style='color:red'>User admin tidak ditemukan di database!</p>";
}
?>