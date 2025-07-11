<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'siswa') {
    header("Location: ../index.php");
    exit();
}

$id = $_SESSION['id'];

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 5;
$start = ($page - 1) * $per_page;

$where = "WHERE se.id_siswa = '$id'";
if ($search) {
    $where .= " AND (j.hari LIKE '%$search%' OR e.nama_ekskul LIKE '%$search%')";
}

$total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM siswa_eskul se 
    JOIN ekskul e ON se.id_ekskul = e.id_ekskul 
    JOIN jadwal j ON j.id_ekskul = e.id_ekskul 
    $where");
$total = mysqli_fetch_assoc($total_query)['total'];
$total_pages = ceil($total / $per_page);

$data = mysqli_query($conn, "SELECT j.*, e.nama_ekskul FROM siswa_eskul se 
    JOIN ekskul e ON se.id_ekskul = e.id_ekskul 
    JOIN jadwal j ON j.id_ekskul = e.id_ekskul 
    $where
    ORDER BY j.hari ASC
    LIMIT $start, $per_page");
?>

<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div class="content">
    <h2>Jadwal Ekstrakurikuler</h2>

    <form method="GET" style="margin-bottom:15px;">
        <input type="text" name="search" placeholder="Cari hari / ekskul" value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Cari</button>
    </form>

    <table>
        <thead>
            <tr>
                <th style="text-align:left;">Hari</th>
                <th style="text-align:left;">Jam Mulai</th>
                <th style="text-align:left;">Jam Selesai</th>
                <th style="text-align:left;">Ekskul</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($data) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($data)): ?>
                    <tr>
                        <td><?= $row['hari']; ?></td>
                        <td><?= $row['jam_mulai']; ?></td>
                        <td><?= $row['jam_selesai']; ?></td>
                        <td><?= $row['nama_ekskul']; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align:center;">Tidak ada data jadwal ditemukan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- PAGINATION -->
    <?php if ($total_pages > 1): ?>
    <div class="pagination" style="margin-top: 15px;">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"
               style="<?= $i == $page ? 'font-weight:bold; text-decoration:underline;' : '' ?>">
               <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>