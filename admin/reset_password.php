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

// Proses reset password
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin_id = (int)$_POST['admin_id'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validasi input
    if (empty($new_password) || empty($confirm_password)) {
        $error = "Semua field harus diisi!";
    } elseif ($new_password !== $confirm_password) {
        $error = "Konfirmasi password tidak sesuai!";
    } else {
        // Hash password baru
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Update password dengan prepared statement
        $query = "UPDATE admin SET password = ? WHERE id_admin = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "si", $hashed_password, $admin_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $success = "Password berhasil direset!";
        } else {
            $error = "Gagal mereset password: " . mysqli_error($conn);
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
    <title>Reset Password Admin - SPK Jurusan</title>
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
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Reset Password Admin</h1>
            <a href="dashboard.php" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
        
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
        
        <!-- Reset Password Form -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Reset Password Admin</h2>
            <form method="post" action="">
                <div class="mb-4">
                    <label for="admin_id" class="block text-gray-700 text-sm font-bold mb-2">Pilih Admin</label>
                    <select id="admin_id" name="admin_id" class="w-full border border-gray-300 rounded py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" required>
                        <?php foreach ($admins as $admin): ?>
                        <option value="<?php echo $admin['id_admin']; ?>"><?php echo $admin['nama_lengkap']; ?> (<?php echo $admin['username']; ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="new_password" class="block text-gray-700 text-sm font-bold mb-2">Password Baru</label>
                    <input type="password" id="new_password" name="new_password" class="w-full border border-gray-300 rounded py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" required>
                </div>
                
                <div class="mb-6">
                    <label for="confirm_password" class="block text-gray-700 text-sm font-bold mb-2">Konfirmasi Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="w-full border border-gray-300 rounded py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" required>
                </div>
                
                <button type="submit" class="bg-primary hover:bg-secondary text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition-colors">
                    <i class="fas fa-key mr-2"></i>Reset Password
                </button>
            </form>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="bg-white py-4 shadow-inner mt-auto">
        <div class="container mx-auto px-4">
            <p class="text-center text-gray-600 text-sm">
                &copy; <?php echo date('Y'); ?> Sistem Pendukung Keputusan Jurusan
            </p>
        </div>
    </footer>
</body>
</html>