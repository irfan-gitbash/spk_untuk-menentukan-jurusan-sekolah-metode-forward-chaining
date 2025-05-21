<?php
require_once 'config/database.php';

// Fungsi untuk membersihkan input
function clean($data) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($data)));
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

// Fungsi untuk mendapatkan semua pertanyaan
function getAllPertanyaan() {
    global $conn;
    $query = "SELECT * FROM pertanyaan ORDER BY kode_pertanyaan";
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
    $query = "SELECT * FROM pertanyaan WHERE id_pertanyaan = $id";
    $result = mysqli_query($conn, $query);
    
    return mysqli_fetch_assoc($result);
}

// Fungsi untuk mendapatkan pertanyaan berdasarkan kode
function getPertanyaanByKode($kode) {
    global $conn;
    $kode = clean($kode);
    $query = "SELECT * FROM pertanyaan WHERE kode_pertanyaan = '$kode'";
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
function saveHasil($id_siswa, $id_jurusan) {
    global $conn;
    $id_siswa = (int) $id_siswa;
    $id_jurusan = (int) $id_jurusan;
    
    $query = "INSERT INTO hasil_konsultasi (id_siswa, id_jurusan) VALUES ($id_siswa, $id_jurusan)";
    return mysqli_query($conn, $query);
}

// Fungsi untuk mendapatkan jawaban siswa
function getJawabanSiswa($id_siswa) {
    global $conn;
    $id_siswa = (int) $id_siswa;
    $query = "SELECT js.*, p.kode_pertanyaan FROM jawaban_siswa js 
              JOIN pertanyaan p ON js.id_pertanyaan = p.id_pertanyaan 
              WHERE js.id_siswa = $id_siswa";
    $result = mysqli_query($conn, $query);
    $jawaban = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $jawaban[$row['kode_pertanyaan']] = $row['jawaban'];
    }
    
    return $jawaban;
}

// Fungsi untuk mendapatkan semua aturan
function getAllAturan() {
    global $conn;
    $query = "SELECT * FROM aturan ORDER BY kode_aturan";
    $result = mysqli_query($conn, $query);
    $aturan = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $aturan[] = $row;
    }
    
    return $aturan;
}

// Fungsi forward chaining untuk menentukan jurusan
function forwardChaining($id_siswa) {
    global $conn;
    $jawaban = getJawabanSiswa($id_siswa);
    $aturan = getAllAturan();
    
    foreach ($aturan as $rule) {
        $kondisi = $rule['jika'];
        $parts = explode(' AND ', $kondisi);
        $valid = true;
        
        foreach ($parts as $part) {
            list($kode, $nilai) = explode('=', $part);
            if (!isset($jawaban[$kode]) || $jawaban[$kode] != $nilai) {
                $valid = false;
                break;
            }
        }
        
        if ($valid) {
            $jurusan = getJurusanByKode($rule['maka']);
            saveHasil($id_siswa, $jurusan['id_jurusan']);
            return $jurusan;
        }
    }
    
    // Jika tidak ada aturan yang cocok, berikan rekomendasi default
    if (isset($jawaban['P01']) && $jawaban['P01'] == 'Ya') {
        // Default untuk SMA adalah IPA
        $jurusan = getJurusanByKode('IPA');
    } else {
        // Default untuk SMK adalah TKJ
        $jurusan = getJurusanByKode('TKJ');
    }
    
    saveHasil($id_siswa, $jurusan['id_jurusan']);
    return $jurusan;
}

// Fungsi untuk mendapatkan hasil konsultasi siswa
function getHasilKonsultasi($id_siswa) {
    global $conn;
    $id_siswa = (int) $id_siswa;
    $query = "SELECT h.*, j.*, s.* FROM hasil_konsultasi h 
              JOIN jurusan j ON h.id_jurusan = j.id_jurusan 
              JOIN siswa s ON h.id_siswa = s.id_siswa
              WHERE h.id_siswa = $id_siswa";
    $result = mysqli_query($conn, $query);
    
    return mysqli_fetch_assoc($result);
}

// Fungsi untuk mendapatkan semua hasil konsultasi
function getAllHasilKonsultasi($jenis_sekolah = '') {
    global $conn;
    $query = "SELECT h.*, j.*, s.* FROM hasil_konsultasi h 
              JOIN jurusan j ON h.id_jurusan = j.id_jurusan 
              JOIN siswa s ON h.id_siswa = s.id_siswa";
    
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
?>