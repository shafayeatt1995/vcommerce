<template>
    <button type="button"
            :class="wishlists.some(wishlist => wishlist.id === product.id) ? activeClass : deactiveClass"
            @click="wishlists.findIndex(wishlist => wishlist.id === product.id) !== -1 ? removeWishlist(wishlists.findIndex(wishlist => wishlist.id === product.id)) : addWishlist(product)"
            v-html="wishlists.some(wishlist => wishlist.id === product.id) ? activeText : deactiveText">
    </button>
</template>

<script>
    export default {
        props: {
            product: {},
            activeClass: '',
            deactiveClass: '',
            activeText: '',
            deactiveText: '',
        },

        methods: {
            // Add Product To Wishlist
            addWishlist(product) {
                this.$store.dispatch('addWishlist', product);
                Fire.$emit('success', 'Product Successfully Add To Wishlist');
            },

            // Remove Product Wishlist
            removeWishlist(key){
                this.$store.dispatch('removeWishlist', key);
                Fire.$emit('success', 'Product Successfully Remove Your Wishlist');
            },
        },

        computed: {
            wishlists() {
                return this.$store.state.wishlists;
            },
        },
    };
</script>