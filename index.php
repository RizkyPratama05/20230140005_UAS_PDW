<?php
session_start();
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'asisten') {
        header("Location: asisten/dashboard.php");
    } else {
        header("Location: mahasiswa/dashboard.php");
    }
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Selamat Datang di SIMPRAK</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
    <div class="bg-white p-8 rounded shadow-md text-center">
        <h1 class="text-2xl font-bold mb-4">Selamat Datang di SIMPRAK</h1>
        <p class="mb-6">Silakan login untuk melanjutkan.</p>
        <a href="login.php" class="bg-blue-500 text-white px-4 py-2 rounded">Login</a>
    </div>
</body>
</html>
