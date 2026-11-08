<?php
include 'koneksi.php';
$jum = mysqli_fetch_row(mysqli_query($koneksi, "SELECT COUNT(*) FROM logging"))[0];
$err = $_GET['error'] ?? '';
$pesan = ['1' => 'Username atau password salah.', '2' => 'Login Google gagal. Coba lagi.', '3' => 'Email Gmail ini belum terdaftar. Hubungi guru.'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Login | Sistem Nilai Siswa</title>
<link rel="stylesheet" href="style.css?v=2">
<?php if (defined('GOOGLE_CLIENT_ID')) { ?><script src="https://accounts.google.com/gsi/client" async defer></script><?php } ?>
</head>
<body>
<div class="auth">
  <div class="auth-side">
    <canvas id="cv"></canvas>
    <div class="emoji">🏫</div>
    <h1>Sistem Nilai Siswa</h1>
    <p><span id="ty"></span><span class="caret"></span></p>
    <div class="feat"><span>🎓 Data siswa</span><span>📝 Input nilai</span><span>📊 Peringkat</span><span>🖨️ Cetak laporan</span></div>
  </div>
  <div class="auth-form">
    <div class="auth-box">
      <h2>Selamat datang 👋</h2>
      <p class="sub">Pilih cara masuk.</p>
      <?php if ($err !== '') { ?><div class="alert"><?= e($pesan[$err] ?? 'Terjadi kesalahan.') ?></div><?php } ?>
      <div class="tabs" id="tabs"><i></i>
        <button type="button" class="on" id="tg" onclick="tab('guru')">👩‍🏫 Guru</button>
        <button type="button" id="ts" onclick="tab('siswa')">🎓 Siswa</button>
      </div>
      <form id="pg" class="pane" method="post" action="login.php">
        <label>Username</label>
        <input type="text" name="username" placeholder="guru" required>
        <label>Password</label>
        <div class="pw"><input type="password" id="pw" name="password" placeholder="Password" required><span onclick="var p=document.getElementById('pw');p.type=p.type=='password'?'text':'password'">👁️</span></div>
        <button class="btn" type="submit" name="login">MASUK</button>
        <div class="demo">Akun demo: <b>guru</b> / <b>guru123</b></div>
      </form>
      <div id="ps" class="pane" style="display:none">
        <?php if (defined('GOOGLE_CLIENT_ID')) { ?>
        <div id="g_id_onload" data-client_id="<?= e(GOOGLE_CLIENT_ID) ?>" data-callback="gLogin"></div>
        <div class="g_id_signin" data-type="standard" data-size="large" data-text="signin_with" data-width="316"></div>
        <form id="gf" method="post" action="logingoogle.php"><input type="hidden" name="credential" id="cred"></form>
        <p class="sub" style="margin-top:14px">Pakai Gmail yang sudah didaftarkan guru.</p>
        <?php } else { ?>
        <div class="demo">Login Google belum aktif. Isi GOOGLE_CLIENT_ID di koneksi.php.</div>
        <?php } ?>
      </div>
      <div class="visit">👀 Pengunjung: <span id="vis"><?= $jum ?></span></div>
    </div>
  </div>
</div>
<script>
function tab(t){
  var g=t==='guru';
  document.getElementById('pg').style.display=g?'':'none';
  document.getElementById('ps').style.display=g?'none':'';
  document.getElementById('tg').className=g?'on':'';
  document.getElementById('ts').className=g?'':'on';
  document.getElementById('tabs').className=g?'tabs':'tabs s';
}
function gLogin(r){document.getElementById('cred').value=r.credential;document.getElementById('gf').submit();}

// teks mengetik
var kata=['Lihat nilai dan peringkat dengan mudah.','Kelola data siswa dalam satu tempat.','Cetak laporan rapor dalam sekali klik.'],k=0,c=0,h=false,ty=document.getElementById('ty');
(function ketik(){
  var s=kata[k];
  if(!h){c++;ty.textContent=s.slice(0,c);if(c==s.length){h=true;return setTimeout(ketik,1600);}}
  else{c--;ty.textContent=s.slice(0,c);if(c==0){h=false;k=(k+1)%kata.length;}}
  setTimeout(ketik,h?25:55);
})();

// partikel bergerak, ikut mouse
var cv=document.getElementById('cv'),x=cv.getContext('2d'),P=[],m={x:-999,y:-999};
function ukur(){cv.width=cv.offsetWidth;cv.height=cv.offsetHeight;}
ukur();window.onresize=ukur;
cv.parentNode.onmousemove=function(e){var r=cv.getBoundingClientRect();m.x=e.clientX-r.left;m.y=e.clientY-r.top;};
for(var i=0;i<55;i++)P.push({x:Math.random()*cv.width,y:Math.random()*cv.height,vx:Math.random()-.5,vy:Math.random()-.5,r:Math.random()*3+1});
(function gerak(){
  x.clearRect(0,0,cv.width,cv.height);
  P.forEach(function(a,i){
    a.x+=a.vx;a.y+=a.vy;
    if(a.x<0||a.x>cv.width)a.vx*=-1;if(a.y<0||a.y>cv.height)a.vy*=-1;
    var dx=a.x-m.x,dy=a.y-m.y,d=Math.sqrt(dx*dx+dy*dy);
    if(d<110){a.x+=dx/d*2;a.y+=dy/d*2;}
    x.beginPath();x.arc(a.x,a.y,a.r,0,7);x.fillStyle='rgba(255,255,255,.7)';x.fill();
    for(var j=i+1;j<P.length;j++){var b=P[j],ex=a.x-b.x,ey=a.y-b.y,e=Math.sqrt(ex*ex+ey*ey);
      if(e<100){x.strokeStyle='rgba(255,255,255,'+(.3-e/350)+')';x.beginPath();x.moveTo(a.x,a.y);x.lineTo(b.x,b.y);x.stroke();}}
  });
  requestAnimationFrame(gerak);
})();

// buka tab Siswa kalau error dari login Google
<?php if (in_array($err, ['2', '3'])) echo "tab('siswa');\n"; ?>
// hapus ?error dari alamat, pesan hilang sendiri setelah 4 detik
if (location.search.indexOf('error') > -1) {
  history.replaceState(null, '', location.pathname);
  var al = document.querySelector('.alert');
  if (al) setTimeout(function(){
    al.style.transition = '.5s'; al.style.opacity = 0;
    setTimeout(function(){ al.remove(); }, 500);
  }, 4000);
}
</script>
</body>
</html>