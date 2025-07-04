<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'asisten') {
    header("Location: ../login.php");
    exit;
}

include '../config.php';
include 'templates/header.php';

// CREATE
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $semester = $_POST['semester'];
    mysqli_query($conn, "INSERT INTO mata_praktikum (nama, semester) VALUES ('$nama', '$semester')");
    header("Location: manage_praktikum.php");
}

// UPDATE
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $semester = $_POST['semester'];
    mysqli_query($conn, "UPDATE mata_praktikum SET nama='$nama', semester='$semester' WHERE id=$id");
    header("Location: manage_praktikum.php");
}

// DELETE
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM mata_praktikum WHERE id=$id");
    header("Location: manage_praktikum.php");
}
?>

<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Kelola Mata Praktikum</h1>

    <!-- Form Tambah -->
    <form method="POST" class="mb-4 flex gap-2">
        <input type="text" name="nama" placeholder="Nama Praktikum" class="border p-2 rounded" required>
        <input type="text" name="semester" placeholder="Semester" class="border p-2 rounded" required>
        <button type="submit" name="tambah" class="bg-blue-500 text-white px-4 py-2 rounded">Tambah</button>
    </form>

    <!-- Tabel Praktikum -->
    <table class="table-auto w-full border">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2 border">ID</th>
                <th class="px-4 py-2 border">Nama</th>
                <th class="px-4 py-2 border">Semester</th>
                <th class="px-4 py-2 border">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = mysqli_query($conn, "SELECT * FROM mata_praktikum");
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <tr>
                <td class="px-4 py-2 border"><?= $row['id'] ?></td>
                <td class="px-4 py-2 border">
                    <form method="POST" class="flex gap-2">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <input type="text" name="nama" value="<?= $row['nama'] ?>" class="border p-1 rounded w-full">
                </td>
                <td class="px-4 py-2 border">
                        <input type="text" name="semester" value="<?= $row['semester'] ?>" class="border p-1 rounded w-full">
                </td>
                <td class="px-4 py-2 border">
                        <button type="submit" name="update" class="text-green-600">✔</button>
                        <a href="?delete=<?= $row['id'] ?>" class="text-red-600 ml-2" onclick="return confirm('Hapus?')">✖</a>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include 'templates/footer.php'; ?>
