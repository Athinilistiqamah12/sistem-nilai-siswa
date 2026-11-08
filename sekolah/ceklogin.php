<?php
session_start();
if (!isset($_SESSION['TIPE'])) {
    header('location:formlogin.php');
    exit();
}
if ($_SESSION['TIPE'] == 1) {
    header('location:homeguru.php');
} else {
    header('location:homesiswa.php');
}