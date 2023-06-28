<?php
require 'koneksi.php';

    $id = $_GET["id"];
    $nama = $_GET["nama"];
    $status = $_GET["status"];
    $presentaseKeuntungan = $_GET["presentase_keuntungan"];
    $tempoPembayaran = $_GET["tempo_pembayaran"];
    $kualitasProduk = $_GET["kualitas_produk"];
    $layananKualitas = $_GET["layanan_kualitas"];
    $bonus = $_GET["bonus"];

    // Menyimpan data ke dalam tabel alternatif
    $sql1 = "INSERT INTO alternatif (id, nama, status) VALUES ('$id', '$nama', '$status')";
    if (mysqli_query($conn, $sql1)) {
        $lastInsertId = mysqli_insert_id($conn);

        // Menyimpan data ke dalam tabel nilai
        $sql2 = "INSERT INTO nilai (id_alternatif, presentase_keuntungan, tempo_pembayaran, kualitas_produk, layanan_kualitas, bonus) VALUES ('$lastInsertId', '$presentaseKeuntungan', '$tempoPembayaran', '$kualitasProduk', '$layananKualitas', '$bonus')";
        if (mysqli_query($conn, $sql2)) {
            echo "Data berhasil ditambahkan.";
        } else {
            echo "Terjadi kesalahan saat menyimpan data nilai: " . mysqli_error($conn);
        }
    } else {
        echo "Terjadi kesalahan saat menyimpan data alternatif: " . mysqli_error($conn);
    }

// Menutup koneksi
mysqli_close($conn);
?>