<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'mahasiswa') {
    header("Location: ../login.php");
    exit;
}

include '../config.php';

$pageTitle = 'Modul Saya';
$activePage = 'modul';
include 'templates/header_mahasiswa.php'; // kalau kamu punya header khusus mahasiswa

$user_id = $_SESSION['user_id'];

// Handle upload laporan
if (isset($_POST['upload'])) {
    $id_modul = $_POST['id_modul'];
    $fileName = $_FILES['laporan']['name'];
    $fileTmp = $_FILES['laporan']['tmp_name'];
    $targetPath = "../laporan/" . $fileName;

    if (move_uploaded_file($fileTmp, $targetPath)) {
        mysqli_query($conn, "INSERT INTO laporan (id_modul, id_mahasiswa, file_laporan) 
                             VALUES ('$id_modul', '$user_id', '$fileName')");
        echo "<p class='text-green-600'>Laporan berhasil diupload!</p>";
    } else {
        echo "<p class='text-red-600'>Gagal upload laporan!</p>";
    }
}
?>

<h2 class="text-2xl font-bold mb-4">Modul Praktikum Saya</h2>

<?php
// Ambil semua modul
$query = "SELECT modul.*, mata_praktikum.nama AS nama_praktikum
          FROM modul 
          JOIN mata_praktikum ON modul.id_praktikum = mata_praktikum.id";
$result = mysqli_query($conn, $query);

while ($modul = mysqli_fetch_assoc($result)) {
    // Cek apakah mahasiswa sudah pernah upload laporan
    $id_modul = $modul['id'];
    $laporan = mysqli_query($conn, "SELECT * FROM laporan WHERE id_modul = $id_modul AND id_mahasiswa = $user_id");
    $data_laporan = mysqli_fetch_assoc($laporan);
?>

         <div class="mb-4">
      <a href="praktikum_saya.php" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
          </svg>
            Kembali ke Praktikum Saya
      </a>
    </div>
    
    <div class="bg-white rounded shadow p-4 mb-6">
        <h3 class="text-xl font-semibold mb-2"><?= $modul['nama_modul'] ?> (<?= $modul['nama_praktikum'] ?>)</h3>

        <p class="mb-2">
            📄 <a href="../materi/<?= $modul['file_materi'] ?>" class="text-blue-600 underline" target="_blank">Download Materi</a>
        </p>

        <?php if ($data_laporan): ?>
            <p class="mb-1">✅ Laporan sudah dikumpulkan: <strong><?= $data_laporan['file_laporan'] ?></strong></p>
            <p>📊 Nilai: <strong><?= $data_laporan['nilai'] ?? 'Belum dinilai' ?></strong></p>
            <p>📝 Feedback: <?= $data_laporan['feedback'] ?? '-' ?></p>
        <?php else: ?>
            <form method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row gap-2 items-center">
                <input type="hidden" name="id_modul" value="<?= $modul['id'] ?>">
                <input type="file" name="laporan" accept=".pdf,.docx" required class="border p-1 rounded">
                <button name="upload" class="bg-blue-600 text-white px-4 py-2 rounded">Upload Laporan</button>
            </form>
        <?php endif; ?>
    </div>
<?php } ?>

<?php include 'templates/footer_mahasiswa.php'; ?>
