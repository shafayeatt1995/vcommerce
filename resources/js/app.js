/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


window.Vue = require('vue');

require('./bootstrap');
require('./style/bootstrap.min.css');
require('./style/fontawesome.css');
require('./style/slicknav.min.css');
require('./style/style.css');
require('./style/responsive.css');
// require('./style/sass.css');


// Auth User ID Global Access
Vue.prototype.$userId = typeof window.validation !== 'undefined' ? window.validation.id : '';


// Load Router
import router from './router'


// Page Title
import title from './title'
Vue.mixin(title);


// ACS(Access Control System) and here validation is auth user information.
import Middleware from './middleware';
Vue.prototype.$middleware = new Middleware(typeof window.validation !== 'undefined' ? window.validation : '');


// Vuex Store
import {store} from './store'


// V-form
import {Form, HasError, AlertError} from 'vform'
window.Form = Form;
Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);


// Sweet Alert Setup
import Swal from 'sweetalert2'
window.Swal = Swal;

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    }
});
window.Toast = Toast;


// Date Format
import moment from 'moment'
Vue.filter('date', value=>{
    return moment(value).format("DD-MMM-YY");
});

Vue.filter('date2', value=>{
    return moment(value).format("DD MMMM, YYYY");
});


// Currency Format
Vue.filter('currency', value=>{
    return +parseFloat(value).toFixed(2);
});


// Progress Bar
import VueProgressBar from 'vue-progressbar'
Vue.use(VueProgressBar, {
    color: '#FFCD42',
    failedColor: 'red',
    height: '3px'
});


// Vue Pagination
Vue.component('pagination', require('laravel-vue-pagination'));


// Custom Event
window.Fire = new Vue();


// Image Viewer
import 'viewerjs/dist/viewer.css'
import Viewer from 'v-viewer'
Vue.use(Viewer);


// Vue Editor
import Vue2Editor from "vue2-editor";
Vue.use(Vue2Editor);


// Vue Tool Tip
import VTooltip from 'v-tooltip'
Vue.use(VTooltip);


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

//*************** Laravel Passport for full Oauth2 Support ***************\\
// Vue.component('passport-clients', require('./components/passport/Clients.vue').default);
// Vue.component('passport-authorized-clients', require('./components/passport/AuthorizedClients.vue').default);
// Vue.component('passport-personal-access-tokens', require('./components/passport/PersonalAccessTokens.vue').default);

Vue.component('app', require('./components/app/app').default);
Vue.component('header-view', require('./components/app/partials/header').default);
Vue.component('footer-view', require('./components/app/partials/footer').default);
Vue.component('sidebar', require('./components/app/partials/sidebar').default);
Vue.component('product', require('./components/app/product').default);
Vue.component('quick-view', require('./components/app/quick-vue').default);
Vue.component('store-create', require('./components/app/storeCreate').default);
Vue.component('wishlist', require('./components/app/wishlist').default);
Vue.component('loading', require('./components/app/loading').default);
Vue.component('empty', require('./components/app/empty').default);
Vue.component('error', require('./components/app/error').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#vue',
    router,
    store
});
