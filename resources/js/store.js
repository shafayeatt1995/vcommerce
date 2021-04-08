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
    },

    mutations: {
        setWishlist(state){
            state.wishlists = localStorage.getItem(btoa('wishlist')) !== null ? JSON.parse(atob(localStorage.getItem(btoa('wishlist')))) : [];
        },

        updateWishlist(state, product){
            let productData = {id: product.id, name: product.name, slug: product.slug, feature_image: product.feature_image, stock_status: product.stock_status, price: product.price, discount_fixed: product.discount_fixed};
            state.wishlists.push(productData);
            localStorage.setItem(btoa('wishlist'), btoa(JSON.stringify(state.wishlists)));
        },

        deleteWishlist(state, key){
            state.wishlists.splice(key, 1);
            localStorage.setItem(btoa('wishlist'), btoa(JSON.stringify(state.wishlists)));
        },

        setCart(state){
            state.carts = localStorage.getItem(btoa('carts')) !== null ? JSON.parse(atob(localStorage.getItem(btoa('carts')))) : [];
        },

        updateCart(state, cart){
            let cartData = {id: cart.product.id, name: cart.product.name, slug: cart.product.slug, feature_image: cart.product.feature_image, price: cart.product.price, discount_fixed: cart.product.discount_fixed, color_code: cart.colorCode, color_name: cart.colorName, variant: cart.variantName, quantity: cart.quantity};
            state.carts.push(cartData);
            localStorage.setItem(btoa('carts'), btoa(JSON.stringify(state.carts)));
        },

        productUpdateCart(state, products){
            state.carts = products;
            localStorage.setItem(btoa('carts'), btoa(JSON.stringify(state.carts)));
        },

        deleteCart(state, key){
            state.carts.splice(key, 1);
            localStorage.setItem(btoa('carts'), btoa(JSON.stringify(state.carts)));
        },

        setCurrency(state, currency){
            state.currency.icon = currency.icon;
            state.currency.name = currency.name;
            state.currency.rate = currency.rate;
            localStorage.setItem(btoa('currency'), btoa(currency.id));
        },

        setCurrencies(state, currencies){
            state.currencies = currencies;
        }
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

        // Remove Cart Product Information
        removeCart(context, key){
            context.commit('deleteCart', key);
        },

        // Cart Product Update
        cartProductUpdate(context, products){
            context.commit('productUpdateCart', products);
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
    },

    getters: {}
});