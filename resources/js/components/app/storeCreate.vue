<template>
    <!--Store create area start -->
    <div class="container">
        <div class="error">
            <div class="container text-center">
                <img src="/images/store.png" alt="You Don't Have A Store" class="img-fluid">
                <h1>We See, You Don't Have A Store.</h1>
                <h5>
                    Do You Want to Upgrade Your Business With Us And Get More Customer?
                </h5>
            </div>
        </div>
        <transition name="slide">
            <form @submit.prevent="createStore" class="mt-5">
                <div class="form-group row mb-3">
                    <label for="name" class="col-lg-2">Store Name</label>
                    <input v-model="form.name" type="text" class="form-control col-lg-10" id="name" :class="{ 'is-invalid': form.errors.has('name') }" placeholder="Type Your Store Name" required/>
                    <has-error :form="form" field="name"></has-error>
                </div>

                <div class="image-upload row mb-3">
                    <div class="col-lg-2">
                        <p>Store Logo</p>
                    </div>
                    <div class="col-lg-10 p-0">
                        <label for="image">
                            Select Store Logo
                            <span class="upload-frame">
                              <img :src="form.image" class="img-fluid max-height-200 mt-4" :alt="form.image"/>
                            </span>
                        </label>
                        <input type="file" accept="image/*" id="image" name="image" @change="logo($event)"/>
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <label for="address" class="col-lg-2">Store Address</label>
                    <input v-model="form.address" type="text" class="form-control col-lg-10" id="address" :class="{ 'is-invalid': form.errors.has('address') }" placeholder="Type Your Store Address" required/>
                    <has-error :form="form" field="address"></has-error>
                </div>

                <div class="form-group row mb-3">
                    <label for="map" class="col-lg-2">
                        Store Map <small>(Google Map Embed Code)</small>
                    </label>
                    <input v-model="form.map" type="text" class="form-control col-lg-10" id="map" :class="{ 'is-invalid': form.errors.has('map') }" placeholder="Paste Your Google Map Embaded Code"/>
                    <has-error :form="form" field="map"></has-error>
                    <div class="store-location offset-lg-2 col-lg-10 mt-2" v-html="form.map"></div>
                </div>

                <div class="form-group row mb-3">
                    <label class="col-lg-2">Store Description</label>
                    <div class="col-lg-10 p-0">
                        <vue-editor v-model="form.description"></vue-editor>
                    </div>
                </div>

                <div class="offset-lg-2 col-lg-10">
                    <button type="submit" class="cta-blue-btn p-2 pl-3 pr-3">Submit</button>
                </div>
            </form>
        </transition>
    </div>
    <!--Store create area end -->
</template>

<script>
    export default {
        data() {
            return {
                form: new Form({
                    name: '',
                    image: '',
                    address: '',
                    map: '',
                    description: '',
                }),
                store: false,
                loading: true,
            };
        },

        methods: {
            // Create Your New Store
            createStore() {
                this.$Progress.start();
                if (this.$middleware.adminOrUser()) {
                    this.form.post('/api/store/create').then(() => {
                        this.$router.push('/dashboard');
                        Fire.$emit('success', 'Store Created successfully');
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

            logo(event) {
                let file = event.target.files[0];
                let reader = new FileReader();
                reader.onloadend = () => {
                    this.form.image = reader.result;
                };
                reader.readAsDataURL(file);
            },
        }
    };
</script>

<style scoped>
    .slide-enter-active,
    .slide-leave-active {
        transition: 0.3s;
    }

    .slide-enter-to, .slide-leave {
        max-height: 600px;
        overflow: hidden;
    }

    .slide-enter, .slide-leave-to {
        overflow: hidden;
        max-height: 0;
    }


    .slideLeft-enter-active,
    .slideLeft-leave-active {
        transition: 0.3s;
    }

    .slideLeft-enter-to, .slideLeft-leave {
        min-width: 100%;
        overflow: hidden;
    }

    .slideLeft-enter, .slideLeft-leave-to {
        overflow: hidden;
        max-height: 0;
    }
</style>
