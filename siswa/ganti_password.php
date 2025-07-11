<?php
session_start();
include '../koneksi.php';
include 'header.php';
include 'sidebar.php';

// Cek apakah user sudah login sebagai siswa
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'siswa') {
    header("Location: ../index.php");
    exit();
}

$id = $_SESSION['id']; // ID siswa

if (isset($_POST['ganti_password'])) {
    $pass_lama = $_POST['password_lama'];
    $pass_baru = $_POST['password_baru'];
    $konfirmasi = $_POST['konfirmasi'];

    // Cek apakah siswa ditemukan
    $q = mysqli_query($conn, "SELECT password FROM siswa WHERE id_siswa = '$id'");
    if ($q && mysqli_num_rows($q) > 0) {
        $data = mysqli_fetch_assoc($q);
        $hash = $data['password'];

        if (!empty($hash)) {
            if (password_verify($pass_lama, $hash)) {
                if ($pass_baru === $konfirmasi) {
                    $hash_baru = password_hash($pass_baru, PASSWORD_DEFAULT);
                    $update = mysqli_query($conn, "UPDATE siswa SET password = '$hash_baru' WHERE id_siswa = '$id'");
                    if ($update) {
                        echo "<script>alert('Password berhasil diubah'); window.location.href='profil.php';</script>";
                        exit();
                    } else {
                        echo "<script>alert('Gagal mengubah password');</script>";
                    }
                } else {
                    echo "<script>alert('Konfirmasi password tidak cocok');</script>";
                }
            } else {
                echo "<script>alert('Password lama salah');</script>";
            }
        } else {
            echo "<script>alert('Password belum diset di database');</script>";
        }
    } else {
        echo "<script>alert('Data siswa tidak ditemukan');</script>";
    }
}
?>

<div class="content">
    <h2 style="margin-bottom: 20px;">Ganti Password</h2>

    <form method="POST" action=""
          style="max-width: 600px; background: #f9f9f9; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">

        <label for="password_lama" style="font-weight: bold;">Password Lama</label>
        <div style="position: relative;">
            <input type="password" id="password_lama" name="password_lama" required
                   style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 8px;">
            <span class="toggle-password" onclick="togglePassword('password_lama')"
                  style="position: absolute; right: 12px; top: 12px; cursor: pointer;">👁️</span>
        </div>

        <label for="password_baru" style="font-weight: bold;">Password Baru</label>
        <div style="position: relative;">
            <input type="password" id="password_baru" name="password_baru" required
                   style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 8px;">
            <span class="toggle-password" onclick="togglePassword('password_baru')"
                  style="position: absolute; right: 12px; top: 12px; cursor: pointer;">👁️</span>
        </div>

        <label for="konfirmasi" style="font-weight: bold;">Konfirmasi Password</label>
        <div style="position: relative;">
            <input type="password" id="konfirmasi" name="konfirmasi" required
                   style="width: 100%; padding: 10px; margin-bottom: 25px; border: 1px solid #ccc; border-radius: 8px;">
            <span class="toggle-password" onclick="togglePassword('konfirmasi')"
                  style="position: absolute; right: 12px; top: 12px; cursor: pointer;">👁️</span>
        </div>

        <button type="submit" name="ganti_password"
                style="background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer;">
            Simpan Password Baru
        </button>
    </form>
</div>

<?php include 'footer.php'; ?>

<script>
function togglePassword(id) {
    const field = document.getElementById(id);
    if (field.type === "password") {
        field.type = "text";
    } else {
        field.type = "password";
    }
}
</script>