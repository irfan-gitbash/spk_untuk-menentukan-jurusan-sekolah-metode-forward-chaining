-- Buat database
CREATE DATABASE spk_sekolah;
USE spk_sekolah;

-- Tabel pengguna (admin)
CREATE TABLE admin (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL
);

-- Tabel siswa (untuk menyimpan data konsultasi)
CREATE TABLE siswa (
    id_siswa INT AUTO_INCREMENT PRIMARY KEY,
    nama_lengkap VARCHAR(100) NOT NULL,
    nisn VARCHAR(20) NOT NULL,
    kelas VARCHAR(20) NOT NULL,
    sekolah VARCHAR(100) NOT NULL,
    tanggal_konsultasi DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabel jurusan
CREATE TABLE jurusan (
    id_jurusan INT AUTO_INCREMENT PRIMARY KEY,
    kode_jurusan VARCHAR(10) NOT NULL,
    nama_jurusan VARCHAR(100) NOT NULL,
    jenis_sekolah ENUM('SMA', 'SMK') NOT NULL,
    deskripsi TEXT,
    prospek_karir TEXT
);

-- Tabel pertanyaan
CREATE TABLE pertanyaan (
    id_pertanyaan INT AUTO_INCREMENT PRIMARY KEY,
    kode_pertanyaan VARCHAR(10) NOT NULL,
    isi_pertanyaan TEXT NOT NULL
);

-- Tabel aturan (rules) untuk forward chaining
CREATE TABLE aturan (
    id_aturan INT AUTO_INCREMENT PRIMARY KEY,
    kode_aturan VARCHAR(10) NOT NULL,
    jika TEXT NOT NULL,
    maka VARCHAR(10) NOT NULL
);

-- Tabel hasil konsultasi
CREATE TABLE hasil_konsultasi (
    id_hasil INT AUTO_INCREMENT PRIMARY KEY,
    id_siswa INT NOT NULL,
    id_jurusan INT NOT NULL,
    tanggal DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_siswa) REFERENCES siswa(id_siswa),
    FOREIGN KEY (id_jurusan) REFERENCES jurusan(id_jurusan)
);

-- Tabel jawaban siswa
CREATE TABLE jawaban_siswa (
    id_jawaban INT AUTO_INCREMENT PRIMARY KEY,
    id_siswa INT NOT NULL,
    id_pertanyaan INT NOT NULL,
    jawaban ENUM('Ya', 'Tidak') NOT NULL,
    FOREIGN KEY (id_siswa) REFERENCES siswa(id_siswa),
    FOREIGN KEY (id_pertanyaan) REFERENCES pertanyaan(id_pertanyaan)
);

-- Masukkan data admin default
INSERT INTO admin (username, password, nama_lengkap) VALUES ('admin', '$2y$10$qJxjHnL5Jmj7QKQF9Oin8.ZI3BkzGb4Ksz0CHCEtRXnXLOVZW1Wv.', 'Administrator');

-- Masukkan data jurusan SMA
INSERT INTO jurusan (kode_jurusan, nama_jurusan, jenis_sekolah, deskripsi, prospek_karir) VALUES 
('IPA', 'Ilmu Pengetahuan Alam', 'SMA', 'Jurusan yang mempelajari ilmu-ilmu alam seperti Fisika, Kimia, dan Biologi', 'Dokter, Apoteker, Insinyur, Peneliti, dll'),
('IPS', 'Ilmu Pengetahuan Sosial', 'SMA', 'Jurusan yang mempelajari ilmu-ilmu sosial seperti Ekonomi, Sosiologi, dan Geografi', 'Ekonom, Akuntan, Pengusaha, Diplomat, dll'),
('BHS', 'Bahasa', 'SMA', 'Jurusan yang mempelajari bahasa dan sastra', 'Penerjemah, Jurnalis, Penulis, Diplomat, dll');

-- Masukkan data jurusan SMK
INSERT INTO jurusan (kode_jurusan, nama_jurusan, jenis_sekolah, deskripsi, prospek_karir) VALUES 
('DKV', 'Desain Grafis/Multimedia', 'SMK', 'Jurusan yang mempelajari desain grafis dan multimedia', 'Desainer Grafis, Animator, Editor Video, dll'),
('KPR', 'Keperawatan', 'SMK', 'Jurusan yang mempelajari ilmu keperawatan', 'Perawat, Asisten Dokter, dll'),
('AKT', 'Akuntansi', 'SMK', 'Jurusan yang mempelajari ilmu akuntansi dan keuangan', 'Akuntan, Staff Keuangan, Auditor, dll'),
('APK', 'Administrasi Perkantoran', 'SMK', 'Jurusan yang mempelajari administrasi perkantoran', 'Sekretaris, Staff Administrasi, dll'),
('TBG', 'Tata Boga', 'SMK', 'Jurusan yang mempelajari kuliner dan tata boga', 'Chef, Pengusaha Kuliner, Food Stylist, dll'),
('TBS', 'Tata Busana', 'SMK', 'Jurusan yang mempelajari desain dan pembuatan busana', 'Fashion Designer, Penjahit, Pengusaha Busana, dll'),
('TRS', 'Tata Rias/Kecantikan', 'SMK', 'Jurusan yang mempelajari tata rias dan kecantikan', 'Make-up Artist, Beautician, Pengusaha Salon, dll'),
('FRM', 'Farmasi', 'SMK', 'Jurusan yang mempelajari ilmu farmasi', 'Asisten Apoteker, Staff Apotek, dll'),
('HTL', 'Perhotelan dan Pariwisata', 'SMK', 'Jurusan yang mempelajari perhotelan dan pariwisata', 'Staff Hotel, Tour Guide, Event Organizer, dll'),
('PLY', 'Pelayaran', 'SMK', 'Jurusan yang mempelajari ilmu pelayaran', 'Pelaut, Nahkoda, Teknisi Kapal, dll'),
('TMN', 'Teknik Mesin', 'SMK', 'Jurusan yang mempelajari teknik mesin', 'Teknisi Mesin, Operator Mesin, dll'),
('TKJ', 'Teknik Komputer dan Jaringan', 'SMK', 'Jurusan yang mempelajari teknik komputer dan jaringan', 'Teknisi Komputer, Network Administrator, Programmer, dll'),
('OTO', 'Teknik Otomotif', 'SMK', 'Jurusan yang mempelajari teknik otomotif', 'Mekanik, Teknisi Otomotif, dll');

-- Masukkan data pertanyaan
INSERT INTO pertanyaan (kode_pertanyaan, isi_pertanyaan) VALUES
('P01', 'Apakah berasal dari SMA?'),
('P02', 'Apakah Anda menyukai pelajaran Matematika?'),
('P03', 'Apakah Anda menyukai pelajaran Fisika?'),
('P04', 'Apakah Anda menyukai pelajaran Kimia?'),
('P05', 'Apakah Anda menyukai pelajaran Biologi?'),
('P06', 'Apakah Anda menyukai pelajaran Ekonomi?'),
('P07', 'Apakah Anda menyukai pelajaran Sosiologi?'),
('P08', 'Apakah Anda menyukai pelajaran Geografi?'),
('P09', 'Apakah Anda menyukai pelajaran Bahasa?'),
('P10', 'Apakah Anda menyukai pelajaran Sastra?'),
('P11', 'Apakah Anda menyukai menggambar atau desain?'),
('P12', 'Apakah Anda menyukai merawat orang sakit?'),
('P13', 'Apakah Anda menyukai menghitung keuangan?'),
('P14', 'Apakah Anda menyukai pekerjaan administrasi?'),
('P15', 'Apakah Anda menyukai memasak?'),
('P16', 'Apakah Anda menyukai mendesain pakaian?'),
('P17', 'Apakah Anda menyukai merias wajah?'),
('P18', 'Apakah Anda menyukai ilmu kesehatan?'),
('P19', 'Apakah Anda menyukai traveling atau pariwisata?'),
('P20', 'Apakah Anda menyukai kapal dan laut?'),
('P21', 'Apakah Anda menyukai mesin dan peralatan?'),
('P22', 'Apakah Anda menyukai komputer dan jaringan?'),
('P23', 'Apakah Anda menyukai otomotif dan kendaraan?');

-- Masukkan data aturan (rules) untuk forward chaining
INSERT INTO aturan (kode_aturan, jika, maka) VALUES
-- Aturan untuk SMA
('R01', 'P01=Ya AND P02=Ya AND P03=Ya AND P04=Ya', 'IPA'),
('R02', 'P01=Ya AND P02=Ya AND P05=Ya', 'IPA'),
('R03', 'P01=Ya AND P06=Ya AND P07=Ya', 'IPS'),
('R04', 'P01=Ya AND P06=Ya AND P08=Ya', 'IPS'),
('R05', 'P01=Ya AND P09=Ya AND P10=Ya', 'BHS'),

-- Aturan untuk SMK
('R06', 'P01=Tidak AND P11=Ya', 'DKV'),
('R07', 'P01=Tidak AND P12=Ya AND P18=Ya', 'KPR'),
('R08', 'P01=Tidak AND P13=Ya', 'AKT'),
('R09', 'P01=Tidak AND P14=Ya', 'APK'),
('R10', 'P01=Tidak AND P15=Ya', 'TBG'),
('R11', 'P01=Tidak AND P16=Ya', 'TBS'),
('R12', 'P01=Tidak AND P17=Ya', 'TRS'),
('R13', 'P01=Tidak AND P18=Ya', 'FRM'),
('R14', 'P01=Tidak AND P19=Ya', 'HTL'),
('R15', 'P01=Tidak AND P20=Ya', 'PLY'),
('R16', 'P01=Tidak AND P21=Ya', 'TMN'),
('R17', 'P01=Tidak AND P22=Ya', 'TKJ'),
('R18', 'P01=Tidak AND P23=Ya', 'OTO');