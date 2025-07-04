<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'asisten') {
    header("Location: ../login.php");
    exit;
}

include '../config.php';
include 'templates/header.php';

// Handle penilaian
if (isset($_POST['nilai'])) {
    $id_laporan = $_POST['id_laporan'];
    $nilai = $_POST['nilai_angka'];
    $feedback = $_POST['feedback'];

    mysqli_query($conn, "UPDATE laporan SET nilai = '$nilai', feedback = '$feedback' WHERE id = $id_laporan");
    header("Location: manage_laporan.php");
}

// Export ke Excel
if (isset($_GET['export']) && $_GET['export'] === 'excel') {
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=laporan_mahasiswa.xls");

    echo "<table border='1'>";
    echo "<tr><th>Mahasiswa</th><th>Modul</th><th>File</th><th>Nilai</th><th>Feedback</th></tr>";

    $query = "SELECT laporan.*, users.nama AS mahasiswa, modul.nama_modul
              FROM laporan
              JOIN users ON laporan.id_mahasiswa = users.id
              JOIN modul ON laporan.id_modul = modul.id";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['mahasiswa']}</td>
                <td>{$row['nama_modul']}</td>
                <td>{$row['file_laporan']}</td>
                <td>{$row['nilai']}</td>
                <td>{$row['feedback']}</td>
              </tr>";
    }
    echo "</table>";
    exit;
}

$where = [];
if (!empty($_GET['modul'])) $where[] = "modul.id = " . intval($_GET['modul']);
if (!empty($_GET['mahasiswa'])) $where[] = "users.id = " . intval($_GET['mahasiswa']);
if (!empty($_GET['status'])) {
    if ($_GET['status'] == 'belum') $where[] = "laporan.nilai IS NULL";
    else if ($_GET['status'] == 'sudah') $where[] = "laporan.nilai IS NOT NULL";
}
$whereSql = $where ? "WHERE " . implode(" AND ", $where) : "";
?>

<div class="p-6 max-w-7xl mx-auto">
    <h2 class="text-3xl font-bold mb-6 text-gray-800">📄 Manajemen Laporan Mahasiswa</h2>

    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 bg-white p-4 rounded shadow">
        <!-- Filter Modul -->
        <select name="modul" class="border p-2 rounded">
            <option value="">-- Semua Modul --</option>
            <?php
            $modulList = mysqli_query($conn, "SELECT * FROM modul");
            while ($m = mysqli_fetch_assoc($modulList)) {
                $sel = (isset($_GET['modul']) && $_GET['modul'] == $m['id']) ? 'selected' : '';
                echo "<option value='{$m['id']}' $sel>{$m['nama_modul']}</option>";
            }
            ?>
        </select>
        <!-- Filter Mahasiswa -->
        <select name="mahasiswa" class="border p-2 rounded">
            <option value="">-- Semua Mahasiswa --</option>
            <?php
            $mahasiswaList = mysqli_query($conn, "SELECT * FROM users WHERE role = 'mahasiswa'");
            while ($m = mysqli_fetch_assoc($mahasiswaList)) {
                $sel = (isset($_GET['mahasiswa']) && $_GET['mahasiswa'] == $m['id']) ? 'selected' : '';
                echo "<option value='{$m['id']}' $sel>{$m['nama']}</option>";
            }
            ?>
        </select>
        <!-- Status -->
        <select name="status" class="border p-2 rounded">
            <option value="">-- Status --</option>
            <option value="belum" <?= ($_GET['status'] ?? '') == 'belum' ? 'selected' : '' ?>>Belum Dinilai</option>
            <option value="sudah" <?= ($_GET['status'] ?? '') == 'sudah' ? 'selected' : '' ?>>Sudah Dinilai</option>
        </select>
        <div class="flex gap-2">
            <button class="bg-blue-600 text-white px-4 py-2 rounded">Filter</button>
            <a href="?export=excel" class="bg-green-600 text-white px-4 py-2 rounded">Export Excel</a>
        </div>
    </form>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="w-full text-sm text-left border-collapse">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 font-medium text-gray-700">Mahasiswa</th>
                    <th class="px-6 py-3 font-medium text-gray-700">Modul</th>
                    <th class="px-6 py-3 font-medium text-gray-700">File</th>
                    <th class="px-6 py-3 font-medium text-gray-700">Nilai</th>
                    <th class="px-6 py-3 font-medium text-gray-700">Feedback</th>
                    <th class="px-6 py-3 font-medium text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT laporan.*, users.nama AS mahasiswa, modul.nama_modul 
                          FROM laporan 
                          JOIN users ON laporan.id_mahasiswa = users.id 
                          JOIN modul ON laporan.id_modul = modul.id
                          $whereSql
                          ORDER BY laporan.id DESC";
                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) == 0) {
                    echo "<tr><td colspan='6' class='text-center px-6 py-4 text-gray-500'>Tidak ada laporan ditemukan.</td></tr>";
                }

                while ($row = mysqli_fetch_assoc($result)) {
                ?>
                    <tr class="border-t hover:bg-gray-50 transition">
                        <td class="px-6 py-4"><?= $row['mahasiswa'] ?></td>
                        <td class="px-6 py-4"><?= $row['nama_modul'] ?></td>
                        <td class="px-6 py-4">
                            <a href="../laporan/<?= $row['file_laporan'] ?>" target="_blank" class="text-blue-600 underline">📅 Unduh</a>
                        </td>
                        <td class="px-6 py-4"><?= $row['nilai'] ?? '-' ?></td>
                        <td class="px-6 py-4"><?= $row['feedback'] ?? '-' ?></td>
                        <td class="px-6 py-4">
                            <form method="POST" class="grid gap-2">
                                <input type="hidden" name="id_laporan" value="<?= $row['id'] ?>">
                                <input type="number" name="nilai_angka" placeholder="Nilai" value="<?= $row['nilai'] ?>" required class="border rounded px-2 py-1 w-full">
                                <input type="text" name="feedback" placeholder="Feedback" value="<?= $row['feedback'] ?>" class="border rounded px-2 py-1 w-full">
                                <button name="nilai" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded transition">📂 Simpan</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'templates/footer.php'; ?>
