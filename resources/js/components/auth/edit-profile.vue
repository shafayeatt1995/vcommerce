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
                        <div class="dashboard-title">
                            <h3><i class="fas fa-edit"></i> Edit Profile</h3>
                        </div>
                        <div class="dashboard-info b-2 pt-5">
                            <!--User Setting area start-->
                            <div class="edit-user">
                                <ul class="nav user-tab-title" id="myTab" role="tablist">
                                    <li>
                                        <a data-toggle="tab" href="#user-info" aria-selected="true" class="active" @click.right.prevent><i class="fas fa-user"></i> User Info</a>
                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#user-store" aria-selected="false" @click.right.prevent><i class="fas fa-store-alt"></i> Store</a>
                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#user-password" aria-selected="false" @click.right.prevent v-if="!socialAccount"><i class="fas fa-key"></i> Password</a>
                                    </li>
                                </ul>
                                <div class="tab-content edit-user-content p-2">
                                    <div class="tab-pane fade show active description-tab" id="user-info">
                                        <form @submit.prevent="updateInfo">
                                            <div class="form-group">
                                                <label for="name">User Name</label>
                                                <input v-model="user.name" type="text" id="name" class="form-control" :class="{'is-invalid': user.errors.has('name') }" required/>
                                                <has-error :form="user" field="name"></has-error>
                                            </div>
                                            <div class="image-upload">
                                                <label for="image">
                                                    Select Your Image
                                                    <span class="upload-frame"><img :src="user.image" class="img-fluid max-height-200 mt-4"/></span>
                                                </label>
                                                <input type="file" accept="image/*" id="image" name="image" @change="image($event)"/>
                                            </div>
                                            <div class="text-right">
                                                <button type="submit" class="cta-blue-btn p-2 pl-3 pr-3 mt-3">Update Info</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="user-store">
                                        <div class="container">
                                            <form @submit.prevent="updateStore" class="mt-5" v-if="store">
                                                <div class="form-group mb-3">
                                                    <label for="store-name">Store Name</label>
                                                    <input v-model="form.name" type="text" class="form-control" id="store-name" :class="{ 'is-invalid': form.errors.has('name') }" placeholder="Type Your Store Name" required/>
                                                    <has-error :form="form" field="name"></has-error>
                                                </div>

                                                <div class="image-upload mb-3">
                                                        <p>Store Logo</p>
                                                    <div class="p-0">
                                                        <label for="store-image">
                                                            Select Store Logo
                                                            <span class="upload-frame">
                                                                <img :src="form.image" class="img-fluid max-height-200 mt-4"/>
                                                            </span>
                                                        </label>
                                                        <input type="file" accept="image/*" id="store-image" name="image" @change="logo($event)"/>
                                                    </div>
                                                </div>

                                                <div class="form-group mb-3">
                                                    <label for="address">Store Address</label>
                                                    <input v-model="form.address" type="text" class="form-control" id="address" :class="{ 'is-invalid': form.errors.has('address') }" placeholder="Type Your Store Address" required/>
                                                    <has-error :form="form" field="address"></has-error>
                                                </div>

                                                <div class="form-group mb-3">
                                                    <label for="map">
                                                        Store Map <small>(Google Map Embed Code)</small>
                                                    </label>
                                                    <input v-model="form.map" type="text" class="form-control" id="map" :class="{ 'is-invalid': form.errors.has('map') }" placeholder="Paste Your Google Map Embaded Code"/>
                                                    <has-error :form="form" field="map"></has-error>
                                                </div>
                                                <div class="store-location" v-html="form.map"></div>

                                                <div class="form-group mb-3">
                                                    <label>Store Description</label>
                                                    <div class="p-0">
                                                        <vue-editor v-model="form.description"></vue-editor>
                                                    </div>
                                                </div>
                                                    <button type="submit" class="cta-blue-btn p-2 pl-3 pr-3">Submit</button>
                                            </form>
                                            <div class="text-center" v-else>
                                                <img src="/images/store.png" alt="You Don't Have A Store" class="img-fluid m-2">
                                                <router-link to="/store" class="cta-blue-btn p-2 pl-4 pr-4 m-3">Create your store</router-link>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="user-password" v-if="!socialAccount">
                                        <form @submit.prevent="updatePassword">
                                            <div class="form-group">
                                                <label for="old-password">Old Password</label>
                                                <span class="show-password cursor-pointer" @click="oldPassword = !oldPassword"><i :class="oldPassword ? 'fas fa-eye':'fas fa-eye-slash'"></i></span>
                                                <input v-model="password.old_password" :type="oldPassword ? 'text':'password'" id="old-password" class="form-control" :class="{'is-invalid': password.errors.has('old_password') }" required/>
                                                <has-error :form="password" field="old_password"></has-error>
                                            </div>
                                            <div class="form-group">
                                                <label for="new-password">New Password <span class="small">(Must Be At Last 8 Character)</span></label>
                                                <span class="show-password cursor-pointer" @click="newPassword = !newPassword"><i :class="newPassword ? 'fas fa-eye':'fas fa-eye-slash'"></i></span>
                                                <input v-model="password.password" :type="newPassword ? 'text':'password'" id="new-password" class="form-control" :class="{'is-invalid': password.errors.has('password') }" required/>
                                                <has-error :form="password" field="password"></has-error>
                                            </div>
                                            <div class="form-group">
                                                <label for="old-password">Confirm Password</label>
                                                <span class="show-password cursor-pointer" @click="confirmPassword = !confirmPassword"><i :class="confirmPassword ? 'fas fa-eye':'fas fa-eye-slash'"></i></span>
                                                <input v-model="password.password_confirmation" :type="confirmPassword ? 'text':'password'" id="conform-password" class="form-control" :class="{'is-invalid': password.errors.has('password_confirmation') }" required/>
                                                <has-error :form="password" field="password_confirmation"></has-error>
                                            </div>
                                            <div class="text-right">
                                                <button type="submit" class="cta-blue-btn p-2 pl-3 pr-3 mt-3" :disabled="password.password !== password.password_confirmation">Update Password</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!--User Setting area end-->
                        </div>
                    </div>
                    <!--Dashboard body end-->
                </div>
            </div>
        </div>
        <!--Dashboard area end-->
    </div>
    <div class="dashboard" v-else>
        <error></error>
    </div>
</template>
<script>
    export default {
        title: 'Edit Profile',
        data() {
            return {
                user: new Form({
                    name: '',
                    image: '',
                }),
                form: new Form({
                    name: '',
                    image: '',
                    address: '',
                    map: '',
                    description: '',
                }),
                password: new Form({
                    old_password: '',
                    password: '',
                    password_confirmation : '',
                }),
                store: null,
                oldPassword:false,
                newPassword:false,
                confirmPassword:false,
                socialAccount: true,
            };
        },

        methods: {
            //Get Authenticate User Data
            getUser() {
                axios.get('api/user').then(response => {
                    this.user.name = response.data.user.name;
                    this.user.image = response.data.user.image;
                    this.socialAccount = response.data.user.social_account;
                        if (response.data.store) {
                        this.form.name = response.data.store.name;
                        this.form.image = response.data.store.logo;
                        this.form.address = response.data.store.address;
                        this.form.map = response.data.store.map;
                        this.form.description = response.data.store.description;
                        this.store = true;
                    } else{
                        this.store = false;
                    }
                    this.$Progress.finish();
                },
                ()=>{
                    Fire.$emit('error', 'Something Wrong! Please try Again');
                    this.$Progress.fail();
                });
            },

            // Instant Show Image
            image(event){
                let file = event.target.files[0];
                let reader = new FileReader();
                reader.onloadend = () => {
                    this.user.image = reader.result;
                };
                reader.readAsDataURL(file);
            },
            logo(event){
                let file = event.target.files[0];
                let reader = new FileReader();
                reader.onloadend = () => {
                    this.form.image = reader.result;
                };
                reader.readAsDataURL(file);
            },

            // Update Store
            updateStore() {
                this.$Progress.start();
                if (this.$middleware.adminOrSeller()) {
                    this.form.post('/api/store/update').then(() => {
                        Fire.$emit('success', 'Store Update successfully');
                        this.$Progress.finish();
                    },
                    error=>{
                        Fire.$emit('error', error.response.data.errors ? error.response.data.errors[Object.keys(error.response.data.errors)[0]][0]:'Something Wrong! Please try Again');
                        this.$Progress.fail();
                    });
                }else{
                    Fire.$emit('error', 'You do not have permission to access this option');
                    this.$Progress.fail();
                }
            },

            // Update Authenticate User Information
            updateInfo(){
                this.$Progress.start();
                this.user.post('/api/update-user').then(()=>{
                    this.$emit('user');
                    Fire.$emit('success', 'User Info Update Successfully');
                    this.$Progress.finish();
                },
                error=>{
                    Fire.$emit('error', error.response.data.errors ? error.response.data.errors[Object.keys(error.response.data.errors)[0]][0]:'Something Wrong! Please try Again');
                    this.$Progress.fail();
                })
            },

            // Update Authenticate User Password
            updatePassword(){
                this.$Progress.start();
                if(this.password.password === this.password.password_confirmation){
                    this.password.post('/api/update-password').then(()=>{
                        this.$Progress.finish();
                        Swal.fire({
                            title: "Do You Want to Check Your New Password",
                            text: "",
                            icon: "success",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Logout",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.$Progress.start();
                                axios.post('/logout').then(() => {
                                    this.$Progress.finish();
                                    this.$router.go('/login');
                                },
                                ()=>{
                                    Fire.$emit('error', 'Something Wrong! Please try Again');
                                    this.$progress.fail();
                                });
                            }
                        });
                    },
                    error=>{
                        Fire.$emit('error', error.response.data.errors ? error.response.data.errors[Object.keys(error.response.data.errors)[0]][0]:error.response.data);
                        this.$Progress.fail()
                    })
                }else{
                    Fire.$emit('error', 'Confirm Password Not Match');
                    this.$Progress.fail()
                }
            }
        },

        created() {
            this.$Progress.start();
            if (this.$middleware.authCheck()) {
                this.getUser();

                this.$on('user',()=>{
                    this.getUser();
                })
            }else{
                this.$router.push('/login');
                this.$Progress.fail();
            }
        },
    };
</script>
