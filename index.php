<?php
include 'includes/header.php';
?>

<div class="bg-white">
  <div class="container mx-auto px-6 py-16 flex flex-col lg:flex-row items-center gap-12 max-w-7xl">
    <div class="lg:w-1/2">
      <h1 class="text-4xl font-extrabold text-gray-900 mb-6 leading-tight">
        Selamat Datang di <br />
        <span class="text-primary">Sistem Pendukung Keputusan</span>
      </h1>
      <p class="text-gray-600 mb-8 max-w-lg">
        Sistem ini akan membantu Anda menentukan jurusan yang sesuai di SMA atau SMK berdasarkan minat dan kemampuan Anda.
      </p>
      <a href="konsultasi.php" class="inline-block bg-primary text-white px-8 py-3 rounded-lg shadow-lg hover:bg-secondary transition">
        Mulai Konsultasi
      </a>
    </div>
    <div class="lg:w-1/2">
      <img src="images/sekolah.jpg" class="w-full max-w-md mx-auto" />
    </div>
  </div>
</div>

<div class="container mx-auto px-6 py-12 max-w-7xl">
  <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <div class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-2xl transition-shadow">
      <div class="mb-6 inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary/20 text-primary mx-auto">
        <i class="fas fa-comments fa-2x"></i>
      </div>
      <h3 class="text-xl font-semibold mb-3">Konsultasi</h3>
      <p class="text-gray-600 mb-6">
        Jawab beberapa pertanyaan untuk mendapatkan rekomendasi jurusan yang sesuai dengan minat dan kemampuan Anda.
      </p>
      <a href="konsultasi.php" class="inline-block bg-primary text-white px-6 py-2 rounded-md hover:bg-secondary transition">
        Mulai Konsultasi
      </a>
    </div>
    <div class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-2xl transition-shadow">
      <div class="mb-6 inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary/20 text-primary mx-auto">
        <i class="fas fa-book fa-2x"></i>
      </div>
      <h3 class="text-xl font-semibold mb-3">Jurusan</h3>
      <p class="text-gray-600 mb-6">
        Lihat informasi lengkap tentang berbagai jurusan di SMA dan SMK beserta prospek karirnya.
      </p>
      <a href="jurusan.php" class="inline-block bg-primary text-white px-6 py-2 rounded-md hover:bg-secondary transition">
        Lihat Jurusan
      </a>
    </div>
    <div class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-2xl transition-shadow">
      <div class="mb-6 inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary/20 text-primary mx-auto">
        <i class="fas fa-info-circle fa-2x"></i>
      </div>
      <h3 class="text-xl font-semibold mb-3">Tentang</h3>
      <p class="text-gray-600 mb-6">
        Pelajari lebih lanjut tentang sistem pendukung keputusan ini dan metode forward chaining yang digunakan.
      </p>
      <a href="tentang.php" class="inline-block bg-primary text-white px-6 py-2 rounded-md hover:bg-secondary transition">
        Tentang Sistem
      </a>
    </div>
  </div>
</div>

<?php
include 'includes/footer.php';
?>
