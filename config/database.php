<?php
// Initialize connection variable
$conn = null;

try {
    // Create mysqli connection
    $conn = mysqli_connect("localhost", "root", "root", "spk_sekolah");
    
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
