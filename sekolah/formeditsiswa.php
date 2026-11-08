<?php
if (!isset($koneksi)) { header('location:homeguru.php'); exit(); }
$id = $_GET['idnya'] ?? '';
$q = mysqli_prepare($koneksi, "SELECT * FROM siswa WHERE ID_SISWA = ?");
mysqli_stmt_bind_param($q, "s", $id);
mysqli_stmt_execute($q);
$r = mysqli_fetch_assoc(mysqli_stmt_get_result($q));
if (!$r) {
    echo "<script>alert('Siswa tidak ditemukan');document.location='homeguru.php?pg=forminputsiswa';</script>";
    exit();
}
$punyaFoto = $r['FOTO'] !== '' && file_exists(__DIR__ . '/gambar/' . $r['FOTO']);
?>
<div class="card" style="max-width:520px;margin:auto">
  <h2>Edit siswa</h2>
  <form method="post" action="editsiswa.php" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= e($r['ID_SISWA']) ?>">
    <p><?= avatar($r['NAMA_SISWA'], $r['FOTO']) ?> <b><?= e($r['ID_SISWA']) ?></b></p>
    <label>Nama siswa</label>
    <input type="text" name="nama" value="<?= e($r['NAMA_SISWA']) ?>" required>
    <label>Email Gmail (untuk login siswa)</label>
    <input type="email" name="email" value="<?= e($r['EMAIL'] ?? '') ?>" placeholder="nama@gmail.com">
    <label>Kelas</label>
    <select name="kelas">
      <?php foreach ($daftar_kelas as $k) { ?>
      <option <?= $k == $r['KELAS'] ? 'selected' : '' ?>><?= e($k) ?></option>
      <?php } ?>
    </select>

    <label>Foto</label>
    <div style="display:flex;align-items:center;gap:14px;margin-bottom:8px">
      <img id="pratinjau" src="<?= $punyaFoto ? 'gambar/' . e($r['FOTO']) : '' ?>" alt=""
           style="width:90px;height:90px;border-radius:16px;object-fit:cover;<?= $punyaFoto ? '' : 'display:none' ?>">
      <div style="flex:1">
        <input type="file" name="fo" id="fo" accept="image/*">
        <?php if ($punyaFoto) { ?>
        <label style="display:flex;gap:8px;align-items:center;margin-top:10px;color:#dc2626;cursor:pointer">
          <input type="checkbox" name="hapus_foto" id="hapus" style="width:auto"> Hapus foto saat ini
        </label>
        <?php } ?>
        <small style="color:#64748b">Kosongkan kalau tidak ingin mengubah foto.</small>
      </div>
    </div>

    <div style="display:flex;gap:10px;margin-top:18px">
      <button class="btn" type="submit">UPDATE</button>
      <a class="btn btn-light" href="homeguru.php?pg=forminputsiswa">Kembali</a>
    </div>
  </form>
</div>
<script>
// pratinjau foto baru sebelum disimpan
document.getElementById('fo').addEventListener('change', function () {
  var f = this.files[0], p = document.getElementById('pratinjau');
  if (!f) return;
  p.src = URL.createObjectURL(f);
  p.style.display = '';
  var h = document.getElementById('hapus');
  if (h) h.checked = false;
});
// kalau centang hapus, pratinjau disembunyikan
var hp = document.getElementById('hapus');
if (hp) hp.addEventListener('change', function () {
  document.getElementById('pratinjau').style.opacity = this.checked ? .25 : 1;
});
</script>