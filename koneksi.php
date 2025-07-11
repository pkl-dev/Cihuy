<?php
$conn = mysqli_connect("localhost", "root", "", "eskul"); 
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>