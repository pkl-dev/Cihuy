<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'pembina') {
    header("Location: ../index.php");
    exit();
}

$id_pembina = $_SESSION['id'];
$id_jadwal = intval($_GET['id']); // pastikan angka

// Cek apakah jadwal tersebut milik ekskul pembina ini
$cek = mysqli_query($conn, "
    SELECT j.* FROM jadwal j
    JOIN ekskul e ON j.id_ekskul = e.id_ekskul
    JOIN ekskul pe ON e.id_ekskul = pe.id_ekskul
    WHERE j.id_jadwal = '$id_jadwal' AND pe.id_pembina = '$id_pembina'
");

if (mysqli_num_rows($cek) == 0) {
    echo "<script>alert('❌ Jadwal tidak ditemukan atau bukan milik Anda!'); window.location='jadwal.php';</script>";
    exit();
}

$hapus = mysqli_query($conn, "DELETE FROM jadwal WHERE id_jadwal='$id_jadwal'");

if ($hapus) {
    echo "<script>alert('✅ Jadwal berhasil dihapus!'); window.location='jadwal.php';</script>";
} else {
    echo "<script>alert('❌ Gagal menghapus jadwal!'); window.location='jadwal.php';</script>";
}
?>