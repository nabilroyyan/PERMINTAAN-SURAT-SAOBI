import './bootstrap';

import Alpine from 'alpinejs';
import { initLoadingHandlers } from './loading';
import { initLiveSync } from './live-sync';

window.Alpine = Alpine;

Alpine.start();

// Inisialisasi indikator & animasi loading global
initLoadingHandlers();

// Inisialisasi sinkronisasi data real-time tanpa perlu refresh manual
initLiveSync();
