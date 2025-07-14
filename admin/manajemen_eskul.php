<?php
session_start();
include '../koneksi.php';
include 'header.php';
include 'sidebar.php';

if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$limit = 5;
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
?>

<style>
    body {
        margin-top: 25px;
    }

    .btn-edit, .btn-delete {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 14px;
        text-decoration: none;
        margin-right: 5px;
        transition: background 0.3s, transform 0.2s;
    }

    .btn-edit {
        background-color: #ffc107;
        color: #212529;
        border: 1px solid #e0a800;
    }

    .btn-edit:hover {
        background-color: #e0a800;
        color: white;
        transform: scale(1.05);
    }

    .btn-delete {
        background-color: #dc3545;
        color: white;
        border: 1px solid #bd2130;
    }

    .btn-delete:hover {
        background-color: #bd2130;
        transform: scale(1.05);
    }

    .btn-add {
        background-color: #28a745;
        color: white;
        padding: 10px 15px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        transition: background 0.3s ease, transform 0.2s ease;
    }

    .btn-add:hover {
        background-color: #218838;
        transform: scale(1.05);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background-color: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }

    th, td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #4e73df;
        color: white;
    }

    tbody tr:hover {
        background-color: #f1f1f1;
    }

    form {
        margin-bottom: 20px;
    }

    input[type="text"] {
        padding: 8px 12px;
        border-radius: 6px;
        border: 1px solid #ccc;
        font-size: 14px;
    }

    button[type="submit"] {
        padding: 8px 14px;
        border-radius: 6px;
        background-color: #4e73df;
        color: white;
        border: none;
        cursor: pointer;
        font-size: 14px;
    }

    button[type="submit"]:hover {
        background-color: #2e59d9;
    }

    .pagination {
        margin-top: 20px;
        text-align: center;
    }

    .pagination a {
        display: inline-block;
        padding: 8px 12px;
        margin: 0 4px;
        border-radius: 6px;
        text-decoration: none;
        background-color: #f1f1f1;
        color: #333;
        transition: background 0.3s;
    }

    .pagination a:hover {
        background-color: #d6d6d6;
    }

    .pagination a.active {
        background-color: #4e73df;
        color: white;
        font-weight: bold;
    }
</style>

<div class="content">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
        <h2 style="margin-bottom: 10px;">Manajemen Ekskul</h2>
        <a href="tambah_eskul.php" class="btn-add">➕ Tambah Ekskul</a>
    </div>

    <form method="GET">
        <input type="text" name="search" placeholder="Cari ekskul atau pembina..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Cari</button>
    </form>

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
                            <a href="update_eskul.php?id=<?= $row['id_ekskul'] ?>" class="btn-edit">✏️ Edit</a>
                            <a href="delete_eskul.php?id=<?= $row['id_ekskul'] ?>" class="btn-delete" onclick="return confirm('Yakin ingin menghapus ekskul ini?')">🗑️ Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4" style="text-align: center;">Data tidak ditemukan.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php for ($i = 1; $i <= $pages; $i++): ?>
            <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>" class="<?= ($i == $page) ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
