<?php
require_once 'includes/functions.php';

// Cek apakah ada ID siswa
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: konsultasi.php");
    exit;
}

$id_siswa = (int) $_GET['id'];
$siswa = getSiswaById($id_siswa);

// Jika siswa tidak ditemukan
if (!$siswa) {
    header("Location: konsultasi.php");
    exit;
}

// Ambil hasil konsultasi
$hasil = getHasilKonsultasi($id_siswa);

// Jika hasil tidak ditemukan
if (!$hasil) {
    header("Location: konsultasi.php");
    exit;
}

// Dapatkan skor kecerdasan
$skor_kecerdasan = hitungSkorKecerdasan($id_siswa);
$id_kecerdasan_dominan = getKecerdasanDominan($id_siswa);

// Dapatkan data kecerdasan dominan
$query = "SELECT * FROM kecerdasan WHERE id_kecerdasan = $id_kecerdasan_dominan";
$result = mysqli_query($conn, $query);
$kecerdasan_dominan = mysqli_fetch_assoc($result);

// Dapatkan jurusan yang sesuai dengan kecerdasan dominan
$jurusan_rekomendasi = getJurusanByKecerdasan($id_kecerdasan_dominan);

// Ambil jawaban siswa yang dikelompokkan berdasarkan kecerdasan
$grouped_answers = getGroupedAnswersByIntelligence($id_siswa);

// Ambil detail perhitungan forward chaining
$detail_perhitungan = getDetailedForwardChainingCalculation($id_siswa);

include 'includes/header.php';
?>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
    <!-- Data Siswa Card - Responsive untuk Mobile dan Desktop -->
    <div class="bg-white rounded-lg shadow-md mb-6">
        <div class="bg-blue-500 text-white p-3">
            <h5 class="font-bold text-sm sm:text-base">Data Siswa</h5>
        </div>
        <div class="p-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-xs sm:text-sm mb-1"><span class="font-bold">Nama:</span> <?php echo $siswa['nama_lengkap']; ?></p>
                </div>
                <div>
                    <p class="text-xs sm:text-sm mb-1"><span class="font-bold">NISN:</span> <?php echo $siswa['nisn']; ?></p>
                </div>
                <div>
                    <p class="text-xs sm:text-sm mb-1"><span class="font-bold">Kelas:</span> <?php echo $siswa['kelas']; ?></p>
                </div>
                <div>
                    <p class="text-xs sm:text-sm mb-1"><span class="font-bold">Sekolah:</span> <?php echo $siswa['sekolah']; ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Kiri: Hasil Konsultasi -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Hasil Konsultasi Card -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="bg-green-600 text-white p-3 text-center">
                    <h4 class="font-bold text-base sm:text-lg">Hasil Konsultasi</h4>
                </div>
                <div class="p-4">
                    <!-- Kecerdasan Dominan Section -->
                    <div class="text-center mb-6 py-2">
                        <h3 class="text-base sm:text-lg font-bold mb-2">Kecerdasan Dominan</h3>
                        <div class="text-xl sm:text-2xl text-primary font-bold mb-2"><?php echo htmlspecialchars($kecerdasan_dominan['nama_kecerdasan']); ?></div>
                        <p class="text-gray-600 text-xs sm:text-sm"><?php echo htmlspecialchars($kecerdasan_dominan['deskripsi']); ?></p>
                    </div>

                    <!-- Rekomendasi Section -->
                    <div class="text-center mb-6 py-2">
                        <h3 class="text-base sm:text-lg font-bold mb-2">Rekomendasi Jurusan</h3>
                        <div class="text-xl sm:text-2xl text-primary font-bold mb-2"><?php echo htmlspecialchars($hasil['nama_jurusan']); ?></div>
                        <span class="bg-gray-200 text-gray-800 px-2 py-1 rounded-full text-xs"><?php echo htmlspecialchars($hasil['jenis_sekolah']); ?></span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Deskripsi Card -->
                        <div>
                            <div class="bg-white border rounded-lg h-full">
                                <div class="bg-primary text-white p-2 rounded-t-lg">
                                    <h5 class="font-bold text-xs sm:text-sm">Deskripsi Jurusan</h5>
                                </div>
                                <div class="p-3">
                                    <p class="text-xs sm:text-sm"><?php echo htmlspecialchars($hasil['deskripsi']); ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Prospek Card -->
                        <div>
                            <div class="bg-white border rounded-lg h-full">
                                <div class="bg-primary text-white p-2 rounded-t-lg">
                                    <h5 class="font-bold text-xs sm:text-sm">Prospek Karir</h5>
                                </div>
                                <div class="p-3">
                                    <p class="text-xs sm:text-sm"><?php echo htmlspecialchars($hasil['prospek_karir']); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Perhitungan Forward Chaining Section -->
            <div class="bg-white rounded-lg shadow-md p-4">
                <h5 class="font-bold text-sm mb-3">Perhitungan Metode Forward Chaining</h5>
                <p class="text-xs mb-2">Berikut adalah perhitungan skor kecerdasan berdasarkan jawaban siswa:</p>
                <ul class="list-disc list-inside text-xs mb-4">
                    <?php foreach ($detail_perhitungan['skor_kecerdasan'] as $id_kecerdasan => $skor): ?>
                        <li>
                            <?php 
                                $nama_kecerdasan = '';
                                foreach ($grouped_answers as $key => $val) {
                                    if ($key == $id_kecerdasan) {
                                        $nama_kecerdasan = $val['nama_kecerdasan'];
                                        break;
                                    }
                                }
                                echo htmlspecialchars($nama_kecerdasan) . ": " . $skor . " poin";
                            ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <p class="text-xs mb-2">Kecerdasan dengan skor tertinggi adalah <strong><?php echo htmlspecialchars($detail_perhitungan['kecerdasan_tertinggi']['nama_kecerdasan']); ?></strong> dengan skor <strong><?php echo $detail_perhitungan['skor_tertinggi']; ?></strong>.</p>
                <p class="text-xs mb-2">Berdasarkan kecerdasan dominan tersebut, jurusan yang direkomendasikan adalah:</p>
                <ul class="list-disc list-inside text-xs">
                    <?php foreach ($detail_perhitungan['jurusan_terkait'] as $jurusan): ?>
                        <li><?php echo htmlspecialchars($jurusan['nama_jurusan']) . " (" . htmlspecialchars($jurusan['jenis_sekolah']) . ")"; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Aturan IF-THEN Forward Chaining -->
            <div class="bg-white rounded-lg shadow-md p-4">
                <h5 class="font-bold text-sm mb-3">Aturan IF-THEN Forward Chaining</h5>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 text-xs">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="py-2 px-3 border-b text-left">Aturan</th>
                                <th class="py-2 px-3 border-b text-left">Kondisi (IF)</th>
                                <th class="py-2 px-3 border-b text-left">Hasil (THEN)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="py-2 px-3 border-b">Aturan 1</td>
                                <td class="py-2 px-3 border-b">IF jawaban = "Ya" pada pertanyaan terkait kecerdasan</td>
                                <td class="py-2 px-3 border-b">THEN tambahkan skor pada kecerdasan tersebut</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-3 border-b">Aturan 2</td>
                                <td class="py-2 px-3 border-b">IF skor kecerdasan X > skor kecerdasan lainnya</td>
                                <td class="py-2 px-3 border-b">THEN kecerdasan X adalah kecerdasan dominan</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-3 border-b">Aturan 3</td>
                                <td class="py-2 px-3 border-b">IF kecerdasan dominan = <?php echo htmlspecialchars($kecerdasan_dominan['nama_kecerdasan']); ?></td>
                                <td class="py-2 px-3 border-b">THEN rekomendasikan jurusan <?php echo htmlspecialchars($hasil['nama_jurusan']); ?> (<?php echo htmlspecialchars($hasil['jenis_sekolah']); ?>)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                    <h6 class="font-semibold text-xs mb-2">Penerapan Aturan pada Konsultasi Ini:</h6>
                    <p class="text-xs mb-1">1. Berdasarkan jawaban "Ya" yang diberikan, <?php echo htmlspecialchars($kecerdasan_dominan['nama_kecerdasan']); ?> memiliki skor tertinggi yaitu <?php echo $detail_perhitungan['skor_tertinggi']; ?> poin.</p>
                    <p class="text-xs mb-1">2. Karena kecerdasan dominan adalah <?php echo htmlspecialchars($kecerdasan_dominan['nama_kecerdasan']); ?>, maka jurusan yang direkomendasikan adalah <?php echo htmlspecialchars($hasil['nama_jurusan']); ?> (<?php echo htmlspecialchars($hasil['jenis_sekolah']); ?>).</p>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex flex-wrap gap-2 mt-6">
                <a href="cetak_hasil.php?id=<?php echo $id_siswa; ?>" target="_blank" class="bg-primary hover:bg-secondary text-white py-2 px-4 rounded-md transition-colors flex items-center justify-center text-xs sm:text-sm">
                    <i class="fas fa-print mr-2"></i>Cetak Hasil
                </a>
                <a href="konsultasi.php" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md transition-colors flex items-center justify-center text-xs sm:text-sm">
                    <i class="fas fa-redo mr-2"></i>Konsultasi Baru
                </a>
                <a href="index.php" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-md transition-colors flex items-center justify-center text-xs sm:text-sm">
                    <i class="fas fa-home mr-2"></i>Kembali ke Beranda
                </a>
            </div>
        </div>

        <!-- Kolom Kanan: Jawaban Sebelumnya -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md h-full">
                <div class="bg-blue-500 text-white p-3">
                    <h5 class="font-bold text-sm">Jawaban Sebelumnya</h5>
                </div>
                <div class="p-4 overflow-y-auto max-h-[600px]">
                    <div class="space-y-4">
                        <?php foreach ($grouped_answers as $id_kecerdasan => $data): ?>
                            <div class="mb-4">
                                <h6 class="font-semibold text-xs sm:text-sm mb-2 bg-gray-100 p-2 rounded"><?php echo htmlspecialchars($data['nama_kecerdasan']); ?></h6>
                                <ul class="list-disc list-inside text-xs space-y-1">
                                    <?php foreach ($data['pertanyaan'] as $pertanyaan): ?>
                                        <li class="<?php echo $pertanyaan['jawaban'] == 'Ya' ? 'text-green-600 font-medium' : 'text-gray-500'; ?>">
                                            <?php echo htmlspecialchars($pertanyaan['isi_pertanyaan']); ?>:
                                            <span class="font-bold"><?php echo htmlspecialchars($pertanyaan['jawaban']); ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
