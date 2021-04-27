<template>
    <div>
        <!--Off canvas menu start-->
        <div v-if="$route.path !== '/checkout'" class="off-canvas-menu" :class="cartShow ? 'off-canvas-triggered' : ''">
            <button @click="cartShow = !cartShow" class="cart-close-btn">
                <i class="fas fa-times"></i>
            </button>
            <h3>Shopping Cart</h3>
            <div v-if="carts.length > 0">
                <div class="off-canvas-cart-product" v-for="(product, key) in carts" :key="key">
                    <router-link :to="'/' + product.slug + '/product'" class="off-canvas-cart-product-image">
                        <img :src="'/' + product.feature_image" alt="cart.product.name" class="img-fluid"/>
                    </router-link>
                    <div class="off-canvas-cart-product-info">
                        <router-link :to="'/' + product.slug + '/product'">
                            {{product.name.length > 60 ? product.name.substring(0, 60) + "..." : product.name}}
                        </router-link>
                        <h5>
                            <span class="pointer p-1" @click="cartDecrease(), product.quantity > 1 ? product.quantity-- : ''">-</span>
                            {{ product.quantity }}
                            <span class="pointer p-1" @click="cartIncrease(), product.quantity++">+</span>
                            <i class="fas fa-times"></i>
                            <span v-html="icon"></span>{{ (product.discount_fixed ? product.price - product.discount_fixed : product.price) * rate | currency}} = <span><span v-html="icon"></span>{{ (product.quantity * (product.price - product.discount_fixed)) * rate | currency }}</span>
                        </h5>
                        <button @click="deleteCart(key)">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <hr/>
                </div>
                <hr/>
                <div class="off-canvas-cart-product-ammount">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Heading</th>
                            <th>Ammount</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Subtotal:</td>
                            <td><span v-html="icon"></span> {{ cartSubTotal * rate | currency }}</td>
                        </tr>
                        <tr>
                            <td>Discount:</td>
                            <td><span v-html="icon"></span> {{ cartDiscount * rate | currency }}</td>
                        </tr>
                        <tr>
                            <td><b>Total:</b></td>
                            <td>
                                <b><span v-html="icon"></span> {{ cartTotal * rate | currency }}</b>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <router-link to="/cart" class="cta-grey-btn" v-if="!cartUpdate">View Cart</router-link>
                    <router-link to="/checkout" @click="cartShow = !cartShow" class="cta-blue-btn" v-if="!cartUpdate">Checkout</router-link>
                    <button type="button" @click="$route.path !== '/checkout' ? updateCart():''" class="cta-blue-btn" v-if="cartUpdate">
                        Update Cart
                    </button>
                </div>
            </div>
            <div class="empty-cart container" v-else>
                <img src="/images/cart-empty.png" alt="Your Cart is Empty" class="img-fluid">
                <h4 class="text-center mt-3">Your Cart is Empty</h4>
            </div>
        </div>
        <div v-if="$route.path != '/checkout'" @click="cartShow = !cartShow" class="off-canvas-overlay" :class="cartShow ? 'off-canvas-triggered' : ''"></div>
        <!--Off canvas menu end-->

        <!--Header area start-->
        <header>
            <div class="container">
                <!--Header top area start-->
                <div class="header-top" id="header-top">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="header-top-left">
                                <ul>
                                    <li><router-link to="/about-us">About Us</router-link></li>
                                    <li><router-link to="/contact-us">Contact Us</router-link></li>
                                    <li><router-link to="/order-history">Track Order</router-link></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="header-top-right">
                                <ul>
                                    <li v-if="!$middleware.authCheck()">
                                        <router-link to="/login">Login</router-link>
                                    </li>
                                    <li v-if="!$middleware.authCheck()">
                                        <router-link to="/register">Register</router-link>
                                    </li>
                                    <li v-if="$middleware.authCheck()">
                                        My Account <span><i class="fas fa-chevron-down"></i></span>
                                        <ul>
                                            <li>
                                                <router-link to="/dashboard">
                                                    <i class="fas fa-tachometer-alt mr-1"></i>Dashboard
                                                </router-link>
                                            </li>
                                            <li>
                                                <router-link to="/edit-profile">
                                                    <i class="far fa-edit mr-1"></i> Edit Profile
                                                </router-link>
                                            </li>
                                            <li>
                                                <router-link to="/order-history">
                                                    <i class="fas fa-history mr-1"></i> Order History
                                                </router-link>
                                            </li>
                                            <li>
                                                <button @click="logout">
                                                    <i class="fas fa-sign-out-alt mr-1"></i> Log Out
                                                </button>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        Currency <span><i class="fas fa-chevron-down"></i></span>
                                        <ul>
                                            <li v-for="currency in currencies" :key="currency.id">
                                                <button type="button" @click="changeCurrency(currency)"><span v-html="currency.icon"></span> {{currency.name}}</button>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        Language <span><i class="fas fa-chevron-down"></i></span>
                                        <ul>
                                            <li>
                                                <a href="#">
                                                    <img src="#" data-src="" alt="" class="lazy"/>
                                                    English
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <img src="#" data-src="" alt="" class="lazy"/>
                                                    Bangla
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <img src="#" data-src="" alt="" class="lazy"/>
                                                    Russian
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Header top area end-->

                <!--Header body area start-->
                <div class="header-body">
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="logo">
                                <img src="#" data-src="" alt="logo" class="img-fluid lazy"/>
                                <div class="mobile-menu"></div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="header-search">
                                <form class="search">
                                    <select class="search-categories form-select" style="background-image: url('/images/download.svg')" v-model="selectCategory">
                                        <option value="" selected>All Categories</option>
                                        <option :value="category.id" v-for="(category, key) in categories" :key="key">{{category.name}}</option>
                                    </select>
                                    <input type="text" placeholder="Search.." name="search"/>
                                    <input type="submit" value="Search"/>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="contact">
                                <p><span>Customer Service</span> +88017-28293635</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Header body area end-->

                <!--Header navbar area start-->
                <div class="navigation" id="navigation">
                    <ul>
                        <li><router-link to="/">Home</router-link></li>
                        <li><a href="single-store.html">Shop <span><i class="fas fa-chevron-down"></i></span></a>
                            <ul>
                                <li>
                                    <a href="feature-brands.html"><span></span> Feature Brands</a>
                                </li>
                                <li>
                                    <router-link to="/cart"><span></span> Cart</router-link>
                                </li>
                                <li>
                                    <router-link to="/checkout"><span></span> Checkout</router-link>
                                </li>
                                <li>
                                    <router-link to="checkout-success/1"><span></span> Checkout Success</router-link>
                                </li>
                                <li>
                                    <router-link to="/wishlist"><span></span> Wish list</router-link>
                                </li>
                                <li>
                                    <router-link to="order/1"><span></span> Track Order</router-link>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="products.html"
                            >Product <span><i class="fas fa-chevron-down"></i></span
                            ></a>
                            <ul>
                                <li>
                                    <a href="top-store.html"><span></span> Store</a>
                                </li>
                                <li>
                                    <a href="products.html"><span></span> Products</a>
                                </li>
                                <li>
                                    <a href="single-product.html"
                                    ><span></span> Single Products</a
                                    >
                                </li>
                                <li>
                                    <a href="single-store.html"><span></span> Single store</a>
                                </li>
                                <li>
                                    <a href="single-feature-brand.html"
                                    ><span></span> Single Brands</a
                                    >
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="dashboard.html"
                            >Account <span><i class="fas fa-chevron-down"></i></span
                            ></a>
                            <ul>
                                <li>
                                    <a href="login.html"><span></span> Login</a>
                                </li>
                                <li>
                                    <a href="dashboard.html"><span></span> Dashboard</a>
                                </li>
                                <li>
                                    <a href="edit-profile.html"><span></span> Edit Profile</a>
                                </li>
                                <li>
                                    <a href="order-history.html"><span></span> Order History</a>
                                </li>
                                <li>
                                    <a href="order-details.html"><span></span> Order Details</a>
                                </li>
                                <li>
                                    <a href="edit-address.html"><span></span> Edit Address</a>
                                </li>
                                <li>
                                    <a href="edit-password.html"><span></span> Change Password</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="contact-us.html"
                            >Pages <span><i class="fas fa-chevron-down"></i></span
                            ></a>
                            <ul>
                                <li>
                                    <router-link to="/about-us"><span></span> About Us</router-link>
                                </li>
                                <li>
                                    <router-link to="/contact-us"><span></span> Contact Us</router-link>
                                </li>
                                <li>
                                    <router-link to="/404"><span></span> 404</router-link>
                                </li>
                                <li>
                                    <router-link to="/terms-condition"><span></span> Terms &amp; Condition</router-link>
                                </li>
                                <li>
                                    <router-link to="/faq"><span></span> FAQ</router-link>
                                </li>
                            </ul>
                        </li>
                        <li><a href="#">Buy Theme</a></li>
                        <li class="float-right nav-cart" v-if="$route.path !== '/checkout'">
                            <button class="cart-trigger" @click="cartShow = !cartShow">
                                <i class="fab fa-opencart"></i>
                                <small>{{ carts.length }}</small>
                            </button>
                        </li>
                        <li class="float-right nav-cart">
                            <router-link to="/wishlist"><i class="far fa-heart"></i>
                                <small>{{ wishlists.length }}</small>
                            </router-link>
                        </li>
                    </ul>
                </div>
                <!--Header navbar area end-->
            </div>
        </header>
        <!--Header area end-->
    </div>
</template>


<script>
    export default {
        data() {
            return {
                categories: [],
                selectCategory: '',
                cartInfo: [],
                cartUpdate: false,
                cartShow: false,
            };
        },

        methods: {
            // Logout Function
            logout() {
                this.$Progress.start();
                axios.post('/logout').then(() => {
                    this.$router.go('/');
                    this.$Progress.finish();
                },
                ()=>{
                    Fire.$emit('error', 'Something Wrong! Please try Again');
                    this.$progress.fail();
                });
            },

            //Load all category
            headerCategory(){
                axios.get('/api/all-categories').then(response=>{
                    this.categories = response.data;
                },
                ()=>{
                    Fire.$emit('error', 'Something Wrong! Please try Again');
                });
            },

            // Cart Product Quantity Increase
            cartIncrease() {
                this.cartUpdate = true;
                this.cartInfo = this.carts;
            },

            // Cart Product Quantity Decrease
            cartDecrease() {
                this.cartUpdate = true;
                this.cartInfo = this.carts;
            },

            // Cart Information Update
            updateCart() {
                this.$store.dispatch('cartProductUpdate', this.cartInfo);
                this.cartUpdate = false;
                Fire.$emit('success', 'Cart Successfully Update');
            },

            // Delete cart Product
            deleteCart(key) {
                this.$store.dispatch('removeCart', key);
                Fire.$emit('success', 'Product Successfully Remove From Cart');
            },

            // Change Currency
            changeCurrency(currency){
                this.$store.dispatch('changeCurrency', currency);
            }
        },

        computed: {
            // Wishlist Product Count
            wishlists() {
                return this.$store.state.wishlists;
            },

            // Cart Data
            carts() {
                return this.$store.state.carts;
            },

            // Cart Subtotal Amount
            cartSubTotal() {
                return this.carts.reduce((sum, cart) => {
                    return (sum += cart.quantity * cart.price);
                }, 0);
            },

            // Cart Discount Amount
            cartDiscount() {
                return this.carts.reduce((sum, cart) => {
                    return (sum += cart.quantity * cart.discount_fixed);
                }, 0);
            },

            // Cart Total Amount
            cartTotal() {
                return this.carts.reduce((sum, cart) => {
                    return (sum += cart.quantity * (cart.price - cart.discount_fixed));
                }, 0);
            },

            // Currency Information
            currencies() {
                return this.$store.state.currencies;
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

        created(){
            this.headerCategory();
        },

        watch: {
            // Route Changing Function
            $route(to, from) {
                this.cartShow = false;
            },
        },
    };
</script>