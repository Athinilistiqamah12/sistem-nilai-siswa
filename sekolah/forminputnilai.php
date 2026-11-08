<?php
if (!isset($koneksi)) { header('location:homeguru.php'); exit(); }
$siswa_semua = mysqli_fetch_all(mysqli_query($koneksi, "SELECT * FROM siswa ORDER BY KELAS, NAMA_SISWA"), MYSQLI_ASSOC);
$pilih = $_GET['id'] ?? '';
$nilai = ['matematika' => '', 'bahasa_indonesia' => '', 'bahasa_inggris' => '', 'teori_kejuruan' => ''];
if ($pilih !== '') {
    $q = mysqli_prepare($koneksi, "SELECT * FROM nilai WHERE ID_SISWA = ?");
    mysqli_stmt_bind_param($q, "s", $pilih);
    mysqli_stmt_execute($q);
    $r = mysqli_fetch_assoc(mysqli_stmt_get_result($q));
    if ($r) $nilai = $r;
}
?>
<div class="card">
  <h2>Input nilai siswa</h2>
  <form method="post" action="inputnilai.php">
    <div class="row">
      <div style="flex:2">
        <label>🔎 Cari siswa (ketik nama atau kelas)</label>
        <input type="text" id="cariSiswa" placeholder="Contoh: Ahmad" autocomplete="off">
      </div>
      <div style="flex:2">
        <label>Siswa</label>
        <select name="siswa" id="selSiswa" required>
          <?php foreach ($siswa_semua as $s) { ?>
          <option value="<?= e($s['ID_SISWA']) ?>" <?= $s['ID_SISWA'] == $pilih ? 'selected' : '' ?>><?= e($s['NAMA_SISWA']) ?> | <?= e($s['KELAS']) ?></option>
          <?php } ?>
        </select>
      </div>
    </div>
    <div class="row">
      <div><label>Matematika</label><input type="number" name="matematika" min="0" max="100" value="<?= e($nilai['matematika']) ?>" required></div>
      <div><label>B. Indonesia</label><input type="number" name="bahasa_indonesia" min="0" max="100" value="<?= e($nilai['bahasa_indonesia']) ?>" required></div>
      <div><label>B. Inggris</label><input type="number" name="bahasa_inggris" min="0" max="100" value="<?= e($nilai['bahasa_inggris']) ?>" required></div>
      <div><label>T. Kejuruan</label><input type="number" name="teori_kejuruan" min="0" max="100" value="<?= e($nilai['teori_kejuruan']) ?>" required></div>
      <div style="flex:0"><button class="btn" type="submit">SIMPAN</button></div>
    </div>
  </form>
</div>
<div class="card">
  <h2>Nilai yang sudah diinput</h2>
  <input type="text" id="cariTabel" placeholder="🔎 Cari di tabel (nama atau kelas)..." autocomplete="off" style="margin-bottom:12px">
  <table id="tabelNilai">
    <tr><th>Siswa</th><th>Kelas</th><th>MTK</th><th>B. Indo</th><th>B. Ing</th><th>T. Kejuruan</th><th>Rata-rata</th><th></th></tr>
    <?php
    $ambil = mysqli_query($koneksi, "SELECT s.ID_SISWA, s.NAMA_SISWA, s.KELAS, s.FOTO, n.matematika, n.bahasa_indonesia, n.bahasa_inggris, n.teori_kejuruan
        FROM siswa s INNER JOIN nilai n ON n.ID_SISWA = s.ID_SISWA ORDER BY s.KELAS, s.NAMA_SISWA");
    while ($r = mysqli_fetch_assoc($ambil)) {
    ?>
    <tr class="baris">
      <td><?= avatar($r['NAMA_SISWA'], $r['FOTO']) ?><?= e($r['NAMA_SISWA']) ?></td>
      <td><?= e($r['KELAS']) ?></td>
      <td><?= badge($r['matematika']) ?></td>
      <td><?= badge($r['bahasa_indonesia']) ?></td>
      <td><?= badge($r['bahasa_inggris']) ?></td>
      <td><?= badge($r['teori_kejuruan']) ?></td>
      <td><?= badge(rataRata($r), 1) ?></td>
      <td><a class="btn btn-sm btn-light" href="homeguru.php?pg=forminputnilai&id=<?= urlencode($r['ID_SISWA']) ?>">Edit</a></td>
    </tr>
    <?php } ?>
  </table>
  <p id="kosong" style="display:none;color:#64748b">Siswa tidak ditemukan.</p>
</div>
<script>
// cari di dropdown siswa
var sel = document.getElementById('selSiswa');
var semua = Array.from(sel.options).map(function(o){ return {v:o.value, t:o.text}; });
document.getElementById('cariSiswa').addEventListener('input', function(){
  var q = this.value.toLowerCase(), cur = sel.value;
  sel.innerHTML = '';
  semua.filter(function(o){ return o.t.toLowerCase().indexOf(q) > -1; })
       .forEach(function(o){ var op = new Option(o.t, o.v); if (o.v === cur) op.selected = true; sel.add(op); });
});
// cari di tabel nilai
document.getElementById('cariTabel').addEventListener('input', function(){
  var q = this.value.toLowerCase(), ada = 0;
  document.querySelectorAll('#tabelNilai .baris').forEach(function(r){
    var cocok = r.textContent.toLowerCase().indexOf(q) > -1;
    r.style.display = cocok ? '' : 'none';
    if (cocok) ada++;
  });
  document.getElementById('kosong').style.display = ada ? 'none' : '';
});
</script>