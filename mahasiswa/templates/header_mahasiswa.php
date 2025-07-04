<?php
if (session_status() == PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    header("Location: ../login.php");
    exit;
}

$pageTitle = $pageTitle ?? 'SIMPRAK - Mahasiswa';
$activePage = $activePage ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $pageTitle ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<!-- Navbar Mahasiswa -->
<nav class="bg-blue-600 text-white px-6 py-4 flex justify-between items-center">
    <div class="font-bold text-xl">SIMPRAK</div>
    <ul class="flex gap-6">
        <li><a href="dashboard.php" class="<?= $activePage == 'dashboard' ? 'font-semibold underline' : '' ?>">Dashboard</a></li>
        <li><a href="praktikum_saya.php" class="<?= $activePage == 'praktikum' ? 'font-semibold underline' : '' ?>">Praktikum Saya</a></li>
        <li><a href="cari_praktikum.php" class="<?= $activePage == 'cari' ? 'font-semibold underline' : '' ?>">Cari Praktikum</a></li>
    </ul>
    <a href="../logout.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Logout</a>
</nav>

<main class="p-6 max-w-5xl mx-auto">
