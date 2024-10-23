<?php
include 'database.php';
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php'); // Redirect to login page if not logged in
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Mahasiswa</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .home-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 5px;
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
            width: 36px;
            height: 36px;
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
        }
        .search-container input[type="text"] {
            width: 100%;
            padding: 10px 20px;
            border: 2px solid #ccc;
            border-radius: 25px;
            outline: none;
            font-size: 16px;
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
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
        }
        .navbar a {
            color: #347235; /* Change navbar text color here */
        }
        .navbar a:hover {
            background-color: #73A16C;
        }
        .hero {
            background:#387C44;
            background-size: cover;
            background-position: center;
            height: 400px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #F5F5F5;
            text-align: center;
        }
        .hero h1 {
            font-size: 4em;
        }
        .content {
            flex-grow: 1;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 18px;
            text-align: left;
        }
        table, th, td {
            border: 1px solid #dddddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .logout-button {
            background-color: blue;
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
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand">SIPENGAN</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
            <div class="home-logo">
                <a href="homemahasiswa.php"><i class="fas fa-home"></i></a>
            </div>
            <li class="nav-item">
                <a class="nav-link" href="cekruanganmahasiswa.php">Ruangan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="peminjamanmahasiswa.php">Peminjaman</a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <form class="search-container" action="searchmahasiswa.php" method="GET">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
            <li class="nav-item">
                <form class="user-container" action="profilmahasiswa.php" method="GET">
                    <button class="profile-btn"><i class="fas fa-user"></i></button>
                </form>
            </li>
        </ul>
    </div>
</nav>
<!-- Hero Section -->
<div class="hero">
    <div>
        <h3><span class="navbar-text">Hello, <?php echo $_SESSION['username']; ?>!</span></h3>
        <h1>Selamat Datang Di SIPENGAN 
            <p>(Sistem Peminjaman Ruangan) UNIPI</p></h1>
        <marquee behavior="scroll" direction="left" scrollamount="10"> <p>Temukan ruangan yang tersedia dan lakukan peminjaman dengan mudah.</p></marquee>
    </div>
</div>
<div class="content">
    <center><h2>Data Peminjaman Ruangan</h2></center>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Ruangan</th>
                    <th>Peminjam</th>
                    <th>Status</th>
                    <th>Tanggal Peminjaman</th>
                    <th>Waktu Mulai</th>
                    <th>Waktu Selesai</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT kode_ruangan, organisasi, status, tanggal_peminjaman, waktu_mulai, waktu_selesai FROM peminjaman";
                $result = $koneksi->query($sql);
                $currentDateTime = date('Y-m-d H:i:s');

                $hasRecords = false;

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $tanggalPeminjaman = $row["tanggal_peminjaman"];
                        $waktuMulai = $row["waktu_mulai"];
                        $waktuSelesai = $row["waktu_selesai"];
                        $combinedStartTime = date('Y-m-d H:i:s', strtotime("$tanggalPeminjaman $waktuMulai"));
                        $combinedEndTime = date('Y-m-d H:i:s', strtotime("$tanggalPeminjaman $waktuSelesai"));

                        if ($combinedEndTime > $currentDateTime) {
                            $hasRecords = true;
                            echo "<tr>
                                    <td>" . $row["kode_ruangan"] . "</td>
                                    <td>" . $row["organisasi"] . "</td>
                                    <td>" . $row["status"] . "</td>
                                    <td>" . $tanggalPeminjaman . "</td>
                                    <td>" . $waktuMulai . "</td>
                                    <td>" . $waktuSelesai . "</td>
                                  </tr>";
                        }
                    }
                }

                if (!$hasRecords) {
                    echo "<tr><td colspan='6'>Tidak ada peminjaman</td></tr>";
                }

                $koneksi->close();
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php
include 'footer.php';
?>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
