<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div class="content" style="font-family: 'Segoe UI', sans-serif; padding: 20px;">

    <div style="
        background: linear-gradient(135deg, #28a745, #66d37e);
        color: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    ">
        <h1 style="margin: 0; font-size: 32px;">Halo, <?= $_SESSION['nama']; ?> 👋</h1>
        <p style="font-size: 16px; margin-top: 8px;">Selamat datang di <b>Sistem Absensi Ekstrakurikuler</b> sebagai <b>Siswa</b>.</p>
    </div>

    <div style="
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        border: 1px solid #ddd;
    ">
        <h2 style="margin-top: 0;">Petunjuk Penggunaan</h2>
        <ul style="line-height: 1.8;">
            <li>Lakukan <b>absensi</b> ekskul sesuai jadwal dengan mengunggah foto bukti.</li>
            <li>Cek <b>jadwal ekskul</b> kamu di menu jadwal.</li>
            <li>Lihat <b>riwayat absensi</b> kamu untuk memastikan kehadiran tercatat.</li>
        </ul>
        <p style="margin-top: 10px;">Gunakan menu di sebelah kiri untuk mulai menggunakan sistem. Semangat dan tetap aktif ikut ekskul ya! 🏃‍♂️✨</p>
    </div>

</div>

<?php include 'footer.php'; ?>