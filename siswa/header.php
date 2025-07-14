
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Siswa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="theme-switch-wrapper">
    <label class="theme-switch">
        <input type="checkbox" id="themeToggle" onchange="toggleTheme()">
        <span class="slider"></span>
    </label>
    <span id="theme-label">🌞</span>
</div>

<script>
function toggleTheme() {
    const isDark = document.getElementById("themeToggle").checked;
    const root = document.documentElement;
    const label = document.getElementById("theme-label");

    if (isDark) {
        root.setAttribute("data-theme", "dark");
        localStorage.setItem("theme", "dark");
        label.textContent = "🌙";
    } else {
        root.removeAttribute("data-theme");
        localStorage.setItem("theme", "light");
        label.textContent = "🌞";
    }
}

(function () {
    const savedTheme = localStorage.getItem("theme");
    const toggle = document.getElementById("themeToggle");
    const label = document.getElementById("theme-label");

    if (savedTheme === "dark") {
        document.documentElement.setAttribute("data-theme", "dark");
        toggle.checked = true;
        label.textContent = "🌙";
    } else {
        label.textContent = "🌞";
    }
})();
</script>