<?php
require_once 'includes/functions.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['submit_siswa'])) {
        $nama = clean($_POST['nama']);
        $nisn = clean($_POST['nisn']);
        $kelas = clean($_POST['kelas']);
        $sekolah = clean($_POST['sekolah']);
        
        if (empty($nama) || empty($nisn) || empty($kelas) || empty($sekolah)) {
            $error = "Semua field harus diisi!";
        } else {
            $id_siswa = saveSiswa($nama, $nisn, $kelas, $sekolah);
            header("Location: question.php?id=$id_siswa");
            exit;
        }
    }
}

include 'includes/header.php';
?>

<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-6 py-12">
        <div class="max-w-4xl mx-auto">
            <!-- Header Section -->
            <div class="text-center mb-12">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Form Konsultasi Pemilihan Jurusan</h1>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Silakan isi data diri Anda untuk memulai konsultasi pemilihan jurusan berdasarkan jenis kecerdasan yang Anda miliki.
                </p>
            </div>

            <!-- Main Content -->
            <div class="grid md:grid-cols-5 gap-8">
                <!-- Form Section -->
                <div class="md:col-span-3">
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <?php if ($error): ?>
                            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-circle"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm"><?php echo $error; ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="" class="space-y-6">
                            <div>
                                <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" id="nama" name="nama" required
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200"
                                    placeholder="Masukkan nama lengkap">
                            </div>

                            <div>
                                <label for="nisn" class="block text-sm font-medium text-gray-700 mb-2">NISN</label>
                                <input type="text" id="nisn" name="nisn" required
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200"
                                    placeholder="Masukkan NISN">
                            </div>

                            <div>
                                <label for="kelas" class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                                <input type="text" id="kelas" name="kelas" required
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200"
                                    placeholder="Masukkan kelas">
                            </div>

                            <div>
                                <label for="sekolah" class="block text-sm font-medium text-gray-700 mb-2">Sekolah</label>
                                <input type="text" id="sekolah" name="sekolah" required
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200"
                                    placeholder="Masukkan nama sekolah">
                            </div>

                            <div class="pt-4">
                                <button type="submit" name="submit_siswa"
                                    class="w-full bg-primary text-white py-3 rounded-lg hover:bg-secondary transition duration-200 flex items-center justify-center space-x-2">
                                    <span>Mulai Konsultasi</span>
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                                <a href="index.php"
                                    class="w-full mt-3 bg-white border border-gray-300 text-gray-700 py-3 rounded-lg hover:bg-gray-50 transition duration-200 flex items-center justify-center space-x-2">
                                    <i class="fas fa-home"></i>
                                    <span>Kembali ke Beranda</span>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Info Section -->
                <div class="md:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <div class="text-center mb-6">
                            <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-brain text-primary text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Informasi Tes Kecerdasan</h3>
                        </div>

                        <div class="space-y-6">
                            <p class="text-gray-600">
                                Tes ini akan menentukan jenis kecerdasan dominan yang Anda miliki berdasarkan teori Multiple Intelligence. Hasil tes akan digunakan untuk merekomendasikan jurusan yang sesuai dengan kecerdasan Anda.
                            </p>

                            <div class="bg-gray-50 rounded-xl p-6">
                                <h4 class="font-semibold text-gray-900 mb-4">Petunjuk Pengisian:</h4>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-primary/10 rounded-full flex items-center justify-center text-primary mr-3">1</span>
                                        <span>Jawablah semua pertanyaan dengan jujur sesuai dengan kondisi Anda.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-primary/10 rounded-full flex items-center justify-center text-primary mr-3">2</span>
                                        <span>Pilih "Ya" jika pernyataan sesuai dengan diri Anda, dan "Tidak" jika tidak sesuai.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-primary/10 rounded-full flex items-center justify-center text-primary mr-3">3</span>
                                        <span>Tidak ada jawaban benar atau salah dalam tes ini.</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
