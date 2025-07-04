<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'asisten') {
    header("Location: ../login.php");
    exit;
}

include '../config.php';
include 'templates/header.php';

if (!isset($_GET['id'])) {
    echo "Modul tidak ditemukan.";
    exit;
}

$id = intval($_GET['id']);
$query = mysqli_query($conn, "SELECT * FROM modul WHERE id = $id");
$modul = mysqli_fetch_assoc($query);

if (!$modul) {
    echo "Data tidak ditemukan.";
    exit;
}

if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_modul']);

    // Handle file jika diunggah
    if (!empty($_FILES['file_materi']['name'])) {
        $file = $_FILES['file_materi']['name'];
        $tmp = $_FILES['file_materi']['tmp_name'];
        move_uploaded_file($tmp, "../materi/$file");

        $sql = "UPDATE modul SET nama_modul='$nama', file_materi='$file' WHERE id=$id";
    } else {
        $sql = "UPDATE modul SET nama_modul='$nama' WHERE id=$id";
    }

    if (mysqli_query($conn, $sql)) {
        header("Location: manage_modul.php");
        exit;
    } else {
        echo "Gagal update modul: " . mysqli_error($conn);
    }
}
?>

<div class="p-6 max-w-xl mx-auto bg-white shadow rounded">
    <h2 class="text-2xl font-bold mb-4">Edit Modul</h2>
    <form method="POST" enctype="multipart/form-data" class="space-y-4">
        <div>
            <label class="block text-sm">Nama Modul</label>
            <input type="text" name="nama_modul" value="<?= htmlspecialchars($modul['nama_modul']) ?>" required class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <label class="block text-sm">File Materi (opsional)</label>
            <input type="file" name="file_materi" class="w-full">
            <?php if (!empty($modul['file_materi'])): ?>
                <p class="text-sm mt-1">📎 File saat ini: <?= $modul['file_materi'] ?></p>
            <?php endif; ?>
        </div>

        <div>
            <button type="submit" name="update" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">💾 Simpan Perubahan</button>
        </div>
    </form>
</div>

<?php include 'templates/footer.php'; ?>
