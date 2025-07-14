<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'pembina') {
    header("Location: ../index.php");
    exit();
}

$id_pembina = $_SESSION['id'];
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

$query = mysqli_query($conn, "
    SELECT ks.*, s.nama AS nama_siswa, e.nama_ekskul 
    FROM kritik_saran ks  
    JOIN siswa s ON ks.id_siswa = s.id_siswa  
    JOIN siswa_eskul se ON s.id_siswa = se.id_siswa  
    JOIN ekskul e ON se.id_ekskul = e.id_ekskul  
    WHERE e.id_pembina = '$id_pembina'  
    AND (
        s.nama LIKE '%$search%' OR 
        e.nama_ekskul LIKE '%$search%' OR 
        ks.pesan LIKE '%$search%'
    )
    ORDER BY ks.tanggal_kirim DESC
");
?>

<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div class="content">
    <h2>Daftar Kritik & Saran dari Siswa</h2>

    <form method="GET" style="margin-bottom: 15px;">
        <input type="text" name="search" placeholder="Cari nama siswa / ekskul / pesan..." 
               value="<?= htmlspecialchars($search); ?>" 
               style="padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
        <button type="submit" style="padding: 8px 12px;">Cari</button>
    </form>

    <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse;">
        <thead style="background-color: #007bff; color: white;">
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Ekskul</th>
                <th>Pesan</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($query) > 0): ?>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($query)): ?>
                <tr style="background-color: #fff;">
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['nama_siswa']) ?></td>
                    <td><?= htmlspecialchars($row['nama_ekskul']) ?></td>
                    <td><?= nl2br(htmlspecialchars($row['pesan'])) ?></td>
                    <td><?= date('d M Y H:i', strtotime($row['tanggal_kirim'])) ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align:center; background-color:#f8d7da;">Tidak ada hasil ditemukan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>