<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $nip = $_POST['nip'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $id_ekskul = $_POST['id_ekskul'];

    // Validasi username tidak boleh sama
    $cek = mysqli_query($conn, "SELECT * FROM pembina WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Username sudah digunakan!'); history.back();</script>";
        exit();
    }

    // Insert pembina
    mysqli_query($conn, "INSERT INTO pembina (nama, username, nip, password) VALUES ('$nama', '$username', '$nip', '$password')");
    $last_id = mysqli_insert_id($conn);

    // Set ekskul
    mysqli_query($conn, "UPDATE ekskul SET id_pembina='$last_id' WHERE id_ekskul='$id_ekskul'");

    header("Location: manajemen_pembina.php");
    exit();
}

include 'header.php';
include 'sidebar.php';

$ekskul = mysqli_query($conn, "SELECT * FROM ekskul WHERE id_pembina IS NULL");
?>

<div class="content">
    <h2>Tambah Pembina</h2>
    <form method="POST" class="card-form card">
        <label>Nama:</label>
        <input type="text" name="nama" required>

        <label>Username:</label>
        <input type="text" name="username" required>

        <label>NIP:</label>
        <input type="text" name="nip" required>

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
        <a href="manajemen_pembina.php" class="btn">Kembali</a>
    </form>
</div>

<?php include 'footer.php'; ?>