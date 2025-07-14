<?php
session_start();
include 'header.php';
include '../koneksi.php';

if (!isset($_SESSION['id']) || $_SESSION['level'] !== 'siswa') {
    header("Location: ../index.php");
    exit();
}

$id = $_SESSION['id'];

$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM siswa WHERE id_siswa='$id'"));

$nama_ekskul = [];
$ekskul_query = mysqli_query($conn, "
    SELECT e.nama_ekskul FROM siswa_eskul se
    JOIN ekskul e ON se.id_ekskul = e.id_ekskul
    WHERE se.id_siswa = '$id'
");
while ($e = mysqli_fetch_assoc($ekskul_query)) {
    $nama_ekskul[] = $e['nama_ekskul'];
}

if (isset($_POST['update_profil'])) {
    $namaBaru = $_POST['nama'];
    $usernameBaru = $_POST['username'];

    if ($namaBaru != $data['nama'] || $usernameBaru != $data['username']) {
        $update = mysqli_query($conn, "UPDATE siswa SET nama='$namaBaru', username='$usernameBaru' WHERE id_siswa='$id'");
        if ($update) {
            echo "<script>alert('Profil berhasil diperbarui'); location.href='profil.php';</script>";
        } else {
            echo "<script>alert('Gagal memperbarui profil');</script>";
        }
    } else {
        echo "<script>alert('Tidak ada perubahan yang disimpan');</script>";
    }
}
?>

<?php include 'sidebar.php'; ?>

<div class="content">
    <h2 style="margin-bottom: 20px;">Profil Siswa</h2>

    <form method="POST" action=""
        style="max-width: 600px; background: #f9f9f9; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">

        <input type="hidden" name="id" value="<?= $data['id_siswa']; ?>">

        <label for="nama" style="font-weight:bold;">Nama</label>
        <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($data['nama']); ?>"
            style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 8px;" required>

        <label for="username" style="font-weight:bold;">Username</label>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($data['username']); ?>"
            style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 8px;" required>

        <label for="nisn" style="font-weight:bold;">NISN</label>
        <input type="text" id="nisn" value="<?= htmlspecialchars($data['nisn']); ?>"
            style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 8px;" readonly>

        <label for="ekskul" style="font-weight:bold;">Ekskul</label>
        <input type="text" id="ekskul" value="<?= htmlspecialchars(implode(', ', $nama_ekskul)); ?>"
            style="width: 100%; padding: 10px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 8px;" readonly>

        <div style="display: flex; justify-content: space-between; gap: 10px;">
            <button type="submit" name="update_profil"
                style="flex:1; background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer;">
                Update Profil
            </button>

            <a href="ganti_password.php"
                style="flex:1; background-color: #28a745; color: white; text-align: center; padding: 10px 20px; border-radius: 8px; text-decoration: none;">
                Ganti Password
            </a>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>