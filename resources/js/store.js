import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex);

export const store = new Vuex.Store({
    state: {
        wishlists:[],
        carts:[],
        currencies:[],
        currency:{
            icon:'',
            name:'',
            rate:'',
        },
        quickViewId: null,
        quickViewProduct: {},
        newArrivalProductDateSet: 15,
        trendingProductOrderDate: 30,
        trendingProductOrderCount: 50,
    },

    mutations: {
        // Get Wishlist Product Information
        setWishlist(state){
            state.wishlists = localStorage.getItem(btoa('wishlist')) !== null ? JSON.parse(atob(localStorage.getItem(btoa('wishlist')))) : [];
        },

        // Add Wishlist Product Information
        updateWishlist(state, product){
            let productData = {id: product.id, name: product.name, slug: product.slug, feature_image: product.feature_image, stock_status: product.stock_status, price: product.price, discount_fixed: product.discount_fixed};
            state.wishlists.push(productData);
            localStorage.setItem(btoa('wishlist'), btoa(JSON.stringify(state.wishlists)));
        },

        // Remove Wishlist Product Information
        deleteWishlist(state, key){
            state.wishlists.splice(key, 1);
            localStorage.setItem(btoa('wishlist'), btoa(JSON.stringify(state.wishlists)));
        },

        // Get Cart Product Information
        setCart(state){
            state.carts = localStorage.getItem(btoa('carts')) !== null ? JSON.parse(atob(localStorage.getItem(btoa('carts')))) : [];
        },

        // Add Cart Product Information
        updateCart(state, cart){
            let cartData = {id: cart.product.id, name: cart.product.name, slug: cart.product.slug, feature_image: cart.product.feature_image, price: cart.product.price, discount_fixed: cart.product.discount_fixed, color_code: cart.colorCode, color_name: cart.colorName, variant: cart.variantName, quantity: cart.quantity};
            state.carts.push(cartData);
            localStorage.setItem(btoa('carts'), btoa(JSON.stringify(state.carts)));
        },

        // Cart Product Update
        productUpdateCart(state, products){
            state.carts = products;
            localStorage.setItem(btoa('carts'), btoa(JSON.stringify(state.carts)));
        },

        // Remove Cart Product Information
        deleteCart(state, key){
            state.carts.splice(key, 1);
            localStorage.setItem(btoa('carts'), btoa(JSON.stringify(state.carts)));
        },

        // Remove Cart Product Information
        removeAllCart(state){
            localStorage.removeItem(btoa('carts'));
            state.carts = [];
        },

        // Get Currency Information
        setCurrency(state, currency){
            state.currency.icon = currency.icon;
            state.currency.name = currency.name;
            state.currency.rate = currency.rate;
            localStorage.setItem(btoa('currency'), btoa(currency.id));
        },

        // Change Currency
        setCurrencies(state, currencies){
            state.currencies = currencies;
        },

        // Product Quick View
        setQuickView(state, product){
            if(state.quickViewId === product.id) {
                state.quickViewId = null;
                state.quickViewProduct = {};
            } else {
                state.quickViewId = product.id;
                state.quickViewProduct = product;
            }
        },
    },

    actions: {
        // Get Wishlist Product Information
        getWishlist(context){
            context.commit('setWishlist');
        },

        // Add Wishlist Product Information
        addWishlist(context, product){
            context.commit('updateWishlist', product);
        },

        // Remove Wishlist Product Information
        removeWishlist(context, key){
            context.commit('deleteWishlist', key);
        },

        // Get Cart Product Information
        getCart(context){
            context.commit('setCart');
        },

        // Add Cart Product Information
        addCart(context, cart){
            context.commit('updateCart', cart);
        },

        // Cart Product Update
        cartProductUpdate(context, products){
            context.commit('productUpdateCart', products);
        },

        // Remove Cart Product Information
        removeCart(context, key){
            context.commit('deleteCart', key);
        },

        // Remove Cart Product Information
        deleteAllCart(context){
            context.commit('removeAllCart');
        },

        // Get Currency Information
        getCurrencies(context){
            axios.get('/api/currency').then(response => {
                if (localStorage.getItem(btoa('currency'))) {
                    let userCurrency = response.data.currencies.find(currency => currency.id == atob(localStorage.getItem(btoa('currency'))));
                    if (userCurrency) {
                        context.commit('setCurrency', userCurrency);
                    } else {
                        localStorage.setItem(btoa('currency'), btoa(response.data.currency.id));
                        context.commit('setCurrency', response.data.currency);
                    }
                } else {
                    localStorage.setItem(btoa('currency'), btoa(response.data.currency.id));
                    context.commit('setCurrency', response.data.currency);
                }
                context.commit('setCurrencies', response.data.currencies);
            },
            ()=>{
                Fire.$emit('error', 'Something Wrong! Please try Again');
            });
        },

        // Change Currency
        changeCurrency(context, response){
            context.commit('setCurrency', response);
        },

        // Product Quick View
        quickView(context, response){
            context.commit('setQuickView', response);
        },
    },

    getters: {}
});