<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'asisten') {
    header("Location: ../login.php");
    exit;
}

include '../config.php';
include 'templates/header.php';

// Handle Tambah Modul
if (isset($_POST['tambah'])) {
    $nama_modul = $_POST['nama_modul'];
    $id_praktikum = $_POST['id_praktikum'];

    $fileName = $_FILES['file_materi']['name'];
    $fileTmp = $_FILES['file_materi']['tmp_name'];
    $targetPath = "../materi/" . $fileName;

    if (move_uploaded_file($fileTmp, $targetPath)) {
        mysqli_query($conn, "INSERT INTO modul (id_praktikum, nama_modul, file_materi) 
                             VALUES ('$id_praktikum', '$nama_modul', '$fileName')");
        header("Location: manage_modul.php");
    } else {
        echo "Upload gagal!";
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM modul WHERE id = $id");
    header("Location: manage_modul.php");
}
?>

<div class="p-6">
    <h2 class="text-2xl font-semibold mb-4">Kelola Modul</h2>

    <!-- Form Tambah Modul -->
    <form method="POST" enctype="multipart/form-data" class="mb-6 flex flex-col md:flex-row gap-3">
        <select name="id_praktikum" class="border p-2 rounded" required>
            <option value="">Pilih Praktikum</option>
            <?php
            $praktikum = mysqli_query($conn, "SELECT * FROM mata_praktikum");   
            while ($p = mysqli_fetch_assoc($praktikum)) {
                echo "<option value='{$p['id']}'>{$p['nama']} ({$p['semester']})</option>";
            }
            ?>
        </select>
        <input type="text" name="nama_modul" placeholder="Nama Modul" class="border p-2 rounded" required>
        <input type="file" name="file_materi" accept=".pdf,.docx" class="border p-2 rounded" required>
        <button name="tambah" class="bg-green-600 text-white px-4 py-2 rounded">Tambah</button>
    </form>

    <!-- Daftar Modul -->
    <table class="w-full table-auto border">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2 border">Praktikum</th>
                <th class="px-4 py-2 border">Nama Modul</th>
                <th class="px-4 py-2 border">File Materi</th>
                                <th class="px-4 py-2 border">Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $query = "SELECT modul.*, mata_praktikum.nama AS nama_praktikum 
                  FROM modul 
                  JOIN mata_praktikum ON modul.id_praktikum = mata_praktikum.id";
        $result = mysqli_query($conn, $query);
        while ($modul = mysqli_fetch_assoc($result)) {
        ?>
            <tr>
                <td class="border px-4 py-2"><?= $modul['nama_praktikum'] ?></td>
                <td class="border px-4 py-2"><?= $modul['nama_modul'] ?></td>
                <td class="border px-4 py-2">
                    <a href="../materi/<?= $modul['file_materi'] ?>" target="_blank" class="text-blue-500 underline">
                        <?= $modul['file_materi'] ?>
                    </a>
                </td>
                <td class="border px-4 py-2">
                    <a href="?delete=<?= $modul['id'] ?>" onclick="return confirm('Yakin hapus modul ini?')" class="text-red-600">
                        Hapus
                    </a>
                     <a href="edit_modul.php?id=<?= $modul['id'] ?>" class="text-blue-600 underline ml-2">Edit</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<?php include 'templates/footer.php'; ?>

