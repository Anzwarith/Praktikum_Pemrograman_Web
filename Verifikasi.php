<?php
session_start();
include 'koneksi.php';

// Redirect ke login jika belum login
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$message = '';

// Proses tambah/update data
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $id = $_POST['id'] ?? '';
    $nim = bersihkan_input($_POST['nim']);
    $nama = bersihkan_input($_POST['nama']);
    $jurusan = bersihkan_input($_POST['jurusan']);
    $action = $_POST['action'];
    
    if ($action == 'tambah') {
        // Cek apakah NIM sudah ada
        $cek_query = "SELECT id FROM mahasiswa WHERE nim = '$nim'";
        $cek_result = mysqli_query($koneksi, $cek_query);
        
        if (mysqli_num_rows($cek_result) > 0) {
            $message = 'error=NIM sudah terdaftar!';
        } else {
            // Insert data baru
            $query = "INSERT INTO mahasiswa (nim, nama, jurusan) VALUES ('$nim', '$nama', '$jurusan')";
            if (mysqli_query($koneksi, $query)) {
                $message = 'success=Data mahasiswa berhasil ditambahkan!';
            } else {
                $message = 'error=Gagal menambahkan data: ' . mysqli_error($koneksi);
            }
        }
    } 
    elseif ($action == 'update') {
        // Update data
        $query = "UPDATE mahasiswa SET nim='$nim', nama='$nama', jurusan='$jurusan' WHERE id='$id'";
        if (mysqli_query($koneksi, $query)) {
            $message = 'success=Data mahasiswa berhasil diupdate!';
        } else {
            $message = 'error=Gagal mengupdate data: ' . mysqli_error($koneksi);
        }
    }
}

// Proses hapus data
if (isset($_GET['action']) && $_GET['action'] == 'hapus' && isset($_GET['id'])) {
    $id = bersihkan_input($_GET['id']);
    
    $query = "DELETE FROM mahasiswa WHERE id = '$id'";
    if (mysqli_query($koneksi, $query)) {
        $message = 'success=Data mahasiswa berhasil dihapus!';
    } else {
        $message = 'error=Gagal menghapus data: ' . mysqli_error($koneksi);
    }
}

mysqli_close($koneksi);

// Redirect kembali ke dashboard dengan pesan
header('Location: index.php?' . $message);
exit();
?>