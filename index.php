<?php
include 'includes/header.php';
?>

<div class="jumbotron text-center">
    <div class="container py-5">
        <h1 class="display-4 fw-bold mb-4">Selamat Datang di Sistem Pendukung Keputusan</h1>
        <p class="lead mb-4">Sistem ini akan membantu Anda menentukan jurusan yang sesuai di SMA atau SMK berdasarkan minat dan kemampuan Anda.</p>
        <hr class="my-4 bg-white opacity-25">
        <p class="mb-4">Mulai konsultasi sekarang untuk mendapatkan rekomendasi jurusan yang tepat untuk masa depan Anda.</p>
        <a class="btn btn-light btn-lg shadow-sm hover-lift" href="konsultasi.php" role="button">
            <i class="fas fa-arrow-right me-2"></i>Mulai Konsultasi
        </a>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <div class="col-12 col-md-4">
            <div class="card h-100 border-0 shadow-sm hover-lift">
                <div class="card-body text-center p-4">
                    <div class="feature-icon-wrapper mb-3">
                        <i class="fas fa-user-graduate fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title fw-bold mb-3">Konsultasi</h5>
                    <p class="card-text text-muted mb-4">Jawab beberapa pertanyaan untuk mendapatkan rekomendasi jurusan yang sesuai dengan minat dan kemampuan Anda.</p>
                    <a href="konsultasi.php" class="btn btn-primary w-100">
                        <i class="fas fa-comments me-2"></i>Mulai Konsultasi
                    </a>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card h-100 border-0 shadow-sm hover-lift">
                <div class="card-body text-center p-4">
                    <div class="feature-icon-wrapper mb-3">
                        <i class="fas fa-book fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title fw-bold mb-3">Jurusan</h5>
                    <p class="card-text text-muted mb-4">Lihat informasi lengkap tentang berbagai jurusan di SMA dan SMK beserta prospek karirnya.</p>
                    <a href="jurusan.php" class="btn btn-primary w-100">
                        <i class="fas fa-graduation-cap me-2"></i>Lihat Jurusan
                    </a>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card h-100 border-0 shadow-sm hover-lift">
                <div class="card-body text-center p-4">
                    <div class="feature-icon-wrapper mb-3">
                        <i class="fas fa-info-circle fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title fw-bold mb-3">Tentang</h5>
                    <p class="card-text text-muted mb-4">Pelajari lebih lanjut tentang sistem pendukung keputusan ini dan metode forward chaining yang digunakan.</p>
                    <a href="tentang.php" class="btn btn-primary w-100">
                        <i class="fas fa-info me-2"></i>Tentang Sistem
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Additional styles for index page */
.hover-lift {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.1) !important;
}

.feature-icon-wrapper {
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    border-radius: 50%;
    background-color: rgba(23, 162, 184, 0.1);
}

@media (max-width: 768px) {
    .jumbotron {
        padding: 2rem 0;
    }
    
    .display-4 {
        font-size: 2rem;
    }
    
    .lead {
        font-size: 1.1rem;
    }
    
    .btn-lg {
        padding: 0.5rem 1rem;
        font-size: 1rem;
    }
}

/* Animation for cards */
.card {
    animation: fadeInUp 0.6s ease-out;
    animation-fill-mode: both;
}

.card:nth-child(1) {
    animation-delay: 0.2s;
}

.card:nth-child(2) {
    animation-delay: 0.4s;
}

.card:nth-child(3) {
    animation-delay: 0.6s;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<?php
include 'includes/footer.php';
?>
