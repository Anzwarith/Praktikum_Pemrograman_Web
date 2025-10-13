CREATE DATABASE IF NOT EXISTS dbUnmulCare;
USE dbUnmulCare;

CREATE TABLE mahasiswa (
    id INT(11) PRIMARY KEY,
    nim VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    jurusan VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO mahasiswa (nim, nama, jurusan) VALUES
('2409106001', 'Ahmad Hazazel', 'Teknik Informatika'),
('2409106002', 'Muhammad Hazzel', 'Sistem Informasi'),
('2409106003', 'Budi Putra', 'Teknik Elektro'),
('2409106004', 'Giza Nur Siska', 'Manajemen'),
('2409106005', 'Rizki Arhan', 'Akuntansi'),
('2409106096', 'Putra Wahana', 'Teknik Informatika');