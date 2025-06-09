<?php
require_once 'includes/functions.php';
require_once 'vendor/tcpdf/tcpdf.php';

// Cek apakah ada ID siswa
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: konsultasi.php");
    exit;
}

$id_siswa = (int) $_GET['id'];
$siswa = getSiswaById($id_siswa);

// Jika siswa tidak ditemukan
if (!$siswa) {
    header("Location: konsultasi.php");
    exit;
}

// Ambil hasil konsultasi
$hasil = getHasilKonsultasi($id_siswa);

// Jika hasil tidak ditemukan
if (!$hasil) {
    header("Location: konsultasi.php");
    exit;
}

// Dapatkan skor kecerdasan
$skor_kecerdasan = hitungSkorKecerdasan($id_siswa);
$id_kecerdasan_dominan = getKecerdasanDominan($id_siswa);

// Dapatkan data kecerdasan dominan
$query = "SELECT * FROM kecerdasan WHERE id_kecerdasan = $id_kecerdasan_dominan";
$result = mysqli_query($conn, $query);
$kecerdasan_dominan = mysqli_fetch_assoc($result);

// Dapatkan jurusan yang sesuai dengan kecerdasan dominan
$jurusan_rekomendasi = getJurusanByKecerdasan($id_kecerdasan_dominan);

// Ambil jawaban siswa untuk penjelasan forward chaining
$jawaban_siswa = getJawabanSiswa($id_siswa);

// Hitung total pertanyaan per kecerdasan
$total_pertanyaan_per_kecerdasan = [];
$query = "SELECT id_kecerdasan, COUNT(*) as total FROM pertanyaan GROUP BY id_kecerdasan";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $total_pertanyaan_per_kecerdasan[$row['id_kecerdasan']] = $row['total'];
}

// Hitung persentase kecocokan
$persentase_kecocokan = [];
foreach ($skor_kecerdasan as $id_kecerdasan => $skor) {
    $total_pertanyaan = isset($total_pertanyaan_per_kecerdasan[$id_kecerdasan]) ? 
                        $total_pertanyaan_per_kecerdasan[$id_kecerdasan] : 1; // Hindari pembagian dengan nol
    $persentase_kecocokan[$id_kecerdasan] = round(($skor / $total_pertanyaan) * 100);
}

// Buat PDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator('SPK Jurusan');
$pdf->SetAuthor('SPK Jurusan');
$pdf->SetTitle('Hasil Konsultasi - ' . $siswa['nama_lengkap']);
$pdf->SetSubject('Hasil Konsultasi SPK Jurusan');

// Hapus header dan footer default
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Set margin
$pdf->SetMargins(15, 15, 15);

// Tambah halaman
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', 'B', 16);

// Judul
$pdf->Cell(0, 10, 'HASIL KONSULTASI SISTEM PENDUKUNG KEPUTUSAN', 0, 1, 'C');
$pdf->Cell(0, 10, 'PEMILIHAN JURUSAN SMA/SMK', 0, 1, 'C');
$pdf->Ln(10);

// Data Siswa
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'DATA SISWA', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(40, 7, 'Nama', 0, 0, 'L');
$pdf->Cell(5, 7, ':', 0, 0, 'L');
$pdf->Cell(0, 7, $siswa['nama_lengkap'], 0, 1, 'L');
$pdf->Cell(40, 7, 'NISN', 0, 0, 'L');
$pdf->Cell(5, 7, ':', 0, 0, 'L');
$pdf->Cell(0, 7, $siswa['nisn'], 0, 1, 'L');
$pdf->Cell(40, 7, 'Kelas', 0, 0, 'L');
$pdf->Cell(5, 7, ':', 0, 0, 'L');
$pdf->Cell(0, 7, $siswa['kelas'], 0, 1, 'L');
$pdf->Cell(40, 7, 'Sekolah', 0, 0, 'L');
$pdf->Cell(5, 7, ':', 0, 0, 'L');
$pdf->Cell(0, 7, $siswa['sekolah'], 0, 1, 'L');
$pdf->Cell(40, 7, 'Tanggal Konsultasi', 0, 0, 'L');
$pdf->Cell(5, 7, ':', 0, 0, 'L');
$pdf->Cell(0, 7, date('d-m-Y H:i', strtotime($hasil['tanggal'])), 0, 1, 'L');
$pdf->Ln(5);

// Kecerdasan Dominan
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'KECERDASAN DOMINAN', 0, 1, 'L');
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 7, $kecerdasan_dominan['nama_kecerdasan'], 0, 1, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->MultiCell(0, 7, $kecerdasan_dominan['deskripsi'], 0, 'L');
$pdf->Ln(5);

// Hasil Rekomendasi
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'HASIL REKOMENDASI JURUSAN', 0, 1, 'L');
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 7, $hasil['nama_jurusan'] . ' (' . $hasil['jenis_sekolah'] . ')', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->MultiCell(0, 7, $hasil['deskripsi'], 0, 'L');
$pdf->Ln(3);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 7, 'Prospek Karir:', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->MultiCell(0, 7, $hasil['prospek_karir'], 0, 'L');
$pdf->Ln(5);

// Penjelasan Metode Forward Chaining
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'PENJELASAN METODE FORWARD CHAINING', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 11);
$pdf->MultiCell(0, 7, 'Metode Forward Chaining adalah metode inferensi dalam sistem pakar yang memulai penalaran dari fakta-fakta yang ada untuk mencapai kesimpulan. Dalam aplikasi ini, metode forward chaining digunakan untuk menentukan kecerdasan dominan dan rekomendasi jurusan berdasarkan jawaban yang diberikan.', 0, 'L');
$pdf->Ln(3);

// Tabel Perhitungan Skor Kecerdasan
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 7, 'Perhitungan Skor Kecerdasan:', 0, 1, 'L');
$pdf->Ln(2);

// Header Tabel
$pdf->SetFillColor(220, 220, 220);
$pdf->Cell(80, 7, 'Jenis Kecerdasan', 1, 0, 'C', true);
$pdf->Cell(30, 7, 'Skor', 1, 0, 'C', true);
$pdf->Cell(30, 7, 'Total Pertanyaan', 1, 0, 'C', true);
$pdf->Cell(30, 7, 'Persentase', 1, 1, 'C', true);

// Isi Tabel
$pdf->SetFont('helvetica', '', 10);

// Ambil semua kecerdasan
$all_kecerdasan = getAllKecerdasan();
foreach ($all_kecerdasan as $kecerdasan) {
    $id_kecerdasan = $kecerdasan['id_kecerdasan'];
    $skor = isset($skor_kecerdasan[$id_kecerdasan]) ? $skor_kecerdasan[$id_kecerdasan] : 0;
    $total = isset($total_pertanyaan_per_kecerdasan[$id_kecerdasan]) ? $total_pertanyaan_per_kecerdasan[$id_kecerdasan] : 0;
    $persentase = isset($persentase_kecocokan[$id_kecerdasan]) ? $persentase_kecocokan[$id_kecerdasan] . '%' : '0%';
    
    // Highlight kecerdasan dominan
    $fill = ($id_kecerdasan == $id_kecerdasan_dominan);
    if ($fill) {
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetFillColor(200, 230, 200);
    } else {
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetFillColor(255, 255, 255);
    }
    
    $pdf->Cell(80, 7, $kecerdasan['nama_kecerdasan'], 1, 0, 'L', $fill);
    $pdf->Cell(30, 7, $skor, 1, 0, 'C', $fill);
    $pdf->Cell(30, 7, $total, 1, 0, 'C', $fill);
    $pdf->Cell(30, 7, $persentase, 1, 1, 'C', $fill);
}
$pdf->Ln(5);

// Penjelasan Aturan Forward Chaining
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 7, 'Aturan Forward Chaining yang Digunakan:', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 11);

// Penjelasan aturan dengan if-then
$pdf->MultiCell(0, 7, 'Berikut adalah aturan-aturan yang digunakan dalam metode forward chaining:', 0, 'L');
$pdf->Ln(2);

// Aturan 1
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(0, 7, 'Aturan 1: Perhitungan Skor Kecerdasan', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(0, 7, 'IF jawaban = "Ya" THEN tambahkan skor pada kecerdasan terkait', 0, 'L');
$pdf->MultiCell(0, 7, 'Penjelasan: Setiap kali siswa menjawab "Ya" pada pertanyaan yang terkait dengan suatu jenis kecerdasan, maka skor untuk kecerdasan tersebut akan bertambah 1.', 0, 'L');
$pdf->Ln(2);

// Aturan 2
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(0, 7, 'Aturan 2: Penentuan Kecerdasan Dominan', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(0, 7, 'IF skor kecerdasan X > skor kecerdasan lainnya THEN kecerdasan X adalah kecerdasan dominan', 0, 'L');
$pdf->MultiCell(0, 7, 'Penjelasan: Sistem akan membandingkan skor dari semua jenis kecerdasan dan menentukan kecerdasan dengan skor tertinggi sebagai kecerdasan dominan.', 0, 'L');
$pdf->Ln(2);

// Aturan 3
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(0, 7, 'Aturan 3: Penentuan Rekomendasi Jurusan', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(0, 7, 'IF kecerdasan dominan = X THEN rekomendasikan jurusan yang terkait dengan kecerdasan X', 0, 'L');
$pdf->MultiCell(0, 7, 'Penjelasan: Setelah kecerdasan dominan ditentukan, sistem akan merekomendasikan jurusan yang paling sesuai dengan kecerdasan tersebut berdasarkan relasi yang telah ditentukan dalam database.', 0, 'L');
$pdf->Ln(2);

// Hasil Penerapan Aturan pada Siswa Ini
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 7, 'Hasil Penerapan Aturan pada Konsultasi Ini:', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);

// Penjelasan hasil penerapan aturan
$pdf->MultiCell(0, 7, "1. Berdasarkan jawaban yang diberikan, kecerdasan {$kecerdasan_dominan['nama_kecerdasan']} memiliki skor tertinggi yaitu {$hasil['skor']} poin.", 0, 'L');
$pdf->MultiCell(0, 7, "2. Karena kecerdasan dominan adalah {$kecerdasan_dominan['nama_kecerdasan']}, maka jurusan yang direkomendasikan adalah {$hasil['nama_jurusan']} ({$hasil['jenis_sekolah']}).", 0, 'L');

// Tampilkan beberapa jawaban Ya yang berkontribusi pada kecerdasan dominan
$pdf->Ln(2);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(0, 7, 'Beberapa Jawaban yang Berkontribusi pada Kecerdasan Dominan:', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);

$count = 0;
foreach ($jawaban_siswa as $jawaban) {
    if ($jawaban['id_kecerdasan'] == $id_kecerdasan_dominan && $jawaban['jawaban'] == 'Ya') {
        $pdf->MultiCell(0, 7, "- {$jawaban['isi_pertanyaan']}", 0, 'L');
        $count++;
        if ($count >= 3) break; // Batasi hanya 3 contoh
    }
}

// Output PDF
$pdf->Output('hasil_konsultasi_' . $siswa['nama_lengkap'] . '.pdf', 'I');
