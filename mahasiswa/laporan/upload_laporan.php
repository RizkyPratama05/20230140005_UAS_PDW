<?php
session_start();
include 'config.php';

$id_mahasiswa = $_SESSION['user_id'];
$id_modul = $_POST['id_modul'];
$fileName = $_FILES['file_laporan']['name'];
$tmpName = $_FILES['file_laporan']['tmp_name'];
$target = '../laporan/' . $fileName;

if (move_uploaded_file($tmpName, $target)) {
  mysqli_query($conn, "INSERT INTO laporan (id_mahasiswa, id_modul, file_laporan) VALUES ($id_mahasiswa, $id_modul, '$fileName')");
}
header("Location: mahasiswa/detail_praktikum.php?id=$id_modul");


