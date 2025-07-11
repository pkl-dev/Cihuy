<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'siswa') {
    header("Location: ../index.php");
    exit();
}

$id = $_SESSION['id'];

// Ambil filter bulan & tahun
$filter_bulan = $_GET['bulan'] ?? '';
$filter_tahun = $_GET['tahun'] ?? '';
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Query filter
$filterSql = "AND a.id_siswa='$id'";
if ($filter_bulan && $filter_tahun) {
    $filterSql .= " AND MONTH(a.tanggal) = '$filter_bulan' AND YEAR(a.tanggal) = '$filter_tahun'";
}
if ($search) {
    $filterSql .= " AND (e.nama_ekskul LIKE '%$search%' OR a.kehadiran LIKE '%$search%' OR a.tanggal LIKE '%$search%')";
}

// Data utama
$sql = "SELECT * FROM absensi a 
        JOIN ekskul e ON a.id_ekskul = e.id_ekskul 
        WHERE 1=1 $filterSql 
        ORDER BY tanggal DESC 
        LIMIT $start, $limit";
$data = mysqli_query($conn, $sql);

// Total data
$totalQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM absensi a 
    JOIN ekskul e ON a.id_ekskul = e.id_ekskul 
    WHERE 1=1 $filterSql");
$totalData = mysqli_fetch_assoc($totalQuery)['total'];
$totalPages = ceil($totalData / $limit);

// Ambil data terakhir absen
$lastAbsen = mysqli_query($conn, "SELECT tanggal, kehadiran FROM absensi WHERE id_siswa='$id' ORDER BY tanggal DESC LIMIT 1");
$last = mysqli_fetch_assoc($lastAbsen);
?>

<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div class="content">
    <h2>Riwayat Absensi</h2>
    <p style="margin-top: -10px; margin-bottom: 20px; color: #666; font-size: 14px;">
        Total absensi: <?= $totalData ?> |
        Terakhir absen: <?= $last ? date("d M Y", strtotime($last['tanggal'])) . " (" . $last['kehadiran'] . ")" : "Belum ada" ?>
    </p>

    <!-- FILTER -->
    <form method="GET" style="margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 10px;">
        <select name="bulan" style="padding: 8px; border-radius: 8px;">
            <option value="">Semua Bulan</option>
            <?php for ($m = 1; $m <= 12; $m++): ?>
                <option value="<?= $m ?>" <?= $m == $filter_bulan ? 'selected' : '' ?>>
                    <?= date('F', mktime(0, 0, 0, $m, 10)) ?>
                </option>
            <?php endfor; ?>
        </select>

        <select name="tahun" style="padding: 8px; border-radius: 8px;">
            <option value="">Semua Tahun</option>
            <?php
            $tahunSekarang = date('Y');
            for ($t = $tahunSekarang; $t >= $tahunSekarang - 5; $t--): ?>
                <option value="<?= $t ?>" <?= $t == $filter_tahun ? 'selected' : '' ?>><?= $t ?></option>
            <?php endfor; ?>
        </select>

        <input type="text" name="search" placeholder="Cari ekskul/status/tanggal..." value="<?= htmlspecialchars($search); ?>"
               style="padding: 8px; width: 220px; border-radius: 8px;">

        <button type="submit" style="padding: 8px 15px; border-radius: 8px; background: #007bff; color: white; border: none;">Terapkan</button>
    </form>

    <!-- TABEL -->
    <table style="width: 100%; border-collapse: collapse; font-family: sans-serif;">
        <thead>
            <tr style="background-color: #f7f7f7; text-align: left;">
                <th style="padding: 10px; border: 1px solid #ddd;">No</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Tanggal</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Kehadiran</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Ekskul</th>
                <th style="padding: 10px; border: 1px solid #ddd;">Bukti</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($data) > 0): ?>
                <?php $no = 1; while ($r = mysqli_fetch_assoc($data)) {
                    $badgeColor = match ($r['kehadiran']) {
                        'Hadir' => '#28a745',
                        'Izin'  => '#ffc107',
                        'Sakit' => '#17a2b8',
                        'Alfa'  => '#dc3545',
                        default => '#6c757d'
                    };
                ?>
                    <tr style="background-color: #fff;">
                        <td style="padding: 10px; border: 1px solid #eee;"><?= $start + $no++; ?></td>
                        <td style="padding: 10px; border: 1px solid #eee;"><?= date("d M Y", strtotime($r['tanggal'])); ?></td>
                        <td style="padding: 10px; border: 1px solid #eee;">
                            <span style="background-color: <?= $badgeColor ?>; color: white; padding: 4px 10px; border-radius: 20px; font-size: 13px;">
                                <?= $r['kehadiran']; ?>
                            </span>
                        </td>
                        <td style="padding: 10px; border: 1px solid #eee;"><?= htmlspecialchars($r['nama_ekskul']); ?></td>
                        <td style="padding: 10px; border: 1px solid #eee;">
                            <button onclick="previewFoto('../uploads/<?= $r['foto_bukti']; ?>')" 
                                style="padding: 6px 12px; background: #17a2b8; color: white; border-radius: 6px; border: none;">
                                Lihat Foto
                            </button>
                        </td>
                    </tr>
                <?php } ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align:center; padding: 10px; border: 1px solid #eee;">
                        Tidak ada data ditemukan.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- PAGINATION -->
    <?php if ($totalPages > 1): ?>
    <div style="margin-top: 20px; text-align: center;">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1; ?>&bulan=<?= $filter_bulan ?>&tahun=<?= $filter_tahun ?>&search=<?= urlencode($search); ?>" style="margin: 0 5px;">&laquo; Prev</a>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i; ?>&bulan=<?= $filter_bulan ?>&tahun=<?= $filter_tahun ?>&search=<?= urlencode($search); ?>"
               style="margin: 0 3px; padding: 5px 10px; border-radius: 5px;
                      <?= $i == $page ? 'background: #007bff; color: white;' : 'color: black;' ?>">
                <?= $i; ?>
            </a>
        <?php endfor; ?>
        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1; ?>&bulan=<?= $filter_bulan ?>&tahun=<?= $filter_tahun ?>&search=<?= urlencode($search); ?>" style="margin: 0 5px;">Next &raquo;</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<!-- MODAL -->
<div id="modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
    background: rgba(0,0,0,0.6); justify-content:center; align-items:center; z-index:9999;">
    <div onclick="closeModal()" style="position:absolute; top:20px; right:30px; font-size:30px; color:white; cursor:pointer;">&times;</div>
    <img id="modal-img" src="" style="max-width:90%; max-height:90%; border: 4px solid white; border-radius: 10px;">
</div>

<script>
function previewFoto(src) {
    document.getElementById('modal-img').src = src;
    document.getElementById('modal').style.display = "flex";
}
function closeModal() {
    document.getElementById('modal').style.display = "none";
}
</script>

<?php include 'footer.php'; ?>