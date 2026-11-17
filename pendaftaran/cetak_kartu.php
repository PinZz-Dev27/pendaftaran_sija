<?php
/**
 * Halaman Cetak Kartu Peserta Resmi Lomba Game SIJA 2026
 * Database: datadepin | Tabel: sija2
 */
require_once 'koneksi.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    die("ID Peserta tidak valid.");
}

// Ambil data peserta berdasarkan NO/ID
$stmt = mysqli_prepare($conn, "SELECT * FROM sija2 WHERE NO = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Data peserta tidak ditemukan di tabel sija2.");
}

$nama = !empty($data['NAMA']) ? $data['NAMA'] : ($data['nama'] ?? 'Peserta SIJA');
$email = !empty($data['EMAIL']) ? $data['EMAIL'] : ($data['email'] ?? '-');
$gender = !empty($data['GENDER']) ? $data['GENDER'] : ($data['gender'] ?? '-');
$website = !empty($data['WEBSITE']) ? $data['WEBSITE'] : ($data['website'] ?? '-');
$game = parseGameComment($data['COMMENT'] ?? '');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Peserta Lomba Game SIJA - <?= htmlspecialchars($nama); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Orbitron:wght@700;900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background: #f1f5f9;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #0f172a;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
        }
        .no-print {
            margin-bottom: 25px;
            display: flex;
            gap: 12px;
        }
        .btn-print {
            background: #4f46e5;
            color: #fff;
            border: none;
            padding: 10px 24px;
            font-size: 0.95rem;
            font-weight: 700;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-back {
            background: #e2e8f0;
            color: #334155;
            text-decoration: none;
            padding: 10px 20px;
            font-size: 0.95rem;
            font-weight: 600;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        /* CARD DESIGN */
        .card-container {
            width: 100%;
            max-width: 650px;
            background: #ffffff;
            border-radius: 16px;
            border: 2px solid #cbd5e1;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
            position: relative;
        }
        .card-topbar {
            background: linear-gradient(135deg, #1e1b4b, #312e81, #4338ca);
            color: #fff;
            padding: 24px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .topbar-left h2 {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.4rem;
            font-weight: 900;
            letter-spacing: 1px;
            color: #ffffff;
        }
        .topbar-left p {
            font-size: 0.82rem;
            color: #a5b4fc;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-top: 4px;
        }
        .reg-number {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.25);
            padding: 8px 16px;
            border-radius: 8px;
            text-align: right;
        }
        .reg-number span {
            font-size: 0.72rem;
            display: block;
            color: #c7d2fe;
            text-transform: uppercase;
        }
        .reg-number strong {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.15rem;
            color: #fff;
        }

        .card-content {
            padding: 30px;
        }
        .game-banner {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 18px 22px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .game-icon-box {
            width: 55px;
            height: 55px;
            background: #e0e7ff;
            color: #4338ca;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            flex-shrink: 0;
        }
        .game-info-text h3 {
            font-size: 1.25rem;
            font-weight: 800;
            color: #1e1b4b;
        }
        .game-badges {
            display: flex;
            gap: 8px;
            margin-top: 6px;
        }
        .badge {
            font-size: 0.72rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 6px;
            background: #e2e8f0;
            color: #334155;
        }
        .badge-purple { background: #ede9fe; color: #6d28d9; }
        .badge-blue { background: #e0f2fe; color: #0369a1; }

        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 24px;
        }
        .detail-item {
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 8px;
        }
        .detail-item label {
            display: block;
            font-size: 0.75rem;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 600;
        }
        .detail-item p {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1e293b;
            margin-top: 2px;
        }

        .card-footer {
            border-top: 2px dashed #e2e8f0;
            padding-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .barcode-sim {
            font-family: monospace;
            letter-spacing: 4px;
            font-size: 1.3rem;
            color: #334155;
            font-weight: 800;
        }
        .seal {
            text-align: right;
            font-size: 0.8rem;
            color: #64748b;
        }
        .seal strong {
            display: block;
            color: #0f172a;
            margin-top: 4px;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .no-print {
                display: none;
            }
            .card-container {
                box-shadow: none;
                border: 1px solid #000;
                margin: 20px auto;
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button class="btn-print" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
        <a href="data_peserta.php" class="btn-back">⬅️ Kembali ke Data Peserta</a>
        <a href="index.php" class="btn-back">➕ Formulir Baru</a>
    </div>

    <div class="card-container">
        <div class="card-topbar">
            <div class="topbar-left">
                <h2>SIJA GAMEFEST 2026</h2>
                <p>Kartu Tanda Peserta Resmi</p>
            </div>
            <div class="reg-number">
                <span>No. Registrasi</span>
                <strong>#SG-<?= str_pad($id, 4, '0', STR_PAD_LEFT); ?></strong>
            </div>
        </div>

        <div class="card-content">
            <!-- Game Info Block -->
            <div class="game-banner">
                <div class="game-icon-box">🎮</div>
                <div class="game-info-text">
                    <h3><?= htmlspecialchars($game['judul']); ?></h3>
                    <div class="game-badges">
                        <span class="badge badge-purple"><?= htmlspecialchars($game['genre']); ?></span>
                        <span class="badge badge-blue"><?= htmlspecialchars($game['engine']); ?></span>
                        <span class="badge"><?= htmlspecialchars($game['platform']); ?></span>
                    </div>
                </div>
            </div>

            <!-- Participant Info Grid -->
            <div class="details-grid">
                <div class="detail-item">
                    <label>Nama Peserta / Tim</label>
                    <p><?= htmlspecialchars($nama); ?></p>
                </div>
                <div class="detail-item">
                    <label>Kelas SIJA</label>
                    <p><?= htmlspecialchars($game['kelas']); ?></p>
                </div>
                <div class="detail-item">
                    <label>Email Peserta</label>
                    <p><?= htmlspecialchars($email); ?></p>
                </div>
                <div class="detail-item">
                    <label>Jenis Kelamin</label>
                    <p><?= htmlspecialchars($gender); ?></p>
                </div>
                <div class="detail-item" style="grid-column: span 2;">
                    <label>Link Repositori / Demo Game</label>
                    <p style="word-break: break-all; color:#4338ca; font-weight:600;">
                        <?= htmlspecialchars($website); ?>
                    </p>
                </div>
            </div>

            <!-- Footer & Signature Verification -->
            <div class="card-footer">
                <div>
                    <div class="barcode-sim">|||| ||| ||||| |||| || |||</div>
                    <span style="font-size:0.7rem; color:#94a3b8;">VERIFIED SIJA DATABASE</span>
                </div>
                <div class="seal">
                    Status: <strong style="color:#16a34a;">TERDAFTAR & VALID</strong>
                    <span>Panitia Game Dev SIJA 2026</span>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
