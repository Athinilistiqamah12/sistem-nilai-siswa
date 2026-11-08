<?php
session_start();
include 'koneksi.php';
if (!isset($_SESSION['ID_USER']) || $_SESSION['TIPE'] != 1) { header('location:formlogin.php'); exit(); }

$id = $_POST['id'] ?? '';
$nama = trim($_POST['nama'] ?? '');
$email = strtolower(trim($_POST['email'] ?? ''));
$kelas = $_POST['kelas'] ?? '';
$hapus = isset($_POST['hapus_foto']);
if ($nama === '') alertKembali('Nama wajib diisi');

$q = mysqli_prepare($koneksi, "SELECT FOTO FROM siswa WHERE ID_SISWA = ?");
mysqli_stmt_bind_param($q, "s", $id);
mysqli_stmt_execute($q);
$lama = mysqli_fetch_assoc(mysqli_stmt_get_result($q));
if (!$lama) alertKembali('Siswa tidak ditemukan');

[$ok, $foto] = simpanFoto($_FILES['fo'] ?? []);
if (!$ok) alertKembali($foto);

if ($foto !== '') {                       // ganti foto
    $q = mysqli_prepare($koneksi, "UPDATE siswa SET NAMA_SISWA = ?, KELAS = ?, EMAIL = ?, FOTO = ? WHERE ID_SISWA = ?");
    mysqli_stmt_bind_param($q, "sssss", $nama, $kelas, $email, $foto, $id);
} elseif ($hapus) {                       // hapus foto saja
    $kosong = '';
    $q = mysqli_prepare($koneksi, "UPDATE siswa SET NAMA_SISWA = ?, KELAS = ?, EMAIL = ?, FOTO = ? WHERE ID_SISWA = ?");
    mysqli_stmt_bind_param($q, "sssss", $nama, $kelas, $email, $kosong, $id);
} else {                                  // foto tidak diubah
    $q = mysqli_prepare($koneksi, "UPDATE siswa SET NAMA_SISWA = ?, KELAS = ?, EMAIL = ? WHERE ID_SISWA = ?");
    mysqli_stmt_bind_param($q, "ssss", $nama, $kelas, $email, $id);
}
if (!mysqli_stmt_execute($q)) alertKembali('Gagal mengubah: ' . mysqli_stmt_error($q));

// hapus file foto lama dari folder gambar
if (($foto !== '' || $hapus) && $lama['FOTO'] !== '' && file_exists(__DIR__ . '/gambar/' . $lama['FOTO'])) {
    unlink(__DIR__ . '/gambar/' . $lama['FOTO']);
}
header('location:homeguru.php?pg=forminputsiswa');