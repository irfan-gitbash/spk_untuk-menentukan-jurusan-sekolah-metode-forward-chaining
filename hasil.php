<?php
require_once 'includes/functions.php';

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

include 'includes/header.php';
?>

<div class="container-fluid px-2 px-md-3 py-2">
    <div class="row justify-content-center g-2">
        <!-- Data Siswa Column -->
        <div class="col-12 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-info text-white py-2">
                    <h5 class="mb-0 fs-6">Data Siswa</h5>
                </div>
                <div class="card-body p-2 p-md-3">
                    <div class="row g-2">
                        <div class="col-6 col-lg-12">
                            <p class="mb-1 small"><strong>Nama:</strong> <?php echo $siswa['nama_lengkap']; ?></p>
                            <p class="mb-1 small"><strong>NISN:</strong> <?php echo $siswa['nisn']; ?></p>
                        </div>
                        <div class="col-6 col-lg-12">
                            <p class="mb-1 small"><strong>Kelas:</strong> <?php echo $siswa['kelas']; ?></p>
                            <p class="mb-1 small"><strong>Sekolah:</strong> <?php echo $siswa['sekolah']; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Column -->
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white py-2">
                    <h4 class="mb-0 text-center fs-5">Hasil Konsultasi</h4>
                </div>
                <div class="card-body p-2 p-md-3">
                    <!-- Rekomendasi Section -->
                    <div class="text-center mb-3 py-2">
                        <h3 class="fs-5 mb-2">Rekomendasi Jurusan</h3>
                        <div class="h3 text-primary fw-bold mb-1"><?php echo $hasil['nama_jurusan']; ?></div>
                        <div class="badge bg-secondary px-2 py-1"><?php echo $hasil['jenis_sekolah']; ?></div>
                    </div>

                    <div class="row g-2">
                        <!-- Deskripsi Card -->
                        <div class="col-12 col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-primary text-white py-1">
                                    <h5 class="mb-0 fs-6">Deskripsi Jurusan</h5>
                                </div>
                                <div class="card-body p-2">
                                    <p class="mb-0 small text-justify"><?php echo $hasil['deskripsi']; ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Prospek Card -->
                        <div class="col-12 col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-primary text-white py-1">
                                    <h5 class="mb-0 fs-6">Prospek Karir</h5>
                                </div>
                                <div class="card-body p-2">
                                    <p class="mb-0 small text-justify"><?php echo $hasil['prospek_karir']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-center gap-2 mt-3">
                        <a href="konsultasi.php" class="btn btn-primary btn-sm px-3">
                            <i class="fas fa-redo-alt me-1"></i>Konsultasi Baru
                        </a>
                        <a href="index.php" class="btn btn-secondary btn-sm px-3">
                            <i class="fas fa-home me-1"></i>Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>