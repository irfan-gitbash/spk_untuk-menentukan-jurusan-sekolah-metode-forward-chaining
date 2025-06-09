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

// Proses tambah jurusan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_jurusan'])) {
    $kode_jurusan = clean($_POST['kode_jurusan']);
    $nama_jurusan = clean($_POST['nama_jurusan']);
    $jenis_sekolah = clean($_POST['jenis_sekolah']);
    $deskripsi = clean($_POST['deskripsi']);
    $prospek_karir = clean($_POST['prospek_karir']);
    
    // Validasi input
    if (empty($kode_jurusan) || empty($nama_jurusan) || empty($jenis_sekolah)) {
        $error = "Kode jurusan, nama jurusan, dan jenis sekolah harus diisi!";
    } else {
        // Cek apakah kode jurusan sudah ada
        $query_check = "SELECT * FROM jurusan WHERE kode_jurusan = ?";
        $stmt_check = mysqli_prepare($conn, $query_check);
        mysqli_stmt_bind_param($stmt_check, "s", $kode_jurusan);
        mysqli_stmt_execute($stmt_check);
        $result_check = mysqli_stmt_get_result($stmt_check);
        
        if (mysqli_num_rows($result_check) > 0) {
            $error = "Kode jurusan sudah digunakan!";
        } else {
            // Simpan jurusan baru dengan prepared statement
            $query_insert = "INSERT INTO jurusan (kode_jurusan, nama_jurusan, jenis_sekolah, deskripsi, prospek_karir) VALUES (?, ?, ?, ?, ?)";
            $stmt_insert = mysqli_prepare($conn, $query_insert);
            mysqli_stmt_bind_param($stmt_insert, "sssss", $kode_jurusan, $nama_jurusan, $jenis_sekolah, $deskripsi, $prospek_karir);
            
            if (mysqli_stmt_execute($stmt_insert)) {
                $success = "Jurusan baru berhasil ditambahkan!";
            } else {
                $error = "Gagal menambahkan jurusan: " . mysqli_error($conn);
            }
        }
    }
}

// Proses edit jurusan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_jurusan'])) {
    $id_jurusan = (int) $_POST['id_jurusan'];
    $kode_jurusan = clean($_POST['kode_jurusan']);
    $nama_jurusan = clean($_POST['nama_jurusan']);
    $jenis_sekolah = clean($_POST['jenis_sekolah']);
    $deskripsi = clean($_POST['deskripsi']);
    $prospek_karir = clean($_POST['prospek_karir']);
    
    // Validasi input
    if (empty($kode_jurusan) || empty($nama_jurusan) || empty($jenis_sekolah)) {
        $error = "Kode jurusan, nama jurusan, dan jenis sekolah harus diisi!";
    } else {
        // Cek apakah kode jurusan sudah ada (kecuali untuk jurusan yang sedang diedit)
        $query_check = "SELECT * FROM jurusan WHERE kode_jurusan = ? AND id_jurusan != ?";
        $stmt_check = mysqli_prepare($conn, $query_check);
        mysqli_stmt_bind_param($stmt_check, "si", $kode_jurusan, $id_jurusan);
        mysqli_stmt_execute($stmt_check);
        $result_check = mysqli_stmt_get_result($stmt_check);
        
        if (mysqli_num_rows($result_check) > 0) {
            $error = "Kode jurusan sudah digunakan!";
        } else {
            // Update jurusan dengan prepared statement
            $query_update = "UPDATE jurusan SET kode_jurusan = ?, nama_jurusan = ?, jenis_sekolah = ?, deskripsi = ?, prospek_karir = ? WHERE id_jurusan = ?";
            $stmt_update = mysqli_prepare($conn, $query_update);
            mysqli_stmt_bind_param($stmt_update, "sssssi", $kode_jurusan, $nama_jurusan, $jenis_sekolah, $deskripsi, $prospek_karir, $id_jurusan);
            
            if (mysqli_stmt_execute($stmt_update)) {
                $success = "Data jurusan berhasil diperbarui!";
            } else {
                $error = "Gagal memperbarui data jurusan: " . mysqli_error($conn);
            }
        }
    }
}

// Proses hapus jurusan
if (isset($_GET['delete'])) {
    $id_jurusan = (int) $_GET['delete'];
    
    // Cek apakah jurusan digunakan di tabel kecerdasan_jurusan
    $query_check_kj = "SELECT * FROM kecerdasan_jurusan WHERE id_jurusan = $id_jurusan";
    $result_check_kj = mysqli_query($conn, $query_check_kj);
    
    // Cek apakah jurusan digunakan di tabel hasil_konsultasi
    $query_check_hk = "SELECT * FROM hasil_konsultasi WHERE id_jurusan = $id_jurusan";
    $result_check_hk = mysqli_query($conn, $query_check_hk);
    
    if (mysqli_num_rows($result_check_kj) > 0 || mysqli_num_rows($result_check_hk) > 0) {
        $error = "Jurusan tidak dapat dihapus karena masih digunakan dalam data kecerdasan atau hasil konsultasi!";
    } else {
        // Hapus jurusan
        $query_delete = "DELETE FROM jurusan WHERE id_jurusan = $id_jurusan";
        if (mysqli_query($conn, $query_delete)) {
            $success = "Jurusan berhasil dihapus!";
        } else {
            $error = "Gagal menghapus jurusan: " . mysqli_error($conn);
        }
    }
}

// Ambil semua jurusan
$query = "SELECT * FROM jurusan ORDER BY jenis_sekolah, nama_jurusan";
$result = mysqli_query($conn, $query);
$jurusan = [];
while ($row = mysqli_fetch_assoc($result)) {
    $jurusan[] = $row;
}

// Ambil jurusan untuk edit jika ada
$edit_jurusan = null;
if (isset($_GET['edit'])) {
    $id_jurusan = (int) $_GET['edit'];
    $edit_jurusan = getJurusanById($id_jurusan);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Jurusan - SPK Jurusan</title>
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
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-primary to-secondary text-white shadow-md">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <a href="dashboard.php" class="text-xl font-bold">SPK Jurusan</a>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-sm hidden md:inline">Selamat datang, <?php echo $_SESSION['admin_name']; ?></span>
                    <div class="relative group">
                        <button class="flex items-center focus:outline-none">
                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['admin_name']); ?>&background=random" alt="Profile" class="w-8 h-8 rounded-full">
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden group-hover:block">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Pengaturan</a>
                            <a href="logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-6">
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Sidebar -->
            <div class="w-full md:w-1/4 lg:w-1/5">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h5 class="font-bold text-lg mb-4">Menu Admin</h5>
                    <ul class="space-y-2">
                        <li>
                            <a href="dashboard.php" class="flex items-center text-gray-700 hover:text-primary">
                                <i class="fas fa-tachometer-alt w-5"></i>
                                <span class="ml-2">Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="manage_admin.php" class="flex items-center text-gray-700 hover:text-primary">
                                <i class="fas fa-users-cog w-5"></i>
                                <span class="ml-2">Kelola Admin</span>
                            </a>
                        </li>
                        <li>
                            <a href="manage_jurusan.php" class="flex items-center text-primary font-bold">
                                <i class="fas fa-graduation-cap w-5"></i>
                                <span class="ml-2">Kelola Jurusan</span>
                            </a>
                        </li>
                        <li>
                            <a href="manage_siswa.php" class="flex items-center text-gray-700 hover:text-primary">
                                <i class="fas fa-user-graduate w-5"></i>
                                <span class="ml-2">Data Siswa</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Content -->
            <div class="w-full md:w-3/4 lg:w-4/5">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold mb-6">Kelola Jurusan</h2>
                    
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
                    
                    <!-- Form Tambah/Edit Jurusan -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <h3 class="text-lg font-bold mb-4"><?php echo $edit_jurusan ? 'Edit Jurusan' : 'Tambah Jurusan Baru'; ?></h3>
                        <form method="post" action="">
                            <?php if ($edit_jurusan): ?>
                            <input type="hidden" name="id_jurusan" value="<?php echo $edit_jurusan['id_jurusan']; ?>">
                            <?php endif; ?>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="kode_jurusan" class="block text-gray-700 text-sm font-bold mb-2">Kode Jurusan</label>
                                    <input type="text" id="kode_jurusan" name="kode_jurusan" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $edit_jurusan ? $edit_jurusan['kode_jurusan'] : ''; ?>" required>
                                </div>
                                
                                <div>
                                    <label for="nama_jurusan" class="block text-gray-700 text-sm font-bold mb-2">Nama Jurusan</label>
                                    <input type="text" id="nama_jurusan" name="nama_jurusan" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $edit_jurusan ? $edit_jurusan['nama_jurusan'] : ''; ?>" required>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="jenis_sekolah" class="block text-gray-700 text-sm font-bold mb-2">Jenis Sekolah</label>
                                <select id="jenis_sekolah" name="jenis_sekolah" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                    <option value="">Pilih Jenis Sekolah</option>
                                    <option value="SMA" <?php echo ($edit_jurusan && $edit_jurusan['jenis_sekolah'] == 'SMA') ? 'selected' : ''; ?>>SMA</option>
                                    <option value="SMK" <?php echo ($edit_jurusan && $edit_jurusan['jenis_sekolah'] == 'SMK') ? 'selected' : ''; ?>>SMK</option>
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label for="deskripsi" class="block text-gray-700 text-sm font-bold mb-2">Deskripsi</label>
                                <textarea id="deskripsi" name="deskripsi" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?php echo $edit_jurusan ? $edit_jurusan['deskripsi'] : ''; ?></textarea>
                            </div>
                            
                            <div class="mb-4">
                                <label for="prospek_karir" class="block text-gray-700 text-sm font-bold mb-2">Prospek Karir</label>
                                <textarea id="prospek_karir" name="prospek_karir" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?php echo $edit_jurusan ? $edit_jurusan['prospek_karir'] : ''; ?></textarea>
                            </div>
                            
                            <div class="flex items-center justify-end">
                                <?php if ($edit_jurusan): ?>
                                <a href="manage_jurusan.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-2">Batal</a>
                                <button type="submit" name="edit_jurusan" class="bg-primary hover:bg-secondary text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                                </button>
                                <?php else: ?>
                                <button type="submit" name="add_jurusan" class="bg-primary hover:bg-secondary text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    <i class="fas fa-plus mr-1"></i> Tambah Jurusan
                                </button>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Tabel Jurusan -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-2 px-4 border-b text-left">Kode</th>
                                    <th class="py-2 px-4 border-b text-left">Nama Jurusan</th>
                                    <th class="py-2 px-4 border-b text-left">Jenis Sekolah</th>
                                    <th class="py-2 px-4 border-b text-left">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($jurusan) > 0): ?>
                                    <?php foreach ($jurusan as $j): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-2 px-4 border-b"><?php echo $j['kode_jurusan']; ?></td>
                                        <td class="py-2 px-4 border-b"><?php echo $j['nama_jurusan']; ?></td>
                                        <td class="py-2 px-4 border-b">
                                            <span class="px-2 py-1 rounded text-xs font-semibold <?php echo $j['jenis_sekolah'] == 'SMA' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'; ?>">
                                                <?php echo $j['jenis_sekolah']; ?>
                                            </span>
                                        </td>
                                        <td class="py-2 px-4 border-b">
                                            <a href="?edit=<?php echo $j['id_jurusan']; ?>" class="text-blue-500 hover:text-blue-700 mr-2">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="javascript:void(0)" onclick="confirmDelete(<?php echo $j['id_jurusan']; ?>)" class="text-red-500 hover:text-red-700">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="py-4 px-4 text-center text-gray-500">Tidak ada data jurusan</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function confirmDelete(id) {
            if (confirm('Apakah Anda yakin ingin menghapus jurusan ini?')) {
                window.location.href = 'manage_jurusan.php?delete=' + id;
            }
        }
    </script>
</body>
</html>