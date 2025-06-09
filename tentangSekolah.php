
<?php include 'includes/header.php'; ?>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10 max-w-7xl">
    <!-- Hero Section dengan animasi dan responsif -->
    <div class="text-center mb-8 sm:mb-12 animate-fade-in-down">
        <div class="relative overflow-hidden rounded-xl shadow-2xl mb-6 mx-auto max-w-md hover:shadow-primary transition-shadow duration-300">
            <img src="images/sekolah.jpg" alt="Logo Sekolah" class="w-full h-auto object-cover transform hover:scale-105 transition-transform duration-500">
            <div class="absolute inset-0 bg-gradient-to-t from-primary/60 to-transparent opacity-0 hover:opacity-100 transition-opacity duration-300"></div>
        </div>
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold mb-3 sm:mb-4 text-primary bg-clip-text text-transparent bg-gradient-to-r from-primary to-secondary">Tentang Sekolah</h1>
        <p class="text-base sm:text-lg text-gray-700 max-w-xl sm:max-w-2xl md:max-w-4xl mx-auto leading-relaxed">
            Selamat datang di halaman resmi sekolah kami. Kami berkomitmen untuk memberikan pendidikan terbaik yang mengembangkan potensi setiap siswa secara maksimal.
        </p>
    </div>

    <!-- Grid layout untuk konten pada layar yang lebih besar -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-10">
        <!-- Visi Sekolah dengan efek hover dan animasi -->
        <section class="mb-8 sm:mb-12 transform transition-all duration-300 hover:-translate-y-1 hover:shadow-lg rounded-xl">
            <div class="flex items-center mb-4">
                <div class="bg-primary rounded-full p-2 mr-3">
                    <i class="fas fa-eye text-white"></i>
                </div>
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-secondary">Visi Sekolah</h2>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 border-l-4 border-primary hover:border-secondary transition-colors duration-300">
                <p class="text-base sm:text-lg text-gray-700 leading-relaxed">
                    Menjadi sekolah unggulan yang menghasilkan lulusan berkarakter, berprestasi, dan siap menghadapi tantangan global dengan mengedepankan nilai-nilai kejujuran, disiplin, dan kreativitas. Kami berfokus pada pengembangan akademik, keterampilan sosial, dan teknologi untuk menciptakan generasi masa depan yang kompeten dan bertanggung jawab.
                </p>
            </div>
        </section>

        <!-- Misi Sekolah dengan efek hover dan animasi -->
        <section class="transform transition-all duration-300 hover:-translate-y-1 hover:shadow-lg rounded-xl">
            <div class="flex items-center mb-4">
                <div class="bg-secondary rounded-full p-2 mr-3">
                    <i class="fas fa-bullseye text-white"></i>
                </div>
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-secondary">Misi Sekolah</h2>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 border-l-4 border-secondary hover:border-primary transition-colors duration-300">
                <ul class="space-y-2 sm:space-y-3 text-base sm:text-lg text-gray-700 leading-relaxed">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-primary mt-1 mr-2"></i>
                        <span>Menyelenggarakan proses pembelajaran yang inovatif dan berpusat pada siswa.</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-primary mt-1 mr-2"></i>
                        <span>Mengembangkan karakter dan moral siswa melalui kegiatan ekstrakurikuler dan pembinaan kepribadian.</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-primary mt-1 mr-2"></i>
                        <span>Meningkatkan kompetensi guru dan staf melalui pelatihan dan pengembangan profesional.</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-primary mt-1 mr-2"></i>
                        <span>Mendorong partisipasi aktif siswa dalam kegiatan sosial dan kemasyarakatan.</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-primary mt-1 mr-2"></i>
                        <span>Mengoptimalkan pemanfaatan teknologi informasi dalam proses belajar mengajar.</span>
                    </li>
                </ul>
            </div>
        </section>
    </div>

    <!-- Bagian tambahan: Fasilitas Sekolah -->
    <section class="mt-12 sm:mt-16">
        <div class="flex items-center justify-center mb-6">
            <div class="bg-gradient-to-r from-primary to-secondary h-1 w-16 mr-4"></div>
            <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-800">Fasilitas Sekolah</h2>
            <div class="bg-gradient-to-r from-secondary to-primary h-1 w-16 ml-4"></div>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Fasilitas 1 -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden transform transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
                <div class="p-5 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center">
                        <i class="fas fa-laptop-code text-2xl text-primary mr-3"></i>
                        <h3 class="text-lg font-semibold text-gray-800">Laboratorium Komputer</h3>
                    </div>
                </div>
                <div class="p-5">
                    <p class="text-gray-700">Dilengkapi dengan perangkat komputer terbaru dan koneksi internet cepat untuk mendukung pembelajaran digital dan pemrograman.</p>
                </div>
            </div>
            
            <!-- Fasilitas 2 -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden transform transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
                <div class="p-5 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center">
                        <i class="fas fa-flask text-2xl text-primary mr-3"></i>
                        <h3 class="text-lg font-semibold text-gray-800">Laboratorium Sains</h3>
                    </div>
                </div>
                <div class="p-5">
                    <p class="text-gray-700">Fasilitas lengkap untuk praktikum fisika, kimia, dan biologi dengan peralatan modern dan standar keamanan tinggi.</p>
                </div>
            </div>
            
            <!-- Fasilitas 3 -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden transform transition-all duration-300 hover:-translate-y-2 hover:shadow-xl">
                <div class="p-5 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center">
                        <i class="fas fa-book-reader text-2xl text-primary mr-3"></i>
                        <h3 class="text-lg font-semibold text-gray-800">Perpustakaan Digital</h3>
                    </div>
                </div>
                <div class="p-5">
                    <p class="text-gray-700">Koleksi buku fisik dan digital yang lengkap dengan area belajar yang nyaman dan akses ke jurnal ilmiah online.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <div class="mt-12 sm:mt-16 bg-gradient-to-r from-primary to-secondary rounded-xl p-6 sm:p-8 text-white text-center">
        <h2 class="text-xl sm:text-2xl font-bold mb-4">Tertarik untuk bergabung dengan kami?</h2>
        <p class="mb-6 max-w-2xl mx-auto">Jadilah bagian dari komunitas pendidikan kami dan kembangkan potensi Anda bersama para profesional pendidikan yang berpengalaman.</p>
        <a href="konsultasi.php" class="inline-block bg-white text-primary font-medium px-6 py-3 rounded-lg hover:bg-gray-100 transition-colors duration-300 transform hover:scale-105">Mulai Konsultasi</a>
    </div>
</div>

<!-- Tambahkan style untuk animasi -->
<style>
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in-down {
        animation: fadeInDown 0.8s ease-out;
    }
</style>

<?php include 'includes/footer.php'; ?>