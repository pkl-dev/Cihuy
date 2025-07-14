<?php
session_start();
include 'header.php';
include 'sidebar.php';
include '../koneksi.php';

if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$limit = 10;
$page = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

$where = "WHERE a.id_pembina IS NOT NULL AND a.id_siswa IS NULL";
if ($search) {
    $where .= " AND (
        p.nama LIKE '%$search%' OR
        e.nama_ekskul LIKE '%$search%' OR
        a.kehadiran LIKE '%$search%' OR
        a.tanggal LIKE '%$search%'
    )";
}

$totalQuery = mysqli_query($conn, "
    SELECT COUNT(*) AS total 
    FROM absensi a
    JOIN pembina p ON a.id_pembina = p.id_pembina
    JOIN ekskul e ON a.id_ekskul = e.id_ekskul
    $where
");
$total = mysqli_fetch_assoc($totalQuery)['total'];
$totalPages = ceil($total / $limit);

$data = mysqli_query($conn, "
    SELECT a.*, p.nama AS nama_pembina, e.nama_ekskul 
    FROM absensi a
    JOIN pembina p ON a.id_pembina = p.id_pembina
    JOIN ekskul e ON a.id_ekskul = e.id_ekskul
    $where
    ORDER BY a.tanggal DESC
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
    <h2>Riwayat Absensi Pembina</h2>

    <form method="GET" style="margin-bottom: 15px;">
        <input type="text" name="search" placeholder="Cari nama / ekskul / tanggal..." value="<?= htmlspecialchars($search); ?>">
        <button type="submit">Cari</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Nama Pembina</th>
                <th>Ekskul</th>
                <th>Tanggal</th>
                <th>Kehadiran</th>
                <th>Bukti Foto</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($data) > 0): ?>
                <?php while ($d = mysqli_fetch_assoc($data)): ?>
                    <tr>
                        <td><?= htmlspecialchars($d['nama_pembina']) ?></td>
                        <td><?= htmlspecialchars($d['nama_ekskul']) ?></td>
                        <td><?= $d['tanggal'] ?></td>
                        <td><?= $d['kehadiran'] ?></td>
                        <td>
                            <?php if ($d['foto_bukti']): ?>
                                <a href="#" onclick="showFoto('../uploads/<?= $d['foto_bukti'] ?>'); return false;">Lihat</a>
                            <?php else: ?>
                                (Tidak Ada)
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5" style="text-align:center;">Tidak ada data absensi.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>" class="<?= ($i == $page) ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>
</div>

<div id="fotoModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:#000a; justify-content:center; align-items:center; z-index:1000;">
    <div onclick="hideFoto()" style="cursor:pointer;">
        <img id="fotoView" src="" style="max-width:90vw; max-height:90vh; border:6px solid white; border-radius:10px;">
    </div>
</div>

<script>
function showFoto(src) {
    document.getElementById("fotoView").src = src;
    document.getElementById("fotoModal").style.display = "flex";
}
function hideFoto() {
    document.getElementById("fotoModal").style.display = "none";
}
</script>

<?php include 'footer.php'; ?>