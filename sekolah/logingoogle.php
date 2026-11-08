<?php
session_start();
include 'koneksi.php';
if (!defined('GOOGLE_CLIENT_ID')) { header('location:formlogin.php?error=2'); exit(); }

$token = $_POST['credential'] ?? '';
$res = @file_get_contents('https://oauth2.googleapis.com/tokeninfo?id_token=' . urlencode($token));
$g = json_decode((string)$res, true);

if (!$g || ($g['aud'] ?? '') !== GOOGLE_CLIENT_ID || ($g['email_verified'] ?? '') !== 'true') {
    header('location:formlogin.php?error=2'); exit();
}
$email = strtolower($g['email']);
$q = mysqli_prepare($koneksi, "SELECT ID_SISWA, NAMA_SISWA FROM siswa WHERE LOWER(EMAIL) = ?");
mysqli_stmt_bind_param($q, "s", $email);
mysqli_stmt_execute($q);
$s = mysqli_fetch_assoc(mysqli_stmt_get_result($q));
if (!$s) { header('location:formlogin.php?error=3'); exit(); }

session_regenerate_id(true);
$_SESSION['ID_USER'] = $s['ID_SISWA'];
$_SESSION['USERNAME'] = $s['NAMA_SISWA'];
$_SESSION['TIPE'] = 2;
header('location:homesiswa.php');