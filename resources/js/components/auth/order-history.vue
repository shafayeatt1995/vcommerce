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
                    <div class="dashboard-order-history" v-if="!loading">
                        <h3><i class="fas fa-box-open"></i> Order History</h3>
                        <table class="table table-responsive-md">
                            <thead class="text-center">
                            <tr>
                                <th scope="col">Order Number</th>
                                <th scope="col">Date</th>
                                <th scope="col">Status</th>
                                <th scope="col">Total</th>
                            </tr>
                            </thead>
                            <tbody class="text-center" v-if="orders.data.length <= 0">
                                <tr>
                                    <td colspan="4">
                                        <empty message="No Order Found"></empty>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody class="text-center" v-if="orders.data.length > 0">
                            <tr v-for="order in orders.data">
                                <td><router-link :to="'/order/'+order.id">#{{order.id}}</router-link></td>
                                <td>{{order.created_at | date2}}</td>
                                <td>{{JSON.parse(order.delivery_status).processing === 'running' ? 'Processing':JSON.parse(order.delivery_status).processing === 'cancel' ? 'Cancel':JSON.parse(order.delivery_status).warehouse === 'running' ? 'Warehouse':JSON.parse(order.delivery_status).warehouse === 'cancel' ? 'Cancel':JSON.parse(order.delivery_status).onTheWay === 'running' ? 'On The Way':JSON.parse(order.delivery_status).onTheWay === 'cancel' ? 'Cancel':JSON.parse(order.delivery_status).delivery === 'running' ? 'Delivery':JSON.parse(order.delivery_status).delivery === 'cancel' ? 'Cancel':'Complete'}}</td>
                                <td><span v-html="icon"></span> {{order.total_price  * rate| currency}} for {{order.products.length > '1' ? order.products.length+' items' : order.products.length+' item'}}</td>
                            </tr>
                            </tbody>
                        </table>
                        <pagination :data="orders" @pagination-change-page="getResults"></pagination>
                    </div>
                    <loading v-if="loading"></loading>
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
        title: 'Order History',
        data() {
            return {
                orders: [],
                loading: true,
            };
        },

        methods: {
            // Load Authenticate User Order
            loadData() {
                this.$Progress.start();
                if (this.$middleware.authCheck()) {
                    axios.get('/api/order').then(response => {
                        this.orders = response.data;
                        this.loading = false;
                        this.$Progress.finish();
                    },
                    () => {
                        this.loading = true;
                        Fire.$emit('error', 'Something Wrong! Please try Again');
                        this.$Progress.fail();
                    });
                } else {
                    Fire.$emit('error', 'You do not have permission to access this option');
                    this.$Progress.fail();
                }
            },
            getResults(page = 1) {
                if (this.$middleware.authCheck()) {
                    this.$Progress.start();
                    axios.get('/api/order?page=' + page).then(response => {
                        this.orders = response.data;
                        this.$Progress.finish();
                    },
                    () => {
                        Fire.$emit('error', 'There was something Wrong.');
                        this.$Progress.fail();
                    });
                }
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
                this.loadData();
                this.$Progress.finish();
            } else {
                this.$router.push('/login');
                this.$Progress.fail();
            }
        }
    };
</script>