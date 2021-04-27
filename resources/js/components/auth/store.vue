<template>
    <div class="dashboard">
        <!-- Store area start-->
        <div class="container">
            <div class="section-title">
                <div class="row">
                    <div class="col-lg-12">
                        <h3><i class="fas fa-store-alt"></i> My Store</h3>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-lg-12">
                    <div class="dashboard-body" v-if="!loading">
                        <div v-if="!store">
                            <store-create></store-create>
                        </div>
                        <div v-else>
                            <div class="section-title">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="store-logo">
                                            <img :src="store.logo" :alt="store.name"/>
                                            <h1 class="text-center">{{ store.name }}</h1>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-40"></div>

                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="store-achievement">
                                        <h3>Achievements</h3>
                                        <ul>
                                            <li>
                                                <img src="assets/img/store/store-achivement/trophy.png" alt="" class="img-fluid"/>
                                                <span>Top Brand</span>
                                            </li>
                                            <li>
                                                <img src="assets/img/store/store-achivement/shield.png" alt="" class="img-fluid"/>
                                                <span>Trusted</span>
                                            </li>
                                            <li>
                                                <img src="assets/img/store/store-achivement/delivery-truck.png" alt="" class="img-fluid"/>
                                                <span>90% On Time Delivery</span>
                                            </li>
                                            <li>
                                                <img src="assets/img/store/store-achivement/message.png" alt="" class="img-fluid"/>
                                                <span>80% Positive Feedback</span>
                                            </li>
                                            <li>
                                                <img src="assets/img/store/store-achivement/deliveryman.png" alt="" class="img-fluid"
                                                />
                                                <span>12000 Delivery Completed</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-9">
                                    <div class="about-single-store" v-html="store.description"></div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="mt-40"></div>
                                    <div class="store-location" v-html="store.map"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="loading">
                        <loading></loading>
                    </div>
                </div>
            </div>
        </div>
        <!-- Store area end-->
    </div>
</template>


<script>
    export default {
        title: 'Store',
        data() {
            return {
                store: null,
                loading: true,
            };
        },

        methods: {
            // Load Store Data
            loadStore() {
                this.$Progress.start();
                axios.get('/api/user').then(response => {
                    this.store = response.data.store === null ? false : response.data.store;
                    this.loading = false;
                        this.$Progress.finish();
                },
                () => {
                    this.store = false;
                    this.loading = false;
                    Fire.$emit('error', 'Something Wrong! Please try Again');
                    this.$Progress.fail();
                });
            },
        },

        created() {
            this.$Progress.start();
            if (this.$middleware.authCheck()){
                this.loadStore();

                Fire.$on('triggerCreateStore', () => {
                    this.loadStore();
                });
                this.$Progress.finish();
            } else {
                this.$router.push('/login');
                this.$Progress.fail();
            }
        },
    };
</script>