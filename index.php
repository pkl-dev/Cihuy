<?php
session_start();
include 'koneksi.php';

$error = "";
$debug = ""; // variabel untuk menampilkan pesan debug

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $users = [
        ['table' => 'admin', 'fields' => ['username'], 'id' => 'id_admin', 'role' => 'admin'],
        ['table' => 'pembina', 'fields' => ['username', 'nip'], 'id' => 'id_pembina', 'role' => 'pembina'],
        ['table' => 'siswa', 'fields' => ['username', 'nisn'], 'id' => 'id_siswa', 'role' => 'siswa'],
    ];

    foreach ($users as $user) {
        foreach ($user['fields'] as $field) {
            $sql = "SELECT * FROM {$user['table']} WHERE $field = '$input' LIMIT 1";
            $result = mysqli_query($conn, $sql);

            if (!$result) {
                $debug .= "Query error: " . mysqli_error($conn) . "<br>";
            }

            if ($row = mysqli_fetch_assoc($result)) {
                $debug .= "Ditemukan di tabel: {$user['table']} dengan kolom $field<br>";

                if (password_verify($password, $row['password'])) {
                    $debug .= "✅ Password cocok.<br>";
                    $_SESSION['id'] = $row[$user['id']];
                    $_SESSION['nama'] = $row['nama'];
                    $_SESSION['level'] = $user['role'];
                    header("Location: {$user['role']}/dashboard_{$user['role']}.php");
                    exit();
                } else {
                    $error = "❌ Password salah!";
                    break 2;
                }
            }
        }
    }

    if (!$error) {
        $error = "❌ Username / NIP / NISN tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login Ekstrakurikuler</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #4e73df, #224abe);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-box {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }

        .login-box h2 {
            text-align: center;
            color: #224abe;
            margin-bottom: 20px;
        }

        .login-box input {
            width: 100%;
            padding: 12px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }

        .password-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            top: 12px;
            right: 12px;
            cursor: pointer;
            user-select: none;
            font-size: 16px;
        }

        .login-box button {
            width: 100%;
            background-color: #4e73df;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .login-box button:hover {
            background-color: #224abe;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }

        .debug {
            font-size: 13px;
            background: #f5f5f5;
            padding: 10px;
            margin-top: 10px;
            border: 1px dashed #ccc;
        }

        @media (max-width: 480px) {
            .login-box {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Login Ekstrakurikuler</h2>
    <?php
    if (!empty($error)) echo "<div class='error'>$error</div>";
    if (!empty($debug)) echo "<div class='debug'>$debug</div>";
    ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Username / NIP / NISN" required>

        <div class="password-wrapper">
            <input type="password" name="password" id="password" placeholder="Password" required>
            <span class="toggle-password" onclick="togglePassword()">👁️</span>
        </div>

        <button type="submit">Login</button>
    </form>
</div>

<script>
    function togglePassword() {
        const pwField = document.getElementById("password");
        const icon = document.querySelector(".toggle-password");
        if (pwField.type === "password") {
            pwField.type = "text";
            icon.textContent = "🙈";
        } else {
            pwField.type = "password";
            icon.textContent = "👁️";
        }
    }
</script>

</body>
</html>