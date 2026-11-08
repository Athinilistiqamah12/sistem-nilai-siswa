<?php
session_start();
include 'koneksi.php';
if (!isset($_SESSION['ID_USER']) || $_SESSION['TIPE'] != 1) { header('location:formlogin.php'); exit(); }

$id = $_POST['siswa'] ?? '';
if ($id === '') alertKembali('Pilih siswa dulu');
$mtk = max(0, min(100, (int)$_POST['matematika']));
$ind = max(0, min(100, (int)$_POST['bahasa_indonesia']));
$ing = max(0, min(100, (int)$_POST['bahasa_inggris']));
$tkj = max(0, min(100, (int)$_POST['teori_kejuruan']));

$q = mysqli_prepare($koneksi, "INSERT INTO nilai (ID_SISWA, matematika, bahasa_indonesia, bahasa_inggris, teori_kejuruan)
    VALUES (?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE matematika = VALUES(matematika), bahasa_indonesia = VALUES(bahasa_indonesia),
    bahasa_inggris = VALUES(bahasa_inggris), teori_kejuruan = VALUES(teori_kejuruan)");
mysqli_stmt_bind_param($q, "siiii", $id, $mtk, $ind, $ing, $tkj);
if (!mysqli_stmt_execute($q)) alertKembali('Gagal menyimpan nilai: ' . mysqli_stmt_error($q));
header('location:homeguru.php?pg=forminputnilai');