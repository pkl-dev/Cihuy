<?php
include 'header.php';
include 'sidebar.php';
include '../koneksi.php';

$siswa_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM siswa"))['total'];
$pembina_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pembina"))['total'];
$ekskul_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM ekskul"))['total'];
$absensi_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM absensi"))['total'];

$keyword = isset($_GET['keyword']) ? mysqli_real_escape_string($conn, $_GET['keyword']) : '';

$siswa_result = mysqli_query($conn, "SELECT * FROM siswa WHERE nama LIKE '%$keyword%' OR nis LIKE '%$keyword%'");
$pembina_result = mysqli_query($conn, "SELECT * FROM pembina WHERE nama LIKE '%$keyword%' OR nip LIKE '%$keyword%'");
$ekskul_result = mysqli_query($conn, "SELECT * FROM ekskul WHERE nama_ekskul LIKE '%$keyword%'");

$recent_absen = mysqli_query($conn, "
    SELECT a.*, s.nama, e.nama_ekskul 
    FROM absensi a 
    JOIN siswa s ON a.id_siswa = s.id_siswa 
    JOIN ekskul e ON a.id_ekskul = e.id_ekskul 
    ORDER BY a.tanggal DESC 
    LIMIT 5
");
?>

<style>
    .content { font-family: 'Segoe UI', sans-serif; padding: 20px; }
    .hero { background: linear-gradient(135deg, #007bff, #559cff); color: white; padding: 30px; border-radius: 12px; margin-bottom: 25px; }
    .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .card { background: #fefefe; border: 1px solid #ddd; padding: 20px; border-radius: 10px; text-align: center; }
    .section { background: #f8f9fa; padding: 20px; border-radius: 10px; border: 1px solid #ddd; margin-bottom: 30px; }
    .search-box input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px; margin-top: 10px; }
    .search-box button { margin-top: 10px; padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 8px; }
    .section ul { margin-top: 10px; padding-left: 20px; }
    .section h4 { margin-top: 20px; margin-bottom: 10px; color: #333; }
</style>

<div class="content">
    <div class="hero">
        <h1 style="margin: 0; font-size: 32px;">Halo, <?= $_SESSION['nama']; ?> 👑</h1>
        <p style="font-size: 16px; margin-top: 8px;">Selamat datang di <b>Dashboard Admin</b> Sistem Absensi Ekstrakurikuler.</p>
    </div>

    <div class="grid">
        <div class="card"><h3>👨‍🎓 Siswa</h3><p><?= $siswa_count ?></p></div>
        <div class="card"><h3>🧑‍🏫 Pembina</h3><p><?= $pembina_count ?></p></div>
        <div class="card"><h3>🏆 Ekskul</h3><p><?= $ekskul_count ?></p></div>
        <div class="card"><h3>📝 Absensi</h3><p><?= $absensi_count ?></p></div>
    </div>

    <div class="section">
        <h2>Fitur Admin</h2>
        <ul>
            <li>Manajemen <b>Siswa</b>, <b>Pembina</b>, dan <b>Ekskul</b>.</li>
            <li>Atur <b>Jadwal</b> dan <b>Absensi</b>.</li>
            <li>Periksa <b>Kritik & Saran</b> pengguna.</li>
        </ul>
    </div>

    <div class="section search-box">
        <h3>Pencarian Cepat 🔍</h3>
        <form method="GET">
            <input type="text" name="keyword" placeholder="Cari siswa / pembina / ekskul..." value="<?= htmlspecialchars($keyword) ?>">
            <button type="submit">Cari</button>
        </form>

        <?php if ($keyword): ?>
        <div style="margin-top: 20px;">
            <h4>Hasil Pencarian untuk: "<i><?= htmlspecialchars($keyword) ?></i>"</h4>

            <b>Siswa:</b>
            <ul>
                <?php if (mysqli_num_rows($siswa_result) > 0): ?>
                    <?php while ($s = mysqli_fetch_assoc($siswa_result)): ?>
                        <li><?= $s['nama'] ?> (<?= $s['nis'] ?>)</li>
                    <?php endwhile; ?>
                <?php else: ?>
                    <li>Tidak ditemukan</li>
                <?php endif; ?>
            </ul>

            <b>Pembina:</b>
            <ul>
                <?php if (mysqli_num_rows($pembina_result) > 0): ?>
                    <?php while ($p = mysqli_fetch_assoc($pembina_result)): ?>
                        <li><?= $p['nama'] ?> (<?= $p['nip'] ?>)</li>
                    <?php endwhile; ?>
                <?php else: ?>
                    <li>Tidak ditemukan</li>
                <?php endif; ?>
            </ul>

            <b>Ekskul:</b>
            <ul>
                <?php if (mysqli_num_rows($ekskul_result) > 0): ?>
                    <?php while ($e = mysqli_fetch_assoc($ekskul_result)): ?>
                        <li><?= $e['nama_ekskul'] ?></li>
                    <?php endwhile; ?>
                <?php else: ?>
                    <li>Tidak ditemukan</li>
                <?php endif; ?>
            </ul>
        </div>
        <?php endif; ?>
    </div>

    <div class="section">
        <h3>🕒 Aktivitas Terbaru</h3>
        <ul>
            <?php while ($a = mysqli_fetch_assoc($recent_absen)): ?>
                <li><?= $a['nama'] ?> absen di <b><?= $a['nama_ekskul'] ?></b> pada <?= date('d M Y H:i', strtotime($a['tanggal'])) ?></li>
            <?php endwhile; ?>
        </ul>
    </div>
</div>

<?php include 'footer.php'; ?>