<?php
/**
 * Ekspor Data Pendaftar Lomba Game SIJA 2026 ke file CSV / Excel
 * Database: datadepin | Tabel: sija2
 */
require_once 'koneksi.php';

// Atur header download file CSV
$filename = "peserta_lomba_game_sija_" . date("Ymd_His") . ".csv";

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);

// Buka output stream
$output = fopen('php://output', 'w');

// Masukkan UTF-8 BOM agar Excel menampilkan aksen dengan benar
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Header Kolom CSV
fputcsv($output, [
    'NO',
    'NAMA PESERTA / TIM',
    'EMAIL',
    'KELAS',
    'JUDUL GAME',
    'GENRE',
    'GAME ENGINE',
    'TARGET PLATFORM',
    'GENDER',
    'LINK WEBSITE / PORTOFOLIO',
    'DESKRIPSI GAME'
]);

// Ambil data dari tabel sija2
$query = "SELECT * FROM sija2 ORDER BY NO ASC";
$result = mysqli_query($conn, $query);

$noUrut = 1;
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $parsed = parseGameComment($row['COMMENT'] ?? '');
        $nama = !empty($row['NAMA']) ? $row['NAMA'] : ($row['nama'] ?? '');
        $email = !empty($row['EMAIL']) ? $row['EMAIL'] : ($row['email'] ?? '');
        $website = !empty($row['WEBSITE']) ? $row['WEBSITE'] : ($row['website'] ?? '');
        $gender = !empty($row['GENDER']) ? $row['GENDER'] : ($row['gender'] ?? '');

        fputcsv($output, [
            $noUrut++,
            $nama,
            $email,
            $parsed['kelas'],
            $parsed['judul'],
            $parsed['genre'],
            $parsed['engine'],
            $parsed['platform'],
            $gender,
            $website,
            str_replace(["\r", "\n"], ' ', $parsed['deskripsi'])
        ]);
    }
}

fclose($output);
mysqli_close($conn);
exit();
?>
