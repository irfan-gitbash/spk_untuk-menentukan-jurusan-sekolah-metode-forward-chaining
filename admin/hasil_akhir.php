<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Pagination and search parameters
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Get filter parameters
$filter_kecerdasan = isset($_GET['kecerdasan']) ? (int)$_GET['kecerdasan'] : 0;
$filter_jurusan = isset($_GET['jurusan']) ? (int)$_GET['jurusan'] : 0;
$filter_start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$filter_end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Ambil semua kecerdasan untuk filter
$query_kecerdasan = "SELECT * FROM kecerdasan ORDER BY kode_kecerdasan";
$result_kecerdasan = mysqli_query($conn, $query_kecerdasan);
$kecerdasan_list = [];
while ($row = mysqli_fetch_assoc($result_kecerdasan)) {
    $kecerdasan_list[] = $row;
}

// Ambil semua jurusan untuk filter
$query_jurusan = "SELECT * FROM jurusan ORDER BY jenis_sekolah, nama_jurusan";
$result_jurusan = mysqli_query($conn, $query_jurusan);
$jurusan_list = [];
while ($row = mysqli_fetch_assoc($result_jurusan)) {
    $jurusan_list[] = $row;
}

// Build query with filters and search
$query = "SELECT SQL_CALC_FOUND_ROWS h.*, j.kode_jurusan, j.nama_jurusan, j.jenis_sekolah, s.nama_lengkap, s.nisn, s.sekolah, k.kode_kecerdasan, k.nama_kecerdasan 
          FROM hasil_konsultasi h 
          JOIN jurusan j ON h.id_jurusan = j.id_jurusan 
          JOIN siswa s ON h.id_siswa = s.id_siswa 
          JOIN kecerdasan k ON h.id_kecerdasan = k.id_kecerdasan 
          WHERE 1=1";

if ($filter_kecerdasan > 0) {
    $query .= " AND h.id_kecerdasan = $filter_kecerdasan";
}

if ($filter_jurusan > 0) {
    $query .= " AND h.id_jurusan = $filter_jurusan";
}

if (!empty($filter_start_date)) {
    $query .= " AND DATE(h.tanggal) >= '" . mysqli_real_escape_string($conn, $filter_start_date) . "'";
}

if (!empty($filter_end_date)) {
    $query .= " AND DATE(h.tanggal) <= '" . mysqli_real_escape_string($conn, $filter_end_date) . "'";
}

if (!empty($search)) {
    $search_esc = mysqli_real_escape_string($conn, $search);
    $query .= " AND (s.nama_lengkap LIKE '%$search_esc%' OR s.nisn LIKE '%$search_esc%')";
}

$query .= " ORDER BY h.tanggal DESC LIMIT $limit OFFSET $offset";

$result = mysqli_query($conn, $query);
$hasil_list = [];
while ($row = mysqli_fetch_assoc($result)) {
    $hasil_list[] = $row;
}

// Get total rows for pagination
$total_result = mysqli_query($conn, "SELECT FOUND_ROWS() as total");
$total_row = mysqli_fetch_assoc($total_result);
$total_konsultasi = (int)$total_row['total'];

// Hitung statistik (without pagination, so fetch all matching rows)
$stats_query = "SELECT h.id_kecerdasan, k.kode_kecerdasan, k.nama_kecerdasan, h.id_jurusan, j.kode_jurusan, j.nama_jurusan, j.jenis_sekolah, COUNT(*) as count
                FROM hasil_konsultasi h
                JOIN jurusan j ON h.id_jurusan = j.id_jurusan
                JOIN kecerdasan k ON h.id_kecerdasan = k.id_kecerdasan
                JOIN siswa s ON h.id_siswa = s.id_siswa
                WHERE 1=1";

if ($filter_kecerdasan > 0) {
    $stats_query .= " AND h.id_kecerdasan = $filter_kecerdasan";
}

if ($filter_jurusan > 0) {
    $stats_query .= " AND h.id_jurusan = $filter_jurusan";
}

if (!empty($filter_start_date)) {
    $stats_query .= " AND DATE(h.tanggal) >= '" . mysqli_real_escape_string($conn, $filter_start_date) . "'";
}

if (!empty($filter_end_date)) {
    $stats_query .= " AND DATE(h.tanggal) <= '" . mysqli_real_escape_string($conn, $filter_end_date) . "'";
}

if (!empty($search)) {
    $stats_query .= " AND (s.nama_lengkap LIKE '%$search_esc%' OR s.nisn LIKE '%$search_esc%')";
}

$stats_query .= " GROUP BY h.id_kecerdasan, h.id_jurusan";

$stats_result = mysqli_query($conn, $stats_query);

$kecerdasan_stats = [];
$jurusan_stats = [];
$total_stat_count = 0;

while ($row = mysqli_fetch_assoc($stats_result)) {
    $total_stat_count += $row['count'];

    // Statistik kecerdasan
    $id_kecerdasan = $row['id_kecerdasan'];
    if (!isset($kecerdasan_stats[$id_kecerdasan])) {
        $kecerdasan_stats[$id_kecerdasan] = [
            'kode' => $row['kode_kecerdasan'],
            'nama' => $row['nama_kecerdasan'],
            'count' => 0
        ];
    }
    $kecerdasan_stats[$id_kecerdasan]['count'] += $row['count'];

    // Statistik jurusan
    $id_jurusan = $row['id_jurusan'];
    if (!isset($jurusan_stats[$id_jurusan])) {
        $jurusan_stats[$id_jurusan] = [
            'kode' => $row['kode_jurusan'],
            'nama' => $row['nama_jurusan'],
            'jenis' => $row['jenis_sekolah'],
            'count' => 0
        ];
    }
    $jurusan_stats[$id_jurusan]['count'] += $row['count'];
}

// Hitung persentase
foreach ($kecerdasan_stats as $id => $stat) {
    $kecerdasan_stats[$id]['percentage'] = $total_stat_count > 0 ? round(($stat['count'] / $total_stat_count) * 100, 1) : 0;
}

foreach ($jurusan_stats as $id => $stat) {
    $jurusan_stats[$id]['percentage'] = $total_stat_count > 0 ? round(($stat['count'] / $total_stat_count) * 100, 1) : 0;
}

// Sort by count (descending)
uasort($kecerdasan_stats, function($a, $b) {
    return $b['count'] - $a['count'];
});

uasort($jurusan_stats, function($a, $b) {
    return $b['count'] - $a['count'];
});

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Hasil Akhir - SPK Jurusan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4640DE',
                        secondary: '#8E8CF3',
                        accent: '#F6F6FE',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-[#F9F9F9]">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-gray-800">SPK Jurusan</h1>
                <p class="text-sm text-gray-500">Panel Admin</p>
            </div>
            <nav class="mt-4">
                <a href="dashboard.php" class="flex items-center px-6 py-3 text-gray-600 hover:bg-accent hover:text-primary">
                    <i class="fas fa-home w-5"></i>
                    <span class="ml-3">Dashboard</span>
                </a>
                <a href="manage_siswa.php" class="flex items-center px-6 py-3 text-gray-600 hover:bg-accent hover:text-primary">
                    <i class="fas fa-user-graduate w-5"></i>
                    <span class="ml-3">Kelola Siswa</span>
                </a>
                <a href="manage_jurusan.php" class="flex items-center px-6 py-3 text-gray-600 hover:bg-accent hover:text-primary">
                    <i class="fas fa-book w-5"></i>
                    <span class="ml-3">Kelola Jurusan</span>
                </a>
                <a href="manage_pertanyaan.php" class="flex items-center px-6 py-3 text-gray-600 hover:bg-accent hover:text-primary">
                    <i class="fas fa-question-circle w-5"></i>
                    <span class="ml-3">Kelola Pertanyaan</span>
                </a>
                <a href="manage_kecerdasan.php" class="flex items-center px-6 py-3 text-gray-600 hover:bg-accent hover:text-primary">
                    <i class="fas fa-brain w-5"></i>
                    <span class="ml-3">Kelola Kecerdasan</span>
                </a>
                <a href="manage_aturan.php" class="flex items-center px-6 py-3 text-gray-600 hover:bg-accent hover:text-primary">
                    <i class="fas fa-cogs w-5"></i>
                    <span class="ml-3">Kelola Aturan</span>
                </a>
                <a href="hasil_akhir.php" class="flex items-center px-6 py-3 bg-accent text-primary">
                    <i class="fas fa-chart-bar w-5"></i>
                    <span class="ml-3">Hasil Akhir</span>
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">Hasil Akhir Konsultasi</h2>
            </div>

            <!-- Filter Section -->
            <div class="bg-white rounded-xl p-6 border border-gray-100 shadow mb-6">
                <form method="get" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label for="kecerdasan" class="block text-sm font-medium text-gray-700 mb-1">Filter Kecerdasan</label>
                        <select name="kecerdasan" id="kecerdasan" class="w-full rounded-lg border-gray-300 text-gray-700 text-sm focus:ring-primary focus:border-primary">
                            <option value="0">Semua Kecerdasan</option>
                            <?php foreach ($kecerdasan_list as $kecerdasan): ?>
                                <option value="<?php echo $kecerdasan['id_kecerdasan']; ?>" <?php echo $filter_kecerdasan == $kecerdasan['id_kecerdasan'] ? 'selected' : ''; ?>>
                                    <?php echo $kecerdasan['kode_kecerdasan'] . ' - ' . $kecerdasan['nama_kecerdasan']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label for="jurusan" class="block text-sm font-medium text-gray-700 mb-1">Filter Jurusan</label>
                        <select name="jurusan" id="jurusan" class="w-full rounded-lg border-gray-300 text-gray-700 text-sm focus:ring-primary focus:border-primary">
                            <option value="0">Semua Jurusan</option>
                            <optgroup label="SMA">
                                <?php foreach ($jurusan_list as $jurusan): ?>
                                    <?php if ($jurusan['jenis_sekolah'] == 'SMA'): ?>
                                        <option value="<?php echo $jurusan['id_jurusan']; ?>" <?php echo $filter_jurusan == $jurusan['id_jurusan'] ? 'selected' : ''; ?>>
                                            <?php echo $jurusan['kode_jurusan'] . ' - ' . $jurusan['nama_jurusan']; ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </optgroup>
                            <optgroup label="SMK">
                                <?php foreach ($jurusan_list as $jurusan): ?>
                                    <?php if ($jurusan['jenis_sekolah'] == 'SMK'): ?>
                                        <option value="<?php echo $jurusan['id_jurusan']; ?>" <?php echo $filter_jurusan == $jurusan['id_jurusan'] ? 'selected' : ''; ?>>
                                            <?php echo $jurusan['kode_jurusan'] . ' - ' . $jurusan['nama_jurusan']; ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </optgroup>
                        </select>
                    </div>

                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date" value="<?php echo htmlspecialchars($filter_start_date); ?>" class="w-full rounded-lg border-gray-300 text-gray-700 text-sm focus:ring-primary focus:border-primary" />
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                        <input type="date" name="end_date" id="end_date" value="<?php echo htmlspecialchars($filter_end_date); ?>" class="w-full rounded-lg border-gray-300 text-gray-700 text-sm focus:ring-primary focus:border-primary" />
                    </div>

                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Nama/NISN</label>
                        <input type="text" name="search" id="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Cari siswa..." class="w-full rounded-lg border-gray-300 text-gray-700 text-sm focus:ring-primary focus:border-primary" />
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition-colors">
                            <i class="fas fa-filter mr-2"></i>Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-xl p-6 border border-gray-100 shadow">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Total Konsultasi</h3>
                        <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center">
                            <i class="fas fa-users text-primary"></i>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-800"><?php echo $total_konsultasi; ?></p>
                    <p class="text-sm text-gray-500 mt-1">Hasil konsultasi</p>
                </div>
                
                <div class="bg-white rounded-xl p-6 border border-gray-100 shadow">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Kecerdasan Dominan</h3>
                        <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-brain text-green-600"></i>
                        </div>
                    </div>
                    <p class="text-xl font-semibold text-gray-800 mb-2"><?php echo reset($kecerdasan_stats)['nama'] ?? '-'; ?></p>
                    <canvas id="kecerdasanChart" class="w-full h-48"></canvas>
                </div>

                <div class="bg-white rounded-xl p-6 border border-gray-100 shadow">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Jurusan Dominan</h3>
                        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-book text-blue-600"></i>
                        </div>
                    </div>
                    <p class="text-xl font-semibold text-gray-800 mb-2"><?php echo reset($jurusan_stats)['nama'] ?? '-'; ?></p>
                    <canvas id="jurusanChart" class="w-full h-48"></canvas>
                </div>
            </div>

            <!-- Results Table -->
            <div class="bg-white rounded-xl p-6 border border-gray-100 shadow mb-6 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Tanggal</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Nama Siswa</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">NISN</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Sekolah</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Kecerdasan</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-700">Jurusan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (count($hasil_list) > 0): ?>
                            <?php foreach ($hasil_list as $hasil): ?>
                                <tr>
                                    <td class="px-4 py-2"><?php echo date('d-m-Y', strtotime($hasil['tanggal'])); ?></td>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($hasil['nama_lengkap']); ?></td>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($hasil['nisn']); ?></td>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($hasil['sekolah']); ?></td>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($hasil['nama_kecerdasan']); ?></td>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($hasil['nama_jurusan']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-500">Tidak ada data hasil konsultasi.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex justify-center space-x-2">
                <?php
                $total_pages = ceil($total_konsultasi / $limit);
                $query_params = $_GET;
                for ($p = 1; $p <= $total_pages; $p++):
                    $query_params['page'] = $p;
                    $url = $_SERVER['PHP_SELF'] . '?' . http_build_query($query_params);
                ?>
                    <a href="<?php echo $url; ?>" class="px-3 py-1 rounded <?php echo $p == $page ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700 hover:bg-primary hover:text-white'; ?>">
                        <?php echo $p; ?>
                    </a>
                <?php endfor; ?>
            </div>

        </div>
    </div>

    <script>
        const kecerdasanData = {
            labels: <?php echo json_encode(array_column($kecerdasan_stats, 'nama')); ?>,
            datasets: [{
                label: 'Persentase Kecerdasan',
                data: <?php echo json_encode(array_column($kecerdasan_stats, 'percentage')); ?>,
                backgroundColor: [
                    '#4ade80', '#22d3ee', '#facc15', '#f87171', '#a78bfa', '#fbbf24', '#60a5fa', '#f472b6'
                ],
                borderWidth: 1
            }]
        };

        const jurusanData = {
            labels: <?php echo json_encode(array_column($jurusan_stats, 'nama')); ?>,
            datasets: [{
                label: 'Persentase Jurusan',
                data: <?php echo json_encode(array_column($jurusan_stats, 'percentage')); ?>,
                backgroundColor: [
                    '#3b82f6', '#10b981', '#f97316', '#8b5cf6', '#ec4899', '#14b8a6', '#f43f5e', '#6366f1'
                ],
                borderWidth: 1
            }]
        };

        const kecerdasanConfig = {
            type: 'pie',
            data: kecerdasanData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed + '%';
                            }
                        }
                    }
                }
            },
        };

        const jurusanConfig = {
            type: 'bar',
            data: jurusanData,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + '%';
                            }
                        }
                    }
                }
            },
        };

        window.addEventListener('DOMContentLoaded', () => {
            const kecerdasanCtx = document.getElementById('kecerdasanChart').getContext('2d');
            new Chart(kecerdasanCtx, kecerdasanConfig);

            const jurusanCtx = document.getElementById('jurusanChart').getContext('2d');
            new Chart(jurusanCtx, jurusanConfig);
        });
    </script>
</body>
</html>
