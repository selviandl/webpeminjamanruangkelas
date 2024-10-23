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

            // Redirect berdasarkan peran
            switch ($user['role']) {
                case 'admin':
                    header("Location: admin");
                    break;
                case 'mahasiswa':
                    header("Location: mahasiswa");
                    break;
                case 'warek1':
                    header("Location: warek1");
                    break;
                case 'warek3':
                        header("Location: warek3");
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
            windows.location.herf="index.php";
            </script>
            ';
        }
    } else {
        echo '
        <script>
        alert("user name tidak ditemukan");
        windows.location.herf="index.php";
        </script>
        ';
    }
}
?>
