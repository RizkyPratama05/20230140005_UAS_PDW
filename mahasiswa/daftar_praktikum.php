<?php
session_start();
include 'config.php';

if ($_SESSION['role'] != 'mahasiswa') {
  header('Location: login.php'); exit;
}

$id_mahasiswa = $_SESSION['user_id'];
$id_praktikum = $_POST['id_praktikum'];

// Cek jika sudah terdaftar
$cek = mysqli_query($conn, "SELECT * FROM praktikum_mahasiswa WHERE id_mahasiswa=$id_mahasiswa AND id_praktikum=$id_praktikum");
if (mysqli_num_rows($cek) == 0) {
    mysqli_query($conn, "INSERT INTO praktikum_mahasiswa (id_mahasiswa, id_praktikum) VALUES ($id_mahasiswa, $id_praktikum)");
}

header('Location: mahasiswa/praktikum_saya.php');
