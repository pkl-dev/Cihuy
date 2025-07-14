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
<style>
    .card {
        background: white;
        padding: 20px;
        border-radius: 10px;
        max-width: 500px;
        margin: auto;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    .card-form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .card-form input, .card-form select {
        padding: 10px;
        border-radius: 6px;
        border: 1px solid #ccc;
        font-size: 14px;
    }

    .form-actions {
        display: flex;
        justify-content: flex-start;
        gap: 10px;
        margin-top: 10px;
    }

    .form-actions button[type="submit"] {
        background-color: #28a745;
        color: white;
        padding: 10px 15px;
        border-radius: 8px;
        font-weight: bold;
        border: none;
        cursor: pointer;
        transition: background 0.3s ease, transform 0.2s ease;
    }

    .form-actions button[type="submit"]:hover {
        background-color: #218838;
        transform: scale(1.05);
    }

    .btn-cancel {
        background-color: #6c757d;
        color: white;
        padding: 10px 15px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        transition: background 0.3s ease, transform 0.2s ease;
    }

    .btn-cancel:hover {
        background-color: #5a6268;
        transform: scale(1.05);
    }
</style>


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