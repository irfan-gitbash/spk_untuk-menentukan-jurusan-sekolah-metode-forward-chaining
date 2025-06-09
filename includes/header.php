<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

// Cek session untuk admin
session_start();
$is_admin = isset($_SESSION['admin_id']) ? true : false;
?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pendukung Keputusan Jurusan SMA dan SMK</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="assets/js/theme.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#17a2b8',
                        secondary: '#138496'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">
    <nav class="bg-primary shadow-md">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-3">
                <a class="text-white font-semibold text-xl flex items-center" href="index.php">
                    <i class="fas fa-graduation-cap mr-2"></i>
                    Sistem Pendukung Keputusan
                </a>
                <div class="block lg:hidden">
                    <button id="mobile-menu-button" class="text-white hover:text-gray-200 focus:outline-none p-2" style="min-width: 44px; min-height: 44px;">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
                <div id="menu" class="hidden lg:flex lg:items-center">
                    <ul class="lg:flex space-y-3 lg:space-y-0 lg:space-x-4">
                        <li>
                            <a class="text-white hover:bg-primary-dark px-3 py-2 rounded flex items-center" href="index.php">
                                <i class="fas fa-home mr-1"></i> Beranda
                            </a>
                        </li>
                        <li>
                            <a class="text-white hover:bg-primary-dark px-3 py-2 rounded flex items-center" href="konsultasi.php">
                                <i class="fas fa-comments mr-1"></i> Konsultasi
                            </a>
                        </li>
                        <li>
                            <a class="text-white hover:bg-primary-dark px-3 py-2 rounded flex items-center" href="jurusan.php">
                                <i class="fas fa-book mr-1"></i> Jurusan
                            </a>
                        </li>
                        <li>
                            <a class="text-white hover:bg-primary-dark px-3 py-2 rounded flex items-center" href="tentangSekolah.php">
                                <i class="fas fa-info-circle mr-1"></i>Tentang Sekolah
                            </a>
                        </li>
                        <li>
                            <a class="text-white hover:bg-primary-dark px-3 py-2 rounded flex items-center" href="tentang.php">
                                <i class="fas fa-info-circle mr-1"></i>Cara Kerja
                            </a>
                        </li>
                        <li>
                            <a class="text-white hover:bg-primary-dark px-3 py-2 rounded flex items-center" href="hasiloutput.php">
                                <i class="fas fa-chart-bar mr-1"></i> Data Hasil
                            </a>
                        </li>
                        <?php if ($is_admin): ?>
                        <li>
                            <a class="text-white hover:bg-primary-dark px-3 py-2 rounded flex items-center" href="admin/dashboard.php">
                                <i class="fas fa-user-shield mr-1"></i> Admin
                            </a>
                        </li>
                        <?php else: ?>
                        <li>
                            <a class="text-white hover:bg-primary-dark px-3 py-2 rounded flex items-center" href="admin/login.php">
                                <i class="fas fa-sign-in-alt mr-1"></i> Login
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="lg:hidden hidden bg-primary-dark pb-4 px-4">
            <ul class="space-y-2">
                <li>
                    <a class="text-white hover:bg-primary block px-3 py-2 rounded" href="index.php">
                        <i class="fas fa-home mr-1"></i> Beranda
                    </a>
                </li>
                <li>
                    <a class="text-white hover:bg-primary block px-3 py-2 rounded" href="konsultasi.php">
                        <i class="fas fa-comments mr-1"></i> Konsultasi
                    </a>
                </li>
                <li>
                    <a class="text-white hover:bg-primary block px-3 py-2 rounded" href="jurusan.php">
                        <i class="fas fa-book mr-1"></i> Jurusan
                    </a>
                </li>
                <li>
                    <a class="text-white hover:bg-primary block px-3 py-2 rounded" href="tentang.php">
                        <i class="fas fa-info-circle mr-1"></i> Tentang
                    </a>
                </li>
                <li>
                    <a class="text-white hover:bg-primary block px-3 py-2 rounded" href="hasiloutput.php">
                        <i class="fas fa-chart-bar mr-1"></i> Data Hasil
                    </a>
                </li>
                <?php if ($is_admin): ?>
                <li>
                    <a class="text-white hover:bg-primary block px-3 py-2 rounded" href="admin/dashboard.php">
                        <i class="fas fa-user-shield mr-1"></i> Admin
                    </a>
                </li>
                <?php else: ?>
                <li>
                    <a class="text-white hover:bg-primary block px-3 py-2 rounded" href="admin/login.php">
                        <i class="fas fa-sign-in-alt mr-1"></i> Login
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <div class="flex-grow">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6 max-w-7xl">
