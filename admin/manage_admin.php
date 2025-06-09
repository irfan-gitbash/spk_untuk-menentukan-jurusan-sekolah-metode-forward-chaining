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

// Proses tambah admin
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_admin'])) {
    $username = clean($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $nama_lengkap = clean($_POST['nama_lengkap']);
    
    // Validasi input
    if (empty($username) || empty($password) || empty($confirm_password) || empty($nama_lengkap)) {
        $error = "Semua field harus diisi!";
    } elseif ($password !== $confirm_password) {
        $error = "Konfirmasi password tidak sesuai!";
    } else {
        // Cek apakah username sudah ada
        $query_check = "SELECT * FROM admin WHERE username = '$username'";
        $result_check = mysqli_query($conn, $query_check);
        
        if (mysqli_num_rows($result_check) > 0) {
            $error = "Username sudah digunakan!";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Simpan admin baru
            $query_insert = "INSERT INTO admin (username, password, nama_lengkap) VALUES ('$username', '$hashed_password', '$nama_lengkap')";
            
            if (mysqli_query($conn, $query_insert)) {
                $success = "Admin baru berhasil ditambahkan!";
            } else {
                $error = "Gagal menambahkan admin: " . mysqli_error($conn);
            }
        }
    }
}

// Proses hapus admin
if (isset($_GET['delete'])) {
    $id_admin = (int) $_GET['delete'];
    
    // Cek apakah admin yang akan dihapus adalah admin yang sedang login
    if ($id_admin == $_SESSION['admin_id']) {
        $error = "Anda tidak dapat menghapus akun admin yang sedang digunakan!";
    } else {
        // Hapus admin
        $query_delete = "DELETE FROM admin WHERE id_admin = $id_admin";
        
        if (mysqli_query($conn, $query_delete)) {
            $success = "Admin berhasil dihapus!";
        } else {
            $error = "Gagal menghapus admin: " . mysqli_error($conn);
        }
    }
}

// Ambil data admin
$query = "SELECT * FROM admin ORDER BY nama_lengkap";
$result = mysqli_query($conn, $query);
$admins = [];
while ($row = mysqli_fetch_assoc($result)) {
    $admins[] = $row;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Admin - SPK Jurusan</title>
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
            <h1 class="
<!-- Tambahkan tombol Reset Password -->
<a href="reset_password.php" class="bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded transition-colors ml-2">
    <i class="fas fa-key mr-2"></i>Reset Password
</a>