<?php
session_start();
include 'config.php';

$query = mysqli_query($conn, "SELECT * FROM mata_praktikum");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Katalog Praktikum</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
  <h1 class="text-3xl font-bold mb-6">Katalog Mata Praktikum</h1>
  <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
    <?php while ($row = mysqli_fetch_assoc($query)) { ?>
      <div class="bg-white p-4 rounded shadow">
        <h2 class="text-xl font-semibold"><?= $row['nama'] ?></h2>
        <p class="text-gray-500">Semester: <?= $row['semester'] ?></p>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'mahasiswa') { ?>
          <form method="POST" action="daftar_praktikum.php">
            <input type="hidden" name="id_praktikum" value="<?= $row['id'] ?>">
            <button class="bg-blue-500 text-white px-4 py-2 mt-2 rounded">Daftar</button>
          </form>
        <?php } ?>
      </div>
    <?php } ?>
  </div>
</body>
</html>
