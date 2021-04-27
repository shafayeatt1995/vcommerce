<template>
    <!-- Wish list area start-->
    <div class="wishlist">
        <div class="container">
            <div class="section-title">
                <div class="row">
                    <div class="col-lg-12">
                        <h3><i class="far fa-heart"></i> Wishlist</h3>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="shopping-cart-body">
                <table class="table shopping-cart-table table-responsive-sm">
                    <thead class="thead-light text-center">
                    <tr>
                        <th scope="col">Image</th>
                        <th scope="col">Product</th>
                        <th scope="col">Status</th>
                        <th scope="col">Price</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-if="wishlists.length <= 0">
                        <td colspan="5">
                            <empty message="Your wishlist is empty"></empty>
                        </td>
                    </tr>
                    <tr v-for="(data, key) in wishlists" :key="key" v-else>
                        <td>
                            <router-link :to="data.slug + '/product'"><img :src="data.feature_image" :alt="data.name"/></router-link>
                        </td>
                        <td class="text-left">
                            <router-link :to="data.slug + '/product'">{{data.name}}</router-link>
                        </td>
                        <td>
                            <div class="badge color-white" :class="data.stock_status == 1 ? 'bg-green' : 'bg-red'">{{data.stock_status == 1 ? "In Stock" : "Out Of Stock"}}</div>
                        </td>
                        <td v-if="data.discount_fixed == null">
                            <span v-html="icon"></span> {{data.price * rate | currency}}
                        </td>
                        <td v-if="data.discount_fixed !== null">
                            <del class="font-red"><span v-html="icon"></span> {{data.price * rate | currency}} </del><span v-html="icon"></span> {{(data.price - data.discount_fixed) * rate | currency}}
                        </td>
                        <td>
                            <button type="button" @click="removeWishlist(key)"><i class="fas fa-times"></i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Wish list area end-->
</template>


<script>
    export default {
        title: 'Wishlist',

        methods: {
            // Remove Product Wishlist
            removeWishlist(key){
                this.$store.dispatch('removeWishlist', key);
                Fire.$emit('success', 'Product Successfully Remove Your Wishlist');
            },
        },

        computed: {
            // Get Wishlist Data
            wishlists() {
                return this.$store.state.wishlists;
            },

            // Load Currency Rate
            rate(){
                return this.$store.state.currency.rate;
            },

            // Load Currency Icon
            icon(){
                return this.$store.state.currency.icon;
            },
        },
    };
</script>