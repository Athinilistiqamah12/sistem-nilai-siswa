<?php
session_start();
include 'koneksi.php';
if (!isset($_SESSION['ID_USER']) || $_SESSION['TIPE'] != 1) { header('location:formlogin.php'); exit(); }

$id = $_GET['idnya'] ?? '';
$q = mysqli_prepare($koneksi, "SELECT FOTO FROM siswa WHERE ID_SISWA = ?");
mysqli_stmt_bind_param($q, "s", $id);
mysqli_stmt_execute($q);
$r = mysqli_fetch_assoc(mysqli_stmt_get_result($q));

if ($r) {
    $q = mysqli_prepare($koneksi, "DELETE FROM nilai WHERE ID_SISWA = ?");
    mysqli_stmt_bind_param($q, "s", $id);
    mysqli_stmt_execute($q);
    $q = mysqli_prepare($koneksi, "DELETE FROM siswa WHERE ID_SISWA = ?");
    mysqli_stmt_bind_param($q, "s", $id);
    mysqli_stmt_execute($q);
    if ($r['FOTO'] !== '' && file_exists(__DIR__ . '/gambar/' . $r['FOTO'])) unlink(__DIR__ . '/gambar/' . $r['FOTO']);
}
header('location:homeguru.php?pg=forminputsiswa');