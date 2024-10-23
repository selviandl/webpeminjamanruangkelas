<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pencarian Ruangan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f0f4f3;
        }
        .home-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        .home-logo a {
            text-decoration: none;
            color: #397d54;
            font-size: 24px;
            padding: 10px;
            border: 2px solid #397d54;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            transition: background-color 0.3s, color 0.3s;
        }
        .home-logo a:hover {
            background-color: #397d54;
            color: #fff;
        }
        .search-container {
            max-width: 600px;
            margin: auto;
            position: relative;
            background-color: #e6f2ed;
            border: 2px solid #b2d8c5;
            border-radius: 25px;
            padding: 10px;
        }
        .search-container input[type="text"] {
            width: 100%;
            padding: 10px 20px;
            border: none;
            border-radius: 25px;
            outline: none;
            font-size: 16px;
            background-color: #e6f2ed;
        }
        .search-container button {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: #397d54;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .search-container button i {
            font-size: 16px;
        }
        .results {
            margin-top: 20px;
            padding: 10px;
            background-color: #e6f2ed;
            border: 1px solid #b2d8c5;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="home-logo">
        <a href="homeadmin.php"><i class="fas fa-home"></i></a>
    </div>
    <h1>Pencarian Ruangan</h1>
    <form class="search-container" action="searchadmin.php" method="GET">
        <input type="text" name="kode_ruangan" placeholder="Silahkan ketik nama Ruangan contoh FK201" required>
        <button type="submit"><i class="fas fa-search"></i></button>
    </form>
    <div class="results">
        <?php
        if (isset($_GET['kode_ruangan'])) {
            $kode_ruangan = $_GET['kode_ruangan'];
            echo "<h2>Hasil pencarian untuk: " . htmlspecialchars($kode_ruangan) . "</h2>";
            // Hasil pencarian akan ditampilkan di sini
        }
        ?>
    </div>
</body>
</html>
    <?php
// Konfigurasi database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "peminjamankelas";

// Membuat koneksi
$koneksi = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Mengambil input pencarian
if (isset($_GET['kode_ruangan'])) {
    $kode_ruangan = $_GET['kode_ruangan'];
    $sql = "SELECT * FROM peminjaman WHERE kode_ruangan LIKE ?";
    $stmt = $koneksi->prepare($sql);
    $searchTerm = "%" . $kode_ruangan . "%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<div class='results'>";
        while ($row = $result->fetch_assoc()) {
            // Sesuaikan kolom yang ingin ditampilkan
            echo '<h2>Hasil Pencarian</h2>';
            echo 'Kode Ruangan: ' . $row["kode_ruangan"] . '<br>';
            echo 'Keterangan: ' . $row["keterangan"] . '<br>';
            echo 'Peminjam: ' . $row["organisasi"] . '<br>';
            echo 'Nama Peminjam: ' . $row["nama_peminjam"] . '<br>';
            echo 'Status: ' . $row["status"] . '<br>';
            echo 'Tanggal Peminjaman: ' . $row["tanggal_peminjaman"] . '<br>';
            echo 'Waktu mulai: ' . $row["waktu_mulai"] . '<br>';
            echo 'Waktu selesai: ' . $row["waktu_selesai"] . '<br>';
        }
        echo "</div>";
    } else {
        echo "<p>Tidak ada hasil untuk pencarian: " . htmlspecialchars($kode_ruangan) . "</p>";
    }
    
    $stmt->close();
}

$koneksi->close();
?>

</body>
</html>
