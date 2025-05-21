<?php
require_once 'includes/functions.php';

$error = '';
$success = '';

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Jika form data siswa
    if (isset($_POST['submit_siswa'])) {
        $nama = clean($_POST['nama']);
        $nisn = clean($_POST['nisn']);
        $kelas = clean($_POST['kelas']);
        $sekolah = clean($_POST['sekolah']);
        
        // Validasi input
        if (empty($nama) || empty($nisn) || empty($kelas) || empty($sekolah)) {
            $error = "Semua field harus diisi!";
        } else {
            // Simpan data siswa dan dapatkan ID
            $id_siswa = saveSiswa($nama, $nisn, $kelas, $sekolah);
            
            // Redirect ke halaman pertanyaan dengan ID siswa
            header("Location: question.php?id=$id_siswa");
            exit;
        }
    }
}

include 'includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Form Konsultasi</h4>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <form method="post" action="">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="nisn" class="form-label">NISN</label>
                        <input type="text" class="form-control" id="nisn" name="nisn" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="kelas" class="form-label">Kelas</label>
                        <input type="text" class="form-control" id="kelas" name="kelas" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="sekolah" class="form-label">Sekolah</label>
                        <input type="text" class="form-control" id="sekolah" name="sekolah" required>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" name="submit_siswa" class="btn btn-primary">Lanjutkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>