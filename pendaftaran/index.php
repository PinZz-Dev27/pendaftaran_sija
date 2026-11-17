<?php
require_once 'koneksi.php';

// Ambil status notifikasi dari query parameter jika ada
$status = $_GET['status'] ?? '';
$msg = $_GET['msg'] ?? '';
$registered_name = $_GET['nama'] ?? '';
$registered_id = $_GET['id'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Lomba Game SIJA 2026 | LetPHP</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- Include Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-badge">
            <span class="pulse-dot"></span>
            SIJA GAME DEVELOPMENT ARENA 2026
        </div>
        <h2 class="hero-title">Kembangkan Kreativitas & Skill Codingmu</h2>
        <p class="hero-desc">
            Pendaftaran resmi kompetisi pembuatan game untuk siswa jurusan Sistem Informasi, Jaringan, dan Aplikasi (SIJA). 
            Daftarkan ide game terbaikmu dan jadilah juara!
        </p>
    </section>

    <!-- Main Content Container -->
    <div class="container">

        <!-- Notifikasi Jika Database MySQL Belum Terkoneksi (Khusus Server/VM) -->
        <?php if (!$db_connected): ?>
            <div class="alert-banner alert-danger" style="display:block; line-height:1.6;">
                <div style="font-size:1rem; font-weight:700; margin-bottom:6px;">
                    ⚠️ Peringatan: Web Server Aktif, Namun Database MySQL Belum Terhubung
                </div>
                <div style="font-size:0.88rem; color:#cbd5e1;">
                    Pesan MySQL: <code><?= htmlspecialchars($db_error_message); ?></code><br>
                    <strong>Solusi di Terminal Ubuntu:</strong> Jalankan perintah berikut agar MySQL aktif dan dapat diakses:
                    <pre style="background:#090d16; padding:8px 12px; border-radius:6px; margin-top:8px; color:#38bdf8; font-size:0.82rem; overflow-x:auto;">sudo mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY ''; CREATE DATABASE IF NOT EXISTS datadepin; FLUSH PRIVILEGES;"</pre>
                </div>
            </div>
        <?php endif; ?>

        <!-- Notifikasi Status Sukses / Gagal Form -->
        <?php if ($status === 'success'): ?>
            <div class="alert-banner alert-success">
                <div>
                    <strong>🎉 Pendaftaran Berhasil!</strong> 
                    Data peserta <strong><?= htmlspecialchars($registered_name); ?></strong> telah berhasil tersimpan ke database MySQL <code>datadepin</code> (tabel <code>sija2</code>).
                </div>
                <div style="display:flex; gap:10px;">
                    <?php if ($registered_id): ?>
                        <a href="cetak_kartu.php?id=<?= $registered_id; ?>" target="_blank" class="btn-secondary" style="padding:6px 14px; font-size:0.8rem;">
                            🖨️ Cetak Kartu
                        </a>
                    <?php endif; ?>
                    <a href="data_peserta.php" class="btn-secondary" style="padding:6px 14px; font-size:0.8rem;">
                        📋 Lihat Tabel Data
                    </a>
                </div>
            </div>
        <?php elseif ($status === 'error'): ?>
            <div class="alert-banner alert-danger">
                <div>
                    <strong>❌ Terjadi Kesalahan:</strong> <?= htmlspecialchars(urldecode($msg)); ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="app-grid">
            
            <!-- Left Column: Form Pendaftaran -->
            <div class="glass-card">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">📝 Formulir Pendaftaran Peserta</h3>
                        <p class="card-subtitle">Isi data identitas diri dan informasi game yang akan diperlombakan.</p>
                    </div>
                </div>

                <form action="proses_daftar.php" method="POST" id="formDaftar">
                    
                    <!-- Section 1: Identitas Siswa SIJA -->
                    <div class="form-section-title">
                        <span>👤</span> 1. Identitas Peserta / Tim
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="inputNama">Nama Lengkap Siswa / Ketua Tim <span class="req">*</span></label>
                        <div class="input-wrapper">
                            <span class="input-icon">🧑‍💻</span>
                            <input type="text" id="inputNama" name="nama" class="form-control" placeholder="Contoh: Muhammad Devin" maxlength="80" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="inputKelas">Kelas SIJA <span class="req">*</span></label>
                            <select id="inputKelas" name="kelas" class="form-control" required>
                                <option value="">-- Pilih Kelas --</option>
                                <option value="X SIJA 1">X SIJA 1</option>
                                <option value="X SIJA 2">X SIJA 2</option>
                                <option value="XI SIJA 1">XI SIJA 1</option>
                                <option value="XI SIJA 2" selected>XI SIJA 2</option>
                                <option value="XII SIJA 1">XII SIJA 1</option>
                                <option value="XII SIJA 2">XII SIJA 2</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="inputEmail">Email Aktif <span class="req">*</span></label>
                            <div class="input-wrapper">
                                <span class="input-icon">📧</span>
                                <input type="email" id="inputEmail" name="email" class="form-control" placeholder="nama@gmail.com" maxlength="80" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin <span class="req">*</span></label>
                        <div class="gender-options">
                            <label class="gender-card">
                                <input type="radio" name="gender" value="Laki-laki" checked>
                                <div class="gender-box">
                                    <span>👨</span> Laki-laki
                                </div>
                            </label>
                            <label class="gender-card">
                                <input type="radio" name="gender" value="Perempuan">
                                <div class="gender-box">
                                    <span>👩</span> Perempuan
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Section 2: Informasi Game -->
                    <div class="form-section-title">
                        <span>🎮</span> 2. Spesifikasi Proyek Game
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="inputJudul">Judul Game <span class="req">*</span></label>
                        <div class="input-wrapper">
                            <span class="input-icon">🕹️</span>
                            <input type="text" id="inputJudul" name="judul_game" class="form-control" placeholder="Contoh: Cyber Runner SIJA" maxlength="60" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="inputGenre">Kategori / Genre Game <span class="req">*</span></label>
                            <select id="inputGenre" name="genre" class="form-control" required>
                                <option value="2D Platformer">2D Platformer</option>
                                <option value="Action & Adventure">Action & Adventure</option>
                                <option value="RPG (Role Playing Game)">RPG (Role Playing Game)</option>
                                <option value="Puzzle & Logic">Puzzle & Logic</option>
                                <option value="Endless Runner">Endless Runner</option>
                                <option value="Horror & Mystery">Horror & Mystery</option>
                                <option value="Edu-Game / Simulasi">Edu-Game / Simulasi</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="inputEngine">Game Engine / Tools <span class="req">*</span></label>
                            <select id="inputEngine" name="engine" class="form-control" required>
                                <option value="Godot Engine">Godot Engine</option>
                                <option value="Unity">Unity</option>
                                <option value="Unreal Engine">Unreal Engine</option>
                                <option value="Construct 3">Construct 3</option>
                                <option value="Scratch">Scratch</option>
                                <option value="Pygame (Python)">Pygame (Python)</option>
                                <option value="HTML5 / Phaser">HTML5 / Phaser JS</option>
                                <option value="Roblox Studio">Roblox Studio</option>
                                <option value="Custom / Lainnya">Custom / Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="inputPlatform">Target Platform <span class="req">*</span></label>
                            <select id="inputPlatform" name="platform" class="form-control" required>
                                <option value="PC / Windows">PC / Windows</option>
                                <option value="Android / Mobile">Android / Mobile</option>
                                <option value="Web Browser (HTML5)">Web Browser (HTML5)</option>
                                <option value="Multiplatform">Multiplatform</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="inputWebsite">Link Demo / Portofolio / GitHub</label>
                            <div class="input-wrapper">
                                <span class="input-icon">🔗</span>
                                <input type="url" id="inputWebsite" name="website" class="form-control" placeholder="https://github.com/akun/game" maxlength="200">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="inputDeskripsi">
                            Sinopsis Ide Game & Mekanisme Gameplay
                            <span style="float:right; font-size:0.75rem; color:var(--text-dim);"><span id="charCount">0</span> karakter</span>
                        </label>
                        <textarea id="inputDeskripsi" name="deskripsi" class="form-control no-icon" rows="4" placeholder="Jelaskan cerita singkat, tujuan game, dan fitur gameplay yang menarik..."></textarea>
                    </div>

                    <!-- Terms agreement -->
                    <label class="checkbox-container">
                        <input type="checkbox" required checked>
                        <span>Saya menyatakan bahwa data yang dimasukkan benar dan game yang didaftarkan merupakan karya orisinal siswa SIJA.</span>
                    </label>

                    <!-- Tombol Submit -->
                    <button type="submit" class="btn-submit">
                        <span>🚀</span> Kirim Pendaftaran Game
                    </button>

                </form>
            </div>

            <!-- Right Column: Interactive Live Game Pass Preview & Timeline -->
            <div class="preview-sticky">
                
                <!-- Live Virtual Card -->
                <div class="game-pass-card">
                    <div class="pass-header">
                        <div class="pass-chip">
                            <span>🎮</span> GAME PASS
                        </div>
                        <div class="pass-tag">SIJA 2026</div>
                    </div>

                    <div class="game-avatar-box" id="cardIcon">
                        🤖
                    </div>

                    <h4 class="pass-game-title" id="cardJudul">Judul Proyek Game</h4>
                    <p class="pass-author">
                        Oleh: <span id="cardNama">Nama Peserta / Tim</span> (<span id="cardKelas">XI SIJA 2</span>)
                    </p>

                    <div class="pass-badges">
                        <span class="badge-tag cyan" id="cardGenre">2D Platformer</span>
                        <span class="badge-tag purple" id="cardEngine">Godot Engine</span>
                        <span class="badge-tag emerald" id="cardPlatform">PC / Windows</span>
                    </div>

                    <div class="pass-desc-box" id="cardDesc">
                        Sinopsis dan keunikan mekanisme gameplay kamu akan tampil otomatis secara real-time di sini...
                    </div>
                </div>

                <!-- Timeline / Informasi Lomba Card -->
                <div class="timeline-card">
                    <h4 style="font-size: 0.95rem; font-weight: 700; color: #fff; margin-bottom: 16px; display:flex; align-items:center; gap:8px;">
                        <span>📅</span> Timeline Kompetisi Game SIJA
                    </h4>

                    <div class="timeline-item">
                        <div class="timeline-icon">1</div>
                        <div class="timeline-content">
                            <h4>Pendaftaran Peserta</h4>
                            <p>Batas pengisian formulir online hingga tahap seleksi berkas.</p>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-icon">2</div>
                        <div class="timeline-content">
                            <h4>Pengumpulan Build Game</h4>
                            <p>Upload file APK / Executable / Link Web ke panitia SIJA.</p>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-icon">3</div>
                        <div class="timeline-content">
                            <h4>Showcase & Pengumuman Juara</h4>
                            <p>Presentasi di laboratorium SIJA & demonstrasi game di hadapan juri.</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Footer -->
    <footer>
        <div class="container" style="padding-bottom: 0;">
            <p>© 2026 <strong>SIJA GameFest</strong> • Aplikasi Pendaftaran Lomba Game Berbasis HTML, CSS, PHP & MySQL</p>
            <p style="margin-top: 6px; font-size: 0.8rem;">Database: <code>datadepin</code> | Tabel: <code>sija2</code> | Folder: <code>letphp</code></p>
        </div>
    </footer>

    <!-- Interactive Script -->
    <script src="assets/js/script.js"></script>
</body>
</html>
