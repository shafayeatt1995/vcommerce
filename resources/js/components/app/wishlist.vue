<template>
    <button type="button" :class="wishlists.some(wishlist => wishlist.product_id === productId) ? activeClass : deactiveClass" @click="wishlistStatus(productId)" v-html="wishlists.some(wishlist => wishlist.product_id === productId) ? activeText : deactiveText"></button>
</template>

<script>
    export default {
        props: {
            productId: '',
            activeClass: '',
            deactiveClass: '',
            activeText: '',
            deactiveText: '',
        },

        methods: {
            wishlistStatus(id) {
                this.$Progress.start();
                if (this.$middleware.authCheck()) {
                    axios.post('/api/wishlist/create/' + id).then(response => {
                        this.$store.dispatch('getWishlist');
                        Fire.$emit('success', response.data);
                        this.$Progress.finish();
                    },
                    () => {
                        this.$Progress.fail();
                    }
                    );
                } else {
                    Fire.$emit('info', 'Login First For Add To Wishlist');
                    this.$Progress.finish();
                }
            },
        },

        computed: {
            wishlists() {
                return this.$store.state.wishlists;
            },
        },
    };
</script>