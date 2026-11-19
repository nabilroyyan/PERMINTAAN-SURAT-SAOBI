let progressInterval = null;
let progressValue = 0;
let overlayTimeout = null;
let autoHideTimeout = null;
let minHideTimeout = null;
let loadingShownAt = 0;

// Batas maksimal overlay loading tampil (failsafe), dalam milidetik
const MAX_LOADING_MS = 15000;

// Durasi minimal overlay tampil agar sempat dibaca user sebelum menutup perlahan
const MIN_LOADING_MS = 1800;

export function startProgressBar() {
    const bar = document.getElementById('global-top-progress-bar');
    if (!bar) return;

    clearInterval(progressInterval);
    progressValue = 15;
    bar.style.width = '15%';
    bar.style.opacity = '1';

    progressInterval = setInterval(() => {
        if (progressValue < 85) {
            const diff = (90 - progressValue) * 0.15;
            progressValue += Math.max(diff, 1.5);
            bar.style.width = `${progressValue}%`;
        }
    }, 150);
}

export function finishProgressBar() {
    const bar = document.getElementById('global-top-progress-bar');
    if (!bar) return;

    clearInterval(progressInterval);
    progressValue = 100;
    bar.style.width = '100%';

    setTimeout(() => {
        bar.style.opacity = '0';
        setTimeout(() => {
            bar.style.width = '0%';
            progressValue = 0;
        }, 300);
    }, 200);
}

export function showLoading(title = 'Memuat Halaman...', subtitle = 'Mohon tunggu sebentar...') {
    startProgressBar();

    const overlay = document.getElementById('global-loading-overlay');
    const titleEl = document.getElementById('global-loading-title');
    const subtitleEl = document.getElementById('global-loading-subtitle');

    if (overlay) {
        if (titleEl) titleEl.textContent = title;
        if (subtitleEl) subtitleEl.textContent = subtitle;

        overlay.classList.remove('opacity-0', 'pointer-events-none');
        overlay.classList.add('opacity-100');
        const card = overlay.querySelector('.loading-card');
        if (card) {
            card.classList.remove('scale-95');
            card.classList.add('scale-100');
        }
    }

    // Failsafe: overlay hanya tampil maksimal 15 detik, lalu tertutup otomatis.
    loadingShownAt = Date.now();
    clearTimeout(minHideTimeout);
    clearTimeout(autoHideTimeout);
    autoHideTimeout = setTimeout(() => hideLoading(), MAX_LOADING_MS);
}

export function hideLoading() {
    clearTimeout(autoHideTimeout);

    // Pastikan overlay tampil minimal 1 detik agar tidak berkedip sekejap.
    const elapsed = Date.now() - loadingShownAt;
    if (loadingShownAt > 0 && elapsed < MIN_LOADING_MS) {
        clearTimeout(minHideTimeout);
        minHideTimeout = setTimeout(() => hideLoading(), MIN_LOADING_MS - elapsed);
        return;
    }

    loadingShownAt = 0;
    finishProgressBar();

    const overlay = document.getElementById('global-loading-overlay');
    if (overlay) {
        overlay.classList.remove('opacity-100');
        overlay.classList.add('opacity-0', 'pointer-events-none');
        const card = overlay.querySelector('.loading-card');
        if (card) {
            card.classList.remove('scale-100');
            card.classList.add('scale-95');
        }
    }
}

// Menyesuaikan teks loading dengan halaman yang sedang dituju
function getNavigationMessage(pathname) {
    if (!pathname) return { title: 'Memuat Halaman...', subtitle: 'Mohon tunggu sebentar...' };
    const lower = pathname.toLowerCase();

    if (lower.includes('/admin/dashboard')) {
        return { title: 'Membuka Dashboard Admin...', subtitle: 'Menyiapkan statistik dan aktivitas desa...' };
    }
    if (lower.includes('/client/dashboard')) {
        return { title: 'Membuka Dashboard Warga...', subtitle: 'Menyiapkan ringkasan layanan surat Anda...' };
    }
    if (lower.includes('/surat/create')) {
        return { title: 'Membuka Formulir Surat...', subtitle: 'Menyiapkan lembar permohonan baru...' };
    }
    if (lower.includes('/admin/permohonan')) {
        return { title: 'Memuat Permohonan Surat...', subtitle: 'Menyiapkan daftar verifikasi surat warga...' };
    }
    if (lower.includes('/client/surat')) {
        return { title: 'Memuat Riwayat Surat...', subtitle: 'Mengambil riwayat pengajuan surat Anda...' };
    }
    if (lower.includes('/admin/penduduk')) {
        return { title: 'Memuat Data Kependudukan...', subtitle: 'Mengakses arsip data warga Desa Saobi...' };
    }
    if (lower.includes('/admin/jenis-surat')) {
        return { title: 'Memuat Jenis & Format Surat...', subtitle: 'Menyiapkan konfigurasi template surat...' };
    }
    if (lower.includes('/admin/pengaturan')) {
        return { title: 'Membuka Pengaturan Desa...', subtitle: 'Menyiapkan data Kades dan tanda tangan...' };
    }
    if (lower.includes('/profile')) {
        return { title: 'Membuka Profil Pengguna...', subtitle: 'Menyiapkan pengaturan akun...' };
    }
    return { title: 'Memuat Halaman...', subtitle: 'Mohon tunggu sebentar...' };
}

// Menyesuaikan teks loading dengan aksi formulir / permintaan yang dikirim
function getFormSubmitMessage(form) {
    const action = (form.getAttribute('action') || '').toLowerCase();
    const method = (form.getAttribute('method') || 'GET').toUpperCase();

    // Cek jika terdapat upload file
    const fileInput = form.querySelector('input[type="file"]');
    if (fileInput && fileInput.files && fileInput.files.length > 0) {
        return { title: 'Mengunggah Berkas & Memproses...', subtitle: 'Mohon jangan menutup atau me-refresh halaman...' };
    }

    if (action.includes('/logout')) {
        return { title: 'Keluar dari Sistem...', subtitle: 'Mengakhiri sesi akun dengan aman...' };
    }
    if (action.includes('/login')) {
        return { title: 'Memverifikasi Akun...', subtitle: 'Memeriksa kredensial masuk...' };
    }
    if (action.includes('/register')) {
        return { title: 'Mendaftarkan Akun...', subtitle: 'Menyimpan data pendaftaran warga...' };
    }
    if (action.includes('/terbitkan')) {
        return { title: 'Menerbitkan Surat Resmi...', subtitle: 'Membuat nomor surat & dokumen PDF...' };
    }
    if (action.includes('/status')) {
        return { title: 'Memperbarui Status Surat...', subtitle: 'Menyimpan keputusan verifikasi...' };
    }
    if (action.includes('/surat') && method === 'POST') {
        return { title: 'Mengirim Permohonan Surat...', subtitle: 'Menyimpan berkas pengajuan warga...' };
    }
    if (action.includes('/penduduk') && (method === 'POST' || action.includes('/import'))) {
        return { title: 'Menyimpan Data Penduduk...', subtitle: 'Memperbarui arsip kependudukan desa...' };
    }
    if (action.includes('/pengaturan') && method === 'POST') {
        return { title: 'Menyimpan Pengaturan Desa...', subtitle: 'Memperbarui profil pejabat & tanda tangan...' };
    }
    if (action.includes('/profile')) {
        return { title: 'Menyimpan Profil Akun...', subtitle: 'Memperbarui informasi pengguna...' };
    }
    return { title: 'Memproses Permintaan...', subtitle: 'Mohon tunggu sebentar...' };
}

export function initLoadingHandlers() {
    // 1. Tangani klik tautan (navigasi halaman)
    document.addEventListener('click', (e) => {
        const link = e.target.closest('a');
        if (!link) return;

        const href = link.getAttribute('href');
        if (!href) return;

        // Abaikan tautan lokal/khusus
        if (
            href.startsWith('#') ||
            href.startsWith('javascript:') ||
            href.startsWith('mailto:') ||
            href.startsWith('tel:') ||
            link.getAttribute('target') === '_blank' ||
            link.hasAttribute('download') ||
            e.ctrlKey || e.metaKey || e.shiftKey || e.altKey
        ) {
            return;
        }

        let targetUrl;
        try {
            targetUrl = new URL(link.href, window.location.origin);
        } catch (err) {
            return;
        }

        const path = targetUrl.pathname;

        // Tautan unduhan berkas (PDF surat, berkas dokumen, template/sample Excel).
        // Responsnya berupa attachment sehingga halaman TIDAK berpindah — jangan
        // biarkan overlay loading menggantung. Cukup tampilkan indikator singkat.
        const isDownloadLink =
            /\/download(\/|$)/.test(path) ||
            /\/dokumen\//.test(path) ||
            path.endsWith('/template');

        if (isDownloadLink) {
            // Overlay akan tertutup otomatis lewat failsafe 5 detik di showLoading().
            showLoading('Menyiapkan Berkas Dokumen...', 'Sedang mengunduh berkas...');
            return;
        }

        // Navigasi internal ke halaman lain
        const isSameOrigin = targetUrl.origin === window.location.origin;
        const isDifferentPage = path !== window.location.pathname || targetUrl.search !== window.location.search;

        if (isSameOrigin && isDifferentPage) {
            const msg = getNavigationMessage(path);
            startProgressBar();

            clearTimeout(overlayTimeout);
            // Munculkan overlay halus jika proses navigasi butuh waktu > 180ms
            overlayTimeout = setTimeout(() => {
                showLoading(msg.title, msg.subtitle);
            }, 180);
        }
    });

    // 2. Tangani pengiriman formulir (submit permintaan)
    document.addEventListener('submit', (e) => {
        const form = e.target;
        if (!form) return;

        if (form.getAttribute('target') === '_blank') return;

        const msg = getFormSubmitMessage(form);
        showLoading(msg.title, msg.subtitle);

        // Berikan status loading pada tombol submit agar tidak dobel klik
        const submitButtons = form.querySelectorAll('button[type="submit"], input[type="submit"]');
        submitButtons.forEach(btn => {
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-not-allowed');
        });
    });

    // 3. Reset jika pengguna kembali (Back/Forward Cache browser)
    window.addEventListener('pageshow', () => {
        clearTimeout(overlayTimeout);
        hideLoading();
    });

    // Ekspos secara global agar bisa dipanggil secara manual jika diperlukan
    window.showLoading = showLoading;
    window.hideLoading = hideLoading;
    window.startProgressBar = startProgressBar;
    window.finishProgressBar = finishProgressBar;
}
