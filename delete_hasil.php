<?php
require_once 'includes/functions.php';

if (isset($_POST['id_siswa']) && !empty($_POST['id_siswa'])) {
    $id_siswa = (int) $_POST['id_siswa'];
    
    // Delete related records first (foreign key constraints)
    $conn->query("DELETE FROM jawaban_siswa WHERE id_siswa = $id_siswa");
    $conn->query("DELETE FROM hasil_konsultasi WHERE id_siswa = $id_siswa");
    $conn->query("DELETE FROM siswa WHERE id_siswa = $id_siswa");
    
    header("Location: hasiloutput.php?deleted=1");
    exit;
}

header("Location: hasiloutput.php?error=1");
exit;
?>
