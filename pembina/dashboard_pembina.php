<?php session_start();?>
<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div class="content" style="font-family: 'Segoe UI', sans-serif; padding: 20px;">
    <div style="
        background: linear-gradient(135deg, #007bff, #00c6ff);
        color: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    ">
        <h1 style="margin: 0; font-size: 32px;">Halo, <?= $_SESSION['nama']; ?> 👋</h1>
        <p style="font-size: 16px; margin-top: 8px;">Selamat datang di Sistem Absensi Ekstrakurikuler sebagai <b>Pembina</b>.</p>
    </div>

    <div style="
        background: #f9f9f9;
        padding: 20px;
        border-radius: 10px;
        border: 1px solid #ddd;
    ">
        <h2 style="margin-top: 0;">Panduan Singkat</h2>
        <ul style="line-height: 1.8;">
            <li>Kelola siswa yang mengikuti ekskul Anda melalui menu <b>Data Siswa</b>.</li>
            <li>Cek dan sesuaikan <b>Jadwal Ekskul</b> Anda sesuai kebutuhan.</li>
            <li>Lihat laporan <b>Absensi</b> siswa secara real-time.</li>
        </ul>
        <p style="margin-top: 10px;">Gunakan menu di samping kiri untuk navigasi sistem. Selamat bekerja! 💼</p>
    </div>
</div>

<?php include 'footer.php'; ?>