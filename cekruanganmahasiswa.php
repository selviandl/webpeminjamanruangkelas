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
        .btn-book {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 12px;
            font-size: 14px;
            color: #fff;
            background-color: #397d54;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-book:hover {
            background-color: #2e6b44;
        }
    </style>
</head>
<body>
<?php
include 'database.php';
?>

<div class="header">
    <div class="home-logo">
        <a href="homemahasiswa.php"><i class="fas fa-home"></i></a>
    </div>
    <h1>Ruangan Universitas Insan Pembangunan Indonesia 
        <br>FK (berada di gedung rektorat), FB (berada di gedung Fakultas Bisnis)</br></h1>
</div>

<div class="container">
    <div class="column">
        <div class="room-list">
            <?php
            $sql = "SELECT r.id, r.kode_ruangan, r.gambar_ruangan, r.deskripsi, 
                           GROUP_CONCAT(p.tanggal_peminjaman ORDER BY p.tanggal_peminjaman ASC SEPARATOR ', ') as tanggal_peminjaman 
                    FROM ruangan r 
                    LEFT JOIN peminjaman p ON r.kode_ruangan = p.kode_ruangan AND p.status = 'acc' 
                    WHERE r.building = 'FK'
                    GROUP BY r.kode_ruangan";
            $result = $koneksi->query($sql);
            $currentDate = date('Y-m-d');

            if ($result->num_rows > 0) {
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
                    // Add the booking button
                    echo '<a href="peminjamanmahasiswa.php?kode_ruangan=' . urlencode($row["kode_ruangan"]) . '" class="btn-book">Pinjam</a>';
                    echo '</div>';
                }
            } else {
                echo "0 results";
            }
            ?>
        </div>
    </div>
    <div class="column">
        <div class="room-list">
            <?php
            $sql = "SELECT r.id, r.kode_ruangan, r.gambar_ruangan, r.deskripsi, 
                           GROUP_CONCAT(p.tanggal_peminjaman ORDER BY p.tanggal_peminjaman ASC SEPARATOR ', ') as tanggal_peminjaman 
                    FROM ruangan r 
                    LEFT JOIN peminjaman p ON r.kode_ruangan = p.kode_ruangan AND p.status = 'acc' 
                    WHERE r.building = 'FB'
                    GROUP BY r.kode_ruangan";
            $result = $koneksi->query($sql);
            if ($result === false) {
    
    
                die('Error: ' . $koneksi->error);
                }
            $currentDate = date('Y-m-d');

            if ($result->num_rows > 0) {
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
                    // Add the booking button
                    echo '<a href="peminjamanmahasiswa.php?kode_ruangan=' . urlencode($row["kode_ruangan"]) . '" class="btn-book">Pinjam</a>';
                    echo '</div>';
                }
            } else {
                echo "0 results";
            }
            $koneksi->close();
            ?>
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
</script>
</body>
</html>
