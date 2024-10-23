<?php
session_start();
include 'database.php'; // Sertakan file koneksi database Anda

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $npm = $_POST['npm'];
    $role = $_POST['role'];
    $organisasi = $_POST['organisasi'];
    $password = ($_POST['password']); // Hash password

    // Debugging: Periksa koneksi
    if ($koneksi->connect_error) {
        die("Koneksi gagal: " . $koneksi->connect_error);
    }

    // Debugging: Periksa pernyataan SQL
    $stmt = $koneksi->prepare("INSERT INTO user (username, npm, password, role, organisasi) VALUES (?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("Error dalam pernyataan SQL: " . $koneksi->error);
    }

    $stmt->bind_param("sssss", $username, $npm, $password, $role, $organisasi);

    if ($stmt->execute()) {
        echo '
        <script>
        alert("Register berhasil silahkan login");
        window.location.href = "index.php";
        </script>
        ';
    } else {
        echo '
        <script>
        alert("Register gagal data anda sudah dipakai tolong periksa kembali");
        window.location.href = "register.php";
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
    <title>Register</title>
    <style>
         .logout-button {
            background-color: #ff4c4c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .logout-button:hover {
            background-color: #ff1c1c;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: #f7f7f7;
            font-family: Arial, sans-serif;
        }

        .register-container {
            background: #fff;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }

        .register-container h2 {
            margin-bottom: 40px;
            font-size: 24px;
            color: #333;
        }

        .register-container .input-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .register-container .input-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .register-container .input-group input,
        .register-container .input-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s;
        }

        .register-container .input-group input:focus,
        .register-container .input-group select:focus {
            border-color: #007bff;
        }

        .register-container button {
            display: inline-block;
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background: #007bff;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.3s;
        }

        .register-container button:hover {
            background: #0056b3;
            text-decoration: none;
        }

        .register-container p {
            margin-top: 20px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Register SIPENGAN</h2>
        <form action="register.php" method="post">
            <div class="input-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" placeholder="Klik untuk mengetik nama" required>
            </div>
            <div class="input-group">
                <label for="npm">NPM:</label>
                <input type="text" id="npm" name="npm" placeholder="ketik 8 digit NPM anda" required>
            </div>
            <div class="input-group">
                <label for="role">Role:</label>
                <select id="role" name="role" required>
                    <option value="mahasiswa">mahasiswa</option>
                </select>
            </div>
            <div class="input-group">
                <label for="organisasi">Organisasi:</label>
                <select id="organisasi" name="organisasi" required>
                    <option value="hmsi">HMSI</option>
                    <option value="hmti">HMTI</option>
                    <option value="hmm">HMM</option>
                    <option value="hmak">HMAK</option>
                    <option value="hmse">HMSE</option>
                    <option value="HMSIA">HMSIA</option>
                    <option value="Pealip">PEALIP</option>
                    <option value="kuas">KUAS</option>
                    <option value="comit">COMIT</option>
                    <option value="ldkarroyyan">LDK AR ROYYAN</option>
                    <option value="dolip">D.OLIP</option>
                    <option value="kwu">KEWIRAUSAHAAN</option>
                    <option value="kpum">KPUM</option>
                    <option value="himakip">HIMAKIP</option>
                    <option value="englishclub">ENGLISH CLUB</option>
                </select>
            </div>
            <div class="input-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Klik untuk mengetik password" required>
            </div>
            <button type="submit" name="register">Register</button>
             &nbsp;
            <button class="logout-button" type="button" onclick="batal()">Batal</button>
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
