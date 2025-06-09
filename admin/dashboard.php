<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Fungsi untuk mendapatkan data dashboard
function getTotalSiswa() {
    global $conn;
    $query = "SELECT COUNT(*) as total FROM siswa";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total'];
}

function getTotalKonsultasi() {
    global $conn;
    $query = "SELECT COUNT(*) as total FROM hasil_konsultasi";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total'];
}

function getTotalByJenisSekolah($jenis) {
    global $conn;
    $query = "SELECT COUNT(*) as total FROM hasil_konsultasi hk 
              JOIN jurusan j ON hk.id_jurusan = j.id_jurusan 
              WHERE j.jenis_sekolah = '$jenis'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total'];
}

$total_siswa = getTotalSiswa();
$total_konsultasi = getTotalKonsultasi();
$total_sma = getTotalByJenisSekolah('SMA');
$total_smk = getTotalByJenisSekolah('SMK');

// Get latest consultations
$query_terbaru = "SELECT s.nama_lengkap, s.sekolah, j.nama_jurusan, j.jenis_sekolah, hk.tanggal 
                 FROM hasil_konsultasi hk 
                 JOIN siswa s ON hk.id_siswa = s.id_siswa 
                 JOIN jurusan j ON hk.id_jurusan = j.id_jurusan 
                 ORDER BY hk.tanggal DESC LIMIT 5";
$result_terbaru = mysqli_query($conn, $query_terbaru);
$konsultasi_terbaru = [];
while ($row = mysqli_fetch_assoc($result_terbaru)) {
    $konsultasi_terbaru[] = $row;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SPK Jurusan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
    <div class="flex flex-col md:flex-row min-h-screen">
        <!-- Sidebar - Hidden on mobile by default, toggleable -->
        <div id="sidebar" class="hidden md:block md:w-64 bg-white shadow-lg fixed md:sticky top-0 h-screen z-30 overflow-y-auto transition-all duration-300">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-gray-800">SPK Jurusan</h1>
                <p class="text-sm text-gray-500">Panel Admin</p>
            </div>
            <nav class="mt-4">
                <a href="dashboard.php" class="flex items-center px-6 py-3 bg-accent text-primary">
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
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 w-full">
            <!-- Top Navigation -->
            <div class="bg-white shadow-sm sticky top-0 z-20">
                <div class="flex justify-between items-center px-4 md:px-8 py-4">
                    <div class="flex items-center">
                        <!-- Mobile menu button -->
                        <button id="mobile-menu-button" class="md:hidden mr-4 text-gray-600 focus:outline-none">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h2 class="text-xl font-semibold text-gray-800">Dashboard</h2>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-600 hidden sm:inline"><?php echo $_SESSION['admin_name']; ?></span>
                        <div class="relative group">
                            <button class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-user text-gray-600"></i>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 hidden group-hover:block z-30">
                                <a href="profile.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user-edit mr-2"></i>Edit Profil
                                </a>
                                <a href="logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Content -->
            <div class="p-4 md:p-8 overflow-y-auto" style="height: calc(100vh - 72px);">
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-8">
                    <div class="bg-white rounded-xl p-4 md:p-6 border border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center">
                                <i class="fas fa-users text-primary"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-400">Total Siswa</span>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800"><?php echo $total_siswa; ?></h3>
                        <p class="text-sm text-gray-500 mt-1">Siswa terdaftar</p>
                    </div>

                    <div class="bg-white rounded-xl p-4 md:p-6 border border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-comments text-green-500"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-400">Total Konsultasi</span>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800"><?php echo $total_konsultasi; ?></h3>
                        <p class="text-sm text-gray-500 mt-1">Konsultasi selesai</p>
                    </div>

                    <div class="bg-white rounded-xl p-4 md:p-6 border border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-school text-purple-500"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-400">Rekomendasi SMA</span>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800"><?php echo $total_sma; ?></h3>
                        <p class="text-sm text-gray-500 mt-1">Siswa memilih SMA</p>
                    </div>

                    <div class="bg-white rounded-xl p-4 md:p-6 border border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-tools text-orange-500"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-400">Rekomendasi SMK</span>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800"><?php echo $total_smk; ?></h3>
                        <p class="text-sm text-gray-500 mt-1">Siswa memilih SMK</p>
                    </div>
                </div>

                <!-- Chart Section -->
                <div class="bg-white rounded-xl p-4 md:p-6 border border-gray-100 shadow mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik Konsultasi</h3>
                    <div class="w-full overflow-hidden" style="height: 250px;">
                        <canvas id="dashboardChart"></canvas>
                    </div>
                </div>

                <!-- Quick Access Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 md:gap-6 mb-8">
                    <div class="bg-white rounded-xl p-4 md:p-6 border border-gray-100 hover:shadow-lg transition-shadow">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center">
                                <i class="fas fa-user-graduate text-primary"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">Kelola Siswa</h3>
                                <p class="text-sm text-gray-500">Tambah, edit, atau hapus data siswa</p>
                            </div>
                        </div>
                        <a href="manage_siswa.php" class="mt-4 inline-flex items-center text-primary hover:text-secondary">
                            <span class="text-sm">Buka Menu</span>
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>

                    <div class="bg-white rounded-xl p-4 md:p-6 border border-gray-100 hover:shadow-lg transition-shadow">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-book text-green-500"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">Kelola Jurusan</h3>
                                <p class="text-sm text-gray-500">Atur data jurusan SMA dan SMK</p>
                            </div>
                        </div>
                        <a href="manage_jurusan.php" class="mt-4 inline-flex items-center text-primary hover:text-secondary">
                            <span class="text-sm">Buka Menu</span>
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>

                    <div class="bg-white rounded-xl p-4 md:p-6 border border-gray-100 hover:shadow-lg transition-shadow">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-question-circle text-purple-500"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">Kelola Pertanyaan</h3>
                                <p class="text-sm text-gray-500">Atur pertanyaan konsultasi</p>
                            </div>
                        </div>
                        <a href="manage_pertanyaan.php" class="mt-4 inline-flex items-center text-primary hover:text-secondary">
                            <span class="text-sm">Buka Menu</span>
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>

                <!-- Recent Consultations -->
                <div class="bg-white rounded-xl border border-gray-100">
                    <div class="p-4 md:p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-800">Konsultasi Terbaru</h2>
                            <a href="hasiloutput.php" class="text-primary hover:text-secondary text-sm">
                                Lihat Semua
                            </a>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left bg-gray-50">
                                    <th class="px-4 md:px-6 py-3 text-xs font-medium text-gray-500 uppercase">Nama Siswa</th>
                                    <th class="px-4 md:px-6 py-3 text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Asal Sekolah</th>
                                    <th class="px-4 md:px-6 py-3 text-xs font-medium text-gray-500 uppercase">Rekomendasi</th>
                                    <th class="px-4 md:px-6 py-3 text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Jenis</th>
                                    <th class="px-4 md:px-6 py-3 text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php if (empty($konsultasi_terbaru)): ?>
                                <tr>
                                    <td colspan="5" class="px-4 md:px-6 py-4 text-center text-gray-500">Belum ada data konsultasi</td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach ($konsultasi_terbaru as $konsultasi): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 md:px-6 py-4">
                                            <span class="font-medium text-gray-900"><?php echo $konsultasi['nama_lengkap']; ?></span>
                                        </td>
                                        <td class="px-4 md:px-6 py-4 text-gray-600 hidden sm:table-cell"><?php echo $konsultasi['sekolah']; ?></td>
                                        <td class="px-4 md:px-6 py-4 text-gray-900"><?php echo $konsultasi['nama_jurusan']; ?></td>
                                        <td class="px-4 md:px-6 py-4 hidden md:table-cell">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                <?php echo $konsultasi['jenis_sekolah'] == 'SMA' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'; ?>">
                                                <?php echo $konsultasi['jenis_sekolah']; ?>
                                            </span>
                                        </td>
                                        <td class="px-4 md:px-6 py-4 text-gray-500 hidden sm:table-cell">
                                            <?php echo date('d M Y H:i', strtotime($konsultasi['tanggal'])); ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const sidebar = document.getElementById('sidebar');
            
            if (mobileMenuButton && sidebar) {
                mobileMenuButton.addEventListener('click', function() {
                    sidebar.classList.toggle('hidden');
                    sidebar.classList.toggle('fixed');
                    sidebar.classList.toggle('inset-0');
                    sidebar.classList.toggle('z-40');
                });
                
                // Close sidebar when clicking outside on mobile
                document.addEventListener('click', function(event) {
                    const isClickInsideSidebar = sidebar.contains(event.target);
                    const isClickOnMenuButton = mobileMenuButton.contains(event.target);
                    
                    if (!isClickInsideSidebar && !isClickOnMenuButton && !sidebar.classList.contains('hidden') && window.innerWidth < 768) {
                        sidebar.classList.add('hidden');
                        sidebar.classList.remove('fixed', 'inset-0', 'z-40');
                    }
                });
                
                // Handle window resize
                window.addEventListener('resize', function() {
                    if (window.innerWidth >= 768) {
                        sidebar.classList.remove('hidden', 'fixed', 'inset-0', 'z-40');
                        sidebar.classList.add('md:block');
                    } else if (!sidebar.classList.contains('hidden')) {
                        sidebar.classList.add('fixed', 'inset-0', 'z-40');
                    }
                });
            }
        });

        // Chart initialization
        const ctx = document.getElementById('dashboardChart').getContext('2d');
        const dashboardChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Total Siswa', 'Total Konsultasi', 'Rekomendasi SMA', 'Rekomendasi SMK'],
                datasets: [{
                    label: 'Jumlah',
                    data: [<?php echo $total_siswa; ?>, <?php echo $total_konsultasi; ?>, <?php echo $total_sma; ?>, <?php echo $total_smk; ?>],
                    backgroundColor: [
                        'rgba(70, 64, 222, 0.7)',
                        'rgba(34, 197, 94, 0.7)',
                        'rgba(139, 92, 246, 0.7)',
                        'rgba(251, 191, 36, 0.7)'
                    ],
                    borderColor: [
                        'rgba(70, 64, 222, 1)',
                        'rgba(34, 197, 94, 1)',
                        'rgba(139, 92, 246, 1)',
                        'rgba(251, 191, 36, 1)'
                    ],
                    borderWidth: 1,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
</body>
</html>
