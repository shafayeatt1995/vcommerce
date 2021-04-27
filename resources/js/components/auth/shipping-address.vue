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
                            <button type="button" class="cta-blue-btn p-2 pl-3 pr-3 float-right m-3 cta-mobile-btn" @click="newModal">
                                <i class="fas fa-plus"></i> Add New Shipping Address
                            </button>
                            <h3><i class="fas fa-address-card"></i> Shipping Address</h3>
                        </div>
                        <div class="dashboard-info b-2 p-2">
                            <empty message="You have no Shipping Address" v-if="!address"></empty>
                            <div class="row" v-if="address">
                                <div class="col-xl-4 col-md-6 mb-2" v-for="address in addresses" :key="address.id">
                                    <div class="address">
                                        <h6 class="bg-blue color-white">
                                            {{ address.address_name }}
                                        </h6>
                                        <div class="address-name">
                                            <h3>{{ address.name }}</h3>
                                            <p><small>Address:</small>{{address.address + ", " + address.shipping_cost.town + " (" + address.shipping_cost.zip + ")"}}</p>
                                        </div>
                                        <div class="address-phone">
                                            <p><small>Phone:</small>{{ address.phone }}</p>
                                        </div>
                                        <div class="address-email">
                                            <p><small>Email:</small>{{ address.email }}</p>
                                        </div>
                                        <button class="cta-blue-btn" @click="editData(address)">Edit Shipping Address</button>
                                        <button class="cta-red-btn" @click="deleteData(address.id)"><i class="fas fa-trash-alt"></i></button>
                                        <button :class="address.default ? 'cta-green-btn' : 'cta-grey-btn'" @click="setDefault(address.id)"><i class="fas fa-check-circle"></i></button>
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
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-if="!editMode">Add New Shipping Address</h5>
                        <h5 class="modal-title" v-if="editMode">Edit Shipping Address</h5>
                    </div>
                    <form @submit.prevent="editMode ? updateData() : createData()" class="modal-body">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="title">Address Title</label>
                                <input v-model="form.address_name" type="text" id="title" class="form-control" :class="{ 'is-invalid': form.errors.has('address_name') }" required/>
                                <has-error :form="form" field="address_name"></has-error>
                            </div>
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input v-model="form.name" type="text" id="name" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }" required/>
                                <has-error :form="form" field="name"></has-error>
                            </div>
                            <div class="form-group">
                                <label for="town">Town</label>
                                <select class="form-control form-select" id="town" v-model="form.shipping_cost_id" :class="{ 'is-invalid': form.errors.has('shipping_cost_id') }" style="background-image: url('/images/download.svg')" required>
                                    <option value="n">Select A Town</option>
                                    <option v-for="data in shippingCost" :value="data.id" :key="data.id">{{ data.town + " (" + data.zip + ")" }}</option>
                                </select>
                                <has-error :form="form" field="shipping_cost_id"></has-error>
                            </div>
                            <div class="form-group">
                                <label for="address">Street Address</label>
                                <input v-model="form.address" type="text" id="address" class="form-control" :class="{ 'is-invalid': form.errors.has('address') }" required/>
                                <has-error :form="form" field="address"></has-error>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input v-model="form.phone" type="text" id="phone" class="form-control" :class="{ 'is-invalid': form.errors.has('phone') }" required/>
                                <has-error :form="form" field="phone"></has-error>
                            </div>
                            <div class="form-group">
                                <label for="email">Email <span class="small">(Optional)</span></label>
                                <input v-model="form.email" type="email" id="email" class="form-control" :class="{ 'is-invalid': form.errors.has('email') }"/>
                                <has-error :form="form" field="email"></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="cta-red-btn p-2 pl-3 pr-3" data-dismiss="modal">Close</button>
                            <button type="submit" class="cta-blue-btn p-2 pl-3 pr-3" v-if="!editMode">Add Shipping Address</button>
                            <button type="submit" class="cta-blue-btn p-2 pl-3 pr-3" v-if="editMode">Update Shipping Address</button>
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
    export default {
        title: 'Shipping Address',
        data() {
            return {
                form: new Form({
                    id: '',
                    shipping_cost_id: 'n',
                    address_name: '',
                    name: '',
                    address: '',
                    phone: '',
                    email: '',
                }),
                addresses: {},
                shippingCost: [],
                address: true,
                editMode: null,
            };
        },

        methods: {
            // Load Authenticate User Shipping Address
            loadShippingAddress() {
                if (this.$middleware.authCheck()) {
                    this.$Progress.start();
                    axios.get('api/shipping-address').then(response => {
                        if (response.data.length <= 0) {
                            this.address = false;
                        } else {
                            this.address = true;
                            this.addresses = response.data;
                        }
                        this.$Progress.finish();
                    },
                    error => {
                        Fire.$emit('error', 'Something Wrong! Please try Again');
                        this.$Progress.fail();
                    });
                }
            },

            // Load All Shipping Cost
            getShippingCost() {
                if (this.$middleware.authCheck()) {
                    this.$Progress.start();
                    axios.get('api/get/shipping-cost').then(response => {
                        this.shippingCost = response.data;
                        this.$Progress.finish();
                    },
                    error => {
                        Fire.$emit('error', 'Something Wrong! Please try Again');
                        this.$Progress.fail();
                    });
                }
            },

            // Open New Fresh Modal
            newModal() {
                this.editMode = false;
                this.form.reset();
                $('#modal').modal('show');
            },

            // Create New Shipping Address
            createData() {
                if (this.$middleware.authCheck()) {
                    this.$Progress.start();
                    this.form.post('api/shipping-address').then(response => {
                        if (response.data.length <= 0) {
                            this.address = false;
                        } else {
                            this.address = true;
                            this.addresses = response.data;
                        }
                        $('#modal').modal('hide');
                        Fire.$emit('success', 'Address Created successfully');
                        this.$Progress.finish();
                    },
                    error => {
                        Fire.$emit('error', error.response.data.errors ? error.response.data.errors[Object.keys(error.response.data.errors)[0]][0]:'Something Wrong! Please try Again');
                        this.$Progress.fail();
                    });
                }
            },

            // Modal With Existing Shipping Address
            editData(data) {
                this.editMode = true;
                $('#modal').modal('show');
                this.form.fill(data);
            },

            // Edit Existing Shipping Address
            updateData() {
                if (this.$middleware.authCheck()) {
                    this.$Progress.start();
                    this.form.post('api/shipping-address/' + this.form.id).then(response => {
                        if (response.data.length <= 0) {
                            this.address = false;
                        } else {
                            this.address = true;
                            this.addresses = response.data;
                        }
                        $('#modal').modal('hide');
                        Fire.$emit('success', 'Address Updated successfully');
                        this.$Progress.finish();
                    },
                    error => {
                        Fire.$emit('error', error.response.data.errors ? error.response.data.errors[Object.keys(error.response.data.errors)[0]][0]:'Something Wrong! Please try Again');
                        this.$Progress.fail();
                    });
                }
            },

            // Delete Existing Shipping Address
            deleteData(id) {
                if (this.$middleware.authCheck()) {
                    Swal.fire({
                        title: "Are you sure to delete this address?",
                        text: "You won't be able to revert this!",
                        icon: "error",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, delete it!",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.$Progress.start();
                            axios.post('api/shipping-address-delete/' + id).then(response => {
                                Swal.fire("Deleted!", "Address has been deleted.", "success");
                                if (response.data.length <= 0) {
                                    this.address = false;
                                } else {
                                    this.address = true;
                                    this.addresses = response.data;
                                }
                                this.$Progress.finish();
                            },
                            () => {
                                Swal.fire("Failed!", "There was something Wrong.", "warning");
                                this.$Progress.fail();
                            });
                        }
                    });
                }
            },

            setDefault(id){
                this.$Progress.start();
                axios.post('/api/shipping-address-default/'+id).then(()=>{
                    Fire.$emit('getShippingAddress');
                    Fire.$emit('success', 'Default Address Successfully Change');
                    this.$Progress.finish();
                },
                ()=>{
                    Fire.$emit('error', 'Something Wrong! Please try Again');
                    this.$Progress.fail();
                })
            },
        },

        created() {
            this.$Progress.start();
            if (this.$middleware.authCheck()) {
                this.loadShippingAddress();
                this.getShippingCost();
                this.$Progress.finish();

                Fire.$on('getShippingAddress',()=>{
                    this.loadShippingAddress();
                })
            }else{
                this.$router.push('/login');
                this.$Progress.fail();
            }
        },
    };
</script>
