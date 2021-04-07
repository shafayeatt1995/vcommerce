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
        setWishlist(state, wishlist){
            state.wishlists = wishlist;
        },

        setCart(state, cart){
            state.carts = cart;
        },

        setCurrency(state, currency){
            state.currency.icon = currency.icon;
            state.currency.name = currency.name;
            state.currency.rate = currency.rate;
        },

        setCurrencies(state, currencies){
            state.currencies = currencies;
        }
    },
    actions: {
        // Get Wishlist Information
        getWishlist(context){
            axios.get('/api/wishlist').then(response => {
                context.commit('setWishlist', response.data);
            });
        },

        // Get Cart Information
        getCart(context){
            axios.get('/api/cart').then(response => {
                context.commit('setCart', response.data);
            });
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
            localStorage.setItem(btoa('currency'), btoa(response.id));
            context.commit('setCurrency', response);
        },
    },
    getters: {}
});