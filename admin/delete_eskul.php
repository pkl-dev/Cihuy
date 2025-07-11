<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$id = $_GET['id'] ?? '';
$cek = mysqli_query($conn, "SELECT * FROM ekskul WHERE id_ekskul='$id'");
if (mysqli_num_rows($cek) == 0) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='manajemen_eskul.php';</script>";
    exit();
}

$hapus = mysqli_query($conn, "DELETE FROM ekskul WHERE id_ekskul='$id'");
if ($hapus) {
    echo "<script>alert('Ekskul berhasil dihapus'); window.location.href='manajemen_eskul.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus ekskul'); window.location.href='manajemen_eskul.php';</script>";
}