<template>
    <div>
        <transition name="fade">
            <div class="quick-view-product" v-if="$store.state.quickViewId !== null">
                <!-- Single Product info area start-->
                <div class="single-product-info bg-white p-3">
                    <button type="button" class="quick-product-close cta-blue-btn" @click="quickView(product)"><i class="fas fa-times"></i></button>
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-5 col-xl-6">
                                <!-- Single Product View area start-->
                                <div class="single-product-view">
                                    <viewer :images="product.product_images" v-if="product.product_images.length > 0">
                                        <carousel :autoplay="false" :nav="true" :dots="false" :items="1" :navText="['〱', '〱']" :loop="true" :autoplayHoverPause="true">
                                            <img v-for="image in product.product_images" :src="'/' + image.image" :key="image.id" class="product-image"/>
                                        </carousel>
                                    </viewer>
                                    <h3 class="text-center" v-else>This product has no image</h3>
                                </div>
                                <!-- Single Product View area end-->
                            </div>
                            <div class="col-lg-7 col-xl-6">
                                <div class="single-product-info">
                                    <h2>{{ product.name }}</h2>
                                    <ul class="single-product-rating">
                                        <li><i class="fa-star" :class="product.rating >= '1' ? 'fas color-yellow' : 'far'"></i></li>
                                        <li><i class="fa-star" :class="product.rating >= '2' ? 'fas color-yellow' : 'far'"></i></li>
                                        <li><i class="fa-star" :class="product.rating >= '3' ? 'fas color-yellow' : 'far'"></i></li>
                                        <li><i class="fa-star" :class="product.rating >= '4' ? 'fas color-yellow' : 'far'"></i></li>
                                        <li><i class="fa-star" :class="product.rating >= '5' ? 'fas color-yellow' : 'far'"></i></li>
                                        <li>
                                            <p>({{product.reviews_count}} {{product.reviews_count > 1 ? 'Reviews':'Review'}})</p>
                                        </li>
                                    </ul>
                                    <h3 v-if="product.discount_fixed !== null">
                                        <del><span v-html="icon"></span>{{ product.price * rate | currency }}</del> <span v-html="icon"></span>{{(product.price - product.discount_fixed) * rate | currency }}
                                    </h3>
                                    <h3 v-else><span v-html="icon"></span>{{ product.price * rate | currency}}</h3>
                                    <p v-html="product.description"></p>
                                    <hr>
                                    <div class="single-product-brand-info">
                                        <ul>
                                            <li>
                                                <p>Sold By: <router-link to="/">{{product.user.name}}</router-link></p>
                                            </li>
                                            <li>
                                                <p>Sold: {{ product.orders.length}} {{product.orders.length > 0 ? 'times':'time'}}</p>
                                            </li>
                                            <li>
                                                <p v-if="product.brand_name == null">Brand: <router-link to="/">{{product.brand.name}}</router-link></p>
                                                <p v-else>Brand: {{ product.brand_name }}</p>
                                            </li>
                                            <li>
                                                <p>Model: {{ product.model_name }}</p>
                                            </li>
                                            <li>
                                                <p class="available">Availability: <span :class="product.stock_status ? 'color-green':'color-red'"> {{ product.stock_status ? 'In Stock':'Out Of Stock' }}</span></p>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="single-product-size">
                                        <p>Variant</p>
                                        <ul>
                                            <li v-for="(data, key) in product.variants" :key="data.id">
                                                <input type="radio" :id="'variant' + key" name="quickVariant" :value="data.id" :disabled="!product.stock_status" v-model="cart.variantId"/>
                                                <label :for="'variant' + key" @click="changeVariant(data)">{{data.variant}}</label>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="single-product-color">
                                        <p>Color</p>
                                        <ul>
                                            <li v-for="(data, key) in product.colors" class="mr-2" :key="data.id">
                                                <input type="radio" :id="'color' + key" name="quickColor" :value="data.id" :disabled="!product.stock_status" v-model="cart.colorId"/>
                                                <label :for="'color' + key" :style="'background-color:' + data.color + ';'" @click="changeColor(data)"></label>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="single-product-purchase">
                                        <ul>
                                            <li>
                                                <div class="cart-plus-minus">
                                                    <div class="dec qtybutton" @click="cart.quantity > 1 ? cart.quantity-- : ''">-</div>
                                                    <span class="cart-plus-minus-box" type="text" name="qtybutton">{{cart.quantity}}</span>
                                                    <div class="inc qtybutton" @click="cart.quantity++">+</div>
                                                </div>
                                            </li>
                                            <li>
                                                <button type="button" class="cta-grey-btn" @click="carts.some(cart => cart.id === product.id) ? removeCart(carts.findIndex(cart => cart.id === product.id)) : product.stock_status ? addCart(): outOfStock()">Add To Cart <span v-if="carts.some(cart => cart.id === product.id)"><i class="far fa-check-circle"></i></span></button>
                                            </li>
                                            <li>
                                                <button type="button" class="cta-blue-btn" @click="buyNow">Buy Now</button>
                                            </li>
                                            <li>
                                                <wishlist :product="product" activeClass="cta-grey-btn" deactiveClass="cta-grey-btn" activeText="<i class='fas fa-heart'></i>" deactiveText="<i class='far fa-heart'></i>"></wishlist>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="single-product-category">
                                        <ul>
                                            <li>
                                                <p>Categories: {{ product.category.name }} <i class="fas fa-arrow-right"></i> {{ product.sub_category.name }}</p>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="single-product-share">
                                        <ul>
                                            <li><a href="#"><i class="fab fa-facebook bg-blue"></i></a></li>
                                            <li><a href="#"><i class="fab fa-twitter bg-ltblue"></i></a></li>
                                            <li><a href="#"><i class="fab fa-youtube bg-red"></i></a></li>
                                            <li><a href="#"><i class="fab fa-instagram bg-purple"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="quick-view-product-overlay" v-if="$store.state.quickViewId !== null" @click="quickView(product)"></div>
                <!-- Single Product info area end-->
            </div>
        </transition>
        <transition name="fade">
        </transition>
    </div>
</template>

<script>
    import carousel from 'vue-owl-carousel';
    export default {
        components: {carousel},
        data(){
            return{
                product: {},
                cart: {
                    product: {},
                    quantity: '1',
                    colorId: '',
                    colorName: '',
                    colorCode: '',
                    variantId: '',
                    variantName: '',
                },
            }
        },

        methods: {
            // Show or hide quick view product
            quickView(product){
                this.$store.dispatch('quickView', product)
            },

            // Variant Change
            changeVariant(data){
                this.cart.variantId = data.id;
                this.cart.variantName = data.variant;
            },

            // Color Change
            changeColor(data){
                this.cart.colorId = data.id;
                this.cart.colorName = data.name;
                this.cart.colorCode = data.color;
            },

            // Product Add To Cart
            addCart(){
                this.$store.dispatch('addCart', this.cart);
                Fire.$emit('success', 'Product Successfully Add To Cart')
            },

            // Product Remove From Cart
            removeCart(key){
                this.$store.dispatch('removeCart', key);
                Fire.$emit('success', 'Product Successfully Remove From Cart');
            },

            // Show Out Of Stock Message
            outOfStock(){
                Fire.$emit('info', 'Product is out of stock')
            },

            // Buy now
            buyNow(){
                let anik = this.carts.some(cart => cart.id === this.cart.product.id);
                anik ? '' : this.$store.dispatch('addCart', this.cart);
                this.$store.dispatch('quickView', this.product);
                this.$router.push('/cart');
            },
        },

        computed: {
            // Get Quick View Product
            quickProduct(){
                return this.$store.state.quickViewProduct;
            },

            // Load Cart Information
            carts(){
                return this.$store.state.carts;
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

        watch: {
            quickProduct(newProduct){
                if (Object.keys(newProduct).length > 0) {
                    this.product = newProduct;
                    this.cart.product = newProduct;
                    this.cart.variantId= newProduct.variants[0].id;
                    this.cart.variantName= newProduct.variants[0].variant;
                    this.cart.colorId= newProduct.colors[0].id;
                    this.cart.colorName= newProduct.colors[0].name;
                    this.cart.colorCode= newProduct.colors[0].color;
                } else {
                    this.product = {};
                    this.cart.product = {};
                    this.cart.variantId= '';
                    this.cart.variantName= '';
                    this.cart.colorId= '';
                    this.cart.colorName= '';
                    this.cart.colorCode= '';
                }
            },
        },
    }

</script>

<style scoped>
    .fade-enter,
    .fade-leave-to {
        opacity: 0;
    }

    .fade-enter-active,
    .fade-leave-active {
        transition: opacity 0.3s;
    }
</style>