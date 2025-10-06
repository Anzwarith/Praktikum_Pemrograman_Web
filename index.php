<?php
session_start();

// Redirect ke login jika belum login
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$message = '';
if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
}

// Data user 
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
        <div class="notification success" style="margin: 20px auto; max-width: 600px; text-align: center;">
            <?php echo $message; ?>
        </div>
        <?php endif; ?>

        <div class="form-container">
            <h2>Manajemen Data Mahasiswa</h2>
            
            <form id="mahasiswaForm">
                
                <input type="hidden" id="mahasiswaId" name="id">

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

                <button type="submit">Simpan Data</button>
            </form>
        </div>

        <div class="table-container">
            <h2>Daftar Mahasiswa</h2>
            
            <table id="mahasiswaTable">
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Jurusan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="mahasiswaTableBody">
                </tbody>
            </table>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 - Kelas Web Programming</p>
        <p id="modeStatus">Mode Saat Ini: Light Mode</p>
        <p><a href="logout.php" style="color: #4a90e2; text-decoration: none;">Logout</a></p>
    </footer>

    <script src="script/script.js"></script>
    <script src="script/handleAPI.js"></script>

</body>
</html>