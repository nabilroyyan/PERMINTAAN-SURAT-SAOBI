import axios from 'axios';
import './bootstrap';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import '../css/app.css';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
