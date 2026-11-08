<?php
if (!isset($koneksi)) { header('location:homeguru.php'); exit(); }
$data = mysqli_fetch_all(mysqli_query($koneksi, "SELECT * FROM siswa ORDER BY KELAS, NAMA_SISWA"), MYSQLI_ASSOC);
?>
<div class="card">
  <h2>Tambah siswa</h2>
  <form method="post" action="inputsiswa.php" enctype="multipart/form-data">
    <div class="row">
      <div><label>ID siswa (NISN)</label><input type="text" name="nisn" required></div>
      <div style="flex:2"><label>Nama siswa</label><input type="text" name="nama" required></div>
      <div style="flex:2"><label>Email Gmail (untuk login siswa)</label><input type="email" name="email" placeholder="nama@gmail.com"></div>
    </div>
    <div class="row">
      <div>
        <label>Kelas</label>
        <select name="kelas">
          <?php foreach ($daftar_kelas as $k) { ?><option><?= e($k) ?></option><?php } ?>
        </select>
      </div>
      <div><label>Foto (opsional)</label><input type="file" name="fo" accept="image/*"></div>
      <div style="flex:0"><button class="btn" type="submit">SIMPAN</button></div>
    </div>
  </form>
</div>
<div class="card">
  <h2>Data siswa (<?= count($data) ?>)</h2>
  <table>
    <tr><th>No</th><th>ID</th><th>Siswa</th><th>Email</th><th>Kelas</th><th></th></tr>
    <?php foreach ($data as $i => $r) { ?>
    <tr>
      <td><?= $i + 1 ?></td>
      <td><?= e($r['ID_SISWA']) ?></td>
      <td><?= avatar($r['NAMA_SISWA'], $r['FOTO']) ?><?= e($r['NAMA_SISWA']) ?></td>
      <td><?= e($r['EMAIL'] ?? '') ?: '-' ?></td>
      <td><?= e($r['KELAS']) ?></td>
      <td>
        <a class="btn btn-sm btn-light" href="homeguru.php?pg=formeditsiswa&idnya=<?= urlencode($r['ID_SISWA']) ?>">Edit</a>
        <a class="btn btn-sm btn-danger" href="deletesiswa.php?idnya=<?= urlencode($r['ID_SISWA']) ?>" onclick="return confirm('Yakin akan dihapus?')">Hapus</a>
      </td>
    </tr>
    <?php } ?>
  </table>
</div>