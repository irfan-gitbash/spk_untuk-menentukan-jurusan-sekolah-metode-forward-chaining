</div>
    </div>

    <footer class="footer mt-auto py-4">
        <div class="container">
            <div class="row g-4">
                <div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-0">
                    <h5 class="text-white mb-3">Tentang Kami</h5>
                    <p class="text-white-50">Sistem Pendukung Keputusan untuk Menentukan jurusan sma dan smk menggunakan metode forward chaining.</p>
                </div>
                <div class="col-12 col-md-6 col-lg-4 mb-4 mb-lg-0">
                    <h5 class="text-white mb-3">Menu Utama</h5>
                    <ul class="list-unstyled footer-links">
                        <li><a href="index.php" class="text-white-50"><i class="fas fa-chevron-right me-2"></i>Beranda</a></li>
                        <li><a href="konsultasi.php" class="text-white-50"><i class="fas fa-chevron-right me-2"></i>Konsultasi</a></li>
                        <li><a href="jurusan.php" class="text-white-50"><i class="fas fa-chevron-right me-2"></i>Jurusan</a></li>
                        <li><a href="tentang.php" class="text-white-50"><i class="fas fa-chevron-right me-2"></i>Tentang</a></li>
                        <li><a href="hasiloutput.php" class="text-white-50"><i class="fas fa-chevron-right me-2"></i>Data Hasil</a></li>
                    </ul>
                </div>
                <div class="col-12 col-lg-4">
                    <h5 class="text-white mb-3">Kontak</h5>
                    <ul class="list-unstyled text-white-50">
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i>Email: info@spkjurusan.com</li>
                        <li class="mb-2"><i class="fas fa-phone me-2"></i>Telepon: (021) 1234567</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i>Alamat: Jl. Pendidikan No. 123</li>
                    </ul>
                </div>
            </div>
            <hr class="mt-4 mb-3 border-light">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="text-white-50 mb-0">&copy; <?php echo date('Y'); ?> Sistem Pendukung Keputusan Jurusan SMA dan SMK</p>
                </div>
                <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                    <div class="social-links">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <style>
        .footer {
            background-color: var(--primary-color);
        }

        .footer-links a {
            text-decoration: none;
            transition: color 0.3s ease;
            display: block;
            padding: 5px 0;
        }

        .footer-links a:hover {
            color: white !important;
        }

        .social-links a {
            text-decoration: none;
            transition: opacity 0.3s ease;
        }

        .social-links a:hover {
            opacity: 0.8;
        }

        @media (max-width: 768px) {
            .footer {
                text-align: center;
            }

            .footer-links {
                margin-bottom: 2rem;
            }

            .social-links {
                margin-top: 1rem;
            }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
