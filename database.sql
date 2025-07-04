CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('mahasiswa','asisten') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE mata_praktikum (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    semester VARCHAR(20) NOT NULL
);

CREATE TABLE modul (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_praktikum INT,
    nama_modul VARCHAR(100),
    file_materi VARCHAR(255),
    FOREIGN KEY (id_praktikum) REFERENCES mata_praktikum(id) ON DELETE CASCADE
);

CREATE TABLE laporan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_modul INT,
    id_mahasiswa INT,
    file_laporan VARCHAR(255),
    nilai INT DEFAULT NULL,
    feedback TEXT DEFAULT NULL,
    tanggal_upload DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_modul) REFERENCES modul(id) ON DELETE CASCADE,
    FOREIGN KEY (id_mahasiswa) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE praktikum_mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_mahasiswa INT,
    id_praktikum INT,
    tanggal_daftar DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_mahasiswa) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (id_praktikum) REFERENCES mata_praktikum(id) ON DELETE CASCADE
);



