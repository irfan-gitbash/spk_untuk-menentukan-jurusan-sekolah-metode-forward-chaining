<?php
require_once 'includes/functions.php';

$jenis_sekolah = isset($_GET['jenis_sekolah']) ? $_GET['jenis_sekolah'] : '';
$semua_hasil = getAllHasilKonsultasi($jenis_sekolah);

include 'includes/header.php';
?>

<div class="container-fluid px-2 px-md-3 py-2">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Data Hasil Konsultasi</h4>
                        <div>
                            <a href="konsultasi.php" class="btn btn-light btn-sm">
                                <i class="fas fa-plus me-1"></i>Konsultasi Baru
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <!-- Stats Summary -->
                    <div class="bg-light p-3 border-bottom">
                        <div class="row g-3">
                            <div class="col-12 col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body p-3">
                                        <h6 class="mb-1">Total Konsultasi</h6>
                                        <h3 class="mb-0"><?php echo count($semua_hasil); ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body p-3">
                                        <h6 class="mb-1">Rekomendasi SMA</h6>
                                        <h3 class="mb-0"><?php echo count(array_filter($semua_hasil, function($h) { return $h['jenis_sekolah'] == 'SMA'; })); ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body p-3">
                                        <h6 class="mb-1">Rekomendasi SMK</h6>
                                        <h3 class="mb-0"><?php echo count(array_filter($semua_hasil, function($h) { return $h['jenis_sekolah'] == 'SMK'; })); ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="card bg-warning text-dark">
                                    <div class="card-body p-3">
                                        <h6 class="mb-1">Konsultasi Hari Ini</h6>
                                        <h3 class="mb-0"><?php echo count(array_filter($semua_hasil, function($h) { return date('Y-m-d', strtotime($h['tanggal'])) == date('Y-m-d'); })); ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Section -->
                    <div class="p-3 border-bottom">
                        <div class="row g-2">
                            <div class="col-12 col-md-3">
                                <input type="text" class="form-control form-control-sm" id="searchInput" placeholder="Cari nama/NISN...">
                            </div>
                            <div class="col-12 col-md-3">
                            <select class="form-select form-select-sm" id="filterSekolah" onchange="window.location.href='hasiloutput.php?jenis_sekolah=' + this.value">
                                <option value="">Semua Jenis Sekolah</option>
                                <option value="SMA" <?php echo ($jenis_sekolah == 'SMA') ? 'selected' : ''; ?>>SMA</option>
                                <option value="SMK" <?php echo ($jenis_sekolah == 'SMK') ? 'selected' : ''; ?>>SMK</option>
                            </select>
                            </div>
                            <div class="col-12 col-md-3">
                                <select class="form-select form-select-sm" id="sortBy">
                                    <option value="date_desc">Tanggal Terbaru</option>
                                    <option value="date_asc">Tanggal Terlama</option>
                                    <option value="name_asc">Nama A-Z</option>
                                    <option value="name_desc">Nama Z-A</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Results Table -->
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle mb-0" id="resultsTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 50px">#</th>
                                    <th class="text-center" style="width: 120px">Tanggal</th>
                                    <th>Nama Siswa</th>
                                    <th class="text-center">NISN</th>
                                    <th>Sekolah</th>
                                    <th>Kelas</th>
                                    <th>Rekomendasi</th>
                                    <th class="text-center" style="width: 100px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($semua_hasil as $index => $h): ?>
                                <tr>
                                    <td class="text-center"><?php echo $index + 1; ?></td>
                                    <td class="text-center small">
                                        <div><?php echo date('d/m/Y', strtotime($h['tanggal'])); ?></div>
                                        <small class="text-muted"><?php echo date('H:i', strtotime($h['tanggal'])); ?></small>
                                    </td>
                                    <td>
                                        <div class="fw-bold"><?php echo $h['nama_lengkap']; ?></div>
                                    </td>
                                    <td class="text-center"><?php echo $h['nisn']; ?></td>
                                    <td><?php echo $h['sekolah']; ?></td>
                                    <td><?php echo $h['kelas']; ?></td>
                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            <span class="badge bg-primary"><?php echo $h['nama_jurusan']; ?></span>
                                            <span class="badge bg-secondary"><?php echo $h['jenis_sekolah']; ?></span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="hasil.php?id=<?php echo $h['id_siswa']; ?>" class="btn btn-info" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-primary" title="Cetak Hasil" onclick="printHasil(<?php echo $h['id_siswa']; ?>)">
                                                <i class="fas fa-print"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger" title="Hapus Data" onclick="deleteHasil(<?php echo $h['id_siswa']; ?>, '<?php echo addslashes($h['nama_lengkap']); ?>')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if (empty($semua_hasil)): ?>
                    <div class="text-center p-4">
                        <div class="text-muted mb-3">
                            <i class="fas fa-inbox fa-3x"></i>
                        </div>
                        <h5>Belum Ada Data</h5>
                        <p class="mb-0">Belum ada hasil konsultasi yang tersimpan.</p>
                        <div class="mt-3">
                            <a href="konsultasi.php" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Mulai Konsultasi
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for filtering and sorting -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const filterSekolah = document.getElementById('filterSekolah');
    const sortBy = document.getElementById('sortBy');
    const resultsTable = document.getElementById('resultsTable');
    const tbody = resultsTable.querySelector('tbody');

    function filterAndSortTable() {
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const searchTerm = searchInput.value.toLowerCase();
        const selectedSekolah = filterSekolah.value;

        // Filter rows
        const filteredRows = rows.filter(row => {
            const nama = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            const nisn = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
            const jenisSekolah = row.querySelector('td:nth-child(7)').textContent;
            
            const matchesSearch = nama.includes(searchTerm) || nisn.includes(searchTerm);
            const matchesSekolah = !selectedSekolah || jenisSekolah.includes(selectedSekolah);
            
            return matchesSearch && matchesSekolah;
        });

        // Sort rows
        filteredRows.sort((a, b) => {
            const getValue = (row, selector) => row.querySelector(selector).textContent;
            
            switch(sortBy.value) {
                case 'date_desc':
                    return new Date(getValue(b, 'td:nth-child(2)')) - new Date(getValue(a, 'td:nth-child(2)'));
                case 'date_asc':
                    return new Date(getValue(a, 'td:nth-child(2)')) - new Date(getValue(b, 'td:nth-child(2)'));
                case 'name_asc':
                    return getValue(a, 'td:nth-child(3)').localeCompare(getValue(b, 'td:nth-child(3)'));
                case 'name_desc':
                    return getValue(b, 'td:nth-child(3)').localeCompare(getValue(a, 'td:nth-child(3)'));
            }
        });

        // Update table
        tbody.innerHTML = '';
        filteredRows.forEach((row, index) => {
            row.querySelector('td:first-child').textContent = index + 1;
            tbody.appendChild(row);
        });
    }

    // Add event listeners
    searchInput.addEventListener('input', filterAndSortTable);
    filterSekolah.addEventListener('change', filterAndSortTable);
    sortBy.addEventListener('change', filterAndSortTable);
});

function printHasil(id) {
    window.open('cetak_hasil.php?id=' + id, '_blank');
}

function deleteHasil(id, nama) {
    if (confirm('Apakah Anda yakin ingin menghapus hasil konsultasi untuk siswa ' + nama + '?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'delete_hasil.php';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'id_siswa';
        input.value = id;
        
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}

// Show success message if deletion was successful
<?php if (isset($_GET['deleted'])): ?>
    document.addEventListener('DOMContentLoaded', function() {
        alert('Data hasil konsultasi berhasil dihapus');
    });
<?php endif; ?>
</script>

<?php include 'includes/footer.php'; ?>
