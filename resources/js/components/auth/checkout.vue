<template>
    <div class="shopping-cart" v-if="$middleware.authCheck()">
        <div v-if="carts.length > 0" class="container">
            <div class="section-title">
                <div class="row">
                    <div class="col-lg-12">
                        <h3><i class="fas fa-shopping-basket"></i> Checkout</h3>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="shopping-cart-body">
                <div class="shopping-cart-info">
                    <form class="row" @submit.prevent="order">
                        <div class="col-lg-7">
                            <div class="row min-h-100">
                                <div class="col-md-12 h-100">
                                    <div class="shopping-cart-coupon">
                                        <input @input="coupon.code = $event.target.value.toUpperCase()" @click.right.prevent @copy.prevent @paste.prevent :value="coupon.code" @keydown.space.prevent type="text" @keydown.enter.prevent="applyCoupon" class="text-uppercase" :class="{'is-invalid': form.errors.has('code')}" :placeholder="form.couponUse ? 'Coupon Successfully Use':'Type Your Coupon'" :disabled="form.couponUse"/>
                                        <button type="button" @click="applyCoupon" class="cta-blue-btn" :disabled="form.couponUse">Apply Coupon <i class="far fa-check-circle" v-if="form.couponUse"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="address">
                                        <h6 class="bg-blue color-white">Shipping Address</h6>
                                        <div class="form-group mt-3">
                                            <select class="form-control form-select" v-model="form.selectAddress" style="background-image: url('/images/download.svg')" required>
                                                <option :value="null">Select a Address</option>
                                                <option v-for="data in addresses" :value="data" :key="data.id">{{ data.address_name }}</option>
                                            </select>
                                        </div>
                                        <div class="address border-none" v-if="addresses.length <= 0">
                                            <h4>Please Add Your Address</h4>
                                            <router-link to="/shipping-address" class="cta-blue-btn">Add Your Address</router-link>
                                        </div>
                                        <transition name="slide" v-if="addresses.length > 0">
                                            <div v-if="form.selectAddress !== null">
                                                <div class="address-name p-0">
                                                    <h3>{{ form.selectAddress.name }}</h3>
                                                    <p><small>Address: </small>{{form.selectAddress.address + " " + form.selectAddress.shipping_cost.town + "(" + form.selectAddress.shipping_cost.zip + ")"}}</p>
                                                </div>
                                                <div class="address-phone">
                                                    <p><small>Phone: </small>{{ form.selectAddress.phone }}</p>
                                                </div>
                                                <div class="address-email">
                                                    <p><small>Email: </small>{{ form.selectAddress.email }}</p>
                                                </div>
                                                <div class="form-group mt-2">
                                                    <label for="shippingType" class="strong">Shipping Type</label>
                                                    <select class="form-control form-select" v-model="form.shippingType" id="shippingType" style="background-image: url('/images/download.svg')" required>
                                                        <option :value="true">Normal Shipping</option>
                                                        <option :value="false">Express Shipping</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </transition>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="address">
                                        <h6 class="bg-blue color-white">Billing Address</h6>
                                        <div class="shopping-cart-personal-address mt-3">
                                            <input type="checkbox" id="billingStatus" v-model="form.billingStatus"/>
                                            <label for="billingStatus">Use My Shipping Address As A Billing Address</label>
                                        </div>
                                        <transition name="slide">
                                            <div v-if="form.billingStatus">
                                                <div class="address-name p-0">
                                                    <h3>{{ form.selectAddress.name }}</h3>
                                                    <p><small>Address:</small>{{form.selectAddress.address + " " + form.selectAddress.shipping_cost.town + "(" + form.selectAddress.shipping_cost.zip + ")"}}</p>
                                                </div>
                                                <div class="address-phone">
                                                    <p><small>Phone: </small>{{ form.selectAddress.phone }}</p>
                                                </div>
                                                <div class="address-email">
                                                    <p><small>Email: </small>{{ form.selectAddress.email }}</p>
                                                </div>
                                            </div>
                                        </transition>

                                        <transition name="slide">
                                            <div v-if="!form.billingStatus">
                                                <div class="form-group">
                                                    <label for="name">Name</label>
                                                    <input v-model="form.name" type="text" id="name" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }" required/>
                                                </div>
                                                <div class="form-group">
                                                    <label for="address">Billing Address</label>
                                                    <input v-model="form.address" type="text" id="address" class="form-control" :class="{'is-invalid': form.errors.has('address')}" required/>
                                                </div>
                                                <div class="form-group">
                                                    <label for="phone">Phone Number</label>
                                                    <input v-model="form.phone" type="text" id="phone" class="form-control" :class="{ 'is-invalid': form.errors.has('phone') }" required/>
                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Email <span class="small">(Optional)</span></label>
                                                    <input v-model="form.email" type="email" id="email" class="form-control" :class="{ 'is-invalid': form.errors.has('email')}"/>
                                                </div>
                                            </div>
                                        </transition>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="shopping-cart-ammount">
                                <h2>Cart Total</h2>
                                <ul>
                                    <li>
                                        <p>Price
                                            <span class="text-right">{{ cartPrice * rate | currency }}</span>
                                            <span class="text-right mr-1" v-html="icon"></span>
                                        </p>
                                    </li>
                                    <li>
                                        <p>Discount:
                                            <span class="text-right">{{ (form.couponUse ? '0' : cartDiscount) * rate | currency}}</span>
                                            <span class="text-right mr-1" v-html="icon"></span>
                                        </p>
                                    </li>
                                    <li>
                                        <p>Coupon:
                                            <span class="text-right">{{(form.couponUse ? form.cartCoupon.fixed_offer !== null ? form.cartCoupon.fixed_offer : ((cartPrice * form.cartCoupon.percent_offer) / 100) : '0') * rate | currency}}</span>
                                            <span class="text-right mr-1" v-html="icon"></span>
                                        </p>
                                    </li>
                                    <li>
                                        <p>Shipping Fee:
                                            <span class="text-right">{{(form.selectAddress === null ? '0' : form.shippingType == true ? form.selectAddress.shipping_cost.normal_price : form.selectAddress.shipping_cost.express_price) * rate | currency}}</span>
                                            <span class="text-right mr-1" v-html="icon"></span>
                                        </p>
                                    </li>
                                    <hr/>
                                    <li class="shopping-cart-total">
                                        <h4>Total: <span class="text-right" v-if="form.couponUse">{{((parseFloat(cartPrice) + parseFloat(form.selectAddress === null ? "0" : form.shippingType == true ? form.selectAddress.shipping_cost.normal_price : form.selectAddress.shipping_cost.express_price)) - parseInt(form.cartCoupon.fixed_offer !== null ? form.cartCoupon.fixed_offer : (cartPrice * form.cartCoupon.percent_offer) / 100)) * rate | currency}}</span>
                                            <span class="text-right" v-else>{{(parseFloat(cartTotal) + parseFloat(form.selectAddress === null ? "0" : form.shippingType == true ? form.selectAddress.shipping_cost.normal_price : form.selectAddress.shipping_cost.express_price)) * rate | currency}}</span>
                                            <span class="text-right mr-1" v-html="icon"></span>
                                        </h4>
                                    </li>
                                </ul>
                                <div class="payment-methods">
                                    <p>Select a Payment Method:</p>
                                    <ul class="mt-0">
                                        <li>
                                            <input type="radio" id="cod" name="payment-method" value="cod" @click="paymentReset" v-model="form.paymentType"/>
                                            <label for="cod">Cash On Delivery</label>
                                        </li>
                                        <li>
                                            <input type="radio" id="stripe" name="payment-method" value="stripe" @click="paymentReset" v-model="form.paymentType"/>
                                            <label for="stripe">Card Payment</label>
                                        </li>
                                        <li>
                                            <input type="radio" id="paypal" name="payment-method" value="paypal" @click="paymentReset" v-model="form.paymentType"/>
                                            <label for="paypal">Paypal</label>
                                        </li>
                                        <li>
                                            <input type="radio" id="bkash" name="payment-method" value="bkash" @click="paymentReset" v-model="form.paymentType"/>
                                            <label for="bkash">Bkash</label>
                                        </li>
                                    </ul>
                                </div>
                                <transition name="slide">
                                    <div class="stript-payment" v-if="form.paymentType == 'stripe'">
                                        <div class="row">
                                            <div class="form-group col-lg-12">
                                                <label for="card">Card Number</label>
                                                <input v-model="form.cardNumber" type="number" id="card" class="form-control" placeholder="1234 1234 1234 1234" required/>
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label for="cvc">Card CVC Number</label>
                                                <input v-model="form.cvc" type="number" id="cvc" class="form-control" placeholder="123" required/>
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label for="month">Card Expire</label>
                                                <date-picker v-model="form.expire" type="month" placeholder="YYYY/MM" id="month" valueType="format"></date-picker>
                                            </div>
                                        </div>
                                    </div>
                                </transition>
                                <hr/>
                                <div class="agreement">
                                    <input type="checkbox" id="agrement" v-model="agree" @click="agree = !agree"/>
                                    <label for="agrement">I have read and agree to the website <router-link to="/terms-condition">terms and conditions</router-link></label>
                                </div>
                                <button type="submit" :disabled="!agree">Placed To Order</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="empty-cart container text-center" v-else>
            <img src="/images/cart-empty.png" alt="Your Cart is Empty" class="img-fluid h-350">
            <h4 class="mt-3">Your Cart is Empty</h4>
        </div>
    </div>
    <div v-else>
        <error></error>
    </div>
</template>

<script>
    import DatePicker from 'vue2-datepicker';
    import 'vue2-datepicker/index.css';
    export default {
        title: 'Checkout',
        components: {DatePicker},
        data() {
            return {
                form: new Form({
                    name: '',
                    address: '',
                    phone: '',
                    email: '',
                    cardNumber: '',
                    cvc: '',
                    expire: '',
                    billingStatus: false,
                    selectAddress: null,
                    cartCoupon: {},
                    couponUse: false,
                    shippingType: true,
                    paymentType: 'cod',
                }),
                coupon: new Form({
                    code: ''
                }),
                addresses: [],
                agree: false,
                loading: true
            };
        },

        methods: {
            // Get Auth User Address
            getAddress() {
                if (this.$middleware.authCheck()) {
                    this.$Progress.start();
                    axios.get('api/shipping-address').then(response => {
                        this.addresses = response.data;
                        this.$Progress.finish();
                    },
                    () => {
                        Fire.$emit('error', 'Something Wrong! Please try Again');
                        this.$Progress.fail();
                    });
                }
            },

            // Apply Coupon
            applyCoupon(){
                this.coupon.post('/api/cart/coupon').then(response => {
                    if (Object.keys(response.data).length !== 0) {
                        if (response.data.message) {
                            this.coupon.reset();
                            Fire.$emit('info', response.data.message);
                        } else {
                            if (this.cartPrice >= response.data.target_price) {
                                this.form.cartCoupon = response.data;
                                this.form.couponUse = true;
                                this.coupon.reset();
                                Fire.$emit('success', 'Coupon Use Successfully');
                            } else {
                                Fire.$emit('info', 'You Need To Purchase More to Use This Coupon');
                            }
                        }
                    } else {
                        Fire.$emit('error', 'Please Use valid Coupon');
                    }
                },
                error => {
                    Fire.$emit('error', error.response.data.message);
                })
            },

            // Payment Option Reset
            paymentReset(){
                this.form.cardNumber = '';
                this.form.cvc = '';
                this.form.expire = '';
            },

            // Place A new Order
            order(){
                this.$Progress.start();
                this.form.post('/api/order').then(response => {
                    this.$store.dispatch('getCart');
                    this.$router.push('/checkout-success/' + response.data);
                    Fire.$emit('success', 'We Are Received Your Order Successfully');
                    this.$Progress.finish();
                },
                error => {
                    Fire.$emit('error', error.response.data.message ? error.response.data.message:'Something Wrong! Please try Again');
                    this.$Progress.fail();
                })
            }
        },

        computed: {
            // Cart Information
            carts() {
                return this.$store.state.carts;
            },

            // Cart Subtotal Price
            cartPrice() {
                return this.carts.reduce((sum, cart) => {
                    return (sum += cart.quantity * cart.price);
                }, 0);
            },

            // Cart Discount Price
            cartDiscount() {
                return this.carts.reduce((sum, cart) => {
                    return (sum += cart.quantity * cart.discount_fixed);
                }, 0);
            },

            // Cart Total Price
            cartTotal() {
                return this.carts.reduce((sum, cart) => {
                    return (sum += cart.quantity * (cart.price - cart.discount_fixed));
                }, 0);
            },

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
            if (this.$middleware.authCheck()) {
                this.getAddress();
            } else {
                Fire.$emit('info', "Please Login For Checkout");
                this.$router.push('/login');
                this.$Progress.fail();
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