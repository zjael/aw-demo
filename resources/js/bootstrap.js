import Alpine from 'alpinejs'
import axios from 'axios';

window.axios = axios;
window.Alpine = Alpine

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

Alpine.start();
