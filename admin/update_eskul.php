<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$id = $_GET['id'] ?? '';
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM ekskul WHERE id_ekskul='$id'"));

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='manajemen_eskul.php';</script>";
    exit();
}

if (isset($_POST['update'])) {
    $nama = trim($_POST['nama_ekskul']);
    $id_pembina = !empty($_POST['id_pembina']) ? $_POST['id_pembina'] : "NULL";

    $cek = mysqli_query($conn, "SELECT * FROM ekskul WHERE nama_ekskul='$nama' AND id_ekskul != '$id'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Nama ekskul sudah digunakan!');</script>";
    } else {
        mysqli_query($conn, "UPDATE ekskul SET nama_ekskul='$nama', id_pembina=$id_pembina WHERE id_ekskul='$id'");
        echo "<script>alert('Ekskul berhasil diperbarui!'); window.location.href='manajemen_eskul.php';</script>";
        exit();
    }
}

include 'header.php';
include 'sidebar.php';
?>

<div class="content">
    <div class="card">
        <h2>Edit Ekskul</h2>
        <form method="POST" class="card-form">
            <label>Nama Ekskul:</label>
            <input type="text" name="nama_ekskul" value="<?= htmlspecialchars($data['nama_ekskul']) ?>" required>

            <label>Pilih Pembina:</label>
            <select name="id_pembina">
                <option value="">-- Belum Ditentukan --</option>
                <?php
                $pembina = mysqli_query($conn, "SELECT * FROM pembina");
                while ($p = mysqli_fetch_assoc($pembina)) {
                    $selected = ($data['id_pembina'] == $p['id_pembina']) ? 'selected' : '';
                    echo "<option value='{$p['id_pembina']}' $selected>{$p['nama']}</option>";
                }
                ?>
            </select>

            <div class="form-actions">

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
            </div>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>