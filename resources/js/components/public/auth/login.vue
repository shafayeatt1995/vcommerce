<template>
    <!--Login area start-->
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
                            <h4>Login to Your Account</h4>
                            <p>Login with your social network, Note: Laracommerce will neverpost any content to your social pages.</p>
                            <ul>
                                <li>
                                    <a href="api/auth/google/redirect" class="bg-red"><i class="fab fa-google"></i> Google</a>
                                </li>
                                <li>
                                    <a href="api/auth/facebook/redirect" class="bg-blue"><i class="fab fa-facebook-f"></i> Facebook</a>
                                </li>
                                <li>
                                    <a href="api/auth/twitter/redirect" class="bg-ltblue"><i class="fab fa-twitter"></i> Twitter</a>
                                </li>
                            </ul>
                            <div class="form-divider">
                                <p>Or</p>
                            </div>
                            <!--Login form area start-->
                            <form @submit.prevent="login" class="auth-form login-form">
                                <div class="form-group">
                                    <label for="email">Email address</label>
                                    <i class="fas fa-user"></i>
                                    <input v-model="form.email" type="email" class="form-control" id="email" :class="{ 'is-invalid': form.errors.has('email') }" autocomplete="email" required/>
                                    <has-error :form="form" field="email"></has-error>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <i class="fas fa-lock"></i>
                                    <input v-model="form.password" :type="password ? 'text':'password'" class="form-control" id="password" :class="{ 'is-invalid': form.errors.has('password') }" autocomplete="password" required/>
                                    <has-error :form="form" field="email"></has-error>
                                    <span class="login-password cursor-pointer" @click="password = !password"><i :class="password ? 'fas fa-eye':'fas fa-eye-slash'"></i></span>
                                </div>
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="remember" v-model="remember"/>
                                    <label class="form-check-label Label-fix" for="remember">Remember Me</label>
                                </div>
                                <a href="" class="forget-password">Forgot Password ?</a>
                                <button type="submit" class="cta-blue-btn p-2 pl-5 pr-5" v-if="!loading">Login</button>
                                <button type="submit" class="cta-blue-btn p-1 pl-5 pr-5 mt-2" v-if="loading" disabled>
                                    <span class="spinner-border text-light" role="status">
                                        <span class="visually-hidden"></span>
                                    </span>
                                </button>
                            </form>
                            <!--Login form area end-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Login area end-->
</template>


<script>
    export default {
        title: 'Login',
        data() {
            return {
                form: new Form({
                    email: '',
                    password: '',
                }),
                loading: false,
                password: false,
                remember: false
            };
        },

        methods: {
            // Login Function
            login() {
                this.loading = true;
                this.form.post('login').then(() => {
                    if(this.remember){
                        localStorage.setItem(btoa('email'), btoa(this.form.email));
                        localStorage.setItem(btoa('password') ,btoa(this.form.password));
                    } else {
                        localStorage.removeItem(btoa('email'));
                        localStorage.removeItem(btoa('password'));
                    }
                        this.loading = false;
                        this.$router.go('/dashboard');
                    },
                () => {
                    this.loading = false;
                });
            },

//            githubLogin(){
//                this.$router.go('api/auth/github/redirect');
//            },
        },

        created() {
            if(localStorage.getItem(btoa('email')) !== null){
                this.form.email = atob(localStorage.getItem(btoa('email')));
                this.form.password = atob(localStorage.getItem(btoa('password')));
                this.remember = true;
            }
            if (this.$middleware.authCheck()) {
                this.$router.push('/dashboard');
            }
        },
    };
</script>
