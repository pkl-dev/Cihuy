<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'pembina') {
    header("Location: ../index.php");
    exit();
}

$id_jadwal = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$query = mysqli_query($conn, "SELECT * FROM jadwal WHERE id_jadwal = '$id_jadwal'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='jadwal.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hari = $_POST['hari'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];

    if ($jam_selesai <= $jam_mulai) {
        echo "<script>alert('Jam selesai harus lebih dari jam mulai!');</script>";
    } else {
        $update = mysqli_query($conn, "UPDATE jadwal SET hari='$hari', jam_mulai='$jam_mulai', jam_selesai='$jam_selesai' 
            WHERE id_jadwal='$id_jadwal'");

        if ($update) {
            echo "<script>alert('Jadwal berhasil diperbarui!'); window.location='jadwal.php';</script>";
        } else {
            echo "<script>alert('Gagal mengedit jadwal!');</script>";
        }
    }
}
?>

<?php include 'header.php'; include 'sidebar.php'; ?>

<div class="content">
    <h2>Edit Jadwal Ekstrakurikuler</h2>
    <form method="POST" style="max-width: 400px;">
        <label for="hari">Hari</label>
        <select name="hari" id="hari" required>
            <?php 
            $hariList = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
            foreach ($hariList as $h): ?>
                <option value="<?= $h ?>" <?= ($data['hari'] == $h) ? 'selected' : '' ?>><?= $h ?></option>
            <?php endforeach; ?>
        </select>

        <label for="jam_mulai">Jam Mulai</label>
        <input type="time" name="jam_mulai" id="jam_mulai" value="<?= $data['jam_mulai'] ?>" required>

        <label for="jam_selesai">Jam Selesai</label>
        <input type="time" name="jam_selesai" id="jam_selesai" value="<?= $data['jam_selesai'] ?>" required>

        <br><br>
        <button type="submit" style="background-color: #2f8fdd; color: white; border: none; padding: 8px 15px; border-radius: 5px;">Simpan Perubahan</button>
    </form>
</div>

<?php include 'footer.php'; ?>