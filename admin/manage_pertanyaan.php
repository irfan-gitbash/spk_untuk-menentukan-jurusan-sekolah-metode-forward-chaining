<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Handle delete request
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $query = "DELETE FROM pertanyaan WHERE id_pertanyaan = $id";
    mysqli_query($conn, $query);
    header("Location: manage_pertanyaan.php");
    exit;
}

// Handle clear all request
if (isset($_GET['clear']) && $_GET['clear'] == 'all') {
    // Truncate the table to delete all questions and reset auto-increment
    $query = "TRUNCATE TABLE pertanyaan";
    mysqli_query($conn, $query);
    header("Location: manage_pertanyaan.php");
    exit;
}

// Fetch all questions
$query = "SELECT * FROM pertanyaan ORDER BY id_pertanyaan DESC";
$result = mysqli_query($conn, $query);
$pertanyaan_list = [];
while ($row = mysqli_fetch_assoc($result)) {
    $pertanyaan_list[] = $row;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kelola Pertanyaan - Admin SPK Jurusan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
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
<body class="bg-[#F9F9F9] min-h-screen">
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
                <a href="manage_pertanyaan.php" class="flex items-center px-6 py-3 bg-accent text-primary">
                    <i class="fas fa-question-circle w-5"></i>
                    <span class="ml-3">Kelola Pertanyaan</span>
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">Kelola Pertanyaan</h2>
                <div class="flex space-x-4">
                    <a href="cetak_pertanyaan.php" target="_blank" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                        <i class="fas fa-print mr-2"></i>Cetak
                    </a>
                    <a href="manage_pertanyaan.php?clear=all" onclick="return confirm('Anda yakin ingin menghapus semua pertanyaan? Tindakan ini tidak dapat diurungkan.')" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                        <i class="fas fa-trash-alt mr-2"></i>Hapus Semua
                    </a>
                    <a href="tambah_pertanyaan.php" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition">
                        <i class="fas fa-plus mr-2"></i>Tambah Pertanyaan
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pertanyaan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($pertanyaan_list)): ?>
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">Belum ada pertanyaan</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($pertanyaan_list as $pertanyaan): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $pertanyaan['id_pertanyaan']; ?></td>
<td class="px-6 py-4 text-sm text-gray-900"><?php echo isset($pertanyaan['pertanyaan']) ? htmlspecialchars($pertanyaan['pertanyaan']) : ''; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="edit_pertanyaan.php?id=<?php echo $pertanyaan['id_pertanyaan']; ?>" class="text-primary hover:text-secondary mr-4">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="manage_pertanyaan.php?delete=<?php echo $pertanyaan['id_pertanyaan']; ?>" onclick="return confirm('Yakin ingin menghapus pertanyaan ini?')" class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
