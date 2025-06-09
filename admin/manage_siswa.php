<?php
require_once '../includes/functions.php';
session_start();

// Cek login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$success = '';
$error = '';

// Proses hapus siswa
if (isset($_GET['delete'])) {
    $id_siswa = (int) $_GET['delete'];
    
    // Hapus data terkait di tabel jawaban_siswa
    $query_delete_jawaban = "DELETE FROM jawaban_siswa WHERE id_siswa = $id_siswa";
    mysqli_query($conn, $query_delete_jawaban);
    
    // Hapus data terkait di tabel hasil_konsultasi
    $query_delete_hasil = "DELETE FROM hasil_konsultasi WHERE id_siswa = $id_siswa";
    mysqli_query($conn, $query_delete_hasil);
    
    // Hapus data siswa
    $query_delete_siswa = "DELETE FROM siswa WHERE id_siswa = $id_siswa";
    if (mysqli_query($conn, $query_delete_siswa)) {
        $success = "Data siswa berhasil dihapus!";
    } else {
        $error = "Gagal menghapus data siswa: " . mysqli_error($conn);
    }
}

// Ambil data siswa
$query = "SELECT s.*, 
         (SELECT j.nama_jurusan FROM hasil_konsultasi hk 
          JOIN jurusan j ON hk.id_jurusan = j.id_jurusan 
          WHERE hk.id_siswa = s.id_siswa LIMIT 1) as rekomendasi_jurusan,
         (SELECT j.jenis_sekolah FROM hasil_konsultasi hk 
          JOIN jurusan j ON hk.id_jurusan = j.id_jurusan 
          WHERE hk.id_siswa = s.id_siswa LIMIT 1) as jenis_sekolah
         FROM siswa s ORDER BY s.tanggal_konsultasi DESC";
$result = mysqli_query($conn, $query);
$siswa = [];
while ($row = mysqli_fetch_assoc($result)) {
    $siswa[] = $row;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Siswa - SPK Jurusan</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#17a2b8',
                        secondary: '#138496',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Header/Navbar -->
    <nav class="bg-gradient-to-r from-primary to-secondary text-white shadow-md">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-3">
                <div class="flex items-center">
                    <a href="dashboard.php" class="text-xl font-bold">
                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard Admin
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="hidden md:inline-block">
                        <i class="fas fa-user-circle mr-1"></i>
                        <?php echo $_SESSION['admin_name']; ?>
                    </span>
                    <div class="relative group">
                        <button class="focus:outline-none">
                            <i class="fas fa-cog text-white hover:text-gray-200"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10 hidden group-hover:block">
                            <a href="profile.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
                                <i class="fas fa-user-edit mr-2"></i>Edit Profil
                            </a>
                            <a href="manage_admin.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
                                <i class="fas fa-users-cog mr-2"></i>Kelola Admin
                            </a>
                            <a href="logout.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-6 flex-grow">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-4 md:mb-0">Kelola Data Siswa</h1>
            <div class="flex space-x-2">
                <a href="dashboard.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <a href="add_siswa.php" class="bg-primary hover:bg-secondary text-white px-4 py-2 rounded-md transition-colors">
                    <i class="fas fa-plus mr-2"></i>Tambah Siswa
                </a>
            </div>
        </div>

        <!-- Alerts -->
        <?php if ($success): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?php echo $success; ?>
        </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php echo $error; ?>
        </div>
        <?php endif; ?>

        <!-- Search and Filter -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                <div class="relative w-full md:w-64">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" id="search-input" class="pl-10 w-full border border-gray-300 rounded py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Cari siswa...">
                </div>
                <div class="flex space-x-2">
                    <select id="filter-jenis" class="border border-gray-300 rounded py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Semua Jenis</option>
                        <option value="SMA">SMA</option>
                        <option value="SMK">SMK</option>
                    </select>
                    <button id="reset-filter" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-2 rounded-md transition-colors">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NISN</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sekolah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rekomendasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="siswa-table-body">
                        <?php if (empty($siswa)): ?>
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-gray-500">Belum ada data siswa</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($siswa as $s): ?>
                            <tr class="siswa-row" data-jenis="<?php echo $s['jenis_sekolah']; ?>">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $s['id_siswa']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo $s['nama_lengkap']; ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $s['nisn']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $s['kelas']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $s['sekolah']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $s['rekomendasi_jurusan'] ?? '-'; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($s['jenis_sekolah']): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $s['jenis_sekolah'] == 'SMA' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'; ?>">
                                        <?php echo $s['jenis_sekolah']; ?>
                                    </span>
                                    <?php else: ?>
                                    <span class="text-gray-500">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo date('d M Y', strtotime($s['tanggal_konsultasi'])); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="../hasil.php?id=<?php echo $s['id_siswa']; ?>" class="text-primary hover:text-secondary" title="Lihat Hasil">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="edit_siswa.php?id=<?php echo $s['id_siswa']; ?>" class="text-yellow-500 hover:text-yellow-600" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="#" onclick="confirmDelete(<?php echo $s['id_siswa']; ?>)" class="text-red-500 hover:text-red-600" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-4 mt-6">
        <div class="container mx-auto px-4">
            <p class="text-center text-gray-600 text-sm">&copy; 2025 Sistem Pakar Pemilihan - Panel Admin</p>
        </div>
    </footer>

    <!-- JavaScript for search and filter -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            const filterJenis = document.getElementById('filter-jenis');
            const resetFilter = document.getElementById('reset-filter');
            const siswaRows = document.querySelectorAll('.siswa-row');
            
            // Search function
            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                
                siswaRows.forEach(row => {
                    const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const nisn = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    const school = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
                    
                    if (name.includes(searchTerm) || nisn.includes(searchTerm) || school.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
            
            // Filter function
            filterJenis.addEventListener('change', function() {
                const filterValue = this.value;
                
                siswaRows.forEach(row => {
                    if (!filterValue || row.dataset.jenis === filterValue) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
            
            // Reset filter
            resetFilter.addEventListener('click', function() {
                searchInput.value = '';
                filterJenis.value = '';
                
                siswaRows.forEach(row => {
                    row.style.display = '';
                });
            });
        });
        
        // Confirm delete
        function confirmDelete(id) {
            if (confirm('Apakah Anda yakin ingin menghapus data siswa ini?')) {
                window.location.href = `?delete=${id}`;
            }
        }
    </script>
</body>
</html>