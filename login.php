<?php
session_start();
include 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $selected_role = $_POST['role']; // Role dipilih user

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    $user = mysqli_fetch_assoc($query);

    // Validasi login dan role
    if ($user && password_verify($password, $user['password'])) {
        if ($user['role'] === $selected_role) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['nama'] = $user['nama'];

            // Redirect sesuai role
            if ($user['role'] == 'asisten') {
                header("Location: asisten/dashboard.php");
            } elseif ($user['role'] == 'mahasiswa') {
                header("Location: mahasiswa/dashboard.php");
            }
            exit;
        } else {
            $error = "Role yang dipilih tidak sesuai!";
        }
    } else {
        $error = "Email atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login | Sistem Praktikum</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }
  </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-100 to-white flex items-center justify-center">

  <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-md">
    <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Login Sistem Praktikum</h2>

    <?php if (!empty($error)) : ?>
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
        <?= $error ?>
      </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
      <div>
        <label class="block text-sm text-gray-600 mb-1">Email</label>
        <input type="email" name="email" required class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="you@example.com">
      </div>
      <div>
        <label class="block text-sm text-gray-600 mb-1">Password</label>
        <input type="password" name="password" required class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="••••••••">
      </div>
      <div>
        <label class="block text-sm text-gray-600 mb-1">Login Sebagai</label>
        <select name="role" required class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
          <option value="">-- Pilih Role --</option>
          <option value="mahasiswa">Mahasiswa</option>
          <option value="asisten">Asisten</option>
        </select>
      </div>
      <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded font-semibold transition">Login</button>
    </form>

    <p class="text-center text-sm text-gray-500 mt-4">Belum punya akun? Hubungi admin</p>
  </div>

</body>
</html>
