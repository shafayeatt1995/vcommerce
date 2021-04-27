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
                <div class="col-lg-9">
                    <!--Dashboard body start-->
                    <div class="dashboard-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="dashboard-profile">
                                    <img :src="'/'+user.image" alt="avatar" class="img-fluid"/>
                                    <h3>{{ user.name }}</h3>
                                    <router-link to="/edit-profile">Edit Profile</router-link>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="address" v-if="shippingAddress.length > 0">
                                    <h6 class="bg-blue color-white">Shipping Address</h6>
                                    <div class="address-name">
                                        <h3>{{shippingAddress[0].address_name}}</h3>
                                        <p><small>Address: </small>{{shippingAddress[0].address + ', ' + shippingAddress[0].shipping_cost.town + '(' + shippingAddress[0].shipping_cost.zip + ')'}}</p>
                                    </div>
                                    <div class="address-phone">
                                        <p><small>Phone: </small>{{shippingAddress[0].phone}}</p>
                                    </div>
                                    <div class="address-email">
                                        <p><small>Email: </small>{{shippingAddress[0].email}}</p>
                                    </div>
                                    <router-link to="shipping-address" class="cta-blue-btn">Edit Address</router-link>
                                </div>
                                <div class="address" v-else>
                                    <h6 class="bg-blue color-white">Shipping Address</h6>
                                    <h3 class="pt-3">You Have No Shipping Address</h3>
                                    <router-link to="shipping-address" class="cta-blue-btn">Add Your Shipping Address</router-link>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="dashboard-order">
                                    <h3>Recent Order</h3>
                                    <table class="table table-hover table-responsive-sm" v-if="orders.length > 0">
                                        <thead class="text-center">
                                        <tr>
                                            <th>Order Number</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Total</th>
                                        </tr>
                                        </thead>
                                        <tbody class="text-center">
                                        <tr v-for="order in orders">
                                            <td><router-link :to="'/order/'+order.id">#{{order.id}}</router-link></td>
                                            <td>{{order.created_at | date2}}</td>
                                            <td>{{JSON.parse(order.delivery_status).processing === 'running' ? 'Processing':JSON.parse(order.delivery_status).processing === 'cancel' ? 'Cancel':JSON.parse(order.delivery_status).warehouse === 'running' ? 'Warehouse':JSON.parse(order.delivery_status).warehouse === 'cancel' ? 'Cancel':JSON.parse(order.delivery_status).onTheWay === 'running' ? 'On The Way':JSON.parse(order.delivery_status).onTheWay === 'cancel' ? 'Cancel':JSON.parse(order.delivery_status).delivery === 'running' ? 'Delivery':JSON.parse(order.delivery_status).delivery === 'cancel' ? 'Cancel':'Complete'}}</td>
                                            <td><span v-html="icon"></span> {{order.total_price * rate | currency}} for {{order.products.length > '1' ? order.products.length+' items' : order.products.length+' item'}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <empty message="You Have no Order" class="p-2" v-else></empty>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Dashboard body end-->
                </div>
            </div>
        </div>
        <!--Dashboard area end-->
    </div>
    <div v-else>
        <error></error>
    </div>
</template>


<script>
    export default {
        title: 'Dashboard',
        data() {
            return {
                user: {},
                shippingAddress:[],
                orders:[]
            };
        },

        methods: {
            // Load Auth User Data
            loadUser() {
                axios.get('api/user').then(response => {
                    this.user = response.data.user;
                    this.shippingAddress = response.data.shippingAddresses;
                });
            },

            // Load Recent 5 Orders
            getOrder() {
                axios.get('api/get-order').then(response => {
                    this.orders = response.data;
                });
            },
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

        created() {
            this.$Progress.start();
            if (this.$middleware.authCheck()) {
                this.loadUser();
                this.getOrder();
                this.$Progress.finish();
            }else{
                this.$router.push('/login');
                this.$Progress.fail();
            }
        },
    };
</script>