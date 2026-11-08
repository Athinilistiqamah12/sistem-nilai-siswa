<?php
session_start();
include 'koneksi.php';
if (!isset($_SESSION['ID_USER']) || $_SESSION['TIPE'] != 1) {
    echo "<script>alert('ANDA HARUS LOGIN SEBAGAI GURU UNTUK MENGAKSES HALAMAN INI');document.location='formlogin.php';</script>";
    exit();
}
$boleh = ['forminputsiswa', 'formeditsiswa', 'forminputnilai', 'formpencarian', 'laporan'];
$pg = $_GET['pg'] ?? 'home';
$menu = ($pg == 'formeditsiswa') ? 'forminputsiswa' : $pg;
function aktif($a, $m) { return $a == $m ? 'active' : ''; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Dashboard Guru</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="topbar">
  <div class="brand">Nilai<span>Siswa</span></div>
  <div class="user">Halo, <?= e($_SESSION['USERNAME']) ?> (Guru) <a href="logout.php">Logout</a></div>
</div>
<div class="nav">
  <a class="<?= aktif('home', $menu) ?>" href="homeguru.php">Dashboard</a>
  <a class="<?= aktif('forminputsiswa', $menu) ?>" href="homeguru.php?pg=forminputsiswa">Data Siswa</a>
  <a class="<?= aktif('forminputnilai', $menu) ?>" href="homeguru.php?pg=forminputnilai">Input Nilai</a>
  <a class="<?= aktif('formpencarian', $menu) ?>" href="homeguru.php?pg=formpencarian">Pencarian</a>
  <a class="<?= aktif('laporan', $menu) ?>" href="homeguru.php?pg=laporan">Laporan</a>
</div>
<div class="container">
<?php
if (in_array($pg, $boleh)) {
    include $pg . ".php";
} else {
    $js = mysqli_fetch_row(mysqli_query($koneksi, "SELECT COUNT(*) FROM siswa"))[0];
    $jn = mysqli_fetch_row(mysqli_query($koneksi, "SELECT COUNT(*) FROM nilai"))[0];
    $jr = mysqli_fetch_row(mysqli_query($koneksi, "SELECT AVG((matematika+bahasa_indonesia+bahasa_inggris+teori_kejuruan)/4) FROM nilai"))[0];
    $jp = mysqli_fetch_row(mysqli_query($koneksi, "SELECT COUNT(*) FROM logging"))[0];
    $per_kelas = mysqli_query($koneksi, "SELECT s.KELAS, COUNT(s.ID_SISWA) AS jml,
        AVG((n.matematika+n.bahasa_indonesia+n.bahasa_inggris+n.teori_kejuruan)/4) AS rata
        FROM siswa s LEFT JOIN nilai n ON n.ID_SISWA = s.ID_SISWA GROUP BY s.KELAS ORDER BY s.KELAS");
?>
  <div class="stats">
    <div class="stat"><span>Jumlah siswa</span><b><?= $js ?></b></div>
    <div class="stat"><span>Siswa sudah ada nilai</span><b><?= $jn ?></b></div>
    <div class="stat"><span>Rata-rata nilai</span><b><?= $jr === null ? '-' : number_format($jr, 1) ?></b></div>
    <div class="stat"><span>Pengunjung</span><b><?= $jp ?></b></div>
  </div>
  <div class="card">
    <h2>Ringkasan per kelas</h2>
    <table>
      <tr><th>Kelas</th><th>Jumlah siswa</th><th>Rata-rata nilai</th></tr>
      <?php while ($k = mysqli_fetch_assoc($per_kelas)) { ?>
      <tr>
        <td><?= e($k['KELAS']) ?></td>
        <td><?= $k['jml'] ?></td>
        <td><?= badge($k['rata'], 1) ?></td>
      </tr>
      <?php } ?>
    </table>
  </div>
  <div class="card">
    <h2>Mulai dari sini</h2>
    <a class="btn" href="homeguru.php?pg=forminputsiswa">Tambah siswa</a>
    <a class="btn btn-light" href="homeguru.php?pg=forminputnilai">Input nilai</a>
    <a class="btn btn-light" href="homeguru.php?pg=laporan">Lihat laporan</a>
  </div>
<?php } ?>
</div>
<script src="app.js"></script>
</body>
</html>