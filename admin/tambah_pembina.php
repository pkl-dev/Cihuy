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

    $cek = mysqli_query($conn, "SELECT * FROM pembina WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Username sudah digunakan!'); history.back();</script>";
        exit();
    }

    mysqli_query($conn, "INSERT INTO pembina (nama, username, nip, password) VALUES ('$nama', '$username', '$nip', '$password')");
    $last_id = mysqli_insert_id($conn);

    mysqli_query($conn, "UPDATE ekskul SET id_pembina='$last_id' WHERE id_ekskul='$id_ekskul'");

    header("Location: manajemen_pembina.php");
    exit();
}

include 'header.php';
include 'sidebar.php';

$ekskul = mysqli_query($conn, "SELECT * FROM ekskul WHERE id_pembina IS NULL");
?>

<style>
    .content {
        margin-left: 230px;
        padding: 30px;
        min-height: calc(100vh - 100px);
        box-sizing: border-box;
    }

    .form-pembina {
        background-color: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        width: 100%;
        margin: 0 auto;
    }

    .form-pembina form {
        display: flex;
        flex-direction: column;
    }

    .form-pembina label {
        margin-top: 12px;
        font-weight: 600;
        color: #333;
    }

    .form-pembina input,
    .form-pembina select {
        margin-top: 6px;
        padding: 10px;
        border-radius: 8px;
        border: 1px solid #ccc;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .form-pembina input:focus,
    .form-pembina select:focus {
        border-color: #4e73df;
        outline: none;
    }

    .btn-wrapper {
        display: flex;
        justify-content: flex-start;
        gap: 10px;
        margin-top: 25px;
    }

    .btn-wrapper button,
    .btn-wrapper a {
        padding: 10px 18px;
        border: none;
        border-radius: 8px;
        font-size: 15px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }

    .btn-wrapper button {
        background-color: #4e73df;
        color: white;
        transition: 0.3s ease;
    }

    .btn-wrapper button:hover {
        background-color: #2e59d9;
    }

    .btn-wrapper a {
        background-color: #6c757d;
        color: white;
    }

    .btn-wrapper a:hover {
        background-color: #5a6268;
    }

    h2 {
        margin-top: 0;
        color: #343a40;
        text-align: center;
    }
</style>

<div class="content">
    <div class="form-pembina">
        <h2>Tambah Pembina</h2>
        <form method="POST">
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

            <div class="btn-wrapper">
                <button type="submit" name="tambah">Simpan</button>
                <a href="manajemen_pembina.php">Kembali</a>
            </div>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
