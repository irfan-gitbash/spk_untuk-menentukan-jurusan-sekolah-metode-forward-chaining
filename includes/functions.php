<?php
require_once __DIR__ . '/../config/database.php';

// Fungsi untuk membersihkan input - perbaikan
function clean($data) {
    global $conn;
    $data = trim($data);
    $data = htmlspecialchars($data);
    if ($conn) {
        $data = mysqli_real_escape_string($conn, $data);
    }
    return $data;
}

// Fungsi untuk mendapatkan semua jurusan
function getAllJurusan() {
    global $conn;
    $query = "SELECT * FROM jurusan ORDER BY jenis_sekolah, nama_jurusan";
    $result = mysqli_query($conn, $query);
    $jurusan = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $jurusan[] = $row;
    }
    
    return $jurusan;
}

// Fungsi untuk mendapatkan jurusan berdasarkan ID
function getJurusanById($id) {
    global $conn;
    $id = (int) $id;
    $query = "SELECT * FROM jurusan WHERE id_jurusan = $id";
    $result = mysqli_query($conn, $query);
    
    return mysqli_fetch_assoc($result);
}

// Fungsi untuk mendapatkan jurusan berdasarkan kode
function getJurusanByKode($kode) {
    global $conn;
    $kode = clean($kode);
    $query = "SELECT * FROM jurusan WHERE kode_jurusan = '$kode'";
    $result = mysqli_query($conn, $query);
    
    return mysqli_fetch_assoc($result);
}

// Fungsi untuk mendapatkan semua kecerdasan
function getAllKecerdasan() {
    global $conn;
    $query = "SELECT * FROM kecerdasan ORDER BY kode_kecerdasan";
    $result = mysqli_query($conn, $query);
    $kecerdasan = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $kecerdasan[] = $row;
    }
    
    return $kecerdasan;
}

// Fungsi untuk mendapatkan kecerdasan berdasarkan ID
function getKecerdasanById($id) {
    global $conn;
    $id = (int) $id;
    $query = "SELECT * FROM kecerdasan WHERE id_kecerdasan = $id";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return null;
}

// Fungsi untuk mendapatkan jurusan berdasarkan kecerdasan
function getJurusanByKecerdasan($id_kecerdasan) {
    global $conn;
    $id_kecerdasan = (int) $id_kecerdasan;
    $query = "SELECT j.* FROM jurusan j 
              JOIN kecerdasan_jurusan kj ON j.id_jurusan = kj.id_jurusan 
              WHERE kj.id_kecerdasan = $id_kecerdasan 
              ORDER BY j.jenis_sekolah, j.nama_jurusan";
    $result = mysqli_query($conn, $query);
    $jurusan = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $jurusan[] = $row;
    }
    
    return $jurusan;
}

// Fungsi untuk mendapatkan semua pertanyaan
function getAllPertanyaan() {
    global $conn;
    $query = "SELECT p.*, k.kode_kecerdasan, k.nama_kecerdasan 
              FROM pertanyaan p 
              JOIN kecerdasan k ON p.id_kecerdasan = k.id_kecerdasan 
              ORDER BY p.kode_pertanyaan";
    $result = mysqli_query($conn, $query);
    $pertanyaan = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $pertanyaan[] = $row;
    }
    
    return $pertanyaan;
}

// Fungsi untuk mendapatkan pertanyaan berdasarkan kecerdasan
function getPertanyaanByKecerdasan($id_kecerdasan) {
    global $conn;
    $id_kecerdasan = (int) $id_kecerdasan;
    $query = "SELECT * FROM pertanyaan WHERE id_kecerdasan = $id_kecerdasan ORDER BY kode_pertanyaan";
    $result = mysqli_query($conn, $query);
    $pertanyaan = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $pertanyaan[] = $row;
    }
    
    return $pertanyaan;
}

// Fungsi untuk mendapatkan pertanyaan berdasarkan ID
function getPertanyaanById($id) {
    global $conn;
    $id = (int) $id;
    $query = "SELECT p.*, k.kode_kecerdasan, k.nama_kecerdasan 
              FROM pertanyaan p 
              JOIN kecerdasan k ON p.id_kecerdasan = k.id_kecerdasan 
              WHERE p.id_pertanyaan = $id";
    $result = mysqli_query($conn, $query);
    
    return mysqli_fetch_assoc($result);
}

// Fungsi untuk mendapatkan pertanyaan berdasarkan kode
function getPertanyaanByKode($kode) {
    global $conn;
    $kode = clean($kode);
    $query = "SELECT p.*, k.kode_kecerdasan, k.nama_kecerdasan 
              FROM pertanyaan p 
              JOIN kecerdasan k ON p.id_kecerdasan = k.id_kecerdasan 
              WHERE p.kode_pertanyaan = '$kode'";
    $result = mysqli_query($conn, $query);
    
    return mysqli_fetch_assoc($result);
}

// Fungsi untuk menyimpan data siswa
function saveSiswa($nama, $nisn, $kelas, $sekolah) {
    global $conn;
    $nama = clean($nama);
    $nisn = clean($nisn);
    $kelas = clean($kelas);
    $sekolah = clean($sekolah);
    
    $query = "INSERT INTO siswa (nama_lengkap, nisn, kelas, sekolah) VALUES ('$nama', '$nisn', '$kelas', '$sekolah')";
    mysqli_query($conn, $query);
    
    return mysqli_insert_id($conn);
}

// Fungsi untuk menyimpan jawaban siswa
function saveJawaban($id_siswa, $id_pertanyaan, $jawaban) {
    global $conn;
    $id_siswa = (int) $id_siswa;
    $id_pertanyaan = (int) $id_pertanyaan;
    $jawaban = clean($jawaban);
    
    $query = "INSERT INTO jawaban_siswa (id_siswa, id_pertanyaan, jawaban) VALUES ($id_siswa, $id_pertanyaan, '$jawaban')";
    return mysqli_query($conn, $query);
}

// Fungsi untuk menyimpan hasil konsultasi
function saveHasil($id_siswa, $id_jurusan, $id_kecerdasan, $skor) {
    global $conn;
    $id_siswa = (int) $id_siswa;
    $id_jurusan = (int) $id_jurusan;
    $id_kecerdasan = (int) $id_kecerdasan;
    $skor = (int) $skor;
    
    $query = "INSERT INTO hasil_konsultasi (id_siswa, id_jurusan, id_kecerdasan, skor) VALUES ($id_siswa, $id_jurusan, $id_kecerdasan, $skor)";
    return mysqli_query($conn, $query);
}

// Fungsi untuk mendapatkan jawaban siswa yang sudah diberikan
function getJawabanSiswa($id_siswa) {
    global $conn;
    $id_siswa = (int) $id_siswa;
    $query = "SELECT js.*, p.isi_pertanyaan, p.id_kecerdasan FROM jawaban_siswa js JOIN pertanyaan p ON js.id_pertanyaan = p.id_pertanyaan WHERE js.id_siswa = $id_siswa ORDER BY js.id_jawaban ASC";
    $result = mysqli_query($conn, $query);
    $jawaban = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $jawaban[] = $row;
    }
    return $jawaban;
}

// Fungsi untuk melakukan forward chaining dan menentukan hasil
function forwardChaining($id_siswa) {
    global $conn;

    // 1. Ambil semua jawaban siswa untuk konsultasi ini
    $jawaban_siswa = getJawabanSiswa($id_siswa);

    // 2. Inisialisasi skor kecerdasan
    $skor_kecerdasan = [];
    $all_kecerdasan = getAllKecerdasan(); // Asumsi fungsi ini ada dan mengembalikan semua kecerdasan
    foreach ($all_kecerdasan as $kecerdasan) {
        $skor_kecerdasan[$kecerdasan['id_kecerdasan']] = 0;
    }

    // 3. Proses setiap jawaban
    foreach ($jawaban_siswa as $jawaban) {
        if ($jawaban['jawaban'] == 'Ya') {
            // Jika jawaban 'Ya', tambahkan skor ke kecerdasan terkait
            $id_kecerdasan = $jawaban['id_kecerdasan'];
            if (isset($skor_kecerdasan[$id_kecerdasan])) {
                $skor_kecerdasan[$id_kecerdasan]++;
            }
        }
    }

    // 4. Tentukan kecerdasan dengan skor tertinggi
    $id_kecerdasan_tertinggi = null;
    $skor_tertinggi = -1;

    foreach ($skor_kecerdasan as $id_kecerdasan => $skor) {
        if ($skor > $skor_tertinggi) {
            $skor_tertinggi = $skor;
            $id_kecerdasan_tertinggi = $id_kecerdasan;
        }
    }

    // 5. Jika ada kecerdasan tertinggi, tentukan jurusan yang relevan
    $id_jurusan_rekomendasi = null;
    if ($id_kecerdasan_tertinggi !== null) {
        // Asumsi ada fungsi getJurusanByKecerdasan yang mengembalikan jurusan terkait
        $jurusan_terkait = getJurusanByKecerdasan($id_kecerdasan_tertinggi);
        if (!empty($jurusan_terkait)) {
            // Ambil jurusan pertama sebagai rekomendasi (bisa disesuaikan)
            $id_jurusan_rekomendasi = $jurusan_terkait[0]['id_jurusan'];
        }
    }

    // 6. Simpan hasil konsultasi
    saveHasil($id_siswa, $id_jurusan_rekomendasi, $id_kecerdasan_tertinggi, $skor_tertinggi);

    // Mengembalikan hasil yang relevan (misalnya, ID jurusan yang direkomendasikan)
    return [
        'id_jurusan' => $id_jurusan_rekomendasi,
        'id_kecerdasan' => $id_kecerdasan_tertinggi,
        'skor' => $skor_tertinggi
    ];
}

// Fungsi untuk mendapatkan kecerdasan dominan dari hasil konsultasi
function getKecerdasanDominan($id_siswa) {
    global $conn;
    $id_siswa = (int) $id_siswa;
    $query = "SELECT id_kecerdasan FROM hasil_konsultasi WHERE id_siswa = $id_siswa ORDER BY skor DESC LIMIT 1";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['id_kecerdasan'];
    }
    return null; // Atau nilai default lainnya jika tidak ada hasil
}

// Fungsi untuk menghitung skor kecerdasan (jika belum ada)
function hitungSkorKecerdasan($id_siswa) {
    global $conn;
    $id_siswa = (int) $id_siswa;

    // Ambil semua jawaban siswa
    $jawaban_siswa = getJawabanSiswa($id_siswa);

    // Inisialisasi skor untuk setiap kecerdasan
    $skor_kecerdasan = [];
    $all_kecerdasan = getAllKecerdasan();
    foreach ($all_kecerdasan as $kecerdasan) {
        $skor_kecerdasan[$kecerdasan['id_kecerdasan']] = 0;
    }

    // Hitung skor berdasarkan jawaban 'Ya'
    foreach ($jawaban_siswa as $jawaban) {
        if ($jawaban['jawaban'] == 'Ya') {
            $skor_kecerdasan[$jawaban['id_kecerdasan']]++;
        }
    }

    // Simpan atau perbarui skor di tabel hasil_konsultasi
    // Ini adalah contoh sederhana, Anda mungkin perlu logika yang lebih kompleks
    // untuk menyimpan skor per kecerdasan atau hanya kecerdasan dominan.
    // Untuk tujuan ini, kita akan mengembalikan array skor.
    return $skor_kecerdasan;
}

// Fungsi untuk mendapatkan rekomendasi jurusan berdasarkan kecerdasan tertinggi
function getRekomendasiJurusan($id_siswa) {
    $skor_kecerdasan = hitungSkorKecerdasan($id_siswa);
    
    // Ambil kecerdasan dengan skor tertinggi
    $kecerdasan_tertinggi = $skor_kecerdasan[0];
    
    // Dapatkan jurusan berdasarkan kecerdasan tertinggi
    $jurusan = getJurusanByKecerdasan($kecerdasan_tertinggi['id_kecerdasan']);
    
    // Simpan hasil konsultasi
    if (!empty($jurusan)) {
        saveHasil($id_siswa, $jurusan[0]['id_jurusan'], $kecerdasan_tertinggi['id_kecerdasan'], $kecerdasan_tertinggi['skor']);
        return [
            'jurusan' => $jurusan,
            'kecerdasan' => $kecerdasan_tertinggi,
            'skor_kecerdasan' => $skor_kecerdasan
        ];
    }
    
    return null;
}

// Fungsi untuk mendapatkan hasil konsultasi siswa
function getHasilKonsultasi($id_siswa) {
    global $conn;
    $id_siswa = (int) $id_siswa;
    $query = "SELECT h.*, j.*, s.*, k.kode_kecerdasan, k.nama_kecerdasan, k.deskripsi as deskripsi_kecerdasan 
              FROM hasil_konsultasi h 
              JOIN jurusan j ON h.id_jurusan = j.id_jurusan 
              JOIN siswa s ON h.id_siswa = s.id_siswa 
              JOIN kecerdasan k ON h.id_kecerdasan = k.id_kecerdasan 
              WHERE h.id_siswa = $id_siswa";
    $result = mysqli_query($conn, $query);
    
    return mysqli_fetch_assoc($result);
}

// Fungsi untuk mendapatkan semua hasil konsultasi
function getAllHasilKonsultasi($jenis_sekolah = '') {
    global $conn;
    $query = "SELECT h.*, j.*, s.*, k.kode_kecerdasan, k.nama_kecerdasan 
              FROM hasil_konsultasi h 
              JOIN jurusan j ON h.id_jurusan = j.id_jurusan 
              JOIN siswa s ON h.id_siswa = s.id_siswa 
              JOIN kecerdasan k ON h.id_kecerdasan = k.id_kecerdasan";
    
    if ($jenis_sekolah && in_array($jenis_sekolah, ['SMA', 'SMK'])) {
        $jenis_sekolah = mysqli_real_escape_string($conn, $jenis_sekolah);
        $query .= " WHERE j.jenis_sekolah = '$jenis_sekolah'";
    }
    
    $query .= " ORDER BY h.tanggal DESC";
    $result = mysqli_query($conn, $query);
    $hasil = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $hasil[] = $row;
    }
    
    return $hasil;
}

// Fungsi untuk mendapatkan data siswa berdasarkan ID
function getSiswaById($id) {
    global $conn;
    $id = (int) $id;
    $query = "SELECT * FROM siswa WHERE id_siswa = $id";
    $result = mysqli_query($conn, $query);
    
    return mysqli_fetch_assoc($result);
}
function getAllCiriKecerdasan() {
    global $conn;
    $sql = "SELECT p.*, k.nama_kecerdasan FROM pertanyaan p 
            JOIN kecerdasan k ON p.id_kecerdasan = k.id_kecerdasan 
            ORDER BY p.id_pertanyaan ASC";
    $result = mysqli_query($conn, $sql);
    $ciri_kecerdasan = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Menyesuaikan nama kolom agar kompatibel dengan kode yang ada
            $row['id_ciri'] = $row['id_pertanyaan'];
            $row['isi_ciri'] = $row['isi_pertanyaan'];
            $ciri_kecerdasan[] = $row;
        }
    }
    return $ciri_kecerdasan;
}

// Fungsi untuk mendapatkan jawaban siswa yang dikelompokkan berdasarkan kecerdasan
function getGroupedAnswersByIntelligence($id_siswa) {
    global $conn;
    $id_siswa = (int) $id_siswa;
    $query = "SELECT k.id_kecerdasan, k.nama_kecerdasan, p.isi_pertanyaan, js.jawaban
              FROM jawaban_siswa js
              JOIN pertanyaan p ON js.id_pertanyaan = p.id_pertanyaan
              JOIN kecerdasan k ON p.id_kecerdasan = k.id_kecerdasan
              WHERE js.id_siswa = $id_siswa
              ORDER BY k.id_kecerdasan, p.id_pertanyaan";
    $result = mysqli_query($conn, $query);
    $grouped_answers = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $id_kecerdasan = $row['id_kecerdasan'];
        if (!isset($grouped_answers[$id_kecerdasan])) {
            $grouped_answers[$id_kecerdasan] = [
                'nama_kecerdasan' => $row['nama_kecerdasan'],
                'pertanyaan' => []
            ];
        }
        $grouped_answers[$id_kecerdasan]['pertanyaan'][] = [
            'isi_pertanyaan' => $row['isi_pertanyaan'],
            'jawaban' => $row['jawaban']
        ];
    }
    return $grouped_answers;
}

// Fungsi untuk mendapatkan detail perhitungan forward chaining untuk siswa
function getDetailedForwardChainingCalculation($id_siswa) {
    global $conn;
    $id_siswa = (int) $id_siswa;

    // Ambil semua jawaban siswa
    $jawaban_siswa = getJawabanSiswa($id_siswa);

    // Inisialisasi skor untuk setiap kecerdasan
    $skor_kecerdasan = [];
    $all_kecerdasan = getAllKecerdasan();
    foreach ($all_kecerdasan as $kecerdasan) {
        $skor_kecerdasan[$kecerdasan['id_kecerdasan']] = 0;
    }

    // Hitung skor berdasarkan jawaban 'Ya'
    foreach ($jawaban_siswa as $jawaban) {
        if ($jawaban['jawaban'] == 'Ya') {
            $skor_kecerdasan[$jawaban['id_kecerdasan']]++;
        }
    }

    // Cari kecerdasan dengan skor tertinggi
    $id_kecerdasan_tertinggi = null;
    $skor_tertinggi = -1;
    foreach ($skor_kecerdasan as $id_kecerdasan => $skor) {
        if ($skor > $skor_tertinggi) {
            $skor_tertinggi = $skor;
            $id_kecerdasan_tertinggi = $id_kecerdasan;
        }
    }

    // Ambil nama kecerdasan tertinggi
    $kecerdasan_tertinggi = getKecerdasanById($id_kecerdasan_tertinggi);

    // Ambil jurusan terkait kecerdasan tertinggi
    $jurusan_terkait = getJurusanByKecerdasan($id_kecerdasan_tertinggi);

    return [
        'skor_kecerdasan' => $skor_kecerdasan,
        'kecerdasan_tertinggi' => $kecerdasan_tertinggi,
        'skor_tertinggi' => $skor_tertinggi,
        'jurusan_terkait' => $jurusan_terkait
    ];
}

?>
