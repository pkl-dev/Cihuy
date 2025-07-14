
<div class="sidebar">
    <h2>Admin Panel</h2>

    <a href="dashboard_admin.php">🏠 Dashboard</a>
    <a href="manajemen_siswa.php">👨‍🎓 Manajemen Siswa</a>
    <a href="manajemen_pembina.php">🧑‍🏫 Manajemen Pembina</a>
    <a href="manajemen_eskul.php">🏅 Manajemen Ekskul</a>
    <a href="riwayat_pembina.php">📝 Riwayat Pembina</a>
        <a href="riwayat_siswa.php">📝 Riwayat Siswa</a>
    <a href="../logout.php">🚪 Logout</a>
</div>

<style>

.sidebar {
    width: 230px;
    height: 100vh;
    background-color: var(--sidebar-bg);
    padding: 20px 0;
    position: fixed;
    top: 0;
    left: 0;
    overflow-y: auto;
    z-index: 100;
}

.sidebar h2 {
    color: var(--sidebar-text);
    text-align: center;
    margin-bottom: 30px;
    font-size: 22px;
}

.sidebar a {
    display: block;
    color: var(--sidebar-text);
    padding: 12px 20px;
    text-decoration: none;
    font-size: 15px;
    border-radius: 6px;
    transition: 0.3s;
}

.sidebar a:hover {
    background-color: var(--primary);
}

@media screen and (max-width: 768px) {
    .sidebar {
        width: 100%;
        height: auto;
        position: relative;
    }
}
</style>