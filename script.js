/**
 * SIJA GAME DEV ARENA 2026 - Main Interactive JavaScript
 * Menangani Live Preview Card, Filter & Search Tabel, Modal Detail, Alert Auto-Dismiss
 */

document.addEventListener('DOMContentLoaded', function () {
    // ==========================================
    // 1. LIVE GAME CARD PREVIEW (FORM PENDAFTARAN)
    // ==========================================
    const inputNama = document.getElementById('inputNama');
    const inputKelas = document.getElementById('inputKelas');
    const inputJudul = document.getElementById('inputJudul');
    const inputGenre = document.getElementById('inputGenre');
    const inputEngine = document.getElementById('inputEngine');
    const inputPlatform = document.getElementById('inputPlatform');
    const inputDeskripsi = document.getElementById('inputDeskripsi');
    const charCounter = document.getElementById('charCount');

    const cardNama = document.getElementById('cardNama');
    const cardJudul = document.getElementById('cardJudul');
    const cardKelas = document.getElementById('cardKelas');
    const cardGenre = document.getElementById('cardGenre');
    const cardEngine = document.getElementById('cardEngine');
    const cardPlatform = document.getElementById('cardPlatform');
    const cardDesc = document.getElementById('cardDesc');
    const cardIcon = document.getElementById('cardIcon');

    // Emoji icon untuk masing-masing Game Engine
    const engineIcons = {
        'Godot Engine': '\u{1F916}',      // 🤖
        'Unity': '\u{26A1}',             // ⚡
        'Unreal Engine': '\u{1F525}',     // 🔥
        'Construct 3': '\u{1F9E9}',       // 🧩
        'Scratch': '\u{1F431}',           // 🐱
        'Pygame (Python)': '\u{1F40D}',   // 🐍
        'HTML5 / Phaser': '\u{1F310}',    // 🌐
        'Roblox Studio': '\u{1F9F1}',     // 🧱
        'Custom / Lainnya': '\u{1F6E0}\u{FE0F}' // 🛠️
    };

    if (inputJudul && cardJudul) {
        inputJudul.addEventListener('input', function () {
            cardJudul.textContent = this.value.trim() || 'Judul Proyek Game';
        });
    }

    if (inputNama && cardNama) {
        inputNama.addEventListener('input', function () {
            cardNama.textContent = this.value.trim() || 'Nama Peserta / Tim';
        });
    }

    if (inputKelas && cardKelas) {
        inputKelas.addEventListener('change', function () {
            cardKelas.textContent = this.value || 'XI SIJA 2';
        });
    }

    if (inputGenre && cardGenre) {
        inputGenre.addEventListener('change', function () {
            cardGenre.textContent = this.value || '2D Platformer';
        });
    }

    if (inputEngine && cardEngine) {
        inputEngine.addEventListener('change', function () {
            const val = this.value || 'Godot Engine';
            cardEngine.textContent = val;
            if (cardIcon && engineIcons[val]) {
                cardIcon.textContent = engineIcons[val];
            }
        });
    }

    if (inputPlatform && cardPlatform) {
        inputPlatform.addEventListener('change', function () {
            cardPlatform.textContent = this.value || 'PC / Windows';
        });
    }

    if (inputDeskripsi && cardDesc) {
        inputDeskripsi.addEventListener('input', function () {
            const text = this.value.trim();
            cardDesc.textContent = text || 'Sinopsis dan keunikan mekanisme gameplay kamu akan tampil otomatis secara real-time di sini...';
            if (charCounter) {
                charCounter.textContent = this.value.length;
            }
        });
    }

    // ==========================================
    // 2. LIVE SEARCH & FILTER (DATA PESERTA)
    // ==========================================
    const tableSearch = document.getElementById('tableSearch');
    const genreFilter = document.getElementById('genreFilter');
    const engineFilter = document.getElementById('engineFilter');
    const dataTable = document.getElementById('pesertaTable');

    if (dataTable && (tableSearch || genreFilter || engineFilter)) {
        function filterTable() {
            const query = tableSearch ? tableSearch.value.toLowerCase().trim() : '';
            const selectedGenre = genreFilter ? genreFilter.value.toLowerCase().trim() : '';
            const selectedEngine = engineFilter ? engineFilter.value.toLowerCase().trim() : '';
            const rows = dataTable.querySelectorAll('tbody tr.data-row');

            let visibleCount = 0;

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                const rowGenre = (row.getAttribute('data-genre') || '').toLowerCase();
                const rowEngine = (row.getAttribute('data-engine') || '').toLowerCase();

                const matchQuery = !query || text.includes(query);
                const matchGenre = !selectedGenre || rowGenre.includes(selectedGenre);
                const matchEngine = !selectedEngine || rowEngine.includes(selectedEngine);

                if (matchQuery && matchGenre && matchEngine) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            const emptyState = document.getElementById('noResultsRow');
            if (emptyState) {
                emptyState.style.display = visibleCount === 0 ? '' : 'none';
            }
        }

        if (tableSearch) tableSearch.addEventListener('input', filterTable);
        if (genreFilter) genreFilter.addEventListener('change', filterTable);
        if (engineFilter) engineFilter.addEventListener('change', filterTable);
    }

    // ==========================================
    // 3. AUTO-HIDE ALERT BANNER
    // ==========================================
    const alerts = document.querySelectorAll('.alert-banner');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 500);
        }, 5000);
    });

    // ==========================================
    // 4. MODAL CLOSE EVENT LISTENERS
    // ==========================================
    const modal = document.getElementById('detailModal');
    if (modal) {
        // Klik di luar area modal content (overlay background)
        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                closeDetailModal();
            }
        });

        // Tutup modal dengan tombol Escape (ESC)
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modal.classList.contains('active')) {
                closeDetailModal();
            }
        });
    }
});

// ==========================================
// 5. GLOBAL HELPER FUNCTIONS
// ==========================================

/**
 * Menampilkan modal pop-up detail proyek game siswa
 */
function showDetailModal(nama, judul, kelas, genre, engine, platform, website, deskripsi) {
    const modal = document.getElementById('detailModal');
    if (!modal) return;

    document.getElementById('mNama').textContent = nama || '-';
    document.getElementById('mJudul').textContent = judul || '-';
    document.getElementById('mKelas').textContent = kelas || '-';
    document.getElementById('mGenre').textContent = genre || '-';
    document.getElementById('mEngine').textContent = engine || '-';
    document.getElementById('mPlatform').textContent = platform || '-';

    const webWrapper = document.getElementById('mWebsiteWrapper');
    const webLink = document.getElementById('mWebsite');
    if (webWrapper && webLink) {
        if (website && website !== '-') {
            const formattedUrl = website.startsWith('http') ? website : 'https://' + website;
            webLink.href = formattedUrl;
            webLink.textContent = website;
            webLink.style.display = 'inline-block';
            webWrapper.innerHTML = '';
            webWrapper.appendChild(webLink);
        } else {
            webWrapper.innerHTML = '<span style="color:var(--text-dim); font-size:0.85rem;">-</span>';
        }
    }

    const deskripsiEl = document.getElementById('mDeskripsi');
    if (deskripsiEl) {
        deskripsiEl.textContent = deskripsi || 'Tidak ada sinopsis / deskripsi tambahan.';
    }

    modal.classList.add('active');
}

/**
 * Menutup modal pop-up detail proyek game
 */
function closeDetailModal() {
    const modal = document.getElementById('detailModal');
    if (modal) {
        modal.classList.remove('active');
    }
}

/**
 * Konfirmasi dialog sebelum menghapus data peserta
 */
function confirmDelete(id, nama) {
    if (confirm(`Yakin ingin menghapus data peserta "${nama}" (ID: #${id}) dari database? Tindakan ini tidak dapat dibatalkan.`)) {
        window.location.href = `hapus_peserta.php?id=${id}`;
    }
}
