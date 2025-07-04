<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'mahasiswa') {
    header("Location: ../login.php");
    exit;
}

include '../config.php'; 


$id_praktikum = $_GET['id'];
$id_mahasiswa = $_SESSION['user_id'];

$modul = mysqli_query($conn, "SELECT * FROM modul WHERE id_praktikum = $id_praktikum");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Detail Praktikum</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 text-gray-800 p-8">
  <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-lg">
    <h1 class="text-3xl font-bold mb-6 border-b pb-2">📘 Modul Praktikum</h1>

    <div class="mb-4">
      <a href="praktikum_saya.php" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
          </svg>
            Kembali ke Praktikum Saya
      </a>
    </div>


    <div class="overflow-x-auto">
      <table class="w-full border-collapse">
        <thead>
          <tr class="bg-blue-50 text-blue-800">
            <th class="px-6 py-3 text-left text-sm font-semibold border-b">Nama Modul</th>
            <th class="px-6 py-3 text-left text-sm font-semibold border-b">Materi</th>
            <th class="px-6 py-3 text-left text-sm font-semibold border-b">Laporan</th>
            <th class="px-6 py-3 text-left text-sm font-semibold border-b">Nilai</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($m = mysqli_fetch_assoc($modul)) {
            $id_modul = $m['id'];
            $laporan = mysqli_query($conn, "SELECT * FROM laporan WHERE id_mahasiswa = $id_mahasiswa AND id_modul = $id_modul LIMIT 1");
            $data = mysqli_fetch_assoc($laporan);
          ?>
          <tr class="hover:bg-gray-50 transition">
            <td class="px-6 py-4 border-b"><?= htmlspecialchars($m['nama_modul']) ?></td>
            <td class="px-6 py-4 border-b">
              <a href="../materi/<?= urlencode($m['file_materi']) ?>" class="text-blue-600 hover:underline" target="_blank">Download</a>
            </td>
            <td class="px-6 py-4 border-b">
              <?php if ($data): ?>
                <span class="inline-flex items-center gap-1 text-green-600">
                  ✅ <a href="../laporan/<?= urlencode($data['file_laporan']) ?>" target="_blank" class="underline">Lihat</a>
                </span>
              <?php else: ?>
                <form method="POST" action="upload_laporan.php" enctype="multipart/form-data" class="flex items-center gap-2 text-sm">
                  <input type="hidden" name="id_modul" value="<?= $m['id'] ?>">
                  <input type="file" name="file_laporan" class="text-sm border rounded px-2 py-1" required>
                  <button class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">Upload</button>
                </form>
              <?php endif; ?>
            </td>
            <td class="px-6 py-4 border-b text-center font-semibold">
              <?= $data['nilai'] ?? '<span class="text-gray-400">-</span>' ?>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</body>

</html>