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