<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

if (isset($_POST['tambah'])) {
    $nama = trim($_POST['nama_ekskul']);
    $id_pembina = !empty($_POST['id_pembina']) ? $_POST['id_pembina'] : "NULL";

    $cek = mysqli_query($conn, "SELECT * FROM ekskul WHERE nama_ekskul = '$nama'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Ekskul dengan nama ini sudah ada!');</script>";
    } else {
        mysqli_query($conn, "INSERT INTO ekskul (nama_ekskul, id_pembina) VALUES ('$nama', $id_pembina)");
        echo "<script>alert('Ekskul berhasil ditambahkan!'); window.location.href='manajemen_eskul.php';</script>";
        exit();
    }
}

include 'header.php';
include 'sidebar.php';
?>

<div class="content">
    <div class="card">
        <h2>Tambah Ekskul</h2>
        <form method="POST" class="card-form">
            <label>Nama Ekskul:</label>
            <input type="text" name="nama_ekskul" required>

            <label>Pilih Pembina:</label>
            <select name="id_pembina">
                <option value="">-- Belum Ditentukan --</option>
                <?php
                $pembina = mysqli_query($conn, "SELECT * FROM pembina");
                while ($p = mysqli_fetch_assoc($pembina)) {
                    echo "<option value='{$p['id_pembina']}'>{$p['nama']}</option>";
                }
                ?>
            </select>

            <div class="form-actions">
                <button type="submit" name="tambah">➕ Tambah</button>
                <a href="manajemen_eskul.php" class="btn-cancel">⬅️ Batal</a>
            </div>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>