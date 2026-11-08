<?php
session_start();
include 'koneksi.php';
$u = $_POST['username'] ?? '';
$p = $_POST['password'] ?? '';

$q = mysqli_prepare($koneksi, "SELECT * FROM login WHERE USERNAME = ?");
mysqli_stmt_bind_param($q, "s", $u);
mysqli_stmt_execute($q);
$d = mysqli_fetch_assoc(mysqli_stmt_get_result($q));

if ($d && password_verify($p, $d['PASSWORD'])) {
    session_regenerate_id(true);
    $_SESSION['ID_USER'] = $d['ID_USER'];
    $_SESSION['USERNAME'] = $d['USERNAME'];
    $_SESSION['TIPE'] = $d['TIPE'];
    header('location:ceklogin.php');
} else {
    header('location:formlogin.php?error=1');
}