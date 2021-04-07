<template>
    <!--Product card area start-->
    <div class="slider-single-product">
        <div class="slider-product-image">
            <h6 class="bg-blue color-white">New Arrival</h6>
            <ul class="slider-product-cart">
                <li>
                    <button data-toggle="tooltip" data-placement="top" title="Quick View" @click="productId = product.id"><i class="fas fa-search"></i>
                    </button>
                </li>
                <li>
                    <wishlist :productId="product.id" activeText="<i class='fas fa-heart color-blue'></i>" deactiveText="<i class='far fa-heart'></i>"></wishlist>
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
            <h6>15 Review</h6>
            <p class="available" :class="product.discount_fixed !== null ? 'mt-2':'mt-0'">Availability: <span :class="product.discount_fixed !== null ? 'color-green':'color-red'">{{product.discount_fixed !== null ? 'In Stock':'Out Of Stock'}}</span>
            </p>

            <p class="discount">
                <span v-html="icon"></span> {{ (product.discount_fixed !== null ? (product.price - product.discount_fixed) : product.price) * rate | currency }}
                <del v-if="product.discount_fixed !== null"><span v-html="icon"></span> {{ product.price * rate | currency }}</del>
            </p>

            <h5 class="sold">Sold: 12</h5>
        </div>
    </div>
    <!--Product card area end-->
</template>

<script>
    export default {
        props: {
            product: {},
        },

        computed: {
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