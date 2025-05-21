<?php
require_once 'includes/functions.php';
require_once 'vendor/tcpdf/tcpdf.php';

// Cek apakah ada ID siswa
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('ID siswa tidak valid');
}

$id_siswa = (int) $_GET['id'];
$hasil = getHasilKonsultasi($id_siswa);

// Jika hasil tidak ditemukan
if (!$hasil) {
    die('Data tidak ditemukan');
}

// Create new PDF document
class MYPDF extends TCPDF {
    public function Header() {
        $this->SetFont('helvetica', 'B', 16);
        $this->Cell(0, 15, 'Hasil Konsultasi Pemilihan Jurusan', 0, true, 'C');
        $this->SetFont('helvetica', '', 12);
        $this->Cell(0, 10, 'Sistem Pendukung Keputusan Pemilihan Jurusan SMA dan SMK', 0, true, 'C');
        $this->Ln(10);
    }
    
    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Halaman '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C');
    }
}

// Create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator('SPK Jurusan');
$pdf->SetAuthor('Administrator');
$pdf->SetTitle('Hasil Konsultasi - ' . $hasil['nama_lengkap']);

// Set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// Set margins
$pdf->SetMargins(15, 50, 15);
$pdf->SetHeaderMargin(5);
$pdf->SetFooterMargin(10);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 25);

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Data Siswa Section
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Data Siswa', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 12);

$pdf->Cell(40, 7, 'Nama', 0, 0);
$pdf->Cell(5, 7, ':', 0, 0);
$pdf->Cell(0, 7, $hasil['nama_lengkap'], 0, 1);

$pdf->Cell(40, 7, 'NISN', 0, 0);
$pdf->Cell(5, 7, ':', 0, 0);
$pdf->Cell(0, 7, $hasil['nisn'], 0, 1);

$pdf->Cell(40, 7, 'Kelas', 0, 0);
$pdf->Cell(5, 7, ':', 0, 0);
$pdf->Cell(0, 7, $hasil['kelas'], 0, 1);

$pdf->Cell(40, 7, 'Sekolah', 0, 0);
$pdf->Cell(5, 7, ':', 0, 0);
$pdf->Cell(0, 7, $hasil['sekolah'], 0, 1);

$pdf->Ln(10);

// Hasil Konsultasi Section
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Hasil Konsultasi', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 12);

$pdf->Cell(40, 7, 'Jenis Sekolah', 0, 0);
$pdf->Cell(5, 7, ':', 0, 0);
$pdf->Cell(0, 7, $hasil['jenis_sekolah'], 0, 1);

$pdf->Cell(40, 7, 'Jurusan', 0, 0);
$pdf->Cell(5, 7, ':', 0, 0);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 7, $hasil['nama_jurusan'], 0, 1);
$pdf->SetFont('helvetica', '', 12);

$pdf->Ln(5);

// Deskripsi Jurusan
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 7, 'Deskripsi Jurusan:', 0, 1);
$pdf->SetFont('helvetica', '', 12);
$pdf->MultiCell(0, 7, $hasil['deskripsi'], 0, 'J');

$pdf->Ln(5);

// Prospek Karir
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 7, 'Prospek Karir:', 0, 1);
$pdf->SetFont('helvetica', '', 12);
$pdf->MultiCell(0, 7, $hasil['prospek_karir'], 0, 'J');

$pdf->Ln(10);

// Tanggal dan Tanda Tangan
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 7, 'Dicetak pada: ' . date('d/m/Y H:i'), 0, 1, 'R');

// Output the PDF
$pdf->Output('Hasil_Konsultasi_' . $hasil['nama_lengkap'] . '.pdf', 'I');
