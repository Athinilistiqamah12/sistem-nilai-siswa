<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "sekolah";
$koneksi = mysqli_connect($host, $username, $password, $dbname);
if (!$koneksi) {
    die("Koneksi database gagal. Pastikan MySQL menyala dan sekolah.sql sudah diimpor.");
}
mysqli_set_charset($koneksi, "utf8mb4");

$daftar_kelas = ['XII RPL 1', 'XII RPL 2', 'XII RPL 3'];
define('GOOGLE_CLIENT_ID', '722489190910-2ntrbuf96lshk403f2f3ip9cg9acarfq.apps.googleusercontent.com');
function e($s) {
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

function alertKembali($pesan) {
    echo "<script>alert(" . json_encode($pesan) . ");history.go(-1);</script>";
    exit();
}

function avatar($nama, $foto) {
    if ($foto && file_exists(__DIR__ . '/gambar/' . $foto)) {
        return '<img class="ava" src="gambar/' . e($foto) . '" alt="">';
    }
    return '<span class="ava ini">' . e(strtoupper(substr($nama, 0, 1))) . '</span>';
}

function badge($n, $desimal = 0) {
    if ($n === null) return '<span class="badge none">-</span>';
    $c = $n >= 80 ? 'ok' : ($n >= 65 ? 'mid' : 'low');
    return '<span class="badge ' . $c . '">' . number_format((float)$n, $desimal) . '</span>';
}

function rataRata($r) {
    if ($r['matematika'] === null) return null;
    return ($r['matematika'] + $r['bahasa_indonesia'] + $r['bahasa_inggris'] + $r['teori_kejuruan']) / 4;
}

function simpanFoto($f) {
    if (!isset($f['error']) || $f['error'] === UPLOAD_ERR_NO_FILE) return [true, ''];
    if ($f['error'] !== UPLOAD_ERR_OK) return [false, 'Upload gagal, coba lagi'];
    $izin = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif'];
    $mime = mime_content_type($f['tmp_name']);
    if (!isset($izin[$mime])) return [false, 'Tipe file harus JPG, PNG, atau GIF'];
    if ($f['size'] > 2 * 1024 * 1024) return [false, 'Ukuran foto maksimal 2 MB'];
    if (!is_dir(__DIR__ . '/gambar')) mkdir(__DIR__ . '/gambar', 0777, true);
    $nama = uniqid('siswa_') . '.' . $izin[$mime];
    if (!move_uploaded_file($f['tmp_name'], __DIR__ . '/gambar/' . $nama)) return [false, 'Foto gagal disimpan'];
    return [true, $nama];
}

function ambilNilai($koneksi, $kelas = '') {
    $sql = "SELECT s.ID_SISWA, s.NAMA_SISWA, s.KELAS, s.FOTO, n.matematika, n.bahasa_indonesia, n.bahasa_inggris, n.teori_kejuruan,
            (n.matematika + n.bahasa_indonesia + n.bahasa_inggris + n.teori_kejuruan) / 4 AS rata
            FROM siswa s INNER JOIN nilai n ON n.ID_SISWA = s.ID_SISWA";
    if ($kelas !== '') $sql .= " WHERE s.KELAS = ?";
    $sql .= " ORDER BY rata DESC, s.NAMA_SISWA ASC";
    $q = mysqli_prepare($koneksi, $sql);
    if ($kelas !== '') mysqli_stmt_bind_param($q, "s", $kelas);
    mysqli_stmt_execute($q);
    return mysqli_fetch_all(mysqli_stmt_get_result($q), MYSQLI_ASSOC);
}
?>