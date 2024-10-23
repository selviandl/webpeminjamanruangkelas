<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "peminjamankelas";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['id']) && isset($_POST['status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    // Prepare and bind
    $stmt = $conn->prepare("UPDATE peminjaman SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $id);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>alert('Status peminjaman berhasil diperbarui.'); window.location.href = 'konfirmasipeminjamanadmin.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
