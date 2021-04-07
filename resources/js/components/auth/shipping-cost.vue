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
                                <button type="button" class="cta-blue-btn p-2 pl-3 pr-3 float-right m-3 cta-mobile-btn" @click="newModal"><i class="fas fa-plus"></i> Add New Address</button>
                                <h3><i class="fas fa-truck"></i> Shipping Cost</h3>
                            </div>
                            <div class="dashboard-info b-2 p-2">
                                <div class="table-responsive">
                                    <table class="table table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                        <tr>
                                            <th class="text-center">ID</th>
                                            <th class="text-center">Town</th>
                                            <th class="text-center">Post/Zip Code</th>
                                            <th class="text-center">Express Shipping Price</th>
                                            <th class="text-center">Normal Shipping Price</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th class="text-center">ID</th>
                                            <th class="text-center">Town</th>
                                            <th class="text-center">Post/Zip Code</th>
                                            <th class="text-center">Express Shipping Price</th>
                                            <th class="text-center">Normal Shipping Price</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                        </tfoot>
                                        <tbody v-if="!cost">
                                            <tr>
                                                <td colspan="6">
                                                    <empty message="You have no Shipping Cost Info"></empty>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tbody v-if="cost">
                                            <tr v-for="data in shippingCosts.data" :key="data.id">
                                                <td class="text-center align-middle">
                                                    {{ data.id }}
                                                </td>
                                                <td class="text-center align-middle">
                                                    {{ data.town }}
                                                </td>
                                                <td class="text-center align-middle">
                                                    {{ data.zip }}
                                                </td>
                                                <td class="text-center align-middle">
                                                    <span v-html="icon" v-if="data.express_price !== null"></span> {{data.express_price !== null ? data.express_price : "Free Shipping"}}
                                                </td>
                                                <td class="text-center align-middle">
                                                    <span v-html="icon" v-if="data.express_price !== null"></span> {{data.normal_price !== null ? data.normal_price : "Free Shipping"}}
                                                </td>
                                                <td class="text-center align-middle">
                                                    <button type="button" class="cta-blue-btn p-3 pr-3 pl-3 mb-2" @click="editData(data)"><i class="fas fa-edit"></i></button>
                                                    <button type="button" class="cta-red-btn p-3 pr-3 pl-3" @click="deleteData(data.id)"><i class="fas fa-trash-alt"></i></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <pagination :data="shippingCosts" @pagination-change-page="getResults"></pagination>
                            </div>
                    </div>
                    <!--Dashboard body start-->
                </div>
            </div>
        </div>
        <!--Dashboard area end-->

        <!-- Modal start-->
        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-if="!editMode">Add New Address</h5>
                        <h5 class="modal-title" v-if="editMode">Edit Address</h5>
                    </div>
                    <form @submit.prevent="editMode ? updateData() : createData()" class="modal-body">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="town">Town Name</label>
                                <input v-model="form.town" type="text" id="town" class="form-control" :class="{ 'is-invalid': form.errors.has('town') }" required/>
                                <has-error :form="form" field="town"></has-error>
                            </div>
                            <div class="form-group">
                                <label for="zip">Post/Zip Code</label>
                                <input v-model="form.zip" type="text" id="zip" class="form-control" :class="{ 'is-invalid': form.errors.has('zip') }" required/>
                                <has-error :form="form" field="zip"></has-error>
                            </div>
                            <div class="form-group">
                                <label for="express">Express Shipping Price
                                    <span class="small">(Empty Field Mean Free Shipping)</span></label>
                                <input v-model="form.express_price" type="number" step="0.01" max="999.99" id="express" class="form-control" :class="{ 'is-invalid': form.errors.has('express_price') }"/>
                                <has-error :form="form" field="express"></has-error>
                            </div>
                            <div class="form-group">
                                <label for="normal">Normal Shipping Price
                                    <span class="small">(Empty Field Mean Free Shipping)</span></label>
                                <input v-model="form.normal_price" type="number" step="0.01" max="999.99" id="normal" class="form-control" :class="{ 'is-invalid': form.errors.has('normal_price') }"/>
                                <has-error :form="form" field="normal"></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="cta-red-btn p-2 pl-3 pr-3" data-dismiss="modal">Close</button>
                            <button type="submit" class="cta-blue-btn p-2 pl-3 pr-3" v-if="!editMode">Add Address</button>
                            <button type="submit" class="cta-blue-btn p-2 pl-3 pr-3" v-if="editMode">Update Address</button>
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
        title: 'Shipping Cost',
        data() {
            return {
                form: new Form({
                    id: '',
                    town: '',
                    zip: '',
                    express_price: '',
                    normal_price: '',
                }),
                shippingCosts: {},
                cost: true,
                editMode: null,
            };
        },

        methods: {
            // Load All Shipping Cost
            loadShippingCost() {
                if (this.$middleware.admin()) {
                    this.$Progress.start();
                    axios.get('api/shipping-cost').then(response => {
                        if (response.data.data.length <= 0) {
                            this.cost = false;
                        } else {
                            this.cost = true;
                            this.shippingCosts = response.data;
                        }
                        this.$Progress.finish();
                    },
                    () => {
                        Fire.$emit('error', 'There was something Wrong.');
                        this.$Progress.fail();
                    });
                }
            },
            getResults(page = 1) {
                if (this.$middleware.admin()) {
                    this.$Progress.start();
                    axios.get('api/shipping-cost?page=' + page).then(response => {
                        this.shippingCosts = response.data;
                        this.$Progress.finish();
                    },
                    () => {
                        Fire.$emit('error', 'There was something Wrong.');
                        this.$Progress.fail();
                    });
                }
            },

            // New Fresh Modal
            newModal() {
                this.editMode = false;
                this.form.reset();
                $('#modal').modal('show');
            },

            // Create New Shipping Cost
            createData() {
                if (this.$middleware.admin()) {
                    this.$Progress.start();
                    this.form.post('api/shipping-cost').then(response => {
                        if (response.data.data.length <= 0) {
                            this.cost = false;
                        } else {
                            this.cost = true;
                            this.shippingCosts = response.data;
                        }
                        $('#modal').modal('hide');
                        Fire.$emit('success', 'New Address Created successfully');
                        this.$Progress.finish();
                    },
                    error=> {
                        Fire.$emit('error', error.response.data.errors ? error.response.data.errors[Object.keys(error.response.data.errors)[0]][0]:'Something Wrong! Please try Again');
                        this.$Progress.fail();
                    });
                }
            },

            // Modal With Existing Shipping Cost
            editData(data) {
                this.editMode = true;
                $('#modal').modal('show');
                this.form.fill(data);
            },

            // Update Existing Shipping Cost
            updateData() {
                if (this.$middleware.admin()) {
                    this.$Progress.start();
                    this.form.post('api/shipping-cost/' + this.form.id).then(response => {
                        if (response.data.data.length <= 0) {
                            this.cost = false;
                        } else {
                            this.cost = true;
                            this.shippingCosts = response.data;
                        }
                        $('#modal').modal('hide');
                        Fire.$emit('success', 'Address Updated successfully');
                        this.$Progress.finish();
                    },
                    error=> {
                        Fire.$emit('error', error.response.data.errors ? error.response.data.errors[Object.keys(error.response.data.errors)[0]][0]:'Something Wrong! Please try Again');
                        this.$Progress.fail();
                    });
                }
            },

            // Delete Existing Shipping Cost
            deleteData(id) {
                if (this.$middleware.admin()) {
                    Swal.fire({
                        title: "Are you sure to delete the Address?",
                        text: "You won't be able to revert this!",
                        icon: "error",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, delete it!",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.$Progress.start();
                            axios.post('api/shipping-cost-delete/' + id).then(response => {
                                if (response.data.data.length <= 0) {
                                    this.cost = false;
                                } else {
                                    this.cost = true;
                                    this.shippingCosts = response.data;
                                }
                                Swal.fire("Deleted!", "Shipping Cost has been deleted.", "success");
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
                this.loadShippingCost();
                this.$Progress.finish();
            }else{
                this.$router.push('/login');
                this.$Progress.fail();
            }
        },
    };
</script>