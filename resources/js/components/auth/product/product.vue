<template>
    <div class="dashboard" v-if="$middleware.adminOrSeller()">
        <!--Dashboard area start -->
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
                    <!--Dashboard Product area start -->
                    <div v-if="!loading">
                        <div class="dashboard-body" v-if="store">
                            <div class="dashboard-title">
                                <router-link to="/product/create" class="cta-blue-btn p-2 pl-3 pr-3 float-right m-3 cta-mobile-btn"><i class="fas fa-plus"></i> Add New Product</router-link>
                                <h3><i class="fas fa-boxes"></i> Product</h3>
                            </div>
                            <div class="dashboard-info b-2 p-2">
                                <div class="table-responsive">
                                    <table class="table table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th class="text-center">ID</th>
                                                <th class="text-center">Image</th>
                                                <th class="text-center">Owner Info</th>
                                                <th class="text-center min-w-200">Product Info</th>
                                                <th class="text-center">Price</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th class="text-center">ID</th>
                                                <th class="text-center">Image</th>
                                                <th class="text-center">Owner Info</th>
                                                <th class="text-center">Product Info</th>
                                                <th class="text-center">Price</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </tfoot>
                                        <tbody v-if="!product">
                                            <tr>
                                                <td colspan="6">
                                                    <empty message="You have no product"></empty>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tbody v-if="product">
                                            <tr v-for="data in products.data" :key="data.id">
                                                <td class="text-center align-middle">
                                                    {{ data.id }}
                                                </td>
                                                <td class="text-center align-middle">
                                                    <router-link :to="data.slug + '/product'">
                                                        <img :src="data.feature_image" :alt="data.name" class="img-fluid width-150"/>
                                                    </router-link>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <p v-if="data.name.length < 50">
                                                        Product Name: {{ data.name }}
                                                    </p>
                                                    <p v-else>
                                                        Product Name:
                                                        {{ data.name.substring(0, 50) + "..." }}
                                                    </p>
                                                    <p>Owner Name: {{ data.user.name }}</p>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <p>Brand: {{data.brand_name == null ? data.brand.name : data.brand_name}}</p>
                                                    <p>Category: {{ data.category.name }}</p>
                                                    <p>Sub Category: {{ data.sub_category.name }}</p>
                                                    <p>Rating: {{ data.rating }} <i class="fas fa-star"></i></p>
                                                    <p>Model: {{ data.model_name }}</p>
                                                    <p>View: {{ data.view_count }} <i class="far fa-eye"></i></p>
                                                    <p>Status: <span :class="data.status == true ? 'color-green' : 'color-red'">{{data.status == true ? 'Visible' : 'Hidden'}}</span>
                                                    </p>
                                                    <p>Stock: <span :class="data.stock_status == true ? 'color-green' : 'color-red'">{{data.stock_status == true ? 'In Stock' : 'Out Of Sock'}}</span></p>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <table>
                                                        <tr v-if="!data.discount_fixed">
                                                            <td class="border-none">Price:</td>
                                                            <td class="border-none">{{ data.price }}</td>
                                                        </tr>
                                                        <tr v-if="data.discount_fixed">
                                                            <td class="border-none">Previous Price:</td>
                                                            <td class="border-none">
                                                                <del>{{ data.price }}</del>
                                                            </td>
                                                        </tr>
                                                        <tr v-if="data.discount_fixed">
                                                            <td class="border-none">Discount Price:</td>
                                                            <td class="border-none">
                                                                {{ data.discount_fixed }}
                                                            </td>
                                                        </tr>
                                                        <tr v-if="data.discount_fixed">
                                                            <td class="border-none">Current Price:</td>
                                                            <td class="border-none">
                                                                {{ data.price - data.discount_fixed }}
                                                            </td>
                                                        </tr>
                                                        <tr v-if="data.discount_fixed">
                                                            <td class="border-none">Discount Percent:</td>
                                                            <td class="border-none">
                                                                {{ data.discount_percent }}%
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <router-link class="cta-blue-btn p-3 pr-3 pl-3 mb-2" :to="'product/edit/' + data.id">
                                                        <i class="fas fa-edit"></i>
                                                    </router-link>
                                                    <button type="button" class="cta-red-btn p-3 pr-3 pl-3" @click="deleteData(data.id)">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <pagination :data="products" @pagination-change-page="getResults"></pagination>
                            </div>
                        </div>
                        <div class="dashboard-body" v-else>
                            <store-create></store-create>
                        </div>
                    </div>
                    <loading v-if="loading"></loading>
                    <!--Dashboard Product area end -->
                </div>
            </div>
        </div>
        <!--Dashboard area end -->
    </div>
    <div v-else>
        <error></error>
    </div>
</template>

<script>
    export default {
        title: 'Product',
        data() {
            return {
                form: new Form({
                    id: '',
                    name: '',
                }),
                store: null,
                products: {},
                product: true,
                loading: true,
            };
        },

        methods: {
            // Load Auth User All Product
            loadProduct() {
                if (this.$middleware.adminOrSeller()) {
                    this.$Progress.start();
                    axios.get('api/product').then(response => {
                        if (response.data.data.length <= 0) {
                            this.store = true;
                            this.product = false;
                            this.loading = false;
                        } else {
                            this.store = true;
                            this.product = true;
                            this.products = response.data;
                            this.loading = false;
                        }
                        this.$Progress.finish();
                    },
                    () => {
                        this.store = false;
                        this.loading = false;
                        this.$Progress.finish();
                    });
                }
            },
            getResults(page = 1) {
                if (this.$middleware.adminOrSeller()) {
                    this.$Progress.start();
                    axios.get('api/product?page=' + page).then(response => {
                        this.products = response.data;
                        this.$Progress.finish();
                    },
                    () => {
                        this.$Progress.fail();
                    });
                }
            },

            // Delete Existing Product
            deleteData(id) {
                if (this.$middleware.adminOrSeller()) {
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
                            axios.post('api/product-delete/' + id).then(() => {
                                    Swal.fire("Deleted!", "Category has been deleted.", "success");
                                    Fire.$emit('getProduct');
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
            if (this.$middleware.authCheck()) {
                this.loadProduct();
                this.$Progress.finish();

                Fire.$on('getProduct', () => {
                    this.loadProduct();
                });
            }else{
                this.$router.push('/login');
                this.$Progress.fail()
            }
        }
    };
</script>