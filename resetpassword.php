<?php
session_start();
include 'database.php'; // Sertakan file koneksi database Anda

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $npm = $_POST['npm'];
    $new_password = $_POST['new_password']; // Hash password baru

    // Periksa apakah pengguna dengan username dan npm ada
    $stmt = $koneksi->prepare("SELECT * FROM user WHERE username = ? AND npm = ?");
    $stmt->bind_param("ss", $username, $npm);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Perbarui password
        $stmt = $koneksi->prepare("UPDATE user SET password = ? WHERE username = ? AND npm = ?");
        $stmt->bind_param("sss", $new_password, $username, $npm);
        if ($stmt->execute()) {
            echo  '
            <script>
        alert("Reset Password berhasil silahkan login");
        window.location.href = "index.php";
        </script>
            ';
        } else {
            echo '
            <script>
            alert("Reset password gagal");
            windows.location.herf="resetpassword.php";
            </script>
            ';
        }
    } else {
        echo '
        <script>
        alert("Username dan NPM tidak ditemukan");
        windows.location.herf="resetpassword.php";
        </script>
        ';
    }
    $stmt->close();
    $koneksi->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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

        .reset-container {
            background: #fff;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }

        .reset-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .reset-container .input-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .reset-container .input-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .reset-container .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s;
        }

        .reset-container .input-group input:focus {
            border-color: #007bff;
        }

        .reset-container button {
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

        .reset-container button:hover {
            background: #0056b3;
        }

        .reset-container p {
            margin-top: 20px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <h2>Reset Password</h2>
        <form action="resetpassword.php" method="post">
            <div class="input-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" placeholder="ketik username yang terdaftar"required>
            </div>
            <div class="input-group">
                <label for="npm">NPM:</label>
                <input type="text" id="npm" name="npm" placeholder="ketik NPM yang terdaftar" required>
            </div>
            <div class="input-group">
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" placeholder="ketik password yang baru" required>
            </div>
            <button type="submit" name="reset">Reset Password</button>
            &nbsp;
            <button class="logout-button" onclick="batal()">Batal</button>
            &nbsp;
           
        </form>
    </div>
    <script>
        function batal() {
            window.location.href = "index.php";
        }
        function login() {
            window.location.href = "index.php";
        }
    </script>
</body>
</html>
