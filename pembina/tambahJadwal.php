<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'pembina') {
    header("Location: ../index.php");
    exit();
}

$id_pembina = $_SESSION['id'];

$ekskul_query = mysqli_query($conn, "
    SELECT e.id_ekskul, e.nama_ekskul 
    FROM ekskul pe 
    JOIN ekskul e ON pe.id_ekskul = e.id_ekskul 
    WHERE pe.id_pembina = '$id_pembina'
");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_ekskul = $_POST['id_ekskul'];
    $hari = $_POST['hari'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];

    if ($jam_selesai <= $jam_mulai) {
        echo "<script>alert('Jam selesai harus lebih dari jam mulai!');</script>";
    } else {
        $insert = mysqli_query($conn, "INSERT INTO jadwal (id_ekskul, hari, jam_mulai, jam_selesai) 
            VALUES ('$id_ekskul', '$hari', '$jam_mulai', '$jam_selesai')");

        if ($insert) {
            echo "<script>alert('Jadwal berhasil ditambahkan!'); window.location='jadwal.php';</script>";
        } else {
            echo "<script>alert('Gagal menambahkan jadwal!');</script>";
        }
    }
}
?>

<?php include 'header.php'; include 'sidebar.php'; ?>

<div class="content">
    <h2>Tambah Jadwal Ekskul</h2>
    <form method="POST" style="max-width: 400px;">
        <label for="id_ekskul">Ekskul</label>
        <select name="id_ekskul" id="id_ekskul" required>
            <option value="">-- Pilih Ekskul --</option>
            <?php while ($e = mysqli_fetch_assoc($ekskul_query)): ?>
                <option value="<?= $e['id_ekskul'] ?>"><?= $e['nama_ekskul'] ?></option>
            <?php endwhile; ?>
        </select>

        <label for="hari">Hari</label>
        <select name="hari" id="hari" required>
            <option value="">-- Pilih Hari --</option>
            <?php foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $h): ?>
                <option value="<?= $h ?>"><?= $h ?></option>
            <?php endforeach; ?>
        </select>

        <label for="jam_mulai">Jam Mulai</label>
        <input type="time" name="jam_mulai" id="jam_mulai" required>

        <label for="jam_selesai">Jam Selesai</label>
        <input type="time" name="jam_selesai" id="jam_selesai" required>

        <br><br>
        <button type="submit" style="background-color:#2f8fdd; color:white; padding:8px 15px; border:none; border-radius:5px;">
            Simpan
        </button>
    </form>
</div>

<?php include 'footer.php'; ?>