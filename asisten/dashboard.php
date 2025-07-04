<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'asisten') {
    header("Location: ../login.php");
    exit;
}

include '../config.php';
include 'templates/header.php';

$modulCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM modul"))['total'];
$laporanCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM laporan"))['total'];
$belumCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM laporan WHERE nilai IS NULL"))['total'];
?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-white p-6 rounded-lg shadow-md flex items-center space-x-4">
        <div class="bg-blue-100 p-3 rounded-full">
            <svg class="w-6 h-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
            </svg>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Modul Diajarkan</p>
            <p class="text-2xl font-bold text-gray-800"><?= $modulCount ?></p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md flex items-center space-x-4">
        <div class="bg-green-100 p-3 rounded-full">
            <svg class="w-6 h-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Laporan Masuk</p>
            <p class="text-2xl font-bold text-gray-800"><?= $laporanCount ?></p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md flex items-center space-x-4">
        <div class="bg-yellow-100 p-3 rounded-full">
            <svg class="w-6 h-6 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div>
            <p class="text-sm text-gray-500">Laporan Belum Dinilai</p>
            <p class="text-2xl font-bold text-gray-800"><?= $belumCount ?></p>
        </div>
    </div>
</div>


<div class="bg-white p-6 rounded-lg shadow-md">
    <h3 class="text-xl font-bold text-gray-800 mb-4">📄 Aktivitas Laporan Terbaru</h3>
    <div class="space-y-4">
        <?php

        $recent = mysqli_query($conn, "
            SELECT laporan.created_at, users.nama AS mahasiswa, modul.nama_modul
            FROM laporan
            JOIN users ON laporan.id_mahasiswa = users.id
            JOIN modul ON laporan.id_modul = modul.id
            ORDER BY laporan.created_at DESC
            LIMIT 5
        ");

        while ($row = mysqli_fetch_assoc($recent)) {
            $inisial = strtoupper(substr($row['mahasiswa'], 0, 1));
            echo "
            <div class='flex items-center'>
                <div class='w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center mr-4'>
                    <span class='font-bold text-gray-500'>$inisial</span>
                </div>
                <div>
                    <p class='text-gray-800'><strong>{$row['mahasiswa']}</strong> mengumpulkan laporan untuk <strong>{$row['nama_modul']}</strong></p>
                    <p class='text-sm text-gray-500'>Baru saja</p>
                </div>
            </div>";
        }
        ?>
    </div>
</div>

<?php include 'templates/footer.php'; ?>
