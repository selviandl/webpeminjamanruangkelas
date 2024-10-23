<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['organisasi'])) {
    header("Location: index.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "peminjamankelas";

$koneksi = new mysqli($servername, $username, $password, $dbname);

if ($koneksi->connect_error) {
    die("Connection failed: " . $koneksi->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $sql_update = "UPDATE peminjaman SET status='$status' WHERE id='$id'";

    if ($koneksi->query($sql_update) === TRUE) {
        echo "<script>alert('Status updated successfully');</script>";
    } else {
        echo "Error updating record: " . $koneksi->error;
    }
}

$sql = "SELECT id, kode_ruangan, keterangan, organisasi, nama_peminjam, status, tanggal_peminjaman, waktu_mulai, waktu_selesai 
        FROM peminjaman 
        WHERE status NOT IN ('acc', 'Ditolak')";
$result = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Peminjaman Warek3</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .table-container {
            max-width: 900px;
            margin: auto;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .form-group button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: #45a049;
        }
        .logout-button {
            background-color: #ff4c4c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-bottom: 15px;
        }
        .logout-button:hover {
            background-color: #ff1c1c;
        }
    </style>
</head>
<body>
    <div class="table-container">
        <button class="logout-button" onclick="home()">Home</button>
        <h2>Konfirmasi Peminjaman</h2>
        <table>
            <thead>
                <tr>
                    <th>Kode Ruangan</th>
                    <th>Keterangan</th>
                    <th>Organisasi</th>
                    <th>Nama Peminjam</th>
                    <th>Status</th>
                    <th>Tanggal Peminjaman</th>
                    <th>Waktu Mulai</th>
                    <th>Waktu Selesai</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result === false) {
                    echo "Error: " . $koneksi->error;
                } else if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>" . htmlspecialchars($row["kode_ruangan"]). "</td>
                            <td>" . htmlspecialchars($row["keterangan"]). "</td>
                            <td>" . htmlspecialchars($row["organisasi"]). "</td>
                            <td>" . htmlspecialchars($row["nama_peminjam"]). "</td>
                            <td>" . htmlspecialchars($row["status"]). "</td>
                            <td>" . htmlspecialchars($row["tanggal_peminjaman"]). "</td>
                            <td>" . htmlspecialchars($row["waktu_mulai"]). "</td>
                            <td>" . htmlspecialchars($row["waktu_selesai"]). "</td>
                            <td>
                                <form action='' method='post'>
                                    <input type='hidden' name='id' value='" . htmlspecialchars($row["id"]) . "'>
                                    <select name='status'>
                                        <option value='pending'" . ($row["status"] == 'pending' ? ' selected' : '') . ">Pending</option>
                                        <option value='acc'" . ($row["status"] == 'acc' ? ' selected' : '') . ">acc</option>
                                        <option value='Ditolak'" . ($row["status"] == 'Ditolak' ? ' selected' : '') . ">Ditolak</option>
                                    </select>
                                    <button type='submit'>Update</button>
                                </form>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script>
        function home() {
            window.location.href = "homewarek3.php";
        }
    </script>
</body>
</html>

<?php
$koneksi->close();
?>
