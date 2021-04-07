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
                    <!--Dashboard category area start-->
                    <div class="dashboard-body">
                        <div class="dashboard-title">
                            <button type="button" class="cta-blue-btn p-2 pl-3 pr-3 float-right m-3 cta-mobile-btn" @click="newModal">
                                <i class="fas fa-plus"></i> Add New Category
                            </button>
                            <h3><i class="fas fa-th-large"></i> Category</h3>
                        </div>
                        <div class="dashboard-info b-2 p-2">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" >
                                    <thead>
                                    <tr>
                                        <th class="text-center">ID</th>
                                        <th class="text-center">Category Name</th>
                                        <th class="text-center">Sub Category Name</th>
                                        <th class="text-center">Create Date</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th class="text-center">ID</th>
                                        <th class="text-center">Category Name</th>
                                        <th class="text-center">Sub Category Name</th>
                                        <th class="text-center">Create Date</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                    </tfoot>
                                    <tbody v-if="!category">
                                    <tr>
                                        <td colspan="5">
                                            <empty message="Category is empty"></empty>
                                        </td>
                                    </tr>
                                    </tbody>
                                    <tbody v-if="category">
                                        <tr v-for="data in datas.data" :key="data.id">
                                        <td class="text-center align-middle">
                                            {{ data.id }}
                                        </td>
                                        <td class="text-center align-middle">
                                            {{ data.name }}
                                        </td>
                                        <td class="text-center align-middle">
                                            <p v-for="subCategory in data.sub_categories">
                                                {{ subCategory.name }}
                                            </p>
                                        </td>
                                        <td class="text-center align-middle">
                                            {{ data.created_at | date }}
                                        </td>
                                        <td class="text-center align-middle">
                                            <button type="button" class="cta-blue-btn p-2 pr-3 pl-3 mb-2" @click="editData(data)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="cta-red-btn p-2 pr-3 pl-3" @click="deleteData(data.id)">
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
                    <!--Dashboard category area end-->
                </div>
            </div>
        </div>
        <!--Dashboard area end -->

        <!-- Modal start -->
        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-if="!editMode">Add New Category</h5>
                        <h5 class="modal-title" v-if="editMode">Edit Category</h5>
                    </div>
                    <form @submit.prevent="editMode ? updateData() : createData()" class="modal-body">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Category Name</label>
                                <input v-model="form.name" type="text" id="name" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }" required/>
                                <has-error :form="form" field="name"></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="cta-red-btn p-2 pl-3 pr-3" data-dismiss="modal">
                                Close
                            </button>
                            <button type="submit" class="cta-blue-btn p-2 pl-3 pr-3" v-if="!editMode">
                                Add Category
                            </button>
                            <button type="submit" class="cta-blue-btn p-2 pl-3 pr-3" v-if="editMode">
                                Update Category
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
        title: 'Category',
        data() {
            return {
                form: new Form({
                    id: '',
                    name: '',
                }),
                datas: {},
                category: true,
                editMode: null,
            };
        },

        methods: {
            // Load All Categories
            loadCategory() {
                if (this.$middleware.admin()) {
                    this.$Progress.start();
                    axios.get('api/category').then(response => {
                        if (response.data.data.length <= 0) {
                            this.category = false;
                        } else {
                            this.category = true;
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
                    axios.get('api/category?page=' + page).then(response => {
                        this.datas = response.data;
                        this.$Progress.finish();
                    },
                    () => {
                        Fire.$emit('error', 'There was something Wrong.');
                        this.$Progress.fail();
                    });
                }
            },

            // Load New Fresh Modal
            newModal() {
                this.editMode = false;
                this.form.reset();
                $('#modal').modal('show');
            },

            // Create New Category
            createData() {
                if (this.$middleware.admin()) {
                    this.$Progress.start();
                    this.form.post('api/category').then(() => {
                        Fire.$emit('getCategory');
                        $('#modal').modal('hide');
                        Fire.$emit('success', 'Category Created successfully');
                        this.$Progress.finish();
                    },
                    error=> {
                        Fire.$emit('error', error.response.data.errors ? error.response.data.errors[Object.keys(error.response.data.errors)[0]][0]:'Something Wrong! Please try Again');
                        this.$Progress.fail();
                    });
                }
            },

            // Open Modal Existing Category
            editData(data) {
                this.editMode = true;
                $('#modal').modal('show');
                this.form.fill(data);
            },

            // Update Existing Category
            updateData() {
                if (this.$middleware.admin()) {
                    this.$Progress.start();
                    this.form.post('api/category/' + this.form.id).then(() => {
                        Fire.$emit('getCategory');
                        $('#modal').modal('hide');
                        Fire.$emit('success', 'Category Updated successfully');
                        this.$Progress.finish();
                    },
                    error => {
                        Fire.$emit('error', error.response.data.errors ? error.response.data.errors[Object.keys(error.response.data.errors)[0]][0]:'Something Wrong! Please try Again');
                        this.$Progress.fail();
                    });
                }
            },

            // Delete Category
            deleteData(id) {
                if (this.$middleware.admin()) {
                    Swal.fire({
                        title: "Are you sure to delete the Category?",
                        text: "You won't be able to revert this!",
                        icon: "error",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, delete it!",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.$Progress.start();
                            axios.post('api/category-delete/' + id).then(() => {
                                Swal.fire("Deleted!", "Category has been deleted.", "success");
                                Fire.$emit('getCategory');
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
                this.loadCategory();
                this.$Progress.finish();

                Fire.$on('getCategory', () => {
                    this.loadCategory();
                });

            }else{
                this.$router.push('/login');
                this.$Progress.fail();
            }
        },
    };
</script>