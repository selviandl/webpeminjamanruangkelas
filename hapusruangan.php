<?php
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $sql = "DELETE FROM ruangan WHERE id = $id";

    if ($koneksi->query($sql) === TRUE) {
        echo "Room deleted successfully";
    } else {
        echo "Error deleting record: " . $koneksi->error;
    }

    $koneksi->close();
    header("Location: cekruanganadmin.php"); // replace 'your_page.php' with the actual page name
}
?>
