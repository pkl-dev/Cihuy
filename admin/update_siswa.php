<?php
session_start();
include '../koneksi.php';
include 'header.php';
include 'sidebar.php';

if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin' || !isset($_GET['id'])) {
    header("Location: ../index.php");
    exit();
}

$id = $_GET['id'];
$siswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM siswa WHERE id_siswa='$id'"));
$eskul_siswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id_ekskul FROM siswa_eskul WHERE id_siswa='$id'"));

if (isset($_POST['update'])) {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $nisn = $_POST['nisn'];
    $id_ekskul = $_POST['id_ekskul'];

    $cek = mysqli_query($conn, "SELECT * FROM siswa WHERE username='$username' AND id_siswa!='$id'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Username sudah digunakan!'); history.back();</script>";
        exit();
    }

    mysqli_query($conn, "UPDATE siswa SET nama='$nama', username='$username', nisn='$nisn' WHERE id_siswa='$id'");
    mysqli_query($conn, "UPDATE siswa_eskul SET id_ekskul='$id_ekskul' WHERE id_siswa='$id'");
    header("Location: manajemen_siswa.php");
    exit();
}

$ekskul = mysqli_query($conn, "SELECT * FROM ekskul");
?>

<div class="content">
    <h2>Edit Siswa</h2>
    <div class="card card-form">
        <form method="POST">
            <input type="hidden" name="id_siswa" value="<?= $siswa['id_siswa'] ?>">

            <label>Nama:</label>
            <input type="text" name="nama" value="<?= $siswa['nama'] ?>" required>

            <label>Username:</label>
            <input type="text" name="username" value="<?= $siswa['username'] ?>" required>

            <label>NISN:</label>
            <input type="text" name="nisn" value="<?= $siswa['nisn'] ?>" required>

            <label>Ekskul:</label>
            <select name="id_ekskul" required>
                <?php while ($e = mysqli_fetch_assoc($ekskul)): ?>
                    <option value="<?= $e['id_ekskul'] ?>" <?= $e['id_ekskul'] == $eskul_siswa['id_ekskul'] ? 'selected' : '' ?>>
                        <?= $e['nama_ekskul'] ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <div style="margin-top: 20px; display: flex; gap: 10px;">
                <button type="submit" name="update" style="
                    background-color: #4e73df;
                    color: white;
                    padding: 10px 16px;
                    border: none;
                    border-radius: 8px;
                    cursor: pointer;
                    font-size: 15px;
                ">💾 Update</button>

                <a href="manajemen_siswa.php" style="
                    background-color: #6c757d;
                    color: white;
                    padding: 10px 16px;
                    border-radius: 8px;
                    text-decoration: none;
                    font-size: 15px;
                    display: inline-block;
                    line-height: 1.5;
                ">⬅️ Kembali</a>
            </div>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>