<?php
include 'database.php';

$id = $_GET['id'];
$sql = "SELECT * FROM ruangan WHERE id=$id";
$result = $koneksi->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "Ruangan tidak ditemukan";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Ruangan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
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
    </style>
</head>
<body>
<div class="form-container">
    <h2>Edit Ruangan</h2>
    <form action="updateruangan.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        
        <label for="kode_ruangan">Kode Ruangan:</label>
        <input type="text" id="kode_ruangan" name="kode_ruangan" value="<?php echo $row['kode_ruangan']; ?>" required>
        
        <label for="gambar_ruangan">Gambar Ruangan:</label>
        <input type="file" id="gambar_ruangan" name="gambar_ruangan">
        
        <label for="link_ruangan">Link Ruangan:</label>
        <input type="text" id="link_ruangan" name="link_ruangan" value="<?php echo $row['link_ruangan']; ?>" required>
        
        <label for="building">Building:</label>
        <input type="text" id="building" name="building" value="<?php echo $row['building']; ?>" required>
        
        <input type="submit" value="Update Ruangan">
    </form>
</div>
</body>
</html>
