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
                    <tr v-for="data in wishlists" :key="data.id" v-else>
                        <td>
                            <router-link :to="data.product.slug + '/product'"><img :src="data.product.feature_image" :alt="data.product.name"/></router-link>
                        </td>
                        <td class="text-left">
                            <router-link :to="data.product.slug + '/product'">{{data.product.name}}</router-link>
                        </td>
                        <td>
                            <div class="badge color-white" :class="data.product.stock_status == 1 ? 'bg-green' : 'bg-red'">{{data.product.stock_status == 1 ? "In Stock" : "Out Of Stock"}}</div>
                        </td>
                        <td v-if="data.product.discount_fixed == null">
                            ${{ data.product.price }}
                        </td>
                        <td v-if="data.product.discount_fixed !== null">
                            <del class="font-red">${{ data.product.price }} </del>${{data.product.price - data.product.discount_fixed}}
                        </td>
                        <td>
                            <button type="button" @click="removeWishlist(data.id)"><i class="fas fa-times"></i></button>
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
        data() {
            return {};
        },

        methods: {
            // Remove Product from wishlist
            removeWishlist(id) {
                this.$Progress.start();
                axios.post('/api/wishlist/delete/' + id).then(response => {
                    this.$store.dispatch('getWishlist');
                    Fire.$emit('success', response.data);
                    this.$Progress.finish();
                });
            },
        },

        computed: {
            wishlists() {
                return this.$store.state.wishlists;
            },
        },

        created() {
            this.$Progress.start();
            if (!this.$middleware.authCheck()) {
                this.$router.push('/login');
                this.$Progress.fail();
            }
        },
    };
</script>