<?php
include 'database.php';

$id = $_POST['id'];
$kode_ruangan = $_POST['kode_ruangan'];
$link_ruangan = $_POST['link_ruangan'];
$building = $_POST['building'];

if (isset($_FILES['gambar_ruangan']) && $_FILES['gambar_ruangan']['size'] > 0) {
    $gambar_ruangan = $_FILES['gambar_ruangan']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($gambar_ruangan);
    move_uploaded_file($_FILES['gambar_ruangan']['tmp_name'], $target_file);

    $sql = "UPDATE ruangan SET kode_ruangan='$kode_ruangan', gambar_ruangan='$target_file', link_ruangan='$link_ruangan', building='$building' WHERE id=$id";
} else {
    $sql = "UPDATE ruangan SET kode_ruangan='$kode_ruangan', link_ruangan='$link_ruangan', building='$building' WHERE id=$id";
}

if ($koneksi->query($sql) === TRUE) {
    echo "Ruangan berhasil diperbarui";
} else {
    echo "Error: " . $sql . "<br>" . $koneksi->error;
}

$koneksi->close();
header("Location: cekruanganadmin.php");
?>
