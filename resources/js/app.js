import './bootstrap';

import 'admin-lte/plugins/jquery/jquery.min.js';

import Swal from 'admin-lte/plugins/sweetalert2/sweetalert2.min.js';

import 'admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js';

import 'admin-lte/dist/js/adminlte.min.js';

import { createApp } from 'vue/dist/vue.esm-bundler.js';

import { createRouter, createWebHistory } from 'vue-router';

import Routes from './routes.js';

window.showSuccessToast = function () {
    Swal.fire({
        icon: 'success',
        title: 'File uploaded successfully!',
        showConfirmButton: false,
        timer: 2000 
    });
};

const app = createApp({});

const router = createRouter({
    routes: Routes,
    history: createWebHistory(),
});

app.use(router);
app.mount('#app');