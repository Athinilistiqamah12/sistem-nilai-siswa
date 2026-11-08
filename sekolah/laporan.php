<?php
if (!isset($koneksi)) { header('location:homeguru.php'); exit(); }
$kls = $_GET['kelas'] ?? '';
$data = ambilNilai($koneksi, $kls);
$medali = ['🥇', '🥈', '🥉'];
?>
<style>
.kop{display:none}
@media print{
  @page{size:A4 portrait;margin:15mm}
  body{background:#fff!important;font-size:12px}
  .container{max-width:none;margin:0;padding:0}
  .topbar,.nav,.noprint,.medali,.bar,.ava{display:none!important}
  .kop{display:block;text-align:center;margin-bottom:14px;border-bottom:2px solid #000;padding-bottom:8px}
  .kop h1{margin:0;font-size:18px}
  .kop p{margin:3px 0 0;font-size:12px}
  .card{border:0!important;box-shadow:none!important;padding:0!important;animation:none!important;overflow:visible}
  .card h2{font-size:14px;margin:0 0 8px}
  table{font-size:12px}
  th,td{border:1px solid #000!important;padding:6px 8px!important;background:none!important;color:#000!important;text-transform:none;white-space:nowrap}
  th{font-weight:700;text-align:center}
  td{text-align:center}
  td.kiri{text-align:left}
  tr{animation:none!important;page-break-inside:avoid}
  thead{display:table-header-group}
  .badge{background:none!important;color:#000!important;padding:0;min-width:0;font-weight:400}
  .rata{display:inline!important;font-weight:700}
  .ttd{display:block!important}
}
.ttd{display:none;margin-top:40px;text-align:right}
.ttd div{display:inline-block;text-align:center;width:200px}
.rata{display:none}
</style>

<div class="card noprint">
  <h2>Laporan peringkat</h2>
  <form method="get" action="homeguru.php">
    <input type="hidden" name="pg" value="laporan">
    <div class="row">
      <div>
        <label>Kelas</label>
        <select name="kelas" onchange="this.form.submit()">
          <option value="">Semua kelas</option>
          <?php foreach ($daftar_kelas as $k) { ?>
          <option <?= $k == $kls ? 'selected' : '' ?>><?= e($k) ?></option>
          <?php } ?>
        </select>
      </div>
      <div style="flex:0"><button type="button" class="btn" onclick="window.print()">🖨️ CETAK</button></div>
    </div>
  </form>
</div>

<div class="kop">
  <h1>LAPORAN PERINGKAT NILAI SISWA</h1>
  <p><?= $kls === '' ? 'Semua Kelas' : e($kls) ?> &nbsp;|&nbsp; Dicetak: <?= date('d-m-Y') ?></p>
</div>

<div class="card">
  <h2 class="noprint">Peringkat <?= $kls === '' ? 'semua kelas' : e($kls) ?></h2>
  <?php if (!$data) { ?><p>Belum ada nilai.</p><?php } else { ?>
  <table>
    <thead>
      <tr><th>No</th><th>Nama Siswa</th><th>Kelas</th><th>MTK</th><th>B. Indo</th><th>B. Ing</th><th>T. Kejuruan</th><th style="width:20%">Rata-rata</th></tr>
    </thead>
    <tbody>
    <?php foreach ($data as $i => $d) { ?>
    <tr>
      <td><span class="medali"><?= $medali[$i] ?? '' ?></span> <?= $i + 1 ?></td>
      <td class="kiri"><?= avatar($d['NAMA_SISWA'], $d['FOTO']) ?><?= e($d['NAMA_SISWA']) ?></td>
      <td><?= e($d['KELAS']) ?></td>
      <td><?= badge($d['matematika']) ?></td>
      <td><?= badge($d['bahasa_indonesia']) ?></td>
      <td><?= badge($d['bahasa_inggris']) ?></td>
      <td><?= badge($d['teori_kejuruan']) ?></td>
      <td><div class="bar"><i style="width:<?= round($d['rata']) ?>%"></i></div><small class="noprint"><?= number_format($d['rata'], 1) ?></small><span class="rata"><?= number_format($d['rata'], 1) ?></span></td>
    </tr>
    <?php } ?>
    </tbody>
  </table>
  <?php } ?>
  <div class="ttd"><div>Mengetahui,<br>Guru<br><br><br><br>( ............................ )</div></div>
</div>