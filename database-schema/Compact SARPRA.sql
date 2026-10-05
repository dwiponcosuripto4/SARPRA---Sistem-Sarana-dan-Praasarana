CREATE TABLE `role` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(50) UNIQUE NOT NULL,
  `description` varchar(255),
  `created_at` timestamp,
  `updated_at` timestamp
);

CREATE TABLE `user` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `role_id` bigint NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) UNIQUE NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_active` boolean DEFAULT true,
  `last_login_at` timestamp,
  `created_at` timestamp,
  `updated_at` timestamp
);

CREATE TABLE `kategori_barang` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(100) UNIQUE NOT NULL,
  `description` varchar(255),
  `created_at` timestamp,
  `updated_at` timestamp
);

CREATE TABLE `satuan` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(50) UNIQUE NOT NULL,
  `description` varchar(255),
  `created_at` timestamp,
  `updated_at` timestamp
);

CREATE TABLE `lokasi` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(255),
  `created_at` timestamp,
  `updated_at` timestamp
);

CREATE TABLE `kondisi_barang` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(50) UNIQUE NOT NULL,
  `description` varchar(255),
  `created_at` timestamp,
  `updated_at` timestamp
);

CREATE TABLE `status_barang` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(50) UNIQUE NOT NULL,
  `description` varchar(255),
  `created_at` timestamp,
  `updated_at` timestamp
);

CREATE TABLE `jenis_pemeliharaan` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(100) UNIQUE NOT NULL,
  `description` varchar(255),
  `created_at` timestamp,
  `updated_at` timestamp
);

CREATE TABLE `jenis_pengadaan` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(100) UNIQUE NOT NULL,
  `description` varchar(255),
  `created_at` timestamp,
  `updated_at` timestamp
);

CREATE TABLE `prioritas` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(50) UNIQUE NOT NULL,
  `description` varchar(255),
  `created_at` timestamp,
  `updated_at` timestamp
);

CREATE TABLE `periode` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(50) UNIQUE NOT NULL,
  `description` varchar(255),
  `created_at` timestamp,
  `updated_at` timestamp
);

CREATE TABLE `perencanaan` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `kategori_barang_id` bigint NOT NULL,
  `satuan_id` bigint NOT NULL,
  `prioritas_id` bigint NOT NULL,
  `periode_id` bigint NOT NULL,
  `nama_kebutuhan` varchar(150) NOT NULL,
  `jumlah` int NOT NULL,
  `alasan` text,
  `status` varchar(50) NOT NULL,
  `created_at` timestamp,
  `updated_at` timestamp
);

CREATE TABLE `riwayat_perencanaan` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `perencanaan_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `status` varchar(50) NOT NULL,
  `catatan` text,
  `created_at` timestamp
);

CREATE TABLE `pengadaan` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `perencanaan_id` bigint,
  `jenis_pengadaan_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `nomor_pengadaan` varchar(100) UNIQUE,
  `tanggal_pengajuan` date NOT NULL,
  `alasan` text,
  `status` varchar(50) NOT NULL,
  `created_at` timestamp,
  `updated_at` timestamp
);

CREATE TABLE `detail_pengadaan` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `pengadaan_id` bigint NOT NULL,
  `kategori_barang_id` bigint NOT NULL,
  `satuan_id` bigint NOT NULL,
  `nama_barang` varchar(150) NOT NULL,
  `spesifikasi` text,
  `jumlah` int NOT NULL,
  `estimasi_harga` decimal(15,2),
  `subtotal` decimal(15,2),
  `created_at` timestamp,
  `updated_at` timestamp
);

CREATE TABLE `dokumen_pengadaan` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `pengadaan_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `nama_dokumen` varchar(150) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(50),
  `file_size` bigint,
  `created_at` timestamp
);

CREATE TABLE `persetujuan_pengadaan` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `pengadaan_id` bigint NOT NULL,
  `pimpinan_id` bigint NOT NULL,
  `status` varchar(50) NOT NULL,
  `catatan` text,
  `tanggal_persetujuan` timestamp,
  `created_at` timestamp,
  `updated_at` timestamp
);

CREATE TABLE `riwayat_pengadaan` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `pengadaan_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `status` varchar(50) NOT NULL,
  `catatan` text,
  `created_at` timestamp
);

CREATE TABLE `barang` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `kategori_barang_id` bigint NOT NULL,
  `satuan_id` bigint NOT NULL,
  `lokasi_id` bigint NOT NULL,
  `kondisi_barang_id` bigint NOT NULL,
  `status_barang_id` bigint NOT NULL,
  `pengadaan_id` bigint,
  `perencanaan_id` bigint,
  `kode_barang` varchar(100) UNIQUE NOT NULL,
  `nama_barang` varchar(150) NOT NULL,
  `merk` varchar(100),
  `spesifikasi` text,
  `jumlah` int NOT NULL,
  `tahun_perolehan` year,
  `sumber_perolehan` varchar(100),
  `harga_perolehan` decimal(15,2),
  `foto` varchar(255),
  `dokumen` varchar(255),
  `created_at` timestamp,
  `updated_at` timestamp
);

CREATE TABLE `mutasi_barang` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `barang_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `lokasi_asal_id` bigint,
  `lokasi_tujuan_id` bigint NOT NULL,
  `tanggal_mutasi` date NOT NULL,
  `alasan` text,
  `created_at` timestamp
);

CREATE TABLE `riwayat_barang` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `barang_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `kondisi_barang_id` bigint,
  `status_barang_id` bigint,
  `lokasi_id` bigint,
  `catatan` text,
  `created_at` timestamp
);

CREATE TABLE `pemeliharaan` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `barang_id` bigint NOT NULL,
  `jenis_pemeliharaan_id` bigint NOT NULL,
  `prioritas_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `nomor_pemeliharaan` varchar(100) UNIQUE,
  `tanggal_pengajuan` date NOT NULL,
  `masalah` text NOT NULL,
  `estimasi_biaya` decimal(15,2),
  `status` varchar(50) NOT NULL,
  `created_at` timestamp,
  `updated_at` timestamp
);

CREATE TABLE `dokumen_pemeliharaan` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `pemeliharaan_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `nama_dokumen` varchar(150) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(50),
  `file_size` bigint,
  `created_at` timestamp
);

CREATE TABLE `riwayat_pemeliharaan` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `pemeliharaan_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `tanggal` date NOT NULL,
  `jenis_tindakan` varchar(150),
  `masalah` text,
  `tindakan` text,
  `biaya` decimal(15,2),
  `pelaksana` varchar(150),
  `status` varchar(50) NOT NULL,
  `catatan` text,
  `dokumen_sebelum` varchar(255),
  `dokumen_sesudah` varchar(255),
  `created_at` timestamp
);

CREATE TABLE `notifikasi` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `title` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(50),
  `reference_type` varchar(100),
  `reference_id` bigint,
  `is_read` boolean DEFAULT false,
  `read_at` timestamp,
  `created_at` timestamp
);

CREATE TABLE `riwayat_aktivitas` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `user_id` bigint,
  `activity` varchar(150) NOT NULL,
  `description` text,
  `module` varchar(100),
  `reference_type` varchar(100),
  `reference_id` bigint,
  `ip_address` varchar(45),
  `user_agent` text,
  `created_at` timestamp
);

CREATE TABLE `konfigurasi_sistem` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `key` varchar(100) UNIQUE NOT NULL,
  `value` text,
  `description` varchar(255),
  `created_at` timestamp,
  `updated_at` timestamp
);

ALTER TABLE `user` ADD FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);

ALTER TABLE `perencanaan` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `perencanaan` ADD FOREIGN KEY (`kategori_barang_id`) REFERENCES `kategori_barang` (`id`);

ALTER TABLE `perencanaan` ADD FOREIGN KEY (`satuan_id`) REFERENCES `satuan` (`id`);

ALTER TABLE `perencanaan` ADD FOREIGN KEY (`prioritas_id`) REFERENCES `prioritas` (`id`);

ALTER TABLE `perencanaan` ADD FOREIGN KEY (`periode_id`) REFERENCES `periode` (`id`);

ALTER TABLE `riwayat_perencanaan` ADD FOREIGN KEY (`perencanaan_id`) REFERENCES `perencanaan` (`id`);

ALTER TABLE `riwayat_perencanaan` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `pengadaan` ADD FOREIGN KEY (`perencanaan_id`) REFERENCES `perencanaan` (`id`);

ALTER TABLE `pengadaan` ADD FOREIGN KEY (`jenis_pengadaan_id`) REFERENCES `jenis_pengadaan` (`id`);

ALTER TABLE `pengadaan` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `detail_pengadaan` ADD FOREIGN KEY (`pengadaan_id`) REFERENCES `pengadaan` (`id`);

ALTER TABLE `detail_pengadaan` ADD FOREIGN KEY (`kategori_barang_id`) REFERENCES `kategori_barang` (`id`);

ALTER TABLE `detail_pengadaan` ADD FOREIGN KEY (`satuan_id`) REFERENCES `satuan` (`id`);

ALTER TABLE `dokumen_pengadaan` ADD FOREIGN KEY (`pengadaan_id`) REFERENCES `pengadaan` (`id`);

ALTER TABLE `dokumen_pengadaan` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `persetujuan_pengadaan` ADD FOREIGN KEY (`pengadaan_id`) REFERENCES `pengadaan` (`id`);

ALTER TABLE `persetujuan_pengadaan` ADD FOREIGN KEY (`pimpinan_id`) REFERENCES `user` (`id`);

ALTER TABLE `riwayat_pengadaan` ADD FOREIGN KEY (`pengadaan_id`) REFERENCES `pengadaan` (`id`);

ALTER TABLE `riwayat_pengadaan` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `barang` ADD FOREIGN KEY (`kategori_barang_id`) REFERENCES `kategori_barang` (`id`);

ALTER TABLE `barang` ADD FOREIGN KEY (`satuan_id`) REFERENCES `satuan` (`id`);

ALTER TABLE `barang` ADD FOREIGN KEY (`lokasi_id`) REFERENCES `lokasi` (`id`);

ALTER TABLE `barang` ADD FOREIGN KEY (`kondisi_barang_id`) REFERENCES `kondisi_barang` (`id`);

ALTER TABLE `barang` ADD FOREIGN KEY (`status_barang_id`) REFERENCES `status_barang` (`id`);

ALTER TABLE `barang` ADD FOREIGN KEY (`pengadaan_id`) REFERENCES `pengadaan` (`id`);

ALTER TABLE `barang` ADD FOREIGN KEY (`perencanaan_id`) REFERENCES `perencanaan` (`id`);

ALTER TABLE `mutasi_barang` ADD FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`);

ALTER TABLE `mutasi_barang` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `mutasi_barang` ADD FOREIGN KEY (`lokasi_asal_id`) REFERENCES `lokasi` (`id`);

ALTER TABLE `mutasi_barang` ADD FOREIGN KEY (`lokasi_tujuan_id`) REFERENCES `lokasi` (`id`);

ALTER TABLE `riwayat_barang` ADD FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`);

ALTER TABLE `riwayat_barang` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `riwayat_barang` ADD FOREIGN KEY (`kondisi_barang_id`) REFERENCES `kondisi_barang` (`id`);

ALTER TABLE `riwayat_barang` ADD FOREIGN KEY (`status_barang_id`) REFERENCES `status_barang` (`id`);

ALTER TABLE `riwayat_barang` ADD FOREIGN KEY (`lokasi_id`) REFERENCES `lokasi` (`id`);

ALTER TABLE `pemeliharaan` ADD FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`);

ALTER TABLE `pemeliharaan` ADD FOREIGN KEY (`jenis_pemeliharaan_id`) REFERENCES `jenis_pemeliharaan` (`id`);

ALTER TABLE `pemeliharaan` ADD FOREIGN KEY (`prioritas_id`) REFERENCES `prioritas` (`id`);

ALTER TABLE `pemeliharaan` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `dokumen_pemeliharaan` ADD FOREIGN KEY (`pemeliharaan_id`) REFERENCES `pemeliharaan` (`id`);

ALTER TABLE `dokumen_pemeliharaan` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `riwayat_pemeliharaan` ADD FOREIGN KEY (`pemeliharaan_id`) REFERENCES `pemeliharaan` (`id`);

ALTER TABLE `riwayat_pemeliharaan` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `notifikasi` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `riwayat_aktivitas` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
