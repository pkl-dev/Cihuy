<?php
session_start();
include 'header.php';
include '../koneksi.php';

if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'pembina') {
    header("Location: ../index.php");
    exit();
}

$id_pembina = $_SESSION['id'];
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

$limit = 10;
$page = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1;
$start = ($page - 1) * $limit;

$searchSql = $search ? "AND (
    e.nama_ekskul LIKE '%$search%' OR 
    a.kehadiran LIKE '%$search%' OR 
    a.tanggal LIKE '%$search%'
)" : '';

$query = mysqli_query($conn, "
    SELECT a.*, e.nama_ekskul
    FROM absensi a
    JOIN ekskul e ON a.id_ekskul = e.id_ekskul
    WHERE a.id_pembina = '$id_pembina' AND a.id_siswa IS NULL $searchSql
    ORDER BY a.tanggal DESC
    LIMIT $start, $limit
");

$totalResult = mysqli_query($conn, "
    SELECT COUNT(*) as total
    FROM absensi a
    JOIN ekskul e ON a.id_ekskul = e.id_ekskul
    WHERE a.id_pembina = '$id_pembina' AND a.id_siswa IS NULL $searchSql
");
$totalData = mysqli_fetch_assoc($totalResult)['total'];
$totalPages = ceil($totalData / $limit);
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

<?php include 'sidebar.php'; ?>
<div class="content">
    <h2>Riwayat Absensi Pembina</h2>

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
                <tr><td colspan="4" style="text-align:center;">Tidak ada data.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>" class="<?= $i == $page ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>
</div>

<div id="modalFoto" class="modal-foto" onclick="closeModal()">
    <span class="modal-close" onclick="closeModal()">×</span>
    <img id="imgPreview" src="#" alt="Foto Bukti Absensi">
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