<?php
session_start();
include 'header.php';
include '../koneksi.php';

if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'pembina') {
    header("Location: ../index.php");
    exit();
}

$id_siswa = $_SESSION['id'];
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1;
$start = ($page - 1) * $limit;

$searchSql = $search ? "AND (
    e.nama_ekskul LIKE '%$search%' OR 
    a.kehadiran LIKE '%$search%' OR 
    a.tanggal LIKE '%$search%'
)" : '';

// Ambil riwayat absensi siswa login
$query = mysqli_query($conn, "
    SELECT a.*, e.nama_ekskul
    FROM absensi a
    JOIN ekskul e ON a.id_ekskul = e.id_ekskul
    WHERE a.id_siswa = '$id_siswa' $searchSql
    ORDER BY a.tanggal DESC
    LIMIT $start, $limit
");

$totalResult = mysqli_query($conn, "
    SELECT COUNT(*) as total
    FROM absensi a
    JOIN ekskul e ON a.id_ekskul = e.id_ekskul
    WHERE a.id_siswa = '$id_siswa' $searchSql
");
$totalData = mysqli_fetch_assoc($totalResult)['total'];
$totalPages = ceil($totalData / $limit);
?>

<?php include 'sidebar.php'; ?>
<div class="content">
    <h2>Riwayat Absensi Saya</h2>

    <form method="GET" style="margin-bottom: 10px;">
        <input type="text" name="search" placeholder="Cari ekskul / tanggal / keterangan..." value="<?= htmlspecialchars($search); ?>">
        <button type="submit">Cari</button>
    </form>

    <table border="1" cellpadding="8" cellspacing="0" style="width: 100%;">
        <thead>
            <tr>
                <th>Ekskul</th>
                <th>Tanggal</th>
                <th>Kehadiran</th>
                <th>Bukti</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($query) > 0): ?>
                <?php while ($r = mysqli_fetch_assoc($query)): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['nama_ekskul']) ?></td>
                        <td><?= $r['tanggal'] ?></td>
                        <td><?= $r['kehadiran'] ?></td>
                        <td>
                            <?php if (!empty($r['foto_bukti'])): ?>
                                <a href="javascript:void(0);" onclick="showModal('../uploads/<?= $r['foto_bukti'] ?>')">Lihat</a>
                            <?php else: ?>
                                (Tidak Ada)
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4" style="text-align:center;">Belum ada data absensi.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>" class="<?= $i == $page ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>
</div>

<!-- Modal Foto Preview -->
<div id="modalFoto" class="modal-foto" onclick="closeModal()">
    <span class="modal-close" onclick="closeModal()">×</span>
    <img id="imgPreview" src="#" alt="Foto Absensi">
</div>

<?php include 'footer.php'; ?>

<script>
function showModal(path) {
    const modal = document.getElementById('modalFoto');
    const img = document.getElementById('imgPreview');
    img.src = path;
    modal.style.display = 'flex';
}
function closeModal() {
    document.getElementById('modalFoto').style.display = 'none';
    document.getElementById('imgPreview').src = '';
}
</script>