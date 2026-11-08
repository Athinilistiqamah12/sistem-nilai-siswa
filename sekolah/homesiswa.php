<?php
session_start();
include 'koneksi.php';
if (!isset($_SESSION['ID_USER'])) {
    echo "<script>alert('ANDA HARUS LOGIN DULU UNTUK BISA MENGAKSES HALAMAN INI');document.location='formlogin.php';</script>";
    exit();
}
$data = ambilNilai($koneksi, '');          // sudah urut dari rata-rata tertinggi
$jumlah = count($data);
$total = 0;
foreach ($data as $d) { $total += $d['rata']; }
$juara = $jumlah ? $data[0]['NAMA_SISWA'] : '-';

// urutkan: kelas RPL 1, 2, 3, lalu dari nilai tertinggi
usort($data, function ($a, $b) {
    return strnatcmp($a['KELAS'], $b['KELAS']) ?: ($b['rata'] <=> $a['rata']);
});
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Nilai Siswa</title>
<link rel="stylesheet" href="style.css?v=2">
<style>
.chips{display:flex;gap:8px;flex-wrap:wrap;margin-top:12px}
.chip{border:1px solid var(--border);background:#fff;color:var(--muted);padding:8px 16px;border-radius:99px;font-size:13px;font-weight:600;cursor:pointer;transition:.25s}
.chip:hover{transform:translateY(-2px);color:var(--p)}
.chip.on{background:linear-gradient(135deg,var(--p),var(--v));color:#fff;border-color:transparent;box-shadow:0 4px 12px rgba(99,102,241,.35)}
.cariwrap{position:relative}
.cariwrap span{position:absolute;left:14px;top:50%;transform:translateY(-50%)}
.cariwrap input{padding-left:42px;font-size:15px}
</style>
</head>
<body>
<div class="topbar">
  <div class="brand">Nilai<span>Siswa</span></div>
  <div class="user">Halo, <?= e($_SESSION['USERNAME']) ?> <a href="logout.php">Logout</a></div>
</div>
<div class="container">
  <div class="stats">
    <div class="stat"><span>Jumlah siswa</span><b><?= $jumlah ?></b></div>
    <div class="stat"><span>Rata-rata</span><b><?= $jumlah ? number_format($total / $jumlah, 1) : '-' ?></b></div>
    <div class="stat"><span>Peringkat 1</span><b style="font-size:20px"><?= e($juara) ?></b></div>
  </div>
  <div class="card">
    <h2>Data nilai siswa</h2>
    <div class="cariwrap">
      <span>🔎</span>
      <input type="text" id="cari" placeholder="Cari nama siswa..." autocomplete="off">
    </div>
    <div class="chips" id="chips">
      <button type="button" class="chip on" data-k="">Semua kelas</button>
      <?php foreach ($daftar_kelas as $k) { ?>
      <button type="button" class="chip" data-k="<?= e($k) ?>"><?= e($k) ?></button>
      <?php } ?>
    </div>
    <br>
    <?php if ($jumlah == 0) { ?>
    <p>Data nilai belum tersedia.</p>
    <?php } else {
        $no = 0; ?>
    <table id="tbl">
      <tr><th>No</th><th>Siswa</th><th>Kelas</th><th>MTK</th><th>B. Indo</th><th>B. Ing</th><th>T. Kejuruan</th><th>Rata-rata</th></tr>
      <?php foreach ($data as $d) {
          $no++; ?>
      <tr class="baris" data-n="<?= e(strtolower($d['NAMA_SISWA'])) ?>" data-k="<?= e($d['KELAS']) ?>">
        <td><?= $no ?></td>
        <td><?= avatar($d['NAMA_SISWA'], $d['FOTO']) ?><?= e($d['NAMA_SISWA']) ?></td>
        <td><?= e($d['KELAS']) ?></td>
        <td><?= badge($d['matematika']) ?></td>
        <td><?= badge($d['bahasa_indonesia']) ?></td>
        <td><?= badge($d['bahasa_inggris']) ?></td>
        <td><?= badge($d['teori_kejuruan']) ?></td>
        <td><?= badge($d['rata'], 1) ?></td>
      </tr>
      <?php } ?>
    </table>
    <p id="kosong" style="display:none;color:#64748b;text-align:center;padding:20px">😕 Siswa tidak ditemukan.</p>
    <?php } ?>
  </div>
</div>
<script src="app.js"></script>
<script>
var kelasAktif = '';
function saring() {
  var q = document.getElementById('cari').value.toLowerCase().trim(), ada = 0;
  document.querySelectorAll('.baris').forEach(function (r) {
    var cocok = r.dataset.n.indexOf(q) > -1 && (kelasAktif === '' || r.dataset.k === kelasAktif);
    r.style.display = cocok ? '' : 'none';
    if (cocok) { ada++; r.cells[0].textContent = ada; }
  });
  document.getElementById('kosong').style.display = ada ? 'none' : '';
}
document.getElementById('cari').addEventListener('input', saring);
document.getElementById('chips').addEventListener('click', function (e) {
  var c = e.target.closest('.chip'); if (!c) return;
  document.querySelectorAll('.chip').forEach(function (x) { x.classList.remove('on'); });
  c.classList.add('on');
  kelasAktif = c.dataset.k;
  saring();
});
</script>
</body>
</html>