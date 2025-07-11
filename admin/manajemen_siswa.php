<?php
session_start();
include '../koneksi.php';
include 'header.php';
include 'sidebar.php';

if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$limit = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$offset = ($page - 1) * $limit;

$where = $search ? "WHERE s.nama LIKE '%$search%' OR s.nisn LIKE '%$search%' OR s.username LIKE '%$search%'" : '';

$total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM siswa s $where");
$total = mysqli_fetch_assoc($total_query)['total'];
$pages = ceil($total / $limit);

$query = mysqli_query($conn, "
    SELECT s.*, e.nama_ekskul
    FROM siswa s
    LEFT JOIN siswa_eskul se ON s.id_siswa = se.id_siswa
    LEFT JOIN ekskul e ON se.id_ekskul = e.id_ekskul
    $where
    LIMIT $offset, $limit
");
?>

<div class="content">
    <<div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
    <h2 style="margin-bottom: 10px;">Manajemen Siswa</h2>
    <a href="tambah_siswa.php" class="btn-add">
        ➕ Tambah Siswa
    </a>
</div>

    <form method="GET" style="margin-bottom: 20px;">
        <input type="text" name="search" placeholder="Cari siswa..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Cari</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Username</th>
                <th>NISN</th>
                <th>Ekskul</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = $offset + 1; while ($d = mysqli_fetch_assoc($query)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($d['nama']) ?></td>
                    <td><?= htmlspecialchars($d['username']) ?></td>
                    <td><?= $d['nisn'] ?></td>
                    <td><?= $d['nama_ekskul'] ?></td>
                    <td>
                        <a href="update_siswa.php?id=<?= $d['id_siswa'] ?>">Edit</a> |
                        <a href="delete_siswa.php?id=<?= $d['id_siswa'] ?>" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php for ($i = 1; $i <= $pages; $i++): ?>
            <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>
</div>

<?php include 'footer.php'; ?>