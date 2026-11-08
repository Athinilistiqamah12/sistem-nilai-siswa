<?php
if (!isset($koneksi)) { header('location:homeguru.php'); exit(); }
$cari = trim($_GET['cari'] ?? '');
$kls = $_GET['kelas'] ?? '';
$sql = "SELECT s.ID_SISWA, s.NAMA_SISWA, s.KELAS, s.FOTO, n.matematika, n.bahasa_indonesia, n.bahasa_inggris, n.teori_kejuruan
        FROM siswa s LEFT JOIN nilai n ON n.ID_SISWA = s.ID_SISWA
        WHERE (s.NAMA_SISWA LIKE ? OR s.ID_SISWA LIKE ?)";
$like = "%" . $cari . "%";
$tipe = "ss";
$param = [$like, $like];
if ($kls !== '') {
    $sql .= " AND s.KELAS = ?";
    $tipe .= "s";
    $param[] = $kls;
}
$sql .= " ORDER BY s.KELAS, s.NAMA_SISWA";
$q = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($q, $tipe, ...$param);
mysqli_stmt_execute($q);
$hasil = mysqli_fetch_all(mysqli_stmt_get_result($q), MYSQLI_ASSOC);
?>
<div class="card">
  <h2>Pencarian siswa</h2>
  <form method="get" action="homeguru.php">
    <input type="hidden" name="pg" value="formpencarian">
    <div class="row">
      <div style="flex:2"><label>Nama atau ID siswa</label><input type="text" name="cari" value="<?= e($cari) ?>" placeholder="Contoh: Ahmad"></div>
      <div>
        <label>Kelas</label>
        <select name="kelas">
          <option value="">Semua kelas</option>
          <?php foreach ($daftar_kelas as $k) { ?>
          <option <?= $k == $kls ? 'selected' : '' ?>><?= e($k) ?></option>
          <?php } ?>
        </select>
      </div>
      <div style="flex:0"><button class="btn" type="submit">CARI</button></div>
    </div>
  </form>
</div>
<div class="card">
  <h2>Hasil (<?= count($hasil) ?> siswa)</h2>
  <table>
    <tr><th>Siswa</th><th>Kelas</th><th>MTK</th><th>B. Indo</th><th>B. Ing</th><th>T. Kejuruan</th><th>Rata-rata</th></tr>
    <?php foreach ($hasil as $r) { ?>
    <tr>
      <td><?= avatar($r['NAMA_SISWA'], $r['FOTO']) ?><?= e($r['NAMA_SISWA']) ?></td>
      <td><?= e($r['KELAS']) ?></td>
      <td><?= badge($r['matematika']) ?></td>
      <td><?= badge($r['bahasa_indonesia']) ?></td>
      <td><?= badge($r['bahasa_inggris']) ?></td>
      <td><?= badge($r['teori_kejuruan']) ?></td>
      <td><?= badge(rataRata($r), 1) ?></td>
    </tr>
    <?php } ?>
  </table>
</div>