<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Pagination dan search
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

$where = $search ? "WHERE e.nama_ekskul LIKE '%$search%' OR p.nama LIKE '%$search%'" : '';

$total_query = mysqli_query($conn, "
    SELECT COUNT(*) as total 
    FROM ekskul e 
    LEFT JOIN pembina p ON e.id_pembina = p.id_pembina 
    $where
");
$total = mysqli_fetch_assoc($total_query)['total'];
$pages = ceil($total / $limit);

$ekskul = mysqli_query($conn, "
    SELECT e.*, p.nama AS nama_pembina 
    FROM ekskul e 
    LEFT JOIN pembina p ON e.id_pembina = p.id_pembina 
    $where 
    ORDER BY e.nama_ekskul ASC 
    LIMIT $offset, $limit
");

include 'header.php';
include 'sidebar.php';
?>

<div class="content">
    <h2>Manajemen Ekskul</h2>

    <div class="top-bar">
        <form method="GET">
            <input type="text" name="search" placeholder="Cari ekskul atau pembina..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn">🔍 Cari</button>
        </form>
        <a href="tambah_eskul.php" class="btn btn-add">➕ Tambah Ekskul</a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Ekskul</th>
                    <th>Pembina</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($ekskul) > 0): $no = $offset + 1; ?>
                    <?php while ($row = mysqli_fetch_assoc($ekskul)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['nama_ekskul']) ?></td>
                            <td><?= $row['nama_pembina'] ?? '-' ?></td>
                            <td>
                                <a href="update_eskul.php?id=<?= $row['id_ekskul'] ?>" class="btn btn-edit">✏️ Edit</a>
                                <a href="delete_eskul.php?id=<?= $row['id_ekskul'] ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus ekskul ini?')">🗑️ Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4" style="text-align: center;">Data tidak ditemukan.</td></tr>
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