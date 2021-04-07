<template>
    <div class="col-lg-3">
        <!--Dashboard sidebar area Start-->
        <div class="dashboard-navigation">
            <h3>Menu</h3>
            <ul>
                <li><router-link to="/dashboard"><span></span><i class="fas fa-tachometer-alt"></i> Dashboard</router-link></li>
                <li v-if="$middleware.admin()"><router-link to="/brand"><span></span><i class="fas fa-tags"></i> Brand</router-link></li>
                <li v-if="$middleware.admin()"><router-link to="/category"><span></span><i class="fas fa-th-large"></i> Category</router-link></li>
                <li v-if="$middleware.admin()"><router-link to="/sub-category"><span></span><i class="fas fa-th"></i> Sub-Category</router-link></li>
                <li v-if="$middleware.admin()"><router-link to="/shipping-cost"><span></span><i class="fas fa-truck"></i> Shipping Cost</router-link></li>
                <li v-if="$middleware.authCheck()"><router-link to="/shipping-address"><span></span><i class="fas fa-address-card"></i> Shipping Address</router-link></li>
                <li v-if="$middleware.adminOrSeller()"><router-link to="/product"><span></span><i class="fas fa-boxes"></i> Product</router-link></li>
                <li v-if="$middleware.admin()"><router-link to="/coupon"><span></span><i class="fas fa-receipt"></i> Coupon</router-link></li>
                <li v-if="$middleware.authCheck()"><router-link to="/order-history"><span></span><i class="fas fa-box-open"></i> Order History</router-link></li>
                <li v-if="$middleware.authCheck()"><router-link to="/edit-profile"><span></span><i class="fas fa-edit"></i> Edit Profile</router-link></li>
                <li v-if="$middleware.admin()"><router-link to="/currency"><span></span><i class="fas fa-dollar-sign"></i> Currency</router-link></li>
                <li v-if="$middleware.admin()"><router-link to="/site-setting"><span></span><i class="fas fa-tools"></i> Site Setting</router-link></li>
                <li><button @click="logout"><span></span><i class="fas fa-sign-out-alt"></i> Logout</button></li>
            </ul>
        </div>
        <!--Dashboard sidebar area end-->
    </div>
</template>


<script>
    export default {
        data() {
            return {};
        },

        methods: {
            // Logout Function
            logout() {
                this.$Progress.start();
                axios.post('/logout').then(() => {
                    this.$Progress.finish();
                    this.$router.go('/');
                },
                ()=>{
                    Fire.$emit('error', 'Something Wrong! Please try Again');
                    this.$progress.fail();
                });
            },
        },

        created() {
            if (!this.$middleware.authCheck()) {
                this.$router.push('/login');
            }
        },
    };
</script>
