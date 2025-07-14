<?php
session_start();
include '../koneksi.php';
include 'header.php';
include 'sidebar.php';

if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'pembina') {
    header("Location: ../index.php");
    exit();
}

$id_pembina = $_SESSION['id'];
$nama_pembina = $_SESSION['nama'] ?? '';
$pesan = '';

$q = mysqli_query($conn, "SELECT * FROM ekskul WHERE id_pembina = '$id_pembina' LIMIT 1");
$ekskul = mysqli_fetch_assoc($q);
$nama_ekskul = $ekskul['nama_ekskul'] ?? 'Tidak ditemukan';
$id_ekskul = $ekskul['id_ekskul'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal = $_POST['tanggal'] ?? '';
    $kehadiran = $_POST['kehadiran'] ?? '';

    $foto_name_original = $_FILES['foto']['name'];
    $foto_tmp = $_FILES['foto']['tmp_name'];
    $ext = strtolower(pathinfo($foto_name_original, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png'];

    if (!in_array($ext, $allowed)) {
        $pesan = "❌ Format file tidak didukung.";
    } else {
        $nama_file = time() . '_' . uniqid() . '.' . $ext;
        $path_upload = '../uploads/' . $nama_file;

        if (move_uploaded_file($foto_tmp, $path_upload)) {
            $insert = mysqli_query($conn, "
                INSERT INTO absensi (id_siswa, id_pembina, id_ekskul, tanggal, kehadiran, foto_bukti)
                VALUES (NULL, '$id_pembina', '$id_ekskul', '$tanggal', '$kehadiran', '$nama_file')
            ");

            $pesan = $insert ? "✅ Absensi berhasil dikirim." : "❌ Gagal menyimpan data.";
        } else {
            $pesan = "❌ Upload foto gagal.";
        }
    }
}
?>

<div class="content">
    <h2 style="margin-bottom: 20px;">Absensi Pembina Ekstrakurikuler</h2>

    <?php if ($pesan): ?>
        <div style="color: <?= strpos($pesan, '✅') !== false ? 'green' : 'red' ?>; margin-bottom: 10px;">
            <?= $pesan ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data"
        style="max-width: 600px; background: #f9f9f9; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">

        <label style="font-weight: bold;">Nama Pembina</label>
        <input type="text" value="<?= htmlspecialchars($nama_pembina) ?>" readonly
            style="width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 8px; border: 1px solid #ccc;">

        <label style="font-weight: bold;">Ekskul</label>
        <input type="text" value="<?= htmlspecialchars($nama_ekskul) ?>" readonly
            style="width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 8px; border: 1px solid #ccc;">

        <label style="font-weight: bold;">Tanggal</label>
        <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>" required
            style="width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 8px; border: 1px solid #ccc;">

        <label style="font-weight: bold;">Keterangan</label>
        <select name="kehadiran" required
            style="width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 8px; border: 1px solid #ccc;">
            <option value="">-- Pilih --</option>
            <option value="Hadir">Hadir</option>
            <option value="Izin">Izin</option>
            <option value="Sakit">Sakit</option>
            <option value="Alfa">Alfa</option>
        </select>

        <label style="font-weight: bold;">Foto Bukti</label>
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