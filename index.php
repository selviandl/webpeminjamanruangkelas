<?php
session_start();
include 'database.php'; // Sertakan file koneksi database Anda

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Periksa apakah pengguna ada di database
    $stmt = $koneksi->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Verifikasi password
        if ($password === $user['password']) { // Gunakan password_verify() untuk password yang di-hash
            // Set variabel sesi
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['organisasi'] = $user['organisasi']; // Tambahkan organisasi ke sesi

            // Redirect berdasarkan peran
            switch ($user['role']) {
                case 'admin':
                    header("Location: homeadmin.php");
                    break;
                case 'mahasiswa':
                    header("Location: homemahasiswa.php");
                    break;
                case 'warek1':
                    header("Location: homewarek1.php");
                    break;
                case 'warek3':
                    header("Location: homewarek3.php");
                    break;
                default:
                    header("Location: index.php");
                    break;
            }
            exit();
        } else {
            echo '
            <script>
            alert("password salah");
            window.location.href="index.php";
            </script>
            ';
        }
    } else {
        echo '
        <script>
        alert("username tidak ditemukan");
        window.location.href="index.php";
        </script>
        ';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: #f7f7f7;
            font-family: Arial, sans-serif;
        }

        .login-container {
            background: #fff;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .login-container .input-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .login-container .input-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .login-container .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s;
        }

        .login-container .input-group input:focus {
            border-color: #007bff;
        }

        .login-container button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background: #007bff;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .login-container button:hover {
            background: #0056b3;
        }

        .login-container p {
            margin-top: 20px;
            color: #666;
        }

        .login-container img {
            width: 100px;
          
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="logokampus.png" alt="Logo UNIPI">
        <h2>Selamat datang di SIPENGAN</h2>
        <p>Silahkan login</p>
        <form action="index.php" method="post">                    
            <div class="input-group">        
                <label for="username">Username:</label>    
                <input type="text" id="username" name="username" placeholder="Klik untuk mengetik nama" required>
            </div> 
            <div class="input-group">        
                <label for="password">Password:</label>         
                <input type="password" id="password" name="password" placeholder="Klik untuk mengetik password" required>        
            </div>           
            <button type="submit" name="login">Login</button>   
        </form>  
        <p>Lupa password? <a href="resetpassword.php">Reset password</a></p>     
        <p>Belum memiliki akun? <a href="register.php">Register</a></p>
    </div>
</body>
</html>
