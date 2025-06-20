<?php
require_once '../config/database.php';
require_once '../vendor/tcpdf/tcpdf.php';

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch all questions from the database
$query = "SELECT * FROM pertanyaan ORDER BY id_pertanyaan ASC";
$result = mysqli_query($conn, $query);
$pertanyaan_list = [];
while ($row = mysqli_fetch_assoc($result)) {
    $pertanyaan_list[] = $row;
}

// Create new PDF document
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Set document information
$pdf->SetCreator('SPK Jurusan');
$pdf->SetAuthor('Admin');
$pdf->SetTitle('Daftar Pertanyaan');
$pdf->SetSubject('Daftar Pertanyaan Sistem Pendukung Keputusan');

// Remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Set margins
$pdf->SetMargins(15, 15, 15);

// Add a page
$pdf->AddPage();

// Title
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Daftar Pertanyaan', 0, 1, 'C');
$pdf->Ln(5);

// Table header
$pdf->SetFont('helvetica', 'B', 12);
$pdf->SetFillColor(224, 235, 255);
$pdf->Cell(20, 7, 'ID', 1, 0, 'C', 1);
$pdf->Cell(155, 7, 'Pertanyaan', 1, 1, 'C', 1);

// Table body
$pdf->SetFont('helvetica', '', 10);
$pdf->SetFillColor(248, 249, 250);

foreach ($pertanyaan_list as $pertanyaan) {
    // Get the height of the MultiCell
    $start_y = $pdf->GetY();
    $pdf->MultiCell(155, 0, $pertanyaan['pertanyaan'], 0, 'L');
    $end_y = $pdf->GetY();
    $cell_height = $end_y - $start_y;
    $pdf->SetXY(15, $start_y); // Reset X position to the beginning of the row

    // Draw the cells
    $pdf->Cell(20, $cell_height, $pertanyaan['id_pertanyaan'], 1, 0, 'C');
    $pdf->MultiCell(155, $cell_height, $pertanyaan['pertanyaan'], 1, 'L', false, 1);
}

// Close and output PDF document
$pdf->Output('daftar_pertanyaan.pdf', 'I');

?>