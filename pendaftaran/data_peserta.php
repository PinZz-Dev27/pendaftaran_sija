<?php
/**
 * Halaman Data Peserta Lomba Game SIJA 2026
 * Menampilkan data dari database datadepin, tabel sija2
 */
require_once 'koneksi.php';

// Notifikasi pesan
$msg = $_GET['msg'] ?? '';
$status = $_GET['status'] ?? '';

// Ambil seluruh data dari tabel sija2 jika koneksi aktif
$totalPeserta = 0;
$totalPria = 0;
$totalWanita = 0;
$engineCounts = [];
$pesertaList = [];

if ($db_connected && $conn) {
    $query = "SELECT * FROM sija2 ORDER BY NO DESC";
    $result = mysqli_query($conn, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $parsed = parseGameComment($row['COMMENT'] ?? '');
            $row['parsed_game'] = $parsed;
            $pesertaList[] = $row;

            $totalPeserta++;
            $g = strtolower($row['GENDER'] ?? '');
            if ($g === 'laki-laki' || $g === 'pria' || $g === 'male') {
                $totalPria++;
            } elseif ($g === 'perempuan' || $g === 'wanita' || $g === 'female') {
                $totalWanita++;
            }

            $eng = $parsed['engine'];
            if (!empty($eng) && $eng !== '-') {
                $engineCounts[$eng] = ($engineCounts[$eng] ?? 0) + 1;
            }
        }
    }
}

// Menentukan engine terfavorit
arsort($engineCounts);
$topEngine = !empty($engineCounts) ? array_key_first($engineCounts) : 'Godot Engine';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pendaftar Lomba Game SIJA 2026</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- Include Navbar -->
    <?php include 'navbar.php'; ?>

    <div class="container" style="padding-top: 35px;">

        <!-- Alert Banner -->
        <?php if ($status === 'deleted'): ?>
            <div class="alert-banner alert-success">
                <span>✅ Data pendaftar telah berhasil dihapus dari database <code>datadepin</code>.</span>
            </div>
        <?php elseif ($status === 'delete_error'): ?>
            <div class="alert-banner alert-danger">
                <span>❌ Gagal menghapus data: <?= htmlspecialchars(urldecode($msg)); ?></span>
            </div>
        <?php endif; ?>

        <!-- Section Header -->
        <div style="display:flex; justify-content:space-between; align-items:flex-end; flex-wrap:wrap; gap:16px; margin-bottom:24px;">
            <div>
                <span class="hero-badge" style="margin-bottom: 8px;">DATABASE: datadepin &bull; TABEL: sija2</span>
                <h2 style="font-family:'Orbitron', sans-serif; font-size: 1.8rem; font-weight:800; color:#fff;">
                    👥 Data Pendaftar Lomba Game SIJA
                </h2>
                <p style="color:var(--text-muted); font-size:0.9rem; margin-top:4px;">
                    Daftar seluruh siswa SIJA yang telah mendaftarkan karya game mereka ke kompetisi.
                </p>
            </div>
            <div style="display:flex; gap:10px;">
                <a href="index.php" class="btn-submit" style="width:auto; padding:10px 20px; font-size:0.88rem; text-decoration:none;">
                    ➕ Daftar Peserta Baru
                </a>
            </div>
        </div>

        <!-- Stat Cards Row -->
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-icon primary">🏆</div>
                <div class="stat-info">
                    <h3><?= $totalPeserta; ?></h3>
                    <p>Total Karya Game</p>
                </div>
            </div>

            <div class="stat-box">
                <div class="stat-icon cyan">👨</div>
                <div class="stat-info">
                    <h3><?= $totalPria; ?></h3>
                    <p>Peserta Laki-laki</p>
                </div>
            </div>

            <div class="stat-box">
                <div class="stat-icon purple">👩</div>
                <div class="stat-info">
                    <h3><?= $totalWanita; ?></h3>
                    <p>Peserta Perempuan</p>
                </div>
            </div>

            <div class="stat-box">
                <div class="stat-icon emerald">⚡</div>
                <div class="stat-info">
                    <h3 style="font-size:1.2rem;"><?= htmlspecialchars($topEngine); ?></h3>
                    <p>Engine Terpopuler</p>
                </div>
            </div>
        </div>

        <!-- Main Card with Table -->
        <div class="glass-card" style="padding:26px;">
            
            <!-- Toolbar: Search, Filters & Export -->
            <div class="data-toolbar">
                <div class="search-box">
                    <span>🔍</span>
                    <input type="text" id="tableSearch" placeholder="Cari nama siswa, judul game, atau engine...">
                </div>

                <div class="filter-group">
                    <select id="genreFilter" class="form-control no-icon" style="padding: 8px 12px; width:auto; font-size:0.85rem;">
                        <option value="">Semua Genre</option>
                        <option value="Platformer">Platformer</option>
                        <option value="Action">Action & Adventure</option>
                        <option value="RPG">RPG</option>
                        <option value="Puzzle">Puzzle</option>
                        <option value="Runner">Endless Runner</option>
                        <option value="Edu">Edu-Game</option>
                    </select>

                    <select id="engineFilter" class="form-control no-icon" style="padding: 8px 12px; width:auto; font-size:0.85rem;">
                        <option value="">Semua Engine</option>
                        <option value="Godot">Godot Engine</option>
                        <option value="Unity">Unity</option>
                        <option value="Unreal">Unreal Engine</option>
                        <option value="Construct">Construct 3</option>
                        <option value="Scratch">Scratch</option>
                        <option value="Pygame">Pygame</option>
                        <option value="HTML5">HTML5 / Phaser</option>
                    </select>

                    <a href="export_csv.php" class="btn-secondary" title="Download Excel/CSV Spreadsheet">
                        📥 Download CSV
                    </a>
                </div>
            </div>

            <!-- Table Responsive -->
            <div class="table-responsive">
                <table class="custom-table" id="pesertaTable">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Peserta & Kelas</th>
                            <th>Judul Proyek Game</th>
                            <th>Genre</th>
                            <th>Game Engine</th>
                            <th>Gender</th>
                            <th>Link Proyek</th>
                            <th style="text-align:center; width: 130px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pesertaList)): ?>
                            <tr>
                                <td colspan="8" style="text-align:center; padding: 40px; color:var(--text-dim);">
                                    Belum ada data pendaftar lomba game di tabel <code>sija2</code>.<br>
                                    <a href="index.php" style="color:var(--accent-cyan); text-decoration:none; font-weight:600; margin-top:10px; display:inline-block;">
                                        ➕ Masukkan Data Pendaftar Pertama
                                    </a>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php 
                            $nomorUrut = 1;
                            foreach ($pesertaList as $row): 
                                $game = $row['parsed_game'];
                                $rawName = !empty($row['NAMA']) ? $row['NAMA'] : (!empty($row['nama']) ? $row['nama'] : 'Tanpa Nama');
                                $rawEmail = !empty($row['EMAIL']) ? $row['EMAIL'] : (!empty($row['email']) ? $row['email'] : '-');
                                $rawWebsite = !empty($row['WEBSITE']) ? $row['WEBSITE'] : (!empty($row['website']) ? $row['website'] : '-');
                                $rawGender = !empty($row['GENDER']) ? $row['GENDER'] : (!empty($row['gender']) ? $row['gender'] : '-');
                                $rowId = $row['NO'] ?? $row['no'] ?? 0;
                            ?>
                                <tr class="data-row" 
                                    data-genre="<?= htmlspecialchars($game['genre']); ?>" 
                                    data-engine="<?= htmlspecialchars($game['engine']); ?>">
                                    <td style="color:var(--text-dim); font-weight:600;"><?= $nomorUrut++; ?></td>
                                    
                                    <td>
                                        <div class="table-user-cell">
                                            <span class="table-user-name"><?= htmlspecialchars($rawName); ?></span>
                                            <span class="table-user-email">
                                                <?= htmlspecialchars($rawEmail); ?> • <strong style="color:var(--accent-cyan);"><?= htmlspecialchars($game['kelas']); ?></strong>
                                            </span>
                                        </div>
                                    </td>

                                    <td>
                                        <strong style="color:#fff; font-size:0.95rem;">
                                            🎮 <?= htmlspecialchars($game['judul']); ?>
                                        </strong>
                                    </td>

                                    <td>
                                        <span class="badge-tag cyan">
                                            <?= htmlspecialchars($game['genre']); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge-tag purple">
                                            <?= htmlspecialchars($game['engine']); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <?php if (stripos($rawGender, 'pria') !== false || stripos($rawGender, 'laki') !== false || stripos($rawGender, 'male') !== false): ?>
                                            <span style="color:#60a5fa;">👨 Laki-laki</span>
                                        <?php else: ?>
                                            <span style="color:#f472b6;">👩 Perempuan</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?php if (!empty($rawWebsite) && $rawWebsite !== '-'): ?>
                                            <a href="<?= (strpos($rawWebsite, 'http') === 0) ? htmlspecialchars($rawWebsite) : 'https://' . htmlspecialchars($rawWebsite); ?>" 
                                               target="_blank" class="badge-tag emerald" style="text-decoration:none;">
                                                🔗 Buka Link
                                            </a>
                                        <?php else: ?>
                                            <span style="color:var(--text-dim); font-size:0.8rem;">-</span>
                                        <?php endif; ?>
                                    </td>

                                    <td style="text-align:center;">
                                        <div class="action-btns" style="justify-content:center;">
                                            <!-- Tombol Detail Modal -->
                                            <button type="button" class="btn-icon-action btn-icon-view" title="Lihat Detail Game"
                                                    onclick="showDetailModal(
                                                        '<?= addslashes(htmlspecialchars($rawName)); ?>',
                                                        '<?= addslashes(htmlspecialchars($game['judul'])); ?>',
                                                        '<?= addslashes(htmlspecialchars($game['kelas'])); ?>',
                                                        '<?= addslashes(htmlspecialchars($game['genre'])); ?>',
                                                        '<?= addslashes(htmlspecialchars($game['engine'])); ?>',
                                                        '<?= addslashes(htmlspecialchars($game['platform'])); ?>',
                                                        '<?= addslashes(htmlspecialchars($rawWebsite)); ?>',
                                                        '<?= addslashes(htmlspecialchars($game['deskripsi'])); ?>'
                                                    )">
                                                👁️
                                            </button>

                                            <!-- Tombol Cetak Kartu Peserta -->
                                            <a href="cetak_kartu.php?id=<?= $rowId; ?>" target="_blank" class="btn-icon-action btn-icon-print" title="Cetak Kartu Tanda Peserta">
                                                🖨️
                                            </a>

                                            <!-- Tombol Hapus Data -->
                                            <button type="button" class="btn-icon-action btn-icon-delete" title="Hapus Data Peserta"
                                                    onclick="confirmDelete(<?= $rowId; ?>, '<?= addslashes(htmlspecialchars($rawName)); ?>')">
                                                🗑️
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        
                        <!-- Row pesan jika hasil pencarian kosong -->
                        <tr id="noResultsRow" style="display:none;">
                            <td colspan="8" style="text-align:center; padding:30px; color:var(--text-dim);">
                                Tidak ditemukan data pendaftar yang cocok dengan filter pencarian.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

    </div>

    <!-- Modal Popup Detail Game -->
    <div class="modal-overlay" id="detailModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" style="display:flex; align-items:center; gap:8px;">
                    <span>🎮</span> Detail Proyek Game Siswa
                </h3>
                <button class="modal-close" onclick="closeDetailModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div style="background:rgba(15,23,42,0.7); padding:16px; border-radius:12px; margin-bottom:16px; border:1px solid rgba(255,255,255,0.08);">
                    <h4 id="mJudul" style="font-family:'Orbitron', sans-serif; font-size:1.3rem; color:#fff; margin-bottom:6px;">Judul Game</h4>
                    <p style="font-size:0.86rem; color:var(--text-muted);">
                        Pengembang: <strong id="mNama" style="color:#fff;">-</strong> (<span id="mKelas" style="color:var(--accent-cyan); font-weight:600;">-</span>)
                    </p>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:16px;">
                    <div style="background:rgba(255,255,255,0.03); padding:10px 14px; border-radius:8px;">
                        <span style="font-size:0.75rem; color:var(--text-dim); text-transform:uppercase; letter-spacing:1px; display:block;">Genre</span>
                        <strong id="mGenre" style="color:var(--accent-cyan); font-size:0.92rem;">-</strong>
                    </div>
                    <div style="background:rgba(255,255,255,0.03); padding:10px 14px; border-radius:8px;">
                        <span style="font-size:0.75rem; color:var(--text-dim); text-transform:uppercase; letter-spacing:1px; display:block;">Game Engine</span>
                        <strong id="mEngine" style="color:#c084fc; font-size:0.92rem;">-</strong>
                    </div>
                    <div style="background:rgba(255,255,255,0.03); padding:10px 14px; border-radius:8px;">
                        <span style="font-size:0.75rem; color:var(--text-dim); text-transform:uppercase; letter-spacing:1px; display:block;">Target Platform</span>
                        <strong id="mPlatform" style="color:var(--accent-emerald); font-size:0.92rem;">-</strong>
                    </div>
                    <div style="background:rgba(255,255,255,0.03); padding:10px 14px; border-radius:8px;">
                        <span style="font-size:0.75rem; color:var(--text-dim); text-transform:uppercase; letter-spacing:1px; display:block;">Portofolio / Demo</span>
                        <div id="mWebsiteWrapper">
                            <a id="mWebsite" href="#" target="_blank" style="color:var(--accent-cyan); font-size:0.85rem; word-break:break-all;">-</a>
                        </div>
                    </div>
                </div>

                <div style="margin-top:14px;">
                    <label style="font-size:0.82rem; font-weight:700; color:#e2e8f0; text-transform:uppercase; letter-spacing:1px; display:block; margin-bottom:8px;">
                        📖 Sinopsis & Konsep Gameplay
                    </label>
                    <div id="mDeskripsi" style="background:rgba(15,23,42,0.8); border:1px solid rgba(255,255,255,0.08); border-radius:8px; padding:14px; font-size:0.88rem; color:#cbd5e1; line-height:1.6; max-height:160px; overflow-y:auto; white-space:pre-wrap;">
                        -
                    </div>
                </div>

                <div style="margin-top:20px; text-align:right;">
                    <button type="button" class="btn-secondary" onclick="closeDetailModal()">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container" style="padding-bottom:0;">
            <p>© 2026 <strong>SIJA GameFest</strong> • Aplikasi Pendaftaran Lomba Game Berbasis HTML, CSS, PHP & MySQL</p>
            <p style="margin-top: 6px; font-size: 0.8rem;">Database: <code>datadepin</code> | Tabel: <code>sija2</code> | Folder: <code>letphp</code></p>
        </div>
    </footer>

    <!-- Interactive JS -->
    <script src="assets/js/script.js"></script>
</body>
</html>
