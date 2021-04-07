<template>
    <div class="dashboard" v-if="$middleware.admin()">
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
                            <button type="button" class="cta-blue-btn p-2 pl-3 pr-3 float-right m-3 cta-mobile-btn" @click="newModal">
                                <i class="fas fa-plus"></i> Add New Coupon
                            </button>
                            <h3><i class="fas fa-receipt"></i> Coupon</h3>
                        </div>
                        <div class="dashboard-info b-2 p-2">
                            <empty message="You have no Coupon" v-if="!coupon"></empty>
                            <div class="row pt-5 pt-md-0" v-if="coupon">
                                <div class="col-xl-4 col-md-6 mb-2" v-for="coupon in coupons" :key="coupon.id">
                                    <div class="coupon b-2">
                                        <img :src="coupon.image" class="img-fluid" alt=""/>
                                        <div class="coupon-info p-2">
                                            <h4>Coupon Code: {{coupon.coupon}}</h4>
                                            <p>Coupon Name: {{coupon.name}}</p>
                                            <p>Target Price: <span v-html="icon"></span> {{coupon.target_price * rate | currency}}</p>
                                            <p v-if="coupon.fixed_offer !== null">
                                                Offer Price: <span v-html="icon"></span> {{ coupon.fixed_offer * rate | currency }}
                                            </p>
                                            <p v-else>
                                                Offer Price: {{ coupon.percent_offer + '%'}}
                                            </p>
                                            <p>
                                                Coupon Use: {{coupon.usages === null ? 'Every Time Use' : coupon.usages + ' Time Use'}}</p>
                                            <p>Coupon Usages: {{'Total ' + coupon.total_use + ' Time Use'}}</p>
                                            <p>Start Date: {{coupon.start_date | date}}</p>
                                            <p>Expire Date: {{coupon.end_date | date}}</p>
                                            <button class="cta-blue-btn p-2 pl-4 pr-4 mt-3"
                                                    @click="editData(coupon)">Edit Coupon
                                            </button>
                                            <button class="cta-red-btn p-2 pl-3 pr-3 mt-3"
                                                    @click="deleteData(coupon.id)"><i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Dashboard body end-->
                </div>
            </div>
        </div>
        <!--Dashboard area end-->

        <!-- Modal start-->
        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-if="!editMode">Add New Coupon</h5>
                        <h5 class="modal-title" v-if="editMode">Edit Coupon</h5>
                    </div>
                    <form @submit.prevent="editMode ? updateData() : createData()" class="modal-body">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name"> Coupon Name </label>
                                <input v-model="form.name" type="text" id="name" class="form-control"
                                       :class="{'is-invalid': form.errors.has('name')}" required/>
                                <has-error :form="form" field="name"></has-error>
                            </div>

                            <div class="form-group">
                                <label for="coupon"> Coupon Code </label>
                                <input @input="form.coupon = $event.target.value.toUpperCase()" :value="form.coupon"
                                       @keydown.space.prevent type="text" id="coupon"
                                       class="form-control text-uppercase"
                                       :class="{'is-invalid': form.errors.has('coupon')}" required/>
                                <has-error :form="form" field="coupon"></has-error>
                            </div>

                            <div class="form-group">
                                <label> Set Coupon Use Purchase Price Targe </label>
                                <select v-model="purchaseTarget" @change="form.target_price = ''"
                                        class="form-control form-select" required>
                                    <option :value="false">No</option>
                                    <option :value="true">Yes</option>
                                </select>
                            </div>
                            <transition name="slide" mode="out-in">
                                <div class="form-group" v-if="purchaseTarget">
                                    <label for="target_price">Coupon Use Purchase Price Target</label>
                                    <input v-model="form.target_price" type="number" id="target_price"
                                           class="form-control"
                                           :class="{'is-invalid': form.errors.has('target_price')}" required/>
                                    <has-error :form="form" field="target_price"></has-error>
                                </div>
                            </transition>

                            <div class="form-group">
                                <label> Coupon Offer Type </label>
                                <select v-model="couponOffer"
                                        @change="(form.fixed_offer = ''), (form.percent_offer = '')"
                                        class="form-control form-select" required>
                                    <option value="">Select A Offer Type</option>
                                    <option :value="true">Fixed Price</option>
                                    <option :value="false">Percent</option>
                                </select>
                            </div>

                            <transition name="slide" mode="out-in">
                                <div class="form-group" v-if="couponOffer === true">
                                    <label for="fixed_offer">Set Coupon Fixed Offer Price </label>
                                    <input v-model="form.fixed_offer" type="number" id="fixed_offer"
                                           class="form-control"
                                           :class="{'is-invalid': form.errors.has('fixed_offer')}" required/>
                                    <has-error :form="form" field="fixed_offer"></has-error>
                                </div>
                            </transition>

                            <transition name="slide" mode="out-in">
                                <div class="form-group" v-if="couponOffer === false">
                                    <label for="offer_price">Set Coupon Percent Offer Price<span class="small"> (Max 99%)</span></label>
                                    <input v-model="form.percent_offer" type="number" id="offer_price" max="99"
                                           class="form-control"
                                           :class="{'is-invalid': form.errors.has('percent_offer')}" required/>
                                    <has-error :form="form" field="percent_offer"></has-error>
                                </div>
                            </transition>

                            <div class="form-group">
                                <label>A Customer How Many Time Use This Coupon </label>
                                <select v-model="couponUsages" @change="usagesCoupon" class="form-control form-select" required>
                                    <option value="">Select A Coupon Usages Type</option>
                                    <option :value="null">Every Time</option>
                                    <option :value="true">You Chose Usage Limit</option>
                                </select>
                            </div>

                            <transition name="slide">
                                <div class="form-group" v-if="couponUsages === true">
                                    <label for="usages">Set A Customer How Many Time Use This Coupon Code</label>
                                    <input v-model="form.usages" type="number" id="usages" class="form-control" :class="{'is-invalid': form.errors.has('usages')}" required/>
                                    <has-error :form="form" field="usages"></has-error>
                                </div>
                            </transition>

                            <div class="form-group">
                                <label for="usages">Coupon Active Time </label>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <date-picker v-model="form.start_date" valueType="format" type="date" placeholder="Select Start Date"></date-picker>
                                    </div>
                                    <div class="col-lg-6">
                                        <date-picker v-model="form.end_date" valueType="format" type="date" placeholder="Select End Date"></date-picker>
                                    </div>
                                </div>
                            </div>

                            <div class="image-upload mb-3">
                                <label for="image">
                                    Select Coupon Image
                                    <span class="upload-frame">
                                        <img :src="form.image" class="img-fluid max-height-200 mt-4"/>
                                    </span>
                                </label>
                                <input type="file" accept="image/*" id="image" @change="image($event)" :required="!editMode"/>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="cta-red-btn p-2 pl-3 pr-3" data-dismiss="modal">Close</button>
                            <button type="submit" class="cta-blue-btn p-2 pl-3 pr-3" v-if="!editMode">Add Coupon</button>
                            <button type="submit" class="cta-blue-btn p-2 pl-3 pr-3" v-if="editMode">Update Coupon</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal end-->
    </div>
    <div v-else>
        <error></error>
    </div>
</template>

<script>
    import DatePicker from 'vue2-datepicker';
    import 'vue2-datepicker/index.css';
    export default {
        title: 'Coupon',
        components: {DatePicker},
        data() {
            return {
                form: new Form({
                    id: '',
                    name: '',
                    coupon: '',
                    usages: '',
                    image: '',
                    target_price: '',
                    fixed_offer: '',
                    percent_offer: '',
                    start_date: '',
                    end_date: '',
                }),
                coupons: [],
                coupon: true,
                editMode: null,
                purchaseTarget: false,
                couponOffer: '',
                couponUsages: '',
            };
        },

        methods: {
            // Load All Coupon
            loadCoupon() {
                if (this.$middleware.admin()) {
                    this.$Progress.start();
                    axios.get('api/coupon').then(response => {
                        if (response.data.length <= 0) {
                            this.coupon = false;
                        } else {
                            this.coupon = true;
                            this.coupons = response.data;
                        }
                        this.$Progress.finish();
                    },
                        error => {
                        Fire.$emit('error', 'Something Wrong! Please try Again');
                        this.$Progress.fail();
                    });
                }
            },

            // Show Instant Image & Image Validation
            image(event) {
                let file = event.target.files[0];
                let reader = new FileReader();
                reader.onloadend = () => {
                    if (file.size < 2000000) {
                        this.form.image = reader.result;
                    } else {
                        Fire.$emit('info', 'Please Use Less Than 2 MB Size Image');
                    }
                };
                reader.readAsDataURL(file);
            },

            // Coupon Usage Sub Form Show or Hide
            usagesCoupon(event) {
                event.target.value === '' ? this.form.usages = null : this.form.usages = '';
            },

            // Show Fresh New Model
            newModal() {
                this.editMode = false;
                this.form.reset();
                $('#modal').modal('show');
            },

            // Create New Coupon
            createData() {
                if (this.$middleware.admin()) {
                    this.$Progress.start();
                    this.form.post('api/coupon').then(response => {
                        if (response.data.length <= 0) {
                            this.coupon = false;
                        } else {
                            this.coupon = true;
                            this.coupons = response.data;
                        }
                        $('#modal').modal('hide');
                        this.form.reset();
                        Fire.$emit('success', 'Coupon Created successfully');
                        this.$Progress.finish();
                    },
                    error => {
                        Fire.$emit('error', error.response.data.errors ? error.response.data.errors[Object.keys(error.response.data.errors)[0]][0]:'Something Wrong! Please try Again');
                        this.$Progress.fail();
                    });
                }
            },

            // Model With Existing Coupon
            editData(data) {
                this.editMode = true;
                $('#modal').modal('show');
                data.target_price === null ? this.purchaseTarget = false : this.purchaseTarget = true;
                data.fixed_offer === null ? this.couponOffer = false : this.couponOffer = true;
                data.usages === null ? this.couponUsages = null : this.couponUsages = true;
                this.form.fill(data);
            },

            // Update Existing Coupon
            updateData() {
                if (this.$middleware.admin()) {
                    this.$Progress.start();
                    this.form.post('api/coupon/' + this.form.id).then(response => {
                        if (response.data.length <= 0) {
                            this.coupon = false;
                        } else {
                            this.coupon = true;
                            this.coupons = response.data;
                        }
                        $('#modal').modal('hide');
                        this.form.reset();
                        Fire.$emit('success', 'Coupon Updated successfully');
                        this.$Progress.finish();
                    },
                    error=> {
                        Fire.$emit('error', error.response.data.errors ? error.response.data.errors[Object.keys(error.response.data.errors)[0]][0]:'Something Wrong! Please try Again');
                        this.$Progress.fail();
                    });
                }
            },

            // Delete Existing Coupon
            deleteData(id) {
                if (this.$middleware.admin()) {
                    Swal.fire({
                        title: "Are you sure to delete this coupon?",
                        text: "You won't be able to revert this!",
                        icon: "error",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, delete it!",
                    }).then(result => {
                        if (result.isConfirmed) {
                            this.$Progress.start();
                            axios.post('api/coupon-delete/' + id).then(response => {
                                Swal.fire('Deleted!', 'Address has been deleted.', 'success');
                                if (response.data.length <= 0) {
                                    this.coupon = false;
                                } else {
                                    this.coupon = true;
                                    this.coupons = response.data;
                                }
                                this.$Progress.finish();
                            },
                            () => {
                                Swal.fire('Failed!', 'There was something Wrong.', 'warning');
                                this.$Progress.fail();
                            });
                        }
                    });
                }
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

        created() {
            this.$Progress.start();
            if (this.$middleware.admin()) {
                this.loadCoupon();
                this.$Progress.finish();
            } else {
                this.$router.push('/login');
                this.$Progress.fail();
            }
        }
    };
</script>

<style scoped>
    .slide-enter-active {
        transition: 0.3s ease-in;
    }

    .slide-leave-active {
        transition: 0.3s cubic-bezier(0, 1, 0.5, 1);
    }

    .slide-enter-to, .slide-leave {
        max-height: 150px;
        overflow: hidden;
    }

    .slide-enter, .slide-leave-to {
        overflow: hidden;
        max-height: 0;
    }
</style>