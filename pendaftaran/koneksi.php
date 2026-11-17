<?php
/**
 * File Koneksi Database MySQL
 * Database : datadepin
 * Tabel    : sija2
 */

// Matikan exception fatal mysqli agar tidak menjadi HTTP 500
if (function_exists('mysqli_report')) {
    mysqli_report(MYSQLI_REPORT_OFF);
}

$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "datadepin";

$conn = null;
$db_connected = false;
$db_error_message = "";

try {
    // 1. Coba koneksi langsung ke database datadepin
    $conn = @mysqli_connect($db_host, $db_user, $db_pass, $db_name);

    // 2. Jika gagal (misal datadepin belum dibuat), coba konek ke MySQL dulu lalu buat databasenya
    if (!$conn) {
        $conn_temp = @mysqli_connect($db_host, $db_user, $db_pass);
        if ($conn_temp) {
            @mysqli_query($conn_temp, "CREATE DATABASE IF NOT EXISTS datadepin");
            @mysqli_close($conn_temp);
            $conn = @mysqli_connect($db_host, $db_user, $db_pass, $db_name);
        }
    }

    if ($conn) {
        $db_connected = true;
        @mysqli_set_charset($conn, "utf8mb4");

        // 3. Otomatis buat tabel sija2 jika belum ada
        $create_table_sql = "CREATE TABLE IF NOT EXISTS sija2 (
            NO INT(11) AUTO_INCREMENT PRIMARY KEY,
            NAMA VARCHAR(100) NOT NULL,
            EMAIL VARCHAR(100) NOT NULL,
            WEBSITE VARCHAR(255) NOT NULL,
            COMMENT TEXT NOT NULL,
            GENDER VARCHAR(20) NOT NULL
        )";
        @mysqli_query($conn, $create_table_sql);
    } else {
        $db_error_message = mysqli_connect_error() ?: "Gagal terhubung ke MySQL di localhost.";
    }
} catch (Throwable $e) {
    $conn = null;
    $db_connected = false;
    $db_error_message = $e->getMessage();
}

/**
 * Helper untuk parsing data game yang tersimpan di kolom 'COMMENT'
 */
function parseGameComment($rawComment) {
    $info = [
        'judul' => 'Belum Diisi',
        'kelas' => '-',
        'genre' => 'Game Umum',
        'engine' => 'Custom Engine',
        'platform' => 'PC / Web',
        'deskripsi' => $rawComment
    ];

    if (empty($rawComment)) {
        return $info;
    }

    if (preg_match('/\[JUDUL_GAME\]:\s*(.*?)(?=\n\[|$)/i', $rawComment, $m)) {
        $info['judul'] = trim($m[1]);
    }
    if (preg_match('/\[KELAS\]:\s*(.*?)(?=\n\[|$)/i', $rawComment, $m)) {
        $info['kelas'] = trim($m[1]);
    }
    if (preg_match('/\[GENRE\]:\s*(.*?)(?=\n\[|$)/i', $rawComment, $m)) {
        $info['genre'] = trim($m[1]);
    }
    if (preg_match('/\[ENGINE\]:\s*(.*?)(?=\n\[|$)/i', $rawComment, $m)) {
        $info['engine'] = trim($m[1]);
    }
    if (preg_match('/\[PLATFORM\]:\s*(.*?)(?=\n\[|$)/i', $rawComment, $m)) {
        $info['platform'] = trim($m[1]);
    }
    if (preg_match('/\[DESKRIPSI\]:\s*(.*)/is', $rawComment, $m)) {
        $info['deskripsi'] = trim($m[1]);
    }

    return $info;
}
?>
