<?php
include 'koneksi.php';
mysqli_query($koneksi, "INSERT INTO logging(KUNJUNGAN) VALUES ('kunjung')");
header('location:formlogin.php');