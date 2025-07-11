<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin' || !isset($_GET['id'])) {
    header("Location: ../index.php");
    exit();
}

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM siswa_eskul WHERE id_siswa='$id'");
mysqli_query($conn, "DELETE FROM siswa WHERE id_siswa='$id'");

header("Location: manajemen_siswa.php");
exit();
?>