<template>
    <div class="dashboard" v-if="$middleware.adminOrSeller()">
        <!-- Dashboard area start -->
        <div class="container">
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
                    <!-- Dashboard product edit area start -->
                    <div class="dashboard-body">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <router-link to="/product" class="cta-blue-btn p-2 pl-3 pr-3 float-right">
                                    <i class="fas fa-arrow-left"></i> Back
                                </router-link>
                                <h4 class="mt-2 font-weight-bold">Add New Product</h4>
                            </div>
                            <div class="card-body">
                                <form @submit.prevent="updateData()">
                                    <div class="form-group">
                                        <label>Product Name</label>
                                        <input v-model="form.name" type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }" placeholder="Type Your Product Name" required/>
                                        <has-error :form="form" field="name"></has-error>
                                    </div>

                                    <div class="form-group">
                                        <label>Product Brand</label>
                                        <select v-model="form.brand" class="form-control form-select" :class="{ 'is-invalid': form.errors.has('brand') }" @change="brandChange($event)" required>
                                            <option value="">Select A Brand</option>
                                            <option value="n">My Brand Not in List</option>
                                            <option v-for="data in brands" :value="data.id" :key="data.id">
                                                {{ data.name }}
                                            </option>
                                        </select>
                                        <has-error :form="form" field="brand"></has-error>
                                    </div>

                                    <transition name="slide">
                                        <div class="form-group" v-if="!brandType">
                                            <label>Product Brand Name</label>
                                            <input v-model="form.brandName" type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('brandName') }" placeholder="Type Your Product Brand Name" required/>
                                            <has-error :form="form" field="brandName"></has-error>
                                        </div>
                                    </transition>

                                    <div class="form-group">
                                        <label>Product Model</label>
                                        <input v-model="form.model" type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('model') }" placeholder="Type Your Product Model" required/>
                                        <has-error :form="form" field="model"></has-error>
                                    </div>

                                    <div class="form-group">
                                        <label>Product Category</label>
                                        <select v-model="form.category" class="form-control form-select" :class="{ 'is-invalid': form.errors.has('category') }" @change="categoryChange($event)" required>
                                            <option value="">Select A Category</option>
                                            <option v-for="data in categories" :value="data.id" :key="data.id">
                                                {{ data.name }}
                                            </option>
                                        </select>
                                        <has-error :form="form" field="category"></has-error>
                                    </div>

                                    <div class="form-group">
                                        <label>Product Sub Category</label>
                                        <select v-model="form.subCategory" class="form-control form-select" :class="{'is-invalid': form.errors.has('subCategory')}" :disabled="subCategoryType ? false : true" required>
                                            <option value="" selected>Select A Sub Category</option>
                                            <option v-for="data in subCategories" :value="data.id" :key="data.id">
                                                {{ data.name }}
                                            </option>
                                        </select>
                                        <div class="spinner" v-if="subCategoryLoading">
                                            <div class="bounce1"></div>
                                            <div class="bounce2"></div>
                                            <div class="bounce3"></div>
                                        </div>
                                        <has-error :form="form" field="subCategory"></has-error>
                                    </div>

                                    <div class="form-group">
                                        <label>Product Description
                                            <span class="small" title="For using image in description then first upload your image in server like Google Drive or any cloud storage and click right button in the image and click copy Image then past in the text editor">(Need Help? Hover Me)</span></label>
                                        <vue-editor v-model="form.description"></vue-editor>
                                    </div>

                                    <div class="form-group">
                                        <label>Product Price</label>
                                        <input v-model="form.price" type="number" step="0.01" class="form-control" :class="{ 'is-invalid': form.errors.has('price') }" placeholder="Set Your Product Price" required/>
                                        <has-error :form="form" field="price"></has-error>
                                    </div>

                                    <div class="form-group">
                                        <label>Product Discount
                                            <small>(Optional)</small>
                                        </label>
                                        <select class="form-control form-select" @change="discountChange($event)" required>
                                            <option value="false" :selected="discountType == false">No</option>
                                            <option value="true" :selected="discountType == true">Yes</option>
                                        </select>
                                    </div>

                                    <transition name="slide">
                                        <div class="form-group" v-if="discountType">
                                            <label>Product Discount Amount</label>
                                            <input v-model="form.discount" type="number" step="0.01" class="form-control" :class="{ 'is-invalid': form.errors.has('discount') }" placeholder="Set Your Product Discount Price" required/>
                                            <has-error :form="form" field="discount"></has-error>
                                        </div>
                                    </transition>

                                    <div class="form-group">
                                        <label>Product Stock Status</label>
                                        <select v-model="form.stock" class="form-control form-select" :class="{ 'is-invalid': form.errors.has('stock') }" required>
                                            <option value="true" :selected="form.stock">In Stock</option>
                                            <option value="false" :selected="!form.stock">Out Of Stock</option>
                                        </select>
                                        <has-error :form="form" field="stock"></has-error>
                                    </div>

                                    <div class="image-upload">
                                        <label for="image">Product Feature Image
                                            <span class="upload-frame"><img :src="form.image" class="img-fluid max-height-200 mt-4"/></span>
                                        </label>
                                        <input type="file" accept="image/*" id="image" @change="image($event)"/>
                                    </div>

                                    <div class="form-group mt-3">
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <!-- Button trigger modal -->
                                                <button type="button" class="cta-blue-btn display-block p-2" data-toggle="modal" data-target="#productColor">
                                                    Product Color
                                                </button>
                                                <!-- Button trigger modal -->

                                                <!-- Modal start-->
                                                <div class="modal fade" id="productColor" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Product Color</h5>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group" :class="key != 0 ? 'color-close' : ''" v-for="(color, key) in form.colors" v-if="form.colors.length > 0" :key="key">
                                                                    <label>Pic Your Product Color</label>
                                                                    <i class="fas fa-times" v-if="key != 0" @click="removeColor(key)"></i>
                                                                    <input v-model="color.name" type="text" class="form-control" required/>
                                                                    <input v-model="color.color" type="color" class="form-control mt-2" required/>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="cta-blue-btn p-2 pl-3 pr-3" @click="addColor">
                                                                    Add New Color Form
                                                                </button>
                                                                <button type="button" class="cta-blue-btn p-2 pl-3 pr-3" data-dismiss="modal">
                                                                    OK
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Modal end-->
                                            </div>
                                            <div class="col-lg-3">
                                                <!-- Button trigger modal -->
                                                <button type="button" class="cta-blue-btn display-block p-2" data-toggle="modal" data-target="#productVariant">
                                                    Product Variant
                                                </button>
                                                <!-- Button trigger modal -->

                                                <!-- Modal start-->
                                                <div class="modal fade" id="productVariant" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Product Variant</h5>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group" :class="key != 0 ? 'variant-close' : ''" v-for="(variant, key) in form.variants" :key="key">
                                                                    <label>Product Variant Name</label>
                                                                    <i class="fas fa-times" v-if="key != 0" @click="removeVariant(key)" ></i>
                                                                    <input v-model="variant.variant" type="text" class="form-control" placeholder="Type Your Product Variant Name" required/>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="cta-blue-btn p-2 pl-3 pr-3" @click="addVariant">
                                                                    Add New Veriant Form
                                                                </button>
                                                                <button type="button" class="cta-blue-btn p-2 pl-3 pr-3" data-dismiss="modal">
                                                                    OK
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Modal end-->
                                            </div>
                                            <div class="col-lg-3">
                                                <!-- Button trigger modal -->
                                                <button type="button" class="cta-blue-btn display-block p-2" data-toggle="modal" data-target="#productDescription">
                                                    Feature Description
                                                </button>
                                                <!-- Button trigger modal -->

                                                <!-- Modal start-->
                                                <div class="modal fade" id="productDescription" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">
                                                                    Product Feature Description
                                                                </h5>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group" :class="key != 0 ? 'description-close' : ''" v-for="(description, key) in form.descriptions" :key="key">
                                                                    <label>Product Feature Description</label>
                                                                    <i class="fas fa-times" v-if="key != 0" @click="removeDescription(key)"></i>
                                                                    <input v-model="description.feature_description" type="text" class="form-control" placeholder="Type Your Product Feature Description" required/>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="cta-blue-btn p-2 pl-3 pr-3" @click="addDescription">
                                                                    Add New Description Form
                                                                </button>
                                                                <button type="button" class="cta-blue-btn p-2 pl-3 pr-3" data-dismiss="modal">
                                                                    OK
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Modal end-->
                                            </div>
                                            <div class="col-lg-3">
                                                <!-- Button trigger modal -->
                                                <button type="button" class="cta-blue-btn display-block p-2" data-toggle="modal" data-target="#productSpecification">
                                                    Product Specification
                                                </button>
                                                <!-- Button trigger modal -->

                                                <!-- Modal start-->
                                                <div class="modal fade" id="productSpecification" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">
                                                                    Product Specification
                                                                </h5>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group" :class="key != 0 ? 'specification-close' : ''" v-for="(specification, key) in form.specification" :key="key">
                                                                    <label>Product Feature Description</label>
                                                                    <i class="fas fa-times" v-if="key != 0" @click="removeSpecification(key)"></i>
                                                                    <input v-model="specification.title" type="text" class="form-control" placeholder="Type Your Product Specification Title" required/>
                                                                    <input v-model="specification.description" type="text" class="form-control mt-1" placeholder="Type Your Product Specification Description" required/>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="cta-blue-btn p-2 pl-3 pr-3" @click="addSpecification">
                                                                    Add New Specification Form
                                                                </button>
                                                                <button type="button" class="cta-blue-btn p-2 pl-3 pr-3" data-dismiss="modal">
                                                                    OK
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Modal end-->
                                            </div>
                                            <div class="col-lg-12 mt-2">
                                                <!-- Button trigger modal -->
                                                <button type="button" class="cta-blue-btn display-block p-2" data-toggle="modal" data-target="#productImage">
                                                    Product Image
                                                </button>
                                                <!-- Button trigger modal -->

                                                <!-- Modal start-->
                                                <div class="modal fade" id="productImage" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xl">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">
                                                                    Product Image
                                                                </h5>
                                                            </div>
                                                            <div class="modal-body">
                                                                <vue-dropzone ref="myVueDropzone" id="dropzone" v-on:vdropzone-sending="sendingEvent" :options="dropzoneOptions">
                                                                    <div class="dropzone-custom-content">
                                                                        <h3 class="dropzone-custom-title">
                                                                            Drag and drop to upload content!
                                                                        </h3>
                                                                        <div class="subtitle">
                                                                            ...or click to select a file from your
                                                                            computer
                                                                        </div>
                                                                    </div>
                                                                </vue-dropzone>
                                                                <div class="product-image mt-2 active">
                                                                    <h4>Image Preview</h4>
                                                                    <div class="loading" v-if="loading">
                                                                        <div class="sk-circle">
                                                                            <div class="sk-circle1 sk-child"></div>
                                                                            <div class="sk-circle2 sk-child"></div>
                                                                            <div class="sk-circle3 sk-child"></div>
                                                                            <div class="sk-circle4 sk-child"></div>
                                                                            <div class="sk-circle5 sk-child"></div>
                                                                            <div class="sk-circle6 sk-child"></div>
                                                                            <div class="sk-circle7 sk-child"></div>
                                                                            <div class="sk-circle8 sk-child"></div>
                                                                            <div class="sk-circle9 sk-child"></div>
                                                                            <div class="sk-circle10 sk-child"></div>
                                                                            <div class="sk-circle11 sk-child"></div>
                                                                            <div class="sk-circle12 sk-child"></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row pl-3 pr-3" id="image-preview">
                                                                        <div class="col-lg-2 col-md-4 mt-2 mb-2" v-for="data in productImages" :key="data.id">
                                                                            <img :src="'/' + data.image" class="img-fluid"/>
                                                                            <div class="text-center block">
                                                                                <button type="button" class="cta-red-btn p-3 pl-3 pr-3 mt-2 mb-2" @click="deleteImage(data.id)">
                                                                                    <i class="fas fa-trash-alt"></i>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="cta-blue-btn p-2 pl-3 pr-3" data-dismiss="modal">
                                                                    OK
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Modal end-->
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Product Tag</label>
                                        <ul class="tag">
                                            <li v-for="(tag, key) in form.tags" :key="key">
                                                <div class="tag-name">
                                                    {{ tag.tag }} <span @click="removeTag(key)">x</span>
                                                </div>
                                            </li>
                                            <input type="text" placeholder="Enter a Tag" @keydown.enter.prevent="addTag" id="tag"/>
                                        </ul>
                                    </div>

                                    <div class="form-group">
                                        <div class="agrement">
                                            <p>Product Show Option:</p>
                                            <ul>
                                                <li>
                                                    <input type="radio" v-model="form.visible" value="true" id="visible"/>
                                                    <label for="visible">Show Your Product</label>
                                                </li>
                                                <li>
                                                    <input type="radio" v-model="form.visible" value="false" id="hidden"/>
                                                    <label for="hidden">Hide Your Product</label>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <button type="submit" class="cta-blue-btn p-2 pl-3 pr-3">
                                        Update Your Product
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Dashboard product edit area end -->
                </div>
            </div>
        </div>
        <!-- Dashboard area end -->
    </div>
    <div v-else>
        <error></error>
    </div>
</template>

<script>
    import {VueEditor} from 'vue2-editor';
    import vue2Dropzone from 'vue2-dropzone';
    import 'vue2-dropzone/dist/vue2Dropzone.min.css';
    export default {
        title: 'Edit Product',
        components: {
            vueDropzone: vue2Dropzone,
        },
        data() {
            return {
                form: new Form({
                    id: '',
                    name: '',
                    brand: '',
                    brandName: '',
                    model: '',
                    category: '',
                    subCategory: '',
                    price: '',
                    discount: '',
                    description: '<p>Type About Your Product</p>',
                    stock: true,
                    image: '',
                    colors: [],
                    variants: [],
                    descriptions: [],
                    specification: [],
                    tags: [],
                    visible: true,
                }),
                brands: {},
                brandType: true,
                categories: {},
                subCategories: {},
                subCategoryType: true,
                subCategoryLoading: false,
                discountType: false,
                productImages: {},
                loading: false,
                dropzoneOptions: {
                    url: window.location.origin + '/api/product/edit-image',
                    headers: {'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content},
                    thumbnailWidth: 150,
                    maxFilesize: 2,
                    maxFiles: 15,
                    dictMaxFilesExceeded: 'You Can Not Upload More Than 15 images',
                    dictDefaultMessage: '<i class="fas fa-cloud-upload-alt"></i> Drop File Here To Upload. Max File Size 2 MB. Maximum Upload 15 Images. Use Square Image For Better Output',
                    success: function (file, response) {
                    },
                    error: function (response, xhr) {
                        Fire.$emit('error', 'Have some problem to upload an image');
                    },
                    init: function () {
                        this.on('complete', function () {
                            if (this.getUploadingFiles().length == 0) {
                                this.removeAllFiles();
                            }
                            Fire.$emit('getProductEditImage');
                        });
                    },
                },
            };
        },

        methods: {
            // Load Required Parameter For The Form
            loadData(id) {
                if (this.$middleware.adminOrSeller()) {
                    this.$Progress.start();
                    axios.get('/api/product/' + id + '/edit').then(response => {
                        let product = response.data.product;
                        this.form.id = product.id;
                        this.form.name = product.name;
                        if (product.brand_id === null) {
                            this.brandType = false;
                            this.form.brand = 'n';
                            this.form.brandName = product.brand_name;
                        } else {
                            this.form.brand = product.brand_id;
                        }
                        this.form.model = product.model_name;
                        this.form.model = product.model_name;
                        this.form.category = product.category_id;
                        this.form.subCategory = product.sub_category_id;
                        this.form.price = product.price;
                        if (product.discount_fixed === null) {
                            this.discountType = false;
                        } else {
                            this.discountType = true;
                            this.form.discount = product.discount_fixed;
                        }
                        this.form.description = product.description;
                        this.form.stock = product.stock_status === 1 ? true : false;
                        this.form.image = '/' + product.feature_image;
                        this.form.visible = product.status === 1 ? true : false;
                        this.form.colors = response.data.colors;
                        this.form.variants = response.data.variants;
                        this.form.descriptions = response.data.descriptions;
                        this.form.specification = response.data.specifications;
                        this.form.tags = response.data.tags;
                        this.brands = response.data.brands;
                        this.categories = response.data.categories;
                        this.subCategories = response.data.subCategories;
                        this.$Progress.finish();
                    },
                    () => {
                        this.$Progress.fail();
                    });
                }
            },

            // Custom Brand Option
            brandChange(event) {
                this.brandType = event.target.value !== 'n';
            },

            // Dependable Dropdown For Category & Sub Category
            categoryChange(event) {
                if (event.target.value !== '') {
                    this.subCategoryLoading = true;
                    axios.post('/api/product/getSubCategory/' + event.target.value).then(response => {
                        if (response.data.length >= 0) {
                            this.subCategories = response.data;
                            this.subCategoryType = true;
                            this.subCategoryLoading = false;
                        } else {
                            this.subCategories = null;
                            this.subCategoryType = false;
                            this.subCategoryLoading = false;
                        }
                    },
                    () => {
                        this.subCategories = null;
                        this.subCategoryType = false;
                        this.subCategoryLoading = false;
                        Fire.$emit('error', 'Something Wrong! Please try Again');
                    });
                } else {
                    this.subCategories = null;
                    this.subCategoryType = false;
                }
            },

            // Discount Option
            discountChange(event) {
                if (event.target.value === 'true') {
                    this.form.discount = null;
                    this.discountType = true;
                } else {
                    this.form.discount = null;
                    this.discountType = false;
                }
            },

            // Instant showing Image
            image(event) {
                let file = event.target.files[0];
                let reader = new FileReader();
                reader.onloadend = () => {
                    this.form.image = reader.result;
                };
                reader.readAsDataURL(file);
            },

            // Showing Multiple Product Image
            sendingEvent(file, xhr, formData) {
                formData.append('id', this.$route.params.id);
            },
            imagePreview() {
                this.$Progress.start();
                axios.post('/api/product/get-edit-image/' + this.$route.params.id).then(response => {
                    this.productImages = response.data;
                    this.$Progress.finish();
                },
                () => {
                    Fire.$emit('error', 'Something Wrong! Please try Again');
                    this.$Progress.fail();
                });
            },

            // Delete Multiple Product Image
            deleteImage(id) {
                this.$Progress.start();
                this.loading = true;
                axios.post('/api/product/image-delete/' + id).then(() => {
                    Fire.$emit('getProductEditImage');
                    this.loading = false;
                },
                () => {
                    this.loading = false;
                    Fire.$emit('error', 'Something Wrong! Please try Again');
                    this.$Progress.fail();
                });
            },

            // Add Or Remove Color Option
            addColor() {
                this.form.colors.push({color: '#000000', name: 'Black'});
            },
            removeColor(key) {
                this.form.colors.splice(key, 1);
            },

            // Add Or Remove Variant Option
            addVariant() {
                this.form.variants.push({variant: ''});
            },
            removeVariant(key) {
                this.form.variants.splice(key, 1);
            },

            // Add Or Remove Description Option
            addDescription() {
                this.form.descriptions.push({feature_description: ''});
            },
            removeDescription(key) {
                this.form.descriptions.splice(key, 1);
            },

            // Add Or Remove Specification Option
            addSpecification() {
                this.form.specification.push({title: '', description: ''});
            },
            removeSpecification(key) {
                this.form.specification.splice(key, 1);
            },

            // Add Or Remove Tag Option
            addTag(event) {
                let tag = this.form.tags;
                let exists = tag.some((tag) => {
                    return event.target.value === tag.tag || event.target.value === '';
                });

                if (!exists) {
                    this.form.tags.push({tag: event.target.value});
                    document.getElementById('tag').value = '';
                } else {
                    document.getElementById('tag').value = '';
                }
            },
            removeTag(key) {
                this.form.tags.splice(key, 1);
            },

            // Update Existing Product
            updateData() {
                if (this.$middleware.adminOrSeller()) {
                    this.$Progress.start();
                    this.form.post('/api/product-update/' + this.$route.params.id).then(() => {
                        this.$store.dispatch('getCart');
                        Fire.$emit('success', 'Product Successfully Updated');
                        this.$Progress.finish();
                        this.$router.push('/product');
                    },
                    error => {
                        Fire.$emit('error', error.response.data.errors ? error.response.data.errors[Object.keys(error.response.data.errors)[0]][0]:'Something Wrong! Please try Again');
                        this.$Progress.fail();
                    });
                }
            },
        },

        created() {
            this.$Progress.start();
            if (this.$middleware.adminOrSeller()) {
                this.loadData(this.$route.params.id);
                this.imagePreview();
                this.$Progress.finish();

                Fire.$on('getProductEditImage', () => {
                    this.imagePreview();
                });
            }else{
                this.$router.push('/login');
            }
        },
    };
</script>

<style scoped>
    .slide-enter-active,
    .slide-leave-active {
        transition: 0.3s;
    }

    .slide-enter-to, .slide-leave {
        max-height: 300px;
        overflow: hidden;
    }

    .slide-enter, .slide-leave-to {
        overflow: hidden;
        max-height: 0;
    }
</style>
