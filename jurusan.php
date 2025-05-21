<?php
include 'includes/header.php';

// Ambil semua jurusan
$jurusan_sma = [];
$jurusan_smk = [];

$all_jurusan = getAllJurusan();
foreach ($all_jurusan as $jurusan) {
    if ($jurusan['jenis_sekolah'] == 'SMA') {
        $jurusan_sma[] = $jurusan;
    } else {
        $jurusan_smk[] = $jurusan;
    }
}
?>

<div class="container-fluid px-3 py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <h2 class="text-center mb-4">Daftar Jurusan</h2>
            
            <ul class="nav nav-pills nav-fill mb-4 gap-2" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active px-4 py-2" id="sma-tab" data-bs-toggle="tab" data-bs-target="#sma" type="button" role="tab" aria-controls="sma" aria-selected="true">
                        <i class="fas fa-school me-2"></i>SMA
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link px-4 py-2" id="smk-tab" data-bs-toggle="tab" data-bs-target="#smk" type="button" role="tab" aria-controls="smk" aria-selected="false">
                        <i class="fas fa-tools me-2"></i>SMK
                    </button>
                </li>
            </ul>
            
            <div class="tab-content" id="myTabContent">
                <!-- Tab SMA -->
                <div class="tab-pane fade show active" id="sma" role="tabpanel" aria-labelledby="sma-tab">
                    <div class="row g-3">
                        <?php foreach ($jurusan_sma as $jurusan): ?>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-header bg-primary text-white py-2">
                                        <h5 class="mb-0 fs-6"><?php echo $jurusan['nama_jurusan']; ?></h5>
                                    </div>
                                    <div class="card-body p-3">
                                        <p class="small mb-3"><?php echo $jurusan['deskripsi']; ?></p>
                                        <div class="border-top pt-2">
                                            <h6 class="fw-bold small mb-2">Prospek Karir:</h6>
                                            <p class="small mb-0 text-muted"><?php echo $jurusan['prospek_karir']; ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Tab SMK -->
                <div class="tab-pane fade" id="smk" role="tabpanel" aria-labelledby="smk-tab">
                    <div class="row g-3">
                        <?php foreach ($jurusan_smk as $jurusan): ?>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-header bg-primary text-white py-2">
                                        <h5 class="mb-0 fs-6"><?php echo $jurusan['nama_jurusan']; ?></h5>
                                    </div>
                                    <div class="card-body p-3">
                                        <p class="small mb-3"><?php echo $jurusan['deskripsi']; ?></p>
                                        <div class="border-top pt-2">
                                            <h6 class="fw-bold small mb-2">Prospek Karir:</h6>
                                            <p class="small mb-0 text-muted"><?php echo $jurusan['prospek_karir']; ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>