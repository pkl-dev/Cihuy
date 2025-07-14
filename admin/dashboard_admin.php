<?php
session_start();
include 'header.php';
include 'sidebar.php';
?>
<style>
    a{
        color:black;
    }
</style>

<div class="content" style="font-family: 'Segoe UI', sans-serif; padding: 20px;">

    <div style="
        background: linear-gradient(135deg, #007bff, #559cff);
        color: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    ">
        <h1 style="margin: 0; font-size: 32px;">Halo, <?= $_SESSION['nama']; ?> 👑</h1>
        <p style="font-size: 16px; margin-top: 8px;">
            Selamat datang di <b>Dashboard Admin</b> Sistem Absensi Ekstrakurikuler.
        </p>
    </div>

    <div style="
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        border: 1px solid #ddd;
    ">
        <h2 style="margin-top: 0;">Fitur Admin</h2>
        <ul style="line-height: 1.8;">
            <li>Kelola data <b><a href="manajemen_siswa.php">Siswa</a></b> dan <b><a href="manajemen_pembina.php">Pembina</a></b>.</li>
            <li>Atur dan sesuaikan <b><a href="manajemen_eskul.php">Manajemen Ekskul</a></b>.</li>
        </ul>
        <p style="margin-top: 10px;">Gunakan menu di sidebar kiri untuk mengakses fitur admin. Jadilah admin bijak yang dicintai seluruh ekskul! 😎🧠</p>
    </div>

</div>

<?php
include 'footer.php';
?>