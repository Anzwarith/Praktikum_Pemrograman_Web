<?php
session_start();
include 'koneksi.php';

// Redirect ke login jika belum login
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Tangani pesan
$message = '';
if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
}

// Ambil data mahasiswa dari database
$query = "SELECT * FROM mahasiswa ORDER BY created_at DESC";
$result = mysqli_query($koneksi, $query);
$mahasiswa = [];

if ($result) {
    $mahasiswa = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $message = 'Error: ' . mysqli_error($koneksi);
}

// Data user dari session
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Dashboard Mahasiswa</title>

    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
</head>

<body>

    <header>
        <h1 id="headerTitle">Data Mahasiswa Unmul</h1>
        <div style="position: absolute; top: 50%; right: 150px; transform: translateY(-50%); color: white;">
            Selamat datang, <?php echo $username; ?>!
        </div>
        <button id="themeToggle" class="theme-toggle-btn">
            <span class="theme-icon"><i class="fas fa-moon"></i></span>
            <span class="theme-text">Dark Mode</span>
        </button>
    </header>

    <main>
        <?php if ($message): ?>
        <div class="notification success" style="margin: 20px auto; max-width: 600px; text-align: center; padding: 15px; background: #d4edda; color: #155724; border-radius: 8px;">
            <?php echo $message; ?>
        </div>
        <?php endif; ?>

        <div class="form-container">
            <h2>Manajemen Data Mahasiswa</h2>
            
            <form method="POST" action="proses_mahasiswa.php">
                <input type="hidden" name="id" id="mahasiswaId" value="">
                
                <div class="form-group">
                    <label for="nim">NIM:</label>
                    <input type="text" id="nim" name="nim" placeholder="Masukkan Nomor Induk Mahasiswa" required>
                </div>

                <div class="form-group">
                    <label for="nama">Nama Lengkap:</label>
                    <input type="text" id="nama" name="nama" placeholder="Masukkan Nama Lengkap" required>
                </div>

                <div class="form-group">
                    <label for="jurusan">Jurusan:</label>
                    <input type="text" id="jurusan" name="jurusan" placeholder="Contoh: Teknik Informatika" required>
                </div>

                <button type="submit" name="action" value="tambah" id="submitBtn" class="btn-primary">Tambah Data</button>
                <button type="button" onclick="resetForm()" class="btn-secondary" style="margin-top: 10px; width: 100%;">Reset Form</button>
            </form>
        </div>

        <div class="table-container">
            <h2>Daftar Mahasiswa</h2>
            
            <?php if (empty($mahasiswa)): ?>
                <div style="text-align: center; padding: 40px; color: #666;">
                    <p>Belum ada data mahasiswa</p>
                </div>
            <?php else: ?>
            <table id="mahasiswaTable">
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Jurusan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mahasiswa as $mhs): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($mhs['nim']); ?></td>
                        <td><?php echo htmlspecialchars($mhs['nama']); ?></td>
                        <td><?php echo htmlspecialchars($mhs['jurusan']); ?></td>
                        <td>
                            <button class="action-btn edit-btn" 
                                    onclick="editMahasiswa(<?php echo $mhs['id']; ?>, '<?php echo $mhs['nim']; ?>', '<?php echo $mhs['nama']; ?>', '<?php echo $mhs['jurusan']; ?>')"
                                    style="background-color: #28a745; color: white; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px;">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="action-btn delete-btn" 
                                    onclick="hapusMahasiswa(<?php echo $mhs['id']; ?>, '<?php echo $mhs['nama']; ?>')"
                                    style="background-color: #dc3545; color: white; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer;">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 - Kelas Web Programming</p>
        <p id="modeStatus">Mode Saat Ini: Light Mode</p>
        <p><a href="logout.php" style="color: #4a90e2; text-decoration: none;">Logout</a></p>
    </footer>

    <script>
        function editMahasiswa(id, nim, nama, jurusan) {
            document.getElementById('mahasiswaId').value = id;
            document.getElementById('nim').value = nim;
            document.getElementById('nama').value = nama;
            document.getElementById('jurusan').value = jurusan;
            document.getElementById('submitBtn').value = 'update';
            document.getElementById('submitBtn').innerHTML = 'Update Data';
            document.getElementById('submitBtn').style.backgroundColor = '#ffc107';
            document.getElementById('submitBtn').style.color = '#212529';
            
            // Scroll ke form
            document.querySelector('.form-container').scrollIntoView({ 
                behavior: 'smooth' 
            });
        }

        function hapusMahasiswa(id, nama) {
            if (confirm(`Apakah Anda yakin ingin menghapus data mahasiswa: ${nama}?`)) {
                window.location.href = `proses_mahasiswa.php?action=hapus&id=${id}`;
            }
        }

        function resetForm() {
            document.getElementById('mahasiswaId').value = '';
            document.getElementById('nim').value = '';
            document.getElementById('nama').value = '';
            document.getElementById('jurusan').value = '';
            document.getElementById('submitBtn').value = 'tambah';
            document.getElementById('submitBtn').innerHTML = 'Tambah Data';
            document.getElementById('submitBtn').style.backgroundColor = '';
            document.getElementById('submitBtn').style.color = '';
        }
    </script>

    <script src="script/script.js"></script>

</body>
</html>
<?php mysqli_close($koneksi); ?>