<?php
session_start();
include '../koneksi.php';

// Cek apakah user adalah pembina
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'pembina') {
    header("Location: ../index.php");
    exit();
}

$id_pembina = $_SESSION['id'];

// SEARCH & PAGINATION
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 5;
$start = ($page - 1) * $per_page;

$where = "WHERE pe.id_pembina = '$id_pembina'";
if ($search) {
    $where .= " AND (j.hari LIKE '%$search%' OR e.nama_ekskul LIKE '%$search%')";
}

// Hitung total data
$total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM ekskul pe 
    JOIN ekskul e ON pe.id_ekskul = e.id_ekskul 
    JOIN jadwal j ON j.id_ekskul = e.id_ekskul 
    $where");
$total = mysqli_fetch_assoc($total_query)['total'];
$total_pages = ceil($total / $per_page);

// Ambil data jadwal
$data = mysqli_query($conn, "SELECT j.*, e.nama_ekskul FROM ekskul pe 
    JOIN ekskul e ON pe.id_ekskul = e.id_ekskul 
    JOIN jadwal j ON j.id_ekskul = e.id_ekskul 
    $where
    ORDER BY j.hari ASC
    LIMIT $start, $per_page");
?>

<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<!-- STYLING -->
<style>
    .content {
        padding: 20px;
    }

    h2 {
        margin-bottom: 15px;
    }

    form input[type="text"] {
        padding: 8px;
        border-radius: 6px;
        border: 1px solid #ccc;
        width: 250px;
    }

    form button {
        padding: 8px 12px;
        border: none;
        background-color: #007bff;
        color: white;
        border-radius: 6px;
        cursor: pointer;
    }

    form button:hover {
        background-color: #0056b3;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border-radius: 8px;
        overflow: hidden;
    }

    table thead {
        background-color: #007bff;
        color: white;
    }

    table th, table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    table tbody tr:hover {
        background-color: #f1f1f1;
    }

    .pagination {
        margin-top: 15px;
    }

    .pagination a {
        margin: 0 5px;
        padding: 6px 12px;
        background-color: #eee;
        border-radius: 4px;
        text-decoration: none;
        color: #333;
        transition: 0.2s;
    }

    .pagination a:hover {
        background-color: #ddd;
    }

    .pagination a.active {
        font-weight: bold;
        background-color: #007bff;
        color: white;
    }

    .add-button {
        display: inline-block;
        margin-top: 15px;
        padding: 8px 12px;
        background-color: #28a745;
        color: white;
        text-decoration: none;
        border-radius: 6px;
    }

    .add-button:hover {
        background-color: #218838;
    }
</style>

<!-- ISI KONTEN -->
<div class="content">
    <h2>Kelola Jadwal Ekstrakurikuler</h2>

    <form method="GET" style="margin-bottom:15px;">
        <input type="text" name="search" placeholder="Cari hari / ekskul" value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Cari</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Hari</th>
                <th>Jam Mulai</th>
                <th>Jam Selesai</th>
                <th>Ekskul</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($data) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($data)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['hari']); ?></td>
                    <td><?= htmlspecialchars($row['jam_mulai']); ?></td>
                    <td><?= htmlspecialchars($row['jam_selesai']); ?></td>
                    <td><?= htmlspecialchars($row['nama_ekskul']); ?></td>
                    <td>
                        <a href="editJadwal.php?id=<?= $row['id_jadwal']; ?>">✏️ Edit</a> |
                        <a href="hapusJadwal.php?id=<?= $row['id_jadwal']; ?>" 
                           onclick="return confirm('Yakin ingin menghapus jadwal ini?')">🗑️ Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align:center;">Tidak ada data jadwal ditemukan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- PAGINATION -->
    <?php if ($total_pages > 1): ?>
    <div class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"
               class="<?= $i == $page ? 'active' : '' ?>">
               <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>

    <a href="tambahJadwal.php" class="add-button">➕ Tambah Jadwal</a>
</div>

<?php include 'footer.php'; ?>