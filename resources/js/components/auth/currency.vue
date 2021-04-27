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
                    <!--Dashboard currency area start-->
                    <div class="dashboard-body">
                        <div class="dashboard-title">
                            <button type="button" class="cta-blue-btn p-2 pl-3 pr-3 float-right m-3 cta-mobile-btn" @click="newModal">
                                <i class="fas fa-plus"></i> Add New Currency
                            </button>
                            <h3><i class="fas fa-dollar-sign"></i> Currency</h3>
                        </div>
                        <div class="dashboard-info b-2 p-2">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" >
                                    <thead>
                                    <tr>
                                        <th class="text-center align-middle">SL No</th>
                                        <th class="text-center align-middle">Currency Icon</th>
                                        <th class="text-center align-middle">Currency Country</th>
                                        <th class="text-center align-middle">Currency Name</th>
                                        <th class="text-center align-middle">Currency Exchange Rate</th>
                                        <th class="text-center align-middle">Default Currency</th>
                                        <th class="text-center align-middle">Action</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th class="text-center align-middle">SL No</th>
                                        <th class="text-center align-middle">Currency Icon</th>
                                        <th class="text-center align-middle">Currency Country</th>
                                        <th class="text-center align-middle">Currency Name</th>
                                        <th class="text-center align-middle">Currency Exchange Rate</th>
                                        <th class="text-center align-middle">Default Currency</th>
                                        <th class="text-center align-middle">Action</th>
                                    </tr>
                                    </tfoot>
                                    <tbody v-if="currencyData">
                                        <tr v-for="(currency, key) in currencies" :key="currency.id">
                                            <td class="text-center align-middle">
                                                {{ key+1 }}
                                            </td>
                                            <td class="text-center align-middle strong" v-html="currency.icon"></td>
                                            <td class="text-center align-middle">
                                                {{ currency.country }}
                                            </td>
                                            <td class="text-center align-middle">
                                                {{ currency.name }}
                                            </td>
                                            <td class="text-center align-middle">
                                                {{ currency.rate }}
                                            </td>
                                            <td class="text-center align-middle">
                                                <button type="button" class="p-2 px-3" :class="currency.default ? 'cta-green-btn':'cta-grey-btn'" @click="setDefault(currency.id)"><i class="fas fa-check-circle"></i></button>
                                            </td>
                                            <td class="text-center align-middle">
                                                <button type="button" class="cta-blue-btn p-2 pr-3 pl-3 mb-2" @click="editData(currency)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="cta-red-btn p-2 pr-3 pl-3" @click="deleteData(currency.id)">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="6">
                                                <empty message="Currency table is empty"></empty>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!--Dashboard currency area end-->
                </div>
            </div>
        </div>
        <!--Dashboard area end -->

        <!-- Modal start -->
        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ editMode ? 'Edit Currency':'Add New Currency'}}</h5>
                    </div>
                    <form @submit.prevent="editMode ? updateData() : createData()" class="modal-body">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="icon">Currency Icon</label>
                                <input v-model="form.icon" type="text" id="icon" class="form-control" :class="{ 'is-invalid': form.errors.has('icon') }"/>
                                <has-error :form="form" field="icon"></has-error>
                            </div>
                            <div class="form-group">
                                <label for="country">Currency Country</label>
                                <input v-model="form.country" type="text" id="country" class="form-control" :class="{ 'is-invalid': form.errors.has('country') }" required/>
                                <has-error :form="form" field="country"></has-error>
                            </div>
                            <div class="form-group">
                                <label for="name">Currency Name</label>
                                <input v-model="form.name" type="text" id="name" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }" required/>
                                <has-error :form="form" field="name"></has-error>
                            </div>
                            <div class="form-group">
                                <label for="rate">Currency Exchange Rate</label>
                                <input v-model="form.rate" type="number" step=".01" id="rate" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }" required/>
                                <has-error :form="form" field="rate"></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="cta-red-btn p-2 pl-3 pr-3" data-dismiss="modal">
                                Close
                            </button>
                            <button type="submit" class="cta-blue-btn p-2 pl-3 pr-3">
                                {{ editMode ? 'update Currency' : 'Add Currency'}}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal end -->
    </div>
    <div v-else>
        <error></error>
    </div>
</template>

<script>
    export default {
        title: 'Multi Currency',
        data(){
            return {
                currency: {},
                form: new Form({
                   id: '',
                   icon: '',
                   country: '',
                   name: '',
                   rate: '',
                }),
                currencyData: true,
                editMode: false,
            }
        },

        methods: {
            // Load New Fresh Modal
            newModal() {
                this.editMode = false;
                this.form.reset();
                $('#modal').modal('show');
            },

            // Create New Currency
            createData() {
                if (this.$middleware.admin()) {
                    this.$Progress.start();
                    this.form.post('api/currency').then(() => {
                        this.$store.dispatch('getCurrencies');
                        $('#modal').modal('hide');
                        Fire.$emit('success', 'Currency Created successfully');
                        this.$Progress.finish();
                    },
                    error=> {
                        Fire.$emit('error', error.response.data.errors ? error.response.data.errors[Object.keys(error.response.data.errors)[0]][0]:'Something Wrong! Please try Again');
                        this.$Progress.fail();
                    });
                }
            },

            // Open Modal Existing Currency
            editData(data) {
                this.editMode = true;
                $('#modal').modal('show');
                this.form.fill(data);
            },

            // Update Existing Currency
            updateData() {
                if (this.$middleware.admin()) {
                    this.$Progress.start();
                    this.form.post('api/currency-update/' + this.form.id).then(() => {
                        this.$store.dispatch('getCurrencies');
                        $('#modal').modal('hide');
                        Fire.$emit('success', 'Currency Updated successfully');
                        this.$Progress.finish();
                    },
                    error => {
                        Fire.$emit('error', error.response.data.errors ? error.response.data.errors[Object.keys(error.response.data.errors)[0]][0]:'Something Wrong! Please try Again');
                        this.$Progress.fail();
                    });
                }
            },

            // Delete existing Currency
            deleteData(id) {
                if (this.$middleware.admin()) {
                    Swal.fire({
                        title: "Are you sure to delete the Currency?",
                        text: "You won't be able to revert this!",
                        icon: "error",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, delete it!",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.$Progress.start();
                            axios.post('api/currency-delete/' + id).then(() => {
                                Swal.fire("Deleted!", "Currency has been deleted successfully.", "success");
                                this.$store.dispatch('getCurrencies');
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

            // Set Default Currency
            setDefault(id){
                if (this.$middleware.admin()) {
                    this.$Progress.finish();
                    axios.post('/api/currency-default/' + id).then(response => {
                        this.$store.dispatch('getCurrencies');
                        Fire.$emit('success', response.data);
                        this.$Progress.finish();
                    },
                    () => {
                        Fire.$emit('error', 'Something Wrong! Please try Again');
                        this.$Progress.fail();
                    });
                }
            },
        },

        computed: {
            // Currency Information
            currencies() {
                return this.$store.state.currencies;
            },
        },

        created(){
            if(!this.$middleware.admin()){
                this.$router.push('/login');
            }
        }
    }
</script>