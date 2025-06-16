-- Tabel untuk Peran Pengguna (Roles)
CREATE TABLE `roles` (
    `role_id` INT AUTO_INCREMENT PRIMARY KEY,
    `role_name` VARCHAR(50) NOT NULL UNIQUE COMMENT 'Nama peran, contoh: Admin, User',
    `description` TEXT NULL COMMENT 'Deskripsi singkat peran'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel untuk Pengguna Sistem (Users)
CREATE TABLE `users` (
    `user_id` INT AUTO_INCREMENT PRIMARY KEY,
    `full_name` VARCHAR(255) NOT NULL COMMENT 'Nama lengkap pengguna',
    `username` VARCHAR(100) NOT NULL UNIQUE COMMENT 'Username untuk login',
    `password_hash` VARCHAR(255) NOT NULL COMMENT 'Password yang sudah di-hash',
    `email` VARCHAR(255) NULL UNIQUE COMMENT 'Email pengguna, opsional tapi berguna untuk reset password',
    `role_id` INT NOT NULL COMMENT 'ID peran dari tabel roles',
    `account_status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active' COMMENT 'Status akun pengguna',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu pembuatan akun',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Waktu terakhir update akun',
    `password_last_changed` TIMESTAMP NULL COMMENT 'Waktu terakhir password diubah',
    FOREIGN KEY (`role_id`) REFERENCES `roles`(`role_id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel untuk Bidang Dokumen (Bidang)
CREATE TABLE `bidang` (
    `bidang_id` INT AUTO_INCREMENT PRIMARY KEY,
    `nama_bidang` VARCHAR(255) NOT NULL UNIQUE COMMENT 'Nama bidang, contoh: Perencanaan, Umum',
    `description` TEXT NULL COMMENT 'Deskripsi singkat bidang'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel untuk Dokumen Kearsipan (Dokumen)
CREATE TABLE `dokumen` (
    `dokumen_id` INT AUTO_INCREMENT PRIMARY KEY,
    `bidang_id` INT NOT NULL COMMENT 'ID bidang dari tabel bidang',
    `uploader_user_id` INT NOT NULL COMMENT 'ID pengguna yang mengunggah dari tabel users',
    `nomor_dokumen` VARCHAR(100) NULL COMMENT 'Nomor identifikasi dokumen',
    `nama_dokumen` VARCHAR(255) NOT NULL COMMENT 'Judul atau nama dokumen',
    `tahun_dokumen` YEAR NULL COMMENT 'Tahun terkait dokumen',
    `kategori_surat` ENUM('Surat Masuk', 'Surat Keluar') NOT NULL COMMENT 'Kategori surat berdasarkan alurnya',
    `jenis_file_extension` VARCHAR(10) NULL COMMENT 'Ekstensi file, contoh: PDF, DOCX',
    `klasifikasi_jenis_dokumen` VARCHAR(100) NULL COMMENT 'Klasifikasi jenis dokumen internal, contoh: Surat Undangan, Laporan',
    `file_path` VARCHAR(255) NOT NULL COMMENT 'Path penyimpanan file di server',
    `file_name_original` VARCHAR(255) NOT NULL COMMENT 'Nama asli file yang diunggah',
    `file_size_kb` INT NULL COMMENT 'Ukuran file dalam Kilobyte',
    `tanggal_upload` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu dokumen diunggah',
    `last_edited_user_id` INT NULL COMMENT 'ID pengguna terakhir yang mengedit metadata',
    `last_edited_at` TIMESTAMP NULL COMMENT 'Waktu terakhir metadata diedit',
    `deskripsi_tambahan` TEXT NULL COMMENT 'Deskripsi atau catatan tambahan untuk dokumen',
    FOREIGN KEY (`bidang_id`) REFERENCES `bidang`(`bidang_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (`uploader_user_id`) REFERENCES `users`(`user_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (`last_edited_user_id`) REFERENCES `users`(`user_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Afiliasi Pengguna ke Bidang (User Bidang Affiliation)
-- Opsional, digunakan jika pengguna dengan peran 'User' memiliki akses terbatas ke bidang tertentu
-- dan bisa terafiliasi dengan lebih dari satu bidang.
CREATE TABLE `user_bidang_affiliation` (
    `affiliation_id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL COMMENT 'ID pengguna dari tabel users',
    `bidang_id` INT NOT NULL COMMENT 'ID bidang dari tabel bidang',
    FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`bidang_id`) REFERENCES `bidang`(`bidang_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY `user_bidang_unique` (`user_id`, `bidang_id`) COMMENT 'Memastikan kombinasi user dan bidang unik'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Menambahkan beberapa data awal untuk tabel roles (opsional)
INSERT INTO `roles` (`role_name`, `description`) VALUES
('Admin', 'Pengguna dengan hak akses penuh ke sistem.'),
('User', 'Pengguna dengan hak akses terbatas sesuai atribusi.');

-- Menambahkan indeks untuk performa query (opsional, namun direkomendasikan)
ALTER TABLE `dokumen` ADD INDEX `idx_nama_dokumen` (`nama_dokumen`);
ALTER TABLE `dokumen` ADD INDEX `idx_tahun_dokumen` (`tahun_dokumen`);
ALTER TABLE `dokumen` ADD INDEX `idx_kategori_surat` (`kategori_surat`);