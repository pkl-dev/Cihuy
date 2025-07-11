<footer class="footer">
    <p>&copy; <?php echo date('Y'); ?> Sistem Absensi Ekstrakurikuler</p>
</footer>

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

    // Saat halaman dimuat, cek preferensi
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

</body>
</html>