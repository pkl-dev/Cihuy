<?php
session_start();
include 'header.php';
include 'sidebar.php';
include '../koneksi.php';

if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'siswa') {
    header("Location: ../index.php");
    exit();
}

$id_siswa = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pesan = mysqli_real_escape_string($conn, trim($_POST['pesan']));

    if (strlen($pesan) < 5) {
        echo "<script>alert('Pesan terlalu pendek!');</script>";
    } else {
        $insert = mysqli_query($conn, "INSERT INTO kritik_saran (id_siswa, pesan, tanggal_kirim) 
                                       VALUES ('$id_siswa', '$pesan', NOW())");

        if ($insert) {
            echo "<script>alert('Terima kasih atas masukannya!'); window.location.href='kritik_saran.php';</script>";
        } else {
            echo "<script>alert('Gagal mengirim pesan. Silakan coba lagi.');</script>";
        }
    }
}
?>

<div class="content">
    <h2 style="margin-bottom: 20px;">Kritik & Saran</h2>

    <form action="" method="POST" style="max-width: 600px; background: #f9f9f9; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <label for="pesan" style="font-weight: bold; display:block; margin-bottom: 10px;">Tulis pesan kamu di bawah ya:</label>
        <textarea name="pesan" id="pesan" rows="5" placeholder="Masukkan kritik atau saran Anda..." 
            style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px; resize: vertical; font-family: inherit;" required></textarea>
        
        <button type="submit" style="margin-top: 15px; padding: 10px 20px; background-color: #007bff; border: none; color: white; border-radius: 6px; cursor: pointer;">
            Kirim
        </button>
    </form>
</div>

<?php include 'footer.php'; ?>