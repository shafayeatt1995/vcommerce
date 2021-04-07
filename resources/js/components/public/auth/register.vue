<template>
    <!--Register area start-->
    <div class="login-register">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="login-tab">
                        <ul class="nav login-tab-title">
                            <li>
                                <router-link to="/login" class="login-title-divider"><i class="fas fa-sign-in-alt"></i> Login Your Account</router-link>
                            </li>
                            <li>
                                <router-link to="/register" class=""><i class="fas fa-user"></i> Register Account</router-link>
                            </li>
                        </ul>
                        <div class="auth">
                            <h4>Create Your Account</h4>
                            <p>Create an account to get Best Item</p>
                            <ul>
                                <li>
                                    <a href="#" class="bg-red"><i class="fab fa-google"></i> Google</a>
                                </li>
                                <li>
                                    <a href="#" class="bg-blue"><i class="fab fa-facebook-f"></i> Facebook</a>
                                </li>
                                <li>
                                    <a href="#" class="bg-ltblue"><i class="fab fa-twitter"></i> Twitter</a>
                                </li>
                            </ul>
                            <div class="form-divider">
                                <p>Or</p>
                            </div>
                            <!--Register form start-->
                            <form @submit.prevent="register" class="auth-form register-form">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <i class="fas fa-user"></i>
                                    <input v-model="form.name" type="text" id="name" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }" required/>
                                    <has-error :form="form" field="name"></has-error>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email address</label>
                                    <i class="fas fa-envelope"></i>
                                    <input v-model="form.email" type="email" id="email" class="form-control" :class="{ 'is-invalid': form.errors.has('email') }" required/>
                                    <has-error :form="form" field="email"></has-error>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <i class="fas fa-lock"></i>
                                    <input v-model="form.password" type="password" id="password" class="form-control" :class="{ 'is-invalid': form.errors.has('password') }" required/>
                                    <has-error :form="form" field="password"></has-error>
                                    <small id="emailHelp" class="form-text text-muted">Must use 8-15 characters and one number or symbol.</small>
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <i class="fas fa-lock"></i>
                                    <input v-model="form.password_confirmation" type="password" id="password_confirmation" class="form-control" :class="{'is-invalid': form.errors.has('password_confirmation'),}" required/>
                                    <has-error :form="form" field="password_confirmation"></has-error>
                                </div>
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="reg" @click="status = !status" required/>
                                    <label class="form-check-label Label-fix" for="reg">I Agree to Lara Commerce<router-link to="/terms">Terms of Services</router-link></label>
                                </div>
                                <button type="submit" class="auth-btn" v-if="status">Register Now</button>
                                <button class="auth-btn register-disable" v-if="!status" disabled>Register Now</button>
                            </form>
                            <!--Register form end-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Register area end-->
</template>


<script>
    export default {
        title: 'Register',
        data() {
            return {
                form: new Form({
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                }),
                status: false,
            };
        },

        methods: {
            // Submit New Register Information
            register() {
                this.$Progress.start();
                this.form.post('register').then(() => {
                    this.$router.go('/dashboard');
                    this.$Progress.finish();
                },
                error=>{
                    Fire.$emit('error', error.response.data.message ? error.response.data.message:'Something Wrong! Please try Again');
                    this.$Progress.fail();
                });
            },
        },

        created() {
            if (this.$middleware.authCheck()) {
                this.$router.push('/dashboard');
            }
        },
    };
</script>