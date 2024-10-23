<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ruangan Universitas Insan Pembangunan Indonesia</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .header {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .header .home-logo {
            margin-right: 20px;
            position: relative;
            top: -5px; /* Naikkan logo 5px ke atas */
        }
        .header img {
            vertical-align: middle;
        }
        .header h1 {
            display: inline;
            margin: 0;
            font-size: 24px;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            margin-top: 60px;
        }
        .column {
            flex: 50%;
            padding: 10px;
        }
        .room-list {
            display: flex;
            flex-wrap: wrap;
        }
        .room {
            width: 100px;
            margin: 10px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-align: center;
            padding: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            position: relative;
            cursor: pointer;
        }
        .room img {
            width: 80px;
            height: 80px;
            border-radius: 5px;
        }
        .room-name {
            margin-top: 5px;
            font-weight: bold;
        }
        .room-status {
            margin-top: 5px;
            font-size: 12px;
            color: red;
        }
        .home-logo a {
            text-decoration: none;
            color: #397d54;
            font-size: 24px;
            padding: 10px;
            display: inline-flex;
            width: 20px;
            height: 20px;
            transition: background-color 0.3s, color 0.3s;
        }
        .home-logo a:hover {
            background-color: #397d54;
            color: #fff;
        }
        .form-container {
            margin: 20px;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .form-container form {
            display: flex;
            flex-direction: column;
        }
        .form-container label {
            margin-bottom: 5px;
        }
        .form-container input[type="text"], .form-container input[type="file"] {
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-container input[type="submit"] {
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
            cursor: pointer;
        }
        .form-container input[type="submit"]:hover {
            background-color: #555;
        }

        /* Modal Styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 30%;
            max-width: 300px;
            border-radius: 5px;
        }

        .close-btn {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close-btn:hover,
        .close-btn:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .modal img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }

        /* Button for booking */
        .btn-book, .btn-edit, .btn-delete {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 12px;
            font-size: 14px;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-book {
            background-color: #397d54;
        }
        .btn-book:hover {
            background-color: #2e6b44;
        }
        .btn-edit {
            background-color: #0000FF;
        }
        .btn-edit:hover {
            background-color: #ec971f;
        }
        .btn-delete {
            background-color: #c9302c;
        }
        .btn-delete:hover {
            background-color: #d9534f;
        }
    </style>
</head>
<body>
<?php
include 'database.php';

// Handle form submissions for adding and editing rooms
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_room'])) {
        // Add room
        $kode_ruangan = $_POST['kode_ruangan'];
        $deskripsi = $_POST['deskripsi'];
        $building = $_POST['building'];
        $gambar_ruangan = ''; // Handle file upload if needed

        if (isset($_FILES['gambar_ruangan']) && $_FILES['gambar_ruangan']['error'] == UPLOAD_ERR_OK) {
            $tmp_name = $_FILES['gambar_ruangan']['tmp_name'];
            $gambar_ruangan = 'uploads/' . basename($_FILES['gambar_ruangan']['name']);
            move_uploaded_file($tmp_name, $gambar_ruangan);
        }

        $sql = "INSERT INTO ruangan (kode_ruangan, deskripsi, building, gambar_ruangan) VALUES ('$kode_ruangan', '$deskripsi', '$building', '$gambar_ruangan')";
        if ($koneksi->query($sql) === TRUE) {
            echo "New room added successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $koneksi->error;
        }
    } elseif (isset($_POST['edit_room'])) {
        // Edit room
        $id = $_POST['id'];
        $kode_ruangan = $_POST['kode_ruangan'];
        $deskripsi = $_POST['deskripsi'];
        $building = $_POST['building'];
        $gambar_ruangan = $_POST['existing_gambar_ruangan'];

        if (isset($_FILES['gambar_ruangan']) && $_FILES['gambar_ruangan']['error'] == UPLOAD_ERR_OK) {
            $tmp_name = $_FILES['gambar_ruangan']['tmp_name'];
            $gambar_ruangan = 'uploads/' . basename($_FILES['gambar_ruangan']['name']);
            move_uploaded_file($tmp_name, $gambar_ruangan);
        }

        $sql = "UPDATE ruangan SET kode_ruangan='$kode_ruangan', deskripsi='$deskripsi', building='$building', gambar_ruangan='$gambar_ruangan' WHERE id=$id";
        if ($koneksi->query($sql) === TRUE) {
            echo "Room updated successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $koneksi->error;
        }
    } elseif (isset($_POST['delete_room'])) {
        // Delete room
        $id = $_POST['id'];
        $sql = "DELETE FROM ruangan WHERE id=$id";
        if ($koneksi->query($sql) === TRUE) {
            echo "Room deleted successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $koneksi->error;
        }
    }
}

// Fetch rooms for display
$sql = "SELECT r.id, r.kode_ruangan, r.gambar_ruangan, r.deskripsi, r.building, 
               GROUP_CONCAT(p.tanggal_peminjaman ORDER BY p.tanggal_peminjaman ASC SEPARATOR ', ') as tanggal_peminjaman 
        FROM ruangan r 
        LEFT JOIN peminjaman p ON r.kode_ruangan = p.kode_ruangan AND p.status = 'acc' 
        GROUP BY r.kode_ruangan";
$result = $koneksi->query($sql);
$currentDate = date('Y-m-d');

?>
<div class="header">
    <div class="home-logo">
        <a href="homeadmin.php"><i class="fas fa-home"></i></a>
    </div>
    <h1>Ruangan Universitas Insan Pembangunan Indonesia 
        <br>FK (berada di gedung rektorat), FB (berada di gedung Fakultas Bisnis)</br></h1>
</div>

<div class="container">
    <div class="column">
        <div class="room-list">
            <?php
            while($row = $result->fetch_assoc()) {
                echo '<div class="room">';
                echo '<img src="' . $row["gambar_ruangan"] . '" alt="' . $row["kode_ruangan"] . '" onclick="showModal(\'' . $row["kode_ruangan"] . '\', \'' . $row["gambar_ruangan"] . '\', \'' . addslashes($row["deskripsi"]) . '\')">';
                echo '<div class="room-name">' . $row["kode_ruangan"] . '</div>';
                if ($row["tanggal_peminjaman"]) {
                    $tanggalList = explode(', ', $row["tanggal_peminjaman"]);
                    $futureDates = array_filter($tanggalList, function($date) use ($currentDate) {
                        return $date >= $currentDate;
                    });
                    if (!empty($futureDates)) {
                        echo '<div class="room-status">Sudah dibooking pada tanggal: <br>' . implode('<br>', $futureDates) . '</div>';
                    }
                }
                // Add the booking, edit, and delete buttons
                echo '<a href="peminjamanadmin.php?kode_ruangan=' . urlencode($row["kode_ruangan"]) . '" class="btn-book">Pinjam</a>';
                echo '<a href="#" class="btn-edit" onclick="editRoom(' . $row["id"] . ', \'' . addslashes($row["kode_ruangan"]) . '\', \'' . addslashes($row["deskripsi"]) . '\', \'' . addslashes($row["gambar_ruangan"]) . '\', \'' . $row["building"] . '\')">Edit</a>';
                echo '<form method="POST" style="display:inline;" action="">
                        <input type="hidden" name="id" value="' . $row["id"] . '">
                        <input type="submit" name="delete_room" value="Hapus" class="btn-delete">
                      </form>';
                echo '</div>';
            }
            $koneksi->close();
            ?>
        </div>
    </div>
    <div class="column">
        <div class="form-container">
            <h2>Tambah Ruangan</h2>
            <form method="POST" enctype="multipart/form-data" action="">
                <label for="kode_ruangan">Kode Ruangan:</label>
                <input type="text" id="kode_ruangan" name="kode_ruangan" required>
                <label for="deskripsi">Deskripsi:</label>
                <input type="text" id="deskripsi" name="deskripsi" required>
                <label for="building">Gedung:</label>
                <input type="text" id="building" name="building" required>
                <label for="gambar_ruangan">Gambar Ruangan:</label>
                <input type="file" id="gambar_ruangan" name="gambar_ruangan">
                <input type="submit" name="add_room" value="Tambah Ruangan">
            </form>
        </div>

        <div class="form-container" id="edit-form-container" style="display: none;">
            <h2>Edit Ruangan</h2>
            <form method="POST" enctype="multipart/form-data" action="">
                <input type="hidden" id="edit-id" name="id">
                <label for="edit-kode_ruangan">Kode Ruangan:</label>
                <input type="text" id="edit-kode_ruangan" name="kode_ruangan" required>
                <label for="edit-deskripsi">Deskripsi:</label>
                <input type="text" id="edit-deskripsi" name="deskripsi" required>
                <label for="edit-building">Gedung:</label>
                <input type="text" id="edit-building" name="building" required>
                <label for="edit-gambar_ruangan">Gambar Ruangan:</label>
                <input type="file" id="edit-gambar_ruangan" name="gambar_ruangan">
                <input type="hidden" id="existing-gambar_ruangan" name="existing_gambar_ruangan">
                <input type="submit" name="edit_room" value="Update Ruangan">
            </form>
        </div>
    </div>
</div>

<!-- Modal for room details -->
<div id="room-modal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h2 id="modal-room-name"></h2>
        <img id="modal-room-image" src="" alt="Room Image">
        <p id="modal-room-description"></p>
    </div>
</div>

<script>
    // Get modal element
    var modal = document.getElementById("room-modal");
    var closeBtn = document.getElementsByClassName("close-btn")[0];

    // Function to show modal
    function showModal(name, imageSrc, description) {
        document.getElementById("modal-room-name").innerText = name;
        document.getElementById("modal-room-image").src = imageSrc;
        document.getElementById("modal-room-description").innerText = description;
        modal.style.display = "block";
    }

    // Function to close modal
    closeBtn.onclick = function() {
        modal.style.display = "none";
    }

    // Close modal when clicking outside the modal
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Function to show edit form
    function editRoom(id, kode_ruangan, deskripsi, gambar_ruangan, building) {
        document.getElementById("edit-id").value = id;
        document.getElementById("edit-kode_ruangan").value = kode_ruangan;
        document.getElementById("edit-deskripsi").value = deskripsi;
        document.getElementById("edit-building").value = building;
        document.getElementById("existing-gambar_ruangan").value = gambar_ruangan;
        document.getElementById("edit-form-container").style.display = "block";
    }
</script>
</body>
</html>
