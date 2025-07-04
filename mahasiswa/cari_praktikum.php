<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'mahasiswa') {
    header("Location: ../login.php");
    exit;
}

$pageTitle = 'Cari Praktikum';
$activePage = 'cari';
require_once 'templates/header_mahasiswa.php';


require_once '../config.php';
$user_id = $_SESSION['user_id'];

if (isset($_GET['daftar'])) {
    $id_praktikum = $_GET['daftar'];
    $cek = mysqli_query($conn, "SELECT * FROM pendaftaran WHERE id_mahasiswa = $user_id AND id_praktikum = $id_praktikum");
    if (mysqli_num_rows($cek) == 0) {
        mysqli_query($conn, "INSERT INTO pendaftaran (id_mahasiswa, id_praktikum) VALUES ($user_id, $id_praktikum)");
        echo "<script>alert('Berhasil mendaftar ke praktikum!'); window.location='cari_praktikum.php';</script>";
        exit;
    }
}
$query = mysqli_query($conn, "SELECT * FROM mata_praktikum");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cari Praktikum - SIMPRAK</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-4xl mx-auto bg-white shadow p-6 rounded-lg">
        <h1 class="text-2xl font-bold mb-4">Daftar Semua Praktikum</h1>
        <table class="w-full table-auto border-collapse border border-gray-300">
            <thead class="bg-blue-100">
                <tr>
                    <th class="border px-4 py-2">Nama</th>
                    <th class="border px-4 py-2">Semester</th>
                    <th class="border px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($p = mysqli_fetch_assoc($query)): ?>
                <tr class="hover:bg-gray-50">
                    <td class="border px-4 py-2"><?= htmlspecialchars($p['nama']) ?></td>
                    <td class="border px-4 py-2"><?= htmlspecialchars($p['semester']) ?></td>
                    <td class="border px-4 py-2 text-center">
                        <?php
                        $cek = mysqli_query($conn, "SELECT * FROM pendaftaran WHERE id_mahasiswa = $user_id AND id_praktikum = {$p['id']}");
                        if (mysqli_num_rows($cek) > 0) {
                            echo "<span class='text-green-600 font-semibold'>✔ Terdaftar</span>";
                        } else {
                            echo "<a href='?daftar={$p['id']}' class='bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700'>Daftar</a>";
                        }
                        ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php require_once 'templates/footer_mahasiswa.php'; ?>

</body>
</html>
