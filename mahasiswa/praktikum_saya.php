<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'mahasiswa') {
    header("Location: ../login.php");
    exit;
}

require_once '../config.php';
$pageTitle = 'Praktikum Saya';
include 'templates/header_mahasiswa.php'; // jika punya header mahasiswa

$user_id = $_SESSION['user_id'];

$query = "SELECT mp.nama, mp.semester, mp.id
          FROM pendaftaran p
          JOIN mata_praktikum mp ON p.id_praktikum = mp.id
          WHERE p.id_mahasiswa = $user_id";

$result = mysqli_query($conn, $query);
?>

<h2 class="text-2xl font-bold mb-4">Praktikum yang Kamu Ikuti</h2>

<table class="w-full table-auto border">
    <thead class="bg-gray-100">
        <tr>
            <th class="border px-4 py-2">Nama Praktikum</th>
            <th class="border px-4 py-2">Semester</th>
            <th class="border px-4 py-2">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td class="border px-4 py-2"><?= $row['nama'] ?></td>
            <td class="border px-4 py-2"><?= $row['semester'] ?></td>
           <td class="border px-4 py-2">
                <a href="modul_saya.php?id_praktikum=<?= $row['id'] ?>" class="text-blue-600 underline">Lihat Modul</a> |
                <a href="detail_praktikum.php?id=<?= $row['id'] ?>" class="text-blue-600 underline">Lihat Detail</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include 'templates/footer_mahasiswa.php'; ?>
