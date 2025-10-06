<?php
session_start();

// Redirect ke dashboard jika sudah login
if (isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username == 'admin' && $password == 'admin123') {
        $_SESSION['username'] = $username;
        header('Location: index.php?message=Login berhasil!');
        exit();
    } else {
        $error = 'Username atau password salah!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dasbor Mahasiswa</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 40px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid #e1e4e8;
        }
        
        .login-container h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #4a90e2;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e1e4e8;
            border-radius: 8px;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
        }
        
        .login-btn {
            width: 100%;
            padding: 14px;
            background-color: #4a90e2;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        
        .login-btn:hover {
            background-color: #357abd;
        }
        
        .error-message {
            color: #dc3545;
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8d7da;
            border-radius: 6px;
        }
    </style>
</head>
<body>
    <header>
        <h1 style="margin: 0;">Sistem Manajemen Mahasiswa</h1>
    </header>

    <main>
        <div class="login-container">
            <h2><i class="fas fa-user-circle"></i> Login</h2>
            
            <?php if ($error): ?>
            <div class="error-message">
                <?php echo $error; ?>
            </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" placeholder="Masukkan username" required 
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                </div>
                
                <button type="submit" class="login-btn">Login</button>
            </form>
            
            <div style="margin-top: 20px; text-align: center; font-size: 0.9rem; color: #666;">
                <strong>Default login:</strong><br>
                Username: admin<br>
                Password: admin123
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 - Kelas Web Programming</p>
    </footer>
</body>
</html>