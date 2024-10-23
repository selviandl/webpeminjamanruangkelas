<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php'); // Redirect to login page if not logged in
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "peminjamankelas";

    // Create connection
    $koneksi = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($koneksi->connect_error) {
        die("Connection failed: " . $koneksi->connect_error);
    }

    $kode_ruangan = $_POST['kode_ruangan'];
    $keterangan = $_POST['keterangan'];
    $organisasi = $_POST['organisasi'];
    $nama_peminjam = $_SESSION['username']; // Get username from session
    $status = 'pending'; // default status
    $tanggal_peminjaman = $_POST['tanggal_peminjaman'];
    $waktu_mulai = $_POST['waktu_mulai'];
    $waktu_selesai = $_POST['waktu_selesai'];

    // Check for time conflicts
    $stmt = $koneksi->prepare("SELECT * FROM peminjaman WHERE kode_ruangan = ? AND tanggal_peminjaman = ? AND ((waktu_mulai < ? AND waktu_selesai > ?) OR (waktu_mulai < ? AND waktu_selesai > ?) OR (waktu_mulai >= ? AND waktu_mulai < ?))");
    $stmt->bind_param("ssssssss", $kode_ruangan, $tanggal_peminjaman, $waktu_mulai, $waktu_mulai, $waktu_selesai, $waktu_selesai, $waktu_mulai, $waktu_selesai);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>
                alert('Waktu peminjaman sudah dipakai silahkan pilih waktu yang lain.');
                localStorage.setItem('kode_ruangan', '$kode_ruangan');
                localStorage.setItem('organisasi', '$organisasi');
                localStorage.setItem('keterangan', '$keterangan');
                localStorage.setItem('tanggal_peminjaman', '$tanggal_peminjaman');
                localStorage.setItem('waktu_mulai', '$waktu_mulai');
                localStorage.setItem('waktu_selesai', '$waktu_selesai');
                window.location.href = 'peminjamanmahasiswa.php';
              </script>";
    } else {
        // Prepare and bind
        $stmt = $koneksi->prepare("INSERT INTO peminjaman (kode_ruangan, organisasi, nama_peminjam, keterangan, status, tanggal_peminjaman, waktu_mulai, waktu_selesai) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $kode_ruangan, $organisasi, $nama_peminjam, $keterangan, $status, $tanggal_peminjaman, $waktu_mulai, $waktu_selesai);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Peminjaman berhasil. Tunggu konfirmasi di halaman home.');
                    window.location.href = 'homemahasiswa.php';
                  </script>";
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    $stmt->close();
    $koneksi->close();
}
?>

<?php
include 'database.php';

$kode_ruangan = isset($_GET['kode_ruangan']) ? $_GET['kode_ruangan'] : '';

// Assuming user data is stored in session after login
$nama_peminjam = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$organisasi = isset($_SESSION['organisasi']) ? $_SESSION['organisasi'] : '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Peminjaman Ruangan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .form-container {
            max-width: 600px;
            margin: auto;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 10px;
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
        .form-group .cancel-button {
            background-color: #f44336;
        }
        .form-group .cancel-button:hover {
            background-color: #e53935;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <form action="peminjamanmahasiswa.php" method="post" onsubmit="return validateForm()">
        <center><a>Jam operasional kampus senin-jum'at 09:00 - 21:45 Minggu 09:00 - 16:00</a>
            <p>Pastikan waktu peminjaman tidak melewati jam operasional kampus</p></center>
            <div class="form-group">
                <label for="kode_ruangan">Ruangan:</label>
                <input type="text" id="kode_ruangan" name="kode_ruangan" placeholder="Silahkan Masuk Ke Menu Ruangan Terlebih Dahulu Lalu Klik Button Pinjam" value="<?php echo htmlspecialchars($kode_ruangan); ?>" required readonly>
            </div>
            
            <div class="form-group">
                <label for="organisasi">Organisasi:</label>
                <input type="text" id="organisasi" name="organisasi" value="<?php echo htmlspecialchars($organisasi); ?>" readonly>
            </div>
            
            <div class="form-group">
                <label for="nama_peminjam">Nama Peminjam:</label>
                <input type="text" id="nama_peminjam" name="nama_peminjam" value="<?php echo htmlspecialchars($nama_peminjam); ?>" readonly>
            </div>
            
            <div class="form-group">
                <label for="keterangan">Keterangan:</label>
                <input type="text" id="keterangan" name="keterangan" placeholder="Ketik Acara Yang Akan di Laksanakan?" required>
            </div>
            
            <div class="form-group">
                <label for="tanggal_peminjaman">Tanggal Peminjaman:</label>
                <input type="date" id="tanggal_peminjaman" name="tanggal_peminjaman" required>
            </div>
            
            <div class="form-group">
                <label for="waktu_mulai">Waktu Mulai:</label>
                <input type="time" id="waktu_mulai" name="waktu_mulai" required>
            </div>
            
            <div class="form-group">
                <label for="waktu_selesai">Waktu Selesai:</label>
                <input type="time" id="waktu_selesai" name="waktu_selesai" required>
            </div>
            
            <div class="form-group">
                <button type="submit">Submit</button>
            </div>
            <div class="form-group">
                <button type="button" class="cancel-button" onclick="window.location.href='homemahasiswa.php'">Batal</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var kode_ruangan = localStorage.getItem('kode_ruangan');
            var organisasi = localStorage.getItem('organisasi');
            var keterangan = localStorage.getItem('keterangan');
            var tanggal_peminjaman = localStorage.getItem('tanggal_peminjaman');
            var waktu_mulai = localStorage.getItem('waktu_mulai');
            var waktu_selesai = localStorage.getItem('waktu_selesai');

            if (kode_ruangan) document.getElementById('kode_ruangan').value = kode_ruangan;
            if (organisasi) document.getElementById('organisasi').value = organisasi;
            if (keterangan) document.getElementById('keterangan').value = keterangan;
            if (tanggal_peminjaman) document.getElementById('tanggal_peminjaman').value = tanggal_peminjaman;
            if (waktu_mulai) document.getElementById('waktu_mulai').value = waktu_mulai;
            if (waktu_selesai) document.getElementById('waktu_selesai').value = waktu_selesai;

            // Clear localStorage after repopulating the form
            localStorage.removeItem('kode_ruangan');
            localStorage.removeItem('organisasi');
            localStorage.removeItem('keterangan');
            localStorage.removeItem('tanggal_peminjaman');
            localStorage.removeItem('waktu_mulai');
            localStorage.removeItem('waktu_selesai');
        });

        function validateForm() {
            var kode_ruangan = document.getElementById("kode_ruangan").value;
            if (kode_ruangan === "") {
                alert("Kode ruangan harus diisi silahkan ke menu ruangan terlebih dahulu lalu klik button pinjam.");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
