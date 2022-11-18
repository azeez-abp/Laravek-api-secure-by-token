import './bootstrap';

import Alpine from 'alpinejs';
import request  ,{ axios_request } from './request';
window.Alpine = Alpine;
window.request  =  request //attch request to window to make oit global
Alpine.start();

