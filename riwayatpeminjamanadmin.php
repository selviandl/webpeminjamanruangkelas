<?php
session_start();
include 'db.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil data pengguna dari database
$sql_user = "SELECT * FROM users WHERE id='$user_id'";
$result_user = $conn->query($sql_user);

if ($result_user->num_rows > 0) {
    $user = $result_user->fetch_assoc();
} else {
    echo "Pengguna tidak ditemukan.";
    exit();
}

// Ambil data peminjaman dari database
$sql_peminjaman = "SELECT * FROM peminjaman WHERE nama_peminjam='$user_id'";
$result_peminjaman = $conn->query($sql_peminjaman);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
</head>
<body>
    <h1>Profil Pengguna</h1>
    <p><strong>Nama:</strong> <?php echo $user['nama']; ?></p>
    <p><strong>Email:</strong> <?php echo $user['email']; ?></p>

    <h2>Riwayat Peminjaman</h2>
    <table border="1">
        <tr>
            <th>ID Peminjaman</th>
            <th>Kode Ruangan</th>
            <th>Organisasi</th>
            <th>Keterangan</th>
            <th>Status</th>
            <th>Tanggal Peminjaman</th>
            <th>Waktu Mulai</th>
            <th>Waktu Selesai</th>
        </tr>
        <?php
        if ($result_peminjaman->num_rows > 0) {
            while ($row = $result_peminjaman->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['kode_ruangan'] . "</td>";
                echo "<td>" . $row['organisasi'] . "</td>";
                echo "<td>" . $row['keterangan'] . "</td>";
                echo "<td>" . $row['status'] . "</td>";
                echo "<td>" . $row['tanggal_peminjaman'] . "</td>";
                echo "<td>" . $row['waktu_mulai'] . "</td>";
                echo "<td>" . $row['waktu_selesai'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>Tidak ada data peminjaman.</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>
