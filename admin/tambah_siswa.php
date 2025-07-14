<?php
session_start();
include '../koneksi.php';
include 'header.php';


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

<style>
    .main {
        display: flex;
    }

    .content {
        margin-left: 250px; 
        padding: 20px;
        width: calc(100% - 270px); 
    }


    .form-siswa {
        background-color: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 500px;
        margin-right: 30px;
        min-height: calc(100vh - 100px);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }



    .form-siswa form {
        display: flex;
        flex-direction: column;
    }

    .form-siswa label {
        margin-top: 10px;
        font-weight: 600;
        color: #333;
    }

    .form-siswa input,
    .form-siswa select {
        margin-top: 6px;
        padding: 10px;
        border-radius: 8px;
        border: 1px solid #ccc;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .form-siswa input:focus,
    .form-siswa select:focus {
        border-color: #4e73df;
        outline: none;
    }

    .form-siswa .btn-wrapper {
        display: flex;
        gap: 10px;
        margin-top: 25px;
    }

    .form-siswa button[type="submit"],
    .form-siswa a.button {
        padding: 10px 18px;
        border: none;
        border-radius: 8px;
        font-size: 15px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }

    .form-siswa button[type="submit"] {
        background-color: #4e73df;
        color: white;
        transition: 0.3s ease;
    }

    .form-siswa button[type="submit"]:hover {
        background-color: #2e59d9;
    }

    .form-siswa a.button {
        background-color: #6c757d;
        color: white;
    }

    .form-siswa a.button:hover {
        background-color: #5a6268;
    }

    h2 {
        margin-bottom: 20px;
        color: #343a40;
    }
</style>


<div class="main">
    <?php include 'sidebar.php'; ?>

    <div class="content">
        <div class="form-siswa">
            <h2>Tambah Siswa</h2>
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

                <div class="btn-wrapper">
                    <button type="submit" name="tambah">Simpan</button>
                    <a href="manajemen_siswa.php" class="button">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
