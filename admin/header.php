<?php
session_start();
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Absensi Ekstrakurikuler - Admin</title>
    <link rel="stylesheet" href="style.css?v=<?= time(); ?>">
</head>
<body>

<!-- Toggle Tema -->
<div class="theme-switch-wrapper">
    <label class="theme-switch">
        <input type="checkbox" id="themeToggle">
        <span class="slider"></span>
    </label>
    <span id="theme-label">🌞</span>
</div>

<script>
    const toggle = document.getElementById('themeToggle');
    const themeLabel = document.getElementById('theme-label');

    toggle.addEventListener('change', function () {
        if (this.checked) {
            document.documentElement.setAttribute('data-theme', 'dark');
            localStorage.setItem('theme', 'dark');
            themeLabel.textContent = '🌙';
        } else {
            document.documentElement.removeAttribute('data-theme');
            localStorage.setItem('theme', 'light');
            themeLabel.textContent = '🌞';
        }
    });

    (function () {
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
            toggle.checked = true;
            themeLabel.textContent = '🌙';
        } else {
            toggle.checked = false;
            themeLabel.textContent = '🌞';
        }
    })();
</script>