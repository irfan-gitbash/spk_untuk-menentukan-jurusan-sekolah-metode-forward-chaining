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

// Ambil semua ciri kecerdasan
$ciri_kecerdasan = getAllCiriKecerdasan();

// Cek apakah ada pertanyaan aktif
$current_question = 0;
if (isset($_GET['q'])) {
    $current_question = (int) $_GET['q'];
}

// Jika form jawaban disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_jawaban'])) {
    $id_ciri = (int) $_POST['id_ciri'];
    $jawaban = clean($_POST['submit_jawaban']);
    saveJawaban($id_siswa, $id_ciri, $jawaban);
    if (!empty($ciri_kecerdasan) && $current_question < count($ciri_kecerdasan) - 1) {
        header("Location: question.php?id=$id_siswa&q=" . ($current_question + 1));
        exit;
    } else {
        $hasil = forwardChaining($id_siswa);
        header("Location: hasil.php?id=$id_siswa");
        exit;
    }
}

// Dapatkan pertanyaan saat ini
$current_ciri = !empty($ciri_kecerdasan) && isset($ciri_kecerdasan[$current_question]) ? $ciri_kecerdasan[$current_question] : null;

// Dapatkan informasi kecerdasan terkait
$kecerdasan_info = null;
if (!empty($current_ciri) && isset($current_ciri['id_kecerdasan'])) {
    $kecerdasan_info = getKecerdasanById($current_ciri['id_kecerdasan']);
}

// Dapatkan jawaban yang sudah diberikan
$jawaban_sebelumnya = getJawabanSiswa($id_siswa);

$nama = $siswa['nama_lengkap'];
// Hapus baris berikut:
// $umur = rand(15, 18); // Simulasi umur untuk tampilan

// Ambil NISN dan kelas dari data siswa
$nisn = $siswa['nisn'];
$kelas = $siswa['kelas'];

include 'includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-center">
        <div class="w-full max-w-4xl">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Header Card -->
                <div class="bg-primary text-white p-4">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-bold">Pertanyaan Diagnosis</h2>
                        <span class="bg-white text-primary px-3 py-1 rounded-full text-sm font-semibold">Siswa ID: <?php echo $id_siswa; ?></span>
                    </div>
                </div>
                
                <!-- Body Card -->
                <div class="p-6">
                    <!-- Informasi Siswa -->
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                        <div class="flex items-center">
                            <div class="bg-primary text-white rounded-full w-14 h-14 flex items-center justify-center text-2xl mr-4">
                                <?php echo substr($nama, 0, 1); ?>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold">Hai, <?php echo $nama; ?></h3>
                                <p class="text-gray-600">NISN: <?php echo $nisn; ?> | Kelas: <?php echo $kelas; ?></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-gray-500 block">Tanggal Konsultasi:</span>
                            <strong><?php echo date('d F Y'); ?></strong>
                        </div>
                    </div>
                    
                    <?php if (!empty($current_ciri)): ?>
                        <!-- Container Pertanyaan -->
                        <div class="bg-gray-50 p-6 rounded-lg border-l-4 border-primary mb-6 transition-all duration-300 hover:shadow-md">
                            <?php if (!empty($kecerdasan_info)): ?>
                            <div class="mb-4">
                                <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-sm"><?php echo $kecerdasan_info['nama_kecerdasan']; ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <div class="text-primary font-semibold mb-2">
                                Pertanyaan <?php echo ($current_question + 1); ?> dari <?php echo count($ciri_kecerdasan); ?>
                            </div>
                            
                            <h4 class="text-xl font-bold mb-6"><?php echo $current_ciri['isi_ciri']; ?></h4>
                            
                            <form method="post" action="" class="mt-6">
                                <input type="hidden" name="id_ciri" value="<?php echo $current_ciri['id_ciri']; ?>">
                                
                                <div class="flex flex-col sm:flex-row justify-center gap-4">
                                    <button type="submit" name="submit_jawaban" value="Ya" class="bg-primary hover:bg-primary-dark text-white py-3 px-8 rounded-lg text-lg font-semibold transition-transform duration-200 hover:-translate-y-1 flex items-center justify-center">
                                        <i class="fas fa-check-circle mr-2"></i> Ya
                                    </button>
                                    <button type="submit" name="submit_jawaban" value="Tidak" class="border-2 border-gray-300 hover:border-gray-400 text-gray-700 py-3 px-8 rounded-lg text-lg font-semibold transition-transform duration-200 hover:-translate-y-1 flex items-center justify-center">
                                        <i class="fas fa-times-circle mr-2"></i> Tidak
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <?php if (!empty($kecerdasan_info)): ?>
                        <!-- Informasi Kecerdasan -->
                        <div class="bg-white p-4 rounded-lg border mb-6">
                            <h5 class="text-primary font-bold mb-2">Tentang <?php echo $kecerdasan_info['nama_kecerdasan']; ?></h5>
                            <p class="text-gray-700"><?php echo $kecerdasan_info['deskripsi']; ?></p>
                        </div>
                        <?php endif; ?>
                        
                    <?php else: ?>
                        <!-- Pesan Tidak Ada Pertanyaan -->
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-triangle text-yellow-500 mr-3"></i>
                                <p>Tidak ada pertanyaan tersedia atau semua pertanyaan telah dijawab.</p>
                            </div>
                        </div>
                        <div class="text-center mt-6">
                            <a href="konsultasi.php" class="bg-primary hover:bg-primary-dark text-white py-2 px-6 rounded-lg transition-colors duration-200">Kembali ke Konsultasi</a>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($jawaban_sebelumnya) && count($jawaban_sebelumnya) > 0): ?>
                    <!-- Jawaban Sebelumnya -->
                    <div class="mt-8">
                        <h5 class="text-primary font-bold mb-4">Jawaban Sebelumnya</h5>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="py-2 px-4 text-left">No</th>
                                        <th class="py-2 px-4 text-left">Pertanyaan</th>
                                        <th class="py-2 px-4 text-left">Jawaban</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <?php $i = 1; foreach($jawaban_sebelumnya as $jawaban): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-2 px-4"><?php echo $i++; ?></td>
                                        <td class="py-2 px-4"><?php echo $jawaban['isi_pertanyaan']; ?></td>
                                        <td class="py-2 px-4">
                                            <?php if($jawaban['jawaban'] == 'Ya'): ?>
                                                <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs">Ya</span>
                                            <?php else: ?>
                                                <span class="bg-gray-500 text-white px-2 py-1 rounded-full text-xs">Tidak</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Footer Card -->
                <div class="bg-gray-50 p-4 border-t">
                    <!-- Progress Bar -->
                    <div class="w-full bg-gray-200 rounded-full h-4 mb-4">
                        <?php
                        $total = count($ciri_kecerdasan);
                        $progress = ($total > 0) ? (($current_question + 1) / $total) * 100 : 0;
                        ?>
                        <div class="bg-primary h-4 rounded-full text-xs text-white flex items-center justify-center" 
                             style="width: <?php echo $progress; ?>%">
                            <?php echo ($total > 0 ? ($current_question + 1) . '/' . $total : '0/0'); ?>
                        </div>
                    </div>
                    
                    <!-- Navigation Buttons -->
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
                        <a href="konsultasi.php" class="text-gray-600 hover:text-gray-800 flex items-center">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </a>
                        
                        <?php if ($current_question > 0): ?>
                        <a href="question.php?id=<?php echo $id_siswa; ?>&q=<?php echo ($current_question - 1); ?>" class="text-primary hover:text-primary-dark flex items-center">
                            <i class="fas fa-chevron-left mr-1"></i> Pertanyaan Sebelumnya
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Footer Text -->
            <div class="text-center mt-6 text-gray-500 text-sm">
                <p>Sistem Pendukung Keputusan Pemilihan Jurusan &copy; <?php echo date('Y'); ?></p>
            </div>
        </div>
    </div>
</div>

<style>
/* Tambahan CSS untuk animasi dan efek */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.bg-primary {
    background-color: #17a2b8;
}

.bg-primary-dark {
    background-color: #138496;
}

.text-primary {
    color: #17a2b8;
}

.border-primary {
    border-color: #17a2b8;
}

.hover\:bg-primary-dark:hover {
    background-color: #138496;
}

.hover\:text-primary-dark:hover {
    color: #138496;
}

/* Animasi untuk pertanyaan */
.bg-gray-50 {
    animation: fadeIn 0.5s ease-out;
}

/* Responsif untuk layar kecil */
@media (max-width: 640px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
}
</style>

<?php include 'includes/footer.php'; ?>