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

-- Tabel kecerdasan
CREATE TABLE kecerdasan (
    id_kecerdasan INT AUTO_INCREMENT PRIMARY KEY,
    kode_kecerdasan VARCHAR(10) NOT NULL,
    nama_kecerdasan VARCHAR(100) NOT NULL,
    deskripsi TEXT
);

-- Tabel relasi kecerdasan dan jurusan
CREATE TABLE kecerdasan_jurusan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_kecerdasan INT NOT NULL,
    id_jurusan INT NOT NULL,
    FOREIGN KEY (id_kecerdasan) REFERENCES kecerdasan(id_kecerdasan),
    FOREIGN KEY (id_jurusan) REFERENCES jurusan(id_jurusan)
);

-- Tabel pertanyaan (ciri kecerdasan)
CREATE TABLE pertanyaan (
    id_pertanyaan INT AUTO_INCREMENT PRIMARY KEY,
    kode_pertanyaan VARCHAR(10) NOT NULL,
    isi_pertanyaan TEXT NOT NULL,
    id_kecerdasan INT NOT NULL,
    FOREIGN KEY (id_kecerdasan) REFERENCES kecerdasan(id_kecerdasan)
);

-- Tabel hasil konsultasi
CREATE TABLE hasil_konsultasi (
    id_hasil INT AUTO_INCREMENT PRIMARY KEY,
    id_siswa INT NOT NULL,
    id_jurusan INT NOT NULL,
    id_kecerdasan INT NOT NULL,
    skor INT NOT NULL,
    tanggal DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_siswa) REFERENCES siswa(id_siswa),
    FOREIGN KEY (id_jurusan) REFERENCES jurusan(id_jurusan),
    FOREIGN KEY (id_kecerdasan) REFERENCES kecerdasan(id_kecerdasan)
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

-- Masukkan data kecerdasan
INSERT INTO kecerdasan (kode_kecerdasan, nama_kecerdasan, deskripsi) VALUES
('K1', 'Kecerdasan Linguistic-Verbal', 'Kemampuan menggunakan kata-kata secara efektif, baik lisan maupun tulisan'),
('K2', 'Kecerdasan Logika-Matematika', 'Kemampuan menggunakan angka dengan baik dan melakukan penalaran yang benar'),
('K3', 'Kecerdasan Spasial-Visual', 'Kemampuan mempersepsi dunia visual-spasial secara akurat dan melakukan transformasi persepsi tersebut'),
('K4', 'Kecerdasan Kinetik', 'Keahlian menggunakan seluruh tubuh untuk mengekspresikan ide dan perasaan'),
('K5', 'Kecerdasan Ritmik-Musik', 'Kemampuan mengenali dan menyusun pola nada dan ritme'),
('K6', 'Kecerdasan Interpersonal', 'Kemampuan memahami dan berinteraksi dengan orang lain secara efektif'),
('K7', 'Kecerdasan Intrapersonal', 'Kemampuan memahami diri sendiri dan bertindak berdasarkan pemahaman tersebut'),
('K8', 'Kecerdasan Naturalis', 'Keahlian mengenali dan mengkategorikan spesies flora dan fauna di lingkungan sekitar'),
('K9', 'Kecerdasan Eksistensial', 'Kemampuan untuk menempatkan diri dalam hubungannya dengan kosmos dan eksistensi manusia');

-- Masukkan data jurusan SMA
INSERT INTO jurusan (kode_jurusan, nama_jurusan, jenis_sekolah, deskripsi, prospek_karir) VALUES 
('IPA', 'Ilmu Pengetahuan Alam', 'SMA', 'Jurusan yang mempelajari ilmu-ilmu alam seperti Fisika, Kimia, dan Biologi', 'Dokter, Apoteker, Insinyur, Peneliti, dll'),
('IPS', 'Ilmu Pengetahuan Sosial', 'SMA', 'Jurusan yang mempelajari ilmu-ilmu sosial seperti Ekonomi, Sosiologi, dan Geografi', 'Ekonom, Akuntan, Pengusaha, Diplomat, dll'),
('BHS', 'Bahasa', 'SMA', 'Jurusan yang mempelajari bahasa dan sastra', 'Penerjemah, Jurnalis, Penulis, Diplomat, dll');

-- Masukkan data jurusan SMK
INSERT INTO jurusan (kode_jurusan, nama_jurusan, jenis_sekolah, deskripsi, prospek_karir) VALUES 
('DKV', 'Desain Komunikasi Visual', 'SMK', 'Jurusan yang mempelajari desain grafis dan komunikasi visual', 'Desainer Grafis, Ilustrator, UI/UX Designer, dll'),
('MM', 'Multimedia/Desain Grafis', 'SMK', 'Jurusan yang mempelajari desain grafis dan multimedia', 'Desainer Grafis, Animator, Editor Video, dll'),
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
('OTO', 'Teknik Otomotif', 'SMK', 'Jurusan yang mempelajari teknik otomotif', 'Mekanik, Teknisi Otomotif, dll'),
('PMS', 'Pemasaran', 'SMK', 'Jurusan yang mempelajari teknik pemasaran dan penjualan', 'Marketing, Sales, Brand Manager, dll');

-- Masukkan data relasi kecerdasan dan jurusan
-- K1: Kecerdasan Linguistic-Verbal
INSERT INTO kecerdasan_jurusan (id_kecerdasan, id_jurusan) 
SELECT k.id_kecerdasan, j.id_jurusan FROM kecerdasan k, jurusan j 
WHERE k.kode_kecerdasan = 'K1' AND j.kode_jurusan IN ('BHS', 'IPS', 'APK', 'HTL', 'PMS');

-- K2: Kecerdasan Logika-Matematika
INSERT INTO kecerdasan_jurusan (id_kecerdasan, id_jurusan) 
SELECT k.id_kecerdasan, j.id_jurusan FROM kecerdasan k, jurusan j 
WHERE k.kode_kecerdasan = 'K2' AND j.kode_jurusan IN ('IPA', 'AKT', 'FRM', 'TKJ', 'OTO');

-- K3: Kecerdasan Spasial-Visual
INSERT INTO kecerdasan_jurusan (id_kecerdasan, id_jurusan) 
SELECT k.id_kecerdasan, j.id_jurusan FROM kecerdasan k, jurusan j 
WHERE k.kode_kecerdasan = 'K3' AND j.kode_jurusan IN ('DKV', 'MM', 'TRS', 'TBS');

-- K4: Kecerdasan Kinetik
INSERT INTO kecerdasan_jurusan (id_kecerdasan, id_jurusan) 
SELECT k.id_kecerdasan, j.id_jurusan FROM kecerdasan k, jurusan j 
WHERE k.kode_kecerdasan = 'K4' AND j.kode_jurusan IN ('TMN', 'TBG', 'PLY', 'OTO');

-- K5: Kecerdasan Ritmik-Musik
INSERT INTO kecerdasan_jurusan (id_kecerdasan, id_jurusan) 
SELECT k.id_kecerdasan, j.id_jurusan FROM kecerdasan k, jurusan j 
WHERE k.kode_kecerdasan = 'K5' AND j.kode_jurusan IN ('MM', 'TRS', 'HTL');

-- K6: Kecerdasan Interpersonal
INSERT INTO kecerdasan_jurusan (id_kecerdasan, id_jurusan) 
SELECT k.id_kecerdasan, j.id_jurusan FROM kecerdasan k, jurusan j 
WHERE k.kode_kecerdasan = 'K6' AND j.kode_jurusan IN ('KPR', 'APK', 'HTL', 'IPS', 'PMS');

-- K7: Kecerdasan Intrapersonal
INSERT INTO kecerdasan_jurusan (id_kecerdasan, id_jurusan) 
SELECT k.id_kecerdasan, j.id_jurusan FROM kecerdasan k, jurusan j 
WHERE k.kode_kecerdasan = 'K7' AND j.kode_jurusan IN ('KPR', 'FRM', 'IPA', 'IPS');

-- K8: Kecerdasan Naturalis
INSERT INTO kecerdasan_jurusan (id_kecerdasan, id_jurusan) 
SELECT k.id_kecerdasan, j.id_jurusan FROM kecerdasan k, jurusan j 
WHERE k.kode_kecerdasan = 'K8' AND j.kode_jurusan IN ('IPA', 'KPR', 'FRM', 'TBG');

-- K9: Kecerdasan Eksistensial
INSERT INTO kecerdasan_jurusan (id_kecerdasan, id_jurusan) 
SELECT k.id_kecerdasan, j.id_jurusan FROM kecerdasan k, jurusan j 
WHERE k.kode_kecerdasan = 'K9' AND j.kode_jurusan IN ('IPS', 'BHS', 'APK', 'TKJ', 'TRS');

-- Masukkan data pertanyaan (ciri kecerdasan)
INSERT INTO pertanyaan (kode_pertanyaan, isi_pertanyaan, id_kecerdasan) VALUES
('C1', 'Apakah anda menyukai berbicara dan bercerita?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K1')),
('C2', 'Apakah anda suka mengutarakan atau bertukar ide dengan orang lain ataupun berdebat?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K1')),
('C3', 'Apakah anda senang menulis karangan, atau segala hal berbentuk tulisan?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K1')),
('C4', 'Apakah anda menyukai pelajaran bahasa dari pada matematika atau ilmu alam?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K1')),
('C5', 'Apakah anda menyukai permainan olah kata, seperti berpantun, bermain teka-teki, puisi, dll?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K1')),
('C6', 'Apakah anda menyukai ilmu sains atau matematika?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K2')),
('C7', 'Apakah anda tertarik dengan permainan yang mengendalkan strategi dan mengasah cara berpikir, contohnya permainan catur, maze, dll?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K2')),
('C8', 'Apakah anda senang bermain komputer dan tertarik untuk mengetahui bagaimana komputer bekerja?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K2')),
('C9', 'Jika sesuatu rusak dan tidak berfungsi, apakah anda tertarik melihat bagian-bagiannya dan mencari tahu bagaimana cara kerjanya?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K2')),
('C10', 'Suka berpikir melalui masalah dengan hati-hati, mempertimbangkan segala konsekuensinya. Apakah anda sependapat dengan teori tersebut?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K1')),
('C11', 'Apakah anda menyukai pelajaran geografi daripada matematika?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K2')),
('C12', 'Ketika membaca majalah, lebih suka melihat gambar-gambarnya daripada membaca teksnya. Apakah anda sependapat dengan teori tersebut?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K3')),
('C13', 'Apakah anda memiliki ingatan yang tinggi pada gambar, grafik dan bagan?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K3')),
('C14', 'Apakah anda memiliki daya ingat yang tinggi pada tempat, jalan walaupun belum terlalu sering mengunjunginya?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K3')),
('C15', 'Apakah anda memiliki ingatan yang mudah saat mengenali wajah dibandingkan mengingat nama seseorang?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K3')),
('C16', 'Apakah anda menyukai pekerjaan yang melibatkan keterampilan tangan?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K4')),
('C17', 'Apakah anda suka banyak bergerak saat belajar?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K4')),
('C18', 'Apakah anda lebih menyukai beraktivitas di alam bebas atau di luar ruangan?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K4')),
('C19', 'Apakah anda lebih memilih praktek langsung saat belajar sesuatu?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K4')),
('C20', 'Apakah anda tidak dapat duduk diam dalam waktu yang lama?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K4')),
('C21', 'Apakah anda memiliki hobi bernyanyi atau mendengarkan musik?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K5')),
('C22', 'Apakah anda suka mendengarkan musik sambil belajar atau sambil membaca buku?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K5')),
('C23', 'Apakah anda bisa memainkan salah satu alat musik dengan baik?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K5')),
('C24', 'Apakah anda menikmati berbagai macam gaya musik?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K5')),
('C25', 'Apabila mendengarkan suatu karya musik satu atau dua kali, dapat menyanyikannya kembali dengan cukup baik. Apakah anda sependapat dengan teori tersebut?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K5')),
('C26', 'Apakah anda suka untuk mengajar orang lain tentang hal-hal yang dipelajari?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K6')),
('C27', 'Apakah anda tidak segan menawarkan atau memberikan bantuan saat orang lain?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K6')),
('C28', 'Apakah anda mudah bergaul dan bersosialisasi dengan orang sekitar?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K6')),
('C29', 'Senang terlibat dalam kegiatan sosial yang berkaitan dengan organisasi sekolah atau lingkungan tempat tinggal. Apakah anda sependapat dengan teori tersebut?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K6')),
('C30', 'Apakah orang-orang sering menunjuk anda sebagai pemimpin?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K6')),
('C31', 'Apakah anda lebih suka mengerjakan sesuatu sendirian tanpa ada gangguan orang lain?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K7')),
('C32', 'Apakah anda selalu mempersiapkan rencana masa depan serta tujuan yang mau dicapai?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K7')),
('C33', 'Apakah anda lebih memilih menghabiskan waktu di dalam rumah dan sendirian?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K7')),
('C34', 'Apakah anda memiliki buku harian atau catatan pribadi untuk menuliskan kehidupan pribadi?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K7')),
('C35', 'Apakah anda mengetahui kelebihan dan kekurangan diri sendiri?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K7')),
('C36', 'Apakah anda memiliki minat cukup besar pada alam, ekologi, tanaman atau binatang?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K8')),
('C37', 'Apakah anda suka berkelana, hiking, atau sekedar jalan-jalan di alam terbuka?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K8')),
('C38', 'Apakah anda senang mempelajari ilmu pengetahuan alam?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K8')),
('C39', 'Ketika dewasa, ingin pergi dari kota yang ramai ke tempat yang masih alamiah untuk menikmati alam. Apakah anda sependapat dengan teori tersebut?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K8')),
('C40', 'Apakah anda menyukai tamasya ke kebun binatang, taman, laut, akuarium, dll?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K8')),
('C41', 'Apakah anda menyukai pembahasan tentang kehidupan?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K9')),
('C42', 'Apakah anda sering memikirkan tentang tujuan hidup?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K9')),
('C43', 'Apakah anda tertarik dengan filosofi dan pemikiran mendalam?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K9')),
('C44', 'Ingin mengetahui apakah ada bentuk-bentuk lain dari kehidupan di alam
semesta. Apakah anda sependapat dengan teori tersebut?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K9')),
('C45', 'Apakah anda fasih dalam menjelaskan budaya dan sejarah?', (SELECT id_kecerdasan FROM kecerdasan WHERE kode_kecerdasan = 'K9'));