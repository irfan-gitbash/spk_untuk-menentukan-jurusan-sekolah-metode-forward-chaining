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

// Ambil semua kecerdasan
$query_kecerdasan = "SELECT * FROM kecerdasan ORDER BY kode_kecerdasan";
$result_kecerdasan = mysqli_query($conn, $query_kecerdasan);
$kecerdasan_list = [];
while ($row = mysqli_fetch_assoc($result_kecerdasan)) {
    $kecerdasan_list[] = $row;
}

// Ambil semua jurusan
$query_jurusan = "SELECT * FROM jurusan ORDER BY jenis_sekolah, nama_jurusan";
$result_jurusan = mysqli_query($conn, $query_jurusan);
$jurusan_list = [];
while ($row = mysqli_fetch_assoc($result_jurusan)) {
    $jurusan_list[] = $row;
}

// Proses tambah relasi kecerdasan-jurusan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_relation'])) {
    $id_kecerdasan = (int) $_POST['id_kecerdasan'];
    $id_jurusan = (int) $_POST['id_jurusan'];
    
    // Validasi input
    if (empty($id_kecerdasan) || empty($id_jurusan)) {
        $error = "Kecerdasan dan jurusan harus dipilih!";
    } else {
        // Cek apakah relasi sudah ada
        $query_check = "SELECT * FROM kecerdasan_jurusan WHERE id_kecerdasan = ? AND id_jurusan = ?";
        $stmt_check = mysqli_prepare($conn, $query_check);
        mysqli_stmt_bind_param($stmt_check, "ii", $id_kecerdasan, $id_jurusan);
        mysqli_stmt_execute($stmt_check);
        $result_check = mysqli_stmt_get_result($stmt_check);
        
        if (mysqli_num_rows($result_check) > 0) {
            $error = "Relasi kecerdasan-jurusan ini sudah ada!";
        } else {
            // Simpan relasi baru dengan prepared statement
            $query_insert = "INSERT INTO kecerdasan_jurusan (id_kecerdasan, id_jurusan) VALUES (?, ?)";
            $stmt_insert = mysqli_prepare($conn, $query_insert);
            mysqli_stmt_bind_param($stmt_insert, "ii", $id_kecerdasan, $id_jurusan);
            
            if (mysqli_stmt_execute($stmt_insert)) {
                $success = "Relasi kecerdasan-jurusan berhasil ditambahkan!";
            } else {
                $error = "Gagal menambahkan relasi: " . mysqli_error($conn);
            }
        }
    }
}

// Proses hapus relasi
if (isset($_GET['delete'])) {
    $id_relasi = (int) $_GET['delete'];
    
    // Hapus relasi
    $query_delete = "DELETE FROM kecerdasan_jurusan WHERE id = $id_relasi";
    if (mysqli_query($conn, $query_delete)) {
        $success = "Relasi berhasil dihapus!";
    } else {
        $error = "Gagal menghapus relasi: " . mysqli_error($conn);
    }
}

// Ambil semua relasi kecerdasan-jurusan
$query_relations = "SELECT kj.id, k.kode_kecerdasan, k.nama_kecerdasan, j.kode_jurusan, j.nama_jurusan, j.jenis_sekolah 
                   FROM kecerdasan_jurusan kj 
                   JOIN kecerdasan k ON kj.id_kecerdasan = k.id_kecerdasan 
                   JOIN jurusan j ON kj.id_jurusan = j.id_jurusan 
                   ORDER BY k.kode_kecerdasan, j.jenis_sekolah, j.nama_jurusan";
$result_relations = mysqli_query($conn, $query_relations);
$relations = [];
while ($row = mysqli_fetch_assoc($result_relations)) {
    $relations[] = $row;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Aturan - SPK Jurusan</title>
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
                <a href="manage_kecerdasan.php" class="flex items-center px-6 py-3 text-gray-600 hover:bg-accent hover:text-primary">
                    <i class="fas fa-brain w-5"></i>
                    <span class="ml-3">Kelola Kecerdasan</span>
                </a>
                <a href="manage_aturan.php" class="flex items-center px-6 py-3 bg-accent text-primary">
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
                <h2 class="text-2xl font-semibold text-gray-800">Kelola Aturan Forward Chaining</h2>
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

            <!-- Penjelasan Aturan -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold mb-2">Tentang Aturan Forward Chaining</h3>
                <p class="text-gray-600 mb-4">Forward Chaining adalah metode inferensi yang memulai pencarian dari fakta yang diketahui, kemudian mencocokkan fakta-fakta tersebut dengan bagian IF dari aturan IF-THEN. Bila ada fakta yang cocok dengan bagian IF, maka aturan tersebut dieksekusi.</p>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-medium mb-2">Aturan Dasar:</h4>
                    <ol class="list-decimal list-inside space-y-2 text-gray-700">
                        <li>Jika jawaban = "Ya" pada pertanyaan terkait kecerdasan, maka tambahkan skor pada kecerdasan tersebut.</li>
                        <li>Jika skor kecerdasan X > skor kecerdasan lainnya, maka kecerdasan X adalah kecerdasan dominan.</li>
                        <li>Jika kecerdasan dominan = K, maka rekomendasikan jurusan yang terkait dengan kecerdasan K.</li>
                    </ol>
                </div>
            </div>

            <!-- Form Tambah Relasi Kecerdasan-Jurusan -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Tambah Aturan Kecerdasan-Jurusan</h3>
                <form method="post" action="">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="id_kecerdasan" class="block text-sm font-medium text-gray-700 mb-1">Kecerdasan</label>
                            <select name="id_kecerdasan" id="id_kecerdasan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" required>
                                <option value="">Pilih Kecerdasan</option>
                                <?php foreach ($kecerdasan_list as $kecerdasan): ?>
                                    <option value="<?php echo $kecerdasan['id_kecerdasan']; ?>">
                                        <?php echo $kecerdasan['kode_kecerdasan'] . ' - ' . $kecerdasan['nama_kecerdasan']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label for="id_jurusan" class="block text-sm font-medium text-gray-700 mb-1">Jurusan</label>
                            <select name="id_jurusan" id="id_jurusan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" required>
                                <option value="">Pilih Jurusan</option>
                                <optgroup label="SMA">
                                    <?php foreach ($jurusan_list as $jurusan): ?>
                                        <?php if ($jurusan['jenis_sekolah'] == 'SMA'): ?>
                                            <option value="<?php echo $jurusan['id_jurusan']; ?>">
                                                <?php echo $jurusan['kode_jurusan'] . ' - ' . $jurusan['nama_jurusan']; ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </optgroup>
                                <optgroup label="SMK">
                                    <?php foreach ($jurusan_list as $jurusan): ?>
                                        <?php if ($jurusan['jenis_sekolah'] == 'SMK'): ?>
                                            <option value="<?php echo $jurusan['id_jurusan']; ?>">
                                                <?php echo $jurusan['kode_jurusan'] . ' - ' . $jurusan['nama_jurusan']; ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <button type="submit" name="add_relation" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition-colors">
                            <i class="fas fa-plus mr-2"></i>Tambah Aturan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabel Relasi Kecerdasan-Jurusan -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold">Daftar Aturan Kecerdasan-Jurusan</h3>
                    <p class="text-gray-600 text-sm mt-1">Berikut adalah daftar aturan yang menentukan rekomendasi jurusan berdasarkan kecerdasan dominan.</p>
                </div>
                
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kecerdasan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jurusan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Sekolah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($relations)): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada data aturan</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($relations as $relation): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900"><?php echo $relation['kode_kecerdasan']; ?></div>
                                        <div class="text-sm text-gray-500"><?php echo $relation['nama_kecerdasan']; ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900"><?php echo $relation['kode_jurusan']; ?></div>
                                        <div class="text-sm text-gray-500"><?php echo $relation['nama_jurusan']; ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $relation['jenis_sekolah'] == 'SMA' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'; ?>">
                                            <?php echo $relation['jenis_sekolah']; ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="manage_aturan.php?delete=<?php echo $relation['id']; ?>" onclick="return confirm('Yakin ingin menghapus aturan ini?')" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i> Hapus
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