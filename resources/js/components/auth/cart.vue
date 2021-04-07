<template>
    <!--Cart area start-->
    <div class="shopping-cart" v-if="$middleware.authCheck()">
        <div v-if="carts.length > 0" class="container">
            <div class="section-title">
                <div class="row">
                    <div class="col-lg-12">
                        <h3><i class="fab fa-opencart"></i> Shopping Cart</h3>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="shopping-cart-body">
                <div class="cart-table">
                    <table class="table shopping-cart-table">
                        <thead class="thead-light text-center">
                        <tr>
                            <th scope="col">Image</th>
                            <th scope="col">Product</th>
                            <th scope="col">Color</th>
                            <th scope="col">Variant</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Total</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="cart in carts" :key="cart.id">
                            <td>
                                <router-link :to="'/' + cart.product.slug + '/product'">
                                    <img :src="cart.product.feature_image" :alt="cart.product.name" class="img-fluid text-center"/>
                                </router-link>
                            </td>
                            <td class="text-left">
                                <router-link :to="'/' + cart.product.slug + '/product'">{{cart.product.name}}</router-link>
                            </td>
                            <td>{{cart.color.name}} <span class="square" :style="'background-color:' + cart.color.color + ';'"></span></td>
                            <td>{{cart.variant.variant}}</td>
                            <td>
                                <p v-if="cart.product.discount_fixed == null">
                                    <span v-html="icon"></span> {{ cart.product.price * rate | currency}}
                                </p>
                                <p v-else>
                                    <del class="color-red mr-1"><span v-html="icon"></span> {{ cart.product.price * rate | currency }}</del>
                                    <span v-html="icon"></span> {{(cart.product.price - cart.product.discount_fixed) * rate | currency}}
                                </p>
                            </td>
                            <td>
                                <div class="cart-plus-minus">
                                    <div class="dec qtybutton" @click="cartDecrease(), cart.quantity > 1 ? cart.quantity-- : ''">-</div>
                                    <input class="cart-plus-minus-box" type="text" name="qtybutton" :value="cart.quantity"/>
                                    <div class="inc qtybutton" @click="cartIncrease(), cart.quantity++">+</div>
                                </div>
                            </td>
                            <td>
                                <span v-html="icon"></span> {{(cart.quantity * (cart.product.price - cart.product.discount_fixed)) * rate | currency}}
                            </td>
                            <td>
                                <button @click="deleteCart(cart.id)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="row mt-3">
                        <div class="offset-lg-7 col-lg-5">
                            <div class="cart-cta-btn text-right">
                                <router-link to="/home" class="cta-grey-btn mb-2">Continue Shoping</router-link>
                                <button class="cta-blue-btn" :disabled="!cartUpdate" @click="updateCart">
                                    Update Cart
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="shopping-cart-info mt-3">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="text-center cart-img">
                                <img src="/images/cart.svg" alt="" class="img-fluid">
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="shopping-cart-ammount pt-4">
                                <h2>Cart Total</h2>
                                <ul>
                                    <li>
                                        <p>Price
                                            <span class="text-right"> {{ cartPrice * rate | currency }}</span>
                                            <span class="text-right mr-1" v-html="icon"></span>
                                        </p>
                                    </li>
                                    <li>
                                        <p>
                                            Discount:
                                            <span class="text-right"> {{ cartDiscount * rate | currency }}</span>
                                            <span class="text-right mr-1" v-html="icon"></span>
                                        </p>
                                    </li>
                                    <hr>
                                    <li class="shopping-cart-total">
                                        <h4>
                                            Total:
                                            <span class="text-right"> {{ cartTotal * rate| currency }}</span>
                                            <span class="text-right mr-1" v-html="icon"></span>
                                        </h4>
                                    </li>
                                </ul>
                                <button v-if="cartUpdate" @click="updateCart">Update Cart</button>
                                <router-link to="/checkout" v-if="!cartUpdate">Placed To Checkout</router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="empty-cart text-center container" v-else>
            <img src="/images/cart-empty.png" alt="Your Cart is Empty" class="img-fluid h-350">
            <h4 class="text-center mt-3">Your Cart is Empty</h4>
        </div>
    </div>
    <!--Cart area end-->
    <div v-else>
        <error></error>
    </div>
</template>

<script>
    export default {
        title: 'Cart',
        data() {
            return {
                cartInfo: [],
                cartUpdate: false,
            };
        },

        methods: {
            // Cart Product Quantity Increase
            cartIncrease() {
                this.cartUpdate = true;
                this.cartInfo = this.carts;
            },

            // Cart Product Quantity Increase
            cartDecrease() {
                this.cartUpdate = true;
                this.cartInfo = this.carts;
            },

            // Cart Information Update
            updateCart() {
                this.$Progress.start();
                axios.post('/api/cart/update', this.cartInfo).then(response => {
                    this.$store.dispatch('getCart');
                    this.cartUpdate = false;
                    Fire.$emit('success', response.data);
                    this.$Progress.finish();
                },
                () => {
                    Fire.$emit('error', 'Something Wrong! Please try Again');
                    this.$Progress.fail();
                });
            },

            // Delete Cart Product
            deleteCart(id) {
                this.$Progress.start();
                axios.post('/api/cart/delete/' + id).then(response => {
                    this.$store.dispatch('getCart');
                    Fire.$emit('success', response.data);
                    this.$Progress.finish();
                },
                () => {
                    Fire.$emit('error', 'Something Wrong! Please try Again');
                    this.$Progress.fail();
                });
            },
        },

        computed: {
            // Cart Information
            carts() {
                return this.$store.state.carts;
            },

            // Cart Subtotal Price
            cartPrice() {
                return this.carts.reduce((sum, cart) => {
                    return (sum += cart.quantity * cart.product.price);
                }, 0);
            },

            // Cart Discount Price
            cartDiscount() {
                return this.carts.reduce((sum, cart) => {
                    return (sum += cart.quantity * cart.product.discount_fixed);
                }, 0);
            },

            // Cart Total Price
            cartTotal() {
                return this.carts.reduce((sum, cart) => {
                    return (sum += cart.quantity * (cart.product.price - cart.product.discount_fixed));
                }, 0);
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

        created() {
            if (!this.$middleware.authCheck()) {
                this.$router.push('/login');
            }
        },
    };
</script>