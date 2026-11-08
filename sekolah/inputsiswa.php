<?php
session_start();
include 'koneksi.php';
if (!isset($_SESSION['ID_USER']) || $_SESSION['TIPE'] != 1) { header('location:formlogin.php'); exit(); }

$nisn = trim($_POST['nisn'] ?? '');
$nama = trim($_POST['nama'] ?? '');
$email = strtolower(trim($_POST['email'] ?? ''));
$kelas = $_POST['kelas'] ?? '';
if ($nisn === '' || $nama === '') alertKembali('ID dan nama wajib diisi');

[$ok, $foto] = simpanFoto($_FILES['fo'] ?? []);
if (!$ok) alertKembali($foto);

try {
    $q = mysqli_prepare($koneksi, "INSERT INTO siswa (ID_SISWA, NAMA_SISWA, KELAS, FOTO, EMAIL) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($q, "sssss", $nisn, $nama, $kelas, $foto, $email);
    mysqli_stmt_execute($q);
} catch (mysqli_sql_exception $ex) {
    alertKembali('Gagal menyimpan (ID mungkin sudah dipakai): ' . $ex->getMessage());
}
header('location:homeguru.php?pg=forminputsiswa');