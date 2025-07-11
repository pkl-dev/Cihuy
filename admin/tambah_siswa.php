<?php
session_start();
include '../koneksi.php';
include 'header.php';
include 'sidebar.php';

if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $nisn = $_POST['nisn'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $id_ekskul = $_POST['id_ekskul'];

    $cek = mysqli_query($conn, "SELECT * FROM siswa WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Username sudah digunakan!'); history.back();</script>";
        exit();
    }

    mysqli_query($conn, "INSERT INTO siswa (nama, username, nisn, password) VALUES ('$nama', '$username', '$nisn', '$password')");
    $last_id = mysqli_insert_id($conn);
    mysqli_query($conn, "INSERT INTO siswa_eskul (id_siswa, id_ekskul) VALUES ('$last_id', '$id_ekskul')");
    header("Location: manajemen_siswa.php");
    exit();
}

$ekskul = mysqli_query($conn, "SELECT * FROM ekskul");
?>

<div class="content">
    <h2>Tambah Siswa</h2>
    <div class="card card-form">
        <form method="POST">
            <label>Nama:</label>
            <input type="text" name="nama" required>

            <label>Username:</label>
            <input type="text" name="username" required>

            <label>NISN:</label>
            <input type="text" name="nisn" required>

            <label>Password:</label>
            <input type="text" name="password" required>

            <label>Ekskul:</label>
            <select name="id_ekskul" required>
                <option value="">-- Pilih Ekskul --</option>
                <?php while ($e = mysqli_fetch_assoc($ekskul)): ?>
                    <option value="<?= $e['id_ekskul'] ?>"><?= $e['nama_ekskul'] ?></option>
                <?php endwhile; ?>
            </select>

            <button type="submit" name="tambah">Simpan</button>
            <a href="manajemen_siswa.php" class="button" style="margin-left:10px;">Kembali</a>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>