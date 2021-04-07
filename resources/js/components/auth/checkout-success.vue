<template>
    <div class="checkout-success" v-if="$middleware.authCheck()">
        <div v-if="loading">
            <loading></loading>
        </div>
        <div v-else>
            <div v-if="orderFind">
                <!--Checkout success area start-->
                <div class="container">
                    <!--Checkout success Body top start-->
                    <div class="checkout-success-body-top">
                        <i class="far fa-check-circle"></i>
                        <h1>Thank You</h1>
                        <h4>Your order has been received</h4>
                        <ul>
                            <li>
                                <p>Order number</p> <span>#{{order.id}}</span>
                            </li>
                            <li>
                                <p>Order Date</p> <span>{{order.created_at | date2}}</span>
                            </li>
                            <li>
                                <p>Total Ammount</p> <span v-html="icon"></span> <span>{{order.total_price * rate | currency}}</span>
                            </li>
                            <li>
                                <p>Payment Method</p>
                                <span>{{order.payment_type === 'cod' ? 'Cash On Delivery' : order.payment_type === 'stripe' ? 'Card Payment' : 'other'}}</span>
                            </li>
                        </ul>
                    </div>
                    <!--Checkout success Body top end-->

                    <!--Checkout success Body start-->
                    <div class="checkout-success-body">
                        <div class="row">
                            <div class="offset-lg-1 col-lg-10">
                                <table class="table shopping-cart-table">
                                    <thead class="thead-light text-center">
                                    <tr>
                                        <th scope="col">Image</th>
                                        <th scope="col">Product</th>
                                        <th scope="col">Color</th>
                                        <th scope="col">Variant</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Price</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="product in order.products ">
                                        <td><router-link :to="'/' + product.product.slug + '/product'"><img :src="'/' + product.product.feature_image" :alt="product.product.name" class="img-fluid text-center"></router-link></td>
                                        <td class="text-left"><router-link :to="'/' + product.product.slug + '/product'">{{product.product.name}}</router-link></td>
                                        <td>{{product.product_color}} <span class="square" :style="'background-color:' + product.product_color_code + ';'"></span>
                                        <td>{{product.product_variant}}</td>
                                        <td>{{product.product_quantity}}</td>
                                        <td><span v-html="icon"></span> {{product.product_price * rate | currency}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="text-left order-success-ammount">
                                            <ul>
                                                <li>
                                                    <p>Subtotal: <span v-html="iocn"></span> <span>{{order.price * rate | currency}}</span></p>
                                                </li>
                                                <li>
                                                    <p>
                                                        Coupon: <span v-html="icon"></span> <span>{{(order.coupon !== null ? order.coupon : '0') * rate | currency}}</span>
                                                    </p>
                                                </li>
                                                <li>
                                                    <p>
                                                        Discount: <span v-html="icon"></span> <span>{{(order.coupon !== null ? '0' : order.discount) * rate | currency}}</span>
                                                    </p>
                                                </li>
                                                <li>
                                                    <p>Shipping Fee: <span v-html="icon"></span> <span>{{order.shipping_fee * rate| currency}}</span>
                                                    </p>
                                                </li>
                                                <hr>
                                                <li>
                                                    <h4>Total: <span v-html="icon"></span> <span>{{order.total_price * rate | currency}}</span></h4>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="offset-lg-1 col-lg-5">
                                <div class="address">
                                    <h6 class="bg-blue color-white">Shipping Address</h6>
                                    <div class="address-name">
                                        <h3>{{order.shipping_address.name}}</h3>
                                        <p><small>Address:</small>{{order.shipping_address.address + ' ' + order.shipping_address.shipping_cost.town + '(' + order.shipping_address.shipping_cost.zip + ')'}}</p>
                                    </div>
                                    <div class="address-phone">
                                        <p><small>Phone:</small>{{order.shipping_address.phone}}</p>
                                    </div>
                                    <div class="address-email">
                                        <p><small>Email:</small>{{order.shipping_address.email}}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="address" v-if="order.billing_address.id">
                                    <h6 class="bg-blue color-white">Billing Address</h6>

                                    <div class="address-name">
                                        <h3>{{order.billing_address.name}}</h3>
                                        <p><small>Address:</small>{{order.billing_address.address + ' ' + order.billing_address.shipping_cost.town + '(' + order.billing_address.shipping_cost.zip + ')'}}</p>
                                    </div>
                                    <div class="address-phone">
                                        <p><small>Phone:</small>{{order.billing_address.phone}}</p>
                                    </div>
                                    <div class="address-email">
                                        <p><small>Email:</small>{{order.billing_address.email}}</p>
                                    </div>
                                </div>
                                <div class="address" v-else>
                                    <h6 class="bg-blue color-white">Billing Address</h6>

                                    <div class="address-name">
                                        <h3>{{order.billing_address.name}}</h3>
                                        <p><small>Address:</small>{{order.billing_address.address}}</p>
                                    </div>
                                    <div class="address-phone">
                                        <p><small>Phone:</small>{{order.billing_address.phone}}</p>
                                    </div>
                                    <div class="address-email">
                                        <p><small>Email:</small>{{order.billing_address.email}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="checkbox-success-cta">
                            <router-link to="/" class="cta-grey-btn">Back To Home</router-link>
                            <a href="#" class="cta-blue-btn">Continue Shopping</a>
                        </div>
                    </div>
                    <!--Checkout success top end-->
                </div>
                <!--Checkout success area end-->
            </div>
            <div v-else>
                <error></error>
            </div>
        </div>
    </div>
    <div v-else>
        <error></error>
    </div>
</template>

<script>
    export default{
        title: 'Checkout Success',
        data(){
            return {
                order: {},
                loading: true,
                orderFind: true,
            }
        },

        methods: {
            // Load Order Details
            getOrder(){
                this.$Progress.start();
                axios.get('/api/order/' + this.$route.params.id).then(response => {
                    this.order = response.data;
                    this.order.billing_address = JSON.parse(response.data.billing_address);
                    this.order.shipping_address = JSON.parse(response.data.shipping_address);
                    this.order.delivery_status = JSON.parse(response.data.delivery_status);
                    this.loading = false;
                    this.$Progress.finish();
                },
                () => {
                    this.orderFind = false;
                    this.loading = false;
                    Fire.$emit('error', 'Something Wrong! Please try Again');
                    this.$Progress.fail();
                })
            }
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
        },

        created(){
            if (this.$middleware.authCheck()) {
                this.getOrder();
            } else {
                this.$router.push('/login');
            }
        },
    }
</script>