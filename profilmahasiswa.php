<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['organisasi'])) {
    header("Location: index.php");
    exit();
}

include 'database.php';

$username = $_SESSION['username'];
$organisasi = $_SESSION['organisasi'];

// Ambil data peminjaman dari database
$sql_peminjaman = "SELECT * FROM peminjaman WHERE nama_peminjam='$username'";
$result_peminjaman = $koneksi->query($sql_peminjaman);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .profile {
            margin: 50px auto;
            width: 500;
            padding: 30px;
            border: 1px solid #ccc;
            box-shadow: 2px 2px 12px #aaa;
        }
        .profile p {
            margin: 0 0 10px;
            padding: 0;
        }
        .profile button {
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            margin-right: 5px;
        }
        .profile button:hover {
            background-color: #218838;
        }
        .logout-button {
            background-color: #ff4c4c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 5px;
        }
        .logout-button:hover {
            background-color: #ff1c1c;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="profile">
        <h2>User Profile</h2>
        <p>Username: <?php echo htmlspecialchars($_SESSION['username']); ?></p>
        <p>Organisasi: <?php echo htmlspecialchars($_SESSION['organisasi']); ?></p>
        <button class="logout-button" onclick="confirmLogout()">Logout</button>
        <button class="logout-button" onclick="home()">Home</button>

        <h3>Riwayat Peminjaman</h3>
        <table>
            <tr>
                <th>Ruangan</th>
                <th>Keterangan</th>
                <th>Tanggal Peminjaman</th>
                <th>Waktu Mulai</th>
                <th>Waktu Selesai</th>
                <th>Status</th>
            </tr>
            <?php if ($result_peminjaman->num_rows > 0): ?>
                <?php while($row = $result_peminjaman->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['kode_ruangan']); ?></td>
                        <td><?php echo htmlspecialchars($row['keterangan']); ?></td>
                        <td><?php echo htmlspecialchars($row['tanggal_peminjaman']); ?></td>
                        <td><?php echo htmlspecialchars($row['waktu_mulai']); ?></td>
                        <td><?php echo htmlspecialchars($row['waktu_selesai']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Belum ada riwayat peminjaman</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
    <script>
        function confirmLogout() {
            if (confirm("Apakah Anda yakin ingin logout?")) {
                window.location.href = "index.php";
            }
        }
        function home() {
            window.location.href = "homemahasiswa.php";
        }
    </script>
</body>
</html>
<?php
$koneksi->close();
?>