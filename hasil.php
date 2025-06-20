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
                <h5 class="font-bold text-sm sm:text-lg mb-3">Aturan IF-THEN Forward Chaining</h5>
                <div class="overflow-x-auto">
                    <div class="mb-4 text-xs sm:text-sm">
                        <p class="mb-2">Forward Chaining adalah metode inferensi yang memulai pencarian dari fakta yang diketahui, kemudian mencocokkan fakta-fakta tersebut dengan bagian IF dari aturan IF-THEN. Bila ada fakta yang cocok dengan bagian IF, maka aturan tersebut dieksekusi.</p>
                    </div>
                    
                    <!-- Tabs untuk kategori aturan -->
                    <div class="mb-4">
                        <div class="flex flex-wrap border-b border-gray-200">
                            <button class="tab-btn active-tab text-xs sm:text-sm px-3 py-2 font-medium" onclick="openTab(event, 'tab-umum')">Aturan Umum</button>
                            <button class="tab-btn text-xs sm:text-sm px-3 py-2 font-medium" onclick="openTab(event, 'tab-kecerdasan')">Aturan Kecerdasan</button>
                            <button class="tab-btn text-xs sm:text-sm px-3 py-2 font-medium" onclick="openTab(event, 'tab-jurusan')">Aturan Jurusan</button>
                            <button class="tab-btn text-xs sm:text-sm px-3 py-2 font-medium" onclick="openTab(event, 'tab-penerapan')">Penerapan Aturan</button>
                        </div>
                    </div>
                    
                    <!-- Tab Aturan Umum -->
                    <div id="tab-umum" class="tab-content block">
                        <table class="min-w-full bg-white border border-gray-200 text-xs sm:text-sm">
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
                    
                    <!-- Tab Aturan Kecerdasan -->
                    <div id="tab-kecerdasan" class="tab-content hidden">
                        <div class="mb-3">
                            <select id="kecerdasanSelect" class="w-full sm:w-auto px-3 py-2 border rounded-md text-xs sm:text-sm" onchange="showKecerdasanRules()">
                                <option value="">Pilih Kecerdasan</option>
                                <?php foreach ($grouped_answers as $id_kecerdasan => $data): ?>
                                <option value="kecerdasan-<?php echo $id_kecerdasan; ?>"><?php echo htmlspecialchars($data['nama_kecerdasan']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <?php foreach ($grouped_answers as $id_kecerdasan => $data): ?>
                        <div id="kecerdasan-<?php echo $id_kecerdasan; ?>" class="kecerdasan-rules hidden">
                            <h6 class="font-semibold text-xs sm:text-sm mb-2"><?php echo htmlspecialchars($data['nama_kecerdasan']); ?></h6>
                            <table class="min-w-full bg-white border border-gray-200 text-xs sm:text-sm mb-4">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="py-2 px-3 border-b text-left">Pertanyaan</th>
                                        <th class="py-2 px-3 border-b text-left">Aturan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['pertanyaan'] as $index => $pertanyaan): ?>
                                    <tr>
                                        <td class="py-2 px-3 border-b"><?php echo htmlspecialchars($pertanyaan['isi_pertanyaan']); ?></td>
                                        <td class="py-2 px-3 border-b">
                                            IF jawaban = "<?php echo $pertanyaan['jawaban']; ?>" THEN 
                                            <?php if ($pertanyaan['jawaban'] == 'Ya'): ?>
                                                <span class="text-green-600">tambahkan 1 poin ke <?php echo htmlspecialchars($data['nama_kecerdasan']); ?></span>
                                            <?php else: ?>
                                                <span class="text-gray-500">tidak ada penambahan poin</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Tab Aturan Jurusan -->
                    <div id="tab-jurusan" class="tab-content hidden">
                        <table class="min-w-full bg-white border border-gray-200 text-xs sm:text-sm">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="py-2 px-3 border-b text-left">Kecerdasan Dominan</th>
                                    <th class="py-2 px-3 border-b text-left">Jurusan yang Direkomendasikan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="<?php echo ($kecerdasan_dominan['kode_kecerdasan'] == 'K1') ? 'bg-green-50' : ''; ?>">
                                    <td class="py-2 px-3 border-b">IF Kecerdasan Dominan = Linguistic-Verbal (K1)</td>
                                    <td class="py-2 px-3 border-b">THEN Jurusan = Bahasa (SMA) / Administrasi Perkantoran (SMK) / Pemasaran (SMK)</td>
                                </tr>
                                <tr class="<?php echo ($kecerdasan_dominan['kode_kecerdasan'] == 'K2') ? 'bg-green-50' : ''; ?>">
                                    <td class="py-2 px-3 border-b">IF Kecerdasan Dominan = Logika-Matematika (K2)</td>
                                    <td class="py-2 px-3 border-b">THEN Jurusan = IPA (SMA) / Akuntansi (SMK) / TKJ (SMK)</td>
                                </tr>
                                <tr class="<?php echo ($kecerdasan_dominan['kode_kecerdasan'] == 'K3') ? 'bg-green-50' : ''; ?>">
                                    <td class="py-2 px-3 border-b">IF Kecerdasan Dominan = Spasial-Visual (K3)</td>
                                    <td class="py-2 px-3 border-b">THEN Jurusan = DKV (SMK) / Multimedia (SMK) / Tata Busana (SMK)</td>
                                </tr>
                                <tr class="<?php echo ($kecerdasan_dominan['kode_kecerdasan'] == 'K4') ? 'bg-green-50' : ''; ?>">
                                    <td class="py-2 px-3 border-b">IF Kecerdasan Dominan = Kinetik (K4)</td>
                                    <td class="py-2 px-3 border-b">THEN Jurusan = Teknik Mesin (SMK) / Tata Boga (SMK) / Otomotif (SMK)</td>
                                </tr>
                                <tr class="<?php echo ($kecerdasan_dominan['kode_kecerdasan'] == 'K5') ? 'bg-green-50' : ''; ?>">
                                    <td class="py-2 px-3 border-b">IF Kecerdasan Dominan = Ritmik-Musik (K5)</td>
                                    <td class="py-2 px-3 border-b">THEN Jurusan = Multimedia (SMK) / Tata Rias (SMK) / Perhotelan (SMK)</td>
                                </tr>
                                <tr class="<?php echo ($kecerdasan_dominan['kode_kecerdasan'] == 'K6') ? 'bg-green-50' : ''; ?>">
                                    <td class="py-2 px-3 border-b">IF Kecerdasan Dominan = Interpersonal (K6)</td>
                                    <td class="py-2 px-3 border-b">THEN Jurusan = IPS (SMA) / Keperawatan (SMK) / Perhotelan (SMK)</td>
                                </tr>
                                <tr class="<?php echo ($kecerdasan_dominan['kode_kecerdasan'] == 'K7') ? 'bg-green-50' : ''; ?>">
                                    <td class="py-2 px-3 border-b">IF Kecerdasan Dominan = Intrapersonal (K7)</td>
                                    <td class="py-2 px-3 border-b">THEN Jurusan = IPA (SMA) / IPS (SMA) / Farmasi (SMK)</td>
                                </tr>
                                <tr class="<?php echo ($kecerdasan_dominan['kode_kecerdasan'] == 'K8') ? 'bg-green-50' : ''; ?>">
                                    <td class="py-2 px-3 border-b">IF Kecerdasan Dominan = Naturalis (K8)</td>
                                    <td class="py-2 px-3 border-b">THEN Jurusan = IPA (SMA) / Keperawatan (SMK) / Tata Boga (SMK)</td>
                                </tr>
                                <tr class="<?php echo ($kecerdasan_dominan['kode_kecerdasan'] == 'K9') ? 'bg-green-50' : ''; ?>">
                                    <td class="py-2 px-3 border-b">IF Kecerdasan Dominan = Eksistensial (K9)</td>
                                    <td class="py-2 px-3 border-b">THEN Jurusan = IPS (SMA) / Bahasa (SMA) / TKJ (SMK)</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Tab Penerapan Aturan -->
                    <div id="tab-penerapan" class="tab-content hidden">
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <h6 class="font-semibold text-xs sm:text-sm mb-2">Penerapan Aturan pada Konsultasi Ini:</h6>
                            <ol class="list-decimal list-inside text-xs sm:text-sm space-y-2">
                                <li>Berdasarkan jawaban yang diberikan, sistem menghitung skor untuk setiap kecerdasan:</li>
                                <ul class="list-disc list-inside ml-4 mt-1 mb-2 text-xs sm:text-sm">
                                    <?php foreach ($detail_perhitungan['skor_kecerdasan'] as $id_kecerdasan => $skor): ?>
                                        <?php 
                                            $nama_kecerdasan = '';
                                            foreach ($grouped_answers as $key => $val) {
                                                if ($key == $id_kecerdasan) {
                                                    $nama_kecerdasan = $val['nama_kecerdasan'];
                                                    break;
                                                }
                                            }
                                        ?>
                                        <li class="<?php echo ($id_kecerdasan == $detail_perhitungan['kecerdasan_tertinggi']['id_kecerdasan']) ? 'text-green-600 font-medium' : ''; ?>">
                                            <?php echo htmlspecialchars($nama_kecerdasan); ?>: <?php echo $skor; ?> poin
                                            <?php if ($id_kecerdasan == $detail_perhitungan['kecerdasan_tertinggi']['id_kecerdasan']): ?>
                                                <span class="text-green-600 font-medium">(Tertinggi)</span>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <li>Karena <?php echo htmlspecialchars($kecerdasan_dominan['nama_kecerdasan']); ?> memiliki skor tertinggi (<?php echo $detail_perhitungan['skor_tertinggi']; ?> poin), maka ini menjadi kecerdasan dominan.</li>
                                <li>Berdasarkan aturan forward chaining, jika kecerdasan dominan adalah <?php echo htmlspecialchars($kecerdasan_dominan['nama_kecerdasan']); ?>, maka jurusan yang direkomendasikan adalah:</li>
                                <ul class="list-disc list-inside ml-4 mt-1 mb-2 text-xs sm:text-sm">
                                    <?php foreach ($detail_perhitungan['jurusan_terkait'] as $index => $jurusan): ?>
                                        <li class="<?php echo ($jurusan['id_jurusan'] == $hasil['id_jurusan']) ? 'text-green-600 font-medium' : ''; ?>">
                                            <?php echo htmlspecialchars($jurusan['nama_jurusan']); ?> (<?php echo htmlspecialchars($jurusan['jenis_sekolah']); ?>)
                                            <?php if ($jurusan['id_jurusan'] == $hasil['id_jurusan']): ?>
                                                <span class="text-green-600 font-medium">(Dipilih)</span>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <li>Jurusan <?php echo htmlspecialchars($hasil['nama_jurusan']); ?> (<?php echo htmlspecialchars($hasil['jenis_sekolah']); ?>) dipilih sebagai rekomendasi utama karena <?php echo ($hasil['jenis_sekolah'] == 'SMA') ? 'memberikan jalur akademik yang sesuai dengan kecerdasan dominan' : 'memberikan keterampilan praktis yang sesuai dengan kecerdasan dominan'; ?>.</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- JavaScript untuk Tab -->
            <script>
                function openTab(evt, tabName) {
                    // Sembunyikan semua konten tab
                    var tabContents = document.getElementsByClassName("tab-content");
                    for (var i = 0; i < tabContents.length; i++) {
                        tabContents[i].classList.add("hidden");
                        tabContents[i].classList.remove("block");
                    }
                    
                    // Hapus kelas active dari semua tombol tab
                    var tabButtons = document.getElementsByClassName("tab-btn");
                    for (var i = 0; i < tabButtons.length; i++) {
                        tabButtons[i].classList.remove("active-tab");
                    }
                    
                    // Tampilkan tab yang dipilih dan tandai tombol sebagai aktif
                    document.getElementById(tabName).classList.remove("hidden");
                    document.getElementById(tabName).classList.add("block");
                    evt.currentTarget.classList.add("active-tab");
                }
                
                function showKecerdasanRules() {
                    // Sembunyikan semua aturan kecerdasan
                    var kecerdasanRules = document.getElementsByClassName("kecerdasan-rules");
                    for (var i = 0; i < kecerdasanRules.length; i++) {
                        kecerdasanRules[i].classList.add("hidden");
                    }
                    
                    // Tampilkan aturan kecerdasan yang dipilih
                    var selectedKecerdasan = document.getElementById("kecerdasanSelect").value;
                    if (selectedKecerdasan) {
                        document.getElementById(selectedKecerdasan).classList.remove("hidden");
                    }
                }
            </script>
            
            <!-- CSS untuk Tab -->
            <style>
                .active-tab {
                    color: #4F46E5;
                    border-bottom: 2px solid #4F46E5;
                }
                
                /* Responsif untuk Mobile */
                @media (max-width: 640px) {
                    .tab-btn {
                        font-size: 0.75rem;
                        padding: 0.5rem 0.75rem;
                    }
                    
                    table {
                        font-size: 0.7rem;
                    }
                    
                    th, td {
                        padding: 0.5rem 0.5rem;
                    }
                }
            </style>

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
