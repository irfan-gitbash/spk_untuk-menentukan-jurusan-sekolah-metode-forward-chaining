<?php
// Initialize connection variable
$conn = null;

try {
    // Create mysqli connection - ubah password sesuai dengan konfigurasi MySQL lokal Anda
    $conn = mysqli_connect("localhost", "root", "", "spk_sekolah");
    
    // Check connection
    if (mysqli_connect_errno()) {
        throw new Exception("Failed to connect to MySQL: " . mysqli_connect_error());
    }
    
    // Set charset to utf8
    mysqli_set_charset($conn, "utf8");
} catch (Exception $e) {
    die("Connection error: " . $e->getMessage());
}
?>
