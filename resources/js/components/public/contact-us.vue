<template>
    <!-- Contact us area start-->
    <div class="contact-us">
        <div class="container" v-if="!loading">
            <div class="section-title">
                <h3><i class="fas fa-users"></i> Contact Us</h3>
            </div>
            <hr>
            <div class="mt-40"></div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="contact-address" v-for="(info, key) in info" :key="key" v-html="info"></div>
                </div>
                <div class="col-lg-6">
                    <div class="contact-us-form">
                        <h3>Leave us a Message</h3>
                        <form action="#">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name">
                            </div>
                            <div class="form-group">
                                <label for="email">Email address</label>
                                <input type="email" class="form-control" id="email">
                            </div>
                            <div class="form-group">
                                <label for="subject">Subject</label>
                                <input type="text" class="form-control" id="subject">
                            </div>
                            <div class="form-group">
                                <label for="message">Your Message</label>
                                <textarea class="form-control" id="message" rows="4"></textarea>
                            </div>
                            <button type="submit" class="cta-blue-btn">Send</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Map area start-->
            <div class="map" v-html="map"></div>
            <!-- Map area end-->
        </div>
        <loading v-else></loading>
    </div>
    <!-- Contact us area end-->

</template>

<script>
    export default {
        title: 'Contact Us',
        data(){
            return {
                map:'',
                info:[],
                loading: true,
            }
        },

        methods: {
            // Load contract Us Data
            loadContactUs(){
                this.$Progress.start();
                axios.get('/api/contact-us').then(response=>{
                    this.map = response.data.map;
                    this.info = JSON.parse(response.data.info);
                    this.loading = false;
                    this.$Progress.finish();
                },
                ()=>{
                    Fire.$emit('error', 'Something Wrong! Please try Again')
                    this.$Progress.fail();
                });
            },
        },

        created(){
            this.loadContactUs();
        }
    }
</script>