import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter);

const routes = [
    // Public Route
    { path: '/', component: require('./components/public/home').default },
    { path: '*', component: require('./components/public/404').default },
    { path: '/login', component: require('./components/public/auth/login').default },
    { path: '/register', component: require('./components/public/auth/register').default },
    { path: '/:slug/product', component: require('./components/public/singleProduct').default },
    { path: '/about-us', component: require('./components/public/about-us').default },
    { path: '/contact-us', component: require('./components/public/contact-us').default },
    { path: '/faq', component: require('./components/public/faq').default },
    { path: '/terms-condition', component: require('./components/public/terms').default },


    // Auth Route
    { path: '/dashboard', component: require('./components/auth/dashboard').default },
    { path: '/edit-profile', component: require('./components/auth/edit-profile').default },
    { path: '/brand', component: require('./components/auth/brand').default },
    { path: '/category', component: require('./components/auth/category').default },
    { path: '/sub-category', component: require('./components/auth/sub-category').default },
    { path: '/store', component: require('./components/auth/store/store').default },
    { path: '/product', component: require('./components/auth/product/product').default },
    { path: '/product/create', component: require('./components/auth/product/product-create').default },
    { path: '/product/edit/:id', component: require('./components/auth/product/product-edit').default },
    { path: '/wishlist', component: require('./components/auth/wishlist').default },
    { path: '/cart', component: require('./components/auth/cart').default },
    { path: '/checkout', component: require('./components/auth/checkout').default },
    { path: '/checkout-success/:id', component: require('./components/auth/checkout-success').default },
    { path: '/shipping-cost', component: require('./components/auth/shipping-cost').default },
    { path: '/shipping-address', component: require('./components/auth/shipping-address').default },
    { path: '/coupon', component: require('./components/auth/coupon').default },
    { path: '/order-history', component: require('./components/auth/order-history').default },
    { path: '/order/:id', component: require('./components/auth/order-details').default },
    { path: '/site-setting', component: require('./components/auth/site-setting').default },
    { path: '/currency', component: require('./components/auth/currency').default },

];


const router = new VueRouter({
    routes,
    mode: 'history'
});

export default router;
