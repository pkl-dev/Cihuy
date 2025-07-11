<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin' || !isset($_GET['id'])) {
    header("Location: ../index.php");
    exit();
}

$id = $_GET['id'];

// Kosongkan ekskul yang masih pakai pembina ini
mysqli_query($conn, "UPDATE ekskul SET id_pembina=NULL WHERE id_pembina='$id'");

// Hapus pembina
mysqli_query($conn, "DELETE FROM pembina WHERE id_pembina='$id'");

header("Location: manajemen_pembina.php");
exit();
?>