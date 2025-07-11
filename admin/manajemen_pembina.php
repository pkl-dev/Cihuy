<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Pagination & Search
$limit = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

$where = $search ? "WHERE p.nama LIKE '%$search%' OR p.nip LIKE '%$search%' OR p.username LIKE '%$search%'" : '';
$total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM pembina p $where");
$total = mysqli_fetch_assoc($total_query)['total'];
$pages = ceil($total / $limit);

$query = mysqli_query($conn, "
    SELECT p.*, e.nama_ekskul
    FROM pembina p
    LEFT JOIN ekskul e ON p.id_pembina = e.id_pembina
    $where
    ORDER BY p.nama ASC
    LIMIT $offset, $limit
");

include 'header.php';
include 'sidebar.php';
?>

<div class="content">
    <h2>Manajemen Pembina</h2>

    <!-- Search dan Tombol Tambah -->
    <div class="top-bar" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <form method="GET">
            <input type="text" name="search" placeholder="Cari pembina..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Cari</button>
        </form>
        <a href="tambah_pembina.php" style="padding: 10px 16px; background: var(--primary); color: white; text-decoration: none; border-radius: 8px;">
            ➕ Tambah Pembina
        </a>
    </div>

    <!-- Tabel -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>NIP</th>
                    <th>Ekskul</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($query) > 0): $no = $offset + 1; ?>
                    <?php while ($d = mysqli_fetch_assoc($query)): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($d['nama']) ?></td>
                        <td><?= htmlspecialchars($d['username']) ?></td>
                        <td><?= htmlspecialchars($d['nip']) ?></td>
                        <td><?= $d['nama_ekskul'] ?? '-' ?></td>
                        <td>
                            <a href="update_pembina.php?id=<?= $d['id_pembina'] ?>" class="btn btn-edit">Edit</a>
                            <a href="delete_pembina.php?id=<?= $d['id_pembina'] ?>" class="btn btn-danger"
                               onclick="return confirm('Yakin ingin menghapus pembina ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" style="text-align:center;">Data tidak ditemukan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <?php for ($i = 1; $i <= $pages; $i++): ?>
            <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>" class="<?= ($i == $page) ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>
</div>

<?php include 'footer.php'; ?>