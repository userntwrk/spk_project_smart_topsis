<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "smart_topsis";

// Membuat koneksi
$conn = mysqli_connect($servername, $username, $password, $database);

// Memeriksa koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
