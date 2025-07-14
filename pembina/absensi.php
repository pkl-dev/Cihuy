<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'pembina') {
    header("Location: ../index.php");
    exit();
}

$id_pembina = $_SESSION['id'] ?? null;

if (!$id_pembina) {
    echo "<h3 style='color:red;'>Session pembina tidak ditemukan. Silakan login kembali.</h3>";
    exit();
}

$queryEkskul = mysqli_query($conn, "SELECT * FROM ekskul WHERE id_pembina='$id_pembina'");
$ekskul = mysqli_fetch_assoc($queryEkskul);

if (!$ekskul) {
    echo "<h3 style='color:red;'>❌ Anda belum menjadi pembina ekskul manapun.</h3>";
    exit();
}

$id_ekskul = $ekskul['id_ekskul'];
$nama_ekskul = $ekskul['nama_ekskul'];


$queryAbsensi = mysqli_query($conn, "
    SELECT s.nama AS nama_siswa, a.tanggal, a.kehadiran, a.foto_bukti
    FROM absensi a
    JOIN siswa s ON a.id_siswa = s.id_siswa
    JOIN siswa_eskul se ON s.id_siswa = se.id_siswa
    WHERE se.id_ekskul = '$id_ekskul'
    ORDER BY a.tanggal DESC
");


include 'header.php';
include 'sidebar.php';
?>

<style>
    body {
        margin-top: 25px;
    }

    .btn-edit, .btn-delete {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 14px;
        text-decoration: none;
        margin-right: 5px;
        transition: background 0.3s, transform 0.2s;
    }

    .btn-edit {
        background-color: #ffc107;
        color: #212529;
        border: 1px solid #e0a800;
    }

    .btn-edit:hover {
        background-color: #e0a800;
        color: white;
        transform: scale(1.05);
    }

    .btn-delete {
        background-color: #dc3545;
        color: white;
        border: 1px solid #bd2130;
    }

    .btn-delete:hover {
        background-color: #bd2130;
        transform: scale(1.05);
    }

    .btn-add {
        background-color: #28a745;
        color: white;
        padding: 10px 15px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        transition: background 0.3s ease, transform 0.2s ease;
    }

    .btn-add:hover {
        background-color: #218838;
        transform: scale(1.05);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background-color: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }

    th, td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #4e73df;
        color: white;
    }

    tbody tr:hover {
        background-color: #f1f1f1;
    }

    form {
        margin-bottom: 20px;
    }

    input[type="text"] {
        padding: 8px 12px;
        border-radius: 6px;
        border: 1px solid #ccc;
        font-size: 14px;
    }

    button[type="submit"] {
        padding: 8px 14px;
        border-radius: 6px;
        background-color: #4e73df;
        color: white;
        border: none;
        cursor: pointer;
        font-size: 14px;
    }

    button[type="submit"]:hover {
        background-color: #2e59d9;
    }

    .pagination {
        margin-top: 20px;
        text-align: center;
    }

    .pagination a {
        display: inline-block;
        padding: 8px 12px;
        margin: 0 4px;
        border-radius: 6px;
        text-decoration: none;
        background-color: #f1f1f1;
        color: #333;
        transition: background 0.3s;
    }

    .pagination a:hover {
        background-color: #d6d6d6;
    }

    .pagination a.active {
        background-color: #4e73df;
        color: white;
        font-weight: bold;
    }
</style>

<div class="content" style="font-family: 'Segoe UI', sans-serif; padding: 20px;">
    <h2>Data Absensi Ekskul: <?= htmlspecialchars($nama_ekskul) ?></h2>

    <table border="1" cellpadding="10" cellspacing="0" style="width:100%; border-collapse: collapse; margin-top: 20px;">
        <thead style="background: #007bff; color: white;">
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Keterangan</th>
                <th>Foto Bukti</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            if (mysqli_num_rows($queryAbsensi) > 0) {
                while ($row = mysqli_fetch_assoc($queryAbsensi)) {
                    echo "<tr>
                        <td>{$no}</td>
                        <td>{$row['nama_siswa']}</td>
                        <td>{$row['tanggal']}</td>
                        <td>{$row['kehadiran']}</td>
                        <td><a href='../uploads/{$row['foto_bukti']}' target='_blank'>Lihat Foto</a></td>
                    </tr>";
                    $no++;
                }
            } else {
                echo "<tr><td colspan='6' style='text-align:center;'>Belum ada data absensi.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
