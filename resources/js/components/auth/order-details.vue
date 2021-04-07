<template>
    <div class="dashboard" v-if="$middleware.authCheck()">
        <!--Dashboard area start-->
        <div class="dashboard-container container">
            <div class="section-title">
                <div class="row">
                    <div class="col-lg-12">
                        <h3><i class="far fa-address-card"></i> My Account</h3>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="row">
                <sidebar></sidebar>
                <!--Dashboard body start-->
                <div class="col-lg-9">
                    <div class="dashboard-body" v-if="order !== ''">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="checkout-success-body">
                                    <div class="checkout-order-details">
                                        <h4>Order #{{order.id}}<span>Was placed on <b>{{order.created_at | date2}}</b> and it is currently <b>{{order.delivery_status.processing === 'running' ? 'Processing':order.delivery_status.processing === 'cancel' ? 'Cancel':order.delivery_status.warehouse === 'running' ? 'Warehouse':order.delivery_status.warehouse === 'cancel' ? 'Cancel':order.delivery_status.onTheWay === 'running' ? 'On The Way':order.delivery_status.onTheWay === 'cancel' ? 'Cancel':order.delivery_status.delivery === 'running' ? 'Delivery':order.delivery_status.delivery === 'cancel' ? 'Cancel':'Complete'}}</b>.</span>
                                        </h4>
                                        <router-link to="/order-history" class="cta-blue-btn">Back to list</router-link>
                                    </div>
                                    <table class="table shopping-cart-table table-responsive-sm">
                                        <thead class="thead-light text-center">
                                        <tr>
                                            <th scope="col">Image</th>
                                            <th scope="col">Product</th>
                                            <th scope="col">Color</th>
                                            <th scope="col">Variant</th>
                                            <th scope="col">Quantity</th>
                                            <th scope="col">Total</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="product in order.products">
                                            <td>
                                                <router-link :to="product.product !== null ? '/'+product.product.slug + '/product':''"><img :src="product.product !== null ? '/'+product.product.feature_image:''" :alt="product.product !== null ? product.product.name:''" class="img-fluid text-center"></router-link>
                                            </td>
                                            <td class="text-left"><router-link :to="product.product !== null ? '/'+product.product.slug + '/product':''">{{product.product !== null ? product.product.name:'product is deleted by owner'}}</router-link></td>
                                            <td>{{product.product !== null ? product.product_color:'product is deleted by owner'}} <span class="square" :style="'background-color:' + product.product_color_code + ';'" v-if="product.product !== null"></span></td>
                                            <td>{{product.product_variant}}</td>
                                            <td>{{product.product_quantity}}</td>
                                            <td><span v-html="icon" class="mr-1"></span>{{product.product_price * rate | currency}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="6" class="text-left order-success-ammount">
                                                <ul>
                                                    <li>
                                                        <p>Price:
                                                            <span> {{order.price * rate | currency}}</span>
                                                            <span v-html="icon" class="mr-1"></span>
                                                        </p>
                                                    </li>
                                                    <li>
                                                        <p>Discount:
                                                            <span>{{(order.discount === null ? '0':order.discount) * rate | currency}}</span>
                                                            <span v-html="icon" class="mr-1"></span>
                                                        </p>
                                                    </li>
                                                    <li>
                                                        <p>Coupon:
                                                            <span>{{(order.coupon === null ? '0':order.coupon) * rate | currency}}</span>
                                                            <span v-html="icon" class="mr-1"></span>
                                                        </p>
                                                    </li>
                                                    <li>
                                                        <p>Shipping Fee:
                                                            <span>{{order.shipping_fee * rate | currency}}</span>
                                                            <span v-html="icon" class="mr-1"></span>
                                                        </p>
                                                    </li>
                                                    <hr>
                                                    <li>
                                                        <h4>Total:
                                                            <span>{{order.total_price * rate | currency}}</span>
                                                            <span v-html="icon" class="mr-1"></span>
                                                        </h4>
                                                    </li>

                                                </ul>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="address">
                                    <h6 class="bg-blue color-white">Shipping Address</h6>
                                    <div class="address-name">
                                        <h3>{{order.shipping_address.name}}</h3>
                                        <p><small>Address: </small>{{order.shipping_address.address + " " + order.shipping_address.shipping_cost.town + "(" + order.shipping_address.shipping_cost.zip + ")"}}</p>
                                    </div>
                                    <div class="address-phone">
                                        <p><small>Phone: </small>{{order.shipping_address.phone}}</p>
                                    </div>
                                    <div class="address-email">
                                        <p><small>Email: </small>{{order.shipping_address.email}}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="address">
                                    <h6 class="bg-blue color-white">Billing Address</h6>

                                    <div class="address-name">
                                        <h3>{{order.billing_address.name}}</h3>
                                        <p><small>Address: </small>{{order.billing_address.id ? order.billing_address.address + ' ' + order.billing_address.shipping_cost.town + '(' + order.billing_address.shipping_cost.zip + ')' : order.billing_address.address}}</p>
                                    </div>
                                    <div class="address-phone">
                                        <p><small>Phone:</small> {{order.billing_address.phone}}</p>
                                    </div>
                                    <div class="address-email">
                                        <p><small>Email:</small> {{order.billing_address.email}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <loading v-if="order === ''"></loading>
                </div>
                <div class="col-lg-12 mt-5" v-if="order !== ''">
                    <!--Order position area start-->
                    <div class="order-position">
                            <ul class="pl-4">
                                <li class="order-position-shop" :class="order.delivery_status.processing === 'pending' ? 'order-position-pending':order.delivery_status.processing === 'cancel' ? 'order-position-cancel':order.delivery_status.processing === 'complete' ? 'order-position-done':''">
                                    <div class="order-position-img">
                                        <img src="/images/delivery/shops.png" alt="" class="img-fluid">
                                    </div>
                                    <div class="order-position-text">
                                        <h4>We Are Collecting Your Product</h4>
                                    </div>
                                </li>
                                <li class="order-position-warehouse" :class="order.delivery_status.warehouse === 'pending' ? 'order-position-pending':order.delivery_status.warehouse === 'cancel' ? 'order-position-cancel':order.delivery_status.warehouse === 'complete' ? 'order-position-done':''">
                                    <div class="order-position-img">
                                        <img src="/images/delivery/warehouse.png" alt="" class="img-fluid">
                                    </div>
                                    <div class="order-position-text">
                                        <h4>Your Product In Our Warehouse</h4>
                                    </div>
                                </li>
                                <li class="order-position-delivery" :class="order.delivery_status.onTheWay === 'pending' ? 'order-position-pending':order.delivery_status.onTheWay === 'cancel' ? 'order-position-cancel':order.delivery_status.onTheWay === 'complete' ? 'order-position-done':''">
                                    <div class="order-position-img">
                                        <img src="/images/delivery/delivery-truck.png" alt="" class="img-fluid lazy" style="">
                                    </div>
                                    <div class="order-position-text">
                                        <h4>Your Product is on The Way</h4>
                                    </div>
                                </li>
                                <li class="order-position-delivery-success" :class="order.delivery_status.delivery === 'pending' ? 'order-position-pending':order.delivery_status.delivery === 'cancel' ? 'order-position-cancel':order.delivery_status.delivery === 'complete' ? 'order-position-done':''">
                                    <div class="order-position-img">
                                        <img src="/images/delivery/service.png" alt="" class="img-fluid lazy" style="">
                                    </div>
                                    <div class="order-position-text">
                                        <h4>Your Product Has Been Delivered Successfully </h4>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    <!--Order position area end-->
                </div>
                <!--Dashboard body end-->
            </div>
        </div>
        <!--Dashboard area end-->
    </div>
    <div v-else>
        <error></error>
    </div>
</template>

<script>
    export default{
        title: 'Order Details',
        data(){
            return {
                order:'',
            }
        },

        methods: {
            loadData(){
                this.$Progress.start();
                axios.get('/api/order/'+this.$route.params.id).then(response=>{
                    this.order = response.data;
                    this.order.billing_address = JSON.parse(response.data.billing_address);
                    this.order.shipping_address = JSON.parse(response.data.shipping_address);
                    this.order.delivery_status = JSON.parse(response.data.delivery_status);
                    this.$Progress.finish();
                },
                ()=>{
                    Fire.$emit('error', 'Order Not Found');
                    this.$router.push('/order-history');
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
            this.$Progress.start();
            if(this.$middleware.authCheck()){
                this.loadData();
                this.$Progress.finish();
            }else{
                this.$router.push('/login');
                this.$Progress.fail();
            }
        },
    }
</script>