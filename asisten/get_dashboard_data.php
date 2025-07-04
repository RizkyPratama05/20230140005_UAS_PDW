<?php
header('Content-Type: application/json');
include '../config.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Pastikan koneksi berhasil
if (!$conn) {
    echo json_encode(['error' => 'Gagal koneksi']);
    exit;
}

function waktuRelatif($timestamp) {
    $selisih = time() - strtotime($timestamp);
    if ($selisih < 60) return $selisih . " detik lalu";
    elseif ($selisih < 3600) return floor($selisih / 60) . " menit lalu";
    elseif ($selisih < 86400) return floor($selisih / 3600) . " jam lalu";
    else return floor($selisih / 86400) . " hari lalu";
}

$total_modul = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM modul"))[0];
$total_laporan = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM laporan"))[0];
$laporan_belum = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM laporan WHERE nilai IS NULL"))[0];

$recent = mysqli_query($conn, "
    SELECT users.nama, modul.nama_modul, laporan.created_at 
    FROM laporan
    JOIN users ON laporan.id_mahasiswa = users.id
    JOIN modul ON laporan.id_modul = modul.id
    ORDER BY laporan.created_at DESC LIMIT 5
");

$aktivitas = [];
while ($r = mysqli_fetch_assoc($recent)) {
    $aktivitas[] = [
        'nama' => $r['nama'],
        'nama_modul' => $r['nama_modul'],
        'waktu' => waktuRelatif($r['created_at'])
    ];
}

echo json_encode([
    'total_modul' => $total_modul,
    'total_laporan' => $total_laporan,
    'laporan_belum' => $laporan_belum,
    'aktivitas' => $aktivitas
]);
