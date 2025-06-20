<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$success = '';
$error = '';

// Proses tambah kecerdasan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_kecerdasan'])) {
    $kode_kecerdasan = trim($_POST['kode_kecerdasan']);
    $nama_kecerdasan = trim($_POST['nama_kecerdasan']);
    $deskripsi = trim($_POST['deskripsi']);
    
    // Validasi input
    if (empty($kode_kecerdasan) || empty($nama_kecerdasan)) {
        $error = "Kode kecerdasan dan nama kecerdasan harus diisi!";
    } else {
        // Cek apakah kode kecerdasan sudah ada
        $query_check = "SELECT * FROM kecerdasan WHERE kode_kecerdasan = ?";
        $stmt_check = mysqli_prepare($conn, $query_check);
        mysqli_stmt_bind_param($stmt_check, "s", $kode_kecerdasan);
        mysqli_stmt_execute($stmt_check);
        $result_check = mysqli_stmt_get_result($stmt_check);
        
        if (mysqli_num_rows($result_check) > 0) {
            $error = "Kode kecerdasan sudah digunakan!";
        } else {
            // Simpan kecerdasan baru dengan prepared statement
            $query_insert = "INSERT INTO kecerdasan (kode_kecerdasan, nama_kecerdasan, deskripsi) VALUES (?, ?, ?)";
            $stmt_insert = mysqli_prepare($conn, $query_insert);
            mysqli_stmt_bind_param($stmt_insert, "sss", $kode_kecerdasan, $nama_kecerdasan, $deskripsi);
            
            if (mysqli_stmt_execute($stmt_insert)) {
                $success = "Kecerdasan baru berhasil ditambahkan!";
            } else {
                $error = "Gagal menambahkan kecerdasan: " . mysqli_error($conn);
            }
        }
    }
}

// Proses edit kecerdasan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_kecerdasan'])) {
    $id_kecerdasan = (int) $_POST['id_kecerdasan'];
    $kode_kecerdasan = trim($_POST['kode_kecerdasan']);
    $nama_kecerdasan = trim($_POST['nama_kecerdasan']);
    $deskripsi = trim($_POST['deskripsi']);
    
    // Validasi input
    if (empty($kode_kecerdasan) || empty($nama_kecerdasan)) {
        $error = "Kode kecerdasan dan nama kecerdasan harus diisi!";
    } else {
        // Cek apakah kode kecerdasan sudah ada (kecuali untuk kecerdasan yang sedang diedit)
        $query_check = "SELECT * FROM kecerdasan WHERE kode_kecerdasan = ? AND id_kecerdasan != ?";
        $stmt_check = mysqli_prepare($conn, $query_check);
        mysqli_stmt_bind_param($stmt_check, "si", $kode_kecerdasan, $id_kecerdasan);
        mysqli_stmt_execute($stmt_check);
        $result_check = mysqli_stmt_get_result($stmt_check);
        
        if (mysqli_num_rows($result_check) > 0) {
            $error = "Kode kecerdasan sudah digunakan!";
        } else {
            // Update kecerdasan dengan prepared statement
            $query_update = "UPDATE kecerdasan SET kode_kecerdasan = ?, nama_kecerdasan = ?, deskripsi = ? WHERE id_kecerdasan = ?";
            $stmt_update = mysqli_prepare($conn, $query_update);
            mysqli_stmt_bind_param($stmt_update, "sssi", $kode_kecerdasan, $nama_kecerdasan, $deskripsi, $id_kecerdasan);
            
            if (mysqli_stmt_execute($stmt_update)) {
                $success = "Data kecerdasan berhasil diperbarui!";
            } else {
                $error = "Gagal memperbarui data kecerdasan: " . mysqli_error($conn);
            }
        }
    }
}

// Proses hapus kecerdasan
if (isset($_GET['delete'])) {
    $id_kecerdasan = (int) $_GET['delete'];
    
    // Cek apakah kecerdasan digunakan di tabel lain
    $query_check = "SELECT * FROM pertanyaan WHERE id_kecerdasan = $id_kecerdasan";
    $result_check = mysqli_query($conn, $query_check);
    
    $query_check2 = "SELECT * FROM kecerdasan_jurusan WHERE id_kecerdasan = $id_kecerdasan";
    $result_check2 = mysqli_query($conn, $query_check2);
    
    $query_check3 = "SELECT * FROM hasil_konsultasi WHERE id_kecerdasan = $id_kecerdasan";
    $result_check3 = mysqli_query($conn, $query_check3);
    
    if (mysqli_num_rows($result_check) > 0 || mysqli_num_rows($result_check2) > 0 || mysqli_num_rows($result_check3) > 0) {
        $error = "Kecerdasan tidak dapat dihapus karena masih digunakan dalam data pertanyaan, relasi jurusan, atau hasil konsultasi!";
    } else {
        // Hapus kecerdasan
        $query_delete = "DELETE FROM kecerdasan WHERE id_kecerdasan = $id_kecerdasan";
        if (mysqli_query($conn, $query_delete)) {
            $success = "Kecerdasan berhasil dihapus!";
        } else {
            $error = "Gagal menghapus kecerdasan: " . mysqli_error($conn);
        }
    }
}

// Ambil semua kecerdasan
$query = "SELECT * FROM kecerdasan ORDER BY kode_kecerdasan";
$result = mysqli_query($conn, $query);
$kecerdasan_list = [];
while ($row = mysqli_fetch_assoc($result)) {
    $kecerdasan_list[] = $row;
}

// Ambil kecerdasan untuk edit jika ada
$edit_kecerdasan = null;
if (isset($_GET['edit'])) {
    $id_kecerdasan = (int) $_GET['edit'];
    $query = "SELECT * FROM kecerdasan WHERE id_kecerdasan = $id_kecerdasan";
    $result = mysqli_query($conn, $query);
    $edit_kecerdasan = mysqli_fetch_assoc($result);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kecerdasan - SPK Jurusan</title>
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
                <a href="manage_kecerdasan.php" class="flex items-center px-6 py-3 bg-accent text-primary">
                    <i class="fas fa-brain w-5"></i>
                    <span class="ml-3">Kelola Kecerdasan</span>
                </a>
                <a href="manage_aturan.php" class="flex items-center px-6 py-3 text-gray-600 hover:bg-accent hover:text-primary">
                    <i class="fas fa-cogs w-5"></i>
                    <span class="ml-3">Kelola Aturan</span>
                </a>
                <a href="hasil_akhir.php" class="flex items-center px-6 py-3 text-gray-600 hover:bg-accent hover:text-primary">
                    <i class="fas fa-chart-bar w-5"></i>
                    <span class="ml-3">Hasil Akhir</span>
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">Kelola Data Kecerdasan</h2>
            </div>

            <?php if ($error): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p><?php echo $error; ?></p>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p><?php echo $success; ?></p>
                </div>
            <?php endif; ?>

            <!-- Form Tambah/Edit Kecerdasan -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">
                    <?php echo $edit_kecerdasan ? 'Edit Kecerdasan' : 'Tambah Kecerdasan Baru'; ?>
                </h3>
                <form method="post" action="">
                    <?php if ($edit_kecerdasan): ?>
                        <input type="hidden" name="id_kecerdasan" value="<?php echo $edit_kecerdasan['id_kecerdasan']; ?>">
                    <?php endif; ?>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="kode_kecerdasan" class="block text-sm font-medium text-gray-700 mb-1">Kode Kecerdasan</label>
                            <input type="text" name="kode_kecerdasan" id="kode_kecerdasan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" value="<?php echo $edit_kecerdasan ? $edit_kecerdasan['kode_kecerdasan'] : ''; ?>" required>
                        </div>
                        
                        <div>
                            <label for="nama_kecerdasan" class="block text-sm font-medium text-gray-700 mb-1">Nama Kecerdasan</label>
                            <input type="text" name="nama_kecerdasan" id="nama_kecerdasan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" value="<?php echo $edit_kecerdasan ? $edit_kecerdasan['nama_kecerdasan'] : ''; ?>" required>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"><?php echo $edit_kecerdasan ? $edit_kecerdasan['deskripsi'] : ''; ?></textarea>
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <?php if ($edit_kecerdasan): ?>
                            <a href="manage_kecerdasan.php" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg mr-2 hover:bg-gray-300 transition-colors">Batal</a>
                            <button type="submit" name="edit_kecerdasan" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition-colors">Update Kecerdasan</button>
                        <?php else: ?>
                            <button type="submit" name="add_kecerdasan" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition-colors">Tambah Kecerdasan</button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Tabel Kecerdasan -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kecerdasan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($kecerdasan_list)): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada data kecerdasan</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($kecerdasan_list as $kecerdasan): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo $kecerdasan['kode_kecerdasan']; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo $kecerdasan['nama_kecerdasan']; ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-500"><?php echo substr($kecerdasan['deskripsi'], 0, 100) . (strlen($kecerdasan['deskripsi']) > 100 ? '...' : ''); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="manage_kecerdasan.php?edit=<?php echo $kecerdasan['id_kecerdasan']; ?>" class="text-primary hover:text-secondary mr-3"><i class="fas fa-edit"></i> Edit</a>
                                        <a href="manage_kecerdasan.php?delete=<?php echo $kecerdasan['id_kecerdasan']; ?>" onclick="return confirm('Yakin ingin menghapus kecerdasan ini?')" class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i> Hapus</a>
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