CREATE TABLE IF NOT EXISTS `pertanyaan` (
  `id_pertanyaan` int(11) NOT NULL AUTO_INCREMENT,
  `kode_pertanyaan` varchar(10) NOT NULL,
  `isi_pertanyaan` text NOT NULL,
  `id_kecerdasan` int(11) NOT NULL,
  PRIMARY KEY (`id_pertanyaan`),
  KEY `id_kecerdasan` (`id_kecerdasan`),
  CONSTRAINT `pertanyaan_ibfk_1` FOREIGN KEY (`id_kecerdasan`) REFERENCES `kecerdasan` (`id_kecerdasan`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `pertanyaan` (`kode_pertanyaan`, `isi_pertanyaan`, `id_kecerdasan`) VALUES
('P001', 'Apakah Anda suka menyelesaikan soal matematika?', 1),
('P002', 'Apakah Anda suka menulis cerita atau puisi?', 2),
('P003', 'Apakah Anda suka menggambar atau melukis?', 3);
