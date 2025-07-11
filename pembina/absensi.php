<?php
session_start();
include '../koneksi.php';
include 'header.php';
include 'sidebar.php';

if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'pembina') {
    header("Location: ../index.php");
    exit();
}

$id_pembina = $_SESSION['id'];
$pesan = '';

// Ambil ekskul yang dibina
$ekskul_query = mysqli_query($conn, "SELECT * FROM ekskul WHERE id_pembina = '$id_pembina'");
$ekskul_data = mysqli_fetch_assoc($ekskul_query);
$id_ekskul = $ekskul_data['id_ekskul'];
$nama_ekskul = $ekskul_data['nama_ekskul'];

// Ambil daftar siswa di ekskul tersebut
$siswa_query = mysqli_query($conn, "
    SELECT s.id_siswa, s.nama
    FROM siswa_eskul se
    JOIN siswa s ON se.id_siswa = s.id_siswa
    WHERE se.id_ekskul = '$id_ekskul'
    ORDER BY s.nama ASC
");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal = $_POST['tanggal'] ?? '';
    $kehadiran = $_POST['kehadiran']; // array: id_siswa => status

    $sukses = 0;
    $gagal = 0;

    foreach ($kehadiran as $id_siswa => $status) {
        if ($status) {
            $insert = mysqli_query($conn, "
                INSERT INTO absensi (tanggal, kehadiran, id_siswa, id_ekskul) 
                VALUES ('$tanggal', '$status', '$id_siswa', '$id_ekskul')
            ");
            if ($insert) $sukses++; else $gagal++;
        }
    }

    $pesan = "✅ Berhasil simpan: $sukses data. ❌ Gagal: $gagal data.";
}
?>

<div class="content">
    <h2>Absensi Siswa - <?= htmlspecialchars($nama_ekskul) ?></h2>

    <?php if ($pesan): ?>
        <div style="color: <?= strpos($pesan, '✅') !== false ? 'green' : 'red' ?>; margin-bottom: 10px;">
            <?= $pesan ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Tanggal</label>
        <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>" required style="margin-bottom:10px;"><br>

        <table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse: collapse;">
            <thead style="background:#007bff; color:white;">
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Kehadiran</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($row = mysqli_fetch_assoc($siswa_query)):
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td>
                        <select name="kehadiran[<?= $row['id_siswa'] ?>]" required>
                            <option value="">-- Pilih --</option>
                            <option value="Hadir">Hadir</option>
                            <option value="Izin">Izin</option>
                            <option value="Sakit">Sakit</option>
                            <option value="Alfa">Alfa</option>
                        </select>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <br>
        <button type="submit" style="background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 6px;">
            Simpan Absensi
        </button>
    </form>
</div>

<?php include 'footer.php'; ?>