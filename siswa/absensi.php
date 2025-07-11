<?php
session_start();
include '../koneksi.php';
include 'header.php';
include 'sidebar.php';

if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'siswa') {
    header("Location: ../index.php");
    exit();
}

$id_siswa = $_SESSION['id'];
$nama = $_SESSION['nama'];

// Ambil nama ekskul
$queryEskul = mysqli_query($conn, "SELECT e.nama_ekskul FROM siswa_eskul se 
    JOIN ekskul e ON se.id_ekskul = e.id_ekskul 
    WHERE se.id_siswa = '$id_siswa' LIMIT 1");
$eskul = mysqli_fetch_assoc($queryEskul)['nama_ekskul'] ?? 'Tidak Ditemukan';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tanggal = $_POST['tanggal'];
    $kehadiran = $_POST['kehadiran'];

    $foto_name_original = $_FILES['foto']['name'];
    $foto_tmp = $_FILES['foto']['tmp_name'];
    $ext = strtolower(pathinfo($foto_name_original, PATHINFO_EXTENSION));

    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($ext, $allowed)) {
        echo "<script>alert('Format file tidak didukung!'); window.history.back();</script>";
        exit();
    }

    $foto_name = time() . '_' . uniqid() . '.' . $ext;
    $foto_path = '../uploads/' . $foto_name;

    if (!move_uploaded_file($foto_tmp, $foto_path)) {
        echo "<script>alert('Gagal upload file!'); window.history.back();</script>";
        exit();
    }

    $q = mysqli_query($conn, "SELECT id_ekskul FROM ekskul WHERE nama_ekskul = '$eskul' LIMIT 1");
    $id_ekskul = mysqli_fetch_assoc($q)['id_ekskul'] ?? 0;

    $insert = mysqli_query($conn, "INSERT INTO absensi (tanggal, kehadiran, id_siswa, id_ekskul, foto_bukti) 
        VALUES ('$tanggal', '$kehadiran', '$id_siswa', '$id_ekskul', '$foto_name')");

    if ($insert) {
        echo "<script>alert('Absensi berhasil dikirim!'); window.location='absensi.php';</script>";
    } else {
        echo "<script>alert('Gagal mengirim absensi!');</script>";
    }
}
?>

<div class="content">
    <h2 style="margin-bottom: 20px;">Absensi Ekstrakurikuler</h2>

    <form method="POST" enctype="multipart/form-data" style="max-width: 600px; background: #f9f9f9; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <label for="nama" style="font-weight: bold;">Nama</label>
        <input type="text" value="<?= htmlspecialchars($nama); ?>" readonly
            style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 8px;">

        <label for="ekskul" style="font-weight: bold;">Ekstrakurikuler</label>
        <input type="text" value="<?= htmlspecialchars($eskul); ?>" readonly
            style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 8px;">

        <label for="tanggal" style="font-weight: bold;">Tanggal</label>
        <input type="date" name="tanggal" required
            style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 8px;">

        <label for="kehadiran" style="font-weight: bold;">Keterangan</label>
        <select name="kehadiran" required
            style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 8px;">
            <option value="">-- Pilih --</option>
            <option value="Hadir">Hadir</option>
            <option value="Izin">Izin</option>
            <option value="Sakit">Sakit</option>
            <option value="Alfa">Alfa</option>
        </select>

        <label for="foto" style="font-weight: bold;">Foto Bukti</label>
        <input type="file" name="foto" accept="image/*" capture="environment" onchange="previewFoto(this)" required
            style="width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 8px;">

        <img id="preview" src="#" alt="Preview Foto" style="display:none; max-width:200px; margin-top:10px;">

        <br><br>
        <button type="submit"
            style="width: 100%; background-color: #007bff; color: white; padding: 12px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">
            Kirim Absensi
        </button>
    </form>
</div>

<script>
function previewFoto(input) {
    const preview = document.getElementById('preview');
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = "block";
        };
        reader.readAsDataURL(file);
    }
}
</script>

<?php include 'footer.php'; ?>