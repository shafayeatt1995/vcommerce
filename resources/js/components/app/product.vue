<template>
    <!--Product card area start-->
    <div class="slider-single-product">
        <div class="slider-product-image">
            <div class="product-tag">
                <h6 class="bg-blue color-white" v-if="(new Date().getTime() - new Date(product.created_at).getTime()) / (1000 * 3600 * 24) <= $store.state.newArrivalProductDateSet">New Arrival</h6>
                <h6 class="bg-orange color-white" v-if="product.discount_fixed !== null">-{{product.discount_percent}}%</h6>
                <h6 class="bg-purple color-white" v-if="product.orders.filter(order=>(new Date().getTime() - new Date(order.created_at).getTime()) / (1000 * 3600 * 24) >= $store.state.trendingProductOrderDate).length >= $store.state.trendingProductOrderCount">Trending</h6>
            </div>
            <ul class="slider-product-cart">
                <li>
                    <button v-tooltip.top-center="'Quick View'" @click="quickView(product)"><i class="fas fa-search"></i>
                    </button>
                </li>
                <li>
                    <button type="button" v-tooltip.top-center="'Add to Cart'" @click="carts.some(cart => cart.id === product.id) ? removeCart():product.stock_status ? addCart(): outOfStock()"><i class="fas" :class="carts.some(cart => cart.id === product.id) ? 'fa-shopping-cart color-blue' : 'fa-cart-plus'"></i></button>

                </li>
                <li>
                    <wishlist :product="product" activeText="<i class='fas fa-heart color-blue'></i>" deactiveText="<i class='far fa-heart'></i>" v-tooltip.top-center="'Add Wishlidt'"></wishlist>
                </li>
            </ul>
            <router-link :to="'/' + product.slug + '/product'">
                <img :src="'/' + product.feature_image" alt="" class="img-fluid lazy"/>
                <div class="product-overlay"></div>
            </router-link>
        </div>
        <div class="slider-product-info">
            <router-link :to="'/' + product.slug + '/product'" id="slider-product-link">
                {{product.name.length < 60 ? product.name : product.name.substring(0, 60) + "..."}}
            </router-link>
            <ul class="product-rating">
                <li><i class="fa-star" :class="product.rating >= '1' ? 'fas color-yellow' : 'far'"></i></li>
                <li><i class="fa-star" :class="product.rating >= '2' ? 'fas color-yellow' : 'far'"></i></li>
                <li><i class="fa-star" :class="product.rating >= '3' ? 'fas color-yellow' : 'far'"></i></li>
                <li><i class="fa-star" :class="product.rating >= '4' ? 'fas color-yellow' : 'far'"></i></li>
                <li><i class="fa-star" :class="product.rating >= '5' ? 'fas color-yellow' : 'far'"></i></li>
            </ul>
            <h6>{{product.reviews_count}} {{product.reviews_count > 1 ? 'Reviews':'Review'}}</h6>
            <p class="available" :class="product.discount_fixed !== null ? 'mt-2':'mt-0'">Availability: <span :class="product.stock_status ? 'color-green':'color-red'">{{product.stock_status ? 'In Stock':'Out Of Stock'}}</span>
            </p>

            <p class="discount">
                <span v-html="icon"></span>{{ (product.discount_fixed !== null ? (product.price - product.discount_fixed) : product.price) * rate | currency }}
                <del v-if="product.discount_fixed !== null"><span v-html="icon"></span>{{ product.price * rate | currency }}</del>
            </p>

            <h5 class="sold">Sold: {{ product.orders.length }}</h5>
        </div>
    </div>
    <!--Product card area end-->
</template>

<script>
    export default {
        props: {
            product: {},
        },
        data(){
            return{
                cart: {
                    product: this.product,
                    quantity: '1',
                    colorId: this.product.colors[0].id,
                    colorName: this.product.colors[0].name,
                    colorCode: this.product.colors[0].color,
                    variantId: this.product.variants[0].id,
                    variantName: this.product.variants[0].variant,
                },
            }
        },

        methods: {
            // Show or hide quick view product
            quickView(product){
                this.$store.dispatch('quickView', product)
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

            anik(){
                let date1 = new Date(this.product.created_at);
                let date2 = new Date();
                let difference = date2.getTime() - date1.getTime();
                let get = difference / (1000 * 3600 * 24);
                console.log(get);
            },
        },

        computed: {
            // Load Cart Information
            carts() {
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
        }
    };
</script>