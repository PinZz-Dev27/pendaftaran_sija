<?php
/**
 * Backend Pengolahan Form Pendaftaran Lomba Game SIJA
 * Database: datadepin | Tabel: sija2
 */

require_once 'koneksi.php';

// Pastikan request menggunakan method POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

// Ambil dan bersihkan data input dari form
$nama       = trim($_POST['nama'] ?? '');
$email      = trim($_POST['email'] ?? '');
$website    = trim($_POST['website'] ?? '');
$gender     = trim($_POST['gender'] ?? '');

$kelas      = trim($_POST['kelas'] ?? '-');
$judul_game = trim($_POST['judul_game'] ?? '');
$genre      = trim($_POST['genre'] ?? 'Lainnya');
$engine     = trim($_POST['engine'] ?? 'Custom');
$platform   = trim($_POST['platform'] ?? 'PC');
$deskripsi  = trim($_POST['deskripsi'] ?? '');

// Validasi sederhana
$errors = [];

if (empty($nama)) {
    $errors[] = "Nama siswa / ketua tim wajib diisi.";
}
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Email tidak valid atau belum diisi.";
}
if (empty($judul_game)) {
    $errors[] = "Judul game lomba wajib diisi.";
}
if (empty($gender)) {
    $errors[] = "Jenis kelamin wajib dipilih.";
}

// Jika ada error validasi
if (!empty($errors)) {
    $errorMessage = urlencode(implode("<br>", $errors));
    header("Location: index.php?status=error&msg=" . $errorMessage);
    exit();
}

// Format kolom COMMENT agar menyimpan seluruh detail game secara terstruktur
$comment = "[JUDUL_GAME]: " . $judul_game . "\n"
         . "[KELAS]: " . $kelas . "\n"
         . "[GENRE]: " . $genre . "\n"
         . "[ENGINE]: " . $engine . "\n"
         . "[PLATFORM]: " . $platform . "\n"
         . "[DESKRIPSI]: " . $deskripsi;

// Sanitasi dasar string nama dan gender
$nama_clean = htmlspecialchars($nama, ENT_QUOTES, 'UTF-8');
$email_clean = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
$website_clean = htmlspecialchars($website, ENT_QUOTES, 'UTF-8');
$gender_clean = htmlspecialchars($gender, ENT_QUOTES, 'UTF-8');

// Pastikan koneksi database tersedia
if (!$db_connected || !$conn) {
    $errorMessage = urlencode("Database MySQL belum aktif di server: " . $db_error_message);
    header("Location: index.php?status=error&msg=" . $errorMessage);
    exit();
}

// Gunakan Prepared Statement untuk keamanan SQL Injection
$query = "INSERT INTO sija2 (nama, email, website, comment, gender) VALUES (?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "sssss", $nama_clean, $email_clean, $website_clean, $comment, $gender_clean);
    
    if (mysqli_stmt_execute($stmt)) {
        $insert_id = mysqli_insert_id($conn);
        mysqli_stmt_close($conn);
        mysqli_close($conn);
        
        // Redirect ke index dengan status sukses dan menyertakan nama
        header("Location: index.php?status=success&nama=" . urlencode($nama_clean) . "&id=" . $insert_id);
        exit();
    } else {
        $dbError = urlencode("Gagal menyimpan data ke MySQL: " . mysqli_stmt_error($stmt));
        mysqli_stmt_close($conn);
        mysqli_close($conn);
        header("Location: index.php?status=error&msg=" . $dbError);
        exit();
    }
} else {
    $dbError = urlencode("Gagal mempersiapkan query: " . mysqli_error($conn));
    mysqli_close($conn);
    header("Location: index.php?status=error&msg=" . $dbError);
    exit();
}
?>
