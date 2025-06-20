<?php
require_once 'includes/functions.php';

$jenis_sekolah = isset($_GET['jenis_sekolah']) ? $_GET['jenis_sekolah'] : '';
$semua_hasil = getAllHasilKonsultasi($jenis_sekolah);

// Hitung total pertanyaan per kecerdasan untuk menghitung persentase akurasi
function hitungTotalPertanyaanPerKecerdasan() {
    global $conn;
    $query = "SELECT id_kecerdasan, COUNT(*) as total FROM pertanyaan GROUP BY id_kecerdasan";
    $result = mysqli_query($conn, $query);
    $total_pertanyaan = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $total_pertanyaan[$row['id_kecerdasan']] = $row['total'];
    }
    
    return $total_pertanyaan;
}

$total_pertanyaan_per_kecerdasan = hitungTotalPertanyaanPerKecerdasan();

// Tambahkan informasi persentase kecocokan ke hasil konsultasi
foreach ($semua_hasil as &$hasil) {
    $id_kecerdasan = $hasil['id_kecerdasan'];
    $skor = $hasil['skor'];
    
    // Hitung persentase kecocokan
    $total_pertanyaan = isset($total_pertanyaan_per_kecerdasan[$id_kecerdasan]) ? 
                        $total_pertanyaan_per_kecerdasan[$id_kecerdasan] : 1; // Hindari pembagian dengan nol
    $hasil['persentase_kecocokan'] = round(($skor / $total_pertanyaan) * 100);
    
    // Tentukan level kecocokan berdasarkan persentase
    if ($hasil['persentase_kecocokan'] >= 80) {
        $hasil['level_kecocokan'] = 'Sangat Tinggi';
    } elseif ($hasil['persentase_kecocokan'] >= 60) {
        $hasil['level_kecocokan'] = 'Tinggi';
    } elseif ($hasil['persentase_kecocokan'] >= 40) {
        $hasil['level_kecocokan'] = 'Sedang';
    } elseif ($hasil['persentase_kecocokan'] >= 20) {
        $hasil['level_kecocokan'] = 'Rendah';
    } else {
        $hasil['level_kecocokan'] = 'Sangat Rendah';
    }
}
unset($hasil); // Lepaskan referensi

include 'includes/header.php';
?>

<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 sm:px-6 py-8 sm:py-12">
        <!-- Header Section -->
        <div class="text-center mb-8 sm:mb-12">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-3 sm:mb-4">Data Hasil Konsultasi</h1>
            <p class="text-gray-600 max-w-2xl mx-auto text-sm sm:text-base">
                Lihat hasil konsultasi pemilihan jurusan berdasarkan minat dan kemampuan siswa.
            </p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
            <!-- Total Konsultasi -->
            <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-primary/10 rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-primary text-lg sm:text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600">Total Konsultasi</p>
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900"><?php echo count($semua_hasil); ?></h3>
                    </div>
                </div>
            </div>

            <!-- Rekomendasi SMA -->
            <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-school text-green-600 text-lg sm:text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600">Rekomendasi SMA</p>
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900">
                            <?php echo count(array_filter($semua_hasil, function($h) { return $h['jenis_sekolah'] == 'SMA'; })); ?>
                        </h3>
                    </div>
                </div>
            </div>

            <!-- Rekomendasi SMK -->
            <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-tools text-blue-600 text-lg sm:text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600">Rekomendasi SMK</p>
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900">
                            <?php echo count(array_filter($semua_hasil, function($h) { return $h['jenis_sekolah'] == 'SMK'; })); ?>
                        </h3>
                    </div>
                </div>
            </div>

            <!-- Konsultasi Hari Ini -->
            <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-calendar-day text-yellow-600 text-lg sm:text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600">Konsultasi Hari Ini</p>
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900">
                            <?php echo count(array_filter($semua_hasil, function($h) { return date('Y-m-d', strtotime($h['tanggal'])) == date('Y-m-d'); })); ?>
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search Section -->
        <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 mb-6 sm:mb-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div>
                    <input type="text" id="searchInput" placeholder="Cari nama/NISN..." 
                        class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200 text-sm">
                </div>
                <!-- Filter Sekolah -->
                <div>
                    <select id="filterSekolah" onchange="window.location.href='hasiloutput.php?jenis_sekolah=' + this.value"
                        class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200 text-sm">
                        <option value="">Semua Jenis Sekolah</option>
                        <option value="SMA" <?php echo ($jenis_sekolah == 'SMA') ? 'selected' : ''; ?>>SMA</option>
                        <option value="SMK" <?php echo ($jenis_sekolah == 'SMK') ? 'selected' : ''; ?>>SMK</option>
                    </select>
                </div>
                <!-- Sort -->
                <div>
                    <select id="sortBy"
                        class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200 text-sm">
                        <option value="date_desc">Tanggal Terbaru</option>
                        <option value="date_asc">Tanggal Terlama</option>
                        <option value="name_asc">Nama A-Z</option>
                        <option value="name_desc">Nama Z-A</option>
                        <option value="accuracy_desc">Akurasi Tertinggi</option>
                        <option value="accuracy_asc">Akurasi Terendah</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Results Table -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="resultsTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                            <th class="hidden md:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NISN</th>
                            <th class="hidden lg:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sekolah</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Kecerdasan</th>
                            <th class="hidden sm:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kecocokan</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akurasi</th>
                            <th class="px-3 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (count($semua_hasil) > 0): ?>
                            <?php $no = 1; foreach ($semua_hasil as $hasil): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500"><?php echo $no++; ?></td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500">
                                    <?php echo date('d/m/Y', strtotime($hasil['tanggal'])); ?>
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                    <div class="text-xs sm:text-sm font-medium text-gray-900"><?php echo $hasil['nama_lengkap']; ?></div>
                                </td>
                                <td class="hidden md:table-cell px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500">
                                    <?php echo $hasil['nisn']; ?>
                                </td>
                                <td class="hidden lg:table-cell px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500">
                                    <?php echo $hasil['sekolah']; ?>
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
                                    <div class="text-gray-900"><?php echo $hasil['nama_kecerdasan']; ?></div>
                                </td>
                                <td class="hidden sm:table-cell px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                        <?php 
                                        $kecocokan_class = '';
                                        if ($hasil['level_kecocokan'] == 'Sangat Tinggi') {
                                            $kecocokan_class = 'bg-green-100 text-green-800';
                                        } elseif ($hasil['level_kecocokan'] == 'Tinggi') {
                                            $kecocokan_class = 'bg-blue-100 text-blue-800';
                                        } elseif ($hasil['level_kecocokan'] == 'Sedang') {
                                            $kecocokan_class = 'bg-yellow-100 text-yellow-800';
                                        } elseif ($hasil['level_kecocokan'] == 'Rendah') {
                                            $kecocokan_class = 'bg-orange-100 text-orange-800';
                                        } else {
                                            $kecocokan_class = 'bg-red-100 text-red-800';
                                        }
                                        echo $kecocokan_class;
                                        ?>"
                                    >
                                        <?php echo $hasil['level_kecocokan']; ?>
                                    </span>
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="h-2.5 rounded-full <?php echo $kecocokan_class; ?>" style="width: <?php echo $hasil['persentase_kecocokan']; ?>%"></div>
                                    </div>
                                    <div class="text-xs text-center mt-1"><?php echo $hasil['persentase_kecocokan']; ?>%</div>
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-center text-xs sm:text-sm font-medium">
                                    <div class="flex justify-center space-x-1 sm:space-x-2">
                                        <a href="hasil.php?id=<?php echo $hasil['id_siswa']; ?>" 
                                            class="text-primary hover:text-secondary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="cetak_hasil.php?id=<?php echo $hasil['id_siswa']; ?>" target="_blank"
                                            class="text-green-600 hover:text-green-700">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        <a href="delete_hasil.php?id=<?php echo $hasil['id_siswa']; ?>" 
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"
                                            class="text-red-600 hover:text-red-700">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="px-3 sm:px-6 py-3 sm:py-4 text-center text-xs sm:text-sm text-gray-500">
                                    Tidak ada data hasil konsultasi
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const sortBy = document.getElementById('sortBy');
    const table = document.getElementById('resultsTable');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    // Search functionality
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        rows.forEach(row => {
            const nama = row.cells[2]?.textContent.toLowerCase() || '';
            const nisn = row.cells[3]?.textContent.toLowerCase() || '';
            
            if (nama.includes(searchTerm) || nisn.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
    
    // Sort functionality
    sortBy.addEventListener('change', function() {
        const sortValue = this.value;
        
        rows.sort((a, b) => {
            let valueA, valueB;
            
            if (sortValue === 'date_desc' || sortValue === 'date_asc') {
                const dateA = a.cells[1].textContent.split('/');
                const dateB = b.cells[1].textContent.split('/');
                
                valueA = new Date(dateA[2], dateA[1] - 1, dateA[0]);
                valueB = new Date(dateB[2], dateB[1] - 1, dateB[0]);
                
                return sortValue === 'date_desc' ? valueB - valueA : valueA - valueB;
            } else if (sortValue === 'name_asc' || sortValue === 'name_desc') {
                valueA = a.cells[2].textContent.toLowerCase();
                valueB = b.cells[2].textContent.toLowerCase();
                
                return sortValue === 'name_desc' ? 
                    valueB.localeCompare(valueA) : 
                    valueA.localeCompare(valueB);
            } else if (sortValue === 'accuracy_desc' || sortValue === 'accuracy_asc') {
                // Ambil nilai persentase dari kolom akurasi
                valueA = parseInt(a.cells[7].querySelector('.text-xs').textContent);
                valueB = parseInt(b.cells[7].querySelector('.text-xs').textContent);
                
                return sortValue === 'accuracy_desc' ? valueB - valueA : valueA - valueB;
            }
        });
        
        // Remove existing rows
        while (tbody.firstChild) {
            tbody.removeChild(tbody.firstChild);
        }
        
        // Add sorted rows
        rows.forEach(row => {
            tbody.appendChild(row);
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>