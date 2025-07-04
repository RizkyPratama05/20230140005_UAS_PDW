<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'asisten') {
    header("Location: ../login.php");
    exit;
}

require_once '../config.php';
$pageTitle = 'Kelola Akun';
include 'templates/header.php';

// Handle Hapus
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM users WHERE id = $id");
    header("Location: manage_users.php");
}

// Ambil semua user
$users = mysqli_query($conn, "SELECT * FROM users");
?>

<h2 class="text-2xl font-bold mb-4">Manajemen Pengguna</h2>

<table class="w-full table-auto border">
    <thead class="bg-gray-100">
        <tr>
            <th class="border px-4 py-2">Nama</th>
            <th class="border px-4 py-2">Email</th>
            <th class="border px-4 py-2">Role</th>
            <th class="border px-4 py-2">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($u = mysqli_fetch_assoc($users)): ?>
        <tr>
            <td class="border px-4 py-2"><?= $u['nama'] ?></td>
            <td class="border px-4 py-2"><?= $u['email'] ?></td>
            <td class="border px-4 py-2"><?= $u['role'] ?></td>
            <td class="border px-4 py-2">
                <a href="?hapus=<?= $u['id'] ?>" onclick="return confirm('Yakin hapus user ini?')" class="text-red-600">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include 'templates/footer.php'; ?>
