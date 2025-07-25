<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin' || !isset($_GET['id'])) {
    header("Location: ../index.php");
    exit();
}

$id = $_GET['id'];
$p = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pembina WHERE id_pembina='$id'"));
$ekskul_now = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id_ekskul FROM ekskul WHERE id_pembina='$id'"));

if (isset($_POST['update'])) {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $nip = $_POST['nip'];
    $id_ekskul = $_POST['id_ekskul'];

    $cek = mysqli_query($conn, "SELECT * FROM pembina WHERE username='$username' AND id_pembina!='$id'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Username sudah digunakan!'); history.back();</script>";
        exit();
    }

    mysqli_query($conn, "UPDATE pembina SET nama='$nama', username='$username', nip='$nip' WHERE id_pembina='$id'");

    mysqli_query($conn, "UPDATE ekskul SET id_pembina=NULL WHERE id_pembina='$id'");
    mysqli_query($conn, "UPDATE ekskul SET id_pembina='$id' WHERE id_ekskul='$id_ekskul'");

    header("Location: manajemen_pembina.php");
    exit();
}

include 'header.php';
include 'sidebar.php';
$ekskul = mysqli_query($conn, "SELECT * FROM ekskul");
?>

<div class="content">
    <h2>Edit Pembina</h2>
    <form method="POST" class="card-form card">
        <input type="hidden" name="id_pembina" value="<?= $p['id_pembina'] ?>">

        <label>Nama:</label>
        <input type="text" name="nama" value="<?= $p['nama'] ?>" required>

        <label>Username:</label>
        <input type="text" name="username" value="<?= $p['username'] ?>" required>

        <label>NIP:</label>
        <input type="text" name="nip" value="<?= $p['nip'] ?>" required>

        <label>Ekskul:</label>
        <select name="id_ekskul" required>
            <option value="">-- Pilih Ekskul --</option>
            <?php while ($e = mysqli_fetch_assoc($ekskul)):
                $selected = $e['id_ekskul'] == ($ekskul_now['id_ekskul'] ?? '') ? 'selected' : ''; ?>
                <option value="<?= $e['id_ekskul'] ?>" <?= $selected ?>><?= $e['nama_ekskul'] ?></option>
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

            <a href="manajemen_pembina.php" style="
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

<?php include 'footer.php'; ?>