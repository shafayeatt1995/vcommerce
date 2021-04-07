<template>
    <div class="dashboard" v-if="$middleware.admin()">
        <!-- Dashboard area start-->
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
                    <!--Dashboard brand area start -->
                    <div class="dashboard-body">
                        <div class="dashboard-title">
                            <button type="button" class="cta-blue-btn p-2 pl-3 pr-3 float-right m-3 cta-mobile-btn" @click="newModal">
                                <i class="fas fa-plus"></i> Add New Brand
                            </button>
                            <h3><i class="fas fa-tags"></i> Brands</h3>
                        </div>
                        <div class="dashboard-info b-2 p-2">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th class="text-center">ID</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Image</th>
                                        <th class="text-center">Create Date</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th class="text-center">ID</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Image</th>
                                        <th class="text-center">Create Date</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                    </tfoot>
                                    <tbody v-if="!brand">
                                        <tr>
                                            <td colspan="5">
                                                <empty message="Brand is empty"></empty>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tbody v-if="brand">
                                        <tr v-for="data in datas.data" :key="data.id">
                                            <td class="text-center align-middle">
                                                {{ data.id }}
                                            </td>
                                            <td class="text-center align-middle">
                                                {{ data.name }}
                                            </td>
                                            <td class="align-middle text-center">
                                                <img :src="data.image" class="img-fluid width-150"/>
                                            </td>
                                            <td class="text-center align-middle">
                                                {{ data.created_at | date }}
                                            </td>
                                            <td class="text-center align-middle">
                                                <button type="button" class="cta-blue-btn p-3 pr-3 pl-3" @click="editData(data)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="cta-red-btn p-3 pr-3 pl-3" @click="deleteData(data.id)">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <pagination :data="datas" @pagination-change-page="getResults"></pagination>
                        </div>
                    </div>
                    <!--Dashboard brand area end -->
                </div>
            </div>
        </div>
        <!-- Dashboard area end-->

        <!-- Modal start-->
        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-if="!editMode">Add New Brand</h5>
                        <h5 class="modal-title" v-if="editMode">Edit Brand</h5>
                    </div>
                    <form @submit.prevent="editMode ? updateData() : createData()" class="modal-body">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Brand Name</label>
                                <input v-model="form.name" type="text" id="name" class="form-control" :class="{'is-invalid': form.errors.has('name') }" required/>
                                <has-error :form="form" field="name"></has-error>
                            </div>

                            <div class="image-upload">
                                <label for="image">
                                    Select Brand Image
                                    <span class="upload-frame">
                                        <img :src="form.image" class="img-fluid max-height-200 mt-4"/>
                                    </span>
                                </label>
                                <input type="file" accept="image/*" id="image" name="image" @change="image($event)"/>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="cta-red-btn p-2 pl-3 pr-3" data-dismiss="modal">
                                Close
                            </button>
                            <button type="submit" class="cta-blue-btn p-2 pl-3 pr-3" v-if="!editMode">
                                Add Brand
                            </button>
                            <button type="submit" class="cta-blue-btn p-2 pl-3 pr-3" v-if="editMode">
                                Update Brand
                            </button>
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
        title: 'Brand',
        data() {
            return {
                form: new Form({
                    id: '',
                    name: '',
                    image: '',
                }),
                datas: {},
                brand: true,
                editMode: null,
            };
        },

        methods: {
            // Load All Brand Data
            loadBrand() {
                if (this.$middleware.admin()) {
                    this.$Progress.start();
                    axios.get('api/brand').then((response) => {
                        if (response.data.data.length <= 0) {
                            this.brand = false;
                        } else {
                            this.brand = true;
                            this.datas = response.data;
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
                    axios.get('api/brand?page=' + page).then((response) => {
                        this.datas = response.data;
                        this.$Progress.finish();
                    },
                    () => {
                        Fire.$emit('error', 'There was something Wrong.');
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

            // Instant Show Image
            image(event) {
                let file = event.target.files[0];
                let reader = new FileReader();
                reader.onloadend = () => {
                    this.form.image = reader.result;
                };
                reader.readAsDataURL(file);
            },

            // Create New Brand
            createData() {
                if (this.$middleware.admin()) {
                    this.$Progress.start();
                    this.form.post('api/brand').then(() => {
                        Fire.$emit('getBrand');
                        $('#modal').modal('hide');
                        Fire.$emit('success', 'Brand Created successfully');
                        this.$Progress.finish();
                    },
                    error => {
                        Fire.$emit('error', error.response.data.errors ? error.response.data.errors[Object.keys(error.response.data.errors)[0]][0]:'Something Wrong! Please try Again');
                        this.$Progress.fail();
                    });
                }
            },

            // Open Modal with existing Data
            editData(data) {
                this.editMode = true;
                $('#modal').modal('show');
                this.form.fill(data);
            },

            // Update Existing Brand
            updateData() {
                if (this.$middleware.admin()) {
                    this.$Progress.start();
                    this.form.post('api/brand/' + this.form.id).then(() => {
                        Fire.$emit('getBrand');
                        $('#modal').modal('hide');
                        Fire.$emit('success', 'Brand Updated successfully');
                        this.$Progress.finish();
                    },
                    error => {
                        Fire.$emit('error', error.response.data.errors ? error.response.data.errors[Object.keys(error.response.data.errors)[0]][0]:'Something Wrong! Please try Again');
                        this.$Progress.fail();
                    });
                }
            },

            // Delete Existing Brand
            deleteData(id) {
                if (this.$middleware.admin()) {
                    Swal.fire({
                        title: "Are you sure to delete the brand?",
                        text: "You won't be able to revert this!",
                        icon: "error",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, delete it!",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.$Progress.start();
                            axios.post('api/brand-delete/' + id).then(() => {
                                Swal.fire("Deleted!", "Brand has been deleted.", "success");
                                Fire.$emit('getBrand');
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

        created() {
            this.$Progress.start();
            if (this.$middleware.admin()) {
                this.loadBrand();
                this.$Progress.finish();

                Fire.$on('getBrand', () => {
                    this.loadBrand();
                });
            }else{
                this.$router.push('/login');
                this.$Progress.fail();
            }
        },
    };
</script>