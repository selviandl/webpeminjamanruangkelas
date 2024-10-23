<?php
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode_ruangan = $_POST["kode_ruangan"];
    $link_ruangan = $_POST["link_ruangan"];
    $building = $_POST["building"];
    
    // Handle file upload
    $target_dir = "uploads/";
    // Create the uploads directory if it doesn't exist
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }
    $target_file = $target_dir . basename($_FILES["gambar_ruangan"]["name"]);
    if (move_uploaded_file($_FILES["gambar_ruangan"]["tmp_name"], $target_file)) {
        $gambar_ruangan = $target_file;

        $sql = "INSERT INTO ruangan (kode_ruangan, gambar_ruangan, link_ruangan, building) VALUES ('$kode_ruangan', '$gambar_ruangan', '$link_ruangan', '$building')";

        if ($koneksi->query($sql) === TRUE) { '
            echo <script>
            alert("New ruangan created successfully";);
            windows.location.herf="cekruanganadmin.php";
            </script>
            ';
        } else {
            echo "Error: " . $sql . "<br>" . $koneksi->error;
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }

    $koneksi->close();
}
?>
